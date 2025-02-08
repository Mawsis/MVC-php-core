<?php

namespace app\core;

use PDO;

class QueryBuilder
{
    private string $table;
    private array $columns = ['*'];
    private array $where = [];
    private array $bindings = [];
    private string $orderBy = '';
    private int $limit = 0;
    private bool $isClass = true;
    private ?string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->table = (new $modelClass) instanceof DbModel ? $modelClass::tableName() : $modelClass;
        $this->isClass = (new $modelClass) instanceof DbModel ? true : false;
        $this->modelClass = $modelClass;
    }

    public function select(array $columns = ['*']): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $param = ":w" . count($this->bindings);
        $this->where[] = "$column $operator $param";
        $this->bindings[$param] = $value;
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

    public function get(): array
    {
        $sql = "SELECT " . implode(", ", $this->columns) . " FROM $this->table";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        if ($this->orderBy) {
            $sql .= " $this->orderBy";
        }
        if ($this->limit > 0) {
            $sql .= " LIMIT $this->limit";
        }

        $statement = Application::$app->db->prepare($sql);
        foreach ($this->bindings as $param => $value) {
            $statement->bindValue($param, $value);
        }
        $statement->execute();
        if ($this->isClass) {
            return $statement->fetchAll(PDO::FETCH_CLASS, $this->modelClass);
        } else {
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function first(): ?object
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }
}