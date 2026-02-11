<?php
session_start();
require_once("auth_check.php");
requireLogin();
requireRoles(['admin','judge','lawyer','clerk','analyst']);
require_once("connect.php");

/* =====================
   CSV EXPORT FUNCTIONS
===================== */
if (isset($_GET['export'])) {

  if (!can('export_data')) {
        header("Location: access_denied.php");
        exit;
    }

    $type = $_GET['export'];
    
    switch($type) {
        case 'cases':
            exportCSV('cases', 'cases_export_' . date('Y-m-d') . '.csv');
            break;
        case 'hearings':
            exportCSV('hearings', 'hearings_export_' . date('Y-m-d') . '.csv');
            break;
        case 'judgements':
            exportCSV('judgements', 'judgements_export_' . date('Y-m-d') . '.csv');
            break;
        default:
            header("Location: index.php");
            exit;
    }
}
function exportCSV($table, $filename) {
    global $conn;

    /* =========================
       1️⃣ WHITELIST TABLES
    ========================== */
    $allowedTables = ['cases', 'hearings', 'judgements'];

    if (!in_array($table, $allowedTables, true)) {
        die("Invalid export request");
    }

    /* =========================
       2️⃣ SAFE QUERY
    ========================== */
    $query = "SELECT * FROM `$table`";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    /* =========================
       3️⃣ CSV OUTPUT
    ========================== */
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    // Column headers
    foreach (mysqli_fetch_fields($result) as $field) {
        $headers[] = $field->name;
    }
    fputcsv($output, $headers);

    // Rows
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}


/* =====================
   REPORT DATA
===================== */
// Case Status Distribution
$statusData = [
  'Open' => 0,
  'Pending' => 0,
  'Closed' => 0
];
$statusQuery = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM cases WHERE case_id != '' GROUP BY status");
while($row = mysqli_fetch_assoc($statusQuery)) {
  if(isset($statusData[$row['status']])) {
    $statusData[$row['status']] = $row['count'];
  }
}

// Judgement Outcomes
$outcomeData = [];
$outcomeQuery = mysqli_query($conn, "SELECT outcome, COUNT(*) as count FROM judgements GROUP BY outcome");
while($row = mysqli_fetch_assoc($outcomeQuery)) {
  $outcomeData[$row['outcome']] = $row['count'];
}

// Monthly Case Data (last 6 months)
$monthlyData = [];
for($i = 5; $i >= 0; $i--) {
  $month = date('Y-m', strtotime("-$i months"));
  $query = mysqli_query($conn, 
    "SELECT 
      SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) as open,
      SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
      SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed
     FROM cases 
     WHERE DATE_FORMAT(date_filed, '%Y-%m') = '$month'");
  $data = mysqli_fetch_assoc($query);
  $monthlyData[] = [
    'month' => date('M Y', strtotime($month . '-01')),
    'open' => $data['open'] ?? 0,
    'pending' => $data['pending'] ?? 0,
    'closed' => $data['closed'] ?? 0
  ];
}

