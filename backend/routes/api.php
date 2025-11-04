<?php
require_once '../config/config.php';

$router = new Router();

// ==================== AUTH ROUTES ====================
$router->post('/api/register', function() {
    $auth = new AuthController();
    $auth->register();
});

$router->post('/api/login', function() {
    $auth = new AuthController();
    $auth->login();
});

$router->get('/api/me', function() {
    $auth = new AuthController();
    $auth->me();
});

// ==================== PRODUCT ROUTES ====================
$router->get('/api/products', function() {
    $productController = new ProductController();
    $productController->getAllProducts();
});

$router->get('/api/products/featured', function() {
    $productController = new ProductController();
    $productController->getFeaturedProducts();
});

$router->get('/api/products/search', function() {
    $productController = new ProductController();
    $productController->searchProducts();
});

$router->get('/api/products/{id}', function($id) {
    $productController = new ProductController();
    $productController->getProductById($id);
});

// ==================== HEALTH CHECK ====================
$router->get('/api/health', function() {
    Response::success([
        'status' => 'OK',
        'timestamp' => time(),
        'service' => 'RF4 Clone API'
    ]);
});

$router->handleRequest();