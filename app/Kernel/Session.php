<?php
declare(strict_types=1);
namespace App\Kernel;

/**
 * Session management class.
 * Handles starting, setting, getting, and destroying sessions.
 */
class Session
{
  public function __construct()
  {
    $this->initialize();
  }
  /**
   * Initializes the session if it is not already active.
   */
  private function initialize() : void
  {
    if (!$this->is_activated()) {
      session_start();
    }
  }

  /**
   * Sets a key-value pair in the session.
   * @param string $key The key to set.
   * @param mixed $value The value to store.
   */
  public static function set(string $key, $value) : void
  {
    $_SESSION[$key] = $value;
  }

  /**
   * Checks if a key exists in the session.
   * @param string $key The key to check.
   * @return bool True if the key exists, false otherwise.
   */
  public static function has(string $key) : bool
  {
    return isset($_SESSION[$key]);
  }

  /**
   * Gets a value from the session by key.
   * @param string $key The key to retrieve.
   * @return mixed|null The value from the session, or null if not found.
   */
  public static function get(string $key)
  {
    return $_SESSION[$key] ?? null;
  }

  /**
   * Sets the user ID in the session.
   * @param int $id The user's ID.
   */
  public static function setUserId(int $id): void
  {
    self::set('userId', $id);
  }

  /**
   * Gets the user ID from the session.
   * @return int|null The user's ID or null if not set.
   */
  public static function getUserId(): ?int
  {
    return self::get('userId');
  }

  /**
   * Destroys the current session, clearing all session data.
   */
  public function destroy() : void
  {
    if ($this->is_activated())
    {
      $_SESSION = [];
      session_unset();
      session_destroy();
    }
  }

  /**
   * Checks if the session is currently active.
   * @return bool True if the session is active, false otherwise.
   */
  private function is_activated() : bool
  {
    return session_status() === PHP_SESSION_ACTIVE;
  }
}