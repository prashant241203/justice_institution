<?php
session_start();
require_once("connect.php");
require_once("auth_check.php");

/* =====================
   LOGIN & PERMISSION
===================== */
requireLogin();

if (!can('upload_document')) {
    header("Location: access_denied.php");
    exit;
}

$backUrl = isLawyer() ? 'lawyer_dashboard.php' : 'index.php';

/* =====================
   GET CASE ID
===================== */
if (!isset($_GET['case_id'])) {
    die("❌ Case ID missing");
}

$case_id = mysqli_real_escape_string($conn, $_GET['case_id']);

/* =====================
   FETCH CASE
===================== */
$caseQuery = mysqli_query($conn, "SELECT * FROM cases WHERE case_id='$case_id'");
$case = mysqli_fetch_assoc($caseQuery);

if (!$case) {
    die("❌ Case not found");
}

/* =====================
   LAWYER ACCESS CHECK
===================== */
if (isLawyer()) {
    if ($case['lawyer_id'] != $_SESSION['user_id']) {
        header("Location: access_denied.php");
        exit;
    }
}

/* =====================
   HANDLE UPLOAD
===================== */
if (isset($_POST['upload'])) {

    if (!isset($_FILES['document']) || $_FILES['document']['error'] != 0) {
        $error = "Please select a valid file";
    } else {

        $allowed = ['pdf','doc','docx','jpg','png'];
        $fileName = $_FILES['document']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Invalid file type";
        } else {

            $newName = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
            $uploadDir = "uploads/documents/";
            $filePath = $uploadDir . $newName;

            if (move_uploaded_file($_FILES['document']['tmp_name'], $filePath)) {

                $uid = $_SESSION['user_id'];

                mysqli_query($conn, "
                    INSERT INTO documents (case_id, uploaded_by, file_name, file_path)
                    VALUES ('$case_id', $uid, '$fileName', '$filePath')
                ");

                $success = "Document uploaded successfully";
            } else {
                $error = "File upload failed";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Document</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow">
<div class="card-header bg-dark text-white">
    <h5 class="mb-0">📎 Upload Document</h5>
</div>

<div class="card-body">

    <p>
        <strong>Case:</strong> <?= htmlspecialchars($case['case_id']) ?>  
        <br>
        <small><?= htmlspecialchars($case['title']) ?></small>
    </p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Select Document</label>
            <input type="file" name="document" class="form-control" required>
            <small class="text-muted">
                Allowed: PDF, DOC, DOCX, JPG, PNG
            </small>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= $backUrl ?>" class="btn btn-secondary mb-3">
                ← Back
            </a>

            <button type="submit" name="upload" class="btn btn-primary">
                Upload
            </button>
        </div>

    </form>

</div>
</div>

</div>
</div>
</div>

</body>
</html>
