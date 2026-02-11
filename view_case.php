<?php
// Start session and check login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once("auth_check.php");
require_once("connect.php");

// Check if case_id is provided
if (!isset($_GET['case_id'])) {
    echo "Case ID missing!";
    exit;
}

$case_id = mysqli_real_escape_string($conn, $_GET['case_id']);

// Fetch case details
$query = mysqli_query($conn, "SELECT * FROM cases WHERE case_id='$case_id'");
$case = mysqli_fetch_assoc($query);

if (!$case) {
    echo "Case not found!";
    exit;
}

// Fetch hearings for this case
$hearingsQuery = mysqli_query($conn, "SELECT * FROM hearings WHERE case_id='$case_id' ORDER BY hearing_date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Case - <?php echo htmlspecialchars($case['case_id']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: #f5f7fb;">

<nav class="navbar navbar-expand-lg navbar-dark" style="background: #0a66c2;">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Case Details</a>
        <div class="d-flex">
            <span class="text-light me-3"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="search.php" class="btn btn-sm btn-outline-light me-2">Back to Search</a>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Case Details - <?php echo htmlspecialchars($case['case_id']); ?></h4>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><strong>Case ID:</strong></div>
                <div class="col-md-8"><?php echo htmlspecialchars($case['case_id']); ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Title:</strong></div>
                <div class="col-md-8"><?php echo htmlspecialchars($case['title']); ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Status:</strong></div>
                <div class="col-md-8"><?php echo htmlspecialchars($case['status']); ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Date Filed:</strong></div>
                <div class="col-md-8"><?php echo date('d M Y', strtotime($case['date_filed'])); ?></div>
            </div>

            <hr>

            <h5>Hearings</h5>
            <?php if (mysqli_num_rows($hearingsQuery) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hearing Date</th>
                            <th>Description</th>
                            <th>Next Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; while ($hearing = mysqli_fetch_assoc($hearingsQuery)): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo date('d M Y', strtotime($hearing['hearing_date'])); ?></td>
                                <td><?php echo htmlspecialchars($hearing['description']); ?></td>
                                <td><?php echo htmlspecialchars($hearing['next_action']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">No hearings scheduled for this case yet.</div>
            <?php endif; ?>

            <div class="mt-3">
                <?php if ($case['status'] == 'Open' || mysqli_num_rows($hearingsQuery) == 0): ?>
                    <a href="add_hearing.php?case_id=<?php echo urlencode($case['case_id']); ?>" class="btn btn-primary">
                        <i class="bi bi-calendar-plus"></i> Add Hearing
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
