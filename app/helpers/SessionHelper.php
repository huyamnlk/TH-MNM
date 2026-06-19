<?php
/**
 * Lớp trợ giúp quản lý Session (SessionHelper).
 * Cung cấp các phương thức tĩnh tiện ích để kiểm tra trạng thái đăng nhập,
 * xác định vai trò của người dùng và phân quyền trong hệ thống.
 */
class SessionHelper
{
    /**
     * Khởi động session nếu hệ thống chưa bắt đầu session nào.
     * Tránh lỗi khởi tạo session nhiều lần (Session already started).
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Kiểm tra xem người dùng hiện tại đã đăng nhập vào hệ thống hay chưa.
     *
     * @return bool True nếu đã đăng nhập, ngược lại là False.
     */
    public static function isLoggedIn()
    {
        self::start();
        return isset($_SESSION['username']);
    }

    /**
     * Kiểm tra xem người dùng hiện tại có quyền quản trị viên (Admin) hay không.
     *
     * @return bool True nếu người dùng đăng nhập có vai trò 'admin', ngược lại là False.
     */
    public static function isAdmin()
    {
        self::start();
        return isset($_SESSION['username']) &&
               isset($_SESSION['role']) &&
               $_SESSION['role'] === 'admin';
    }

    /**
     * Lấy vai trò hiện tại của người dùng trong Session.
     * Nếu chưa đăng nhập hoặc không có vai trò, mặc định trả về 'guest' (Khách).
     *
     * @return string Vai trò người dùng ('admin', 'user', 'guest')
     */
    public static function getRole()
    {
        self::start();
        return $_SESSION['role'] ?? 'guest';
    }
}
?>
