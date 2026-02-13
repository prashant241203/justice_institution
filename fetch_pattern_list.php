<?php
require_once("connect.php");

$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;


$totalQ = mysqli_query($conn,"
  SELECT COUNT(*) as total
  FROM pattern_flags
  WHERE resolved='no'
");
$total = mysqli_fetch_assoc($totalQ)['total'];
$pages = ceil($total / $limit);


$q = mysqli_query($conn,"
  SELECT pf.flag_id, pf.case_id, pf.flag_type, pf.description, pf.created_at,
         c.title, c.status
  FROM pattern_flags pf
  JOIN cases c ON pf.case_id = c.case_id
  WHERE pf.resolved='no'
  ORDER BY pf.created_at DESC
  LIMIT $limit OFFSET $offset
");

$data = [];
while($r=mysqli_fetch_assoc($q)){
  $data[] = $r;
}

echo json_encode([
  "patterns" => $data,
  "pages" => $pages,
  "current" => $page
]);
