<?php
    include "session.php";
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="img/rosariologo.png">
    
    <style>
        @font-face {
            font-family: 'Inter';
            src: url('fonts/Inter/Inter-VariableFont_opsz\,wght.ttf') format('truetype');
            font-weight: 100 900;
            font-stretch: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Karla';
            src: url('fonts/Karla/Karla-VariableFont_wght.ttf') format('truetype');
            font-weight: 100 900;
            font-stretch: normal;
            font-style: normal;
        }
        *, *::before, *::after {
            box-sizing: border-box;
        }
        *, p{
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        #sidebar .font-logo {
            font-family: 'nautigal';
            font-size: 20px !important;
        }
        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            transition: transform 0.3s ease;
            background: #273E26;
            z-index: 199;
        }

        header {
            position: none;
            top: 0;
            left: 0;
            right: 0; 
            width: 100%;
            height: 50px;
            transition: margin-left 0.3s ease;
            align-items: center;
            display: flex;  /* Smooth transition for header */
        }
        #header{
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        #hamburger {
            border: none;
            background: none;
            cursor: pointer;
            margin-left: 15px; /* Space from the left edge */
            display: none; /* Initially hide the hamburger button */
        }

        #main-content {
            transition: margin-left 0.3s ease;
            margin-left: 250px; 
            max-width: 80%;
        }

        hr {
            background-color: #ffff;
            height: 1.5px;
        }

        #sidebar .nav-link {
            font-family: 'Karla';
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            margin-bottom: 2px;
        }

        #sidebar .collapse {
            transition: height 0.3s ease-out, opacity 0.3s ease-out;
        }
        #sidebar .collapse.show {
            height: auto !important;
            opacity: 1;
        }
        #sidebar .collapse:not(.show) {
            height: 0;
            opacity: 0;
            overflow: hidden;
        }
        #sidebar .drop{
            height: 50px;
        }
        .caret-icon .fa-caret-down {
            display: inline-block;
            font-size: 20px;
        }
        .navcircle{
            font-size: 7px;
            text-align: justify;
        }
        .main-menu{
            font-family: 'Karla';
            margin-bottom: 10px;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background-color: #fff !important;
            color: #000 !important;
        }

        .dropdown-item {
            color: #fff !important;
            margin-bottom: 10px;
        }

        .dropdown-item:hover{
            background-color: #fff !important;
            color: #000 !important;
        }

        
        .parent {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            grid-template-rows: auto;
            gap: 15px;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

         /* Logo content */   
        .logo {
            grid-column: span 2;
            grid-row: span 1;
            background-color: #273E26;
            border-radius: 8px;
            padding: 20px;
            min-height: 200px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        .logo h4 {
            color: white !important;
            font-family: 'Karla', sans-serif;
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .current-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            background-color: #273E26;
            border-radius: 6px;
        }
        
        .logo .form-label {
            color: #273E26;
            font-family: 'Karla', sans-serif;
        }
        
        .logo .btn-primary {
            background-color: #273E26;
            border-color: #273E26;
        }
        
        .logo .btn-primary:hover {
            background-color:rgb(35, 77, 35);
            border-color: #1a2c1a;
        }

        /* Info content */
        
        .info {
            grid-column: span 4;
            grid-row: span 1;
            background-color: #273E26;
            border-radius: 8px;
            padding: 20px;
            min-height: 200px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .info h4 {
            color: white;
            font-family: 'Karla', sans-serif;
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
        }

        .info .card {
            border: none;
            border-radius: 6px;
            background-color: #273E26;
            flex-grow: 1;
            color: white;
        }

        .info .form-label {
            color: white;
            font-family: 'Karla', sans-serif;
            font-weight: 500;
        }

        .info .form-control {
            border: 1px solid #5D9C59;
            border-radius: 4px;
            padding: 8px 12px;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;        }

        .info .form-control:focus {
            border-color: #5D9C59;
            box-shadow: 0 0 0 0.2rem rgba(93, 156, 89, 0.25);
        }

        .info .input-group .btn-outline-secondary {
            border-color: #ced4da;
            color: #6c757d;
        }

        .info .input-group .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        .info .form-text {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7); /* Lighter white for form helper text */
        }

        .info .btn-outline-secondary {
            border-color: #5D9C59;
            color: white;
            background-color: transparent;
        }

        .info .btn-outline-secondary:hover {
            background-color: #5D9C59;
            color: white;
            border-color: #5D9C59;
        }

        .info .btn-primary {
            background-color: #273E26;
            border-color: #273E26;
            padding: 8px 20px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .info .btn-primary:hover {
            background-color: #1a2c1a;
            border-color: #1a2c1a;
        }

        .info .alert {
            margin-bottom: 15px;
            border-radius: 4px;
        }



        /* Edit content */
        .edit {
            grid-column: 1 / -1; /* Span all columns */
            grid-row: span 1;
            background-color:#273E26;
            border-radius: 20px;
            padding: 15px;
            min-height: 270px;
        }
        
        
    </style>
</head>
<body>
    <!-- Header -->
    <header id="header" class="bg-light shadow-sm">
        <button id="hamburger" class="btn btn-primary" onclick="toggleSidebar()">
            ☰
        </button>
    </header>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column p-3 text-white vh-100">
        <a href="#" class="mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="font-logo">Tourism Office - Municipality of Rosario</span>
        </a>
        <hr>
        <div class="text-white main-menu">Main Menu</div>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="admin-dashboard.php" class="nav-link text-white"><i class="fa-brands fa-flipboard"></i> Dashboard</a>
            </li>
            <li>
                <a href="visitorslist.php" class="nav-link text-white target"><i class="fa-solid fa-user-group"></i> Visitor's List</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white chat"><i class="fa-solid fa-bus"></i> Scheduled Field Trips</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
            </li>
            <li>
                <a href="settings.php" class="nav-link active text-white"><i class="fa-solid fa-gear"></i> Settings</a>
            </li>
        </ul>
        <hr>
        <div class ="logout">
            <a href="logout.php" class="nav-link text-white"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
        </div>
    </div>

    <div id="main-content" class="container mt-1">
       
        <div class="parent">
            
            <div class="logo">
                <h4 class="mb-3 text-dark">Tourism Office Logo</h4>
                <div class="current-logo mb-3 text-center">
                    <?php
                    // Fetch current logo from database
                    $sql = "SELECT logo_path FROM site_settings WHERE id = 1";
                    $result = mysqli_query($conn, $sql);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $logoPath = $row['logo_path'] ?? 'img/rosariologo.png';
                    } else {
                        $logoPath = 'img/rosariologo.png'; // Default logo
                    }
                    ?>
                    <img src="<?php echo $logoPath; ?>" alt="Tourism Office Logo" class="img-fluid mb-2" style="max-height: 150px;">
                </div>
                
                <form action="update_logo.php" method="post" enctype="multipart/form-data" class="mt-3">
                    <div class="mb-3 text-center">
                        <input type="file" class="form-control" id="logoFile" name="logoFile" accept="image/*" required>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary">Update Logo</button>
                    </div>
                </form>
            </div>


            <div class="info">
                <h4 class="mb-3 text-white text-center">Account Information</h4>
                <div class="card">
                    <div class="card-body">
                        <form id="accountInfoForm" action="update_account.php" method="post">
                            <?php
                            // Fetch current admin information
                            $userId = $_SESSION['userID']; // Using userID from the users table
                            $sql = "SELECT username, email FROM users WHERE userID = ?";
                            $stmt = mysqli_prepare($conn, $sql);
                            mysqli_stmt_bind_param($stmt, "i", $userId);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $adminEmail = '';
                            $username = '';

                            if ($row = mysqli_fetch_assoc($result)) {
                                $adminEmail = $row['email'];
                                $username = $row['username'];
                            }
                            ?>

                            <div class="mb-3">
                                <label for="username" class="form-label fw-bold">Username</label>
                                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                                <small class="form-text text-muted">Username cannot be changed</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($adminEmail); ?>" required>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary" style="background-color: #273E26; border-color: #273E26;">Update Email</button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="password_change.php" class="btn btn-outline-secondary">
                                <i class="fas fa-key"></i> Change Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="edit">
                <!-- Edit content here -->
            </div>

        </div>
    
    </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('hamburger').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
        
        const navbar = document.getElementById('header');
        navbar.classList.toggle('shifted');
        
        const mainContent = document.getElementById('main-content');
        mainContent.classList.toggle('shifted');
        });

        document.querySelectorAll('.collapse').forEach(collapse => {
            collapse.addEventListener('show.bs.collapse', () => {
                collapse.style.height = collapse.scrollHeight + 'px';
            });
            collapse.addEventListener('hidden.bs.collapse', () => {
                collapse.style.height = '0px';
            });
        });

        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
        
        // Form validation
        document.getElementById('accountInfoForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== '' && newPassword !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'New password and confirmation do not match!',
                    confirmButtonColor: '#273E26'
                });
            }
        });

        // Error Success Message
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($_GET['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Logo updated successfully!',
                    confirmButtonColor: '#273E26'
                });
            <?php endif; ?>
            
            <?php if(isset($_GET['error'])): ?>
                <?php
                    $error = $_GET['error'];
                    $message = 'An error occurred.';
                    if($error == 'filetype') {
                        $message = 'Invalid file type. Please upload JPG, JPEG, PNG or GIF.';
                    } elseif($error == 'upload') {
                        $message = 'Failed to upload file. Please try again.';
                    } elseif($error == 'db') {
                        $message = 'Database error. Please try again.';
                    } elseif($error == 'nofile') {
                        $message = 'No file selected or error in upload.';
                    }
                ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?php echo $message; ?>',
                    confirmButtonColor: '#273E26'
                });
            <?php endif; ?>
        });

        <?php if(isset($_GET['account_success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Account information updated successfully!',
                confirmButtonColor: '#273E26'
            });
        <?php endif; ?>
        
        <?php if(isset($_GET['error'])): ?>
            <?php
                $error = $_GET['error'];
                $message = 'An error occurred.';
        
                if($error == 'invalid_email') {
                    $message = 'Please enter a valid email address.';
                } elseif($error == 'wrong_password') {
                    $message = 'Current password is incorrect.';
                } elseif($error == 'password_mismatch') {
                    $message = 'New password and confirmation do not match.';
                } elseif($error == 'update_failed') {
                    $message = 'Failed to update account information.';
                } elseif($error == 'user_not_found') {
                    $message = 'User not found. Please log in again.';
                } elseif($error == 'exception') {
                    $message = 'An error occurred: ' . (isset($_GET['message']) ? $_GET['message'] : 'Unknown error');
                }
            ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $message; ?>',
                confirmButtonColor: '#273E26'
            });
        <?php endif; ?>
    </script>
</body>
</html>
