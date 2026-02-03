<?php
session_start();
require_once("connect.php");

// Check if user is judge
if($_SESSION['user_role'] != 'judge') {
    header("Location: access_denied.php");
    exit;
}

// Get judge's pending cases
$pendingCases = mysqli_query($conn, 
    "SELECT c.* FROM cases c 
     JOIN hearings h ON c.case_id = h.case_id 
     WHERE c.status = 'Pending' 
     AND c.status != 'Closed'
     GROUP BY c.case_id
     ORDER BY c.date_filed DESC");
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
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-gavel"></i> Judge Panel
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    <i class="bi bi-person-badge"></i> 
                    Hon. <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    <span class="badge bg-light text-success">JUDGE</span>
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
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-menu-button"></i> Judge's Menu</h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action active">
                                <i class="bi bi-list-task"></i> Pending Judgements
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-calendar-check"></i> Today's Hearings
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-journal-text"></i> Case Diary
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bi bi-clock-history"></i> Case History
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
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
                            <h4 class="text-success">
                                <?php
                                $casesJudged = mysqli_fetch_row(mysqli_query($conn,
                                    "SELECT COUNT(DISTINCT case_id) FROM judgements"))[0];
                                echo $casesJudged;
                                ?>
                            </h4>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">Pending Decisions</small>
                            <h4 class="text-warning">
                                <?php echo mysqli_num_rows($pendingCases); ?>
                            </h4>
                        </div>
                        <div>
                            <small class="text-muted">Clearance Rate</small>
                            <h4 class="text-info">
                                <?php
                                $totalCases = mysqli_fetch_row(mysqli_query($conn,
                                    "SELECT COUNT(*) FROM cases WHERE status = 'Closed'"))[0];
                                $rate = ($totalCases > 0) ? round(($casesJudged / $totalCases) * 100) : 0;
                                echo $rate . "%";
                                ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Welcome Card -->
                <div class="card judge-card mb-4">
                    <div class="card-body">
                        <h4 class="card-title">
                            <i class="bi bi-gavel"></i> Welcome, Your Honor
                        </h4>
                        <p class="card-text">
                            You have <strong><?php echo mysqli_num_rows($pendingCases); ?></strong> cases pending your judgement.
                            Review case details and deliver judgements.
                        </p>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-success">
                                <i class="bi bi-eye"></i> View Today's Docket
                            </a>
                            <a href="#" class="btn btn-outline-success">
                                <i class="bi bi-calendar-week"></i> Weekly Schedule
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Cases -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Pending for Judgement
                            <span class="badge bg-warning ms-2">
                                <?php echo mysqli_num_rows($pendingCases); ?> Cases
                            </span>
                        </h5>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <button class="btn btn-sm btn-light">
                                <i class="bi bi-sort-alpha-down"></i> Sort
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if(mysqli_num_rows($pendingCases) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover case-list">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Case ID</th>
                                            <th>Title</th>
                                            <th>Filed Date</th>
                                            <th>Hearings</th>
                                            <th>Last Hearing</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($case = mysqli_fetch_assoc($pendingCases)): ?>
                                            <?php
                                            // Get hearings count
                                            $hearingCount = mysqli_fetch_row(mysqli_query($conn,
                                                "SELECT COUNT(*) FROM hearings 
                                                 WHERE case_id = '{$case['case_id']}'"))[0];
                                            
                                            // Get last hearing date
                                            $lastHearing = mysqli_fetch_assoc(mysqli_query($conn,
                                                "SELECT hearing_date FROM hearings 
                                                 WHERE case_id = '{$case['case_id']}' 
                                                 ORDER BY hearing_date DESC LIMIT 1"));
                                            ?>
                                            <tr class="<?php echo ($hearingCount > 3) ? 'urgent' : ''; ?>"
                                                onclick="window.location='view_case.php?case_id=<?php echo urlencode($case['case_id']); ?>'">
                                                <td>
                                                    <strong><?php echo htmlspecialchars($case['case_id']); ?></strong>
                                                    <?php if($hearingCount > 3): ?>
                                                        <span class="badge bg-danger">Urgent</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($case['title']); ?></td>
                                                <td>
                                                    <small>
                                                        <?php echo date('d M Y', strtotime($case['date_filed'])); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <?php echo $hearingCount; ?> hearings
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($lastHearing): ?>
                                                        <small class="text-muted">
                                                            <?php echo date('d M', strtotime($lastHearing['hearing_date'])); ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <small class="text-muted">None</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="add_judgement.php?case_id=<?php echo urlencode($case['case_id']); ?>" 
                                                       class="btn btn-sm btn-success">
                                                       <i class="bi bi-gavel"></i> Judge Now
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle display-1 text-success mb-4"></i>
                                <h3>All Caught Up!</h3>
                                <p class="text-muted">You have no pending judgements at the moment.</p>
                                <a href="index.php" class="btn btn-success">
                                    <i class="bi bi-house"></i> Return to Dashboard
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-calendar-week display-4 text-primary mb-3"></i>
                                <h5>Court Schedule</h5>
                                <p>View your hearing schedule</p>
                                <a href="#" class="btn btn-outline-primary">View Schedule</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-journal-text display-4 text-success mb-3"></i>
                                <h5>Case Notes</h5>
                                <p>Add private case notes</p>
                                <a href="#" class="btn btn-outline-success">Add Notes</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="bi bi-bar-chart display-4 text-info mb-3"></i>
                                <h5>Performance</h5>
                                <p>View your statistics</p>
                                <a href="#" class="btn btn-outline-info">View Stats</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>