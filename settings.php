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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
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
        
        /* Info content */
        
        .info {
            grid-column: span 3;
            grid-row: span 1;
            background-color: #5D9C59;
            border-radius: 8px;
            padding: 20px;
            min-height: 200px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .info h4 {
            color: white;
            font-weight: 600;
            text-align: center;
            margin-bottom: 15px;
        }

        .info .card {
            border: none;
            border-radius: 6px;
            background-color: #77ac67;
            flex-grow: 1;
            color: white;
        }

        .info .form-label {
            color: white;
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

        .info .alert {
            margin-bottom: 15px;
            border-radius: 4px;
        }

        /* Edit content */
        .edit {
            grid-column: 1 / -1; /* Span all columns */
            grid-row: span 1;
            background-color:#5D9C59;
            border-radius: 20px;
            padding: 15px;
            min-height: 270px;
        }

        .card-header{
            background-color: #77ac67;
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
    

        <div class="parent">
            
            <div class="logo-main">
                <h4 class="mb-3 text-white">Rosario Tourism Office Logo</h4>
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
                        <input type="file" class="form-control input-box filters" id="logoFile" name="logoFile" accept="image/*" required>
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
                                <input type="text" class="form-control input-box filters" id="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                                <small class="form-text text-muted">Username cannot be changed</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control input-box filters" id="email" name="email" value="<?php echo htmlspecialchars($adminEmail); ?>" required>
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
                <h4 class="mb-3 text-white text-center">Edit Site Information</h4>
                <div class="card">
                    <div class="card-body">
                        
                        <!-- Visit Purpose Management Section -->
                        <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                if (isset($_POST['newReason'])) {
                                    $purpose = $conn->real_escape_string($_POST['newReason']);
                                    if ($conn->query("INSERT INTO purpose_tbl (purpose) VALUES ('$purpose')")) {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: 'Purpose added successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Failed to add purpose.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    }
                                }

                                if (isset($_POST['delete_purpose'])) {
                                    $id = (int)$_POST['id'];
                                    if ($conn->query("DELETE FROM purpose_tbl WHERE purpose_id = $id")) {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Deleted!',
                                                    text: 'Purpose deleted successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Failed to delete purpose.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    }
                                }
                                
                                if (isset($_POST['edit_city_id']) && isset($_POST['edit_city_name'])) {
                                    $id = (int)$_POST['edit_city_id'];
                                    $city_name = $conn->real_escape_string($_POST['edit_city_name']);
                                    if ($conn->query("UPDATE cities SET city_name = '$city_name' WHERE cityID = $id")) {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Updated!',
                                                    text: 'City updated successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Failed to update city.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    }
                                }

                                if (isset($_POST['edit_purpose_id']) && isset($_POST['edit_purpose_name'])) {
                                    $id = (int)$_POST['edit_purpose_id'];
                                    $purpose = $conn->real_escape_string($_POST['edit_purpose_name']);
                                    if ($conn->query("UPDATE purpose_tbl SET purpose = '$purpose' WHERE purpose_id = $id")) {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Updated!',
                                                    text: 'Purpose updated successfully.',
                                                    icon: 'success',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    } else {
                                        echo "<script>
                                                Swal.fire({
                                                    title: 'Error!',
                                                    text: 'Failed to update purpose.',
                                                    icon: 'error',
                                                    confirmButtonText: 'Okay'
                                                });
                                            </script>";
                                    }
                                }

                            }
                        ?>


                        <h5 class=" mb-3">Manage Visit Purposes</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Add new visit purpose -->
                                <div class="card bg-light mb-3">
                                    <div class="card-header text-white">
                                        <i class="fas fa-plus-circle"></i> Add New Purpose
                                    </div>
                                    <div class="card-body">
                                        <form id="addReasonForm" method="post">
                                            <input type="hidden" name="action" value="add">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control input-box filters" name="newReason" placeholder="New visit purpose" required>
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Current visit purposes list -->
                                <div class="card bg-light">
                                    <div class="card-header text-white">
                                        <i class="fas fa-list"></i> Current Purposes
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="purposeSelect" class="form-label">Select a purpose to manage:</label>
                                            <select id="purposeSelect" class="form-select input-box filters" onchange="showPurpose(this.value)">
                                                <option value="">-- Select a purpose --</option>
                                                <?php
                                                $result = $conn->query("SELECT * FROM purpose_tbl ORDER BY purpose_id DESC");
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['purpose_id']}'>{$row['purpose']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div id="purposeDetails" class="mt-3 p-3 filters rounded bg-white"></div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>  
                </div>
                <!-- Manage Cities -->

                <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['newCity'])) {
                            $city_name = $conn->real_escape_string($_POST['newCity']);
                            if ($conn->query("INSERT INTO cities (city_name) VALUES ('$city_name')")) {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'City added successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'Okay'
                                        });
                                    </script>";
                            } else {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Failed to add city.',
                                            icon: 'error',
                                            confirmButtonText: 'Okay'
                                        });
                                    </script>";
                            }
                        }

                        if (isset($_POST['delete_city'])) {
                            $id = (int)$_POST['id'];
                            if ($conn->query("DELETE FROM cities WHERE cityID = $id")) {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'City deleted successfully.',
                                            icon: 'success',
                                            confirmButtonText: 'Okay'
                                        });
                                    </script>";
                            } else {
                                echo "<script>
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Failed to delete city.',
                                            icon: 'error',
                                            confirmButtonText: 'Okay'
                                        });
                                    </script>";
                            }
                        }
                    }
                ?>

                <div class="card">
                    <div class="card-body">
                        <h5 class=" mb-3">Manage Cities</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-header text-white">
                                        <i class="fas fa-plus-circle"></i> Add New City
                                    </div>

                                    <div class="card-body">
                                        <form id="addCityForm" method="post">
                                            <input type="hidden" name="action" value="add">
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control input-box filters" name="newCity" placeholder="Add New City" required>
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Current city list -->
                                <div class="card bg-light">
                                    <div class="card-header text-white">
                                        <i class="fas fa-list"></i> Current Cities
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="purposeSelect" class="form-label">Select a City to manage:</label>
                                            <select id="citySelect" class="form-select input-box filters" onchange="showCities(this.value)">
                                                <option value="">-- Select a city --</option>
                                                <?php
                                                $result = $conn->query("SELECT * FROM cities ORDER BY cityID DESC");
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['cityID']}'>{$row['city_name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div id="cityDetails" class="mt-3 p-3 rounded bg-white filters"></div>
                                    </div>
                                </div>
                            </div>
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

        // Function to show purpose details
        function showPurpose(id) {
            if (id === "") {
                document.getElementById('purposeDetails').innerHTML = "";
                return;
            }

            const selectElement = document.getElementById('purposeSelect');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const purposeName = selectedOption.text;

            document.getElementById('purposeDetails').innerHTML = `
                <div class='purpose-item'>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">${purposeName}</h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="editPurpose(${id}, '${purposeName.replace(/'/g, "\\'")}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='${id}'>
                                <button type='submit' name='delete_purpose' class='btn btn-danger btn-sm'>
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `;
        }

        function editPurpose(id, oldName) {
            const newName = prompt("Edit Purpose Name:", oldName);
            if (newName && newName.trim() !== "") {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'edit_purpose_id';
                idInput.value = id;
                form.appendChild(idInput);

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'edit_purpose_name';
                nameInput.value = newName;
                form.appendChild(nameInput);

                document.body.appendChild(form);
                form.submit();
            }
        }



        // Function to show city details
        function showCities(id) {
            if (id === "") {
                document.getElementById('cityDetails').innerHTML = "";
                return;
            }

            const selectElement = document.getElementById('citySelect');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const cityName = selectedOption.text;

            document.getElementById('cityDetails').innerHTML = `
                <div class='purpose-item'>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" id="cityNameDisplay">${cityName}</h5>
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="editCity(${id}, '${cityName.replace(/'/g, "\\'")}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id' value='${id}'>
                                <button type='submit' name='delete_city' class='btn btn-danger btn-sm'>
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            `;
        }
        function editCity(id, oldName) {
            const newName = prompt("Edit City Name:", oldName);
            if (newName && newName.trim() !== "") {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'edit_city_id';
                idInput.value = id;
                form.appendChild(idInput);

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = 'edit_city_name';
                nameInput.value = newName;
                form.appendChild(nameInput);

                document.body.appendChild(form);
                form.submit();
            }
        }



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

        // Preview Image
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


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
