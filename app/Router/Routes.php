<?php
// App/Router/Routes.php
namespace App\Router;

use App\Exceptions\ViewNotFound;
use App\Kernel\Http\Router;

try {
  // Define your application routes here
  Router::get('/', 'HomeController@index');
  // Router::get('/edit', 'HomeController@edit');
  
  // Example of a route with a parameter:
  // Router::get('/users/(\d+)', 'UserController@show'); // Matches /users/123
  
  // Example of a redirect route (if you wanted to use that functionality):
  // Router::get('/old-path', '/new-path');
} catch (ViewNotFound $e) {
  throw new ViewNotFound(sprintf('%s', $e->getMessage()));
}