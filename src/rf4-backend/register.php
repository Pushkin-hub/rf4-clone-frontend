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
        $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            http_response_code(409);
            echo json_encode(['message' => 'Username already taken.']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(['message' => 'Registration successful!']);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Registration failed: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed.']);
}
?>
