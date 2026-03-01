<?php
declare(strict_types=1);

namespace App\Models;

use App\Kernel\Database\Database;

final class Payment
{
    private const TABLE = 'payments';

    public function insert(array $details): string|bool
    {
        $sql = 'INSERT INTO ' . self::TABLE . ' (userId, paypalEmail, currency) VALUES (:userId, :paypalEmail, :currency)';

        if (Database::query($sql, $details)) {
            return Database::last_insert_byid();
        }

        return false;
    }

    public function does_details_exist(int|string $userId): bool
    {
        $sql = 'SELECT paymentId FROM ' . self::TABLE . ' WHERE userId = :userId LIMIT 1';
        Database::query($sql, ['userId' => $userId]);

        return Database::row_count() >= 1;
    }

    public function update(int|string $userId, string $paypalEmail, string $currency): bool
    {
        $sql = 'UPDATE ' . self::TABLE . ' SET paypalEmail = :paypalEmail, currency = :currency WHERE userId = :userId LIMIT 1';

        return Database::query($sql, [
            'userId' => $userId,
            'paypalEmail' => $paypalEmail,
            'currency' => $currency,
        ]);
    }

    public function get_details(int|string $userId): array|false
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE userId = :userId LIMIT 1';

        Database::query($sql, ['userId' => $userId]);

        $details = Database::first_row_fetch();
        return $details ?: false;
    }
}

