<?php
require_once 'models/Customer.php';

class CustomerController {
    private $customer;

    public function __construct() {
        $this->customer = new Customer();
    }

    // Handle customer creation
    public function create($data) {
        // Validate required fields
        if(empty($data['customer_name'])) {
            return ['success' => false, 'message' => 'Customer name is required'];
        }

        // Set customer properties
        $this->customer->customer_name = $data['customer_name'];
        $this->customer->customer_type = $data['customer_type'] ?? 'individual';
        $this->customer->email = $data['email'] ?? '';
        $this->customer->phone = $data['phone'] ?? '';
        $this->customer->address = $data['address'] ?? '';
        $this->customer->city = $data['city'] ?? '';
        $this->customer->province = $data['province'] ?? '';
        $this->customer->postal_code = $data['postal_code'] ?? '';
        $this->customer->pic_name = $data['pic_name'] ?? '';
        $this->customer->pic_phone = $data['pic_phone'] ?? '';
        $this->customer->status = $data['status'] ?? 'active';

        // Create customer
        if($this->customer->create()) {
            return ['success' => true, 'message' => 'Customer created successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to create customer'];
        }
    }

    // Handle customer update
    public function update($id, $data) {
        $this->customer->id = $id;
        
        // Set customer properties
        $this->customer->customer_name = $data['customer_name'];
        $this->customer->customer_type = $data['customer_type'];
        $this->customer->email = $data['email'];
        $this->customer->phone = $data['phone'];
        $this->customer->address = $data['address'];
        $this->customer->city = $data['city'];
        $this->customer->province = $data['province'];
        $this->customer->postal_code = $data['postal_code'];
        $this->customer->pic_name = $data['pic_name'];
        $this->customer->pic_phone = $data['pic_phone'];
        $this->customer->status = $data['status'];

        // Update customer
        if($this->customer->update()) {
            return ['success' => true, 'message' => 'Customer updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update customer'];
        }
    }

    // Handle customer deletion
    public function delete($id) {
        $this->customer->id = $id;

        if($this->customer->delete()) {
            return ['success' => true, 'message' => 'Customer deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete customer'];
        }
    }

    // Get all customers
    public function getAll() {
        return $this->customer->readAll();
    }

    // Get single customer
    public function getOne($id) {
        $this->customer->id = $id;
        return $this->customer->readOne();
    }

    // Search customers
    public function search($keyword) {
        return $this->customer->search($keyword);
    }

    // Get customer statistics
    public function getStats() {
        return $this->customer->getStats();
    }
}
?>