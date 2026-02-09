<?php
require_once 'config/Database.php';

class User {
    private $conn;
    private $table = "users";

    public $id;
    public $username;
    public $password;
    public $full_name;
    public $email;
    public $role;
    public $phone;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Create user
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET username = ?, password = ?, full_name = ?, 
                      email = ?, role = ?, phone = ?, status = ?";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bind_param("sssssss", 
            $this->username, 
            $hashed_password, 
            $this->full_name, 
            $this->email, 
            $this->role, 
            $this->phone, 
            $this->status
        );
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all users
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    // Read single user
    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update user
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET username = ?, full_name = ?, email = ?, 
                      role = ?, phone = ?, status = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", 
            $this->username, 
            $this->full_name, 
            $this->email, 
            $this->role, 
            $this->phone, 
            $this->status, 
            $this->id
        );
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete user
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login user
    public function login($username, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // Check if username exists
    public function usernameExists($username) {
        $query = "SELECT id FROM " . $this->table . " WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
?>