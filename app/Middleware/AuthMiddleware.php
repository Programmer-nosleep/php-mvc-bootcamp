<?php

namespace App\Middleware;

use function App\redirect;
/**
 * Class AuthMiddleware
 * 
 * Handles authentication checks for routes.
 * This middleware ensures that users are either authenticated or guests,
 * depending on the requirement of a specific route.
 */
class AuthMiddleware
{
    /**
     * Handle an incoming request and apply authentication rules.
     *
     * This method checks the user's session to determine if they are logged in.
     * It then applies one of two rules:
     * 
     * - 'auth': Requires the user to be logged in. If they are not, they are
     *   redirected to the /signin page. This is used for protected areas
     *   like dashboards, profiles, etc.
     *
     * - 'guest': Requires the user to be a guest (not logged in). If they
     *   are already logged in, they are redirected to the home page. This
     *   is used for pages like the sign-in or sign-up forms.
     *
     * @param string $key The middleware rule to apply ('auth' or 'guest').
     * @return void
     */
    public static function handle(string $key): void
    {
        // Check if a user session exists, which indicates the user is logged in.
        $is_authenticated = isset($_SESSION['users']);

        switch ($key) {
            // If the route requires authentication...
            case 'auth':
                // ...and the user is not logged in, redirect them to the sign-in page.
                if (!$is_authenticated) {
                    redirect('/signin');
                }
                break;

            // If the route requires the user to be a guest...
            case 'guest':
                // ...and the user is already logged in, redirect them to the home page.
                if ($is_authenticated) {
                    redirect('/');
                }
                break;
        }
    }
}
