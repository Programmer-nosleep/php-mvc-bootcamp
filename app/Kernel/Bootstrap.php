<?php
namespace App\Kernel;

use Symfony\Component\Dotenv\Dotenv;
use App\Kernel\Database\Database;

final class Bootstrap 
{
  public function __construct()
  {
    $dotenv = new Dotenv();
    $this->env($dotenv);
    $this->connect_db();
  }

  public function run(): void
  {
    require __DIR__ . '/../Router/Routes.php';
  }

  private function env(Dotenv $dotenv): void
  {
    $dotenv->load(__DIR__ . '/../.env');
  }

  private function connect_db(): void
  {
    $conn = [
      'host' => $_ENV['DB_HOST'],
      'name' => $_ENV['DB_NAME'],
      'user' => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASSWORD'],
    ];
    Database::connect($conn);
  }
}
