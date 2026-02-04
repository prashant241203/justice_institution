<?php
session_start();
require_once("connect.php");

// Check if user is judge
if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'judge') {
    header("Location: access_denied.php");
    exit;
}

$judgeId = $_SESSION['user_id']; 

// Fetch pending cases once
$pendingCasesArray = [];
$result = mysqli_query($conn, "
    SELECT c.*, COUNT(h.hearing_id) AS hearing_count, MAX(h.hearing_date) AS last_hearing
    FROM cases c
    LEFT JOIN hearings h ON c.case_id = h.case_id
    LEFT JOIN judgements j ON c.case_id = j.case_id
    WHERE c.judge_id = '$judgeId' AND j.case_id IS NULL
    GROUP BY c.case_id
    ORDER BY c.date_filed DESC
");

if($result){
    while($row = mysqli_fetch_assoc($result)){
        $pendingCasesArray[] = $row;
    }
} else {
    die("Query Error: " . mysqli_error($conn));
}

// 1️⃣ Cases Judged
$resultJudged = mysqli_query($conn, "SELECT COUNT(*) FROM judgements j
                                     JOIN cases c ON j.case_id = c.case_id
                                     WHERE c.judge_id = '$judgeId'");
$casesJudged = ($resultJudged) ? mysqli_fetch_row($resultJudged)[0] : 0;

// 2️⃣ Pending Decisions
$pendingDecisions = count($pendingCasesArray);

// 3️⃣ Total Closed Cases assigned to this judge
$resultClosed = mysqli_query($conn, "SELECT COUNT(*) FROM cases 
                                     WHERE status = 'Closed' 
                                       AND judge_id = '$judgeId'");
$totalClosed = ($resultClosed) ? mysqli_fetch_row($resultClosed)[0] : 0;

// 4️⃣ Clearance Rate
$clearanceRate = ($totalClosed > 0) ? round(($casesJudged / $totalClosed) * 100) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Judge Panel - Justice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .judge-card { border-left: 4px solid #28a745; }
        .urgent { border-left: 4px solid #dc3545 !important; }
        .case-list tr { cursor: pointer; }
        .case-list tr:hover { background: #e8f5e8; }
        .section { margin-top: 20px; }
    </style>
</head>
<body class="bg-light">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="bi bi-gavel"></i> Judge Panel</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3">
                <i class="bi bi-person-badge"></i> Hon. <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                <span class="badge bg-light text-success">JUDGE</span>
            </span>
            <a href="index.php" class="btn btn-sm btn-outline-light me-2"><i class="bi bi-house"></i> Dashboard</a>
            <a href="logout.php" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-menu-button"></i> Judge's Menu</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active" onclick="showSection('pending', event)">
                            <i class="bi bi-list-task"></i> Pending Judgements
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="showSection('today', event)">
                            <i class="bi bi-calendar-check"></i> Today's Hearings
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="showSection('diary', event)">
                            <i class="bi bi-journal-text"></i> Case Diary
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="showSection('history', event)">
                            <i class="bi bi-clock-history"></i> Case History
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="showSection('performance', event)">
                            <i class="bi bi-bar-chart"></i> Performance
                        </a>
                    </div>
                </div>
            </div>

            <!-- Judge Stats -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-award"></i> Your Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Cases Judged</small>
                        <h4 class="text-success"><?php echo $casesJudged; ?></h4>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Pending Decisions</small>
                        <h4 class="text-warning"><?php echo $pendingDecisions; ?></h4>
                    </div>
                    <div>
                        <small class="text-muted">Clearance Rate</small>
                        <h4 class="text-info"><?php echo $clearanceRate; ?>%</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Welcome Card -->
            <div class="card judge-card mb-4">
                <div class="card-body">
                    <h4 class="card-title"><i class="bi bi-gavel"></i> Welcome, Your Honor</h4>
                    <p class="card-text">
                        You have <strong><?php echo $pendingDecisions; ?></strong> cases pending your judgement.
                        Review case details and deliver judgements.
                    </p>
                    <!-- <div class="d-flex gap-2">
                        <a href="#" class="btn btn-success"><i class="bi bi-eye"></i> View Today's Docket</a>
                        <a href="#" class="btn btn-outline-success"><i class="bi bi-calendar-week"></i> Weekly Schedule</a>
                    </div> -->
                </div>

                <!-- Sections -->
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

<script>
function showSection(id, event) {
    // Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(sec => sec.style.display = 'none');

    // Remove active class from all menu items
    const menuItems = document.querySelectorAll('.list-group-item');
    menuItems.forEach(item => item.classList.remove('active'));

    // Show selected section
    document.getElementById(id).style.display = 'block';

    // Set clicked item as active
    event.currentTarget.classList.add('active');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
