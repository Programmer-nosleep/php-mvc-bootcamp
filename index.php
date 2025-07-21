<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Kernel\Bootstrap;
use App\Kernel\Database\Database;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/app/.env');

Database::connect([
  'host' => $_ENV['DB_HOST'],
  'name' => $_ENV['DB_NAME'],
  'user' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD']
]);

ob_start();
$app = new Bootstrap();
$app->run();
ob_end_flush();