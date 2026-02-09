<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
if($_SESSION['user_role'] != 'admin') {
    $_SESSION['message'] = 'Access denied. Admin only.';
    $_SESSION['message_type'] = 'danger';
    header("Location: index.php?page=dashboard");
    exit();
}

$current_page = 'users';
$page_title = 'User Management';

require_once '../controllers/UserController.php';

$userController = new UserController();

// Get all users
$users = $userController->getAll();

// Handle delete
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $result = $userController->delete($_GET['id']);
    $_SESSION['message'] = $result['message'];
    $_SESSION['message_type'] = $result['success'] ? 'success' : 'danger';
    header("Location: index.php?page=users");
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
        <h1 class="h3 mb-0"><i class="fas fa-user-cog me-2"></i>User Management</h1>
        <a href="index.php?page=users&action=create" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i> Add New User
        </a>
    </div>
    
    <!-- User Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="userTable">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($users->num_rows > 0): ?>
                            <?php while($user = $users->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $user['username']; ?></strong>
                                </td>
                                <td><?php echo $user['full_name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        switch($user['role']) {
                                            case 'admin': echo 'danger'; break;
                                            case 'manager': echo 'warning'; break;
                                            default: echo 'secondary';
                                        }
                                    ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td><?php echo $user['phone'] ?: '-'; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $user['status']; ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="index.php?page=users&action=edit&id=<?php echo $user['id']; ?>" 
                                           class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($user['id'] != 1): // Don't show delete for admin ?>
                                        <button type="button" class="btn btn-outline-danger" 
                                                title="Delete"
                                                onclick="confirmDelete('user', 'index.php?page=users&action=delete&id=<?php echo $user['id']; ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No users found</h5>
                                    <a href="index.php?page=users&action=create" class="btn btn-primary">
                                        <i class="fas fa-user-plus me-1"></i> Add User
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>