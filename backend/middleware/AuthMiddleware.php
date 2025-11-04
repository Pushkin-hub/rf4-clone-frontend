<?php
require_once '../utils/JWT.php';
require_once '../utils/Response.php';

class AuthMiddleware {

    public static function authenticate() {
        $headers = apache_request_headers();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        if (empty($authHeader)) {
            $authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        }
        
        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::error('Authentication token required', 401);
        }
        
        $token = $matches[1];
        $payload = JWT::decode($token);
        
        if (!$payload) {
            Response::error('Invalid or expired token', 401);
        }

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            Response::error('Token has expired', 401);
        }
        
        return $payload;
    }

    public static function requireAdmin() {
        $user = self::authenticate();
        
        if (!isset($user['role']) || $user['role'] !== 'admin') {
            Response::error('Administrator access required', 403);
        }
        
        return $user;
    }

    public static function requireOwnershipOrAdmin($resourceUserId) {
        $user = self::authenticate();

        if (isset($user['role']) && $user['role'] === 'admin') {
            return $user;
        }

        if (!isset($user['user_id']) || $user['user_id'] != $resourceUserId) {
            Response::error('Access denied to this resource', 403);
        }
        
        return $user;
    }

    public static function requireRole($requiredRole) {
        $user = self::authenticate();
        
        if (!isset($user['role']) || $user['role'] !== $requiredRole) {
            Response::error("Role '{$requiredRole}' required", 403);
        }
        
        return $user;
    }

    public static function requireAnyRole($allowedRoles) {
        $user = self::authenticate();
        
        if (!isset($user['role']) || !in_array($user['role'], $allowedRoles)) {
            $rolesString = implode(', ', $allowedRoles);
            Response::error("One of the following roles required: {$rolesString}", 403);
        }
        
        return $user;
    }

    public static function optionalAuth() {
        $headers = apache_request_headers();
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
        
        if (empty($authHeader)) {
            $authHeader = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
        }
        
        if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return null;
        }
        
        $token = $matches[1];
        $payload = JWT::decode($token);
        
        if (!$payload || (isset($payload['exp']) && $payload['exp'] < time())) {
            return null;
        }
        
        return $payload;
    }

    public static function handle($callback, $options = []) {
        return function(...$params) use ($callback, $options) {
            try {

                if (isset($options['auth']) && $options['auth']) {
                    $user = self::authenticate();

                    if (isset($options['role'])) {
                        if (!isset($user['role']) || $user['role'] !== $options['role']) {
                            Response::error("Role '{$options['role']}' required", 403);
                        }
                    }

                    if (isset($options['anyRole'])) {
                        if (!isset($user['role']) || !in_array($user['role'], $options['anyRole'])) {
                            $rolesString = implode(', ', $options['anyRole']);
                            Response::error("One of the following roles required: {$rolesString}", 403);
                        }
                    }

                    $params[] = $user;
                }

                return call_user_func_array($callback, $params);
                
            } catch (Exception $e) {
                Response::error($e->getMessage(), 500);
            }
        };
    }
}
