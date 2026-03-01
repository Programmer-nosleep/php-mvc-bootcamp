<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Input;
use App\Kernel\View;
use App\Service\User as UserService;
use App\Service\UserValidation;
use function App\redirect;

final class AccountController extends Controller
{
    private UserService $userService;
    private UserValidation $userValidation;

    public function __construct(?UserService $userService = null, ?UserValidation $userValidation = null)
    {
        parent::__construct();

        $this->userService = $userService ?? new UserService();
        $this->userValidation = $userValidation ?? new UserValidation();
    }

    public function signup(): void
    {
        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if (Input::postExists('signup_submit')) {
            $fullName = Input::postTrimmed('fullname');
            $email = Input::postTrimmed('email');
            $password = (string)Input::post('password', '');

            if ($fullName !== '' && $email !== '' && $password !== '') {
                if (
                    $this->userValidation->isNameValid($fullName) &&
                    $this->userValidation->isEmailValid($email) &&
                    $this->userValidation->isPasswordValid($password)
                ) {
                    if ($this->userService->doesAccountEmailExist($email)) {
                        $viewVariables[View::get_status_message('ERROR')] = 'An account with the same email address already exists.';
                    } else {
                        $user = [
                            'fullname' => $fullName,
                            'email' => $email,
                            'password' => $this->userService->hashPassword($password),
                        ];

                        if ($userId = $this->userService->create($user)) {
                            $this->userSessionService->setAuthentication($userId, $email, $fullName);
                            redirect('/');
                        } else {
                            $viewVariables[View::get_status_message('ERROR')] = 'An error occurred while creating your account. Please try again.';
                        }
                    }
                } else {
                    $viewVariables[View::get_status_message('ERROR')] = 'Email / password / name is not valid.';
                }
            } else {
                $viewVariables[View::get_status_message('ERROR')] = 'All fields are required.';
            }
        }

        echo View::render('account/signup', 'Sign Up', $viewVariables);
    }

    public function signin(): void
    {
        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if (Input::postExists('signin_submit')) {
            $email = Input::postTrimmed('email');
            $password = (string)Input::post('password', '');

            $userDetails = $email !== '' ? $this->userService->getDetailsFromEmail($email) : false;
            $isLoginValid = is_array($userDetails)
                && !empty($userDetails['password'])
                && $this->userService->verifyPassword($password, (string)$userDetails['password']);

            if ($isLoginValid) {
                $this->userSessionService->setAuthentication(
                    (int)$userDetails['userId'],
                    (string)$userDetails['email'],
                    (string)($userDetails['fullname'] ?? '')
                );
                redirect('/');
            }

            $viewVariables[View::get_status_message('ERROR')] = 'Incorrect login.';
        }

        echo View::render('account/signin', 'Sign In', $viewVariables);
    }

    public function edit(): void
    {
        $userId = $this->userSessionService->getId();
        if ($userId === null) {
            redirect('/signin');
        }

        $userDetails = $this->userService->getDetailsFromId($userId);
        if (!$userDetails) {
            $this->userSessionService->logout();
            redirect('/signin');
        }

        $viewVariables = [
            'user' => $userDetails,
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if (Input::postExists('edit_submit')) {
            $name = Input::postTrimmed('fullname');
            $email = Input::postTrimmed('email');

            if ($name !== '' && $email !== '') {
                $hasEmailChanged = $email !== ($userDetails['email'] ?? '');
                $hasNameChanged = $name !== ($userDetails['fullname'] ?? '');

                if ($hasEmailChanged) {
                    if (!$this->userValidation->isEmailValid($email) || $this->userService->doesAccountEmailExist($email)) {
                        $viewVariables[View::get_status_message('ERROR')][] = 'Email is invalid or already taken.';
                    } else {
                        $this->userService->updateEmail($userId, $email);
                        $this->userSessionService->setEmail($email);
                        $userDetails['email'] = $email;
                        $viewVariables[View::get_status_message('SUCCESS')][] = 'Email has been updated.';
                    }
                }

                if ($hasNameChanged) {
                    if (!$this->userValidation->isNameValid($name)) {
                        $viewVariables[View::get_status_message('ERROR')][] = 'Name is either too short or too long.';
                    } else {
                        $this->userService->updateName($userId, $name);
                        $this->userSessionService->setName($name);
                        $userDetails['fullname'] = $name;
                        $viewVariables[View::get_status_message('SUCCESS')][] = 'Name has been updated.';
                    }
                }

                $viewVariables['user'] = $userDetails;
            } else {
                $viewVariables[View::get_status_message('ERROR')] = 'All fields are required.';
            }
        }

        echo View::render('account/edit', 'Edit Account', $viewVariables);
    }

    public function password(): void
    {
        $userId = $this->userSessionService->getId();
        if ($userId === null) {
            redirect('/signin');
        }

        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if (Input::postExists('password_submit')) {
            $currentPassword = (string)Input::post('current_password', '');
            $newPassword = (string)Input::post('new_password', '');
            $confirmPassword = (string)Input::post('confirm_password', '');

            $hashedPassword = $this->userService->getHashedPassword($userId);

            if ($hashedPassword === '' || !$this->userService->verifyPassword($currentPassword, $hashedPassword)) {
                $viewVariables[View::get_status_message('ERROR')] = 'Your current password is incorrect.';
            } elseif ($newPassword !== $confirmPassword) {
                $viewVariables[View::get_status_message('ERROR')] = 'Your passwords didn\'t match.';
            } elseif (!$this->userValidation->isPasswordValid($newPassword)) {
                $viewVariables[View::get_status_message('ERROR')] = 'Password is too weak.';
            } else {
                $newHashedPassword = $this->userService->hashPassword($newPassword);
                $this->userService->updatePassword($userId, $newHashedPassword);
                $viewVariables[View::get_status_message('SUCCESS')] = 'Password successfully updated.';
            }
        }

        echo View::render('account/password', 'Edit Password', $viewVariables);
    }

    public function logout(): void
    {
        $this->userSessionService->logout();
        redirect('/');
    }
}

