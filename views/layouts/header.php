<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistic System - <?php echo $page_title ?? 'Dashboard'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a252f 100%);
            color: white;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }
        
        .sidebar a {
            color: #bdc3c7;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }
        
        .sidebar a:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--secondary-color);
        }
        
        .sidebar a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--secondary-color);
        }
        
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .navbar-custom {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            margin-left: 250px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card {
            border-left: 4px solid var(--secondary-color);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .table th {
            background-color: var(--light-color);
            border-top: none;
        }
        
        .badge-active {
            background-color: #2ecc71;
        }
        
        .badge-inactive {
            background-color: #e74c3c;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .main-content, .navbar-custom {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-none d-md-block">
        <div class="logo">
            <h3><i class="fas fa-shipping-fast"></i> Logistic System</h3>
            <small class="text-muted">Expedition & Logistics</small>
        </div>
        
        <a href="index.php?page=dashboard" class="<?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        
        <a href="index.php?page=customers" class="<?php echo ($current_page == 'customers') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> Customers
        </a>
        
        <a href="index.php?page=users" class="<?php echo ($current_page == 'users') ? 'active' : ''; ?>">
            <i class="fas fa-user-cog"></i> User Management
        </a>
        
        <div class="mt-5">
            <small class="text-muted px-3">MAIN MENU</small>
        </div>
        
        <a href="#"><i class="fas fa-box"></i> Shipments</a>
        <a href="#"><i class="fas fa-truck"></i> Delivery</a>
        <a href="#"><i class="fas fa-chart-bar"></i> Reports</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
        
        <div class="mt-5">
            <small class="text-muted px-3">ACCOUNT</small>
        </div>
        
        <a href="#"><i class="fas fa-user"></i> My Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> Administrator
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">