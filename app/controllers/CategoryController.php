<?php
require_once 'app/config/database.php';

class CategoryController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $stmt = $this->conn->query("SELECT id, name, description FROM category ORDER BY id DESC");
        $categories = $stmt->fetchAll();
        include 'app/views/category/list.php';
    }

    public function show($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, description FROM category WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        $category = $stmt->fetch();

        if (!$category) {
            die('Category not found');
        }

        include 'app/views/category/show.php';
    }

    public function add()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên danh mục phải có từ 2 đến 100 ký tự.';
            }

            if (empty($errors)) {
                $stmt = $this->conn->prepare("INSERT INTO category (name, description) VALUES (:name, :description)");
                $stmt->execute([
                    'name' => $name,
                    'description' => $description
                ]);
                header('Location: /TH-MNM/Category/list');
                exit();
            }
        }

        include 'app/views/category/add.php';
    }

    public function edit($id)
    {
        $id = (int)$id;
        $errors = [];

        $stmt = $this->conn->prepare("SELECT id, name, description FROM category WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        if (!$category) {
            die('Category not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên danh mục phải có từ 2 đến 100 ký tự.';
            }

            if (empty($errors)) {
                $update = $this->conn->prepare("UPDATE category SET name = :name, description = :description WHERE id = :id");
                $update->execute([
                    'id' => $id,
                    'name' => $name,
                    'description' => $description
                ]);
                header('Location: /TH-MNM/Category/list');
                exit();
            }

            $category['name'] = $name;
            $category['description'] = $description;
        }

        include 'app/views/category/edit.php';
    }

    public function delete($id)
    {
        $id = (int)$id;

        $stmt = $this->conn->prepare("DELETE FROM category WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: /TH-MNM/Category/list');
        exit();
    }
}
