<?php
declare(strict_types=1);

namespace App\Models;

use App\Kernel\Database\Database;

final class Item
{
    private const TABLE = 'items';

    public function insert(int|string $userId, array $itemDetails): string|bool
    {
        $sql = 'INSERT INTO ' . self::TABLE . ' (userId, idName, itemName, businessName, summary, price) 
            VALUES (:userId, :idName, :itemName, :businessName, :summary, :price)';

        $binds = [
            'userId' => $userId,
            'idName' => $itemDetails['idName'],
            'itemName' => $itemDetails['itemName'],
            'businessName' => $itemDetails['businessName'],
            'summary' => $itemDetails['summary'],
            'price' => $itemDetails['price'],
        ];

        if (Database::query($sql, $binds)) {
            return Database::last_insert_byid();
        }

        return false;
    }

    public function update(int|string $userId, array $itemDetails): bool
    {
        $sql = 'UPDATE ' . self::TABLE . ' 
            SET idName = :idName, itemName = :itemName, businessName = :businessName, summary = :summary, price = :price 
            WHERE userId = :userId LIMIT 1';

        return Database::query($sql, [
            'userId' => $userId,
            'idName' => $itemDetails['idName'],
            'itemName' => $itemDetails['itemName'],
            'businessName' => $itemDetails['businessName'],
            'summary' => $itemDetails['summary'],
            'price' => $itemDetails['price'],
        ]);
    }

    public function get(string $value): array|false
    {
        $sql = 'SELECT i.*, p.paypalEmail, p.currency, u.fullname, u.email
            FROM ' . self::TABLE . ' AS i 
            INNER JOIN payments AS p USING(userId) 
            INNER JOIN users AS u USING(userId)
            WHERE i.idName = :value OR i.userId = :value 
            LIMIT 1';

        Database::query($sql, ['value' => $value]);

        $item = Database::first_row_fetch();
        return $item ?: false;
    }

    public function has_user_an_item(int|string $userId): bool
    {
        $sql = 'SELECT userId FROM ' . self::TABLE . ' WHERE userId = :userId LIMIT 1';
        Database::query($sql, ['userId' => $userId]);

        return Database::row_count() >= 1;
    }

    public function does_id_name_exist(string $idName): bool
    {
        $sql = 'SELECT idName FROM ' . self::TABLE . ' WHERE idName = :idName LIMIT 1';
        Database::query($sql, ['idName' => $idName]);

        return Database::row_count() >= 1;
    }
}

