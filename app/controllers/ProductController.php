<?php
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
class ProductController
{
private $products = [];
public function __construct()
{
// Giả sử chúng ta lưu trữ sản phẩm trong session để giữ lại khi làm mới trang
session_start();
if (isset($_SESSION['products'])) {
$this->products = $_SESSION['products'];
}
}
public function index()
{
$this->list();
}
    public function list()
    {
        // Lấy danh sách category để hiển thị tên danh mục và cho bộ lọc
        $categories = [];
        if (isset($_SESSION['categories'])) {
            $categories = $_SESSION['categories'];
        }

        // Xử lý tìm kiếm và lọc
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_filter = isset($_GET['category_filter']) ? $_GET['category_filter'] : '';
        
        $products = [];
        foreach ($this->products as $p) {
            $matchSearch = true;
            $matchCategory = true;
            
            if ($search !== '') {
                $nameMatch = mb_stripos($p->getName(), $search, 0, 'UTF-8') !== false;
                $descMatch = mb_stripos($p->getDescription(), $search, 0, 'UTF-8') !== false;
                if (!$nameMatch && !$descMatch) {
                    $matchSearch = false;
                }
            }
            
            if ($category_filter !== '') {
                if ($p->getCategoryID() != $category_filter) {
                    $matchCategory = false;
                }
            }
            
            if ($matchSearch && $matchCategory) {
                $products[] = $p;
            }
        }
        
        include 'app/views/product/list.php';
    }
    public function add()
    {
        // Lấy danh sách danh mục để hiển thị trong dropdown
        $categories = isset($_SESSION['categories']) ? $_SESSION['categories'] : [];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
            $imageName = '';

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "public/images/";
                // Tạo tên file duy nhất để tránh trùng lặp
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $imageName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                
                // Kiểm tra định dạng
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
                if (in_array($fileType, $allowTypes)) {
                    // Upload file vào thư mục
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                        $errors[] = 'Có lỗi xảy ra khi upload ảnh.';
                    }
                } else {
                    $errors[] = 'Chỉ cho phép upload file JPG, JPEG, PNG, GIF, WEBP.';
                }
            }

            // Kiểm tra tên sản phẩm
            if (empty($name)) {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 3 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 3 đến 100 ký tự.';
            }

            // Kiểm tra giá
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            if (empty($errors)) {
                $id = 1;
                if (!empty($this->products)) {
                    $maxId = 0;
                    foreach ($this->products as $p) {
                        if ($p->getID() > $maxId) {
                            $maxId = $p->getID();
                        }
                    }
                    $id = $maxId + 1;
                }
                $product = new ProductModel($id, $name, $description, $price, $imageName, $category_id);
                $this->products[] = $product;
                $_SESSION['products'] = $this->products;
                header('Location: /project1/Product/list');
                exit();
            }
        }
        include 'app/views/product/add.php';
    }

    public function edit($id)
    {
        // Lấy danh sách danh mục
        $categories = isset($_SESSION['categories']) ? $_SESSION['categories'] : [];
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;

            // Xử lý upload ảnh (nếu có)
            $imageName = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "public/images/";
                $imageName = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $imageName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                
                $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'webp');
                if (in_array($fileType, $allowTypes)) {
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                        $errors[] = 'Có lỗi xảy ra khi upload ảnh.';
                    }
                } else {
                    $errors[] = 'Chỉ cho phép upload file JPG, JPEG, PNG, GIF, WEBP.';
                }
            }

            // Kiểm tra tên sản phẩm
            if (empty($name)) {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 3 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 3 đến 100 ký tự.';
            }

            // Kiểm tra giá
            if (!is_numeric($price) || $price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            if (empty($errors)) {
                foreach ($this->products as $key => $product) {
                    if ($product->getID() == $id) {
                        $this->products[$key]->setName($name);
                        $this->products[$key]->setDescription($description);
                        $this->products[$key]->setPrice($price);
                        $this->products[$key]->setCategoryID($category_id);
                        if ($imageName != '') {
                            // Cập nhật ảnh mới, có thể xóa ảnh cũ nếu muốn
                            $oldImage = $this->products[$key]->getImage();
                            if ($oldImage && file_exists("public/images/" . $oldImage)) {
                                unlink("public/images/" . $oldImage);
                            }
                            $this->products[$key]->setImage($imageName);
                        }
                        break;
                    }
                }
                $_SESSION['products'] = $this->products;
                header('Location: /project1/Product/list');
                exit();
            }
        }
foreach ($this->products as $product) {
if ($product->getID() == $id) {
include 'app/views/product/edit.php';
return;
}
}
die('Product not found');
}
public function delete($id)
{
foreach ($this->products as $key => $product) {
if ($product->getID() == $id) {
unset($this->products[$key]);
break;
}
}
$this->products = array_values($this->products);
$_SESSION['products'] = $this->products;
header('Location: /project1/Product/list');
exit();
}
}
?>