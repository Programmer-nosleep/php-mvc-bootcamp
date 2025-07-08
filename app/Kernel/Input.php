<?php
namespace App\Kernel;

final class Input
{
  public static function get(string $key)
  {
    return !empty($_GET[$key]) ? $_GET[$key] : false;
  }

  public static function post(string $key)
  {
    return !empty($_POST[$key]) ? $_POST[$key] : false;
  }
}