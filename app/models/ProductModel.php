<?php
// Model xử lý dữ liệu sản phẩm (CRUD + validation, kèm tên danh mục)
class ProductModel
{
    private $db;

    public function __construct($db) { $this->db = $db; }

    // Lấy toàn bộ sản phẩm kèm tên danh mục
    public function getProducts()
    {
        $stmt = $this->db->query(
            "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
            FROM product p LEFT JOIN category c ON p.category_id = c.id ORDER BY p.id DESC"
        );
        return $stmt->fetchAll();
    }

    // Lấy một sản phẩm theo ID kèm tên danh mục
    public function getProductById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name AS category_name
            FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE p.id = :id"
        );
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch();
    }

    // Thêm sản phẩm mới, trả về ID hoặc mảng lỗi
    public function addProduct($name, $description, $price, $category_id, $image = null)
    {
        $errors = $this->validateProduct($name, $description, $price, $category_id);
        if (!empty($errors)) return $errors;

        $stmt = $this->db->prepare(
            "INSERT INTO product (name, description, price, image, category_id)
             VALUES (:name, :description, :price, :image, :category_id)"
        );
        $stmt->execute([
            'name' => $name, 'description' => $description,
            'price' => (float)$price, 'image' => $image,
            'category_id' => $category_id !== null ? (int)$category_id : null,
        ]);
        return $this->db->lastInsertId();
    }

    // Cập nhật sản phẩm, trả về true/false/mảng lỗi
    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        if (!$this->getProductById($id)) return false;

        $errors = $this->validateProduct($name, $description, $price, $category_id);
        if (!empty($errors)) return $errors;

        $stmt = $this->db->prepare(
            "UPDATE product SET name=:name, description=:description, price=:price, image=:image, category_id=:category_id WHERE id=:id"
        );
        $stmt->execute([
            'id' => (int)$id, 'name' => $name, 'description' => $description,
            'price' => (float)$price, 'image' => $image,
            'category_id' => $category_id !== null ? (int)$category_id : null,
        ]);
        return true;
    }

    // Xóa sản phẩm theo ID
    public function deleteProduct($id)
    {
        if (!$this->getProductById($id)) return false;
        $stmt = $this->db->prepare("DELETE FROM product WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }

    // Kiểm tra dữ liệu sản phẩm hợp lệ
    private function validateProduct($name, $description, $price, $category_id)
    {
        $errors = [];
        if (trim($name) === '') $errors[] = 'Tên sản phẩm là bắt buộc.';
        if (!is_numeric($price) || (float)$price <= 0) $errors[] = 'Giá phải là số dương.';
        if ($category_id !== null && !is_numeric($category_id)) $errors[] = 'category_id phải là số nguyên.';
        return $errors;
    }
}
?>
