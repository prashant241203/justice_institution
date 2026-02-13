<?php
session_start();
require_once("auth_check.php");
requireLogin();
requireRoles(['admin','judge','lawyer']); 
require_once("connect.php");

$case_id = $_GET['case_id'] ?? '';

$backUrl = isLawyer() ? 'lawyer_dashboard.php' : 'index.php';

if (isLawyer()) {
    $lawyer_id = $_SESSION['user_id'];
    $check = mysqli_query($conn,"
      SELECT 1 FROM cases
      WHERE case_id='$case_id'
      AND lawyer_id=$lawyer_id
    ");
    if (mysqli_num_rows($check) === 0) {
        header("Location: access_denied.php");
        exit;
    }
}

if (!$case_id) {
    die("Case ID missing");
}


$query = mysqli_query($conn, "SELECT h.*, u.name as created_by_name 
                              FROM hearings h 
                              LEFT JOIN users u ON h.created_by = u.user_id
                              WHERE case_id='$case_id' 
                              ORDER BY hearing_date DESC");

if (!$query) {
    die("SQL Error: " . mysqli_error($conn));
}

$hearings = [];
while ($row = mysqli_fetch_assoc($query)) {
    $hearings[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Hearings - Case <?= htmlspecialchars($case_id) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f5f7fb; }
.card { border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
.table th { background-color: #f8f9fa; font-weight: 600; }
.btn-back { background:#0a66c2; color:#fff; border:none; }
.btn-back:hover { background:#084b9e; color:#fff; }
</style>
</head>
<body>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Hearings for Case <span class="text-primary"><?= htmlspecialchars($case_id) ?></span></h3>
        <a href="<?= $backUrl ?>" class="btn btn-back btn-sm">
            ← Back to Cases
        </a>
    </div>

    <?php if (count($hearings) === 0): ?>
        <div class="alert alert-info text-center">No hearing has been scheduled yet for this case.</div>
    <?php else: ?>
        <?php foreach($hearings as $h): ?>
        <div class="card p-3">
            <div class="row">
                <div class="col-md-3"><strong>Hearing ID:</strong> <?= htmlspecialchars($h['hearing_id']) ?></div>
                <div class="col-md-3"><strong>Date:</strong> <?= htmlspecialchars($h['hearing_date']) ?></div>
                <div class="col-md-3"><strong>Court:</strong> <?= htmlspecialchars($h['court_name']) ?></div>
                <div class="col-md-3"><strong>Created By:</strong> <?= htmlspecialchars($h['created_by_name'] ?? $h['created_by']) ?></div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12"><strong>Created At:</strong> <?= htmlspecialchars($h['created_at']) ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
