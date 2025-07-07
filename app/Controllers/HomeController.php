<?php
// App/Controllers/HomeController.php
namespace App\Controllers; // Changed namespace to just 'App\Controllers' for consistency with Router's CONTROLLER constant

class HomeController
{
    // The index method for the home page
    public function index(): void
    {
        echo "<h1>Welcome to the Home Page!</h1>"; // Changed text for clarity
    } 

    public function edit(): void
    {
        echo 'kontol';
    }
}