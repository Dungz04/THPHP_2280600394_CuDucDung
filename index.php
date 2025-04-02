<?php
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

if ($controllerName === 'ApiController' && isset($url[1])) {
    $apiControllerName = ucfirst($url[1]) . 'ApiController';

    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $id = $url[2] ?? null;
        $action = isset($url[2]) && $url[2] != '' ? $url[2] : 'index';

        switch ($method) {
            case 'GET':
                if ($action === 'search') {
                    $controller->search();
                } elseif ($id) {
                    $controller->show($id);
                } else {
                    $controller->index();
                }
                break;
            case 'POST':
                $controller->store();
                break;
            case 'PUT':
                if ($id) $controller->update($id);
                else {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID is required for PUT']);
                }
                break;
            case 'DELETE':
                if ($id) $controller->destroy($id);
                else {
                    http_response_code(400);
                    echo json_encode(['message' => 'ID is required for DELETE']);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Controller not found']);
        exit;
    }
}

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
