<?php
class Product {
    private $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = TRUE ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND is_active = TRUE LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFeatured() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_featured = TRUE AND is_active = TRUE ORDER BY created_at DESC LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCategory($categoryId) {
        $query = "SELECT * FROM " . $this->table . " WHERE category_id = :category_id AND is_active = TRUE ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $categoryId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($searchTerm, $category = '', $minPrice = 0, $maxPrice = 999999, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = TRUE";
        $countQuery = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_active = TRUE";
        
        $params = [];
        $countParams = [];
        
        if (!empty($searchTerm)) {
            $query .= " AND (name LIKE :search OR description LIKE :search)";
            $countQuery .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = "%$searchTerm%";
            $countParams[':search'] = "%$searchTerm%";
        }
        
        if (!empty($category)) {
            $query .= " AND category_id = :category";
            $countQuery .= " AND category_id = :category";
            $params[':category'] = $category;
            $countParams[':category'] = $category;
        }
        
        $query .= " AND price BETWEEN :minPrice AND :maxPrice";
        $countQuery .= " AND price BETWEEN :minPrice AND :maxPrice";
        $params[':minPrice'] = $minPrice;
        $params[':maxPrice'] = $maxPrice;
        $countParams[':minPrice'] = $minPrice;
        $countParams[':maxPrice'] = $maxPrice;
        
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        // Получение общего количества
        $countStmt = $this->conn->prepare($countQuery);
        foreach ($countParams as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $totalResult = $countStmt->fetch(PDO::FETCH_ASSOC);
        $total = $totalResult['total'];
        
        // Получение продуктов
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => ceil($total / $limit)
        ];
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 SET name=:name, description=:description, price=:price, 
                     category_id=:category_id, stock_quantity=:stock_quantity, 
                     image_url=:image_url, is_active=:is_active";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category_id", $data['category_id']);
        $stmt->bindParam(":stock_quantity", $data['stock_quantity']);
        $stmt->bindParam(":image_url", $data['image_url']);
        $stmt->bindParam(":is_active", $data['is_active']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET 
                 name=:name, description=:description, price=:price, 
                 category_id=:category_id, stock_quantity=:stock_quantity, 
                 image_url=:image_url, is_active=:is_active 
                 WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category_id", $data['category_id']);
        $stmt->bindParam(":stock_quantity", $data['stock_quantity']);
        $stmt->bindParam(":image_url", $data['image_url']);
        $stmt->bindParam(":is_active", $data['is_active']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
