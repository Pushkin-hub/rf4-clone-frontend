<?php
require_once 'models/User.php';
require_once 'utils/JWT.php';
require_once 'utils/Response.php';

class AuthController {
    public function register() {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
            Response::error('Все поля обязательны для заполнения');
        }
        
        $user = new User();
        $user->username = $data->username;
        $user->email = $data->email;
        $user->password = $data->password;
        
        if ($user->emailExists()) {
            Response::error('Пользователь с таким email уже существует');
        }
        
        if ($user->create()) {
            Response::success(null, 'Пользователь успешно зарегистрирован');
        } else {
            Response::error('Ошибка при регистрации пользователя');
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!isset($data->email) || !isset($data->password)) {
            Response::error('Email и пароль обязательны');
        }
        
        $user = new User();
        $user->email = $data->email;
        
        if (!$user->emailExists()) {
            Response::error('Неверный email или пароль');
        }
        
        if (password_verify($data->password, $user->password)) {
            $token = JWT::encode([
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'exp' => time() + (60 * 60 * 24) // 24 hours
            ]);
            
            Response::success([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email
                ]
            ], 'Вход выполнен успешно');
        } else {
            Response::error('Неверный email или пароль');
        }
    }

    public function me() {
        $headers = apache_request_headers();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::error('Токен не предоставлен', 401);
        }
        
        $token = $matches[1];
        $payload = JWT::decode($token);
        
        if (!$payload) {
            Response::error('Неверный токен', 401);
        }
        
        $user = new User();
        $userData = $user->getById($payload['user_id']);
        
        if (!$userData) {
            Response::error('Пользователь не найден', 404);
        }
        
        Response::success($userData);
    }
}
