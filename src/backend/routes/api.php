<?php
require_once '../config/config.php';
require_once '../middleware/AuthMiddleware.php';
require_once '../middleware/CorsMiddleware.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/UserController.php';
require_once '../controllers/ProductController.php';
require_once '../controllers/OrderController.php';
require_once '../controllers/CategoryController.php';
require_once '../controllers/UploadController.php';

class Router {
    private $routes = [];
    private $requestMethod;
    private $requestPath;
    private $params = [];

    public function __construct() {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->requestPath = str_replace('/backend/public', '', $this->requestPath);

        if ($this->requestMethod === 'POST' || $this->requestMethod === 'PUT') {
            $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
            if (strpos($contentType, 'application/json') !== false) {
                $input = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $_POST = $input;
                }
            }
        }
    }

    public function addRoute($method, $path, $callback, $middleware = []) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function handleRequest() {
        try {

            CorsMiddleware::setup();
            
            $matchedRoute = null;
            
            foreach ($this->routes as $route) {
                if ($this->matchRoute($route)) {
                    $matchedRoute = $route;
                    break;
                }
            }

            if ($matchedRoute) {
                $this->executeRoute($matchedRoute);
            } else {
                Response::error('Route not found', 404);
            }
            
        } catch (Exception $e) {
            error_log("Router error: " . $e->getMessage());
            Response::error('Internal server error', 500);
        }
    }

    private function matchRoute($route) {
        if ($route['method'] !== $this->requestMethod && $route['method'] !== 'ANY') {
            return false;
        }

        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route['path']);
        $routePattern = str_replace('/', '\/', $routePattern);
        $routePattern = '/^' . $routePattern . '$/';
        
        if (preg_match($routePattern, $this->requestPath, $matches)) {
            array_shift($matches);
            $this->params = $matches;
            return true;
        }
        
        return false;
    }

    private function executeRoute($route) {
        try {

            if (!empty($route['middleware'])) {
                foreach ($route['middleware'] as $middleware) {
                    if ($middleware === 'auth') {
                        $user = AuthMiddleware::authenticate();
                        $this->params[] = $user;
                    } elseif ($middleware === 'admin') {
                        $user = AuthMiddleware::requireAdmin();
                        $this->params[] = $user;
                    }
                }
            }

            if (is_callable($route['callback'])) {
                call_user_func_array($route['callback'], $this->params);
            } elseif (is_string($route['callback'])) {
                $this->callControllerMethod($route['callback']);
            }
            
        } catch (Exception $e) {
            error_log("Route execution error: " . $e->getMessage());
            Response::error($e->getMessage(), 500);
        }
    }

    private function callControllerMethod($callbackString) {
        list($controllerName, $methodName) = explode('@', $callbackString);
        
        if (!class_exists($controllerName)) {
            throw new Exception("Controller {$controllerName} not found");
        }
        
        $controller = new $controllerName();
        
        if (!method_exists($controller, $methodName)) {
            throw new Exception("Method {$methodName} not found in controller {$controllerName}");
        }
        
        call_user_func_array([$controller, $methodName], $this->params);
    }

    public function get($path, $callback, $middleware = []) {
        $this->addRoute('GET', $path, $callback, $middleware);
    }

    public function post($path, $callback, $middleware = []) {
        $this->addRoute('POST', $path, $callback, $middleware);
    }

    public function put($path, $callback, $middleware = []) {
        $this->addRoute('PUT', $path, $callback, $middleware);
    }

    public function delete($path, $callback, $middleware = []) {
        $this->addRoute('DELETE', $path, $callback, $middleware);
    }

    public function patch($path, $callback, $middleware = []) {
        $this->addRoute('PATCH', $path, $callback, $middleware);
    }

    public function any($path, $callback, $middleware = []) {
        $this->addRoute('ANY', $path, $callback, $middleware);
    }
}

$router = new Router();

// ==================== AUTH ROUTES ====================
$router->post('/api/register', 'AuthController@register');
$router->post('/api/login', 'AuthController@login');
$router->post('/api/refresh-token', 'AuthController@refreshToken');
$router->post('/api/logout', 'AuthController@logout', ['auth']);
$router->get('/api/me', 'AuthController@me', ['auth']);

// ==================== USER ROUTES ====================
$router->get('/api/users', 'UserController@getAllUsers', ['auth', 'admin']);
$router->get('/api/users/{id}', 'UserController@getUserById', ['auth']);
$router->put('/api/users/{id}', 'UserController@updateUser', ['auth']);
$router->delete('/api/users/{id}', 'UserController@deleteUser', ['auth', 'admin']);
$router->post('/api/users/{id}/change-password', 'UserController@changePassword', ['auth']);
$router->post('/api/users/{id}/upload-avatar', 'UserController@uploadAvatar', ['auth']);
$router->get('/api/users/{id}/orders', 'UserController@getUserOrders', ['auth']);

// ==================== PRODUCT ROUTES ====================
$router->get('/api/products', 'ProductController@getAllProducts');
$router->get('/api/products/search', 'ProductController@searchProducts');
$router->get('/api/products/featured', 'ProductController@getFeaturedProducts');
$router->get('/api/products/category/{categoryId}', 'ProductController@getProductsByCategory');
$router->get('/api/products/{id}', 'ProductController@getProductById');
$router->post('/api/products', 'ProductController@createProduct', ['auth', 'admin']);
$router->put('/api/products/{id}', 'ProductController@updateProduct', ['auth', 'admin']);
$router->delete('/api/products/{id}', 'ProductController@deleteProduct', ['auth', 'admin']);
$router->post('/api/products/{id}/upload-image', 'ProductController@uploadImage', ['auth', 'admin']);
$router->post('/api/products/{id}/upload-gallery', 'ProductController@uploadGalleryImage', ['auth', 'admin']);
$router->delete('/api/products/{productId}/gallery/{imageId}', 'ProductController@deleteGalleryImage', ['auth', 'admin']);

