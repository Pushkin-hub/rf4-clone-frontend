<?php
require_once 'models/Product.php';
require_once 'utils/JWT.php';
require_once 'utils/Response.php';
require_once 'utils/Validator.php';

class ProductController {
    public function getAllProducts() {
        $productModel = new Product();
        $products = $productModel->getAll();
        
        Response::success($products, 'Products retrieved successfully');
    }

    public function getProductById($id) {
        $productModel = new Product();
        $product = $productModel->getById($id);
        
        if (!$product) {
            Response::error('Product not found', 404);
        }
        
        Response::success($product);
    }

    public function createProduct() {
        $this->authenticateAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'name' => 'required|min:2|max:100',
            'description' => 'required|min:10',
            'price' => 'required|numeric|min:0',
            'category' => 'required',
            'stock_quantity' => 'required|integer|min:0'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        $productModel = new Product();
        $productId = $productModel->create($data);
        
        if ($productId) {
            Response::success(['id' => $productId], 'Product created successfully', 201);
        } else {
            Response::error('Failed to create product');
        }
    }

    public function updateProduct($id) {
        $this->authenticateAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'name' => 'min:2|max:100',
            'price' => 'numeric|min:0',
            'stock_quantity' => 'integer|min:0'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        $productModel = new Product();
        $success = $productModel->update($id, $data);
        
        if ($success) {
            Response::success(null, 'Product updated successfully');
        } else {
            Response::error('Failed to update product');
        }
    }

    public function deleteProduct($id) {
        $this->authenticateAdmin();
        
        $productModel = new Product();
        $success = $productModel->delete($id);
        
        if ($success) {
            Response::success(null, 'Product deleted successfully');
        } else {
            Response::error('Failed to delete product');
        }
    }

    public function searchProducts() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
        $maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : PHP_FLOAT_MAX;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        
        $productModel = new Product();
        $result = $productModel->search($search, $category, $minPrice, $maxPrice, $page, $limit);
        
        Response::success($result, 'Products search completed');
    }

    public function uploadImage($id) {
        $this->authenticateAdmin();
        
        if (!isset($_FILES['image'])) {
            Response::error('No image file provided');
        }
        
        $file = $_FILES['image'];
        
        // Проверка типа файла
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            Response::error('Only JPG, PNG and GIF images are allowed');
        }
        
        // Проверка размера файла (максимум 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            Response::error('Image size should be less than 5MB');
        }
        
        // Создание директории для загрузок, если её нет
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Генерация уникального имени файла
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = 'product_' . $id . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Обновление пути к изображению в базе данных
            $imageUrl = '/uploads/products/' . $fileName;
            $productModel = new Product();
            $productModel->updateImage($id, $imageUrl);
            
            Response::success(['image_url' => $imageUrl], 'Image uploaded successfully');
        } else {
            Response::error('Failed to upload image');
        }
    }

    private function authenticateAdmin() {
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
        
        // В реальном приложении здесь должна быть проверка роли администратора
        return $payload;
    }
}
