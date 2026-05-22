<?php
require_once 'app/config/database.php';

class ProductController
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
        $search = trim($_GET['search'] ?? '');
        $category_filter = trim($_GET['category_filter'] ?? '');

        $catStmt = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $sql = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
                FROM product p
                LEFT JOIN category c ON p.category_id = c.id
                WHERE 1=1";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }

        if ($category_filter !== '') {
            $sql .= " AND p.category_id = :category_id";
            $params['category_id'] = (int)$category_filter;
        }

        $sql .= " ORDER BY p.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $id = (int)$id;

        $stmt = $this->conn->prepare("SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
                                      FROM product p
                                      LEFT JOIN category c ON p.category_id = c.id
                                      WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        if (!$product) {
            die('Product not found');
        }

        $catStmt = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        include 'app/views/product/show.php';
    }

    public function add()
    {
        $errors = [];
        $catStmt = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? '';
            $category_id = ($_POST['category_id'] ?? '') !== '' ? (int)$_POST['category_id'] : null;
            $imageName = '';

            if ($name === '') {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 3 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 3 đến 100 ký tự.';
            }

            if (!is_numeric($price) || (float)$price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = "public/images/";
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $imageName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($fileType, $allowTypes)) {
                    $errors[] = 'Chỉ cho phép upload file JPG, JPEG, PNG, GIF, WEBP.';
                } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $errors[] = 'Có lỗi xảy ra khi upload ảnh.';
                }
            }

            if (empty($errors)) {
                $stmt = $this->conn->prepare("INSERT INTO product (name, description, price, image, category_id)
                                              VALUES (:name, :description, :price, :image, :category_id)");
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'price' => (float)$price,
                    'image' => $imageName !== '' ? $imageName : null,
                    'category_id' => $category_id
                ]);

                header('Location: /TH-MNM/Product/list');
                exit();
            }
        }

        include 'app/views/product/add.php';
    }

    public function edit($id)
    {
        $id = (int)$id;
        $errors = [];

        $catStmt = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $stmt = $this->conn->prepare("SELECT id, name, description, price, image, category_id FROM product WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();

        if (!$product) {
            die('Product not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price = $_POST['price'] ?? '';
            $category_id = ($_POST['category_id'] ?? '') !== '' ? (int)$_POST['category_id'] : null;

            if ($name === '') {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 3 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 3 đến 100 ký tự.';
            }

            if (!is_numeric($price) || (float)$price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            $imageName = $product['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir = "public/images/";
                $newImageName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $newImageName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($fileType, $allowTypes)) {
                    $errors[] = 'Chỉ cho phép upload file JPG, JPEG, PNG, GIF, WEBP.';
                } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $errors[] = 'Có lỗi xảy ra khi upload ảnh.';
                } else {
                    if (!empty($product['image']) && file_exists("public/images/" . $product['image'])) {
                        unlink("public/images/" . $product['image']);
                    }
                    $imageName = $newImageName;
                }
            }

            if (empty($errors)) {
                $update = $this->conn->prepare("UPDATE product
                                                SET name = :name, description = :description, price = :price, image = :image, category_id = :category_id
                                                WHERE id = :id");
                $update->execute([
                    'id' => $id,
                    'name' => $name,
                    'description' => $description,
                    'price' => (float)$price,
                    'image' => $imageName,
                    'category_id' => $category_id
                ]);

                header('Location: /TH-MNM/Product/list');
                exit();
            }

            $product['name'] = $name;
            $product['description'] = $description;
            $product['price'] = $price;
            $product['category_id'] = $category_id;
            $product['image'] = $imageName;
        }

        include 'app/views/product/edit.php';
    }

    public function delete($id)
    {
        $id = (int)$id;

        $find = $this->conn->prepare("SELECT image FROM product WHERE id = :id");
        $find->execute(['id' => $id]);
        $product = $find->fetch();

        if ($product) {
            if (!empty($product['image']) && file_exists("public/images/" . $product['image'])) {
                unlink("public/images/" . $product['image']);
            }

            $delete = $this->conn->prepare("DELETE FROM product WHERE id = :id");
            $delete->execute(['id' => $id]);
        }

        header('Location: /TH-MNM/Product/list');
        exit();
    }
}
