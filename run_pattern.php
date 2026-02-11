<?php
require_once("connect.php");


mysqli_query($conn, "DELETE FROM pattern_flags");


$q = mysqli_query($conn,"
  SELECT case_id
  FROM cases
  WHERE status IN ('Open','Pending')
  AND DATEDIFF(NOW(), date_filed) > 5
");

while($c=mysqli_fetch_assoc($q)){
  $cid = $c['case_id'];

  mysqli_query($conn,"
    INSERT INTO pattern_flags (case_id,flag_type,description)
    SELECT '$cid','DELAY','Case pending more than 5 days'
    FROM DUAL
    WHERE NOT EXISTS (
      SELECT 1 FROM pattern_flags WHERE case_id='$cid'
    )
  ");
}



$q = mysqli_query($conn,"
  SELECT case_id FROM cases WHERE date_filed='0000-00-00'
");

while($c=mysqli_fetch_assoc($q)){
  $cid=$c['case_id'];

  mysqli_query($conn,"
    INSERT INTO pattern_flags (case_id,flag_type,description)
    SELECT '$cid','INVALID_DATE','Invalid case date'
    FROM DUAL
    WHERE NOT EXISTS (
      SELECT 1 FROM pattern_flags WHERE case_id='$cid'
    )
  ");
}


$q = mysqli_query($conn,"
  SELECT judge_id
  FROM cases
  GROUP BY judge_id
  HAVING COUNT(*) > 20
");

while($j=mysqli_fetch_assoc($q)){
  $jid=$j['judge_id'];

  mysqli_query($conn,"
    INSERT INTO pattern_flags (case_id,flag_type,description)
    SELECT case_id,'JUDGE_OVERLOAD','Judge overloaded'
    FROM cases
    WHERE judge_id='$jid'
    AND NOT EXISTS (
      SELECT 1 FROM pattern_flags WHERE case_id=cases.case_id
    )
    LIMIT 10
  ");
}


$q = mysqli_query($conn,"
  SELECT title
  FROM cases
  GROUP BY title
  HAVING COUNT(*) > 10
");

while($t=mysqli_fetch_assoc($q)){
  $title=$t['title'];

  mysqli_query($conn,"
    INSERT INTO pattern_flags (case_id,flag_type,description)
    SELECT case_id,'REPEATED_CASE','Repeated case type'
    FROM cases
    WHERE title='$title'
    AND NOT EXISTS (
      SELECT 1 FROM pattern_flags WHERE case_id=cases.case_id
    )
    LIMIT 5
  ");
}


echo json_encode([
  "status" => "success",
  "message" => "Pattern detection completed"
]);
?>