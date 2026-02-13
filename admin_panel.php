<?php
session_start();
require_once("connect.php");

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin'){
    header("Location: access_denied.php");
    exit;
}
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    mysqli_query($conn,"UPDATE users SET status='approved' WHERE user_id=$id");
    header("Location: admin_panel.php");
    exit;
}


if(isset($_GET['reject'])){
    $id = intval($_GET['reject']);
    mysqli_query($conn,"UPDATE users SET status='rejected' WHERE user_id=$id");
    header("Location: admin_panel.php");
    exit;
}

if(isset($_GET['restore_case'])){

    $case_id = mysqli_real_escape_string($conn, $_GET['restore_case']);

    mysqli_query($conn,"
        UPDATE cases 
        SET is_deleted = 0,
            deleted_at = NULL,
            deleted_by = NULL
        WHERE case_id = '$case_id' AND is_deleted = 1
    ");

    header("Location: admin_panel.php");
    exit;
}

if(isset($_POST['add_user'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn," INSERT INTO users (name,email,password,role,status) VALUES ('$name','$email','$password','$role','approved') ");
    header("Location: admin_panel.php");
    exit;
}

if(isset($_POST['edit_user'])){
    $id = intval($_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET name='$name', email='$email', role='$role', status='$status'";
    if(!empty($_POST['password'])){
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql .= ", password='$password'";
    }
    $sql .= " WHERE user_id=$id";

    mysqli_query($conn, $sql);

    header("Location: admin_panel.php");
    exit;
}

if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    if($id != $_SESSION['user_id']){
        mysqli_query($conn,"DELETE FROM users WHERE user_id=$id");
    }
    header("Location: admin_panel.php");
    exit;
}

$users = mysqli_query(
    $conn,
    "SELECT * FROM users ORDER BY status='pending' DESC, user_id ASC"
);

$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM users")
)['total'];

$today = date('Y-m-d');
$activeToday = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(DISTINCT user_id) AS active_today FROM audit_logs WHERE DATE(created_at)='$today'"))['active_today'] ?? 0;

$roleCounts = ['admin'=>0,'judge'=>0,'lawyer'=>0,'clerk'=>0,'analyst'=>0];
$roleResult = mysqli_query($conn,"SELECT role,COUNT(*) as count FROM users GROUP BY role");
while($row = mysqli_fetch_assoc($roleResult)){
    $roleCounts[$row['role']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Justice System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    .admin-card { border-left: 4px solid #dc3545; }
    .user-table tr:hover { background: #f8f9fa; }
</style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
<div class="container-fluid">
    <a class="navbar-brand" href="#"><i class="bi bi-shield-lock"></i> Admin Panel</a>
    <div class="d-flex align-items-center">
        <span class="text-light me-3">
            <i class="bi bi-person-badge"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
            <span class="badge bg-light text-danger">ADMIN</span>
        </span>
        <a href="index.php" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-house"></i> Dashboard</a>
        <a href="logout.php" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
</nav>

<div class="container-fluid mt-4">
<div class="row">


    <div class="col-lg-3">
        <div class="card mb-4">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="bi bi-menu-button"></i> Admin Menu</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <button class="list-group-item list-group-item-action active" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-people"></i> User Management</button>
                    <!-- <a href="#" class="list-group-item list-group-item-action"><i class="bi bi-bar-chart"></i> System Analytics</a>
                    <a href="#" class="list-group-item list-group-item-action"><i class="bi bi-gear"></i> System Settings</a>
                    <a href="#" class="list-group-item list-group-item-action"><i class="bi bi-clock-history"></i> Audit Logs</a>
                    <a href="#" class="list-group-item list-group-item-action"><i class="bi bi-database"></i> Database Backup</a> -->
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header bg-secondary text-white"><h6 class="mb-0"><i class="bi bi-speedometer2"></i> System Stats</h6></div>
            <div class="card-body">
                <div class="mb-3"><small class="text-muted">Total Users</small><h4><?= $totalUsers ?></h4></div>
                <div class="mb-3"><small class="text-muted">Active Today</small><h4 class="text-success"><?= $activeToday ?></h4></div>
                <div><small class="text-muted">System Status</small><h4 class="text-success"><i class="bi bi-check-circle"></i> Online</h4></div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="card admin-card mb-4">
            <div class="card-body">
                <h4 class="card-title"><i class="bi bi-shield-check"></i> Welcome, System Administrator</h4>
                <p class="card-text">You have full control over the Justice Management System.</p>
            </div>
        </div>
<div class="card mt-4">
<div class="card-header bg-warning text-dark">
    <h5 class="mb-0">Deleted Cases</h5>
</div>
<div class="card-body table-responsive">

<table class="table table-bordered">
<thead>
<tr>
    <th>Case ID</th>
    <th>Title</th>
    <th>Deleted At</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php

$del_limit  = 5; 
$del_page   = isset($_GET['del_page']) ? max(1, intval($_GET['del_page'])) : 1;
$del_offset = ($del_page - 1) * $del_limit;


$del_total_query = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM cases 
    WHERE is_deleted = 1
");
$del_total = mysqli_fetch_assoc($del_total_query)['total']  ;
$del_pages = ceil($del_total / $del_limit);


$deletedCases = mysqli_query($conn,"
    SELECT * FROM cases 
    WHERE is_deleted = 1 
    ORDER BY deleted_at DESC
    LIMIT $del_offset, $del_limit
");


if(mysqli_num_rows($deletedCases) == 0){
    echo "<tr><td colspan='4' class='text-center'>No deleted cases</td></tr>";
}

while($c = mysqli_fetch_assoc($deletedCases)){
    echo "<tr>
        <td>{$c['case_id']}</td>
        <td>{$c['title']}</td>
        <td>{$c['deleted_at']}</td>
        <td>
            <a href='admin_panel.php?restore_case={$c['case_id']}'
               class='btn btn-success btn-sm'
               onclick=\"return confirm('Restore this case?')\">
               Restore
            </a>
        </td>
    </tr>";
}
?>
</tbody>
</table>
<?php if($del_pages > 1): ?>
<nav>
<ul class="pagination justify-content-center">

<?php if($del_page > 1): ?>
<li class="page-item">
<a class="page-link" href="?del_page=<?= $del_page - 1 ?>">Previous</a>
</li>
<?php endif; ?>

<?php for($i = 1; $i <= $del_pages; $i++): ?>
<li class="page-item <?= ($i == $del_page) ? 'active' : '' ?>">
<a class="page-link" href="?del_page=<?= $i ?>"><?= $i ?></a>
</li>
<?php endfor; ?>

<?php if($del_page < $del_pages): ?>
<li class="page-item">
<a class="page-link" href="?del_page=<?= $del_page + 1 ?>">Next</a>
</li>
<?php endif; ?>

</ul>
</nav>
<?php endif; ?>


</div>
</div>
       
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-people-fill"></i> Users</h5>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="bi bi-plus-circle"></i> Add New User</button>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover user-table">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    mysqli_data_seek($users,0);
                    while($user=mysqli_fetch_assoc($users)):
                        $role_colors = ['admin'=>'danger','judge'=>'success','lawyer'=>'primary','clerk'=>'warning','analyst'=>'info'];
                        $color = $role_colors[$user['role']] ?? 'secondary';
                    ?>
                    <tr>
                        <td>#<?= $user['user_id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge bg-<?= $color ?>"><?= ucfirst($user['role']) ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['user_id'] ?>">Edit</button>
                            <?php if($user['user_id'] != $_SESSION['user_id'] && $user['role'] != 'admin'): ?>
                                    <a href="?delete=<?= $user['user_id'] ?>" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this user?')">
                                    Delete
                                    </a>
                            <?php endif; ?>
                            <?php if(
                                $user['status'] === 'pending' &&
                                $user['user_id'] != $_SESSION['user_id'] &&
                                $user['role'] != 'admin'
                                 ): ?>
                                <a href="?approve=<?= $user['user_id'] ?>" 
                                class="btn btn-sm btn-success"
                                onclick="return confirm('Approve this user?')">
                                Approve
                                </a>

                                <a href="?reject=<?= $user['user_id'] ?>" 
                                class="btn btn-sm btn-warning"
                                onclick="return confirm('Reject this user?')">
                                Reject
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                                $statusColors = [
                                    'approved'=>'success',
                                    'pending'=>'warning',
                                    'rejected'=>'danger'
                                ];
                                $sColor = $statusColors[$user['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $sColor ?>">
                                <?= ucfirst($user['status']) ?>
                            </span>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

      
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white"><h6 class="mb-0"><i class="bi bi-pie-chart"></i> Role Distribution</h6></div>
                    <div class="card-body"><canvas id="roleChart" height="200"></canvas></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white"><h5 class="modal-title">Add New User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <input class="form-control mb-2" name="name" placeholder="Name" required>
            <input class="form-control mb-2" name="email" type="email" placeholder="Email" required>
            <input class="form-control mb-2" name="password" type="password" placeholder="Password" required>
            <select class="form-select" name="role" required>
                <option value="admin">Admin</option>
                <option value="judge">Judge</option>
                <option value="lawyer">Lawyer</option>
                <option value="clerk">Clerk</option>
                <option value="analyst">Analyst</option>
            </select>
        </div>
        <div class="modal-footer"><button type="submit" name="add_user" class="btn btn-success">Add User</button></div>
      </div>
    </form>
  </div>
</div>


<?php
mysqli_data_seek($users,0);
while($user=mysqli_fetch_assoc($users)):
?>
<div class="modal fade" id="editUserModal<?= $user['user_id'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST">
      <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white"><h5 class="modal-title">Edit User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>

</div>
        <div class="modal-body">
             <select class="form-select mb-2" name="status">
                <option value="approved" <?= $user['status']=='approved'?'selected':'' ?>>Approved</option>
                <option value="pending" <?= $user['status']=='pending'?'selected':'' ?>>Pending</option>
                <option value="rejected" <?= $user['status']=='rejected'?'selected':'' ?>>Rejected</option>
            </select>
            <input class="form-control mb-2" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <input class="form-control mb-2" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <input class="form-control mb-2" name="password" type="password" placeholder="Leave blank to keep current">
            <select class="form-select" name="role" required>
                <?php foreach(['admin','judge','lawyer','clerk','analyst'] as $r): ?>
                <option value="<?= $r ?>" <?= $user['role']==$r?'selected':'' ?>><?= ucfirst($r) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="modal-footer"><button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button></div>
      </div>
    </form>
  </div>
</div>
<?php endwhile; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

new Chart(document.getElementById('roleChart'), {
    type: 'doughnut',
    data: {
        labels: ['Admin','Judge','Lawyer','Clerk','Analyst'],
        datasets: [{
            data: [<?= $roleCounts['admin'] ?>,<?= $roleCounts['judge'] ?>,<?= $roleCounts['lawyer'] ?>,<?= $roleCounts['clerk'] ?>,<?= $roleCounts['analyst'] ?>],
            backgroundColor: ['#dc3545','#28a745','#007bff','#ffc107','#17a2b8']
        }]
    },
    options: { responsive:true, plugins:{ legend:{ position:'bottom' } } }
});
</script>
</body>
</html>
