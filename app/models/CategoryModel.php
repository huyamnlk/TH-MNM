<?php
class CategoryModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCategories()
    {
        $stmt = $this->db->query("SELECT id, name, description FROM category ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT id, name, description FROM category WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch();
    }

    public function addCategory($name, $description)
    {
        $errors = $this->validateCategory($name, $description);
        if (!empty($errors)) {
            return $errors;
        }

        $stmt = $this->db->prepare("INSERT INTO category (name, description) VALUES (:name, :description)");
        $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);

        return $this->db->lastInsertId();
    }

    public function updateCategory($id, $name, $description)
    {
        $category = $this->getCategoryById($id);
        if (!$category) {
            return false;
        }

        $errors = $this->validateCategory($name, $description);
        if (!empty($errors)) {
            return $errors;
        }

        $stmt = $this->db->prepare("UPDATE category SET name = :name, description = :description WHERE id = :id");
        $stmt->execute([
            'id' => (int)$id,
            'name' => $name,
            'description' => $description
        ]);

        return true;
    }

    public function deleteCategory($id)
    {
        $category = $this->getCategoryById($id);
        if (!$category) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM product WHERE category_id = :id");
        $stmt->execute(['id' => (int)$id]);
        $count = (int)$stmt->fetchColumn();
        if ($count > 0) {
            return ['message' => 'Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này.'];
        }

        $stmt = $this->db->prepare("DELETE FROM category WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }

    private function validateCategory($name, $description)
    {
        $errors = [];

        if (trim($name) === '') {
            $errors[] = 'Tên danh mục là bắt buộc.';
        }

        return $errors;
    }
}
?>

