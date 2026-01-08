<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR. DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'Title.php';
use App\Model\Title;

function getOrCreateEntityId(PDO $pdo, string $table, string $name): int {
    $name = trim($name);
    if (empty($name)) return 0;

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

function runCsvImport() {
    $csvDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'csv';

    if (!is_dir($csvDir)) {
        echo "Nie znaleziono katalogu z plikami csv";
        return;
    }

    $files = glob($csvDir . '/*.csv');
    if (empty($files)) {
        echo "Katalog z plikami csv jest pusty";
        return;
    }

    $pdo = Title::db();
    foreach ($files as $filePath) {
        $num_titles = 0;
        $num_titles_skipped = 0;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ";");
            // Oczekiwany: title;description;kind;year;categories;platforms

            if (!$header || count($header) < 6) {

                fclose($handle);
                continue;
            }

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $titleName = trim($data[0]);
                if (empty($titleName)) {
                    continue;
                }

                $sql = <<<SQL
                    SELECT id FROM titles WHERE title = :title
                    SQL;

                $stmt = $pdo->prepare($sql);
                $stmt->execute(['title' => $titleName]);
                $existingId = $stmt->fetchColumn();

                if ($existingId) {
                    echo "Film o tytule \"" . $titleName . "\" już istnieje, pomijam.\n";
                    $num_titles_skipped++;
                    continue;
                }

                $sql = <<<SQL
                    SELECT MAX(id) FROM titles
                    SQL;

                $stmtMax = $pdo->query($sql);
                $newTitleId = ((int)$stmtMax->fetchColumn()) + 1;

                try {
                    $pdo->beginTransaction();
                    $sql = <<<SQL
                        INSERT INTO titles (id, title, description, kind, year) VALUES (:id, :title, :desc, :kind, :year)
                        SQL;

                    $stmtInsert = $pdo->prepare($sql);
                    $stmtInsert->execute([
                        'id' => $newTitleId,
                        'title' => $titleName,
                        'desc' => $data[1] ?? '',
                        'kind' => $data[2] ?? 'movie',
                        'year' => (int)($data[3] ?? 0)
                    ]);

                    $cats = explode(',', $data[4] ?? '');
                    foreach ($cats as $catName) {
                        $catId = getOrCreateEntityId($pdo, 'categories', $catName);
                        if ($catId > 0) {
                            $sql = <<<SQL
                                INSERT INTO title_categories (title_id, category_id) VALUES (:tid, :cid)
                                SQL;

                            $stmtRel = $pdo->prepare($sql);
                            $stmtRel->execute(['tid' => $newTitleId, 'cid' => $catId]);
                        }
                    }

                    $plats = explode(',', $data[5] ?? '');
                    foreach ($plats as $platName) {
                        $platId = getOrCreateEntityId($pdo, 'platforms', $platName);
                        if ($platId > 0) {
                            $sql = <<<SQL
                                INSERT INTO title_platforms (title_id, platform_id) VALUES (:tid, :pid)
                                SQL;

                            $stmtRel = $pdo->prepare($sql);
                            $stmtRel->execute(['tid' => $newTitleId, 'pid' => $platId]);
                        }
                    }

                    $pdo->commit();
                    $num_titles++;
                } catch (Exception $e) {
                    echo "Wystąpił bład podczas importu: \n";
                    echo $e->getMessage();
                    $pdo->rollBack();
                }
            }
            echo "Pomyślnie wykonano import: \n\tDodane rekordy: " . $num_titles . "\n\tPominięte z powodu duplikatu: "  . $num_titles_skipped . "\n";
            fclose($handle);
        }
    }
}
echo "Uruchamiam import filmów z pliku csv\n";
runCsvImport();
