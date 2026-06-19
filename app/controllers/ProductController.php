<?php
require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';

// Quản lý sản phẩm: danh sách, CRUD, giỏ hàng, thanh toán
class ProductController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function index() { $this->list(); }

    // Danh sách sản phẩm, hỗ trợ tìm kiếm và lọc theo danh mục
    public function list()
    {
        $search          = trim($_GET['search'] ?? '');
        $category_filter = trim($_GET['category_filter'] ?? '');

        $catStmt    = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $sql    = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
                FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE 1=1";
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

        $stmt     = $this->conn->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();
        include 'app/views/product/list.php';
    }

    // Xem chi tiết sản phẩm
    public function show($id)
    {
        $id   = (int)$id;
        $stmt = $this->conn->prepare("SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
                                      FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE p.id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (!$product) die('Product not found');

        $catStmt    = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();
        include 'app/views/product/show.php';
    }

    // Chỉ cho phép admin thực hiện chức năng quản trị
    private function ensureAdmin()
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện chức năng quản trị này.';
            header('Location: /TH-MNM/Product/list');
            exit();
        }
    }

    // Thêm sản phẩm mới kèm upload ảnh
    public function add()
    {
        $this->ensureAdmin();
        $errors     = [];
        $catStmt    = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = $_POST['price'] ?? '';
            $category_id = ($_POST['category_id'] ?? '') !== '' ? (int)$_POST['category_id'] : null;
            $imageName   = '';

            if ($name === '') {
                $errors[] = 'Tên sản phẩm là bắt buộc.';
            } elseif (mb_strlen($name, 'UTF-8') < 3 || mb_strlen($name, 'UTF-8') > 100) {
                $errors[] = 'Tên sản phẩm phải có từ 3 đến 100 ký tự.';
            }
            if (!is_numeric($price) || (float)$price <= 0) {
                $errors[] = 'Giá phải là một số dương lớn hơn 0.';
            }

            // Xử lý upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $targetDir      = "public/images/";
                $imageName      = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $imageName;
                $fileType       = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowTypes     = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

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
                    'name'        => $name,
                    'description' => $description,
                    'price'       => (float)$price,
                    'image'       => $imageName !== '' ? $imageName : null,
                    'category_id' => $category_id
                ]);
                header('Location: /TH-MNM/Product/list');
                exit();
            }
        }
        include 'app/views/product/add.php';
    }

    // Chỉnh sửa sản phẩm, thay ảnh sẽ xóa ảnh cũ
    public function edit($id)
    {
        $this->ensureAdmin();
        $id         = (int)$id;
        $errors     = [];
        $catStmt    = $this->conn->query("SELECT id, name FROM category ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        $stmt = $this->conn->prepare("SELECT id, name, description, price, image, category_id FROM product WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (!$product) die('Product not found');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name        = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $price       = $_POST['price'] ?? '';
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
                $targetDir      = "public/images/";
                $newImageName   = time() . '_' . basename($_FILES["image"]["name"]);
                $targetFilePath = $targetDir . $newImageName;
                $fileType       = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
                $allowTypes     = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($fileType, $allowTypes)) {
                    $errors[] = 'Chỉ cho phép upload file JPG, JPEG, PNG, GIF, WEBP.';
                } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                    $errors[] = 'Có lỗi xảy ra khi upload ảnh.';
                } else {
                    if (!empty($product['image']) && file_exists("public/images/" . $product['image'])) {
                        unlink("public/images/" . $product['image']); // Xóa ảnh cũ
                    }
                    $imageName = $newImageName;
                }
            }

            if (empty($errors)) {
                $update = $this->conn->prepare("UPDATE product
                                                SET name = :name, description = :description, price = :price, image = :image, category_id = :category_id
                                                WHERE id = :id");
                $update->execute([
                    'id'          => $id,
                    'name'        => $name,
                    'description' => $description,
                    'price'       => (float)$price,
                    'image'       => $imageName,
                    'category_id' => $category_id
                ]);
                header('Location: /TH-MNM/Product/list');
                exit();
            }

            $product['name']        = $name;
            $product['description'] = $description;
            $product['price']       = $price;
            $product['category_id'] = $category_id;
            $product['image']       = $imageName;
        }
        include 'app/views/product/edit.php';
    }

    // Xóa sản phẩm và xóa ảnh vật lý trên server
    public function delete($id)
    {
        $this->ensureAdmin();
        $id   = (int)$id;
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

    // Thêm sản phẩm vào giỏ hàng trong Session
    public function addToCart($id)
    {
        $id   = (int)$id;
        $stmt = $this->conn->prepare("SELECT id, name, price, image FROM product WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch();
        if (!$product) die('Không tìm thấy sản phẩm.');

        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++; // Tăng số lượng nếu đã có
        } else {
            $_SESSION['cart'][$id] = [
                'id'       => (int)$product['id'],
                'name'     => $product['name'],
                'price'    => (float)$product['price'],
                'quantity' => 1,
                'image'    => $product['image']
            ];
        }

        $redirect = trim($_GET['redirect'] ?? '');
        header('Location: ' . ($redirect !== '' ? $redirect : ($_SERVER['HTTP_REFERER'] ?? '/TH-MNM/')));
        exit();
    }

    // Hiển thị trang giỏ hàng
    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /TH-MNM/Product/cart'); exit();
        }
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        $quantities = $_POST['quantity'] ?? [];
        foreach ($quantities as $productId => $qty) {
            $productId = (int)$productId;
            $qty       = (int)$qty;
            if (isset($_SESSION['cart'][$productId])) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$productId]); // Xóa nếu số lượng = 0
                } else {
                    $_SESSION['cart'][$productId]['quantity'] = $qty;
                }
            }
        }
        header('Location: /TH-MNM/Product/cart');
        exit();
    }

    // Xóa một sản phẩm khỏi giỏ hàng
    public function removeFromCart($id)
    {
        $id = (int)$id;
        if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
        header('Location: /TH-MNM/Product/cart');
        exit();
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        unset($_SESSION['cart']);
        header('Location: /TH-MNM/Product/cart');
        exit();
    }

    // Trang thanh toán - lọc các sản phẩm được chọn từ giỏ hàng
    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selected      = array_map('intval', (array)($_POST['selected_items'] ?? []));
            $cart          = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
            $checkoutItems = [];

            foreach ($selected as $productId) {
                if (isset($cart[$productId])) $checkoutItems[$productId] = $cart[$productId];
            }

            if (empty($checkoutItems)) { header('Location: /TH-MNM/Product/cart'); exit(); }
            $_SESSION['checkout_items'] = $checkoutItems;
        }

        $checkoutItems = isset($_SESSION['checkout_items']) && is_array($_SESSION['checkout_items'])
                       ? $_SESSION['checkout_items'] : [];
        if (empty($checkoutItems)) { header('Location: /TH-MNM/Product/cart'); exit(); }

        $cart = $checkoutItems;
        include 'app/views/product/checkout.php';
    }

    // Xử lý thanh toán và lưu đơn hàng (sử dụng Transaction)
    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /TH-MNM/Product/checkout'); exit();
        }
        if (!isset($_SESSION['checkout_items']) || empty($_SESSION['checkout_items'])) {
            die('Không có sản phẩm nào được chọn để thanh toán.');
        }

        $name    = trim($_POST['name'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        if ($name === '' || $phone === '' || $address === '') {
            die('Vui lòng nhập đầy đủ thông tin nhận hàng.');
        }

        $this->conn->beginTransaction(); // Bắt đầu Transaction
        try {
            // Tạo đơn hàng
            $stmt = $this->conn->prepare("INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)");
            $stmt->execute(['name' => $name, 'phone' => $phone, 'address' => $address]);
            $order_id = $this->conn->lastInsertId();

            // Lưu chi tiết từng sản phẩm trong đơn
            $checkoutItems = $_SESSION['checkout_items'];
            foreach ($checkoutItems as $product_id => $item) {
                $detailStmt = $this->conn->prepare(
                    "INSERT INTO order_details (order_id, product_id, quantity, price)
                     VALUES (:order_id, :product_id, :quantity, :price)"
                );
                $detailStmt->execute([
                    'order_id'   => $order_id,
                    'product_id' => (int)$product_id,
                    'quantity'   => (int)$item['quantity'],
                    'price'      => (float)$item['price']
                ]);
                if (isset($_SESSION['cart'][$product_id])) unset($_SESSION['cart'][$product_id]);
            }

            unset($_SESSION['checkout_items']);
            if (isset($_SESSION['cart']) && empty($_SESSION['cart'])) unset($_SESSION['cart']);

            $this->conn->commit(); // Xác nhận Transaction
            header('Location: /TH-MNM/Product/orderConfirmation');
            exit();
        } catch (Exception $e) {
            $this->conn->rollBack(); // Hủy Transaction nếu có lỗi
            die("Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage());
        }
    }

    // Trang xác nhận đặt hàng thành công
    public function orderConfirmation()
    {
        include 'app/views/product/order_confirmation.php';
    }
}
?>
