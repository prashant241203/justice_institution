<?php
$judgeId = $_SESSION['user_id'];
$caseDiary = mysqli_query($conn, "
    SELECT * FROM cases
    WHERE judge_id = '$judgeId'
    ORDER BY date_filed DESC
");
?> <div class="card">
  <div class="card-header bg-secondary text-white">
    <h5>Case Diary</h5>
  </div>
  <div class="card-body"> <?php if(mysqli_num_rows($caseDiary) > 0): ?> <table class="table table-hover">
      <thead>
        <tr>
          <th>Case ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody> <?php while($c = mysqli_fetch_assoc($caseDiary)): ?> <tr>
          <td> <?= htmlspecialchars($c['case_id']) ?> </td>
          <td> <?= htmlspecialchars($c['title']) ?> </td>
          <td> <?= $c['status'] ?> </td>
          <td>
            <a href="view_case.php?case_id=
							<?= urlencode($c['case_id']) ?>" class="btn btn-sm btn-info">View </a>
            <a href="edit_case.php?case_id=
							<?= urlencode($c['case_id']) ?>" class="btn btn-sm btn-warning">Edit </a>
          </td>
        </tr> <?php endwhile; ?> </tbody>
    </table> <?php else: ?> <p>No cases assigned.</p> <?php endif; ?> </div>
</div>