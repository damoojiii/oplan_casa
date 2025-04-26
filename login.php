<?php
    session_start();
    include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="img/rosariologo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.css">
    

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    
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
            flex-direction: column;
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
            height: 90px;
            color: white;
            padding-inline: 70px !important;
            padding-left: 90px;
            padding-right: 90px;
            display: flex;
            align-items: center;
            z-index: 10;
            background: linear-gradient(to bottom, #5D9C59 90%, #DF2E38 10%);
        }
        .login{
            padding-inline: 15px;
        }

        .logo {
            height: 50px;
            width: 50px;
            object-fit: cover;
        }

        .logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .header h4 {
            margin: 0;
            font-family: 'Source';
        }

        .btn{
            background: #273E26;
            border-color: #5D9C59;
        }
        .login-container {
            color: white;
            background: #5D9C59;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: auto;
            z-index: 1000;
        }

        .container {
            background-color: white;
            margin-top: 10px;
            padding: 0 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: auto;
        }
        
        .main{
            display: flex;
            flex-direction: column;
        }

        .forgot{
            margin-top: 5px;
            font-size: 0.9rem;
            color: #fff !important;
        }

        h2{
            font-family: 'Source';
            font-weight: 400;
        }

        .password-wrapper {
            position: relative;
        }

        .bi-eye-slash, .bi-eye {
            font-size: 17px;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 1000;
            color: black;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="header d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center">
            <?php
                $db = new mysqli('localhost', 'root', '', 'casadb');
                if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
                }
                $sql = "SELECT logo_path FROM site_settings WHERE id = 1";
                $result = $db->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $logo = !empty($row['logo_path']) ? $row['logo_path'] : 'img/rosariologo.png'; // Use default if empty
                        echo "<div class='logo-item'>";
                        echo "<img src='$logo' alt='Logo' class='logo-circle' style='width: 75px; height: 75px; margin-top: -10px;'>";
                        echo "</div>";
                    }
                } else {
                    // If walay logong makita, display the default logo nganii para di empty yung logo
                    echo "<div class='logo-item'>";
                    echo "<img src='img/rosariologo.png' alt='Default Logo' class='logo-circle' style='width: 75px; height: 75px; margin-top: -10px;'>";
                    echo "</div>";
                }
            ?>
            <h4 class="mb-0 ms-3 text-white" >
            <a href="index.php" style="text-decoration:none; color:white;">Tourism Office - Municipality of Rosario</a></h4>
        </div>
    </div>
    
        
    <div class="main">
        <div class="login-container">
            <h2 class="text-center mb-4">ADMIN</h2>
            <form method="POST" action="#">
                <div class="mb-3">
                    <label for="email" class="form-label">Email or Username</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <!-- Password field with toggle visibility -->
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <i class="bi bi-eye" id="togglePassword"></i>
                    </div>
                    <p class="text-end list-unstyle forgot"><a href="forgot_password.php" style="text-decoration: none; color: #FFFF;">Forgot Password</a></p>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Get the password input and the icon element
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePassword');

        // Toggle the password visibility when the eye icon is clicked
        togglePasswordIcon.addEventListener('click', function () {
            // Check if the password input is of type "password"
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Toggle the eye icon based on the input type
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>

</body>
</html>
<?php

include 'connection.php';
date_default_timezone_set("Asia/Manila");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    // Get the form data and sanitize it
    $email_or_username = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to check if the email/username exists
    $sql = "SELECT userID, email, username, password FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_username, $email_or_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If a user is found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            echo "<script>
                    window.location.href = 'admin-dashboard.php';
                  </script>";
        } else {
            // Invalid password
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid password.',
                        icon: 'error',
                        confirmButtonText: 'Try Again'
                    });
                  </script>";
        }
    } else {
        // User not found
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'User not found. Please check your email or username.',
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
              </script>";
    }
}
?>