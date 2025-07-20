<?php
namespace App\Models\User;

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
    public function insert(array $user_details): bool
    {
        if (!isset($user_details['fullname'], $user_details['email'], $user_details['password'])) {
            return false;
        }

        $sql = 'INSERT INTO ' . self::TABLE . ' (fullname, email, password) VALUES (:fullname, :email, :password)';

        // Hash password before storing
        $user_details['password'] = password_hash($user_details['password'], PASSWORD_BCRYPT);

        return (bool) Database::query($sql, $user_details);
    }

    /**
     * Check if an account with the given email exists.
     *
     * @param string $email
     * @return bool
     */
    public function does_account_isexist(string $email) : bool
    {
      $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = :email LIMIT 1';
      Database::query($sql, ['email' => $email]);
      $user = Database::first_row_fetch();

      return Database::row_count() > 1 && $user;
    }

    /**
     * Attempt to log a user in by verifying credentials.
     *
     * @param string $email
     * @param string $password
     * @return bool True if login successful, false otherwise.
     */
    public function login(string $email, string $password): bool
    {
      $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE email = :email LIMIT 1';
      Database::query($sql, ['email' => $email]);
      $user = Database::first_row_fetch();

      if (!$user || !isset($user['password'])) {
          return false; // email is cannot found. 
      }

      return password_verify($password, $user['password']);
    }
}
