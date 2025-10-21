<?php
require_once 'config/database.php';

class Product {
    private $conn;
    private $table = 'products';

    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $stock_quantity;
    public $image_url;
    public $created_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 SET name=:name, description=:description, price=:price, 
                     category=:category, stock_quantity=:stock_quantity, image_url=:image_url";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":stock_quantity", $data['stock_quantity']);
        $stmt->bindParam(":image_url", $data['image_url'] ?? null);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET ";
        $updates = [];
        
        if (isset($data['name'])) $updates[] = "name=:name";
        if (isset($data['description'])) $updates[] = "description=:description";
        if (isset($data['price'])) $updates[] = "price=:price";
        if (isset($data['category'])) $updates[] = "category=:category";
        if (isset($data['stock_quantity'])) $updates[] = "stock_quantity=:stock_quantity";
        if (isset($data['image_url'])) $updates[] = "image_url=:image_url";
        
        $query .= implode(", ", $updates) . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        
        if (isset($data['name'])) $stmt->bindParam(":name", $data['name']);
        if (isset($data['description'])) $stmt->bindParam(":description", $data['description']);
        if (isset($data['price'])) $stmt->bindParam(":price", $data['price']);
        if (isset($data['category'])) $stmt->bindParam(":category", $data['category']);
        if (isset($data['stock_quantity'])) $stmt->bindParam(":stock_quantity", $data['stock_quantity']);
        if (isset($data['image_url'])) $stmt->bindParam(":image_url", $data['image_url']);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function search($search, $category, $minPrice, $maxPrice, $page, $limit) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $countQuery = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $query .= " AND (name LIKE :search OR description LIKE :search)";
            $countQuery .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = "%$search%";
        }
        
        if (!empty($category)) {
            $query .= " AND category = :category";
            $countQuery .= " AND category = :category";
            $params[':category'] = $category;
        }
        
        $query .= " AND price BETWEEN :minPrice AND :maxPrice";
        $countQuery .= " AND price BETWEEN :minPrice AND :maxPrice";
        $params[':minPrice'] = $minPrice;
        $params[':maxPrice'] = $maxPrice;
        
        $query .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        
        // Получение общего количества
        $countStmt = $this->conn->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Получение данных
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'products' => $products,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ];
    }

    public function updateImage($id, $imageUrl) {
        $query = "UPDATE " . $this->table . " SET image_url = :image_url WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":image_url", $imageUrl);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function decreaseStock($productId, $quantity) {
        $query = "UPDATE " . $this->table . " SET stock_quantity = stock_quantity - :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":id", $productId);
        return $stmt->execute();
    }

    public function increaseStock($productId, $quantity) {
        $query = "UPDATE " . $this->table . " SET stock_quantity = stock_quantity + :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":id", $productId);
        return $stmt->execute();
    }
}
