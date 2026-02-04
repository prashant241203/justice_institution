<?php
require_once("connect.php");

if(isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'analyst'; // Default role for normal registration
    
    // Check if email exists  
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($conn, 
            "INSERT INTO users (name, email, password, role) 
             VALUES ('$name', '$email', '$password', '$role')");
        
        header("Location: login.php?registered=1");
        exit;
    } else {
        $error = "Email already registered!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Justice System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a66c2 0%, #084a8c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .register-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
        }
        .register-header {
            background: #0a66c2;
            color: #fff;
            padding: 25px;
            text-align: center;
        }
        .register-header h4 {
            margin-bottom: 5px;
        }
        .register-header small {
            opacity: 0.9;
        }
        .register-body {
            padding: 30px;
        }
        .register-body .form-control:focus {
            box-shadow: none;
            border-color: #0a66c2;
        }
        .register-body .btn-primary {
            background: #0a66c2;
            border: none;
        }
        .register-body .btn-primary:hover {
            background: #084a8c;
        }
        .register-footer {
            text-align: center;
            margin-top: 15px;
        }
        .register-footer a {
            text-decoration: none;
            color: #0a66c2;
        }
        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-header">
        <h4><i class="bi bi-person-plus-fill"></i> Create Your Account</h4>
        <small>Join the Justice Case Management System</small>
    </div>
    <div class="register-body">
        <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>

            <div class="d-grid mb-3">
                <button type="submit" name="register" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Register
                </button>
            </div>
        </form>

        <div class="register-footer">
            <small>Already have an account? <a href="login.php">Login here</a></small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
