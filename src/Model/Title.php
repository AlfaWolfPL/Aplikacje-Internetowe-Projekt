<?php

namespace App\Model;

class Title extends DefaultModel
{
    protected static string $table = 'titles';
    protected static array $fields = ['title', 'description', 'kind', 'year'];
    private string $title;
    private string $description;

    private string $kind;
    private ?int $year = null;
    private array $platforms = [];
    private array $categories = [];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getKind(): string
    {
        return $this->kind;
    }

    public function setKind(string $kind): self
    {
        $this->kind = $kind;
        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function getPlatforms(): array
    {
        return $this->platforms;
    }

    public function setPlatforms(array $platforms): self
    {
        $this->platforms = $platforms;
        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;
        return $this;
    }

    public static function find($id, $loadRelations = false): ?static
    {
        /**
         * @var Title $title
         */
        $title = parent::find($id);
        if (!empty($title) && $loadRelations) {
            $categories = Category::findByTitle($title->getId());
            $title->setCategories($categories);
            $platforms = Platform::findByTitle($title->getId());
            $title->setPlatforms($platforms);
        }
        return $title;
    }

    public static function findAll($loadRelations = false): array
    {
        $titles = parent::findAll();

        if ($loadRelations) {
            foreach ($titles as $title) {
                /**
                 * @var Title $title
                 */
                $categories = Category::findByTitle($title->getId());
                $title->setCategories($categories);
                $platforms = Platform::findByTitle($title->getId());
                $title->setPlatforms($platforms);
            }
        }
        return $titles;
    }

    public static function findByCategory(int $categoryId): array
    {
        $pdo = static::db();
        $sql = <<<SQL
SELECT t.* FROM title_categories tc
JOIN titles t ON t.id = tc.title_id
WHERE category_id = :id
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $categoryId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => static::fromArray($r), $rows);
    }

    public static function findByPlatform(int $platformId): array
    {
        $pdo = static::db();
        $sql = <<<SQL
SELECT t.* FROM title_platforms tp
JOIN titles t ON t.id = tp.title_id
WHERE platform_id = :id
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $platformId]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return array_map(fn($r) => static::fromArray($r), $rows);
    }

    public static function removePlatformRelationship(int $id, int $platformId): void
    {
        $pdo = static::db();
        $sql = <<<SQL
DELETE FROM title_platforms WHERE title_id = :title_id AND platform_id = :platform_id
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title_id' => $id, 'platform_id' => $platformId]);
    }

    public static function addPlatformRelationship(int $id, int $platformId): void
    {
        $pdo = static::db();
        $sql = <<<SQL
INSERT INTO title_platforms (title_id, platform_id)
VALUES (:title_id, :platform_id)
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title_id' => $id, 'platform_id' => $platformId]);
    }

    public static function removeCategoryRelationship(int $id, int $categoryId): void
    {
        $pdo = static::db();
        $sql = <<<SQL
DELETE FROM title_categories WHERE title_id = :title_id AND category_id = :category_id
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title_id' => $id, 'category_id' => $categoryId]);
    }

    public static function addCategoryRelationship(int $id, int $categoryId): void
    {
        $pdo = static::db();
        $sql = <<<SQL
INSERT INTO title_categories (title_id, category_id)
VALUES (:title_id, :category_id)
SQL;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['title_id' => $id, 'category_id' => $categoryId]);
    }

    public static function search(string $query = '', ?int $categoryId = null, ?int $platformId = null): array
    {
        $pdo = static::db();

        $sql = "SELECT t.* FROM titles t";
        $params = [];
        $conditions = [];

        if ($categoryId) {
            $sql .= " JOIN title_categories tc ON t.id = tc.title_id";
            $conditions[] = "tc.category_id = :catId";
            $params['catId'] = $categoryId;
        }

        if ($platformId) {
            $sql .= " JOIN title_platforms tp ON t.id = tp.title_id";
            $conditions[] = "tp.platform_id = :platId";
            $params['platId'] = $platformId;
        }

        if (!empty($query)) {
            $conditions[] = "(t.title LIKE :query OR t.description LIKE :query)";
            $params['query'] = '%' . $query . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " GROUP BY t.id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $titles = array_map(fn($r) => static::fromArray($r), $rows);

        foreach ($titles as $title) {
            $title->setCategories(Category::findByTitle($title->getId()));
            $title->setPlatforms(Platform::findByTitle($title->getId()));
        }

        return $titles;
    }
}
