<?php
declare(strict_types=1);
namespace App\Kernel\Http;

error_reporting(E_ALL);
ini_set('display_errors', '1');

use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;

enum METHOD: string
{
  case GET = 'GET';
  case POST = 'POST';
}

class Router
{
  private const SEPARATOR = '@';
  private const CONTROLLER = 'App\Controllers\\';
  private static ?string $http_method = null;

  public static function get(string $uri, string $class = '') : void
  {
    self::$http_method = METHOD::GET->value;
    self::exec($uri, $class);
  }

  public static function post(string $uri, string $class = '') : void
  {
    self::$http_method = METHOD::POST->value;
    self::exec($uri, $class);
  }

  private static function exec(string $uri, string $method)
  {
    $uri = '/' . trim($uri, '/');
    $url = '/';
    $url .= !empty($_GET['uri']) ? $_GET['uri'] : '/';

    if (preg_match("#^$uri$#", $url, $params))
    {
      if (self::is_controller($method))
      {

        /* header('Location: ' . $_ENV['LOCAL_URL'] . '/' . $method); */ 
        header(sprintf('Location: %s/%s', $_ENV['LOCAL_URL'], $method));
      } else {
        if (!self::is_http_method_valid())
        {
          throw new InvalidArgumentException(sprintf('Invalid "%s" HTTP Request', $_SERVER['REQUEST_METHOD']));
        }

        $split = explode(self::SEPARATOR, $method); 
        $class_name = self::CONTROLLER . $split[0];
        $method = $split[1];     

        try {
          if (!class_exists($class_name)) {
            throw new InvalidArgumentException("Controller class $class_name not found.");
          }

          $reflection = new ReflectionClass($class_name);
          
          if (class_exists($class_name) && $reflection->hasMethod($method))
          {
            $action = new ReflectionMethod($class_name, $method);
            if ($action->isPublic())
            {
              return $action->invokeArgs(new $class_name, self::get_action_parameters($params));
            }
          }
        } catch (ReflectionException $e) {
          throw new InvalidArgumentException(sprintf("%s", $e->getMessage()));
        }
      }
    }
  }

  private static function is_http_method_valid() : bool
  {
    return self::$http_method !== null && $_SERVER['REQUEST_METHOD'] === self::$http_method;
  } 

  private static function is_controller(string $method) : bool
  {
    return !str_contains($method, self::SEPARATOR);
  }

  private static function get_action_parameters(array $params) : array
  {
    foreach($params as $key => $value) 
    {
      $params[$key] = str_replace('/', '', $params);
    }

    return $params;
  }
}