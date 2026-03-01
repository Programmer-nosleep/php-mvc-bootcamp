<?php
namespace App\Kernel;

use Symfony\Component\Dotenv\Dotenv;
use App\Kernel\Database\Database;
use App\Kernel\Session;

final class Bootstrap 
{
  public function __construct()
  {
    $dotenv = new Dotenv();
    $this->env($dotenv);
    new Session();
    $this->connect_db();
  }

  public function run(): void
  {
    require __DIR__ . '/../Router/Routes.php';
  }

  private function env(Dotenv $dotenv): void
  {
    $envFile = __DIR__ . '/../.env';

    if (is_file($envFile)) {
      $dotenv->load($envFile);
    }
  }

  private function connect_db(): void
  {
    $requiredKeys = ['DB_HOST', 'DB_NAME', 'DB_USER'];
    foreach ($requiredKeys as $key) {
      if (empty($_ENV[$key] ?? '')) {
        return;
      }
    }

    $conn = [
      'host' => $_ENV['DB_HOST'],
      'name' => $_ENV['DB_NAME'],
      'user' => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASSWORD'] ?? '',
    ];

    Database::connect($conn);
  }
}
