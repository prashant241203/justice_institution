<?php
session_start();
require_once("connect.php");
require_once("auth_check.php");

requireLogin();
requireRole('lawyer');

$lawyer_id = $_SESSION['user_id'];


$limit = 5;
$page  = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;



$countQuery = mysqli_query($conn,"
    SELECT COUNT(*) as total 
    FROM cases 
    WHERE lawyer_id = $lawyer_id
");
$totalCases = mysqli_fetch_assoc($countQuery)['total'];
$totalPages = ceil($totalCases / $limit);

$query = mysqli_query($conn,"
    SELECT c.*,
    (SELECT COUNT(*) FROM hearings h WHERE h.case_id = c.case_id) AS hearing_count
    FROM cases c
    WHERE c.lawyer_id = $lawyer_id
    ORDER BY c.created_at DESC
    LIMIT $offset, $limit
");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Lawyer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-dark bg-primary px-4">
      <span class="navbar-brand">Lawyer Dashboard</span>
      <div class="text-white"> <?= $_SESSION['user_name'] ?> | Lawyer <a href="logout.php" class="btn btn-sm btn-light ms-3">Logout</a>
      </div>
    </nav>
    <div class="container mt-4">
      <div class="card mb-4 shadow-sm">
        <div class="card-body text-center">
          <h6>Total Assigned Cases</h6>
          <h2> <?= $totalCases ?> </h2>
        </div>
      </div>
      <div class="card shadow">
        <div class="card-header bg-dark text-white">My Assigned Cases</div>
        <table class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Case ID</th>
              <th>Title</th>
              <th>Status</th>
              <th>Hearings</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody> <?php
$i = $offset + 1;
while($case = mysqli_fetch_assoc($query)):
                        if ($case['status'] == 'Open') {
                            $badge = 'success';
                        } elseif ($case['status'] == 'Pending') {
                            $badge = 'warning';
                        } elseif ($case['status'] == 'Closed') {
                            $badge = 'secondary';
                        } else {
                            $badge = 'info';
                        }

?> <tr>
              <td> <?= $i++ ?> </td>
              <td> <?= htmlspecialchars($case['case_id']) ?> </td>
              <td> <?= htmlspecialchars($case['title']) ?> </td>
              <td>
                <span class="badge bg-
										<?= $badge ?>"> <?= $case['status'] ?> </span>
              </td>
              <td> <?= $case['hearing_count'] ?> </td>
              <td class="d-flex gap-1">
                <a href="view_case.php?case_id=
										<?= urlencode($case['case_id']) ?>" class="btn btn-sm btn-primary">View Case </a>
                <a href="view_hearing.php?case_id=
										<?= urlencode($case['case_id']) ?>#hearings" class="btn btn-sm btn-outline-secondary">Hearings </a> <?php if (can('upload_document')): ?> <a href="upload_document.php?case_id=
										<?= urlencode($case['case_id']) ?>&from=dashboard" class="btn btn-sm btn-outline-success">Upload Docs </a> <?php endif; ?>
              </td>
            </tr> <?php endwhile; ?> <?php if ($totalCases == 0): ?> <tr>
              <td colspan="6" class="text-center text-muted">No cases assigned yet</td>
            </tr> <?php endif; ?> </tbody>
        </table>
      </div> <?php if ($totalPages > 1): ?> <nav class="mt-3">
        <ul class="pagination justify-content-center"> <?php for($p=1;$p<=$totalPages;$p++): ?> <li class="page-item 
							<?= $p==$page?'active':'' ?>">
            <a class="page-link" href="?page=
								<?= $p ?>"> <?= $p ?> </a>
          </li> <?php endfor; ?> </ul>
      </nav> <?php endif; ?>
    </div>
  </body>
</html>