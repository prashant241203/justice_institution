<?php
require_once "connect.php";

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

/* total records */
$totalRes = mysqli_query(
  $conn,
  "SELECT COUNT(DISTINCT case_id) as total 
   FROM cases 
   WHERE case_id != ''"
);
$totalRow = mysqli_fetch_assoc($totalRes);
$total = $totalRow['total'];
$totalPages = ceil($total / $limit);

/* page data */
$sql = "
SELECT 
  c.case_id,
  c.title,
  c.date_filed,
  c.status,
  COUNT(h.hearing_id) AS hearing_count
FROM cases c
LEFT JOIN hearings h ON h.case_id = c.case_id
WHERE c.case_id != ''
GROUP BY c.case_id
ORDER BY c.case_id DESC
LIMIT $limit OFFSET $offset
";

$res = mysqli_query($conn, $sql);

$cases = [];
while ($row = mysqli_fetch_assoc($res)) {
  $cases[] = $row;
}

echo json_encode([
  "cases" => $cases,
  "pages" => $totalPages
]);
