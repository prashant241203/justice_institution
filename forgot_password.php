<?php
session_start();
require_once "connect.php";

date_default_timezone_set('Asia/Kolkata');

$message = '';

if(isset($_POST['forgot'])) {

    $email = trim($_POST['email']);

    // Prepared statement
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Generate secure token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+30 minutes'));

        // Save token using prepared statement
        $update = $conn->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE email=?");
        $update->bind_param("sss", $token, $expires, $email);
        $update->execute();

        // Local testing link
        $reset_link = "http://localhost/justice_institution/reset_password.php?token=$token";

        $message = "<div class='alert alert-success'>
                        Reset Link (Testing Mode): 
                        <a href='$reset_link'>$reset_link</a>
                    </div>";

    } else {
        $message = "<div class='alert alert-danger'>Email not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password - Justice & Institutions</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #0a66c2 0%, #084a8c 100%);
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-container { max-width: 400px; width: 100%; }
.card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
.card-header { background: #0a66c2; color: white; text-align: center; padding: 25px; border-radius: 15px 15px 0 0; }
.card-body { padding: 30px; background: white; border-radius: 0 0 15px 15px; }
</style>
</head>
<body>

<div class="card-container">
    <div class="card">
        <div class="card-header">
            <h3>Forgot Password</h3>
        </div>
        <div class="card-body">
            <?php if($message) echo $message; ?>

            <form method="POST">
                <div class="mb-3">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                </div>
                <div class="d-grid">
                    <button type="submit" name="forgot" class="btn btn-primary">Send Reset Link</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">&larr; Back to Login</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
