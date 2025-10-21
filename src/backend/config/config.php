<?php

define('APP_NAME', 'RF4 Clone API');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development');

define('DB_HOST', 'localhost');
define('DB_NAME', 'rf4_clone');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

define('JWT_SECRET', 'your-super-secret-jwt-key-change-in-production');
define('JWT_EXPIRY', 86400);

define('CORS_ALLOWED_ORIGINS', [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'http://localhost:3001',
    'http://127.0.0.1:3001'
]);

define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024);
define('UPLOAD_ALLOWED_TYPES', [
    'image/jpeg',
    'image/png', 
    'image/gif',
    'image/webp'
]);

define('API_RATE_LIMIT', 100);
define('API_VERSION', 'v1');

define('BCRYPT_COST', 12);

require_once '../middleware/CorsMiddleware.php';
CorsMiddleware::setup([
    'allowed_origins' => CORS_ALLOWED_ORIGINS,
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'X-API-Key'],
    'allow_credentials' => true
]);

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

date_default_timezone_set('Europe/Moscow');

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

function log_message($message, $level = 'INFO') {
    $logFile = '../logs/app_' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[{$timestamp}] {$level}: {$message}" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    log_message("Error {$errno}: {$errstr} in {$errfile} on line {$errline}", 'ERROR');
    
    if (APP_ENV === 'development') {
        error_log("Error {$errno}: {$errstr} in {$errfile} on line {$errline}");
    }
});

set_exception_handler(function($exception) {
    log_message("Uncaught exception: " . $exception->getMessage(), 'CRITICAL');
    
    if (APP_ENV === 'development') {
        error_log("Uncaught exception: " . $exception->getMessage());
    }
    
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal server error',
        'message' => APP_ENV === 'development' ? $exception->getMessage() : 'Something went wrong'
    ]);
});
