<?php
require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';

// Quản lý danh mục sản phẩm qua giao diện web
class CategoryController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function index() { $this->list(); }

    // Hiển thị danh sách tất cả danh mục
    public function list()
    {
        $stmt       = $this->conn->query("SELECT id, name, description FROM category ORDER BY id DESC");
        $categories = $stmt->fetchAll();
        include 'app/views/category/list.php';
    }

    // Xem chi tiết một danh mục
    public function show($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, description FROM category WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $category = $stmt->fetch();
        if (!$category) die('Category not found');
        include 'app/views/category/show.php';
    }

    // Chỉ cho phép admin thực hiện các thao tác quản trị
    private function ensureAdmin()
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện chức năng quản trị này.';
            header('Location: /TH-MNM/Category/list');
            exit();
        }
    }

    // Thêm danh mục mới (GET: hiển thị form, POST: lưu dữ liệu)
    public function add()
    {
        $this->ensureAdmin();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên danh mục phải có từ 2 đến 100 ký tự.';
            }

            if (empty($errors)) {
                $stmt = $this->conn->prepare("INSERT INTO category (name, description) VALUES (:name, :description)");
                $stmt->execute(['name' => $name, 'description' => $description]);
                header('Location: /TH-MNM/Category/list');
                exit();
            }
        }
        include 'app/views/category/add.php';
    }

    // Chỉnh sửa thông tin danh mục theo ID
    public function edit($id)
    {
        $this->ensureAdmin();
        $id     = (int)$id;
        $errors = [];

        $stmt = $this->conn->prepare("SELECT id, name, description FROM category WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();
        if (!$category) die('Category not found');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên danh mục phải có từ 2 đến 100 ký tự.';
            }

            if (empty($errors)) {
                $update = $this->conn->prepare("UPDATE category SET name = :name, description = :description WHERE id = :id");
                $update->execute(['id' => $id, 'name' => $name, 'description' => $description]);
                header('Location: /TH-MNM/Category/list');
                exit();
            }

            $category['name']        = $name;
            $category['description'] = $description;
        }
        include 'app/views/category/edit.php';
    }

    // Xóa danh mục (không cho xóa nếu còn sản phẩm liên kết)
    public function delete($id)
    {
        $this->ensureAdmin();
        $id = (int)$id;

        $check = $this->conn->prepare("SELECT COUNT(*) FROM product WHERE category_id = :id");
        $check->execute(['id' => $id]);
        if ((int)$check->fetchColumn() > 0) {
            $_SESSION['error_message'] = 'Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này.';
            header('Location: /TH-MNM/Category/list');
            exit();
        }

        $stmt = $this->conn->prepare("DELETE FROM category WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['success_message'] = 'Xóa danh mục thành công.';
        header('Location: /TH-MNM/Category/list');
        exit();
    }
}
?>
