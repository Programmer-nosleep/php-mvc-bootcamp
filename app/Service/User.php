<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\User as UserModel;

final class User
{
    private const PASSWORD_COST_FACTOR = 12;
    private const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;

    private UserModel $userModel;

    public function __construct(?UserModel $userModel = null)
    {
        $this->userModel = $userModel ?? new UserModel();
    }

    public function create(array $userDetails): string|bool
    {
        return $this->userModel->insert($userDetails);
    }

    public function updateEmail(int|string $userId, string $email): bool
    {
        return $this->userModel->update_email($userId, $email);
    }

    public function updateName(int|string $userId, string $name): bool
    {
        return $this->userModel->update_name($userId, $name);
    }

    public function updatePassword(int|string $userId, string $hashedPassword): bool
    {
        return $this->userModel->update_password($userId, $hashedPassword);
    }

    public function doesAccountEmailExist(string $email): bool
    {
        return $this->userModel->does_account_isexist($email);
    }

    public function hashPassword(string $password): string
    {
        return (string) password_hash($password, self::PASSWORD_ALGORITHM, ['cost' => self::PASSWORD_COST_FACTOR]);
    }

    public function verifyPassword(string $clearPassword, string $hashedPassword): bool
    {
        return password_verify($clearPassword, $hashedPassword);
    }

    public function getDetailsFromEmail(string $email): array|false
    {
        return $this->userModel->get_details($email);
    }

    public function getDetailsFromId(int|string $userId): array|false
    {
        return $this->userModel->get_details((string) $userId);
    }

    public function getHashedPassword(int|string $userId): string
    {
        $userDetails = $this->getDetailsFromId($userId);

        return $userDetails['password'] ?? '';
    }
}

