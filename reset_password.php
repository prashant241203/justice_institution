<?php
session_start();
require_once "connect.php";

date_default_timezone_set('Asia/Kolkata');

$message = '';

if(!isset($_GET['token'])) {
    die("Invalid access.");
}

$token = $_GET['token'];


$stmt = $conn->prepare("SELECT user_id FROM users 
                        WHERE reset_token=? 
                        AND reset_expires > NOW() 
                        LIMIT 1");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows !== 1) {
    die("Invalid or expired token.");
}

$user = $result->fetch_assoc();
$user_id = $user['user_id'];

if(isset($_POST['reset'])) {

        $new_password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';


    if($new_password !== $confirm_password) {
        $message = "
<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {

        
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);

        
        $update = $conn->prepare("UPDATE users 
                                  SET password=?, 
                                      reset_token=NULL, 
                                      reset_expires=NULL 
                                  WHERE user_id=?");

        $update->bind_param("si", $hashed, $user_id);
        $update->execute();

        $message = "
<div class='alert alert-success'>
                        Password updated successfully!
                        
	<br>
		<a href='login.php'>Login Now</a>
	</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Justice & Institutions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body {
        background: linear-gradient(135deg, #0a66c2 0%, #084a8c 100%);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .card-container {
        max-width: 400px;
        width: 100%;
      }

      .card {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      }

      .card-header {
        background: #0a66c2;
        color: white;
        text-align: center;
        padding: 25px;
        border-radius: 15px 15px 0 0;
      }

      .card-body {
        padding: 30px;
        background: white;
        border-radius: 0 0 15px 15px;
      }
    </style>
  </head>
  <body>
    <div class="card-container">
      <div class="card">
        <div class="card-header">
          <h3>Reset Password</h3>
        </div>
        <div class="card-body"> <?php if($message) echo $message; ?> <form method="POST">
            <div class="mb-3">
              <label>New Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="reset" class="btn btn-primary w-100"> Reset Password </button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>