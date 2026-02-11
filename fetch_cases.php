<?php
session_start();
require_once "connect.php";

$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Total pages
$totalCases = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM cases"))[0];
$pages = ceil($totalCases / $limit);

// Fetch cases for this page
$q = mysqli_query($conn, "SELECT * FROM cases ORDER BY case_id DESC LIMIT $limit OFFSET $offset");

$cases = [];
$currentUserRole = $_SESSION['role'] ?? '';
$currentUserId = $_SESSION['user_id'] ?? 0;

while($r = mysqli_fetch_assoc($q)) {
    $r['can_judgement'] = false;

    // Assigned judge logic: only assigned judge sees Judgement button
    $assignedJudgeId = $r['judge_id'] ?? 0;

    if ($currentUserRole === 'judge' && $r['status'] === 'Pending' && $currentUserId == $assignedJudgeId) {
        $r['can_judgement'] = true;
    }

    $cases[] = $r;
}

echo json_encode([
    'cases' => $cases,
    'pages' => $pages,
    'current' => (int)$page,
    'is_judge' => ($currentUserRole === 'judge')
]);
?>
