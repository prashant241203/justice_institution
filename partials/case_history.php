<?php
$judgeId = $_SESSION['user_id'];
$closedCases = mysqli_query($conn, "
    SELECT c.case_id, c.title, c.date_filed, j.outcome
    FROM cases c
    LEFT JOIN judgements j ON c.case_id = j.case_id
    WHERE c.judge_id = '$judgeId' AND c.status='Closed'
    ORDER BY c.date_filed DESC
");
?> <div class="card">
  <div class="card-header bg-dark text-white">
    <h5>Case History</h5>
  </div>
  <div class="card-body"> <?php if(mysqli_num_rows($closedCases) > 0): ?> <table class="table table-hover">
      <thead>
        <tr>
          <th>Case ID</th>
          <th>Title</th>
          <th>Outcome</th>
          <th>Date Filed</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody> <?php while($c = mysqli_fetch_assoc($closedCases)): ?> <tr>
          <td> <?= htmlspecialchars($c['case_id']) ?> </td>
          <td> <?= htmlspecialchars($c['title']) ?> </td>
          <td> <?= htmlspecialchars($c['outcome'] ?? 'N/A') ?> </td>
          <td> <?= date('d M Y', strtotime($c['date_filed'])) ?> </td>
          <td>
            <a href="view_case.php?case_id=
							<?= urlencode($c['case_id']) ?>" class="btn btn-sm btn-info">View Case </a>
          </td>
        </tr> <?php endwhile; ?> </tbody>
    </table> <?php else: ?> <p>No closed cases found.</p> <?php endif; ?> </div>
</div>