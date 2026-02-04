<?php
require_once("connect.php");

$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit  = 5;
$offset = ($page - 1) * $limit;

/* Total hearings */
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) FROM hearings");
$totalRows  = mysqli_fetch_row($totalQuery)[0];
$totalPages = ceil($totalRows / $limit);

/* Fetch paginated hearings */
$query = mysqli_query(
  $conn,
  "SELECT * FROM hearings
   ORDER BY hearing_id DESC
   LIMIT $limit OFFSET $offset"
);

$hearings = [];

while ($row = mysqli_fetch_assoc($query)) {
  $hearings[] = $row;
}

echo json_encode([
  "hearings" => $hearings,
  "pages" => $totalPages
]);
