<?php
header('Content-Type: application/json');

require_once 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true); 

    if (!isset($data['username']) || !isset($data['password'])) {
        http_response_code(400); 
        echo json_encode(['message' => 'Username and password are required.']);
        exit;
    }

    $username = htmlspecialchars($data['username']);
    $password = $data['password'];

    try {
        $stmt = $db->prepare("SELECT password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            session_start();
            $_SESSION['username'] = $username;
            http_response_code(200);
            echo json_encode(['message' => 'Login successful!', 'username' => $username]);

        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid username or password.']);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Login failed: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>
