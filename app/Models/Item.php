<?php
declare(strict_types=1);

namespace App\Models;

use App\Kernel\Database\DB;
use App\Kernel\Database\Model;

final class Item extends Model
{
    protected static string $table = 'items';
    protected static string $primaryKey = 'itemId';

    public function insert(int|string $userId, array $itemDetails): string|bool
    {
        return DB::table(self::$table)->insert([
            'userId' => $userId,
            'idName' => $itemDetails['idName'],
            'itemName' => $itemDetails['itemName'],
            'businessName' => $itemDetails['businessName'],
            'summary' => $itemDetails['summary'],
            'price' => $itemDetails['price'],
        ]);
    }

    public function update(int|string $userId, array $itemDetails): bool
    {
        return DB::table(self::$table)
            ->where('userId', $userId)
            ->update([
                'idName' => $itemDetails['idName'],
                'itemName' => $itemDetails['itemName'],
                'businessName' => $itemDetails['businessName'],
                'summary' => $itemDetails['summary'],
                'price' => $itemDetails['price'],
            ]);
    }

    public function get(string $value): array|false
    {
        $query = DB::table(self::$table)
            ->select([
                'items.itemId',
                'items.userId',
                'items.idName',
                'items.itemName',
                'items.businessName',
                'items.summary',
                'items.price',
                'payments.paypalEmail',
                'payments.currency',
                'users.fullname',
                'users.email',
            ])
            ->join('payments', 'payments.userId', '=', 'items.userId')
            ->join('users', 'users.userId', '=', 'items.userId')
            ->limit(1);

        if (ctype_digit($value)) {
            $query->where('items.userId', (int) $value);
        } else {
            $query->where('items.idName', $value);
        }

        return $query->first();
    }

    public function has_user_an_item(int|string $userId): bool
    {
        return DB::table(self::$table)
            ->where('userId', $userId)
            ->exists();
    }

    public function does_id_name_exist(string $idName): bool
    {
        return DB::table(self::$table)
            ->where('idName', $idName)
            ->exists();
    }
}
