<?php
namespace App\Controllers;

use App\Kernel\Input;
use App\Kernel\View;
use App\Service\User as UserService; 
use App\Models\User as UserModel;
use function App\redirect;

class AccountController
{ 
  private ?UserService $user_service = null;
    // Constants to define the message keys that will be used in the view.
    // This helps maintain consistency in message variable names throughout the application.
    private const MESSAGE_KEY = [
        'SUCCESS' => 'success_message',
        'WARNING' => 'warning_message',
        'ERROR' => 'error_message',
    ];

    /**
     * Constructor for the AccountController.
     * Uses constructor property promotion to inject the UserService.
     * A default instance is provided for convenience, but dependency injection
     * frameworks would typically manage this.
     *
     * @param UserService $user_service An instance of the UserService.
     */
    public function __construct(?UserService $user_service = null)
    {
        $this->user_service = $user_service ?? new UserService(new UserModel());
    }

    /**
     * Handles the sign-in page display and form submission (if using GET).
     */
    public function signin() : void
    {
        // Check if the signin form was submitted via GET method
        if (Input::get('signin_submit')) 
        {
            // For demonstration: echo the email from GET parameters
            // In a real application, you would process login credentials here.
            echo "Email from GET signin: " . Input::get('email');
        }
        
        // Render the sign-in view.
        // Assuming 'auth/signin' is the template file path.
        // The 'false' indicates it's a full page, not a partial.
        $render = View::render('auth/signin', 'Sign In', ); 
        echo $render;
    }

    /**
     * Handles the sign-up page display and form submission.
     * Processes POST requests for registration.
     */
    public function signup() : void
    {
        $view_vars = []; // Initialize an array to hold variables passed to the view (e.g., messages).

        // Check if the signup form was submitted via POST method
        if (Input::post('signup_submit'))
        {
            // Retrieve form data from POST request
            $fullname = Input::post('fullname');
            $email = Input::post('email');
            $password = Input::post('password');

            // Validate if all required fields are present and not empty.
            // Using trim() for email to handle cases with only whitespace.
            if (!empty($fullname) || !empty(trim($email)) || !empty($password))
            {
              // If all fields are present, proceed with email validation via UserService.
              if ($this->user_service->validate_email($email) && ($this->user_service->validate_password($password)))
              {
                if ($this->user_service->does_account_isexist($email))
                {
                  $view_vars[self::get_status_message('ERROR')] = 'An account with the same email address already exist.';
                } else {
                  /**
                   * If email is valid, proceed with user registration (e.g., save to database).
                   * 
                   * Example: $this->user_service->registerUser($fullname, $email, $password);
                   *  After successful registration, redirect the user to the home page. 
                   */  
                  $user = [
                      'fullname' => $fullname,
                      'email' => $email,
                      'password' => $password,
                  ];
  
                  if($this->user_service->create($user))
                  {
                      redirect('/?uri=home');
                      exit;
                  } else {
                      $view_vars[self::get_status_message('ERROR')] = 'An error while createing your account has occured. Please try again.';
                  }
                }

                // redirect('/?uri=home');
                // exit(); // Important: Stop script execution after redirection.   
              } 
              // If email validation fails.
              else 
              {
                  // Set an error message for invalid email.
                  $view_vars[self::get_status_message('ERROR')] = 'Invalid email address or password.';
              }
            } else {
              // Set an error message if any field is empty.
              // Using self::get_status_message() to get the correct key ('error_message').
              $view_vars[self::get_status_message('ERROR')] = 'All fields are required.';
            } 
        }

        // Render the sign-up view, passing any messages or other variables.
        // 'auth/signup' is the template file path.
        // 'false' indicates it's a full page.
        // $view_vars contains messages to be displayed.
        $render = View::render('auth/signup', 'Sign Up', $view_vars);
        echo $render;
    }
    
    /**
     * Handles the account edit page display.
     */
    public function edit() : void
    {
      // Render the account edit view.
      // Assuming 'auth/edit' is the template file path.
      // The 'false' indicates it's a full page.
      $render = View::render('auth/edit', 'Edit Account' ); 
      echo $render; 
    }

    /**
     * Retrieves the appropriate string key for a given message status type.
     * This method serves as a helper to get the actual variable name to be used
     * in the view context array (e.g., 'success_message', 'error_message') based on
     * an easily readable status key (e.g., 'SUCCESS', 'ERROR').
     *
     * @param string $status The desired message status key.
     * Expected values include: 'SUCCESS', 'WARNING', 'ERROR'.
     * @return string The corresponding string key for the message variable in the view.
     * If the given status key is not found in MESSAGE_KEY,
     * this method will return the string 'message' as a default value.
     */
    public static function get_status_message(string $status): string
    {
      // Returns the value from the MESSAGE_KEY array based on the provided $status key.
      // The null coalescing operator (??) is used to provide a default value of 'message'
      // if the $status key does not exist in MESSAGE_KEY, preventing an undefined array key error.
      return self::MESSAGE_KEY[$status] ?? 'message';
    }
}