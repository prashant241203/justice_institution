<?php
require_once 'connect.php';

$month = intval($_GET['month']);
$year  = intval($_GET['year']);

$sql = "
SELECT 
  DAY(date_filed) AS day,
  COUNT(*) AS total
FROM cases
WHERE 
  MONTH(date_filed) = $month
  AND YEAR(date_filed) = $year
GROUP BY DAY(date_filed)
ORDER BY day ASC
";

$result = mysqli_query($conn, $sql);

$labels = [];
$counts = [];

while ($row = mysqli_fetch_assoc($result)) {
  $labels[] = 'Day ' . $row['day'];
  $counts[] = $row['total'];
}

echo json_encode([
  'labels' => $labels,
  'counts' => $counts
]);
