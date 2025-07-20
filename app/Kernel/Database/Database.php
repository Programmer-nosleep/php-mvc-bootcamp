<?php
namespace App\Kernel\Database;

use Exception;

use PDO;
use PDOException;
use PDOStatement;

class Database 
{
  private static ?PDO $pdo = null;
  private static ?PDOStatement $stmt = null;

  /**
   * Connect to the database using PDO.
   *
   * This method establishes a connection to the database using the credentials
   * provided in the environment variables. It also configures PDO to throw
   * exceptions on error and to fetch results as associative arrays.
   *
   * @param array $db The database configuration.
   * @return void
   * @throws Exception If the database connection fails.
   */
  public static function connect(array $db) : void
  {
    try 
    {
      static::$pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD']
      );
      static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) 
    {
      throw new Exception("Database connection failed: " . $e->getMessage());
    }
  }

  /**
   * Prepare a SQL query for execution.
   *
   * @param string $sql The SQL query to prepare.
   * @param array $binds An array of values to bind to the query.
   * @return void
   */
  public static function query(string $sql, array $binds = []) : void
  {
    static::$stmt = static::$pdo->prepare($sql);
    // static::$stmt->execute($binds);
    foreach($binds as $key)
    {
      
    }
  }

  /**
   * Execute a prepared statement.
   *
   * @return bool True on success, false on failure.
   */
  public static function execute() : bool
  {
    return static::$stmt->execute();
  }

  /**
   * Fetch the first row from the result set.
   *
   * @return mixed The first row of the result set, or false if there are no more rows.
   */
  public static function first()
  {
    return static::$stmt->fetch();
  }

  /**
   * Fetch all rows from the result set.
   *
   * @return array An array containing all of the remaining rows in the result set.
   */
  public static function get() : array
  {
    return static::$stmt->fetchAll();
  }
}