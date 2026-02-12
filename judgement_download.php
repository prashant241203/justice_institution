<?php
require_once "connect.php";

$case_id = $_GET['case_id'] ?? '';
$q = mysqli_query($conn,"SELECT judgement_file FROM cases WHERE case_id='$case_id'");
$row = mysqli_fetch_assoc($q);

if (!$row || !$row['judgement_file']) {
    die("Judgement not available");
}

$file = $row['judgement_file'];
header("Content-Disposition: attachment; filename=".basename($file));
readfile($file);
exit;
?>