<?php
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            Response::error('Invalid JSON data');
        }
        
        if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            Response::error('All fields are required: username, email, password');
        }
        
        if ($this->userModel->emailExists($data['email'])) {
            Response::error('User with this email already exists');
        }
        
        $userData = [
            'username' => trim($data['username']),
            'email' => trim($data['email']),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => 'user'
        ];
        
        if ($this->userModel->create($userData)) {
            Response::success(['message' => 'User registered successfully'], 201);
        } else {
            Response::error('Failed to register user');
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            Response::error('Invalid JSON data');
        }
        
        if (!isset($data['email']) || !isset($data['password'])) {
            Response::error('Email and password are required');
        }
        
        $user = $this->userModel->getByEmail($data['email']);
        
        if (!$user || !password_verify($data['password'], $user['password'])) {
            Response::error('Invalid email or password');
        }
        
        $payload = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'exp' => time() + JWT_EXPIRY
        ];
        
        $token = JWT::encode($payload);
        
        Response::success([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ], 'Login successful');
    }

    public function me() {
        try {
            $user = AuthMiddleware::authenticate();
            $userData = $this->userModel->getById($user['user_id']);
            
            if (!$userData) {
                Response::error('User not found', 404);
            }
            
            Response::success($userData);
        } catch (Exception $e) {
            Response::error($e->getMessage(), 401);
        }
    }

    public function refreshToken() {
        Response::success(['message' => 'Token refresh endpoint']);
    }

    public function logout() {
        Response::success(['message' => 'Logout successful']);
    }
}