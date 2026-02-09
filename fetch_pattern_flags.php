<?php
require_once("connect.php");

$result = mysqli_query($conn, "
    SELECT DATE(created_at) as date, COUNT(*) as count
    FROM pattern_flags
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) ASC
");

$data = ['labels'=>[], 'counts'=>[]];
while($row = mysqli_fetch_assoc($result)){
    $data['labels'][] = $row['date'];
    $data['counts'][] = (int)$row['count'];
}

header('Content-Type: application/json');
echo json_encode($data);
