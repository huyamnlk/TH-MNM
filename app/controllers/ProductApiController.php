<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

// API RESTful quản lý sản phẩm, hỗ trợ xác thực JWT
class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db           = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler   = new JWTHandler();
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

    // GET /Api/Product - Lấy danh sách sản phẩm (yêu cầu JWT)
    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            echo json_encode($this->productModel->getProducts());
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // GET /Api/Product/{id} - Lấy sản phẩm theo ID (công khai)
    public function show($id)
    {
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    }

    // POST /Api/Product - Thêm sản phẩm mới
    public function store()
    {
        header('Content-Type: application/json');
        $data        = json_decode(file_get_contents("php://input"), true);
        $name        = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price       = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;

        $result = $this->productModel->addProduct($name, $description, $price, $category_id, null);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully', 'id' => $result]);
        }
    }

    // PUT /Api/Product/{id} - Cập nhật sản phẩm
    public function update($id)
    {
        header('Content-Type: application/json');
        $data        = json_decode(file_get_contents("php://input"), true);
        $name        = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price       = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, null);
        if ($result === false) {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        } elseif (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            echo json_encode(['message' => 'Product updated successfully']);
        }
    }

    // DELETE /Api/Product/{id} - Xóa sản phẩm
    public function destroy($id)
    {
        header('Content-Type: application/json');
        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    }
}
?>