// ==================== CATEGORY ROUTES ====================
$router->get('/api/categories', 'CategoryController@getAllCategories');
$router->get('/api/categories/tree', 'CategoryController@getCategoryTree');
$router->get('/api/categories/{id}', 'CategoryController@getCategoryById');
$router->post('/api/categories', 'CategoryController@createCategory', ['auth', 'admin']);
$router->put('/api/categories/{id}', 'CategoryController@updateCategory', ['auth', 'admin']);
$router->delete('/api/categories/{id}', 'CategoryController@deleteCategory', ['auth', 'admin']);
$router->post('/api/categories/{id}/upload-image', 'CategoryController@uploadImage', ['auth', 'admin']);

// ==================== ORDER ROUTES ====================
$router->get('/api/orders', 'OrderController@getAllOrders', ['auth', 'admin']);
$router->get('/api/orders/my', 'OrderController@getUserOrders', ['auth']);
$router->get('/api/orders/stats/statistics', 'OrderController@getOrderStatistics', ['auth', 'admin']);
$router->get('/api/orders/stats/sales-report', 'OrderController@getSalesReport', ['auth', 'admin']);
$router->get('/api/orders/{id}', 'OrderController@getOrderById', ['auth']);
$router->post('/api/orders', 'OrderController@createOrder', ['auth']);
$router->put('/api/orders/{id}/status', 'OrderController@updateOrderStatus', ['auth', 'admin']);
$router->post('/api/orders/{id}/cancel', 'OrderController@cancelOrder', ['auth']);
$router->post('/api/orders/{id}/process-payment', 'OrderController@processPayment', ['auth']);
$router->get('/api/orders/{id}/invoice', 'OrderController@generateInvoice', ['auth']);

// ==================== CART ROUTES ====================
$router->get('/api/cart', 'CartController@getCart', ['auth']);
$router->post('/api/cart/add', 'CartController@addToCart', ['auth']);
$router->put('/api/cart/update', 'CartController@updateCartItem', ['auth']);
$router->delete('/api/cart/remove/{productId}', 'CartController@removeFromCart', ['auth']);
$router->delete('/api/cart/clear', 'CartController@clearCart', ['auth']);
$router->get('/api/cart/count', 'CartController@getCartCount', ['auth']);

// ==================== REVIEW ROUTES ====================
$router->get('/api/products/{productId}/reviews', 'ReviewController@getProductReviews');
$router->get('/api/reviews/user', 'ReviewController@getUserReviews', ['auth']);
$router->post('/api/products/{productId}/reviews', 'ReviewController@createReview', ['auth']);
$router->put('/api/reviews/{id}', 'ReviewController@updateReview', ['auth']);
$router->delete('/api/reviews/{id}', 'ReviewController@deleteReview', ['auth']);
$router->get('/api/reviews/pending', 'ReviewController@getPendingReviews', ['auth', 'admin']);
$router->post('/api/reviews/{id}/approve', 'ReviewController@approveReview', ['auth', 'admin']);
$router->post('/api/reviews/{id}/reject', 'ReviewController@rejectReview', ['auth', 'admin']);

// ==================== UPLOAD ROUTES ====================
$router->post('/api/upload/image', 'UploadController@uploadImage', ['auth']);
$router->post('/api/upload/document', 'UploadController@uploadDocument', ['auth']);
$router->delete('/api/upload/{filename}', 'UploadController@deleteFile', ['auth']);
$router->get('/api/upload/temp/{filename}', 'UploadController@getTempFile');

// ==================== DASHBOARD ROUTES ====================
$router->get('/api/dashboard/stats', 'DashboardController@getStats', ['auth', 'admin']);
$router->get('/api/dashboard/recent-activity', 'DashboardController@getRecentActivity', ['auth', 'admin']);
$router->get('/api/dashboard/sales-chart', 'DashboardController@getSalesChart', ['auth', 'admin']);
$router->get('/api/dashboard/top-products', 'DashboardController@getTopProducts', ['auth', 'admin']);

// ==================== SETTINGS ROUTES ====================
$router->get('/api/settings', 'SettingsController@getSettings');
$router->put('/api/settings', 'SettingsController@updateSettings', ['auth', 'admin']);
$router->get('/api/settings/currency', 'SettingsController@getCurrencySettings');
$router->get('/api/settings/shipping', 'SettingsController@getShippingMethods');

// ==================== HEALTH CHECK ROUTES ====================
$router->get('/api/health', function() {
    Response::success([
        'status' => 'OK',
        'timestamp' => time(),
        'version' => APP_VERSION,
        'environment' => APP_ENV
    ]);
});

$router->get('/api/health/database', function() {
    try {
        $database = new Database();
        $info = $database->getDatabaseInfo();
        Response::success([
            'database' => 'Connected',
            'info' => $info
        ]);
    } catch (Exception $e) {
        Response::error('Database connection failed: ' . $e->getMessage(), 500);
    }
});

// ==================== FALLBACK ROUTES ====================
$router->any('/api/{any}', function() {
    Response::error('API endpoint not found', 404);
});

$router->any('/{any}', function() {
    Response::error('Route not found', 404);
});

$router->handleRequest();