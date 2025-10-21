<?php
require_once 'models/Category.php';
require_once 'utils/Response.php';
require_once 'utils/Validator.php';

class CategoryController {
    public function getAllCategories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        Response::success($categories);
    }

    public function getCategoryById($id) {
        $categoryModel = new Category();
        $category = $categoryModel->getById($id);
        
        if (!$category) {
            Response::error('Category not found', 404);
        }
        
        Response::success($category);
    }

    public function getCategoryTree() {
        $categoryModel = new Category();
        $tree = $categoryModel->getTree();
        Response::success($tree);
    }

    public function createCategory() {
        AuthMiddleware::requireAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'name' => 'required|min:2|max:100',
            'description' => 'required|min:10',
            'parent_id' => 'integer'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        $categoryModel = new Category();
        $categoryId = $categoryModel->create($data);
        
        if ($categoryId) {
            Response::success(['id' => $categoryId], 'Category created successfully', 201);
        } else {
            Response::error('Failed to create category');
        }
    }

    public function updateCategory($id) {
        AuthMiddleware::requireAdmin();
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'name' => 'min:2|max:100',
            'description' => 'min:10'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        $categoryModel = new Category();
        $success = $categoryModel->update($id, $data);
        
        if ($success) {
            Response::success(null, 'Category updated successfully');
        } else {
            Response::error('Failed to update category');
        }
    }

    public function deleteCategory($id) {
        AuthMiddleware::requireAdmin();
        
        $categoryModel = new Category();
        $success = $categoryModel->delete($id);
        
        if ($success) {
            Response::success(null, 'Category deleted successfully');
        } else {
            Response::error('Failed to delete category');
        }
    }

    public function uploadImage($id) {
        AuthMiddleware::requireAdmin();
        
        if (!isset($_FILES['image'])) {
            Response::error('No image file provided');
        }
        
        $uploadHandler = new UploadHandler();
        $result = $uploadHandler->handleCategoryImageUpload($_FILES['image'], $id);
        
        if ($result['success']) {

            $categoryModel = new Category();
            $categoryModel->updateImage($id, $result['file_path']);
            
            Response::success(['image_url' => $result['file_path']], 'Image uploaded successfully');
        } else {
            Response::error($result['error']);
        }
    }
}
