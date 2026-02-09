<?php
require_once 'models/User.php';

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    // Handle user creation
    public function create($data) {
        // Validate required fields
        if(empty($data['username']) || empty($data['password']) || 
           empty($data['full_name']) || empty($data['email'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }

        // Check if username exists
        if($this->user->usernameExists($data['username'])) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        // Check if email exists
        if($this->user->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Set user properties
        $this->user->username = $data['username'];
        $this->user->password = $data['password'];
        $this->user->full_name = $data['full_name'];
        $this->user->email = $data['email'];
        $this->user->role = $data['role'] ?? 'staff';
        $this->user->phone = $data['phone'] ?? '';
        $this->user->status = $data['status'] ?? 'active';

        // Create user
        if($this->user->create()) {
            return ['success' => true, 'message' => 'User created successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to create user'];
        }
    }

    // Handle user update
    public function update($id, $data) {
        $this->user->id = $id;
        
        // Get current user data
        $currentUser = $this->user->readOne();
        
        // Check if username changed and exists
        if($data['username'] != $currentUser['username'] && 
           $this->user->usernameExists($data['username'])) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        // Check if email changed and exists
        if($data['email'] != $currentUser['email'] && 
           $this->user->emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Set user properties
        $this->user->username = $data['username'];
        $this->user->full_name = $data['full_name'];
        $this->user->email = $data['email'];
        $this->user->role = $data['role'];
        $this->user->phone = $data['phone'];
        $this->user->status = $data['status'];

        // Update user
        if($this->user->update()) {
            return ['success' => true, 'message' => 'User updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update user'];
        }
    }

    // Handle user deletion
    public function delete($id) {
        $this->user->id = $id;
        
        // Prevent deletion of admin user (id 1)
        if($id == 1) {
            return ['success' => false, 'message' => 'Cannot delete admin user'];
        }

        if($this->user->delete()) {
            return ['success' => true, 'message' => 'User deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete user'];
        }
    }

    // Get all users
    public function getAll() {
        return $this->user->readAll();
    }

    // Get single user
    public function getOne($id) {
        $this->user->id = $id;
        return $this->user->readOne();
    }
}
?>