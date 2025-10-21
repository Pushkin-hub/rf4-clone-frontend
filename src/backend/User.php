<?php
require_once 'config/database.php';

class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $avatar_url;
    public $first_name;
    public $last_name;
    public $phone;
    public $address;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT id, username, email, role, avatar_url, first_name, last_name, phone, address, created_at, updated_at 
                 FROM " . $this->table . " 
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT id, username, email, role, avatar_url, first_name, last_name, phone, address, created_at, updated_at 
                 FROM " . $this->table . " 
                 WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $params = [":email" => $email];
        
        if ($excludeId) {
            $query .= " AND id != :exclude_id";
            $params[":exclude_id"] = $excludeId;
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public function update($id, $data) {
        $allowedFields = ['username', 'email', 'role', 'first_name', 'last_name', 'phone', 'address', 'avatar_url'];
        $updates = [];
        $params = [":id" => $id];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $updates[] = "updated_at = NOW()";
        $query = "UPDATE " . $this->table . " SET " . implode(", ", $updates) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function verifyPassword($id, $password) {
        $query = "SELECT password FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user && password_verify($password, $user['password']);
    }

    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table . " SET password = :password, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function updateAvatar($id, $avatarUrl) {
        $query = "UPDATE " . $this->table . " SET avatar_url = :avatar_url, updated_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":avatar_url", $avatarUrl);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function updateProfile($id, $data) {
        $allowedFields = ['first_name', 'last_name', 'phone', 'address'];
        $updates = [];
        $params = [":id" => $id];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updates[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $updates[] = "updated_at = NOW()";
        $query = "UPDATE " . $this->table . " SET " . implode(", ", $updates) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getRecentUsers($limit = 5) {
        $query = "SELECT id, username, email, created_at 
                 FROM " . $this->table . " 
                 ORDER BY created_at DESC 
                 LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
