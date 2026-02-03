<?php
session_start();
require_once("connect.php");

// Check if user is admin
if($_SESSION['user_role'] != 'admin') {
    header("Location: access_denied.php");
    exit;
}

// Get all users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY user_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Justice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .admin-card { border-left: 4px solid #dc3545; }
        .judge-card { border-left: 4px solid #28a745; }
        .user-table tr:hover { background: #f8f9fa; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-lock"></i> Admin Panel
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    <i class="bi bi-person-badge"></i> 
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    <span class="badge bg-light text-danger">ADMIN</span>
                </span>
                <a href="index.php" class="btn btn-sm btn-outline-light me-2">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a href="logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="bi bi-menu-button"></i> Admin Menu</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action active">
                                <i class="bi bi-people"></i> User Management
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-bar-chart"></i> System Analytics
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-gear"></i> System Settings
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-clock-history"></i> Audit Logs
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-database"></i> Database Backup
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bi bi-speedometer2"></i> System Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Total Users</small>
                            <h4>
                                <?php echo mysqli_num_rows($users); ?>
                            </h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Active Today</small>
                            <h4 class="text-success">
                                <?php
                                $today = date('Y-m-d');
                                $activeToday = mysqli_fetch_row(mysqli_query($conn,
                                    "SELECT COUNT(DISTINCT user_id) FROM audit_logs 
                                     WHERE DATE(created_at) = '$today'"))[0];
                                echo $activeToday;
                                ?>
                            </h4>
                        </div>
                        <div>
                            <small class="text-muted">System Status</small>
                            <h4 class="text-success">
                                <i class="bi bi-check-circle"></i> Online
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Card -->
                <div class="card admin-card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="bi bi-shield-check"></i> Welcome, System Administrator
                        </h4>
                        <p class="card-text">
                            You have full control over the Justice Management System. 
                            Manage users, monitor activities, and configure system settings.
                        </p>
                    </div>
                </div>
                
                <!-- User Management -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people-fill"></i> User Management
                        </h5>
                        <button class="btn btn-sm btn-light">
                            <i class="bi bi-plus-circle"></i> Add New User
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover user-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($user = mysqli_fetch_assoc($users)): ?>
                                        <tr>
                                            <td>#<?php echo $user['user_id']; ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($user['name']); ?>
                                                <?php if($user['user_id'] == $_SESSION['user_id']): ?>
                                                    <span class="badge bg-info">You</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <?php 
                                                $role_colors = [
                                                    'admin' => 'danger',
                                                    'judge' => 'success',
                                                    'lawyer' => 'primary',
                                                    'clerk' => 'warning',
                                                    'analyst' => 'info'
                                                ];
                                                $color = $role_colors[$user['role']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?php echo $color; ?>">
                                                    <?php echo ucfirst($user['role']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-outline-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <?php if($user['user_id'] != $_SESSION['user_id']): ?>
                                                        <button class="btn btn-outline-danger" title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Role Distribution -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Role Distribution</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="roleChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-activity"></i> Recent Activity</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item">
                                        <small class="text-muted">Just now</small>
                                        <div>Admin logged into system</div>
                                    </div>
                                    <div class="list-group-item">
                                        <small class="text-muted">10 min ago</small>
                                        <div>Judge Sharma delivered a judgement</div>
                                    </div>
                                    <div class="list-group-item">
                                        <small class="text-muted">1 hour ago</small>
                                        <div>New case filed by Clerk Kumar</div>
                                    </div>
                                    <div class="list-group-item">
                                        <small class="text-muted">2 hours ago</small>
                                        <div>System backup completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js for Role Distribution -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    // Role Distribution Chart
    new Chart(document.getElementById('roleChart'), {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Judge', 'Lawyer', 'Clerk', 'Analyst'],
            datasets: [{
                data: [1, 1, 1, 1, 0], // Update with real data
                backgroundColor: ['#dc3545', '#28a745', '#007bff', '#ffc107', '#17a2b8'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>