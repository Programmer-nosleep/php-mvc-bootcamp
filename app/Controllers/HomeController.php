<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Kernel\View;

class HomeController
{
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