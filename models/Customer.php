<?php
require_once 'config/Database.php';

class Customer {
    private $conn;
    private $table = "customers";

    public $id;
    public $customer_code;
    public $customer_name;
    public $customer_type;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $province;
    public $postal_code;
    public $pic_name;
    public $pic_phone;
    public $status;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Generate customer code
    private function generateCustomerCode() {
        $prefix = "CUST";
        $year = date('Y');
        $month = date('m');
        
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $year, $month);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $sequence = str_pad($row['count'] + 1, 4, '0', STR_PAD_LEFT);
        return $prefix . $year . $month . $sequence;
    }

    // Create customer
    public function create() {
        // Generate customer code
        $this->customer_code = $this->generateCustomerCode();
        
        $query = "INSERT INTO " . $this->table . " 
                  SET customer_code = ?, customer_name = ?, customer_type = ?, 
                      email = ?, phone = ?, address = ?, city = ?, 
                      province = ?, postal_code = ?, pic_name = ?, 
                      pic_phone = ?, status = ?";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bind_param("ssssssssssss", 
            $this->customer_code,
            $this->customer_name,
            $this->customer_type,
            $this->email,
            $this->phone,
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
            $this->pic_name,
            $this->pic_phone,
            $this->status
        );
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read all customers
    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    // Read single customer
    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update customer
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET customer_name = ?, customer_type = ?, email = ?, 
                      phone = ?, address = ?, city = ?, province = ?, 
                      postal_code = ?, pic_name = ?, pic_phone = ?, 
                      status = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssssssi", 
            $this->customer_name,
            $this->customer_type,
            $this->email,
            $this->phone,
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
            $this->pic_name,
            $this->pic_phone,
            $this->status,
            $this->id
        );
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete customer
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Search customers
    public function search($keyword) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE customer_name LIKE ? OR customer_code LIKE ? 
                  OR email LIKE ? OR phone LIKE ? 
                  ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $search_keyword = "%{$keyword}%";
        $stmt->bind_param("ssss", 
            $search_keyword, 
            $search_keyword, 
            $search_keyword, 
            $search_keyword
        );
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get customer statistics
    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                    customer_type,
                    COUNT(*) as type_count
                  FROM " . $this->table . " 
                  GROUP BY customer_type";
        
        $result = $this->conn->query($query);
        return $result;
    }
}
?>