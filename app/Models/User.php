<?php
namespace App\Models;

use App\Kernel\Database\Database;

class User
{
    private const TABLE = 'users';

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

      $sql = 'INSERT INTO ' . self::TABLE . ' (fullname, email, password) VALUES (:fullname, :email, :password)';

      if(Database::query($sql, $user_details))
      {
        return Database::last_insert_byid();
      }

      return false;
    }

    /**
     * Check if an account with the given email exists.
     *
     * @param string $email
     * @return bool
     */
    public function does_account_isexist(string $email) : bool
    {
      $sql = 'SELECT email FROM ' . self::TABLE . ' WHERE email = :email LIMIT 1';
      Database::query($sql, ['email' => $email]);

      return Database::row_count() >= 1;
    }

    /**
     * Fetch user details by email or userId.
     *
     * @param string $unique_value
     * @return array|false User row or false when not found.
     */
    public function get_details(string $unique_value): array|false
    {
      $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = :value OR userId = :value LIMIT 1';
      Database::query($sql, ['value' => $unique_value]);

      $user = Database::first_row_fetch();
      return $user ?: false;
    }

    public function update_email(int|string $user_id, string $email): bool
    {
      $sql = 'UPDATE ' . self::TABLE . ' SET email = :email WHERE userId = :userId LIMIT 1';

      return Database::query($sql, [
        'userId' => $user_id,
        'email' => $email,
      ]);
    }

    public function update_name(int|string $user_id, string $name): bool
    {
      $sql = 'UPDATE ' . self::TABLE . ' SET fullname = :fullname WHERE userId = :userId LIMIT 1';

      return Database::query($sql, [
        'userId' => $user_id,
        'fullname' => $name,
      ]);
    }

    public function update_password(int|string $user_id, string $hashed_password): bool
    {
      $sql = 'UPDATE ' . self::TABLE . ' SET password = :password WHERE userId = :userId LIMIT 1';

      return Database::query($sql, [
        'userId' => $user_id,
        'password' => $hashed_password,
      ]);
    }
}
