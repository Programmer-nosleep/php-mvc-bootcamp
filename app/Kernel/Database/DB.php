<?php
declare(strict_types=1);

namespace App\Kernel\Database;

final class DB
{
    public static function table(string $table): QueryBuilder
    {
        return new QueryBuilder($table);
    }
}

