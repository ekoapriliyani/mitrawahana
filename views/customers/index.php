<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_page = 'customers';
$page_title = 'Customer Management';

require_once '../controllers/CustomerController.php';

$customerController = new CustomerController();

// Handle search
$customers = isset($_GET['search']) && !empty($_GET['search']) 
    ? $customerController->search($_GET['search']) 
    : $customerController->getAll();

// Handle delete
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $result = $customerController->delete($_GET['id']);
    $_SESSION['message'] = $result['message'];
    $_SESSION['message_type'] = $result['success'] ? 'success' : 'danger';
    header("Location: index.php?page=customers");
    exit();
}

include '../layouts/header.php';

// Display message if exists
if(isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php 
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
endif; 
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-users me-2"></i>Customer Management</h1>
        <a href="index.php?page=customers&action=create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Customer
        </a>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="page" value="customers">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search customers by name, code, email, or phone..." 
                               value="<?php echo $_GET['search'] ?? ''; ?>">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if(isset($_GET['search'])): ?>
                            <a href="index.php?page=customers" class="btn btn-outline-danger">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="type">
                        <option value="">All Types</option>
                        <option value="individual" <?php echo (isset($_GET['type']) && $_GET['type'] == 'individual') ? 'selected' : ''; ?>>Individual</option>
                        <option value="company" <?php echo (isset($_GET['type']) && $_GET['type'] == 'company') ? 'selected' : ''; ?>>Company</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Customer Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="customerTable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Customer Name</th>
                            <th>Type</th>
                            <th>Contact Info</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($customers->num_rows > 0): ?>
                            <?php while($customer = $customers->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $customer['customer_code']; ?></strong>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo $customer['customer_name']; ?></div>
                                    <?php if($customer['pic_name']): ?>
                                        <small class="text-muted">PIC: <?php echo $customer['pic_name']; ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $customer['customer_type'] == 'company' ? 'info' : 'secondary'; ?>">
                                        <?php echo ucfirst($customer['customer_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div><i class="fas fa-phone me-1"></i> <?php echo $customer['phone']; ?></div>
                                    <div><i class="fas fa-envelope me-1"></i> <?php echo $customer['email']; ?></div>
                                </td>
                                <td>
                                    <div><?php echo $customer['city']; ?></div>
                                    <small class="text-muted"><?php echo $customer['province']; ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $customer['status']; ?>">
                                        <?php echo ucfirst($customer['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo date('d M Y', strtotime($customer['created_at'])); ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="index.php?page=customers&action=show&id=<?php echo $customer['id']; ?>" 
                                           class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?page=customers&action=edit&id=<?php echo $customer['id']; ?>" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Delete"
                                                onclick="confirmDelete('customer', 'index.php?page=customers&action=delete&id=<?php echo $customer['id']; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No customers found</h5>
                                    <p class="text-muted">Start by adding your first customer</p>
                                    <a href="index.php?page=customers&action=create" class="btn btn-primary">
                                        <i class="fas fa-plus me-1"></i> Add Customer
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($customers->num_rows > 0): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>