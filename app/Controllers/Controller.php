<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Session;
use App\Kernel\View;
use App\Service\UserSession;

abstract class Controller
{
    protected UserSession $userSessionService;
    protected bool $isLoggedIn;

    public function __construct()
    {
        $this->userSessionService = new UserSession(new Session());
        $this->isLoggedIn = $this->userSessionService->isLoggedIn();
    }

    protected function pageNotFound(): void
    {
        http_response_code(404);

        echo View::render('errors/404', 'Page Not Found', [
            'isLoggedIn' => $this->isLoggedIn,
        ]);
    }
}
