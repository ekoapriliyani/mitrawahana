<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_page = 'customers';
$page_title = 'Edit Customer';

require_once '../controllers/CustomerController.php';

$customerController = new CustomerController();

// Get customer data
$id = $_GET['id'] ?? 0;
$customer = $customerController->getOne($id);

if(!$customer) {
    $_SESSION['message'] = 'Customer not found';
    $_SESSION['message_type'] = 'danger';
    header("Location: index.php?page=customers");
    exit();
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = $customerController->update($id, $_POST);
    
    $_SESSION['message'] = $result['message'];
    $_SESSION['message_type'] = $result['success'] ? 'success' : 'danger';
    
    if($result['success']) {
        header("Location: index.php?page=customers");
        exit();
    }
}

include '../layouts/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2"></i>Edit Customer
            <small class="text-muted"><?php echo $customer['customer_code']; ?></small>
        </h1>
        <a href="index.php?page=customers" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>
    
    <!-- Customer Form -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="" id="customerForm" onsubmit="return validateForm('customerForm')">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                               value="<?php echo $customer['customer_name']; ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="customer_type" class="form-label">Customer Type</label>
                        <select class="form-select" id="customer_type" name="customer_type">
                            <option value="individual" <?php echo ($customer['customer_type'] == 'individual') ? 'selected' : ''; ?>>Individual</option>
                            <option value="company" <?php echo ($customer['customer_type'] == 'company') ? 'selected' : ''; ?>>Company</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo $customer['email']; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="<?php echo $customer['phone']; ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo $customer['address']; ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" 
                               value="<?php echo $customer['city']; ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="province" class="form-label">Province</label>
                        <input type="text" class="form-control" id="province" name="province" 
                               value="<?php echo $customer['province']; ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code" 
                               value="<?php echo $customer['postal_code']; ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pic_name" class="form-label">PIC Name (Person in Charge)</label>
                        <input type="text" class="form-control" id="pic_name" name="pic_name" 
                               value="<?php echo $customer['pic_name']; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="pic_phone" class="form-label">PIC Phone Number</label>
                        <input type="text" class="form-control" id="pic_phone" name="pic_phone" 
                               value="<?php echo $customer['pic_phone']; ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" <?php echo ($customer['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($customer['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php?page=customers" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>