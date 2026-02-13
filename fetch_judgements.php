<?php
require_once("connect.php");

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;


$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM judgements");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalPages = ceil($totalRow['total'] / $limit);


$dataQuery = mysqli_query($conn, "SELECT * FROM judgements ORDER BY judgement_id DESC LIMIT $limit OFFSET $offset");

$judgements = [];
while($row = mysqli_fetch_assoc($dataQuery)) {
    $judgements[] = $row;
}

echo json_encode([
    'judgements' => $judgements,
    'pages' => $totalPages
]);
