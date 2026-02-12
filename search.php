<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
require_once("auth_check.php");
require_once("connect.php");

$searchResults = [];
$searchQuery = '';
$totalResults = 0;

if(isset($_GET['search'])) {
    $searchQuery = mysqli_real_escape_string($conn, $_GET['q'] ?? '');
    $statusFilter = $_GET['status'] ?? '';
    $dateFrom = $_GET['date_from'] ?? '';
    $dateTo = $_GET['date_to'] ?? '';
    
    // Build query
    $query = "SELECT * FROM cases WHERE case_id != ''";
    
    if(!empty($searchQuery)) {
        $query .= " AND (case_id LIKE '%$searchQuery%' OR 
                         title LIKE '%$searchQuery%' OR 
                         status LIKE '%$searchQuery%')";
    }
    
    if(!empty($statusFilter)) {
        $query .= " AND status = '$statusFilter'";
    }
    
    if(!empty($dateFrom)) {
        $query .= " AND date_filed >= '$dateFrom'";
    }
    
    if(!empty($dateTo)) {
        $query .= " AND date_filed <= '$dateTo'";
    }
    
    $query .= " ORDER BY case_id DESC";
    $searchResults = mysqli_query($conn, $query);
    $totalResults = mysqli_num_rows($searchResults);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Cases - Justice & Institutions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: #f5f7fb; }
        .search-card { border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .search-header { background: linear-gradient(135deg, #0a66c2 0%, #084a8c 100%); }
        .result-card { transition: transform 0.2s; }
        .result-card:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
        .filter-section { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #0a66c2;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-search"></i> Case Search
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    <i class="bi bi-person-circle"></i> 
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="index.php" class="btn btn-sm btn-outline-light me-2">
                    <i class="bi bi-grid"></i> Dashboard
                </a>
                <a href="logout.php" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-3">
                <!-- Search Filters -->
                <div class="filter-section">
                    <h5><i class="bi bi-funnel"></i> Filters</h5>
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label class="form-label">Keywords</label>
                            <input type="text" name="q" class="form-control" 
                                   value="<?php echo htmlspecialchars($searchQuery); ?>" 
                                   placeholder="Case ID, Title, Status...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Open" <?php echo ($_GET['status'] ?? '') == 'Open' ? 'selected' : ''; ?>>Open</option>
                                <option value="Pending" <?php echo ($_GET['status'] ?? '') == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Closed" <?php echo ($_GET['status'] ?? '') == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label">From Date</label>
                                <input type="date" name="date_from" class="form-control" 
                                       value="<?php echo $_GET['date_from'] ?? ''; ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label">To Date</label>
                                <input type="date" name="date_to" class="form-control" 
                                       value="<?php echo $_GET['date_to'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="search" class="btn btn-primary">
                                <i class="bi bi-search"></i> Search Cases
                            </button>
                            <a href="search.php" class="btn btn-secondary">Clear Filters</a>
                        </div>
                    </form>
                </div>
                
                <!-- Quick Stats -->
                <div class="filter-section">
                    <h6><i class="bi bi-bar-chart"></i> Quick Stats</h6>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Cases:</span>
                            <strong>
                                <?php 
                                $total = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM cases WHERE case_id != ''"))[0];
                                echo $total;
                                ?>
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Open Cases:</span>
                            <strong class="text-success">
                                <?php 
                                $open = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM cases WHERE status = 'Open'"))[0];
                                echo $open;
                                ?>
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Closed Cases:</span>
                            <strong class="text-secondary">
                                <?php 
                                $closed = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM cases WHERE status = 'Closed'"))[0];
                                echo $closed;
                                ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Search Results -->
            <div class="col-lg-9">
                <div class="card search-card">
                    <div class="card-header search-header text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="bi bi-search"></i> 
                                <?php echo isset($_GET['search']) ? 'Search Results' : 'Case Search'; ?>
                            </h4>
                            <?php if(isset($_GET['search'])): ?>
                                <span class="badge bg-light text-dark fs-6">
                                    <?php echo $totalResults; ?> Results
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if(isset($_GET['search'])): ?>
                            <?php if($totalResults > 0): ?>
                                <!-- Results Summary -->
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            Found <strong><?php echo $totalResults; ?></strong> cases
                                            <?php if(!empty($searchQuery)): ?>
                                                for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <a href="?export=cases&q=<?php echo urlencode($searchQuery); ?>&status=<?php echo $_GET['status'] ?? ''; ?>" 
                                               class="btn btn-sm btn-success">
                                               <i class="bi bi-download"></i> Export Results
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Results Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Case ID</th>
                                                <th>Title</th>
                                                <th>Date Filed</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            mysqli_data_seek($searchResults, 0);
                                            while($case = mysqli_fetch_assoc($searchResults)): 
                                            ?>
                                                <tr class="result-card">
                                                    <td>
                                                        <strong><?php echo htmlspecialchars($case['case_id']); ?></strong>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlspecialchars($case['title']); ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            <?php echo date('d M Y', strtotime($case['date_filed'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            switch($case['status']) {
                                                                        case 'Open': $status_color='success'; break;
                                                                        case 'Pending': $status_color='warning'; break;
                                                                        case 'Closed': $status_color='secondary'; break;
                                                                        default: $status_color='secondary';
                                                            }
                                                        ?>
                                                        <span class="badge bg-<?php echo $status_color; ?>">
                                                            <i class="bi bi-circle-fill"></i> <?php echo $case['status']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <?php if($_SESSION['user_role'] != 'analyst'): ?>
                                                                <a href="view_case.php?case_id=<?php echo urlencode($case['case_id']); ?>" 
                                                                class="btn btn-info" title="View Details">
                                                                <i class="bi bi-eye"></i>
                                                                </a>
                                                                <a href="add_hearing.php?case_id=<?php echo urlencode($case['case_id']); ?>" 
                                                                class="btn btn-primary" title="Schedule Hearing">
                                                                <i class="bi bi-calendar-plus"></i>
                                                                </a>
                                                                <?php if($case['status'] != 'Closed'): ?>
                                                                    <a href="index.php#caseMaster" 
                                                                    class="btn btn-success" title="Go to Case Master">
                                                                    <i class="bi bi-arrow-right"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="text-muted">No Actions Available</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <!-- No Results Found -->
                                <div class="text-center py-5">
                                    <i class="bi bi-search display-1 text-muted mb-4"></i>
                                    <h3>No Cases Found</h3>
                                    <p class="text-muted mb-4">
                                        <?php if(!empty($searchQuery)): ?>
                                            No cases found for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
                                        <?php else: ?>
                                            No cases match your search criteria
                                        <?php endif; ?>
                                    </p>
                                    <a href="search.php" class="btn btn-primary me-2">
                                        <i class="bi bi-arrow-clockwise"></i> Try Different Search
                                    </a>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="bi bi-house-door"></i> Back to Dashboard
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Search Instructions -->
                            <div class="text-center py-5">
                                <i class="bi bi-search display-1 text-primary mb-4"></i>
                                <h3>Search Cases</h3>
                                <p class="text-muted mb-4">
                                    Use the filters on the left to search for cases by ID, title, status, or date range.
                                </p>
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5><i class="bi bi-lightbulb"></i> Search Tips:</h5>
                                                <ul class="text-start">
                                                    <li>Search by Case ID (e.g., C001, C002)</li>
                                                    <li>Search by keywords in case title</li>
                                                    <li>Filter by case status (Open, Pending, Closed)</li>
                                                    <li>Use date range for specific periods</li>
                                                    <li>Click on any case to view detailed information</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>