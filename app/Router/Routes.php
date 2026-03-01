<?php
// App/Router/Routes.php
namespace App\Router;

use App\Exceptions\ViewNotFound;
use App\Kernel\Http\Router;
use App\Kernel\Session;

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
  Router::get_and_post('/contact', 'HomeController@contact');

  Router::get('/p/([a-z0-9\\.\\-_]+)', 'PaymentController@showItem');

  Router::post('/midtrans/token', 'MidtransController@token');
  Router::post('/midtrans/notification', 'MidtransController@notification');

  new Session();
  $isLoggedIn = Session::getUserId() !== null;

  if (!$isLoggedIn) {
    Router::get_and_post('/signup', 'AccountController@signup');
    Router::get_and_post('/signin', 'AccountController@signin');
  }

  if ($isLoggedIn) {
    Router::get_and_post('/account/edit', 'AccountController@edit');
    Router::get_and_post('/account/password', 'AccountController@password');
    Router::get('/account/logout', 'AccountController@logout');

    Router::get_and_post('/payment', 'PaymentController@payment');
    Router::get_and_post('/item', 'PaymentController@item');
  }

  Router::end();
} catch (ViewNotFound $e) {
  throw new ViewNotFound(sprintf('%s', $e->getMessage()));
}
