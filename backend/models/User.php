<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                 SET username=:username, email=:email, password=:password, role=:role";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $data['password']);
        $stmt->bindParam(":role", $data['role']);
        
        return $stmt->execute();
    }

    public function emailExists($email, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
        if ($excludeId) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        if ($excludeId) {
            $stmt->bindParam(":exclude_id", $excludeId);
        }
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function getByEmail($email) {
        $query = "SELECT id, username, email, password, role, created_at 
                 FROM " . $this->table . " 
                 WHERE email = :email LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT id, username, email, role, created_at 
                 FROM " . $this->table . " 
                 WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "SELECT id, username, email, role, created_at 
                 FROM " . $this->table . " 
                 ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET username=:username, email=:email WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $data['username']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $password) {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function verifyPassword($id, $password) {
        $user = $this->getById($id);
        if (!$user) return false;
        
        $query = "SELECT password FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return password_verify($password, $result['password']);
    }
}