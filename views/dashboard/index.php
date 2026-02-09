<?php
// Start session and check login
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_page = 'dashboard';
$page_title = 'Dashboard';

// Include models and controllers
require_once '../models/Customer.php';
require_once '../models/User.php';

// Get statistics
$customerModel = new Customer();
$userModel = new User();

$customerStats = $customerModel->getStats();
$totalCustomers = 0;
$activeCustomers = 0;
$inactiveCustomers = 0;
$customerTypes = [];

while($row = $customerStats->fetch_assoc()) {
    $totalCustomers += $row['total'];
    $activeCustomers += $row['active'];
    $inactiveCustomers += $row['inactive'];
    $customerTypes[$row['customer_type']] = $row['type_count'];
}

$userStats = $userModel->readAll();
$totalUsers = $userStats->num_rows;
$activeUsers = 0;
while($user = $userStats->fetch_assoc()) {
    if($user['status'] == 'active') $activeUsers++;
}

// Include header
include '../layouts/header.php';
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary">Today</button>
            <button type="button" class="btn btn-outline-primary">This Week</button>
            <button type="button" class="btn btn-outline-primary">This Month</button>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Customers</h6>
                            <h2 class="mb-0"><?php echo $totalCustomers; ?></h2>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> 12% from last month</small>
                        </div>
                        <div class="bg-primary p-3 rounded-circle">
                            <i class="fas fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active Users</h6>
                            <h2 class="mb-0"><?php echo $activeUsers; ?></h2>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> 5% from last month</small>
                        </div>
                        <div class="bg-success p-3 rounded-circle">
                            <i class="fas fa-user-check fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Pending Shipments</h6>
                            <h2 class="mb-0">24</h2>
                            <small class="text-danger"><i class="fas fa-arrow-down"></i> 3% from yesterday</small>
                        </div>
                        <div class="bg-warning p-3 rounded-circle">
                            <i class="fas fa-box fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Revenue</h6>
                            <h2 class="mb-0">$12,580</h2>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> 18% from last month</small>
                        </div>
                        <div class="bg-info p-3 rounded-circle">
                            <i class="fas fa-dollar-sign fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts and Tables -->
    <div class="row">
        <!-- Customer Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Customer Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>By Type:</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Individual:</span>
                                    <strong><?php echo $customerTypes['individual'] ?? 0; ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Company:</span>
                                    <strong><?php echo $customerTypes['company'] ?? 0; ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6>By Status:</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Active:</span>
                                    <strong><?php echo $activeCustomers; ?></strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Inactive:</span>
                                    <strong><?php echo $inactiveCustomers; ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas id="customerChart" height="150"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-history me-2"></i>Recent Activities</h5>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    <div class="timeline">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="timeline-icon bg-success rounded-circle p-2 me-3">
                                    <i class="fas fa-user-plus text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">New Customer Added</h6>
                                    <p class="text-muted mb-1">Customer PT. Example <?php echo $i; ?> has been registered</p>
                                    <small class="text-muted">2 hours ago</small>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Customers -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-users me-2"></i>Recent Customers</h5>
                    <a href="index.php?page=customers&action=create" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Add New
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="recentCustomers">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recentCustomers = $customerModel->readAll();
                                $count = 0;
                                while($customer = $recentCustomers->fetch_assoc() and $count < 5):
                                ?>
                                <tr>
                                    <td><strong><?php echo $customer['customer_code']; ?></strong></td>
                                    <td><?php echo $customer['customer_name']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $customer['customer_type'] == 'company' ? 'info' : 'secondary'; ?>">
                                            <?php echo ucfirst($customer['customer_type']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div><?php echo $customer['phone']; ?></div>
                                        <small class="text-muted"><?php echo $customer['email']; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo $customer['status']; ?>">
                                            <?php echo ucfirst($customer['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?page=customers&action=show&id=<?php echo $customer['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="index.php?page=customers&action=edit&id=<?php echo $customer['id']; ?>" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                $count++;
                                endwhile; 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Customer Chart
    const ctx = document.getElementById('customerChart').getContext('2d');
    const customerChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Individual', 'Company'],
            datasets: [{
                data: [<?php echo $customerTypes['individual'] ?? 0; ?>, <?php echo $customerTypes['company'] ?? 0; ?>],
                backgroundColor: [
                    '#3498db',
                    '#2ecc71'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<?php include '../layouts/footer.php'; ?>