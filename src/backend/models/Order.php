<?php
require_once 'config/database.php';

class Order {
    private $conn;
    private $table = 'orders';
    private $itemsTable = 'order_items';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT o.*, u.username, u.email 
                 FROM " . $this->table . " o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE user_id = :user_id 
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT o.*, u.username, u.email 
                 FROM " . $this->table . " o 
                 LEFT JOIN users u ON o.user_id = u.id 
                 WHERE o.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $this->conn->beginTransaction();
        
        try {
            // Создание заказа
            $query = "INSERT INTO " . $this->table . " 
                     SET user_id=:user_id, total_amount=:total_amount, 
                         shipping_address=:shipping_address, payment_method=:payment_method";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $data['user_id']);
            $stmt->bindParam(":total_amount", $data['total_amount']);
            $stmt->bindParam(":shipping_address", $data['shipping_address']);
            $stmt->bindParam(":payment_method", $data['payment_method']);
            $stmt->execute();
            
            $orderId = $this->conn->lastInsertId();
            
            // Добавление элементов заказа
            foreach ($data['items'] as $item) {
                $productQuery = "SELECT price FROM products WHERE id = :product_id";
                $productStmt = $this->conn->prepare($productQuery);
                $productStmt->bindParam(":product_id", $item['product_id']);
                $productStmt->execute();
                $product = $productStmt->fetch(PDO::FETCH_ASSOC);
                
                $itemQuery = "INSERT INTO " . $this->itemsTable . " 
                             SET order_id=:order_id, product_id=:product_id, 
                                 quantity=:quantity, price=:price";
                
                $itemStmt = $this->conn->prepare($itemQuery);
                $itemStmt->bindParam(":order_id", $orderId);
                $itemStmt->bindParam(":product_id", $item['product_id']);
                $itemStmt->bindParam(":quantity", $item['quantity']);
                $itemStmt->bindParam(":price", $product['price']);
                $itemStmt->execute();
            }
            
            $this->conn->commit();
            return $orderId;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, p.name, p.image_url 
                 FROM " . $this->itemsTable . " oi 
                 LEFT JOIN products p ON oi.product_id = p.id 
                 WHERE oi.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatistics() {
        $query = "SELECT 
                 COUNT(*) as total_orders,
                 SUM(total_amount) as total_revenue,
                 AVG(total_amount) as average_order_value,
                 COUNT(DISTINCT user_id) as unique_customers
                 FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSalesReport($startDate, $endDate) {
        $query = "SELECT 
                 DATE(created_at) as date,
                 COUNT(*) as orders_count,
                 SUM(total_amount) as daily_revenue,
                 AVG(total_amount) as avg_order_value
                 FROM " . $this->table . " 
                 WHERE created_at BETWEEN :start_date AND :end_date
                 GROUP BY DATE(created_at)
                 ORDER BY date";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":start_date", $startDate);
        $stmt->bindParam(":end_date", $endDate);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>