<?php
require_once 'models/Order.php';
require_once 'models/Product.php';
require_once 'utils/JWT.php';
require_once 'utils/Response.php';
require_once 'utils/Validator.php';

class OrderController {
    public function getAllOrders() {
        $user = $this->authenticateAdmin();
        
        $orderModel = new Order();
        $orders = $orderModel->getAll();
        
        Response::success($orders, 'Orders retrieved successfully');
    }

    public function getUserOrders() {
        $user = $this->authenticate();
        
        $orderModel = new Order();
        $orders = $orderModel->getByUserId($user['user_id']);
        
        Response::success($orders, 'User orders retrieved successfully');
    }

    public function getOrderById($id) {
        $user = $this->authenticate();
        
        $orderModel = new Order();
        $order = $orderModel->getById($id);
        
        if (!$order) {
            Response::error('Order not found', 404);
        }
        
        // Пользователь может видеть только свои заказы, кроме администратора
        if ($order['user_id'] != $user['user_id'] && !$this->isAdmin($user)) {
            Response::error('Access denied', 403);
        }
        
        // Получение элементов заказа
        $orderItems = $orderModel->getOrderItems($id);
        $order['items'] = $orderItems;
        
        Response::success($order);
    }

    public function createOrder() {
        $user = $this->authenticate();
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'items' => 'required|array',
            'shipping_address' => 'required|min:10',
            'payment_method' => 'required'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        // Проверка наличия товаров и расчет общей суммы
        $totalAmount = 0;
        $productModel = new Product();
        
        foreach ($data['items'] as $item) {
            if (!isset($item['product_id']) || !isset($item['quantity']) || $item['quantity'] <= 0) {
                Response::error('Invalid order items');
            }
            
            $product = $productModel->getById($item['product_id']);
            if (!$product) {
                Response::error('Product not found: ' . $item['product_id']);
            }
            
            if ($product['stock_quantity'] < $item['quantity']) {
                Response::error('Insufficient stock for product: ' . $product['name']);
            }
            
            $totalAmount += $product['price'] * $item['quantity'];
        }
        
        // Создание заказа
        $orderModel = new Order();
        $orderId = $orderModel->create([
            'user_id' => $user['user_id'],
            'total_amount' => $totalAmount,
            'shipping_address' => $data['shipping_address'],
            'payment_method' => $data['payment_method'],
            'items' => $data['items']
        ]);
        
        if ($orderId) {
            // Обновление количества товаров на складе
            foreach ($data['items'] as $item) {
                $productModel->decreaseStock($item['product_id'], $item['quantity']);
            }
            
            Response::success(['order_id' => $orderId], 'Order created successfully', 201);
        } else {
            Response::error('Failed to create order');
        }
    }

    public function updateOrderStatus($id) {
        $this->authenticateAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        $orderModel = new Order();
        $success = $orderModel->updateStatus($id, $data['status']);
        
        if ($success) {
            Response::success(null, 'Order status updated successfully');
        } else {
            Response::error('Failed to update order status');
        }
    }

    public function cancelOrder($id) {
        $user = $this->authenticate();
        
        $orderModel = new Order();
        $order = $orderModel->getById($id);
        
        if (!$order) {
            Response::error('Order not found', 404);
        }
        
        // Пользователь может отменять только свои заказы
        if ($order['user_id'] != $user['user_id']) {
            Response::error('Access denied', 403);
        }
        
        // Можно отменять только заказы в статусе "pending"
        if ($order['status'] !== 'pending') {
            Response::error('Cannot cancel order with current status: ' . $order['status']);
        }
        
        $success = $orderModel->updateStatus($id, 'cancelled');
        
        if ($success) {
            // Возврат товаров на склад
            $orderItems = $orderModel->getOrderItems($id);
            $productModel = new Product();
            
            foreach ($orderItems as $item) {
                $productModel->increaseStock($item['product_id'], $item['quantity']);
            }
            
            Response::success(null, 'Order cancelled successfully');
        } else {
            Response::error('Failed to cancel order');
        }
    }

    public function getOrderStatistics() {
        $this->authenticateAdmin();
        
        $orderModel = new Order();
        $statistics = $orderModel->getStatistics();
        
        Response::success($statistics, 'Order statistics retrieved successfully');
    }

    public function getSalesReport() {
        $this->authenticateAdmin();
        
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        
        $orderModel = new Order();
        $report = $orderModel->getSalesReport($startDate, $endDate);
        
        Response::success($report, 'Sales report generated successfully');
    }

    private function authenticate() {
        $headers = apache_request_headers();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::error('Token not provided', 401);
        }
        
        $token = $matches[1];
        $payload = JWT::decode($token);
        
        if (!$payload) {
            Response::error('Invalid token', 401);
        }
        
        return $payload;
    }

    private function authenticateAdmin() {
        $user = $this->authenticate();
        
        // В реальном приложении здесь должна быть проверка роли администратора
        // if (!$this->isAdmin($user)) {
        //     Response::error('Admin access required', 403);
        // }
        
        return $user;
    }

    private function isAdmin($user) {
        // Заглушка для проверки администратора
        // В реальном приложении здесь должна быть проверка роли из базы данных
        return isset($user['role']) && $user['role'] === 'admin';
    }
}
