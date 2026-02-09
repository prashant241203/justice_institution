<?php
require_once("connect.php");

// 1️⃣ Current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // ek page me 5 records
$offset = ($page - 1) * $limit;

// 2️⃣ Total records
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM cases");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

// 3️⃣ Records for current page
$dataQuery = mysqli_query($conn, "SELECT * FROM cases ORDER BY case_id DESC LIMIT $limit OFFSET $offset");
$cases = [];
while($row = mysqli_fetch_assoc($dataQuery)) {
    $cases[] = $row;
}

// 4️⃣ Return JSON
echo json_encode([
    'cases' => $cases,
    'pages' => $totalPages
]);
