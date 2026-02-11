<?php
require_once("connect.php");

$q = mysqli_query($conn,"
  SELECT flag_type, COUNT(DISTINCT case_id) as total
  FROM pattern_flags
  WHERE resolved='no'
  GROUP BY flag_type
");

$data = ['labels'=>[], 'counts'=>[]];
while($r=mysqli_fetch_assoc($q)){
  $data['labels'][] = $r['flag_type'];
  $data['counts'][] = (int)$r['total'];
}

header('Content-Type: application/json');
echo json_encode($data);
?>