<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");

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
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #fff;
        }
        h2{
            font-family: 'Source';
            font-weight: 400;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="header d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center">
            <img src="img/rosariologo.png" alt="Municipality Logo" class="logo">
            <h4 class="mb-0 ms-3 text-white">Tourism Office - Municipality of Rosario</h4>
        </div>
    </div>

    <div class="main">
        <div class="login-container">
            <h2 class="text-center mb-4">Admin</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email or Username</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label">Password</label>
                        <p class="text-center list-unstyle forgot"><a href="forgot_password.php">Forgot Password</a></p>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
