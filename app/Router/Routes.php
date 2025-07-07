<?php
namespace App\Router;

use App\Kernel\Http\Router;

Router::get('/', 'HomeController@index');