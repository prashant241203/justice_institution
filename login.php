<?php

session_start();
require_once "connect.php";

// Redirect if already logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';
if(isset($_POST['login'])) {

    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);

    $query  = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) === 1) {

        $user = mysqli_fetch_assoc($result);

        if(!password_verify($password, $user['password'])) {
            $error = "Invalid email or password!";
        }
        elseif($user['status'] === 'pending') {
            $error = "Your account is pending admin approval.";
        }
        elseif($user['status'] === 'rejected') {
            $error = "Your registration request was rejected.";
        }
        else {
          
            $_SESSION['user_id']   = $user['user_id'];   
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email']= $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            header("Location: index.php");
            exit;
        }

    } else {
        $error = "Invalid email or password!";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Justice & Institutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a66c2 0%, #084a8c 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .login-header {
            background: #0a66c2;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 25px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
            background: white;
            border-radius: 0 0 15px 15px;
        }
        .logo-text { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
        .logo-subtext { font-size: 14px; opacity: 0.9; }
        .role-badges { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 15px; }
        .role-badge { font-size: 11px; padding: 4px 8px; border-radius: 20px; }
        .register-link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-text">⚖️ Justice & Institutions</div>
            <div class="logo-subtext">Case Management System</div>
            <div class="role-badges">
                <span class="badge bg-danger role-badge">Admin</span>
                <span class="badge bg-success role-badge">Judge</span>
                <span class="badge bg-primary role-badge">Clerk</span>
                <span class="badge bg-warning text-dark role-badge">Lawyer</span>
            </div>
        </div>
        <div class="login-body">
                    <?php if(isset($_GET['pending'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Your account has been created and is pending admin approval.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           placeholder="Enter your email" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                    
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" 
                           placeholder="Enter your password" required>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="login" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </div>
            </form>

            <!-- Link to Registration -->
            <div class="register-link">
                <small class="text-muted">New User?</small><br>

                <a href="register_lawyer.php" class="d-block mt-1">Register as Lawyer</a>
                <a href="register_clerk.php" class="d-block">Register as Clerk</a>
                <a href="register_analyst.php" class="d-block">Register as Analyst</a>

                <small class="text-muted d-block mt-2">
                    Admin & Judge accounts are created by system administrator
                </small>
            </div>


            <div class="text-center mt-3">
                <small class="text-muted">&copy; <?php echo date('Y'); ?> Justice & Institutions</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
