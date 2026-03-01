<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Kernel\Input;
use App\Kernel\View;
use App\Service\Contact as ContactService;
use App\Service\UserValidation;

use function App\site_name;

final class HomeController extends Controller
{
    private ContactService $contactService;
    private UserValidation $userValidation;

    public function __construct(?ContactService $contactService = null, ?UserValidation $userValidation = null)
    {
        parent::__construct();

        $this->contactService = $contactService ?? new ContactService();
        $this->userValidation = $userValidation ?? new UserValidation();
    }

    public function index(): void
    {
        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if ($this->isLoggedIn) {
            $viewVariables['name'] = $this->userSessionService->getName();
        }

        echo View::render('home/index', 'Homepage', $viewVariables);
    }

    public function about(): void
    {
        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
            'siteName' => site_name(),
            'contactEmail' => (string)($_ENV['ADMIN_EMAIL'] ?? ''),
        ];

        echo View::render('home/about', 'About', $viewVariables);
    }

    public function contact(): void
    {
        $viewVariables = [
            'isLoggedIn' => $this->isLoggedIn,
        ];

        if (Input::postExists('contact_submit')) {
            $name = Input::postTrimmed('name');
            $email = Input::postTrimmed('email');
            $message = Input::postTrimmed('message');
            $phoneNumber = Input::postTrimmed('phone_number');

            if ($name !== '' && $email !== '' && $message !== '') {
                if (!$this->userValidation->isEmailValid($email)) {
                    $viewVariables[View::get_status_message('ERROR')] = 'Email is not valid.';
                } else {
                    $isSuccess = $this->contactService->sendEmailToSiteOwner([
                        'name' => $name,
                        'email' => $email,
                        'message' => $message,
                        'phoneNumber' => $phoneNumber,
                    ]);

                    if ($isSuccess) {
                        $viewVariables[View::get_status_message('SUCCESS')] = 'Your message has been sent. We will get back to you.';
                    } else {
                        $viewVariables[View::get_status_message('ERROR')] = 'An error occurred while trying to reach us. Please try again later.';
                    }
                }
            } else {
                $viewVariables[View::get_status_message('ERROR')] = 'All fields are required.';
            }
        }

        echo View::render('home/contact', 'Contact Us', $viewVariables);
    }
}

