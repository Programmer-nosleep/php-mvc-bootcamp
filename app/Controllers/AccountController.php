<?php
namespace App\Controllers;

use App\Kernel\Input;
use App\Kernel\View;

class AccountController
{
  public function signin() : void
  {
    if (Input::get('signin_submit')) 
    {
      echo Input::get('email');
    }
    $render = View::render('auth/signin', 'Sign In');

    echo $render;
  }

  public function signup() : void
  {
    if (Input::post('signup_submit'))
    {
      $fullname = Input::post('fullname');
      $email = Input::post('email');
      $password = Input::post('password');

      if ($this->validate_email($email))
      {

      } else {
        $view_var['erro_message'] = 'email is not valid.';
      }
    }
    $render = View::render('auth/signup', 'Sign Up');

    echo $render;
  }
  
  public function edit() : void
  {
    $render = View::render('auth/edit', 'Edit Account');

    echo $render; 
  }

  private function validate_email(string $email) : bool
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }
}