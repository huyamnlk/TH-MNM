<?php
// Quản lý kết nối CSDL MySQL theo kiểu Singleton (chỉ 1 kết nối duy nhất)
class Database
{
    private static $host = '127.0.0.1';  // Địa chỉ máy chủ DB
    private static $dbName = 'my_store'; // Tên cơ sở dữ liệu
    private static $username = 'root';   // Tài khoản DB
    private static $password = '';       // Mật khẩu DB
    private static $conn = null;         // Lưu thể hiện kết nối

    // Lấy kết nối PDO, tạo mới nếu chưa có
    public static function getConnection()
    {
        if (self::$conn === null) {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbName . ';charset=utf8mb4';
            self::$conn = new PDO($dsn, self::$username, self::$password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // Ném Exception khi lỗi SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Trả về mảng liên hợp
            ]);
        }
        return self::$conn;
    }
}
?>
