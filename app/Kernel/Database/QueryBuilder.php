<?php
declare(strict_types=1);

namespace App\Kernel\Database;

use InvalidArgumentException;

final class QueryBuilder
{
    private string $table;
    private array $columns = ['*'];
    private array $joins = [];
    private array $wheres = [];
    private array $bindings = [];
    private array $orders = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private int $paramCounter = 0;

    public function __construct(string $table)
    {
        $this->assertIdentifier($table);
        $this->table = $table;
    }

    public function select(array|string $columns): self
    {
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }

        if ($columns === []) {
            $columns = ['*'];
        }

        foreach ($columns as $column) {
            if ($column !== '*') {
                $this->assertIdentifier($column);
            }
        }

        $this->columns = $columns;
        return $this;
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self
    {
        $this->assertIdentifier($table);
        $this->assertIdentifier($first);
        $this->assertIdentifier($second);

        $operator = strtoupper(trim($operator));
        $allowedOperators = ['=', '!=', '<>', '<', '<=', '>', '>='];
        if (!in_array($operator, $allowedOperators, true)) {
            throw new InvalidArgumentException('Invalid join operator.');
        }

        $type = strtoupper(trim($type));
        $allowedTypes = ['INNER', 'LEFT', 'RIGHT'];
        if (!in_array($type, $allowedTypes, true)) {
            throw new InvalidArgumentException('Invalid join type.');
        }

        $this->joins[] = sprintf('%s JOIN %s ON %s %s %s', $type, $table, $first, $operator, $second);
        return $this;
    }

    public function where(string $column, mixed $operatorOrValue, mixed $value = null): self
    {
        $this->assertIdentifier($column);

        if ($value === null) {
            $operator = '=';
            $value = $operatorOrValue;
        } else {
            $operator = strtoupper((string) $operatorOrValue);
        }

        $operator = strtoupper(trim((string) $operator));
        $allowedOperators = ['=', '!=', '<>', '<', '<=', '>', '>=', 'LIKE', 'NOT LIKE'];
        if (!in_array($operator, $allowedOperators, true)) {
            throw new InvalidArgumentException('Invalid where operator.');
        }

        $param = $this->newParam('w');
        $this->wheres[] = sprintf('%s %s %s', $column, $operator, $param);
        $this->bindings[$param] = $value;

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = max(1, $limit);
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = max(0, $offset);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->assertIdentifier($column);
        $direction = strtoupper(trim($direction));
        $direction = $direction === 'DESC' ? 'DESC' : 'ASC';

        $this->orders[] = sprintf('%s %s', $column, $direction);
        return $this;
    }

    public function get(): array
    {
        $sql = $this->compileSelectSql();
        Database::query($sql, $this->normalizeBindings());

        return Database::get_all_fetch();
    }

    public function first(): array|false
    {
        $clone = clone $this;
        $clone->limit(1);

        $sql = $clone->compileSelectSql();
        Database::query($sql, $clone->normalizeBindings());

        $row = Database::first_row_fetch();
        return $row ?: false;
    }

    public function exists(): bool
    {
        $clone = clone $this;
        $clone->select(['1'])->limit(1);

        $sql = $clone->compileSelectSql();
        Database::query($sql, $clone->normalizeBindings());

        return Database::first_row_fetch() !== false;
    }

    public function insert(array $data): string|bool
    {
        if ($data === []) {
            return false;
        }

        $columns = array_keys($data);
        foreach ($columns as $column) {
            $this->assertIdentifier((string) $column);
        }

        $placeholders = [];
        $binds = [];
        foreach ($data as $column => $value) {
            $param = ':' . (string) $column;
            $placeholders[] = $param;
            $binds[$param] = $value;
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        if (Database::query($sql, $binds)) {
            return Database::last_insert_byid();
        }

        return false;
    }

    public function update(array $data): bool
    {
        if ($data === []) {
            return false;
        }

        if ($this->wheres === []) {
            throw new InvalidArgumentException('Refusing to update without where clause.');
        }

        $sets = [];
        $binds = $this->normalizeBindings();
        foreach ($data as $column => $value) {
            $this->assertIdentifier((string) $column);
            $param = $this->newParam('u');
            $sets[] = sprintf('%s = %s', $column, $param);
            $binds[$param] = $value;
        }

        $sql = sprintf(
            'UPDATE %s SET %s%s',
            $this->table,
            implode(', ', $sets),
            $this->compileWhereSql()
        );

        return Database::query($sql, $binds);
    }

    public function delete(): bool
    {
        if ($this->wheres === []) {
            throw new InvalidArgumentException('Refusing to delete without where clause.');
        }

        $sql = sprintf('DELETE FROM %s%s', $this->table, $this->compileWhereSql());

        return Database::query($sql, $this->normalizeBindings());
    }

    private function compileSelectSql(): string
    {
        $sql = sprintf('SELECT %s FROM %s', implode(', ', $this->columns), $this->table);

        if ($this->joins !== []) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        $sql .= $this->compileWhereSql();

        if ($this->orders !== []) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . (int) $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . (int) $this->offset;
        }

        return $sql;
    }

    private function compileWhereSql(): string
    {
        if ($this->wheres === []) {
            return '';
        }

        return ' WHERE ' . implode(' AND ', $this->wheres);
    }

    private function newParam(string $prefix): string
    {
        $this->paramCounter++;

        return ':' . $prefix . $this->paramCounter;
    }

    private function assertIdentifier(string $value): void
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException('Identifier cannot be empty.');
        }

        if (!preg_match('/^[a-zA-Z0-9_\\.]+$/', $value)) {
            throw new InvalidArgumentException('Invalid identifier: ' . $value);
        }
    }

    private function normalizeBindings(): array
    {
        $normalized = [];
        foreach ($this->bindings as $key => $value) {
            $normalized[str_starts_with($key, ':') ? $key : ':' . $key] = $value;
        }

        return $normalized;
    }
}

