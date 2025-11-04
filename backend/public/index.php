<?php
// Включение вывода ошибок для разработки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Базовые заголовки CORS
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Обработка preflight OPTIONS запросов
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Регистрация автозагрузчика классов
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
    
    // Логирование если класс не найден
    error_log("Class not found: " . $class_name);
});

// Функция для логгирования запросов
function logRequest($method, $path, $statusCode) {
    $logFile = '../logs/access_' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $logEntry = "[{$timestamp}] {$ip} {$method} {$path} {$statusCode} - {$userAgent}" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

try {
    // Подключение конфигурации
    if (!file_exists('../config/config.php')) {
        throw new Exception('Configuration file not found');
    }
    require_once '../config/config.php';

    // Подключение основных классов
    if (!file_exists('../routes/api.php')) {
        throw new Exception('Routes file not found');
    }
    require_once '../routes/api.php';

    // Логирование успешного запуска
    logRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], 200);

} catch (Exception $e) {
    // Логирование ошибки
    logRequest($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN', $_SERVER['REQUEST_URI'] ?? '/', 500);
    
    // Отправка JSON ошибки
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Server configuration error: ' . $e->getMessage(),
        'timestamp' => time()
    ]);
    exit;
}

// Обработка несуществующих маршрутов (fallback)
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        echo json_encode([
            'error' => true,
            'message' => 'Internal server error',
            'timestamp' => time()
        ]);
    }
});

// Final response if nothing else handled the request
http_response_code(404);
echo json_encode([
    'error' => true,
    'message' => 'Endpoint not found',
    'path' => $_SERVER['REQUEST_URI'] ?? '/',
    'timestamp' => time()
]);