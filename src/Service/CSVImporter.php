<?php
namespace App\Service;
use App\Model\Title;
use PDO;


class CSVImporter
{
    public function getOrCreateEntityId(PDO $pdo, string $table, string $name): int
    {
        $name = trim($name);
        if (empty($name)) {
            return 0;
        }

        $sql = <<<SQL
        SELECT id FROM $table WHERE name = :name
        SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        $id = $stmt->fetchColumn();

        if ($id) {
            return (int)$id;
        }

        $sql = <<<SQL
        SELECT MAX(id) FROM $table
        SQL;

        $stmtMax = $pdo->query($sql);
        $nextId = ((int)$stmtMax->fetchColumn()) + 1;

        $stmtInsert = $pdo->prepare("INSERT INTO $table (id, name) VALUES (:id, :name)");
        $stmtInsert->execute(['id' => $nextId, 'name' => $name]);

        return $nextId;
    }

    public function runSingleCsvImport(string $filePath): array
    {
        if (!file_exists($filePath) || pathinfo($filePath, PATHINFO_EXTENSION) !== 'csv') {
            return ['status' => 'error', 'message' => 'Nieprawidłowy plik CSV'];
        }

        $pdo = Title::db();
        $num_titles = 0;
        $num_titles_skipped = 0;

        if (($handle = fopen($filePath, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ";", '"', '\\');
            // Oczekiwany: title;description;kind;year;categories;platforms

            if (!$header || count($header) < 6) {
                fclose($handle);
                return ['status' => 'error', 'message' => 'Nieprawidłowy format CSV'];
            }

            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                $titleName = trim($data[0] ?? '');
                if (empty($titleName)) {
                    continue;
                }

                $sql = "SELECT id FROM titles WHERE title = :title";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['title' => $titleName]);
                if ($stmt->fetchColumn()) {
                    $num_titles_skipped++;
                    continue;
                }

                try {
                    $pdo->beginTransaction();

                    $sql = "INSERT INTO titles (title, description, kind, year) VALUES (:title, :desc, :kind, :year)";
                    $stmtInsert = $pdo->prepare($sql);
                    $stmtInsert->execute([
                        'title' => $titleName,
                        'desc' => $data[1] ?? '',
                        'kind' => $data[2] ?? 'movie',
                        'year' => (int)($data[3] ?? 0)
                    ]);
                    $newTitleId = (int)$pdo->lastInsertId();

                    $cats = array_map('trim', explode(',', $data[4] ?? ''));
                    foreach ($cats as $catName) {
                        if (!empty($catName)) {
                            $catId = $this->getOrCreateEntityId($pdo, 'categories', $catName);
                            if ($catId > 0) {
                                $sql = "INSERT INTO title_categories (title_id, category_id) VALUES (:tid, :cid)";
                                $pdo->prepare($sql)->execute(['tid' => $newTitleId, 'cid' => $catId]);
                            }
                        }
                    }

                    $plats = array_map('trim', explode(',', $data[5] ?? ''));
                    foreach ($plats as $platName) {
                        if (!empty($platName)) {
                            $platId = $this->getOrCreateEntityId($pdo, 'platforms', $platName);
                            if ($platId > 0) {
                                $sql = "INSERT INTO title_platforms (title_id, platform_id) VALUES (:tid, :pid)";
                                $pdo->prepare($sql)->execute(['tid' => $newTitleId, 'pid' => $platId]);
                            }
                        }
                    }

                    $pdo->commit();
                    $num_titles++;
                } catch (Exception $e) {
                    $pdo->rollBack();
                    return ['status' => 'error', 'message' => 'Błąd importu: ' . $e->getMessage()];
                }
            }
            fclose($handle);
        }

        return [
            'status' => 'success',
            'message' => "Dodano: $num_titles, pominięto duplikatów: $num_titles_skipped",
            'added' => $num_titles,
            'skipped' => $num_titles_skipped
        ];
    }
}