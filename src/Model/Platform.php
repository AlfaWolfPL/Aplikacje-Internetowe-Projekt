<?php

namespace App\Model;

use PDO;

class Platform extends DefaultModel
{
    protected static string $table = 'platforms';
    protected static array $fields = ['name'];

    protected string $name;
    protected array $titles = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getTitles(): array
    {
        return $this->titles;
    }

    public static function find($id, $loadRelations = false): ?static
    {
        /**
         * @var Platform $platform
         */
        $platform = parent::find($id);
        if (!empty($platform) && $loadRelations) {
            $titles = Title::findByPlatform($platform->getId());
            $platform->setTitles($titles);
        }
        return $platform;
    }

    public static function findAll($loadRelations = false): array
    {
        $platforms = parent::findAll();

        if ($loadRelations) {
            foreach ($platforms as $platform) {
                $titles = Title::findByPlatform($platform->getId());
                $platform->setTitles($titles);
            }
        }

        return $platforms;
    }

    public function setTitles(array $titles): self
    {
        $this->titles = $titles;
        return $this;
    }

    public static function findByTitle(int $titleId): array
    {
        $pdo = static::db();
        $sql = <<<SQL
SELECT p.* FROM title_platforms tp
JOIN platforms p ON p.id = tp.platform_id
WHERE title_id = :id
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $titleId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => static::fromArray($r), $rows);
    }

    public static function findByName(string $name): ?Platform
    {
        $pdo = static::db();
        $sql = <<<SQL
SELECT * FROM platforms WHERE name = :name LIMIT 1;
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return Platform::fromArray($row);
        }
        return null;
    }
}
