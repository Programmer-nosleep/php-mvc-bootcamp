<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\View;
use App\Kernel\Session;

final class ErrorController
{
    public function notFound(): void
    {
        http_response_code(404);

        new Session();

        echo View::render('errors/404', 'Page Not Found', [
            'isLoggedIn' => Session::getUserId() !== null,
        ]);
    }

    public function csrfMismatch(): void
    {
        http_response_code(419);

        new Session();

        echo View::render('errors/419', 'Page Expired', [
            'isLoggedIn' => Session::getUserId() !== null,
        ]);
    }
}
