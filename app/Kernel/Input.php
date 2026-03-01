<?php
namespace App\Kernel;

final class Input
{
  public static function get(string $key, mixed $default = null): mixed
  {
    return $_GET[$key] ?? $default;
  }

  public static function post(string $key, mixed $default = null): mixed
  {
    return $_POST[$key] ?? $default;
  }

  public static function getExists(string $key): bool
  {
    return array_key_exists($key, $_GET);
  }

  public static function postExists(string $key): bool
  {
    return array_key_exists($key, $_POST);
  }

  public static function postTrimmed(string $key, string $default = ''): string
  {
    $value = self::post($key);
    if (!is_string($value)) {
      return $default;
    }

    return trim($value);
  }

  public static function getTrimmed(string $key, string $default = ''): string
  {
    $value = self::get($key);
    if (!is_string($value)) {
      return $default;
    }

    return trim($value);
  }
}
