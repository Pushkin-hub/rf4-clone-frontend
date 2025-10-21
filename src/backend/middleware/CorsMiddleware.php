<?php
require_once '../utils/Response.php';

class CorsMiddleware {
    
    private $allowedOrigins;
    private $allowedMethods;
    private $allowedHeaders;
    private $allowCredentials;
    
    public function __construct($config = []) {
        $this->allowedOrigins = $config['allowed_origins'] ?? ['http://localhost:3000', 'http://127.0.0.1:3000'];
        $this->allowedMethods = $config['allowed_methods'] ?? ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];
        $this->allowedHeaders = $config['allowed_headers'] ?? ['Content-Type', 'Authorization', 'X-Requested-With'];
        $this->allowCredentials = $config['allow_credentials'] ?? true;
    }

    public function handle() {

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if (in_array($origin, $this->allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$origin}");
        } else if (in_array('*', $this->allowedOrigins)) {
            header("Access-Control-Allow-Origin: *");
        }

        if ($this->allowCredentials) {
            header("Access-Control-Allow-Credentials: true");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Methods: " . implode(', ', $this->allowedMethods));
            header("Access-Control-Allow-Headers: " . implode(', ', $this->allowedHeaders));
            header("Access-Control-Max-Age: 86400");

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                http_response_code(200);
                exit();
            }
        }

        header("Access-Control-Expose-Headers: Content-Length, X-Total-Count, X-Page-Count");
    }

    public static function setup($config = []) {
        $cors = new self($config);
        $cors->handle();
    }

    public static function middleware($config = []) {
        return function() use ($config) {
            self::setup($config);
        };
    }

    public function addAllowedOrigin($origin) {
        if (!in_array($origin, $this->allowedOrigins)) {
            $this->allowedOrigins[] = $origin;
        }
    }

    public function addAllowedMethod($method) {
        if (!in_array($method, $this->allowedMethods)) {
            $this->allowedMethods[] = $method;
        }
    }

    public function addAllowedHeader($header) {
        if (!in_array($header, $this->allowedHeaders)) {
            $this->allowedHeaders[] = $header;
        }
    }

    public function isOriginAllowed($origin) {
        return in_array($origin, $this->allowedOrigins) || in_array('*', $this->allowedOrigins);
    }

    public function getCorsHeaders($origin = null) {
        $headers = [];
        
        if (!$origin) {
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        }
        
        if ($this->isOriginAllowed($origin)) {
            $headers['Access-Control-Allow-Origin'] = $origin;
        } else if (in_array('*', $this->allowedOrigins)) {
            $headers['Access-Control-Allow-Origin'] = '*';
        }
        
        if ($this->allowCredentials) {
            $headers['Access-Control-Allow-Credentials'] = 'true';
        }
        
        return $headers;
    }

    public static function handleCorsError($message = 'CORS policy violation') {
        Response::error($message, 403);
    }
}
