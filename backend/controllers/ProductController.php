<?php
class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function getAllProducts() {
        try {
            $products = $this->productModel->getAll();
            Response::success($products);
        } catch (Exception $e) {
            Response::error('Failed to fetch products: ' . $e->getMessage());
        }
    }

    public function getProductById($id) {
        try {
            $product = $this->productModel->getById($id);
            
            if (!$product) {
                Response::error('Product not found', 404);
            }
            
            Response::success($product);
        } catch (Exception $e) {
            Response::error('Failed to fetch product: ' . $e->getMessage());
        }
    }

    public function getFeaturedProducts() {
        try {
            $products = $this->productModel->getFeatured();
            Response::success($products);
        } catch (Exception $e) {
            Response::error('Failed to fetch featured products: ' . $e->getMessage());
        }
    }

    public function getProductsByCategory($categoryId) {
        try {
            $products = $this->productModel->getByCategory($categoryId);
            Response::success($products);
        } catch (Exception $e) {
            Response::error('Failed to fetch products by category: ' . $e->getMessage());
        }
    }

    public function searchProducts() {
        try {
            $search = $_GET['search'] ?? '';
            $category = $_GET['category'] ?? '';
            $minPrice = $_GET['minPrice'] ?? 0;
            $maxPrice = $_GET['maxPrice'] ?? 999999;
            $page = $_GET['page'] ?? 1;
            $limit = $_GET['limit'] ?? 12;
            
            $result = $this->productModel->search($search, $category, $minPrice, $maxPrice, $page, $limit);
            Response::success($result);
        } catch (Exception $e) {
            Response::error('Failed to search products: ' . $e->getMessage());
        }
    }

    public function createProduct() {
        try {
            AuthMiddleware::requireAdmin();
            
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (!$data) {
                Response::error('Invalid JSON data');
            }
            
            $required = ['name', 'description', 'price', 'category_id', 'stock_quantity'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    Response::error("Field {$field} is required");
                }
            }
            
            $productData = [
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'category_id' => $data['category_id'],
                'stock_quantity' => $data['stock_quantity'],
                'image_url' => $data['image_url'] ?? '',
                'is_active' => $data['is_active'] ?? true
            ];
            
            $productId = $this->productModel->create($productData);
            
            if ($productId) {
                Response::success(['id' => $productId], 'Product created successfully', 201);
            } else {
                Response::error('Failed to create product');
            }
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function updateProduct($id) {
        try {
            AuthMiddleware::requireAdmin();
            
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (!$data) {
                Response::error('Invalid JSON data');
            }
            
            $success = $this->productModel->update($id, $data);
            
            if ($success) {
                Response::success(null, 'Product updated successfully');
            } else {
                Response::error('Failed to update product');
            }
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }

    public function deleteProduct($id) {
        try {
            AuthMiddleware::requireAdmin();
            
            $success = $this->productModel->delete($id);
            
            if ($success) {
                Response::success(null, 'Product deleted successfully');
            } else {
                Response::error('Failed to delete product');
            }
        } catch (Exception $e) {
            Response::error($e->getMessage());
        }
    }
}