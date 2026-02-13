<?php
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

$judgeId = $_SESSION['user_id'];

$todayHearings = mysqli_query($conn, "
    SELECT h.*, c.title
    FROM hearings h
    JOIN cases c ON h.case_id = c.case_id
    WHERE c.judge_id = '$judgeId'
    AND DATE(h.hearing_date) = '$today'
");

?> <div class="card">
  <div class="card-header bg-primary text-white">
    <h5>Today's Hearings</h5>
  </div>
  <div class="card-body"> <?php if(mysqli_num_rows($todayHearings) > 0): ?> <table class="table table-hover">
      <thead>
        <tr>
          <th>Case ID</th>
          <th>Title</th>
          <th>Hearing Date</th>
          <th>Court Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody> <?php while($h = mysqli_fetch_assoc($todayHearings)): ?> <tr>
          <td> <?= htmlspecialchars($h['case_id']) ?> </td>
          <td> <?= htmlspecialchars($h['title']) ?> </td>
          <td> <?= date('d M Y', strtotime($h['hearing_date'])) ?> </td>
          <td> <?= htmlspecialchars($h['court_name']) ?> </td>
          <td>
            <a href="view_case.php?case_id=
							<?= urlencode($h['case_id']) ?>" class="btn btn-sm btn-info">View Case </a>
          </td>
        </tr> <?php endwhile; ?> </tbody>
    </table> <?php else: ?> <p>No hearings scheduled for today.</p> <?php endif; ?> </div>
</div>