<?php
session_start();
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once("connect.php");
$backUrl = "index.php";

$case_id = $_GET['case_id'] ?? '';
if(empty($case_id)) {
    echo "❌ Error: No case_id provided!";
    exit;
}

// Fetch case details
$caseQuery = mysqli_query($conn, "SELECT * FROM cases WHERE case_id='$case_id'");
if(mysqli_num_rows($caseQuery) == 0) {
    echo "❌ Error: Case not found!";
    exit;
}
$case = mysqli_fetch_assoc($caseQuery);

// Handle form submit
if(isset($_POST['update_case'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $judge_id = mysqli_real_escape_string($conn, $_POST['judge_id']);

    $updateQuery = "UPDATE cases 
                    SET title='$title', status='$status', judge_id='$judge_id'
                    WHERE case_id='$case_id'";
    if(mysqli_query($conn, $updateQuery)) {
        header("Location: view_case.php?case_id=" . urlencode($case_id));
        exit;
    } else {
        $error = "Error updating case: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Case - <?= htmlspecialchars($case['case_id']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Edit Case: <?= htmlspecialchars($case['case_id']) ?></h3>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" class="card p-4 shadow bg-white">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($case['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="Open" <?= $case['status']=='Open' ? 'selected' : '' ?>>Open</option>
                <option value="Pending" <?= $case['status']=='Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Closed" <?= $case['status']=='Closed' ? 'selected' : '' ?>>Closed</option>
                <option value="In Progress" <?= $case['status']=='In Progress' ? 'selected' : '' ?>>In Progress</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Judge ID</label>
            <input type="number" name="judge_id" class="form-control" value="<?= htmlspecialchars($case['judge_id']) ?>">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" name="update_case" class="btn btn-success">Update Case</button>
            <a href="judge_panel.php?case_id=<?= urlencode($case['case_id']) ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
