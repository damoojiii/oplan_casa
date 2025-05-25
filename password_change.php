<?php
    include "session.php";
    include("connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Admin Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="img/rosariologo.png">
    
    <style>
        <?php include 'sidebarcss.php'; ?>
        .parent {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: auto;
            gap: 15px;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

         /* Logo content */   
        .logo-main {
            grid-column: span 2;
            grid-row: span 1;
            background-color: #5D9C59;
            border-radius: 8px;
            padding: 20px;
            min-height: 200px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        .logo h4 {
            color: white !important;
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .current-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-inline: auto;
            padding: 10px;
            width: 45%;
            background-color: #77ac67;
            border-radius: 50%;
        }
        
        .password-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #273E26;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .password-container h4 {
            color: white;
            font-family: 'Karla', sans-serif;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .password-card {
            background-color: #273E26;
            border-radius: 6px;
            padding: 20px;
        }
        
        .form-label {
            font-weight: 500;
            color:rgb(255, 255, 255);
        }
        
        .btn-primary {
            background-color: #273E26;
            border-color: #273E26;
        }
        
        .btn-primary:hover {
            background-color: #1a2c1a;
            border-color: #1a2c1a;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
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
    <div id="sidebar" class="d-flex flex-column p-3 vh-100">
        <!-- Logo/Icon -->
        <div class="text-center">
            <div class="logo-circle">
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
                            echo "<img src='$logo' alt='Logo' class='logo-circle' style='width: 90px; height: 90px;'>";
                            echo "</div>";
                        }
                    } else {
                        // If walay logong makita, display the default logo nganii para di empty yung logo
                        echo "<div class='logo-item'>";
                        echo "<img src='img/rosariologo.png' alt='Default Logo' style='width: 80px; height: 80px;'>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>

        <h6 class="text-white text-center mt-2">Tourism Office</h6>
        <p class="text-white text-center small">Municipality of Rosario</p>
        
        <hr>

        <div class="text-white main-menu">Main Menu</div>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="admin-dashboard.php" class="nav-link">
                    <i class="fa-solid fa-list"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="visitorslist.php" class="nav-link">
                    <i class="fa-solid fa-user-group"></i> Visitor's List
                </a>
            </li>
            <li>
                <a href="trips.php" class="nav-link">
                    <i class="fa-solid fa-bus"></i> Scheduled Field Trips
                </a>
            </li>
            <li>
                <a href="history.php" class="nav-link">
                    <i class="fa-solid fa-clock-rotate-left"></i> History
                </a>
            </li>
            <li>
                <a href="settings.php" class="nav-link active">
                    <i class="fa-solid fa-gear"></i> Settings
                </a>
            </li>
        </ul>

        <hr>

        <div class="logout">
            <a href="logout.php" class="nav-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Log out
            </a>
        </div>
    </div>

    <div id="main-content" class="container mt-1">
        <div class="password-container">
            <h4>Change Password</h4>
            <div class="password-card">
                <form id="passwordChangeForm" action="change_password.php" method="post">
                <div class="mb-3">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <a href="settings.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Settings
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'New password and confirmation do not match!',
                    confirmButtonColor: '#273E26'
                });
            }
        });

        // Display messages if they exist in URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(isset($_GET['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Password changed successfully!',
                    confirmButtonColor: '#273E26'
                });
            <?php endif; ?>
            
            <?php if(isset($_GET['error'])): ?>
                <?php
                   $error = $_GET['error'];
                   $message = 'An error occurred.';
                   
                   if($error == 'wrong_password') {
                       $message = 'Current password is incorrect.';
                   } elseif($error == 'password_mismatch') {
                       $message = 'New password and confirmation do not match.';
                   } elseif($error == 'update_failed') {
                       $message = 'Failed to update password.';
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
        });
    </script>
</body>
</html>
