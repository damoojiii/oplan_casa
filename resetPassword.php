<?php
session_start();
require 'connection.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Use email from verified session
    $email = $_SESSION['email'] ?? null;

    if (!$email) {
      echo "Invalid session. Please restart the reset process.";
      exit;
    }

    if ($newPassword !== $confirmPassword) {
      echo "Passwords do not match.";
      exit;
    }

    // Hash and update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashedPassword, $email);

    if ($update->execute()) {
      // Clear session values related to reset
      unset($_SESSION['email']);
      echo "Password updated successfully. <a href='login.php'>Login now</a>.";
    } else {
      echo "Error updating password.";
    }

    $update->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
        background: url('img/casabg.jpg') no-repeat center center/cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .card-custom {
        background-color: #4a7c4f;
        color: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    .card-custom .form-control {
        border: none;
        border-radius: 8px;
        padding: 12px;
    }

    .card-custom .btn-success {
        background-color: #2e4c2f;
        border: none;
        font-weight: bold;
    }

    .card-custom .btn-success:hover {
        background-color: #1f361f;
    }

    .cancel-link {
      color: #eee;
      font-size: 14px;
      margin-top: 10px;
      display: block;
      cursor: pointer;
      text-decoration: underline;
    }

    .icon {
      font-size: 48px;
      color: #fff;
      margin-bottom: 10px;
    }

    h4 {
      font-weight: bold;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="card-custom">
    <div class="icon">
      📩
    </div>
    <h4>CHANGE<br>PASSWORD</h4>
    <p class="mb-4">Enter your new password below and confirm it to update your account.</p>
    <form action="#" method="POST">
      <div class="mb-3">
        <input type="password" class="form-control" name="new_password" placeholder="New Password" required>
      </div>
      <div class="mb-3">
        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
      </div>
      <button type="submit" class="btn btn-success w-100">UPDATE</button>
    </form>
    <a class="cancel-link" href="#">Cancel</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
