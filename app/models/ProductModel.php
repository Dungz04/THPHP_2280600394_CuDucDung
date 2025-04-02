<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProducts($categoryId = '', $sortPrice = '')
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id";
        
        // Thêm điều kiện lọc theo danh mục
        $conditions = [];
        if (!empty($categoryId)) {
            $conditions[] = "p.category_id = :category_id";
        }

        // Kết hợp điều kiện vào query
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Thêm sắp xếp theo giá
        if ($sortPrice === 'asc') {
            $query .= " ORDER BY p.price ASC";
        } elseif ($sortPrice === 'desc') {
            $query .= " ORDER BY p.price DESC";
        }

        $stmt = $this->conn->prepare($query);

        // Bind tham số nếu có
        if (!empty($categoryId)) {
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductById($id)
    {
        $query = "SELECT p.*, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addProduct($name, $description, $price, $image, $category_id)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (empty($image)) {
            $errors['image'] = 'Ảnh sản phẩm không được để trống';
        }
        if (count($errors) > 0) {
            return $errors;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, description, price, image, category_id) 
                  VALUES (:name, :description, :price, :image, :category_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category_id', $category_id);

        return $stmt->execute();
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, description = :description, price = :price, category_id = :category_id" .
            ($image !== null ? ", image = :image" : "") . " 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        if ($image !== null) {
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function searchProducts($query)
    {
        $query = "%" . $query . "%";
        $sql = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name 
                FROM " . $this->table_name . " p 
                LEFT JOIN category c ON p.category_id = c.id 
                WHERE p.name LIKE :query OR p.description LIKE :query";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':query', $query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}