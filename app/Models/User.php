<?php
namespace App\Models\User;

use App\Kernel\Database\Database;

class User
{
  private const TABLE = 'users';

  public function insert(array $user_details) : bool 
  {
    $sql = 'INSERT INTO ' . self::TABLE . ' (fullname, email, password' . ')';
    return Database::query($sql, $user_details);
  } 

  public function login(string $email, string $password) : bool
  {
    return true;
  }
}