<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once("connect.php");

// Get case_id from URL
$case_id = $_GET['case_id'] ?? '';

if(empty($case_id)) {
    echo "❌ Error: No case_id provided!";
    exit;
}

// Fetch case details
$caseQuery = mysqli_query($conn, "SELECT * FROM cases WHERE case_id = '$case_id'");
if(mysqli_num_rows($caseQuery) == 0) {
    echo "❌ Error: Case not found!";
    exit;
}
$case = mysqli_fetch_assoc($caseQuery);

// Fetch related hearings
$hearingsQuery = mysqli_query($conn, "SELECT * FROM hearings WHERE case_id='$case_id' ORDER BY hearing_date DESC");

// Fetch judgement if exists
$judgementQuery = mysqli_query($conn, "SELECT * FROM judgements WHERE case_id='$case_id'");
$judgement = mysqli_fetch_assoc($judgementQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Case - <?= htmlspecialchars($case['case_id']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Case Details: <?= htmlspecialchars($case['case_id']) ?></h3>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Title:</strong> <?= htmlspecialchars($case['title']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($case['status']) ?></p>
            <p><strong>Assigned Judge ID:</strong> <?= htmlspecialchars($case['judge_id']) ?></p>
            <p><strong>Date Filed:</strong> <?= $case['date_filed'] ?></p>
        </div>
    </div>

    <h5>Hearings</h5>
    <?php if(mysqli_num_rows($hearingsQuery) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hearing ID</th>
                    <th>Date</th>
                    <th>Court</th>
                </tr>
            </thead>
            <tbody>
            <?php while($h = mysqli_fetch_assoc($hearingsQuery)): ?>
                <tr>
                    <td><?= htmlspecialchars($h['hearing_id']) ?></td>
                    <td><?= $h['hearing_date'] ?></td>
                    <td><?= htmlspecialchars($h['court_name']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hearings scheduled yet.</p>
    <?php endif; ?>

    <h5>Judgement</h5>
    <?php if($judgement): ?>
        <p><strong>Date:</strong> <?= $judgement['judgement_date'] ?></p>
        <p><strong>Outcome:</strong> <?= htmlspecialchars($judgement['outcome']) ?></p>
        <p><strong>Summary:</strong> <?= nl2br(htmlspecialchars($judgement['summary'])) ?></p>
    <?php else: ?>
        <p>No judgement entered yet.</p>
    <?php endif; ?>

    <a href="judge_panel.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    <a href="edit_case.php?case_id=<?= urlencode($case['case_id']) ?>" class="btn btn-warning mt-3">Edit Case</a>
</div>
</body>
</html>
