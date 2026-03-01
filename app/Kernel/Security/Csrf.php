<?php
declare(strict_types=1);

namespace App\Kernel\Security;

use App\Kernel\Session;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        new Session();

        if (!Session::has(self::SESSION_KEY)) {
            Session::set(self::SESSION_KEY, bin2hex(random_bytes(32)));
        }

        return (string) Session::get(self::SESSION_KEY);
    }

    public static function regenerate(): string
    {
        new Session();

        Session::set(self::SESSION_KEY, bin2hex(random_bytes(32)));

        return (string) Session::get(self::SESSION_KEY);
    }

    public static function validate(?string $token): bool
    {
        $sessionToken = self::token();

        if ($token === null || $token === '' || $sessionToken === '') {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function fromRequest(): ?string
    {
        if (!empty($_POST['_token']) && is_string($_POST['_token'])) {
            return $_POST['_token'];
        }

        if (!empty($_SERVER['HTTP_X_CSRF_TOKEN']) && is_string($_SERVER['HTTP_X_CSRF_TOKEN'])) {
            return $_SERVER['HTTP_X_CSRF_TOKEN'];
        }

        if (!empty($_SERVER['HTTP_X_XSRF_TOKEN']) && is_string($_SERVER['HTTP_X_XSRF_TOKEN'])) {
            return $_SERVER['HTTP_X_XSRF_TOKEN'];
        }

        return null;
    }
}

