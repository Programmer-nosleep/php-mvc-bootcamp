<?php
declare(strict_types=1);

namespace App\Service;

use App\Kernel\Session;

final class UserSession
{
    public const USER_ID_SESSION_NAME = 'userId';
    private const USER_EMAIL_SESSION_NAME = 'email';
    private const USER_NAME_SESSION_NAME = 'fullName';

    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isLoggedIn(): bool
    {
        return Session::has(self::USER_ID_SESSION_NAME);
    }

    public function setAuthentication(int|string $userId, string $email, string $fullName): void
    {
        Session::setUserId((int) $userId);
        Session::set(self::USER_EMAIL_SESSION_NAME, $email);
        Session::set(self::USER_NAME_SESSION_NAME, $fullName);
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function getName(): string
    {
        return (string) Session::get(self::USER_NAME_SESSION_NAME);
    }

    public function getEmail(): string
    {
        return (string) Session::get(self::USER_EMAIL_SESSION_NAME);
    }

    public function getId(): ?int
    {
        return Session::getUserId();
    }

    public function setName(string $fullName): void
    {
        Session::set(self::USER_NAME_SESSION_NAME, $fullName);
    }

    public function setEmail(string $email): void
    {
        Session::set(self::USER_EMAIL_SESSION_NAME, $email);
    }
}

