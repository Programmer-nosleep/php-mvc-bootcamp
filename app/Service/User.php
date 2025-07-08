<?php
namespace App\Service;

class User
{
  private const MIN_PASSWORD = 10;
  /**
    * Validates the email format using PHP's filter.
    * You can add more complex email validation logic here.
    *
    * @param string $email The email address to validate.
    * @return bool True if the email is valid, false otherwise.
    */
    public function validate_email(string $email) : bool
    {
      return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
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