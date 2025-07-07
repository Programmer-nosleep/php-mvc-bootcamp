<?php
namespace App\Kernel;

use Symfony\Component\Dotenv\Dotenv;

final class Bootstrap 
{
  public function __construct()
  {
    $dotenv = new Dotenv();
    $this->env($dotenv);

    // echo $_ENV['SITE_NAME'];
  }

  public function run() : void
  {
    require __DIR__ . '/../Router/Routes.php';
  }

  private function initialize ()
  {

  }

  private function env(Dotenv $dotenv) : void
  { 
    $dotenv->load(__DIR__ . '/../.env'); 
  }

  private function test()
  {

  }
}