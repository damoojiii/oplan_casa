<?php
ob_start();
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && !isset($_POST['resend_otp'])) {
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

// Resend OTP (uses email from session)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resend_otp'])) {
  error_log('Resend OTP request received: ' . print_r($_POST, true));
    if (!isset($_SESSION['email'])) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please enter your email again.']);
        exit;
    }

    header('Content-Type: application/json');
    $email = $_SESSION['email'];
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

      // Your PHPMailer setup
      $otp = substr(str_shuffle('1234567890'), 0, 4);
      $mail->isHTML(true);
      $mail->Subject = 'Password Reset OTP';
      $mail->Body = 'Your new OTP code is: <strong>' . $otp . '</strong>';

      $conn->query("UPDATE users SET otp = '$otp' WHERE email = '$email'");
      $mail->send();
      $response = ['status' => 'success', 'message' => 'OTP resent successfully'];
    } catch (Exception $e) {
      $response['message'] = "Resend failed. Mailer Error: {$mail->ErrorInfo}";
    }

    ob_end_clean();
    echo json_encode($response);  // Check that the response is being sent correctly
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    $email = $_SESSION['email'];  // Get email from session
    $input_code = $_POST['code']; // Get OTP from POST request

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $input_code);  // Bind parameters
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {  // OTP match found
        // Update the database to remove OTP once verified
        $conn->query("UPDATE users SET otp = NULL WHERE email = '$email'");
        
        // JavaScript to alert success and redirect to password change page
        echo '<script>alert("OTP verified successfully!");</script>';
        echo '<script>window.location.href = "changeForgotPass.php";</script>';
    } else {
        // JavaScript to alert invalid OTP
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

    <style>
      body {
        background: url('img/casabg.jpg') no-repeat center center/cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
      }

        .forgot-password-box {
            background-color: #5D9C59;
            max-width: 400px;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .forgot-password-box h2 {
          font-weight: bold;
        }

        .email-input {
          height: 50px;
          font-size: 16px;
          text-align: center;
        }

        .btn-send {
          background-color: #273E26;
          color: #FFFFFF;
          font-weight: bold;
          width: 100%;
          height: 45px;
          font-size: 16px;
          border: none;
        }
        
        .btn-send:hover {
          background-color:rgb(64, 144, 235);
        }
        
        .cancel-link {
          display: block;
          margin-top: 15px;
          text-decoration: none;
          color: #333;
        }

        .overlay {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: #5D9C5933;
          z-index: -1;
        }

        .modal-content {
          background-color: #5D9C59;
          padding: 40px;
          text-align: center;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .modal-title {
          font-weight: bold;
        }

        .otp-input {
          width: 50px;
          height: 50px;
          font-size: 24px;
          border: none;
          border-bottom: 2px solid #ccc;
          outline: none;
          transition: border-color 0.3s;
        }

        .otp-input:focus {
          border-color: #007bff;
          box-shadow: none;
        }

        .btn-success {
          background-color: #273E26;
          color: #FFFFFF;
          font-weight: bold;
          width: 100%;
          height: 45px;
          font-size: 16px;
          border: none;
        }

        .btn-success:hover {
          background-color: rgb(64, 144, 235);
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<div class="forgot-password-box">
  <img src="https://cdn-icons-png.flaticon.com/512/542/542638.png" alt="Envelope" width="80">
  <h2>FORGOT PASSWORD?</h2>
  <p>Don’t worry! Enter your email below and we’ll email you with instructions on how to reset your password.</p>
  <form id="emailForm">
    <input type="email" class="form-control email-input mb-3" id="email" name="email" placeholder="ENTER YOUR EMAIL" required>
    <button type="submit" class="btn btn-send">SEND</button>
  </form>
  <a href="login.php" class="cancel-link">CANCEL</a>
</div>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded shadow">
      <div class="text-white text-center py-3 rounded-top">
        <img src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" alt="OTP Icon" width="50">
        <h5 class="mt-2 mb-0">OTP Verification</h5>
        <small>Code has been sent to Leonardo DiCarpa</small>
      </div>
      <form id="otpForm">
        <input type="hidden" name="code" id="otpFull">
        <div class="modal-body text-center">
          <h3 id="countdown" class="mb-3" style="color: white;"></h3>
          <div class="d-flex justify-content-center gap-2 mb-3">
            <input type="text" class="form-control text-center otp-input" maxlength="1" required>
            <input type="text" class="form-control text-center otp-input" maxlength="1" required>
            <input type="text" class="form-control text-center otp-input" maxlength="1" required>
            <input type="text" class="form-control text-center otp-input" maxlength="1" required>
          </div>
          <p>Didn't get the code? <button type="button" id="resendBtn" class="btn btn-link p-0 m-0 align-baseline">Resend</button></p>
          <button type="submit" class="btn btn-primary w-100">Validate</button>
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

const otpInputs = document.querySelectorAll('.otp-input');
  const otpForm = document.getElementById('otpForm');
  const otpFull = document.getElementById('otpFull');

  otpInputs.forEach((input, index) => {
    input.addEventListener('input', () => {
      if (input.value.length === 1 && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === "Backspace" && input.value === "" && index > 0) {
        otpInputs[index - 1].focus();
      }
    });
  });

  otpForm.addEventListener('submit', function (e) {
    let otpCode = '';
    otpInputs.forEach(input => otpCode += input.value);
    otpFull.value = otpCode;
  });

  let timer;
  let timeLeft = 15;

  const countdownElement = document.getElementById('countdown');
  const resendBtn = document.getElementById('resendBtn');

  function startTimer() {
    clearInterval(timer);
    resendBtn.disabled = true;
    timeLeft = 15;

    updateCountdownText(timeLeft);

    timer = setInterval(() => {
      timeLeft--;
      updateCountdownText(timeLeft);

      if (timeLeft <= 0) {
        clearInterval(timer);
        countdownElement.textContent = "00:00";
        resendBtn.disabled = false;
      }
    }, 1000);
  }

  function updateCountdownText(seconds) {
    let minutes = Math.floor(seconds / 60);
    let remainingSeconds = seconds % 60;
    countdownElement.textContent = `${minutes}:${remainingSeconds < 10 ? '0' + remainingSeconds : remainingSeconds}`;
  }

 resendBtn.addEventListener('click', function () {
  // Ensure the button is not clicked multiple times
  if (resendBtn.disabled) return;

  // Disable the button to prevent multiple requests
  resendBtn.disabled = true;

  console.log('Sending request to resend OTP...');
  
  // Perform the fetch request to resend OTP
  fetch('forgot_password.php', { 
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'resend_otp=1'
  })
  .then(res => {
    console.log('Response received:', res); 
    return res.text(); 
  })
  .then(text => {
    console.log('Raw Response Text:', text); 
    
    let data;
    try {
      data = JSON.parse(text); 
    } catch (e) {
      throw new Error('Failed to parse JSON response');
    }

    console.log('Data received from server:', data); 
    
    if (data.status === 'success') {
      alert('OTP resent successfully!');
      startTimer();
    } else {
      alert(data.message || 'Failed to resend OTP.');
    }
  })
  .catch(error => {
    console.error('Error during OTP resend:', error);  // Log any error during the process
    alert('Failed to resend OTP. Check console for details.');
  })
  .finally(() => {
    resendBtn.disabled = false;  // Re-enable the button after the request completes
  });
});

  startTimer();

  // Collect OTP input and submit it
  document.querySelector('#otpForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form from reloading page
    let otpCode = '';
    const otpInputs = document.querySelectorAll('.otp-input');
    otpInputs.forEach(input => otpCode += input.value);

    // Set the OTP value to hidden input for submission
    document.getElementById('otpFull').value = otpCode;

    // Submit the form (POST request with OTP)
    this.submit();
  });
</script>

</body>
</html>
