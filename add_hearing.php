<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once("auth_check.php");
require_once("connect.php");

/* =====================
   GET CASE ID FROM URL
===================== */
$case_id = isset($_GET['case_id']) ? trim($_GET['case_id']) : '';

// Check if case_id is empty
if (empty($case_id)) {
    echo "<div class='alert alert-danger'>";
    echo "❌ Error: No case_id provided in URL!<br>";
    echo "Link should look like: add_hearing.php?case_id=C001<br>";
    echo "</div>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Dashboard</a>";
    exit;
}

// ✅ Validate case exists (varchar case_id)
$caseQuery = mysqli_query(
    $conn,
    "SELECT case_id, title, status FROM cases WHERE case_id = '$case_id'"
);



if (mysqli_num_rows($caseQuery) === 0) {
    echo "<div class='alert alert-danger'>";
    echo "❌ Invalid Case ID: '" . htmlspecialchars($case_id) . "'<br>";
    echo "Case ID '" . htmlspecialchars($case_id) . "' does not exist in the database.<br>";
    
    // Show available cases
    echo "<p>Available Cases in Database:</p>";
    $allCases = mysqli_query($conn, "SELECT case_id, title FROM cases WHERE case_id != ''");
    if(mysqli_num_rows($allCases) > 0) {
        echo "<ul>";
        while($row = mysqli_fetch_assoc($allCases)) {
            echo "<li><a href='add_hearing.php?case_id=" . urlencode($row['case_id']) . "'>" . 
                 htmlspecialchars($row['case_id']) . " - " . htmlspecialchars($row['title']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No cases in database!";
    }
    echo "</div>";
    echo "<a href='index.php' class='btn btn-secondary'>Back to Dashboard</a>";
    exit;
}

$case = mysqli_fetch_assoc($caseQuery);


/* =====================
   FORM SUBMIT
===================== */
if (isset($_POST['schedule_hearing'])) {
    $hearing_date = $_POST['hearing_date'];
    $court_name   = $_POST['court_name'];
    
    // Generate hearing_id
    $lastHearingQuery = mysqli_query($conn, "SELECT hearing_id FROM hearings ORDER BY hearing_id DESC LIMIT 1");
    $lastHearing = mysqli_fetch_assoc($lastHearingQuery);
    
    if ($lastHearing && !empty($lastHearing['hearing_id']) && preg_match('/^H(\d+)$/', $lastHearing['hearing_id'], $matches)) {
        $nextNumber = intval($matches[1]) + 1;
        $hearing_id = 'H' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    } else {
        $hearing_id = 'H001';
    }

    // ✅ Insert hearing
    $insertQuery = "INSERT INTO hearings (hearing_id, case_id, hearing_date, court_name)
                   VALUES ('$hearing_id', '$case_id', '$hearing_date', '$court_name')";
    
    echo "Insert Query: " . htmlspecialchars($insertQuery) . "<br>";
    
    if(mysqli_query($conn, $insertQuery)) {
        // ✅ Update case status
        if ($case['status'] !== 'Closed') {
            mysqli_query($conn,
                "UPDATE cases SET status='In Progress' WHERE case_id = '$case_id'"
            );
        }

        header("Location: index.php#hearings");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error inserting hearing: " . mysqli_error($conn) . "</div>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Schedule Hearing - Case <?= htmlspecialchars($case_id) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-7">

<div class="card shadow">
<div class="card-header bg-dark text-white">
    <h5 class="mb-0">Court – Schedule Hearing</h5>
</div>

<form method="POST" class="card-body">
    <!-- Hidden Case ID -->
    <input type="hidden" name="case_id" value="<?= htmlspecialchars($case_id) ?>">

    <!-- Case Display -->
    <div class="mb-3">
        <label class="form-label">Case</label>
        <input class="form-control"
               value="<?= htmlspecialchars($case['case_id']) ?> - <?= htmlspecialchars($case['title']) ?>"
               disabled>
        <small class="text-muted">Current Status: <?= $case['status'] ?></small>
    </div>

    <!-- Hearing Date -->
    <div class="mb-3">
        <label class="form-label">Hearing Date</label>
        <input type="date" name="hearing_date" class="form-control" required>
    </div>

    <!-- Court Name -->
    <div class="mb-3">
        <label class="form-label">Court Name / Number</label>
        <input name="court_name"
               class="form-control"
               placeholder="Court No. 2"
               required>
    </div>

    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        <button class="btn btn-primary" name="schedule_hearing">
            Schedule Hearing
        </button>
    </div>
</form>

</div>
</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>