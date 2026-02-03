<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once("auth_check.php");
require_once("connect.php");

// Get case_id from URL
$case_id = isset($_GET['case_id']) ? mysqli_real_escape_string($conn, $_GET['case_id']) : '';

if(empty($case_id)) {
    header("Location: index.php");
    exit;
}

// Get case details
$caseQuery = mysqli_query($conn, "SELECT * FROM cases WHERE case_id = '$case_id'");
if(mysqli_num_rows($caseQuery) == 0) {
    echo '<div class="alert alert-danger">Case not found!</div>';
    echo '<a href="index.php" class="btn btn-secondary">Back to Dashboard</a>';
    exit;
}
$case = mysqli_fetch_assoc($caseQuery);

// Get hearings for this case
$hearings = mysqli_query($conn, 
    "SELECT * FROM hearings WHERE case_id = '$case_id' ORDER BY hearing_date DESC");

// Get judgement for this case
$judgement = mysqli_query($conn, 
    "SELECT * FROM judgements WHERE case_id = '$case_id' LIMIT 1");
$hasJudgement = mysqli_num_rows($judgement) > 0;
$judgementData = $hasJudgement ? mysqli_fetch_assoc($judgement) : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Case Details - <?php echo htmlspecialchars($case['title']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f5f7fb; }
        .case-header { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .timeline { position: relative; padding-left: 30px; }
        .timeline:before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: #dee2e6; }
        .timeline-item { position: relative; margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; border: 1px solid #dee2e6; }
        .timeline-item:before { content: ''; position: absolute; left: -23px; top: 20px; width: 12px; height: 12px; border-radius: 50%; background: #0d6efd; }
        .action-buttons { position: sticky; top: 20px; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #0a66c2;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-house-door"></i> Justice & Institutions
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    <i class="bi bi-person-circle"></i> 
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?> 
                    <span class="badge bg-light text-dark"><?php echo $_SESSION['user_role']; ?></span>
                </span>
                <a href="index.php" class="btn btn-sm btn-outline-light me-2">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
                <a href="logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="index.php#caseMaster">Case Master</a></li>
                        <li class="breadcrumb-item active">Case Details</li>
                    </ol>
                </nav>

                <!-- Case Information Card -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: #0a66c2; color: white;">
                        <h5 class="mb-0">
                            <i class="bi bi-folder"></i> 
                            Case Information - <?php echo htmlspecialchars($case['case_id']); ?>
                        </h5>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-clock"></i> Created: <?php echo date('d M Y', strtotime($case['created_at'])); ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%"><i class="bi bi-tag"></i> Case ID:</th>
                                        <td><strong><?php echo htmlspecialchars($case['case_id']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th><i class="bi bi-card-heading"></i> Title:</th>
                                        <td><?php echo htmlspecialchars($case['title']); ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="bi bi-calendar-date"></i> Date Filed:</th>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo date('d M Y', strtotime($case['date_filed'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%"><i class="bi bi-activity"></i> Status:</th>
                                        <td>
                                            <?php 
                                            $status_color = match($case['status']) {
                                                'Open' => 'success',
                                                'Pending' => 'warning',
                                                'Closed' => 'secondary',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-<?php echo $status_color; ?>">
                                                <i class="bi bi-circle-fill"></i> <?php echo $case['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="bi bi-calendar-check"></i> Last Updated:</th>
                                        <td><?php echo date('d M Y H:i', strtotime($case['created_at'])); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Hearings Timeline -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Hearings Timeline</h5>
                                <span class="badge bg-light text-dark">
                                    <?php echo mysqli_num_rows($hearings); ?> Hearings
                                </span>
                            </div>
                            <div class="card-body">
                                <?php if(mysqli_num_rows($hearings) > 0): ?>
                                    <div class="timeline">
                                        <?php while($h = mysqli_fetch_assoc($hearings)): ?>
                                            <div class="timeline-item">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1">
                                                        <i class="bi bi-mic"></i> 
                                                        Hearing <?php echo htmlspecialchars($h['hearing_id']); ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <?php echo date('d M Y', strtotime($h['hearing_date'])); ?>
                                                    </small>
                                                </div>
                                                <p class="mb-1">
                                                    <i class="bi bi-building"></i> 
                                                    <strong>Court:</strong> <?php echo htmlspecialchars($h['court_name']); ?>
                                                </p>
                                                <p class="mb-1">
                                                    <i class="bi bi-clock"></i> 
                                                    <strong>Scheduled:</strong> <?php echo date('H:i', strtotime($h['created_at'])); ?>
                                                </p>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                                        <h5>No Hearings Scheduled</h5>
                                        <p class="text-muted">This case has no hearings scheduled yet.</p>
                                        <a href="add_hearing.php?case_id=<?php echo urlencode($case_id); ?>" 
                                           class="btn btn-primary">
                                           <i class="bi bi-plus-circle"></i> Schedule First Hearing
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Judgement Details -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-gavel"></i> Judgement Details</h5>
                                <?php if($hasJudgement): ?>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-check-circle"></i> Delivered
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <?php if($hasJudgement && $judgementData): ?>
                                    <div class="alert alert-success">
                                        <h6><i class="bi bi-award"></i> Judgement Delivered</h6>
                                        <p class="mb-0">This case has been concluded with a judgement.</p>
                                    </div>
                                    
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%"><i class="bi bi-tag"></i> Judgement ID:</th>
                                            <td><?php echo htmlspecialchars($judgementData['judgement_id']); ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-calendar-date"></i> Date:</th>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo date('d M Y', strtotime($judgementData['judgement_date'])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="bi bi-flag"></i> Outcome:</th>
                                            <td>
                                                <?php 
                                                $outcome = $judgementData['outcome'];
                                                $outcome_color = match(true) {
                                                    in_array($outcome, ['Convicted', 'Guilty', 'Imprisonment']) => 'danger',
                                                    in_array($outcome, ['Acquitted', 'Not Guilty', 'Appeal Allowed']) => 'success',
                                                    in_array($outcome, ['Dismissed', 'Case Withdrawn']) => 'warning',
                                                    default => 'info'
                                                };
                                                ?>
                                                <span class="badge bg-<?php echo $outcome_color; ?>">
                                                    <?php echo htmlspecialchars($outcome); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <div class="mt-3">
                                        <h6><i class="bi bi-journal-text"></i> Summary:</h6>
                                        <div class="p-3 bg-light rounded">
                                            <?php echo nl2br(htmlspecialchars($judgementData['summary'] ?? 'No summary provided.')); ?>
                                        </div>
                                    </div>
                                    
                                <?php elseif($case['status'] != 'Closed' && mysqli_num_rows($hearings) > 0): ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-gavel display-4 text-warning mb-3"></i>
                                        <h5>Pending Judgement</h5>
                                        <p class="text-muted">This case is ready for judgement delivery.</p>
                                        <a href="add_judgement.php?case_id=<?php echo urlencode($case_id); ?>" 
                                           class="btn btn-success">
                                           <i class="bi bi-gavel"></i> Deliver Judgement
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="bi bi-hourglass-split display-4 text-muted mb-3"></i>
                                        <h5><?php echo ($case['status'] == 'Closed') ? 'Case Closed' : 'In Progress'; ?></h5>
                                        <p class="text-muted">
                                            <?php echo ($case['status'] == 'Closed') ? 
                                                'This case has been closed.' : 
                                                'Schedule hearings first to proceed with judgement.'; ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar - Quick Actions -->
            <div class="col-lg-3">
                <div class="action-buttons">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="add_hearing.php?case_id=<?php echo urlencode($case_id); ?>" 
                                   class="btn btn-primary">
                                   <i class="bi bi-calendar-plus"></i> Schedule Hearing
                                </a>
                                
                                <?php if(mysqli_num_rows($hearings) > 0 && $case['status'] != 'Closed'): ?>
                                    <a href="add_judgement.php?case_id=<?php echo urlencode($case_id); ?>" 
                                       class="btn btn-success">
                                       <i class="bi bi-gavel"></i> Deliver Judgement
                                    </a>
                                <?php endif; ?>
                                
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                                </a>
                            </div>
                            
                            <hr>
                            
                            <h6><i class="bi bi-info-circle"></i> Case Statistics</h6>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Hearings:</span>
                                    <strong><?php echo mysqli_num_rows($hearings); ?></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Status:</span>
                                    <span class="badge bg-<?php echo $status_color; ?>">
                                        <?php echo $case['status']; ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Days Active:</span>
                                    <strong>
                                        <?php 
                                        $filed_date = new DateTime($case['date_filed']);
                                        $today = new DateTime();
                                        echo $today->diff($filed_date)->format('%a');
                                        ?> days
                                    </strong>
                                </div>
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