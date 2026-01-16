<?php

namespace App\Model;

use PDO;

class Category extends DefaultModel
{
    protected static string $table = 'categories';
    protected static array $fields = ['name'];

    protected string $name;

    protected array $titles = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Category
    {
        $this->name = $name;
        return $this;
    }

    public function getTitles(): array
    {
        return $this->titles;
    }

    public function setTitles(array $titles): self
    {
        $this->titles = $titles;
        return $this;
    }

    public static function find($id, $loadRelations = false): ?static
    {
        /**
         * @var Category $category
         */
        $category = parent::find($id);
        if (!empty($category) && $loadRelations) {
            $titles = Title::findByCategory($category->getId());
            $category->setTitles($titles);
        }
        return $category;
    }

    public static function findAll($loadRelations = false): array
    {
        $categories = parent::findAll();

        if ($loadRelations) {
            foreach ($categories as $category) {
                $titles = Title::findByCategory($category->getId());
                $category->setTitles($titles);
            }
        }

        return $categories;
    }

    public static function findByName(string $name): ?Category
    {
        $pdo = static::db();
        $sql = <<<SQL
SELECT * FROM categories WHERE name = :name LIMIT 1;
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return Category::fromArray($row);
        }
        return null;
    }

    public static function findByTitle(int $titleId): array
    {
        $pdo = static::db();
        $sql = <<<SQL
SELECT c.* FROM title_categories tc
JOIN categories c ON c.id = tc.category_id
WHERE title_id = :id
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $titleId]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => static::fromArray($r), $rows);
    }
}