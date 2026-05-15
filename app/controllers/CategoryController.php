<?php
require_once 'app/models/CategoryModel.php';

class CategoryController
{
    private $categories = [];

    public function __construct()
    {
        session_start();
        if (isset($_SESSION['categories'])) {
            $this->categories = $_SESSION['categories'];
        } else {
            // Seed initial categories
            $this->categories = [
                new CategoryModel(1, 'Điện thoại', 'Các dòng điện thoại di động'),
                new CategoryModel(2, 'Laptop', 'Máy tính xách tay các loại')
            ];
            $_SESSION['categories'] = $this->categories;
        }
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $categories = $this->categories;
        include 'app/views/category/list.php';
    }

    public function add()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];

            if (empty($name)) {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên danh mục phải có từ 2 đến 100 ký tự.';
            }

            if (empty($errors)) {
                $id = 1;
                if (!empty($this->categories)) {
                    $maxId = 0;
                    foreach ($this->categories as $c) {
                        if ($c->getID() > $maxId) {
                            $maxId = $c->getID();
                        }
                    }
                    $id = $maxId + 1;
                }
                $category = new CategoryModel($id, $name, $description);
                $this->categories[] = $category;
                $_SESSION['categories'] = $this->categories;
                header('Location: /project1/Category/list');
                exit();
            }
        }
        include 'app/views/category/add.php';
    }

    public function edit($id)
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];

            if (empty($name)) {
                $errors[] = 'Tên danh mục là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 2 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên danh mục phải có từ 2 đến 100 ký tự.';
            }

            if (empty($errors)) {
                foreach ($this->categories as $key => $category) {
                    if ($category->getID() == $id) {
                        $this->categories[$key]->setName($name);
                        $this->categories[$key]->setDescription($description);
                        break;
                    }
                }
                $_SESSION['categories'] = $this->categories;
                header('Location: /project1/Category/list');
                exit();
            }
        }

        foreach ($this->categories as $category) {
            if ($category->getID() == $id) {
                include 'app/views/category/edit.php';
                return;
            }
        }
        die('Category not found');
    }

    public function delete($id)
    {
        foreach ($this->categories as $key => $category) {
            if ($category->getID() == $id) {
                unset($this->categories[$key]);
                break;
            }
        }
        $this->categories = array_values($this->categories);
        $_SESSION['categories'] = $this->categories;
        
        // Cũng nên cập nhật các sản phẩm có category_id này thành null hoặc xoá (đơn giản nhất là không làm gì lúc này)
        
        header('Location: /project1/Category/list');
        exit();
    }
}
?>
