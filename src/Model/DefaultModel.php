<?php
namespace App\Model;

use App\Service\Config;
use PDO;

abstract class DefaultModel
{
    protected ?int $id = null;

    // Nazwa tabeli i pola muszą być zdefiniowane w klasie potomnej
    protected static string $table;
    protected static array $fields = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public static function db(): PDO
    {
        return new PDO(
            Config::get('db_dsn'),
            Config::get('db_user'),
            Config::get('db_pass')
        );
    }

    public static function fromArray(array $data): self
    {
        $obj = new static();
        $obj->fill($data);
        return $obj;
    }

    public function fill(array $data): self
    {
        if (isset($data['id']) && ! $this->getId()) {
            $this->setId((int)$data['id']);
        }

        foreach (static::$fields as $field) {
            if (array_key_exists($field, $data)) {
                $setter = 'set' . ucfirst($field);
                if (method_exists($this, $setter)) {
                    $this->$setter($data[$field]);
                }
            }
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = static::db();
        $sql = 'SELECT * FROM ' . static::$table;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => static::fromArray($r), $rows);
    }

    public static function find($id): ?static
    {
        $pdo = static::db();
        $sql = 'SELECT * FROM ' . static::$table . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? static::fromArray($row) : null;
    }

    public function save(): void
    {
        $pdo = static::db();

        $data = [];
        foreach (static::$fields as $field) {
            $getter = 'get' . ucfirst($field);
            if (method_exists($this, $getter)) {
                $data[$field] = $this->$getter();
            }
        }

        if (! $this->getId()) {
            $columns = implode(', ', static::$fields);
            $placeholders = implode(', ', array_map(fn($f) => ':' . $f, static::$fields));

            $sql = 'INSERT INTO ' . static::$table .
                " ($columns) VALUES ($placeholders)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);

            $this->setId((int)$pdo->lastInsertId());
        } else {
            $assignments = implode(', ', array_map(fn($f) => "$f = :$f", static::$fields));

            $sql = 'UPDATE ' . static::$table .
                " SET $assignments WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge($data, ['id' => $this->getId()]));
        }
    }

    public function delete(): void
    {
        if (! $this->getId()) {
            return;
        }

        $pdo = static::db();
        $sql = 'DELETE FROM ' . static::$table . ' WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $this->getId()]);

        $this->setId(null);
    }
}
