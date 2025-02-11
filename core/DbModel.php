<?php

namespace app\core;

use app\core\facades\DB;
use PDO;

abstract class DbModel
{
    abstract public static function tableName(): string;
    abstract public static function attributes(): array;
    abstract public static function primaryKey(): string;

    protected static ?PDO $pdo = null;


    public function save()
    {
        $table = static::tableName();
        $attributes = static::attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $sql = "INSERT INTO $table (" . implode(",", $attributes) . ") VALUES (" . implode(",", $params) . ")";

        $statement = self::prepare($sql);
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        return $statement->execute();
    }

    public static function findOne(array $where): ?static
    {
        $table = static::tableName();
        $columns = array_keys($where);
        $conditions = implode(" AND ", array_map(fn($col) => "$col = :$col", $columns));
        $sql = "SELECT * FROM $table WHERE $conditions LIMIT 1";

        $statement = self::prepare($sql);
        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        $statement->execute();
        return $statement->fetchObject(static::class) ?: null;
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::class);
    }

    protected static function prepare(string $sql)
    {
        return DB::prepare($sql);
    }
    public static function create(array $data)
    {
        $table = static::tableName();
        $attributes = static::attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $sql = "INSERT INTO $table (" . implode(",", $attributes) . ") VALUES (" . implode(",", $params) . ")";

        $statement = self::prepare($sql);
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $data[$attribute]);
        }
        return $statement->execute();
    }
}