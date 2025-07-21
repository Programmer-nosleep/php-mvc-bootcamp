<?php
namespace App\Service;

use App\Models\User as UserModel;

class User
{
  private const MIN_PASSWORD = 6;
  private const MAXIMUM_EMAIL_LENGTH = 100;

  public function __construct(private UserModel $user_models)
  {
  }

  public function create(array $user_details) : bool
  {
    return $this->user_models->insert($user_details);
  }

  public function does_account_isexist(string $email): bool
  {
    return $this->user_models->does_account_isexist($email);  
  }

  /**
    * Validates the email format using PHP's filter.
    * You can add more complex email validation logic here.
    *
    * @param string $email The email address to validate.
    * @return bool True if the email is valid, false otherwise.
    */
    public function validate_email(string $email) : bool
    {
      return strlen($email) <= self::MAXIMUM_EMAIL_LENGTH && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validates the password format using 
     * You can add more complexity validation logic here.
     * 
     * @param string $password the email address to validate.
     * @return bool True if the email is valid, false otherwise.
    */ 
    public function validate_password(string $password) : bool
    {
      return strlen($password) > self::MIN_PASSWORD;
    }
}