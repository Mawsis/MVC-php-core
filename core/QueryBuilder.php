<?php

namespace app\core;

use app\core\facades\DB;
use PDO;

class QueryBuilder
{
    private string $table;
    private array $columns = ['*'];
    private array $where = [];
    private array $joins = [];
    private array $bindings = [];
    private array $relations = [];
    private string $groupBy = '';
    private string $having = '';
    private string $orderBy = '';
    private int $limit = 0;
    private int $offset = 0;
    private ?string $modelClass;
    private bool $isClass = true;

    public function __construct(string $modelClass)
    {
        $this->table = str_contains($modelClass, "app\\models") ?
            $modelClass::tableName() :
            $modelClass;
        $this->isClass = str_contains($modelClass, "app\\models");
        $this->modelClass = $modelClass;
    }

    public function select(array $columns = ['*']): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value, string $boolean = 'AND'): self
    {
        $param = ":w" . count($this->bindings);
        $this->where[] = [$boolean, "$column $operator $param"];
        $this->bindings[$param] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, mixed $value): self
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function groupBy(string $column): self
    {
        $this->groupBy = "GROUP BY $column";
        return $this;
    }

    public function having(string $condition): self
    {
        $this->having = "HAVING $condition";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }
    public function with(string $relation)
    {
        $this->relations[] = $relation;
        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT " . implode(", ", $this->columns) . " FROM $this->table";
        if ($this->joins) {
            $sql .= " " . implode(" ", $this->joins);
        }
        if ($this->where) {
            $whereClauses = array_map(fn($w) => "{$w[0]} {$w[1]}", $this->where);
            $sql .= " WHERE " . ltrim(implode(" ", $whereClauses), 'ANDOR');
        }
        if ($this->groupBy) {
            $sql .= " $this->groupBy";
        }
        if ($this->having) {
            $sql .= " $this->having";
        }
        if ($this->orderBy) {
            $sql .= " $this->orderBy";
        }
        if ($this->limit > 0) {
            $sql .= " LIMIT $this->limit";
        }
        if ($this->offset > 0) {
            $sql .= " OFFSET $this->offset";
        }

        $statement = DB::prepare($sql);
        foreach ($this->bindings as $param => $value) {
            $statement->bindValue($param, $value);
        }
        $statement->execute();
        $results = $this->isClass ? $statement->fetchAll(PDO::FETCH_CLASS, $this->modelClass) : $statement->fetchAll(PDO::FETCH_OBJ);
        foreach ($results as $result) {
            foreach ($this->relations as $relation) {
                if (method_exists($result, $relation)) {
                    $result->$relation = $result->$relation();
                }
            }
        }
        return $results;
    }

    public function first(): ?object
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function insert(array $data): bool
    {
        $columns = array_keys($data);
        $params = array_map(fn($col) => ":$col", $columns);
        $sql = "INSERT INTO $this->table (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $params) . ")";

        $statement = DB::prepare($sql);
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        return $statement->execute();
    }

    public function update(array $data): bool
    {
        $columns = array_keys($data);
        $updates = implode(", ", array_map(fn($col) => "$col = :$col", $columns));
        $sql = "UPDATE $this->table SET $updates";

        if ($this->where) {
            $whereClauses = array_map(fn($w) => "{$w[0]} {$w[1]}", $this->where);
            $sql .= " WHERE " . ltrim(implode(" ", $whereClauses), 'ANDOR');
        }

        $statement = DB::prepare($sql);
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        return $statement->execute();
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM $this->table";
        if ($this->where) {
            $whereClauses = array_map(fn($w) => "{$w[0]} {$w[1]}", $this->where);
            $sql .= " WHERE " . ltrim(implode(" ", $whereClauses), 'ANDOR');
        }

        $statement = DB::prepare($sql);
        return $statement->execute();
    }
}