<?php
session_start();
require_once "connect.php";

// Agar user login check karna ho
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$created_by = $_SESSION['user_id'];

// Fetch approved lawyers
$lawyerQuery = "SELECT user_id, name FROM users WHERE role='lawyer' AND status='approved'";
$lawyerResult = mysqli_query($conn, $lawyerQuery);

// Insert case on form submit
if(isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $date_filed = $_POST['date_filed'];
    $status = $_POST['status'];
    $lawyer_id = $_POST['lawyer_id'];

    if(empty($title) || empty($date_filed) || empty($status) || empty($lawyer_id)) {
        $error = "Please fill all fields!";
    } else {
        $judge_id = NULL;

        // Generate custom case_id
        $lastCaseQuery = mysqli_query($conn, "SELECT case_id FROM cases ORDER BY case_id DESC LIMIT 1");
        $lastCase = mysqli_fetch_assoc($lastCaseQuery);

        if ($lastCase && !empty($lastCase['case_id']) && preg_match('/^C(\d+)$/', $lastCase['case_id'], $matches)) {
            $nextNumber = intval($matches[1]) + 1;
            $case_id = 'C' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $case_id = 'C001';
        }

        // Insert query with case_id
        $insertQuery = "INSERT INTO cases (case_id, title, date_filed, status, lawyer_id, judge_id, created_by, created_at) 
                        VALUES ('$case_id', '$title', '$date_filed', '$status', '$lawyer_id', '$judge_id', '$created_by', NOW())";

        if(mysqli_query($conn, $insertQuery)) {
            // Redirect to index page
            header("Location: index.php?msg=Case added successfully&case_id=$case_id");
            exit;
        } else {
            $error = "Error adding case: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Case</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Add New Case</h2>
        <!-- <a href="index.php" class="btn btn-secondary">Back</a> -->
    </div>

    <?php if(isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

    <form action="" method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="title" class="form-label">Case Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Enter case title" required>
        </div>

        <div class="mb-3">
            <label for="date_filed" class="form-label">Date Filed</label>
            <input type="date" class="form-control" name="date_filed" id="date_filed" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="Pending">Pending</option>
                <option value="Closed">Closed</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="lawyer_id" class="form-label">Assign Lawyer</label>
            <select class="form-select" name="lawyer_id" id="lawyer_id" required>
                <option value="">Select Lawyer</option>
                <?php while($lawyer = mysqli_fetch_assoc($lawyerResult)) { ?>
                    <option value="<?= $lawyer['user_id'] ?>"><?= htmlspecialchars($lawyer['name']) ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-secondary">Back</a>
            <button type="submit" name="submit" class="btn btn-primary">Add Case</button>
        </div>
    </form>
</div>
</body>
</html>
