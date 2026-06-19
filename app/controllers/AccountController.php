<?php
require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once('app/utils/JWTHandler.php');

// Xử lý đăng ký, đăng nhập, đăng xuất tài khoản
class AccountController
{
    private $accountModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db           = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler   = new JWTHandler();
    }

    // Hiển thị form đăng ký
    public function register()
    {
        $errors = [];
        $old    = ['username' => '', 'fullname' => '', 'role' => 'user'];
        include_once 'app/views/account/register.php';
    }

    // Hiển thị form đăng nhập
    public function login()
    {
        $error       = '';
        $oldUsername = '';
        include_once 'app/views/account/login.php';
    }

    // Lưu tài khoản đăng ký mới (nhận POST)
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /TH-MNM/Account/register');
            exit;
        }

        $username        = trim($_POST['username'] ?? '');
        $fullName        = trim($_POST['fullname'] ?? '');
        $phone           = trim($_POST['phone'] ?? '');
        $password        = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmpassword'] ?? '';
        $role            = trim($_POST['role'] ?? 'user');

        if (!in_array($role, ['admin', 'user'], true)) {
            $role = 'user';
        }

        $errors = [];
        $old    = ['username' => $username, 'fullname' => $fullName, 'phone' => $phone, 'role' => $role];

        // Kiểm tra dữ liệu đầu vào
        if ($username === '')   
              $errors[] = 'Vui lòng nhập username!';
        if ($fullName === '')      
           $errors[] = 'Vui lòng nhập họ tên!';
        if ($phone === '') {
            $errors[] = 'Vui lòng nhập số điện thoại!';
        } elseif (!preg_match('/^[0-9]{9,11}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (9-11 chữ số).';
        }
        if ($password === '')               
             $errors[] = 'Vui lòng nhập mật khẩu!';
        if ($password !== $confirmPassword)    
          $errors[] = 'Mật khẩu và xác nhận mật khẩu chưa khớp!';
        if ($this->accountModel->getAccountByUsername($username)) $errors[] = 'Tài khoản này đã được đăng ký!';

        if (!empty($errors)) {
            include_once 'app/views/account/register.php';
            return;
        }

        $result = $this->accountModel->save($username, $fullName, $phone, $password, $role);
        if (!$result) {
            $errors[] = 'Đăng ký thất bại, vui lòng thử lại.';
            include_once 'app/views/account/register.php';
            return;
        }

        $_SESSION['success_message'] = 'Đăng ký thành công. Vui lòng đăng nhập.';
        header('Location: /TH-MNM/Account/login');
        exit;
    }

    // Kiểm tra đăng nhập qua JSON, trả về JWT token nếu thành công
    public function checkLogin()
    {
        header('Content-Type: application/json');
        $data     = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $user     = $this->accountModel->getAccountByUserName($username);

        if ($user && password_verify($password, $user->password)) {
            SessionHelper::start();
            $_SESSION['username'] = $user->username;
            $_SESSION['role']     = $user->role;
            $_SESSION['fullname'] = $user->fullname ?? $user->username;

            $token = $this->jwtHandler->encode(['id' => $user->id, 'username' => $user->username]);
            echo json_encode(['token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }

    // Đăng xuất, xóa session và về trang chủ
    public function logout()
    {
        SessionHelper::start();
        unset($_SESSION['username'], $_SESSION['role'], $_SESSION['fullname']);
        header('Location: /TH-MNM/');
        exit;
    }
}
?>
