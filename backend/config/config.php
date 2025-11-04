<?php
// Базовые настройки приложения
define('APP_NAME', 'RF4 Clone API');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // production, development, testing

// Настройки базы данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'rf4_clone');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// JWT настройки
define('JWT_SECRET', 'rf4-clone-secret-key-2024');
define('JWT_EXPIRY', 86400); // 24 hours in seconds

// CORS настройки
define('CORS_ALLOWED_ORIGINS', [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'http://localhost:3001',
    'http://127.0.0.1:3001'
]);

// Настройки загрузки файлов
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Настройки API
define('API_RATE_LIMIT', 1000); // запросов в час
define('API_VERSION', 'v1');

// Настройки безопасности
define('BCRYPT_COST', 12);

// Включить вывод ошибок для разработки
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Установка временной зоны
date_default_timezone_set('Europe/Moscow');

// Автозагрузка классов
spl_autoload_register(function ($class_name) {
    $directories = [
        '../controllers/',
        '../models/',
        '../utils/',
        '../middleware/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Глобальная обработка CORS
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Глобальная обработка ошибок
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("Error {$errno}: {$errstr} in {$errfile} on line {$errline}");
});

set_exception_handler(function($exception) {
    error_log("Uncaught exception: " . $exception->getMessage());
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => APP_ENV === 'development' ? $exception->getMessage() : 'Internal server error'
    ]);
});