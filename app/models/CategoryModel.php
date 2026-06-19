<?php
// Model xử lý dữ liệu danh mục sản phẩm (CRUD + validation)
class CategoryModel
{
    private $db;

    public function __construct($db) { $this->db = $db; }

    // Lấy toàn bộ danh mục
    public function getCategories()
    {
        $stmt = $this->db->query("SELECT id, name, description FROM category ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT id, name, description FROM category WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch();
    }

    // Thêm danh mục mới, trả về ID hoặc mảng lỗi
    public function addCategory($name, $description)
    {
        $errors = $this->validateCategory($name, $description);
        if (!empty($errors)) return $errors;

        $stmt = $this->db->prepare("INSERT INTO category (name, description) VALUES (:name, :description)");
        $stmt->execute(['name' => $name, 'description' => $description]);
        return $this->db->lastInsertId();
    }

    // Cập nhật danh mục, trả về true/false/mảng lỗi
    public function updateCategory($id, $name, $description)
    {
        if (!$this->getCategoryById($id)) return false;

        $errors = $this->validateCategory($name, $description);
        if (!empty($errors)) return $errors;

        $stmt = $this->db->prepare("UPDATE category SET name = :name, description = :description WHERE id = :id");
        $stmt->execute(['id' => (int)$id, 'name' => $name, 'description' => $description]);
        return true;
    }

    // Xóa danh mục (không cho xóa nếu còn sản phẩm liên kết)
    public function deleteCategory($id)
    {
        if (!$this->getCategoryById($id)) return false;

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM product WHERE category_id = :id");
        $stmt->execute(['id' => (int)$id]);
        if ((int)$stmt->fetchColumn() > 0) {
            return ['message' => 'Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này.'];
        }

        $stmt = $this->db->prepare("DELETE FROM category WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }

    // Kiểm tra dữ liệu danh mục hợp lệ
    private function validateCategory($name, $description)
    {
        $errors = [];
        if (trim($name) === '') $errors[] = 'Tên danh mục là bắt buộc.';
        return $errors;
    }
}
?>