/* =====================
   ADD CASE - FIXED for varchar case_id
===================== */
if (isset($_POST['add_case'])) {

    if (!can('add_case')) {
        header("Location: access_denied.php");
        exit;
    }

    $title  = $_POST['title'];
    $date   = $_POST['date_filed'];
    $status = $_POST['status'];
    
    // Generate a unique case_id (like C001, C002, etc.)
    $lastCaseQuery = mysqli_query($conn, "SELECT case_id FROM cases ORDER BY case_id DESC LIMIT 1");
    $lastCase = mysqli_fetch_assoc($lastCaseQuery);
    
    if ($lastCase && !empty($lastCase['case_id']) && preg_match('/^C(\d+)$/', $lastCase['case_id'], $matches)) {
        $nextNumber = intval($matches[1]) + 1;
        $case_id = 'C' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    } else {
        $case_id = 'C001';
    }

      $stmt = $conn->prepare(
      "INSERT INTO cases (case_id, title, date_filed, status)
      VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $case_id, $title, $date, $status);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

/* =====================
   DASHBOARD COUNTS
===================== */
$totalCases      = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM cases WHERE case_id != ''"))[0];
$totalHearings   = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM hearings"))[0];
$totalJudgements = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM judgements"))[0];
$patternFlags    = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM pattern_flags"))[0];





/* =====================
   TABLE DATA
===================== */
// Only get cases with valid case_id
$cases = mysqli_query($conn, "SELECT * FROM cases WHERE case_id != '' ORDER BY case_id DESC");
$hearings   = mysqli_query($conn,"SELECT * FROM hearings ORDER BY hearing_id DESC");
$judgements = mysqli_query($conn,"SELECT * FROM judgements ORDER BY judgement_id DESC");

/* =====================
   CHART DATA
===================== */
$caseTitles = [];
$q = mysqli_query($conn,"SELECT title FROM cases WHERE case_id != ''");
while($r = mysqli_fetch_assoc($q)){
    $caseTitles[] = $r['title'];
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Justice & Institutions</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
body { background:#f5f7fb; }
.app-header { background:#0a66c2; color:#fff; }
.sidebar { background:#fff; border-right:1px solid #ddd; min-height:100vh; }
.sidebar .nav-link { color:#333; }
.sidebar .nav-link.active { font-weight:bold; color:#0a66c2; }
.ms-btn { background:#0a66c2; color:#fff; border:none; padding:8px 12px; border-radius:4px; }
.card { border-radius:6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: box-shadow 0.3s ease; }
.card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
.progress { border-radius: 10px; }
.progress-bar { border-radius: 10px; }
.table th { background-color: #f8f9fa; font-weight: 600; }
</style>
</head>

<body>

<!-- ================= HEADER ================= -->
<header class="app-header py-2 shadow-sm">
  <div class="container-fluid d-flex align-items-center">
    <h5 class="mb-0 me-auto">Justice & Institutions</h5>
    <div class="d-flex align-items-center">
      <?php
      // require_once("auth_check.php");
      $user = getUserInfo();
      ?>
      <span class="text-light me-3">
        <i class="bi bi-person-circle"></i> 
        <?php echo htmlspecialchars($user['name']); ?>
        <?php echo getRoleBadge(); ?>
      </span>
      
      <!-- Show role-specific buttons -->
      <?php if(isAdmin()): ?>
        <a href="admin_panel.php" class="btn btn-sm btn-danger me-2">
          <i class="bi bi-shield-check"></i> Admin
        </a>
      <?php endif; ?>
      
      <?php if(isJudge()): ?>
        <a href="judge_panel.php" class="btn btn-sm btn-success me-2">
          <i class="bi bi-gavel"></i> Judge
        </a>
      <?php endif; ?>
      
      <a href="search.php" class="btn btn-sm btn-outline-light me-2">
        <i class="bi bi-search"></i> Search
      </a>
      <a href="logout.php" class="btn btn-sm btn-outline-light">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </div>
</header>


<div class="container-fluid">
<div class="row">

<!-- ================= SIDEBAR ================= -->
<nav class="col-lg-2 sidebar p-3">
  <h6>Navigation</h6>
  <ul class="nav flex-column mb-4">
    <li class="nav-item"><a class="nav-link active" href="#dashboard">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="#caseMaster">Case Master</a></li>
    <li class="nav-item"><a class="nav-link" href="#hearings">Hearings</a></li>
    <li class="nav-item"><a class="nav-link" href="#judgements">Judgements</a></li>
    <li class="nav-item"><a class="nav-link" href="#reports">Reports</a></li>
  </ul>

  <!-- QUICK ACTIONS -->
  <div class="d-grid gap-2">
      <?php if (can('add_case')): ?>
        <button data-bs-toggle="modal" data-bs-target="#addCase">
          + Add Case
        </button>
      <?php endif; ?>
    <button class="btn btn-outline-primary" onclick="runPatternDetection()">
      ▶ Run Pattern Detection
    </button>
  </div>
</nav>

<!-- ================= MAIN ================= -->
<main class="col-lg-10 p-3">

<!-- ===== DASHBOARD ===== -->
<section id="dashboard" class="mb-4">
<div class="row g-3">
  <div class="col-md-3"><div class="card p-3"><small>Total Cases</small><h4><?= $totalCases ?></h4></div></div>
  <div class="col-md-3"><div class="card p-3"><small>Hearings Scheduled</small><h4><?= $totalHearings ?></h4></div></div>
  <div class="col-md-3"><div class="card p-3"><small>Judgements Issued</small><h4><?= $totalJudgements ?></h4></div></div>
  <div class="col-md-3"><div class="card p-3"><small>Pattern Flags</small><h4><?= $patternFlags ?></h4></div></div>

  <div class="col-12">
    <div class="card p-3">
      <div class="row mb-3">
  <div class="col-md-3">
    <select id="filterMonth" class="form-select">
      <?php
      for ($m = 1; $m <= 12; $m++) {
        $selected = ($m == date('n')) ? 'selected' : '';
        echo "<option value='$m' $selected>" . date('F', mktime(0,0,0,$m,1)) . "</option>";
      }
      ?>
    </select>
  </div>

  <div class="col-md-3">
    <select id="filterYear" class="form-select">
      <?php
      for ($y = date('Y'); $y >= date('Y') - 5; $y--) {
        echo "<option value='$y'>$y</option>";
      }
      ?>
    </select>
  </div>
</div>

      <canvas id="monthlyCaseChart"></canvas>
    </div>
  </div>
</div>
</section>

<!-- ===== CASE MASTER ===== -->
<section id="caseMaster" class="mb-4">
  <div class="card p-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Case Master</h5>

      <div>
        <a href="?export=cases" class="btn btn-outline-secondary btn-sm me-2">
          📥 Export CSV
        </a>

        <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#addCase">
          + Add Case
        </button>
      </div>
    </div>

    <!-- Case Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Case ID</th>
          <th>Title</th>
          <th>Date Filed</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>

      <!-- 🔥 AJAX will fill this -->
      <tbody id="caseBody">
        <tr>
          <td colspan="5" class="text-center">
            Loading cases...
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <ul class="pagination justify-content-center mt-3"
        id="casePagination"></ul>
  </div>
</section>

<!-- ===== HEARINGS ===== -->
<section id="hearings" class="mb-4">
  <div class="card p-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Hearings</h5>
      <div>
        <a href="?export=hearings" class="btn btn-outline-secondary btn-sm">
          📥 Export CSV
        </a>
      </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Hearing ID</th>
          <th>Case ID</th>
          <th>Date</th>
          <th>Court</th>
        </tr>
      </thead>

      <!-- 🔥 AJAX fills this -->
      <tbody id="hearingBody">
        <tr>
          <td colspan="4" class="text-center">Loading hearings...</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <ul class="pagination justify-content-center mt-3"
        id="hearingPagination"></ul>

  </div>
</section>


<!-- ===== JUDGEMENTS ===== -->
<section id="judgements" class="mb-4">
  <div class="card p-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0">Judgements</h5>
      <div>
        <a href="?export=judgements" class="btn btn-outline-secondary btn-sm">
          📥 Export CSV
        </a>
      </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Judgement ID</th>
          <th>Case ID</th>
          <th>Date</th>
          <th>Outcome</th>
          <th>Summary</th>
        </tr>
      </thead>

      <!-- 🔥 AJAX will fill this -->
      <tbody id="judgementBody">
        <tr>
          <td colspan="5" class="text-center">Loading judgements...</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <ul class="pagination justify-content-center mt-3"
        id="judgementPagination"></ul>

  </div>
</section>

<!-- ===== REPORTS ===== -->
<section id="reports">
<div class="card p-3">
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Judicial Analytics Reports</h5>
  <div>
    <button class="btn btn-outline-secondary btn-sm me-2">
      📊 Export Report
    </button>
  </div>
</div>

<div class="row mb-4">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body text-center">
        <h6>Case Status Distribution</h6>
        <canvas id="statusChart" height="150"></canvas>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-body text-center">
        <h6>Judgement Outcomes</h6>
        <canvas id="outcomeChart" height="150"></canvas>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h6>Case Performance</h6>
        <div class="mt-3">
          <div class="d-flex justify-content-between mb-2">
            <span>Average Case Duration</span>
            <strong>
              <?php
              $durationQuery = mysqli_query($conn, 
                "SELECT AVG(DATEDIFF(NOW(), date_filed)) as avg_days 
                 FROM cases WHERE status = 'Closed'");
              $duration = mysqli_fetch_assoc($durationQuery);
              echo round($duration['avg_days'] ?? 0) . " days";
              ?>
            </strong>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Clearance Rate</span>
            <strong>
              <?php
              $totalClosed = mysqli_fetch_row(mysqli_query($conn, 
                "SELECT COUNT(*) FROM cases WHERE status = 'Closed'"))[0];
              $clearanceRate = ($totalCases > 0) ? round(($totalClosed / $totalCases) * 100) : 0;
              echo $clearanceRate . "%";
              ?>
            </strong>
          </div>
          <div class="d-flex justify-content-between">
            <span>Pending > 30 days</span>
            <strong class="text-danger">
              <?php
              $pendingQuery = mysqli_query($conn, 
                "SELECT COUNT(*) as count FROM cases 
                 WHERE status IN ('Open', 'Pending') 
                 AND DATEDIFF(NOW(), date_filed) > 30");
              $pending = mysqli_fetch_assoc($pendingQuery);
              echo $pending['count'] ?? 0;
              ?>
            </strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h6>Monthly Case Timeline</h6>
        <canvas id="monthlyChart" height="80"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h6>Case Summary Report</h6>
        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Category</th>
                <th>Open</th>
                <th>Pending</th>
                <th>Closed</th>
                <th>Total</th>
                <th>% Complete</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Case Type Analysis (You can categorize by title keywords)
              $caseTypes = ['Property', 'Criminal', 'Civil', 'Family', 'Commercial'];
              
              foreach($caseTypes as $type) {
                $typeQuery = mysqli_query($conn, 
                  "SELECT 
                    SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) as open_count,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed_count,
                    COUNT(*) as total
                   FROM cases 
                   WHERE title LIKE '%$type%' AND case_id != ''");
                
                $typeData = mysqli_fetch_assoc($typeQuery);
                $totalType = $typeData['total'] ?? 0;
                $percent = ($totalType > 0) ? round(($typeData['closed_count'] / $totalType) * 100) : 0;
                
                if($totalType > 0) {
                  echo "<tr>
                    <td>$type Cases</td>
                    <td>{$typeData['open_count']}</td>
                    <td>{$typeData['pending_count']}</td>
                    <td>{$typeData['closed_count']}</td>
                    <td>$totalType</td>
                    <td>
                      <div class='progress' style='height: 20px;'>
                        <div class='progress-bar' role='progressbar' 
                             style='width: {$percent}%' 
                             aria-valuenow='{$percent}' 
                             aria-valuemin='0' 
                             aria-valuemax='100'>
                          {$percent}%
                        </div>
                      </div>
                    </td>
                  </tr>";
                }
              }
              ?>
              <!-- Overall Summary -->
              <?php
              $summaryQuery = mysqli_query($conn, 
                "SELECT 
                  SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) as open_count,
                  SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                  SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) as closed_count
                 FROM cases WHERE case_id != ''");
              
              $summary = mysqli_fetch_assoc($summaryQuery);
              $overallPercent = ($totalCases > 0) ? 
                round(($summary['closed_count'] / $totalCases) * 100) : 0;
              ?>
              <tr class="table-active">
                <td><strong>OVERALL</strong></td>
                <td><strong><?= $summary['open_count'] ?? 0 ?></strong></td>
                <td><strong><?= $summary['pending_count'] ?? 0 ?></strong></td>
                <td><strong><?= $summary['closed_count'] ?? 0 ?></strong></td>
                <td><strong><?= $totalCases ?></strong></td>
                <td>
                  <div class='progress' style='height: 20px;'>
                    <div class='progress-bar bg-success' role='progressbar' 
                         style='width: {$overallPercent}%' 
                         aria-valuenow='{$overallPercent}' 
                         aria-valuemin='0' 
                         aria-valuemax='100'>
                      <strong><?= $overallPercent ?>%</strong>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h6>Pattern Detection Analysis</h6>
        <canvas id="patternChart" width="600" height="400"></canvas>
      </div>
    </div>
  </div>
</div>

</div>
</section>
<section id="patternFlagsSection" class="mb-4 mt-3">
  <div class="card p-3">
    <h5>Detected Pattern Flags</h5>

    <table class="table table-bordered table-sm mt-2">
      <thead>
          <tr>
            <th>Case</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
      </thead>
      <tbody id="patternBody">
        <?php
$pf = mysqli_query($conn,"
    SELECT case_id, flag_type, description, created_at
    FROM pattern_flags
    ORDER BY created_at DESC
");

if (!$pf) {
    echo "<tr>
            <td colspan='4' class='text-danger text-center'>
              SQL Error: " . mysqli_error($conn) . "
            </td>
          </tr>";
}
elseif (mysqli_num_rows($pf) == 0) {
    echo "<tr>
            <td colspan='4' class='text-center'>
              No patterns detected
            </td>
          </tr>";
}
else {
    while ($p = mysqli_fetch_assoc($pf)) {
        echo "
        <tr>
          <td>{$p['case_id']}</td>
          <td><span class='badge bg-danger'>{$p['flag_type']}</span></td>
          <td>{$p['description']}</td>
          <td>{$p['created_at']}</td>
        </tr>";
    }
}
?>

      </tbody>
    </table>
   <ul class="pagination pagination-sm justify-content-center"
    id="patternPagination"></ul>
  </div>
</section>

</main>
</div>
</div>

<!-- ================= ADD CASE MODAL ================= -->
<div class="modal fade" id="addCase">
<div class="modal-dialog">
<form method="POST" class="modal-content">
<div class="modal-header">
<h5>Add Case</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input name="title" class="form-control mb-2" placeholder="Case Title" required>
<input type="date" name="date_filed" class="form-control mb-2" required>
<select name="status" class="form-control">
<option>Open</option>
<option>Pending</option>
<option>Closed</option>
</select>
</div>
<div class="modal-footer">
<button class="ms-btn" name="add_case">Save</button>
</div>
</form>
</div>
</div>

<script>
  
let monthlyChart;

function loadMonthlyChart(month, year) {

  fetch(`fetch_monthly_cases.php?month=${month}&year=${year}`)
    .then(res => res.json())
    .then(data => {
        
      const ctx = document.getElementById('monthlyCaseChart');

      if (monthlyChart) monthlyChart.destroy();

      monthlyChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Cases',
            data: data.counts,
            backgroundColor: '#0a66c2',
            borderColor: '#0a66c2',
            borderWidth: 1,
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 1 }
            }
          }
        }
      });
    });
}

// dropdown events
filterMonth.onchange = () =>
  loadMonthlyChart(filterMonth.value, filterYear.value);

filterYear.onchange = () =>
  loadMonthlyChart(filterMonth.value, filterYear.value);

// page load



  // ================= STATUS DISTRIBUTION =================
new Chart(document.getElementById('statusChart'), {
  type: 'doughnut',
  data: {
    labels: ['Open', 'Pending', 'Closed'],
    datasets: [{
      data: [<?= $statusData['Open'] ?>, <?= $statusData['Pending'] ?>, <?= $statusData['Closed'] ?>],
      backgroundColor: ['#28a745', '#ffc107', '#6c757d'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' }
    }
  }
});

// ================= OUTCOME CHART =================
<?php
$outcomeLabels = array_keys($outcomeData);
$outcomeCounts = array_values($outcomeData);
?>
new Chart(document.getElementById('outcomeChart'), {
  type: 'pie',
  data: {
    labels: <?= json_encode($outcomeLabels) ?>,
    datasets: [{
      data: <?= json_encode($outcomeCounts) ?>,
      backgroundColor: ['#dc3545','#28a745','#ffc107','#17a2b8','#6f42c1','#fd7e14'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'bottom',
        labels: { boxWidth: 12 }
      }
    }
  }
});

// ================= MONTHLY TIMELINE =================
<?php
$monthLabels  = array_column($monthlyData, 'month');
$monthOpen    = array_column($monthlyData, 'open');
$monthPending = array_column($monthlyData, 'pending');
$monthClosed  = array_column($monthlyData, 'closed');
?>
new Chart(document.getElementById('monthlyChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($monthLabels) ?>,
    datasets: [
      {
        label: 'Open Cases',
        data: <?= json_encode($monthOpen) ?>,
        borderColor: '#28a745',
        backgroundColor: 'rgba(40,167,69,0.1)',
        tension: 0.3,
        fill: true
      },
      {
        label: 'Pending Cases',
        data: <?= json_encode($monthPending) ?>,
        borderColor: '#ffc107',
        backgroundColor: 'rgba(255,193,7,0.1)',
        tension: 0.3,
        fill: true
      },
      {
        label: 'Closed Cases',
        data: <?= json_encode($monthClosed) ?>,
        borderColor: '#6c757d',
        backgroundColor: 'rgba(108,117,125,0.1)',
        tension: 0.3,
        fill: true
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' }
    },
    scales: {
      y: {
        beginAtZero: true,
        title: { display: true, text: 'Number of Cases' }
      }
    }
  }
});


function loadCases(page = 1) {
  fetch("fetch_cases.php?page=" + page)
    .then(res => res.json())
    .then(data => {
      const tbody = document.getElementById("caseBody");
      const pagination = document.getElementById("casePagination");
      tbody.innerHTML = "";
      pagination.innerHTML = "";

      if (data.cases.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center">No cases found</td></tr>`;
        return;
      }
        
     data.cases.forEach(c => {
    tbody.innerHTML += `
      <tr>
        <td>${c.case_id}</td>
        <td>${c.title}</td>
        <td>${c.date_filed}</td>
        <td>${c.status}</td>
        <td>
          <a href="add_hearing.php?case_id=${c.case_id}"
             class="btn btn-sm btn-outline-primary mb-1">
             Hearings
          </a>

          ${
            data.is_judge && c.can_judgement
            ? `<a href="add_judgement.php?case_id=${c.case_id}"
                 class="btn btn-sm btn-success ms-1">
                 Judgement
               </a>`
            : ''
          }
        </td>
      </tr>`;
});


      // Pagination buttons
      let prevPage = page > 1 ? page - 1 : 1;
      let nextPage = page < data.pages ? page + 1 : data.pages;

      pagination.innerHTML += `<li class="page-item ${page===1?'disabled':''}">
                                 <a class="page-link" href="javascript:void(0)" onclick="loadCases(${prevPage})">Previous</a>
                               </li>`;

      for (let i = 1; i <= data.pages; i++) {
        pagination.innerHTML += `<li class="page-item ${i===page?'active':''}">
                                   <a class="page-link" href="javascript:void(0)" onclick="loadCases(${i})">${i}</a>
                                 </li>`;
      }

      pagination.innerHTML += `<li class="page-item ${page===data.pages?'disabled':''}">
                                 <a class="page-link" href="javascript:void(0)" onclick="loadCases(${nextPage})">Next</a>
                               </li>`;
    });
}



// ================= HEARINGS =================
function loadHearings(page = 1) {
  fetch("fetch_hearings.php?page=" + page)
    .then(res => res.json())
    .then(data => {
      const body = document.getElementById("hearingBody");
      const pagination = document.getElementById("hearingPagination");

      body.innerHTML = "";
      pagination.innerHTML = "";

      if (data.hearings.length === 0) {
        body.innerHTML = `<tr><td colspan="4" class="text-center">No hearings scheduled yet.</td></tr>`;
        return;
      }

      data.hearings.forEach(h => {
        body.innerHTML += `
          <tr>
            <td>${h.hearing_id}</td>
            <td>${h.case_id}</td>
            <td>${h.hearing_date}</td>
            <td>${h.court_name}</td>
          </tr>`;
      });

      // Pagination
      let prevPage = page > 1 ? page - 1 : 1;
      let nextPage = page < data.pages ? page + 1 : data.pages;

      pagination.innerHTML += `<li class="page-item ${page===1?'disabled':''}">
                                 <a class="page-link" href="javascript:void(0)" onclick="loadHearings(${prevPage})">Previous</a>
                               </li>`;

      for (let i = 1; i <= data.pages; i++) {
        pagination.innerHTML += `<li class="page-item ${i===page?'active':''}">
                                   <a class="page-link" href="javascript:void(0)" onclick="loadHearings(${i})">${i}</a>
                                 </li>`;
      }

      pagination.innerHTML += `<li class="page-item ${page===data.pages?'disabled':''}">
                                 <a class="page-link" href="javascript:void(0)" onclick="loadHearings(${nextPage})">Next</a>
                               </li>`;
    });
}

// ================= JUDGEMENTS =================
function loadJudgements(page = 1) {
  fetch("fetch_judgements.php?page=" + page)
    .then(res => res.json())
    .then(data => {
      const body = document.getElementById("judgementBody");
      const pagination = document.getElementById("judgementPagination");

      body.innerHTML = "";
      pagination.innerHTML = "";

      if (data.judgements.length === 0) {
        body.innerHTML = `<tr><td colspan="5" class="text-center">No judgements issued yet.</td></tr>`;
        return;
      }

      data.judgements.forEach(j => {
        let summary = j.summary ?? '';
        if(summary.length > 50) summary = summary.substring(0,50)+'...';

        body.innerHTML += `
          <tr>
            <td>${j.judgement_id}</td>
            <td>${j.case_id}</td>
            <td>${j.judgement_date}</td>
            <td>${j.outcome}</td>
            <td>${summary || '<span class="text-muted">No summary</span>'}</td>
          </tr>`;
      });

      // Pagination
      let prevPage = page > 1 ? page - 1 : 1;
      let nextPage = page < data.pages ? page + 1 : data.pages;

      pagination.innerHTML += `<li class="page-item ${page===1?'disabled':''}">
                                 <a class="page-link" href="javascript:void(0)" onclick="loadJudgements(${prevPage})">Previous</a>
                               </li>`;

      for (let i = 1; i <= data.pages; i++) {
        pagination.innerHTML += `<li class="page-item ${i===page?'active':''}">
                                   <a class="page-link" href="javascript:void(0)" onclick="loadJudgements(${i})">${i}</a>
                                 </li>`;
      }

      pagination.innerHTML += `<li class="page-item ${page===data.pages?'disabled':''}">
                                 <a class="page-link" href="javascript:void(0)" onclick="loadJudgements(${nextPage})">Next</a>
                               </li>`;
    });
}

function loadPatternList(page = 1) {
  fetch("fetch_pattern_list.php?page=" + page)
    .then(res => res.json())
    .then(data => {

      const body = document.getElementById("patternBody");
      const pag  = document.getElementById("patternPagination");

      body.innerHTML = "";
      pag.innerHTML  = "";

      if (data.patterns.length === 0) {
        body.innerHTML = `
          <tr>
            <td colspan="5" class="text-center text-muted">
              No pattern flags detected
            </td>
          </tr>`;
        return;
      }

      data.patterns.forEach(p => {
        body.innerHTML += `
          <tr>
            <td>${p.case_id}<br><small>${p.title}</small></td>
            <td><span class="badge bg-danger">${p.flag_type}</span></td>
            <td>${p.description}</td>
            <td>${p.status}</td>
            <td>${p.created_at}</td>
          </tr>`;
      });

      // pagination
      for (let i = 1; i <= data.pages; i++) {
        pag.innerHTML += `
          <li class="page-item ${i === data.current ? 'active' : ''}">
            <a class="page-link" href="javascript:void(0)"
               onclick="loadPatternList(${i})">${i}</a>
          </li>`;
      }
    });
}



let patternChart;

function loadPatternChart() {
    fetch("fetch_pattern_summary.php")
        .then(res => res.json())
        .then(data => {
              if (!data.labels || data.labels.length === 0) {
            document.getElementById('patternChart').style.display = 'none';
            return;
        }
        document.getElementById('patternChart').style.display = 'block';
            const ctx = document.getElementById('patternChart').getContext('2d');

            if(patternChart) patternChart.destroy();

            patternChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Active Pattern Flags',
                        data: data.counts,
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ` ${ctx.raw} cases`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        });
}

// ================= PATTERN =================
function runPatternDetection(){
  fetch("run_pattern.php")
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      loadPatternChart();
      loadPatternList(1);
    });
}

document.addEventListener("DOMContentLoaded", () => {
  loadPatternChart();
  loadCases(1);
  loadHearings(1);
  loadJudgements(1);
   loadPatternList(1);
  loadMonthlyChart(filterMonth.value, filterYear.value);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>