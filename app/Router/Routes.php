<?php
// App/Router/Routes.php
namespace App\Router;

use App\Exceptions\ViewNotFound;
use App\Kernel\Http\Router;

try {
  /** 
   * Define your application routes here 
   * 
   * Example of a route with a parameter:
   * Router::get('/users/(\d+)', 'UserController@show'); // Matches /users/123
   * 
   * Example of a redirect route (if you wanted to use that functionality):
   * Router::get('/old-path', '/new-path');
  */

  Router::get('/', 'HomeController@index');
  Router::get('/about', 'HomeController@about');
  Router::get('/contact', '/?uri=about');

  Router::get('/signup', 'AccountController@signup');
  Router::get('/signin', 'AccountController@signin');
  Router::get('/account/edit', 'AccountController@edit');
  

} catch (ViewNotFound $e) {
  throw new ViewNotFound(sprintf('%s', $e->getMessage()));
}