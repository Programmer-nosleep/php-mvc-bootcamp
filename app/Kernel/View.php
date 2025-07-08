<?php
namespace App\Kernel;

use App\Exceptions\ViewNotFound; // Ensure this namespace is correct and the ViewNotFound class exists

final class View
{
    private const PATH = __DIR__ . '/../Templates/';
    private const FILE_EXTENSION = '.html.php';

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

        // Starts output buffering. All subsequent output will be captured, not directly printed.
        ob_start();

        if (self::is_view_exist($view)) {
            // Includes the view file. The output from this file will be captured by ob_start().
            include_once self::PATH . $view . self::FILE_EXTENSION;
        } else {
            // If the view is not found, clean and end the buffer, then throw an exception.
            ob_end_clean();
            // Re-enabling the ViewNotFound exception that was previously commented out.
            throw new ViewNotFound(sprintf('%s does not exist', $view . self::FILE_EXTENSION));
        }

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
}