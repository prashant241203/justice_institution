<?php
session_start();
require_once("connect.php");
require_once("auth_check.php");

requireLogin();

$limit  = 10;
$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$userId = $_SESSION['user_id'];
$role   = $_SESSION['user_role'];

if ($role === 'lawyer') {
    $totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cases WHERE lawyer_id = $userId AND is_deleted = 0 ");
    $query = mysqli_query($conn, "SELECT * FROM cases WHERE lawyer_id = $userId AND is_deleted = 0 ORDER BY case_id DESC LIMIT $offset, $limit");
} else {
    $totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cases WHERE case_id != '' AND is_deleted = 0");
    $query = mysqli_query($conn, "SELECT * FROM cases WHERE case_id != '' AND is_deleted = 0 ORDER BY case_id DESC LIMIT $offset, $limit");
}

$totalCases = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalCases / $limit);

$cases = [];
while ($row = mysqli_fetch_assoc($query)) {
    $hearingCheck = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM hearings WHERE case_id = '".$row['case_id']."'");
    $hasHearing = mysqli_fetch_assoc($hearingCheck)['cnt'] > 0;

    $cases[] = [
        'case_id'     => $row['case_id'],
        'title'       => $row['title'],
        'date_filed'  => $row['date_filed'],
        'status'      => $row['status'],
        'has_hearing' => $hasHearing
    ];
}

echo json_encode([
    'cases'   => $cases,
    'pages'   => $totalPages,
    'current' => $page
]);
?>