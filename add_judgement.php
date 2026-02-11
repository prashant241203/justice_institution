<?php
session_start();

require_once("connect.php");
require_once("auth_check.php");

/* =====================
   AUTH CHECK
===================== */
requireLogin();
requireRole('judge');

if (!can('add_judgement')) {
    header("Location: access_denied.php");
    exit;
}

/* =====================
   ADD JUDGEMENT
===================== */
if (isset($_POST['add_judgement'])) {

    $case_id = mysqli_real_escape_string($conn, $_POST['case_id']);
    $date    = $_POST['judgement_date'];
    $outcome = mysqli_real_escape_string($conn, $_POST['outcome']);
    $summary = mysqli_real_escape_string($conn, $_POST['summary']);

    /* 🔒 SAFETY CHECK:
       Case must be Pending + must have hearing
    */
    $checkCase = mysqli_query($conn, "
        SELECT c.case_id, c.status, COUNT(h.hearing_id) AS hearing_count
        FROM cases c
        LEFT JOIN hearings h ON c.case_id = h.case_id
        WHERE c.case_id = '$case_id'
        GROUP BY c.case_id
    ");

    $caseData = mysqli_fetch_assoc($checkCase);

    if (!$caseData || $caseData['status'] !== 'Pending' || $caseData['hearing_count'] == 0) {
        $error = "Judgement allowed only for Pending cases with at least one hearing.";
    } else {

        /* Generate judgement_id */
        $last = mysqli_query($conn, "SELECT judgement_id FROM judgements ORDER BY judgement_id DESC LIMIT 1");
        $row  = mysqli_fetch_assoc($last);

        if ($row && preg_match('/^J(\d+)$/', $row['judgement_id'], $m)) {
            $judgement_id = 'J' . str_pad($m[1] + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $judgement_id = 'J001';
        }

        /* Insert judgement */
        $insert = mysqli_query($conn, "
            INSERT INTO judgements 
            (judgement_id, case_id, judgement_date, outcome, summary)
            VALUES 
            ('$judgement_id', '$case_id', '$date', '$outcome', '$summary')
        ");

        if ($insert) {
            mysqli_query($conn, "UPDATE cases SET status='Closed' WHERE case_id='$case_id'");
            header("Location: index.php#judgements");
            exit;
        } else {
            $error = mysqli_error($conn);
        }
    }
}

/* =====================
   CASE FROM URL
===================== */
$case_id_from_url = $_GET['case_id'] ?? '';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
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
/* ✅ ONLY:
   - Pending cases
   - At least 1 hearing
*/
$cases = mysqli_query($conn, 
    "SELECT c.case_id, c.title, c.status,
            COUNT(h.hearing_id) AS hearing_count
     FROM cases c
     INNER JOIN hearings h ON c.case_id = h.case_id
     WHERE c.status = 'Pending'
     GROUP BY c.case_id
     HAVING hearing_count > 0
     ORDER BY c.case_id DESC"
);

if (mysqli_num_rows($cases) == 0) {
    echo "<option disabled>No Pending cases with hearings</option>";
}

while ($c = mysqli_fetch_assoc($cases)) {
    $selected = ($case_id_from_url == $c['case_id']) ? 'selected' : '';
    echo "<option value='{$c['case_id']}' $selected>
            {$c['case_id']} - {$c['title']} (Hearings: {$c['hearing_count']})
          </option>";
}
?>
</select>
</div>

<div class="mb-3">
<label>Judgement Date</label>
<input type="date" name="judgement_date" class="form-control" required value="<?= date('Y-m-d') ?>">
</div>

<div class="mb-3">
<label>Outcome</label>
<select name="outcome" class="form-control" required>
<option value="">Select Outcome</option>
<option>Convicted</option>
<option>Acquitted</option>
<option>Dismissed</option>
<option>Settlement</option>
<option>Guilty</option>
<option>Not Guilty</option>
</select>
</div>

<div class="mb-3">
<label>Judgement Summary</label>
<textarea name="summary" class="form-control" rows="5" required></textarea>
</div>

<div class="d-flex justify-content-between">
<a href="index.php" class="btn btn-secondary">← Back</a>
<button name="add_judgement" class="btn btn-success">✓ Deliver Judgement</button>
</div>

</form>
</div>
</div>
</div>
</div>
</body>
</html>
