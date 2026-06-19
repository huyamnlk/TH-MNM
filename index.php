<?php
// Front controller: điều hướng request đến controller/action
session_start(); // Khởi động session

require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';

// Lấy và làm sạch URL từ query string
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định Controller và Action từ URL
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';
$action         = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Xử lý request đến API (VD: /Api/Product)
if ($controllerName === 'ApiController' && isset($url[1])) {
    $apiControllerName = ucfirst($url[1]) . 'ApiController';

    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();
        $method     = $_SERVER['REQUEST_METHOD'];
        $id         = $url[2] ?? null;

        // Ánh xạ phương thức HTTP sang action CRUD
        switch ($method) {
            case 'GET':    $action = $id ? 'show'    : 'index';  break;
            case 'POST':   $action = 'store';                    break;
            case 'PUT':    if ($id) $action = 'update';          break;
            case 'DELETE': if ($id) $action = 'destroy';         break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }

        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $id ? [$id] : []);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Action not found']);
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Controller not found']);
        exit;
    }
}

// Xử lý request thông thường (giao diện web)
if (file_exists('app/controllers/' . $controllerName . '.php')) {
    require_once 'app/controllers/' . $controllerName . '.php';
    $controller = new $controllerName();
} else {
    die('Controller not found');
}

if (method_exists($controller, $action)) {
    call_user_func_array([$controller, $action], array_slice($url, 2));
} else {
    die('Action not found');
}
?>