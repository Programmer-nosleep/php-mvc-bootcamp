<?php
declare(strict_types=1);

namespace App\Kernel\Database;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    public static function query(): QueryBuilder
    {
        return DB::table(static::$table);
    }

    public static function find(int|string $id): array|false
    {
        return static::query()
            ->where(static::$primaryKey, $id)
            ->first();
    }

    public static function where(string $column, mixed $operatorOrValue, mixed $value = null): QueryBuilder
    {
        return static::query()->where($column, $operatorOrValue, $value);
    }

    public static function create(array $data): string|bool
    {
        return static::query()->insert($data);
    }
}

