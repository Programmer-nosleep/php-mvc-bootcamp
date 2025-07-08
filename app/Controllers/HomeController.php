<?php
// App/Controllers/HomeController.php
namespace App\Controllers; // Changed namespace to just 'App\Controllers' for consistency with Router's CONTROLLER constant

use App\Kernel\View;

class HomeController
{
    // The index method for the home page
    public function index()
    {
        $data = [
            'name' => 'Hello, World!',
            'framework' => 'PHP Custom Framework'
        ];

        $htmlContent = View::render('home/home', 'Selamat Datang', $data, true);

        echo $htmlContent;
    }

    public function edit(): void
    {
       View::render('home/view', 'Pages', [
        'name' => 'Pages',
        'content' => 'Lorem ipsum dolor sit amet.'
       ], true); 
    }
}