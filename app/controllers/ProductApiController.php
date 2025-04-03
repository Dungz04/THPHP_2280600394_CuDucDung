<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/utils/JWTHandler.php');

class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }

    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');

            // Lấy tham số từ query string
            $categoryId = $_GET['category_id'] ?? '';
            $sortPrice = $_GET['sort_price'] ?? '';

            // Lấy danh sách sản phẩm từ model
            $products = $this->productModel->getProducts($categoryId, $sortPrice);

            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }

        // header('Content-Type: application/json');
        // $products = $this->productModel->getProducts();
        // echo json_encode([
        //     'status' => 'success',
        //     'data' => $products
        // ]);
    }

    public function show($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            error_log("Đang tìm sản phẩm với ID: " . $id);
            $product = $this->productModel->getProductById($id);
            if ($product) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $product
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Product not found',
                    'debug_id' => $id
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    public function store()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');

            // Lấy dữ liệu từ POST thay vì JSON
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            // Xử lý file ảnh
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Không thể tải lên hình ảnh'
                    ]);
                    return;
                }
            }

            // Debug dữ liệu nhận được
            error_log("Dữ liệu nhận được: name=$name, description=$description, price=$price, category_id=$category_id, image=" . ($imagePath ?? 'không có'));

            // Kiểm tra dữ liệu hợp lệ
            $errors = [];
            if (empty($name)) $errors[] = "Tên sản phẩm không được để trống";
            if (empty($description)) $errors[] = "Mô tả không được để trống";
            if (!is_numeric($price) || $price <= 0) $errors[] = "Giá sản phẩm không hợp lệ";
            if (empty($category_id) || !is_numeric($category_id)) $errors[] = "Danh mục không hợp lệ";

            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $errors
                ]);
                return;
            }

            try {
                $result = $this->productModel->addProduct($name, $description, $price, $imagePath, $category_id);
                if ($result) {
                    http_response_code(201);
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Product created successfully'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào cơ sở dữ liệu'
                    ]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Lỗi server: ' . $e->getMessage()
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    public function update($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            error_log("Đang xử lý request cho ID: " . $id);

            // Lấy dữ liệu từ $_POST và $_FILES
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $imagePath = null;

            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));

            // Xử lý file ảnh nếu có
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
                $imagePath = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Không thể tải lên hình ảnh'
                    ]);
                    return;
                }
            }

            error_log("Dữ liệu sau xử lý: name=$name, description=$description, price=$price, category_id=$category_id, image=" . ($imagePath ?? 'không có'));

            // Kiểm tra dữ liệu hợp lệ
            $errors = [];
            if (empty($name)) $errors[] = "Tên sản phẩm không được để trống";
            if (empty($description)) $errors[] = "Mô tả không được để trống";
            if (!is_numeric($price) || $price <= 0) $errors[] = "Giá sản phẩm không hợp lệ";
            if (empty($category_id) || !is_numeric($category_id)) $errors[] = "Danh mục không hợp lệ";

            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $errors
                ]);
                return;
            }

            try {
                $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $imagePath);
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Product updated successfully'
                    ]);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Không tìm thấy sản phẩm hoặc cập nhật thất bại'
                    ]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Lỗi server: ' . $e->getMessage()
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    public function destroy($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $result = $this->productModel->deleteProduct($id);
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Product deleted successfully'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Product deletion failed'
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    public function search()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $query = $_GET['query'] ?? '';
            if (empty($query)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vui lòng nhập từ khóa tìm kiếm'
                ]);
                return;
            }

            $products = $this->productModel->searchProducts($query);
            echo json_encode([
                'status' => 'success',
                'data' => $products
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }
}
