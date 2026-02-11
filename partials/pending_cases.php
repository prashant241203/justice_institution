<?php
$judgeId = $_SESSION['user_id'];

// Fetch pending cases for the logged-in judge only
$pendingCasesArray = [];
$result = mysqli_query($conn, "
    SELECT c.*, 
           COUNT(h.hearing_id) AS hearing_count,
           MAX(h.hearing_date) AS last_hearing
    FROM cases c
    LEFT JOIN hearings h ON c.case_id = h.case_id
    LEFT JOIN judgements j ON c.case_id = j.case_id
    WHERE c.status = 'Pending'
      AND c.judge_id = '$judgeId'
      AND j.case_id IS NULL
    GROUP BY c.case_id
    ORDER BY c.date_filed DESC
");

if($result){
    while($row = mysqli_fetch_assoc($result)){
        $pendingCasesArray[] = $row;
    }
} else {
    die("Query Error: " . mysqli_error($conn));
}
?>

<?php if(count($pendingCasesArray) > 0): ?>
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5>Pending Judgements</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover case-list">
                <thead>
                    <tr>
                        <th>Case ID</th>
                        <th>Title</th>
                        <th>Filed Date</th>
                        <th>Hearings</th>
                        <th>Last Hearing</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pendingCasesArray as $case): ?>
                    <tr>
                        <td><?= htmlspecialchars($case['case_id']) ?></td>
                        <td><?= htmlspecialchars($case['title']) ?></td>
                        <td><?= date('d M Y', strtotime($case['date_filed'])) ?></td>
                        <td><?= $case['hearing_count'] ?></td>
                        <td><?= ($case['last_hearing']) ? date('d M Y', strtotime($case['last_hearing'])) : 'None' ?></td>
                        <td>
                            <?php if ($case['hearing_count'] > 0): ?>
                                <a href="add_judgement.php?case_id=<?= urlencode($case['case_id']) ?>"
                                   class="btn btn-sm btn-success">
                                   <i class="bi bi-gavel"></i> Judge Now
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No hearings yet</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<p>No pending judgements.</p>
<?php endif; ?>
