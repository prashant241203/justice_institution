<?php
require_once("connect.php");

$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

/* Total records */
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) FROM judgements");
$totalRows  = mysqli_fetch_row($totalQuery)[0];
$totalPages = ceil($totalRows / $limit);

/* Fetch paginated data */
$query = mysqli_query(
  $conn,
  "SELECT * FROM judgements
   ORDER BY judgement_id DESC
   LIMIT $limit OFFSET $offset"
);

$judgements = [];

while ($row = mysqli_fetch_assoc($query)) {
  $judgements[] = $row;
}

echo json_encode([
  "judgements" => $judgements,
  "pages" => $totalPages
]);
