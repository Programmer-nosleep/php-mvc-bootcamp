<?php
declare(strict_types=1);
namespace App\Kernel\Http;

error_reporting(E_ALL);
ini_set('display_errors', '1');

use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;

// Define an enum for HTTP methods for type safety and clarity
enum METHOD: string
{
    case GET = 'GET';
    case POST = 'POST';
    case GET_AND_POST = 'GET_POST';
}

class Router
{
    // Separator used in controller strings (e.g., 'ControllerName@methodName')
    private const SEPARATOR = '@';

    // Base namespace for all application controllers
    private const CONTROLLER = 'App\Controllers\\';

    // Stores the HTTP method for the current route being processed
    private static ?string $http_method = null;

    /**
     * Defines a GET route.
     * @param string $uri The URI pattern to match (e.g., '/users/{id}')
     * @param string $class The controller action (e.g., 'UserController@index') or a redirect path
     */
    public static function get(string $uri, string $class = '') : void
    {
        self::$http_method = METHOD::GET->value;
        self::exec($uri, $class);
    }

    /**
     * Defines a POST route.
     * @param string $uri The URI pattern to match
     * @param string $class The controller action or a redirect path
     */
    public static function post(string $uri, string $class = '') : void
    {
        self::$http_method = METHOD::POST->value;
        self::exec($uri, $class);
    }

    /**
     * Defines a POST route.
     * @param string $uri The URI pattern to match
     * @param string $class The controller action or a redirect path
     */
    public static function get_and_post(string $uri, string $class = '') : void
    {
      self::$http_method = METHOD::GET_AND_POST->value;
      self::exec($uri, $class);
    }

    /**
     * Executes the routing logic.
     * @param string $uri The defined route URI pattern.
     * @param string $method The controller action string or a redirect path.
     */
    private static function exec(string $uri, string $method)
    {
        // Normalize the defined URI pattern
        $uri = '/' . trim($uri, '/');

        // Get the current request URI from the server
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Attempt to match the current URL against the defined URI pattern
        if (preg_match("#^$uri$#", $url, $params)) {
            if (self::is_redirect($method)) {
                header(sprintf('Location: %s/%s', $_ENV['LOCAL_URL'], trim($method, '/')));
                exit();
            } else {
                if (!self::is_http_method_valid()) {
                    throw new InvalidArgumentException(sprintf('Invalid "%s" HTTP Request', $_SERVER['REQUEST_METHOD']));
                }

                $split = explode(self::SEPARATOR, $method);
                $class_name = self::CONTROLLER . $split[0];
                $action_method = $split[1];

                try {
                    if (!class_exists($class_name)) {
                        throw new InvalidArgumentException("Controller class $class_name not found.");
                    }

                    $reflection = new ReflectionClass($class_name);

                    if ($reflection->hasMethod($action_method)) {
                        $action = new ReflectionMethod($class_name, $action_method);
                        if ($action->isPublic()) {
                            return $action->invokeArgs(new $class_name, self::get_action_parameters($params));
                        } else {
                            throw new InvalidArgumentException(sprintf("Method '%s' in class '%s' is not public.", $action_method, $class_name));
                        }
                    } else {
                        throw new InvalidArgumentException(sprintf("Method '%s' not found in class '%s'.", $action_method, $class_name));
                    }
                } catch (ReflectionException $e) {
                    throw new InvalidArgumentException(sprintf("Reflection Error: %s", $e->getMessage()));
                }
            }
        }
    }


    /**
     * Checks if the current HTTP request method matches the defined route method.
     * @return bool True if methods match, false otherwise.
     */
    private static function is_http_method_valid() : bool
    {
      if (self::$http_method === METHOD::GET_AND_POST->value)
      {
        return $_SERVER['REQUEST_METHOD'] === METHOD::GET->value || $_SERVER['REQUEST_METHOD'] === METHOD::POST->value;
      }
      return $_SERVER['REQUEST_METHOD'] === self::$http_method;
    }

    /**
     * Determines if the given method string is a controller action string (contains '@').
     * (Note: This method is not directly used in exec's current flow, but is good for clarity)
     * @param string $method The method string.
     * @return bool True if it's a controller action, false otherwise.
     */
    private static function is_controller(string $method) : bool
    {
        return str_contains($method, self::SEPARATOR);
    }

    /**
     * Extracts and prepares parameters from the URL match.
     * @param array $params The array of matches from preg_match.
     * @return array Cleaned parameters to be passed to the controller method.
     */
    private static function get_action_parameters(array $params) : array
    {
        // The first element of $params is the full matched string, so we remove it.
        array_shift($params);

        // Trim any leading/trailing slashes from the captured parameters
        return array_map(fn($param) => trim($param, '/'), $params);
    }

    /**
     * Determines if the given method string is intended for a redirect.
     * A method is considered a redirect if it does NOT contain the SEPARATOR (@),
     * implying it's a simple path to redirect to.
     * @param string $method The method string passed to Router::get/post.
     * @return bool True if it's a redirect target, false if it's a controller action.
     */
    private static function is_redirect(string $method): bool
    {
        return !str_contains($method, self::SEPARATOR);
    }
}