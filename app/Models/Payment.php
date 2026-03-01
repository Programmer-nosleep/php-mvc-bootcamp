<?php
declare(strict_types=1);

namespace App\Models;

use App\Kernel\Database\DB;
use App\Kernel\Database\Model;

final class Payment extends Model
{
    protected static string $table = 'payments';
    protected static string $primaryKey = 'paymentId';

    public function insert(array $details): string|bool
    {
        return DB::table(self::$table)->insert($details);
    }

    public function does_details_exist(int|string $userId): bool
    {
        return DB::table(self::$table)
            ->where('userId', $userId)
            ->exists();
    }

    public function update(int|string $userId, string $paypalEmail, string $currency): bool
    {
        return DB::table(self::$table)
            ->where('userId', $userId)
            ->update([
                'paypalEmail' => $paypalEmail,
                'currency' => $currency,
            ]);
    }

    public function get_details(int|string $userId): array|false
    {
        return DB::table(self::$table)
            ->where('userId', $userId)
            ->first();
    }
}
