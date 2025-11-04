<?php
require_once 'models/Order.php';
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'utils/Response.php';

class DashboardController {
    public function getStats() {
        AuthMiddleware::requireAdmin();
        
        $orderModel = new Order();
        $productModel = new Product();
        $userModel = new User();
        
        $stats = [
            'total_revenue' => $orderModel->getTotalRevenue(),
            'total_orders' => $orderModel->getTotalOrders(),
            'total_products' => $productModel->getTotalProducts(),
            'total_users' => $userModel->getTotalUsers(),
            'pending_orders' => $orderModel->getPendingOrdersCount(),
            'low_stock_products' => $productModel->getLowStockProductsCount()
        ];
        
        Response::success($stats);
    }

    public function getRecentActivity() {
        AuthMiddleware::requireAdmin();
        
        $orderModel = new Order();
        $userModel = new User();
        
        $activity = [
            'recent_orders' => $orderModel->getRecentOrders(10),
            'recent_users' => $userModel->getRecentUsers(5),
            'recent_reviews' => []
        ];
        
        Response::success($activity);
    }

    public function getSalesChart() {
        AuthMiddleware::requireAdmin();
        
        $period = $_GET['period'] ?? 'month';
        $orderModel = new Order();
        $chartData = $orderModel->getSalesChartData($period);
        
        Response::success($chartData);
    }

    public function getTopProducts() {
        AuthMiddleware::requireAdmin();
        
        $limit = $_GET['limit'] ?? 10;
        $productModel = new Product();
        $topProducts = $productModel->getTopSellingProducts($limit);
        
        Response::success($topProducts);
    }
}
