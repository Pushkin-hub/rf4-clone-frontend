<?php
require_once 'models/User.php';
require_once 'utils/JWT.php';
require_once 'utils/Response.php';
require_once 'utils/Validator.php';
require_once 'middleware/AuthMiddleware.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function getAllUsers() {
        $user = AuthMiddleware::requireAdmin();
        
        try {
            $users = $this->userModel->getAll();
            Response::success($users, 'Users retrieved successfully');
        } catch (Exception $e) {
            Response::error('Failed to retrieve users: ' . $e->getMessage(), 500);
        }
    }

    public function getUserById($id) {
        $currentUser = AuthMiddleware::authenticate();
        
        if ((!isset($currentUser['role']) || $currentUser['role'] !== 'admin') && $currentUser['user_id'] != $id) {
            Response::error('Access denied', 403);
        }
        
        try {
            $user = $this->userModel->getById($id);
            
            if (!$user) {
                Response::error('User not found', 404);
            }
            
            unset($user['password']);
            Response::success($user);
        } catch (Exception $e) {
            Response::error('Failed to retrieve user: ' . $e->getMessage(), 500);
        }
    }

    public function updateUser($id) {
        $currentUser = AuthMiddleware::authenticate();
        
        if ($currentUser['user_id'] != $id && (!isset($currentUser['role']) || $currentUser['role'] !== 'admin')) {
            Response::error('Access denied', 403);
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            Response::error('Invalid JSON data');
        }
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'username' => 'required|min:3|max:50',
            'email' => 'required|email'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        try {
            if ($this->userModel->emailExists($data['email'], $id)) {
                Response::error('Email already exists');
            }
            
            $updateData = [
                'username' => $data['username'],
                'email' => $data['email']
            ];
            
            if (isset($currentUser['role']) && $currentUser['role'] === 'admin' && isset($data['role'])) {
                $updateData['role'] = $data['role'];
            }
            
            $success = $this->userModel->update($id, $updateData);
            
            if ($success) {
                Response::success(null, 'User updated successfully');
            } else {
                Response::error('Failed to update user');
            }
        } catch (Exception $e) {
            Response::error('Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    public function deleteUser($id) {
        $currentUser = AuthMiddleware::requireAdmin();
        
        if ($currentUser['user_id'] == $id) {
            Response::error('Cannot delete your own account');
        }
        
        try {
            $success = $this->userModel->delete($id);
            
            if ($success) {
                Response::success(null, 'User deleted successfully');
            } else {
                Response::error('Failed to delete user');
            }
        } catch (Exception $e) {
            Response::error('Failed to delete user: ' . $e->getMessage(), 500);
        }
    }

    public function changePassword($id) {
        $currentUser = AuthMiddleware::authenticate();
        
        if ($currentUser['user_id'] != $id && (!isset($currentUser['role']) || $currentUser['role'] !== 'admin')) {
            Response::error('Access denied', 403);
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            Response::error('Invalid JSON data');
        }
        
        $validator = new Validator();
        
        if (isset($currentUser['role']) && $currentUser['role'] === 'admin' && $currentUser['user_id'] != $id) {
            $validation = $validator->validate($data, [
                'new_password' => 'required|min:6'
            ]);
        } else {
            $validation = $validator->validate($data, [
                'current_password' => 'required',
                'new_password' => 'required|min:6'
            ]);
        }
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        try {
            if (!isset($currentUser['role']) || $currentUser['role'] !== 'admin' || $currentUser['user_id'] == $id) {
                if (!$this->userModel->verifyPassword($id, $data['current_password'])) {
                    Response::error('Current password is incorrect');
                }
            }
            
            $success = $this->userModel->updatePassword($id, $data['new_password']);
            
            if ($success) {
                Response::success(null, 'Password changed successfully');
            } else {
                Response::error('Failed to change password');
            }
        } catch (Exception $e) {
            Response::error('Failed to change password: ' . $e->getMessage(), 500);
        }
    }

    public function uploadAvatar($id) {
        $currentUser = AuthMiddleware::authenticate();
        
        if ($currentUser['user_id'] != $id && (!isset($currentUser['role']) || $currentUser['role'] !== 'admin')) {
            Response::error('Access denied', 403);
        }
        
        if (!isset($_FILES['avatar'])) {
            Response::error('No avatar file provided');
        }
        
        try {
            $uploadHandler = new UploadHandler();
            $result = $uploadHandler->handleUserAvatarUpload($_FILES['avatar'], $id);
            
            if ($result['success']) {
                $this->userModel->updateAvatar($id, $result['file_path']);
                
                Response::success(['avatar_url' => $result['file_path']], 'Avatar uploaded successfully');
            } else {
                Response::error($result['error']);
            }
        } catch (Exception $e) {
            Response::error('Failed to upload avatar: ' . $e->getMessage(), 500);
        }
    }

    public function getUserOrders($id) {
        $currentUser = AuthMiddleware::authenticate();
        
        if ($currentUser['user_id'] != $id && (!isset($currentUser['role']) || $currentUser['role'] !== 'admin')) {
            Response::error('Access denied', 403);
        }
        
        try {
            $orderModel = new Order();
            $orders = $orderModel->getByUserId($id);
            Response::success($orders, 'User orders retrieved successfully');
        } catch (Exception $e) {
            Response::error('Failed to retrieve user orders: ' . $e->getMessage(), 500);
        }
    }

    public function updateProfile($id) {
        $currentUser = AuthMiddleware::authenticate();

        if ($currentUser['user_id'] != $id) {
            Response::error('Access denied', 403);
        }
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            Response::error('Invalid JSON data');
        }
        
        $validator = new Validator();
        $validation = $validator->validate($data, [
            'first_name' => 'max:50',
            'last_name' => 'max:50',
            'phone' => 'max:20',
            'address' => 'max:255'
        ]);
        
        if ($validation !== true) {
            Response::error($validation);
        }
        
        try {
            $success = $this->userModel->updateProfile($id, $data);
            
            if ($success) {
                Response::success(null, 'Profile updated successfully');
            } else {
                Response::error('Failed to update profile');
            }
        } catch (Exception $e) {
            Response::error('Failed to update profile: ' . $e->getMessage(), 500);
        }
    }
}