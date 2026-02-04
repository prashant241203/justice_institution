<?php
require_once("connect.php");

// clear old flags (optional but clean)
mysqli_query($conn, "DELETE FROM pattern_flags");

// Rule 1: Pending/Open cases > 30 days
$q = mysqli_query($conn,"
  SELECT case_id, title, date_filed
  FROM cases
  WHERE status IN ('Open','Pending')
  AND DATEDIFF(NOW(), date_filed) > 30
");

while($c = mysqli_fetch_assoc($q)){
  $cid = $c['case_id'];
  $desc = "Case pending for more than 30 days";

  mysqli_query($conn,"
    INSERT INTO pattern_flags (case_id, flag_type, description)
    VALUES ('$cid','DELAY','$desc')
  ");
}

// Rule 2: Too many hearings for same case
$q2 = mysqli_query($conn,"
  SELECT case_id, COUNT(*) as total
  FROM hearings
  GROUP BY case_id
  HAVING total > 5
");

while($h = mysqli_fetch_assoc($q2)){
  $cid = $h['case_id'];
  $desc = "More than 5 hearings for this case";

  mysqli_query($conn,"
    INSERT INTO pattern_flags (case_id, flag_type, description)
    VALUES ('$cid','MANY_HEARINGS','$desc')
  ");
}

echo json_encode([
  "status" => "success",
  "message" => "Pattern detection completed"
]);
