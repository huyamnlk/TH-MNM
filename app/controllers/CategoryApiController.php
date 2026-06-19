<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

// API RESTful quản lý danh mục, yêu cầu xác thực JWT
class CategoryApiController
{
    private $categoryModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db            = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->jwtHandler    = new JWTHandler();
    }

    // Xác thực JWT từ header Authorization
    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $arr = explode(" ", $headers['Authorization']);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }

    // GET /Api/Category - Lấy danh sách danh mục
    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            echo json_encode($this->categoryModel->getCategories());
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // GET /Api/Category/{id} - Lấy danh mục theo ID
    public function show($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $category = $this->categoryModel->getCategoryById($id);
            if ($category) {
                echo json_encode($category);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Category not found']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // POST /Api/Category - Thêm danh mục mới
    public function store()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data        = json_decode(file_get_contents('php://input'), true);
            $name        = $data['name'] ?? '';
            $description = $data['description'] ?? '';

            $result = $this->categoryModel->addCategory($name, $description);
            if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } else {
                http_response_code(201);
                echo json_encode(['message' => 'Category created successfully', 'id' => $result]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // PUT /Api/Category/{id} - Cập nhật danh mục
    public function update($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data        = json_decode(file_get_contents('php://input'), true);
            $name        = $data['name'] ?? '';
            $description = $data['description'] ?? '';

            $result = $this->categoryModel->updateCategory($id, $name, $description);
            if ($result === false) {
                http_response_code(404);
                echo json_encode(['message' => 'Category not found']);
            } elseif (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } else {
                echo json_encode(['message' => 'Category updated successfully']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // DELETE /Api/Category/{id} - Xóa danh mục
    public function destroy($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $result = $this->categoryModel->deleteCategory($id);
            if ($result === false) {
                http_response_code(404);
                echo json_encode(['message' => 'Category not found']);
            } elseif (is_array($result)) {
                http_response_code(400); // Còn sản phẩm liên kết, không thể xóa
                echo json_encode($result);
            } else {
                echo json_encode(['message' => 'Category deleted successfully']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }
}
?>