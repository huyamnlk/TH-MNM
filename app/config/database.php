<?php
class Database
{
    private static $host = '127.0.0.1';
    private static $dbName = 'my_store';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn === null) {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbName . ';charset=utf8mb4';
            self::$conn = new PDO($dsn, self::$username, self::$password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$conn;
    }
}
