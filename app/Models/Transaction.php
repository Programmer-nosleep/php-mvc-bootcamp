<?php
declare(strict_types=1);

namespace App\Models;

use App\Kernel\Database\DB;
use App\Kernel\Database\Model;

final class Transaction extends Model
{
    protected static string $table = 'transactions';
    protected static string $primaryKey = 'transactionId';

    public function create_new(array $details): string|bool
    {
        return DB::table(self::$table)->insert($details);
    }

    public function get_by_order_id(string $orderId): array|false
    {
        return DB::table(self::$table)
            ->where('orderId', $orderId)
            ->first();
    }

    public function update_status(string $orderId, string $status, array $extra = []): bool
    {
        $data = array_merge(
            [
                'status' => $status,
            ],
            $extra
        );

        return DB::table(self::$table)
            ->where('orderId', $orderId)
            ->update($data);
    }
}

