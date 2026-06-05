<?php
require_once 'app/config/database.php';
require_once 'app/models/AccountModel.php';
require_once 'app/helpers/SessionHelper.php';

class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    public function register()
    {
        $errors = [];
        $old = ['username' => '', 'fullname' => '', 'role' => 'user'];
        include_once 'app/views/account/register.php';
    }

    public function login()
    {
        $error = '';
        $oldUsername = '';
        include_once 'app/views/account/login.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /TH-MNM/Account/register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $fullName = trim($_POST['fullname'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmpassword'] ?? '';
        $role = trim($_POST['role'] ?? 'user');

        if (!in_array($role, ['admin', 'user'], true)) {
            $role = 'user';
        }

        $errors = [];
        $old = [
            'username' => $username,
            'fullname' => $fullName,
            'phone' => $phone,
            'role' => $role
        ];

        if ($username === '') {
            $errors[] = 'Vui lòng nhập username!';
        }
        if ($fullName === '') {
            $errors[] = 'Vui lòng nhập họ tên!';
        }
        if ($phone === '') {
            $errors[] = 'Vui lòng nhập số điện thoại!';
        } elseif (!preg_match('/^[0-9]{9,11}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (9-11 chữ số).';
        }

        if ($password === '') {
            $errors[] = 'Vui lòng nhập mật khẩu!';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Mật khẩu và xác nhận mật khẩu chưa khớp!';
        }

        if ($this->accountModel->getAccountByUsername($username)) {
            $errors[] = 'Tài khoản này đã được đăng ký!';
        }

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

    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /TH-MNM/Account/login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $oldUsername = $username;
        $error = '';

        if ($username === '' || $password === '') {
            $error = 'Vui lòng nhập đầy đủ tài khoản và mật khẩu!';
            include_once 'app/views/account/login.php';
            return;
        }

        $account = $this->accountModel->getAccountByUsername($username);

        if ($account && password_verify($password, $account->password)) {
            SessionHelper::start();
            $_SESSION['username'] = $account->username;
            $_SESSION['role'] = $account->role;
            $_SESSION['fullname'] = $account->fullname ?? $account->username;

            header('Location: /TH-MNM/Product/list');
            exit;
        }

        $error = $account ? 'Mật khẩu không đúng!' : 'Không tìm thấy tài khoản!';
        include_once 'app/views/account/login.php';
    }

    public function logout()
    {
        SessionHelper::start();
        unset($_SESSION['username'], $_SESSION['role'], $_SESSION['fullname']);
        header('Location: /TH-MNM/');
        exit;
    }
}
