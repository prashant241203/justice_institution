<?php
session_start();

require_once("connect.php");
require_once("auth_check.php");
requireLogin();
requireRoles(['admin','judge']);
/* =====================
   LOGIN CHECK
===================== */
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

/* =====================

if (!can('add_hearing')) {
    header("Location: access_denied.php");
    exit;
}

/* =====================
   GET CASE ID
===================== */
$case_id = isset($_GET['case_id']) ? trim($_GET['case_id']) : '';

if ($case_id === '') {
    die("❌ case_id missing in URL");
}

/* =====================
   VALIDATE CASE
===================== */
$caseQuery = mysqli_query(
    $conn,
    "SELECT case_id, title, status 
     FROM cases 
     WHERE case_id = '$case_id'"
);

if (mysqli_num_rows($caseQuery) === 0) {
    die("❌ Invalid Case ID");
}

$case = mysqli_fetch_assoc($caseQuery);

/* =====================
   FORM SUBMIT
===================== */
if (isset($_POST['schedule_hearing'])) {

    $hearing_date = $_POST['hearing_date'];
    $court_name   = $_POST['court_name'];

    /* =====================
       SAFE HEARING ID GENERATION
    ===================== */
    $lastHearingQuery = mysqli_query(
        $conn,
        "SELECT hearing_id 
         FROM hearings 
         ORDER BY CAST(SUBSTRING(hearing_id, 2) AS UNSIGNED) DESC 
         LIMIT 1"
    );
        
    $lastHearing = mysqli_fetch_assoc($lastHearingQuery);

    if ($lastHearing && preg_match('/^H(\d+)$/', $lastHearing['hearing_id'], $matches)) {
        $next = (int)$matches[1] + 1;
        $hearing_id = 'H' . str_pad($next, 3, '0', STR_PAD_LEFT);
    } else {
        $hearing_id = 'H001';
    }

    /* =====================
       INSERT HEARING
    ===================== */
    $insertQuery = "
        INSERT INTO hearings (hearing_id, case_id, hearing_date, court_name)
        VALUES ('$hearing_id', '$case_id', '$hearing_date', '$court_name')
    ";

    if (mysqli_query($conn, $insertQuery)) {

        // Assign judge & update case status
        mysqli_query($conn, "
            UPDATE cases 
            SET status = 'Pending',
                judge_id = '{$_SESSION['user_id']}'
            WHERE case_id = '$case_id'
        ");

        header("Location: index.php#hearings");
        exit;

    } else {
        echo "<div class='alert alert-danger'>";
        echo "DB Error: " . mysqli_error($conn);
        echo "</div>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Schedule Hearing</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-7">

<div class="card shadow">
<div class="card-header bg-dark text-white">
    <h5 class="mb-0">⚖️ Schedule Hearing</h5>
</div>

<form method="POST" class="card-body">

    <div class="mb-3">
        <label class="form-label">Case</label>
        <input class="form-control"
               value="<?= htmlspecialchars($case['case_id']) ?> - <?= htmlspecialchars($case['title']) ?>"
               disabled>
        <small class="text-muted">Status: <?= $case['status'] ?></small>
    </div>

    <div class="mb-3">
        <label class="form-label">Hearing Date</label>
        <input type="date" name="hearing_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Court Name / Number</label>
        <input type="text" name="court_name" class="form-control" required>
    </div>

    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary">Back</a>
        <button class="btn btn-primary" name="schedule_hearing">
            Schedule Hearing
        </button>
    </div>

</form>

</div>
</div>
</div>
</div>

</body>
</html>
