<?php
include "connection.php";
include "loader.php";
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    header('Content-Type: application/json');
    $email = $_POST['email'];
    $_SESSION['email'] = $email;
    $response = ['status' => 'error', 'message' => ''];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'casahacienda393@gmail.com';
        $mail->Password = 'eeji rjxr fjwv mobp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('casahacienda393@gmail.com', 'Casa Hacienda de Tejeros');
        $mail->addAddress($email);

        $otp = substr(str_shuffle('1234567890'), 0, 4);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body = 'Your OTP code is: <strong>' . $otp . '</strong>';

        $verifyQuery = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($verifyQuery->num_rows) {
            $conn->query("UPDATE users SET otp = '$otp' WHERE email = '$email'");
            $mail->send();
            $response = ['status' => 'success', 'message' => 'OTP sent to your email'];
        } else {
            $response['message'] = 'Email not found';
        }
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    if (ob_get_length()) {
        ob_clean();
    }
    echo json_encode($response);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    $email = $_SESSION['email'];
    $input_code = $_POST['code'];

    $result = $conn->query("SELECT otp FROM users WHERE email = '$email' AND otp = '$input_code'");
    if ($result->num_rows > 0) {
        $conn->query("UPDATE users SET otp = NULL WHERE email = '$email'");
        echo '<script>alert("OTP verified successfully!");</script>';
        echo '<script>window.location.href = "changeForgotPass.php";</script>';
    } else {
        echo '<script>alert("Invalid OTP. Please try again.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Forgot Password</h2>
    <form id="emailForm">
        <div class="mb-3">
            <label for="email" class="form-label">Enter your email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Send OTP</button>
    </form>
</div>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="otpForm">
        <div class="modal-header">
          <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="otp" class="form-label">OTP Code</label>
            <input type="text" class="form-control" id="otp" name="code" required maxlength="4">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Verify OTP</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.getElementById('emailForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;

    try {
        const response = await axios.post('forgot_password.php', new URLSearchParams({ email }));

        if (response.data.status === 'success') {
            alert(response.data.message);
            var otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
            otpModal.show();
        } else {
            alert(response.data.message);
        }
    } catch (error) {
        console.error(error);
        alert('An error occurred. Please try again.');
    }
});

document.getElementById('otpForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const code = document.getElementById('otp').value;

    try {
        const response = await axios.post('forgotPass.php', new URLSearchParams({ code }));

        // Since your PHP file uses echo + script for OTP, no need to handle response here.
        // If you want to improve, you can change PHP to JSON response style for better AJAX handling.

    } catch (error) {
        console.error(error);
        alert('An error occurred during OTP verification.');
    }
});
</script>

</body>
</html>
