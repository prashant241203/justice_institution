<?php
require_once("connect.php");

$limit  = 10;
$page   = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Total cases
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM cases WHERE case_id != ''");
$totalCases = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalCases / $limit);

// Fetch cases
$query = mysqli_query($conn, "SELECT * FROM cases WHERE case_id != '' ORDER BY case_id DESC LIMIT $offset, $limit");

$cases = [];
while($row = mysqli_fetch_assoc($query)){
    
    // Check if hearing exists
    $hearingCheck = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM hearings WHERE case_id = '".$row['case_id']."'");
    $hasHearing = mysqli_fetch_assoc($hearingCheck)['cnt'] > 0 ? true : false;

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
