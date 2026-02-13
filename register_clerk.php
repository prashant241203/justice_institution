<?php
require_once("connect.php");
$message = '';

if (isset($_POST['register'])) {

 
    $name = trim(mysqli_real_escape_string($conn, $_POST['name'] ?? ''));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $password_raw = $_POST['password'] ?? '';


    if (empty($name)) {
        $message = "Full name is required!";
    }
    elseif (strlen($name) < 3) {
        $message = "Full name must be at least 3 characters long!";
    }
    elseif (empty($email)) {
        $message = "Email address is required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address!";
    }
    elseif (empty($password_raw)) {
        $message = "Password is required!";
    }
    elseif (strlen($password_raw) < 6) {
        $message = "Password must be at least 6 characters long!";
    }
    else {

  
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $role = 'Clerk';

        
        $check = mysqli_query(
            $conn,
            "SELECT user_id FROM users WHERE email='$email' AND status != 'rejected'"
        );

        if (mysqli_num_rows($check) > 0) {
            $message = "Email already registered!";
        } else {

          
            $query = "INSERT INTO users (name, email, password, role, status)
                      VALUES ('$name', '$email', '$password', '$role', 'pending')";

            if (mysqli_query($conn, $query)) {
                header("Location: login.php?pending=1");
                exit;
            } else {
                $message = "Registration failed! Please try again.";
            }
        }
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clerk Registration - Justice & Institutions</title>

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
    .register-container {
        width: 100%;
        max-width: 420px;
    }
    .register-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .register-header {
        background: #0a66c2;
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 25px;
        text-align: center;
    }
    .register-body {
        padding: 30px;
        background: white;
        border-radius: 0 0 15px 15px;
    }
    .logo-text {
        font-size: 24px;
        font-weight: bold;
    }
    .logo-subtext {
        font-size: 14px;
        opacity: 0.9;
    }
    </style>
    </head>

    <body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo-text">⚖️ Justice & Institutions</div>
                <div class="logo-subtext">Clerk Account Registration</div>
            </div>

            <div class="register-body">

                <?php if($message): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control"
                            placeholder="Enter your full name" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            placeholder="Enter your email" >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Create a password" >
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="register" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-plus"></i> Register as Clerk
                        </button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <small>
                        Already have an account?
                        <a href="login.php">Login here</a>
                    </small>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        &copy; <?php echo date('Y'); ?> Justice & Institutions
                    </small>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
