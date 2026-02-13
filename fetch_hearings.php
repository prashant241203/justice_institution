<?php
require_once("connect.php");

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;


$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM hearings");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalPages = ceil($totalRow['total'] / $limit);


$dataQuery = mysqli_query($conn, "SELECT * FROM hearings ORDER BY hearing_id DESC LIMIT $limit OFFSET $offset");

$hearings = [];
while($row = mysqli_fetch_assoc($dataQuery)) {
    $hearings[] = $row;
}

echo json_encode([
    'hearings' => $hearings,
    'pages' => $totalPages
]);
