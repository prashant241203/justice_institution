<?php
if (!isset($conn)) exit; 

$judgeId = $_SESSION['user_id'];


$resultJudged = mysqli_query($conn, "SELECT COUNT(*) FROM judgements j
                                     JOIN cases c ON j.case_id = c.case_id
                                     WHERE c.judge_id = '$judgeId'");
$casesJudged = ($resultJudged) ? mysqli_fetch_row($resultJudged)[0] : 0;


$resultPending = mysqli_query($conn, "SELECT COUNT(*) FROM cases c
                                      LEFT JOIN judgements j ON c.case_id = j.case_id
                                      WHERE c.judge_id = '$judgeId'
                                        AND c.status = 'Pending'
                                        AND j.case_id IS NULL");
$pendingDecisions = ($resultPending) ? mysqli_fetch_row($resultPending)[0] : 0;

$resultClosed = mysqli_query($conn, "SELECT COUNT(*) FROM cases 
                                     WHERE judge_id = '$judgeId'
                                       AND status = 'Closed'");
$totalClosed = ($resultClosed) ? mysqli_fetch_row($resultClosed)[0] : 0;
    

$clearanceRate = ($totalClosed > 0) ? round(($casesJudged / $totalClosed) * 100) : 0;
?> <div class="card">
  <div class="card-header bg-info text-white">
    <h5>Your Performance</h5>
  </div>
  <div class="card-body">
    <div class="row text-center">
      <div class="col-md-4 mb-3">
        <h6>Cases Judged</h6>
        <h3 class="text-success"> <?= $casesJudged ?> </h3>
      </div>
      <div class="col-md-4 mb-3">
        <h6>Pending Decisions</h6>
        <h3 class="text-warning"> <?= $pendingDecisions ?> </h3>
      </div>
      <div class="col-md-4 mb-3">
        <h6>Clearance Rate</h6>
        <h3 class="text-info"> <?= $clearanceRate ?>% </h3>
      </div>
    </div>
  </div>
</div>