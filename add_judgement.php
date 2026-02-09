<?php
session_start();

require_once("connect.php");      // 1️⃣ DB
require_once("auth_check.php");   // 2️⃣ auth + can()

// 3️⃣ LOGIN CHECK
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

// 4️⃣ ROLE / PERMISSION CHECK
if (!can('add_hearing')) {
    header("Location: access_denied.php");
    exit;
}

/* =====================
   JUDGE ADDS JUDGEMENT
===================== */
if (isset($_POST['add_judgement'])) {
    $case_id = mysqli_real_escape_string($conn, $_POST['case_id']);
    $date    = $_POST['judgement_date'];
    $outcome = mysqli_real_escape_string($conn, $_POST['outcome']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);
    
    // Generate judgement_id
    $lastJudgementQuery = mysqli_query($conn, "SELECT judgement_id FROM judgements ORDER BY judgement_id DESC LIMIT 1");
    $lastJudgement = mysqli_fetch_assoc($lastJudgementQuery);
    
    if ($lastJudgement && !empty($lastJudgement['judgement_id']) && preg_match('/^J(\d+)$/', $lastJudgement['judgement_id'], $matches)) {
        $nextNumber = intval($matches[1]) + 1;
        $judgement_id = 'J' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    } else {
        $judgement_id = 'J001';
    }

    // Insert judgement with summary
    $insertQuery = "INSERT INTO judgements (judgement_id, case_id, judgement_date, outcome, summary)
                   VALUES ('$judgement_id', '$case_id', '$date', '$outcome', '$summary')";
    
    if(mysqli_query($conn, $insertQuery)) {
        // Update case status to Closed
        mysqli_query($conn, "UPDATE cases SET status='Closed' WHERE case_id = '$case_id'");
        
        header("Location: index.php#judgements");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

/* =====================
   GET CASE ID FROM URL (if coming from index.php)
===================== */
$case_id_from_url = isset($_GET['case_id']) ? trim($_GET['case_id']) : '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Deliver Judgement</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-8">

<?php if(isset($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow">
<div class="card-header bg-success text-white">
<h5 class="mb-0">Judge – Deliver Judgement</h5>
</div>

<form method="POST" class="card-body">

<div class="mb-3">
<label class="form-label">Case</label>
<select name="case_id" class="form-control" required id="caseSelect">
<option value="">Select Case</option>
<?php
// Get only cases that have hearings and are not closed
$cases = mysqli_query($conn, 
    "SELECT c.case_id, c.title, c.status, 
            COUNT(h.hearing_id) as hearing_count 
     FROM cases c 
     LEFT JOIN hearings h ON c.case_id = h.case_id 
     WHERE c.status != 'Closed' 
     GROUP BY c.case_id 
     HAVING hearing_count > 0 
     ORDER BY c.case_id DESC");

if(mysqli_num_rows($cases) == 0) {
    echo "<option value='' disabled>No cases available for judgement (need hearings and open status)</option>";
} else {
    while($c = mysqli_fetch_assoc($cases)){
        $selected = ($case_id_from_url == $c['case_id']) ? 'selected' : '';
        echo "<option value='" . htmlspecialchars($c['case_id']) . "' $selected>" . 
             htmlspecialchars($c['case_id']) . " - " . htmlspecialchars($c['title']) . 
             " (Hearings: " . $c['hearing_count'] . ")</option>";
    }
}
?>
</select>
<?php if(!empty($case_id_from_url)): ?>
<small class="text-muted">Case ID from URL: <?= htmlspecialchars($case_id_from_url) ?></small>
<?php endif; ?>
</div>

<div class="mb-3">
<label class="form-label">Judgement Date</label>
<input type="date" name="judgement_date" class="form-control" required 
       value="<?= date('Y-m-d') ?>">
</div>

<div class="mb-3">
<label class="form-label">Outcome</label>
<select name="outcome" class="form-control" required>
  <option value="">Select Outcome</option>
  <option value="Convicted">Convicted</option>
  <option value="Acquitted">Acquitted</option>
  <option value="Dismissed">Dismissed</option>
  <option value="Settlement">Settlement</option>
  <option value="Appeal Allowed">Appeal Allowed</option>
  <option value="Appeal Dismissed">Appeal Dismissed</option>
  <option value="Guilty">Guilty</option>
  <option value="Not Guilty">Not Guilty</option>
  <option value="Case Withdrawn">Case Withdrawn</option>
  <option value="Fine Imposed">Fine Imposed</option>
  <option value="Imprisonment">Imprisonment</option>
  <option value="Probation">Probation</option>
</select>
</div>

<div class="mb-3">
<label class="form-label">Judgement Summary</label>
<textarea name="summary" class="form-control" rows="5" 
          placeholder="Enter detailed judgement summary including key points, reasoning, and final decision..." required></textarea>
<small class="text-muted">Provide a comprehensive summary of the judgement.</small>
</div>

<div class="d-flex justify-content-between mt-4">
<a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
<button class="btn btn-success" name="add_judgement">
✓ Deliver Judgement
</button>
</div>

</form>
</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-select case if coming from URL
document.addEventListener('DOMContentLoaded', function() {
    const urlCaseId = "<?= addslashes($case_id_from_url) ?>";
    const caseSelect = document.getElementById('caseSelect');
    
    if(urlCaseId && caseSelect) {
        // Check if the option exists
        for(let i = 0; i < caseSelect.options.length; i++) {
            if(caseSelect.options[i].value === urlCaseId) {
                caseSelect.value = urlCaseId;
                break;
            }
        }
    }
});
</script>
</body>
</html>