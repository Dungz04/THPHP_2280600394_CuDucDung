<?php
require_once 'app/config/database.php';
require_once 'app/models/CategoryModel.php';

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Hiển thị danh mục trong trang quản trị
    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // API trả về JSON danh sách danh mục
    public function getCategoriesJSON()
    {
        header("Content-Type: application/json; charset=UTF-8");

        $categories = $this->categoryModel->getCategories();

        // Trả về JSON đúng format
        echo json_encode(["categories" => $categories]);
        exit;
    }
}
?>
