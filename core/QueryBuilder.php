<?php

namespace app\core;

use app\core\facades\DB;
use PDO;

class QueryBuilder
{
    private string $table;
    private array $columns = ['*'];
    private array $where = [];
    private array $bindings = [];
    private string $orderBy = '';
    private int $limit = 0;
    private int $offset = 0;
    private bool $isClass = true;
    private ?string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->table = str_contains($modelClass, "app\models") ?
            $modelClass::tableName() :
            $modelClass;
        $this->isClass = str_contains($modelClass, "app\models") ? true : false;
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

    public function offset(int $offset): self
    {
        $this->offset = $offset;
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
        if ($this->offset > 0) {
            $sql .= " OFFSET $this->offset";
        }

        $statement = DB::prepare($sql);
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
    public function paginate(int $perPage = 10, int $page = 1): array
    {
        $offset = ($page - 1) * $perPage;
        $this->limit = $perPage;
        $this->offset = $offset;

        $sql = "SELECT " . implode(", ", $this->columns) . " FROM $this->table";
        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(" AND ", $this->where);
        }
        if ($this->orderBy) {
            $sql .= " $this->orderBy";
        }
        $sql .= " LIMIT $this->limit OFFSET $this->offset";

        $statement = DB::prepare($sql);
        foreach ($this->bindings as $param => $value) {
            $statement->bindValue($param, $value);
        }
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_CLASS, $this->modelClass);

        // Get total records count
        $countSql = "SELECT COUNT(*) as total FROM $this->table";
        if (!empty($this->where)) {
            $countSql .= " WHERE " . implode(" AND ", $this->where);
        }
        $countStmt = DB::prepare($countSql);
        foreach ($this->bindings as $param => $value) {
            $countStmt->bindValue($param, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetchColumn();
        return [
            'data' => $data,
            'pagination' => [
                'total' => (int) $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'url' => $this->removePageParam($_SERVER['REQUEST_URI'])
            ],
        ];
    }
    private function removePageParam(string $url): string
    {
        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['query'])) {
            return $url; // No query string, return as is
        }

        parse_str($parsedUrl['query'], $queryParams);
        unset($queryParams['page']); // Remove "page" from query parameters

        $newQueryString = http_build_query($queryParams);
        $cleanUrl = $parsedUrl['path'] . ($newQueryString ? '?' . $newQueryString : '');

        return $cleanUrl;
    }
}