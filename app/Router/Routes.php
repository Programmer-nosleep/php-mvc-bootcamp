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
  Router::get('/edit', 'HomeController@edit');
  Router::get('/about', 'HomeController@about');
  Router::get('/contact', '/about');

  Router::get_and_post('/signup', 'AccountController@signup');
  Router::get_and_post('/signin', 'AccountController@signin');
  Router::get_and_post('/account/edit', 'AccountController@edit');
  
  Router::get_and_post('/payment', 'Payment Gateway');
} catch (ViewNotFound $e) {
  throw new ViewNotFound(sprintf('%s', $e->getMessage()));
}