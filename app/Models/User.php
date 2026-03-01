<?php
declare(strict_types=1);

namespace App\Models;

use App\Kernel\Database\DB;
use App\Kernel\Database\Model;

final class User extends Model
{
    protected static string $table = 'users';
    protected static string $primaryKey = 'userId';

    /**
     * Insert a new user into the database.
     *
     * @param array $user_details ['fullname' => ..., 'email' => ..., 'password' => ...]
     * @return bool
     */
    public function insert(array $user_details) : string | bool
    {
      if (!isset($user_details['fullname'], $user_details['email'], $user_details['password'])) {
        return false;
      }

      return DB::table(self::$table)->insert($user_details);
    }

    /**
     * Check if an account with the given email exists.
     *
     * @param string $email
     * @return bool
     */
    public function does_account_isexist(string $email) : bool
    {
      return DB::table(self::$table)
        ->where('email', $email)
        ->exists();
    }

    /**
     * Fetch user details by email or userId.
     *
     * @param string $unique_value
     * @return array|false User row or false when not found.
     */
    public function get_details(string $unique_value): array|false
    {
      if (ctype_digit($unique_value)) {
        return DB::table(self::$table)
          ->where('userId', (int) $unique_value)
          ->first();
      }

      return DB::table(self::$table)
        ->where('email', $unique_value)
        ->first();
    }

    public function update_email(int|string $user_id, string $email): bool
    {
      return DB::table(self::$table)
        ->where('userId', $user_id)
        ->update(['email' => $email]);
    }

    public function update_name(int|string $user_id, string $name): bool
    {
      return DB::table(self::$table)
        ->where('userId', $user_id)
        ->update(['fullname' => $name]);
    }

    public function update_password(int|string $user_id, string $hashed_password): bool
    {
      return DB::table(self::$table)
        ->where('userId', $user_id)
        ->update(['password' => $hashed_password]);
    }
}
