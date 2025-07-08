<?php
declare(strict_types=1);
namespace App\Controllers; // Changed namespace to just 'App\Controllers' for consistency with Router's CONTROLLER constant

use App\Kernel\View;

class HomeController
{
    // The index method for the home page
    public function index(): void
    {
        $data = [
            'name' => 'Hello, World!',
            'framework' => 'PHP Custom Framework'
        ];

        $html_content = View::render('home/home', 'Selamat Datang', $data, true);

        echo $html_content;
    }

    public function edit(): void
    {
      /*
       View::render('home/view', 'Pages', [
        'name' => 'Pages',
        'content' => 'Lorem ipsum dolor sit amet.'
       ], true); 
      */

      echo 'pler';
    }
}