<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access Denied - Justice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
      body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .access-card {
        max-width: 600px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        overflow: hidden;
      }

      .access-header {
        background: #dc3545;
        color: white;
        padding: 30px;
        text-align: center;
      }

      .access-body {
        background: white;
        padding: 40px;
      }

      .role-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
      }

      .btn-group-custom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 30px;
      }
    </style>
  </head>
  <body>
    <div class="access-card">
      <div class="access-header">
        <i class="bi bi-shield-slash display-1 mb-3"></i>
        <h2 class="mb-0">Access Denied</h2>
        <p class="mb-0">Insufficient Permissions</p>
      </div>
      <div class="access-body">
        <div class="text-center mb-4">
          <h4 class="text-danger">
            <i class="bi bi-exclamation-octagon"></i> Permission Required
          </h4>
          <p class="text-muted"> You don't have the necessary permissions to access this page. </p>
        </div>
        <div class="role-info">
          <h6>
            <i class="bi bi-person-badge"></i> Current Session Information
          </h6>
          <div class="row mt-3">
            <div class="col-md-6">
              <small class="text-muted">User Name</small>
              <div class="fw-bold"> <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Not logged in'); ?> </div>
            </div>
            <div class="col-md-6">
              <small class="text-muted">User Role</small>
              <div> <?php
                                if(isset($_SESSION['user_role'])) {
                                    $role = $_SESSION['user_role'];
                                    switch($role) {
                                        case 'admin': $color='danger'; break;
                                        case 'judge': $color='success'; break;
                                        case 'lawyer': $color='primary'; break;
                                        case 'clerk': $color='warning'; break;
                                        case 'analyst': $color='info'; break;
                                        default: $color='secondary';
                                    }
                                    echo "
												<span class='badge bg-$color'>" . ucfirst($role) . "</span>";
                                } else {
                                    echo '
												<span class="badge bg-secondary">Guest</span>';
                                }
                                ?> </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <small class="text-muted">Attempted Page</small>
              <div class="fw-bold text-truncate"> <?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])); ?> </div>
            </div>
          </div>
        </div>
        <div class="alert alert-warning">
          <i class="bi bi-info-circle"></i>
          <strong>Note:</strong> This page requires specific user privileges. If you believe this is an error, please contact the system administrator.
        </div>
        <div class="btn-group-custom">
          <a href="index.php" class="btn btn-primary btn-lg">
            <i class="bi bi-house-door"></i> Dashboard </a>
          <a href="logout.php" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-box-arrow-right"></i> Switch Account </a>
        </div>
        <div class="text-center mt-4">
          <small class="text-muted">
            <i class="bi bi-clock"></i> <?php echo date('F j, Y, g:i a'); ?> </small>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>