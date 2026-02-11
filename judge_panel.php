<?php

session_start();

require_once("connect.php");
// echo "<pre>";
// print_r($_SESSION);
// die;

/* =========================
   AUTH CHECK (JUDGE ONLY)
========================= */
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'judge') {
    header("Location: access_denied.php");
    exit;
}

$judgeId = $_SESSION['user_id'];

/* =========================
   FETCH PENDING CASES
========================= */
$pendingCasesArray = [];

$sql = "
SELECT c.*, 
       COUNT(h.hearing_id) AS hearing_count,
       MAX(h.hearing_date) AS last_hearing
FROM cases c
LEFT JOIN hearings h ON c.case_id = h.case_id
LEFT JOIN judgements j ON c.case_id = j.case_id
WHERE c.judge_id = '$judgeId'
  AND c.status = 'Pending'
  AND j.case_id IS NULL
GROUP BY c.case_id
ORDER BY c.date_filed DESC
";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pendingCasesArray[] = $row;
    }
} else {
    die("Query Error: " . mysqli_error($conn));
}

/* =========================
   STATS
========================= */

// Cases judged
$r1 = mysqli_query($conn, "
    SELECT COUNT(*) 
    FROM judgements j
    JOIN cases c ON j.case_id = c.case_id
    WHERE c.judge_id = '$judgeId'
");
$casesJudged = $r1 ? mysqli_fetch_row($r1)[0] : 0;

// Pending
$pendingDecisions = count($pendingCasesArray);

// Closed
$r2 = mysqli_query($conn, "
    SELECT COUNT(*) 
    FROM cases 
    WHERE status='Closed' 
      AND judge_id='$judgeId'
");
$totalClosed = $r2 ? mysqli_fetch_row($r2)[0] : 0;

// Clearance Rate
$clearanceRate = ($totalClosed > 0)
    ? round(($casesJudged / $totalClosed) * 100)
    : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Judge Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
.section { margin-top: 20px; }
.case-list tr { cursor:pointer; }
.case-list tr:hover { background:#e8f5e8; }
</style>
</head>

<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-success">
  <div class="container-fluid">
    <span class="navbar-brand">
      <i class="bi bi-gavel"></i> Judge Panel
    </span>

    <div>
      <span class="text-white me-3">
        Hon. <?= htmlspecialchars($_SESSION['user_name']) ?>
      </span>
      <a href="index.php" class="btn btn-sm btn-outline-light">Dashboard</a>
      <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container-fluid mt-4">
<div class="row">

<!-- SIDEBAR -->
<div class="col-lg-3">
  <div class="card mb-4">
    <div class="card-header bg-success text-white">
      Judge Menu
    </div>
    <div class="list-group list-group-flush">
      <a href="javascript:void(0)" class="list-group-item active"
         onclick="showSection('pending', event)">Pending Judgements</a>
      <a href="javascript:void(0)" class="list-group-item"
         onclick="showSection('today', event)">Today's Hearings</a>
      <a href="javascript:void(0)" class="list-group-item"
         onclick="showSection('diary', event)">Case Diary</a>
      <a href="javascript:void(0)" class="list-group-item"
         onclick="showSection('history', event)">Case History</a>
      <a href="javascript:void(0)" class="list-group-item"
         onclick="showSection('performance', event)">Performance</a>
    </div>
  </div>

  <!-- STATS -->
  <div class="card">
    <div class="card-header bg-secondary text-white">
      Your Statistics
    </div>
    <div class="card-body">
      <p>Cases Judged: <strong><?= $casesJudged ?></strong></p>
      <p>Pending Decisions: <strong><?= $pendingDecisions ?></strong></p>
      <p>Clearance Rate: <strong><?= $clearanceRate ?>%</strong></p>
    </div>
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="col-lg-9">
  <div class="card shadow">
    <div class="card-body">

      <h4>Welcome, Your Honor</h4>
      <p>You have <strong><?= $pendingDecisions ?></strong> pending cases.</p>

      <!-- SECTIONS -->
      <div id="pending" class="section">
        <?php include("partials/pending_cases.php"); ?>
      </div>

      <div id="today" class="section" style="display:none;">
        <?php include("partials/today_hearings.php"); ?>
      </div>

      <div id="diary" class="section" style="display:none;">
        <?php include("partials/case_diary.php"); ?>
      </div>

      <div id="history" class="section" style="display:none;">
        <?php include("partials/case_history.php"); ?>
      </div>

      <div id="performance" class="section" style="display:none;">
        <?php include("partials/performance.php"); ?>
      </div>

    </div>
  </div>
</div>

</div>
</div>

<!-- JS -->
<script>
function showSection(id, event) {
    event.preventDefault();

    document.querySelectorAll('.section')
      .forEach(s => s.style.display = 'none');

    document.querySelectorAll('.list-group-item')
      .forEach(i => i.classList.remove('active'));

    document.getElementById(id).style.display = 'block';
    event.currentTarget.classList.add('active');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
