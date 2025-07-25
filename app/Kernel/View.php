<?php
namespace App\Kernel;

use App\Exceptions\ViewNotFound; // Ensure this namespace is correct and the ViewNotFound class exists

final class View
{
    private const PATH = __DIR__ . '/../Templates/';
    private const FILE_EXTENSION = '.html.php';

    private const ERROR_MESSAGE = [
        'FILENAME_NOT_FOUND' => '"%s" does not exist.',
    ];

    public const MESSAGE_KEY = [
        'SUCCESS' => 'success_message',
        'WARNING' => 'warning_message',
        'ERROR' => 'error_message',
    ];

    /**
     * Renders a view and returns its content as a string.
     *
     * @param string $view The name of the view file (without extension).
     * @param string $title The page title to be used in the view.
     * @param array $context Data to be extracted and made available in the view.
     * @return string The HTML content of the rendered view.
     * @throws ViewNotFound If the view file is not found.
     */
    public static function render(string $view, string $title, array $context = []): string
    {
        // Extracts the $context array into individual variables.
        // Example: ['name' => 'John'] will become $name = 'John';
        extract($context);

        ob_start();

        // Include header if it's not a partial view
        require self::PATH . 'partials/header.inc.html.php';


        $viewPath = self::PATH . $view . self::FILE_EXTENSION;

        if (self::is_view_exist($view)) {
            // Includes the view file. The output from this file will be captured by ob_start().
            include_once $viewPath;
        } else {
            // If the view is not found, clean the buffer and throw an exception.
            ob_end_clean();
            throw new ViewNotFound(sprintf(self::ERROR_MESSAGE['FILENAME_NOT_FOUND'], $view . self::FILE_EXTENSION));
        }

        // Include footer if it's not a partial view

        require self::PATH . 'partials/footer.inc.html.php';


        // Retrieves all content from the output buffer and ends it.
        // The captured content will be returned as a string.
        return ob_get_clean();
    }

    /**
     * Checks if a view file exists.
     *
     * @param string $filename The name of the view file (without extension).
     * @return bool True if the file exists, false otherwise.
     */
    private static function is_view_exist(string $filename): bool
    {
        $fullPath = self::PATH . $filename . self::FILE_EXTENSION;
        // --- IMPORTANT: This line will help you debug! ---
        error_log("Attempting to load view from: " . $fullPath);
        return is_file(self::PATH . $filename . self::FILE_EXTENSION);
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
        return self::MESSAGE_KEY[$status] ?? 'message';
    }
}