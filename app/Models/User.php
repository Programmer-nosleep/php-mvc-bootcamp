<?php
namespace App\Models\User;

use App\Kernel\Database\Database;

class User
{
  public function insert(array $user_details) : bool 
  {
    Database::query();
    return true;
  } 

  public function login(string $email, string $password) : bool
  {
    return true;
  }
}