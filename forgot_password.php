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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitor's Log</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="img/rosariologo.png">

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @font-face {
            font-family: 'Inter';
            src: url('fonts/Inter/Inter-VariableFont_opsz\,wght.ttf') format('truetype');
            font-weight: 100 900;
            font-stretch: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Source';
            src: url('fonts/Source_Serif_4/static/SourceSerif4-SemiBold.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;

        }

        body {
            font-family: 'Inter', Arial;
            background: url('img/casabg.jpg') no-repeat center center/cover;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #5D9C5933;
            z-index: 1;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            color: white;
            padding-inline: 70px !important;
            padding-left: 90px;
            padding-right: 90px;
            display: flex;
            align-items: center;
            z-index: 10;
        }

        .login {
            padding-inline: 15px;
        }

        .header h4 {
            margin: 0;
            font-family: 'Source';
        }

        .visitor {
            font-family: 'Inter', Arial;
            font-weight: 400;
            font-size: 30px;
        }

        .card {
            width: 350px;
            padding: 20px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            z-index: 10;
        }

        .logo {
            width: 80px;
            margin: 0 auto 10px;
            display: block;
        }

        .submit-btn {
            width: 100%;
            background: #ff4d4d;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #d43f3f;
        }

        .otp-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        .otp-input:focus {
            border-color: #ff4d4d;
            box-shadow: 0px 0px 5px #ff4d4d;
        }
    </style>
</head>

<body>

    <div class="overlay"></div>

    <div class="container mt-5">
        <div class="card p-4 d-flex justify-content-center">
            <img src="img/rosariologo.png" class="logo" />
            <h4 style="margin-top: 10px; margin-bottom: 20px;">Forgot Password</h4>
            
            <form id="forgotPasswordForm" method="POST">
                <div class="input-box">
                    <input type="email" name="email" id="emailInput" class="form-control" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="submit-btn">Reset</button>
            </form>
        </div>

        <!-- OTP Modal -->
        <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger text-center w-100" id="otpModalLabel">
                            Enter OTP to Verify Your Account
                        </h5>
                    </div>
                    <div class="modal-body text-center">
                        <img src="img/otp_icon.png" alt="OTP Icon" style="width: 80px; margin-bottom: 10px;">
                        <p class="mb-3">A code has been sent to <strong id="emailDisplay"></strong></p>
                        <form method="post" id="otpForm">
                            <div class="otp-container">
                                <input type="text" class="form-control otp-input" maxlength="1" required>
                                <input type="text" class="form-control otp-input" maxlength="1" required>
                                <input type="text" class="form-control otp-input" maxlength="1" required>
                                <input type="text" class="form-control otp-input" maxlength="1" required>
                            </div>
                            <input type="hidden" name="code" id="hiddenCode">
                            <button type="submit" class="btn btn-danger w-50">Validate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = document.querySelectorAll(".otp-input");

            inputs.forEach((input, index) => {
                input.addEventListener("input", (e) => {
                    if (e.target.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener("keydown", (e) => {
                    if (e.key === "Backspace" && index > 0 && !e.target.value) {
                        inputs[index - 1].focus();
                    }
                });
            });
        });

        $(document).ready(function() {
            $("#forgotPasswordForm").submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var email = $("#emailInput").val();

                $.ajax({
                    type: "POST",
                    url: "forgot_password.php",
                    data: { email: email },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            $("#emailDisplay").text(email);
                            $("#otpModal").modal("show");
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred. Please try again.");
                    }
                });
            });

            $(".otp-input").keyup(function() {
                if (this.value.length === this.maxLength) {
                    $(this).next(".otp-input").focus();
                }
            });
        });
    </script>

</body>

</html>