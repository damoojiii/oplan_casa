<?php
    include "session.php";
    include("connection.php");
    include "loader.php";
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
    <?php include 'sidebarcss.php'; ?>
    .nav-tabs {
       margin-top: 20px;
    }

    .nav-tabs .nav-item {
        margin-right: 3px;
    }
    .nav-tabs .nav-link {
        border: 2px solid #ddd;
        border-bottom: none;
        border-radius: 8px 8px 0 0;
        background: #f8f9fa;
        color: #333;
        padding: 10px 15px;
        font-weight: bold;
        transition: 0.3s;
    }
    .nav-tabs .nav-link.active {
        background: #273E26;
        color: #fff;
        border-color: #273E26;
    }

    /* Hover Effect */
    .nav-tabs .nav-link:hover {
        background: #273E26;
        border-color: #273E26;
        color: #fff;
    }

    .header-title{
        font-weight: bolder;
    }

    thead,
    th {
        background-color: #5D9C59 !important;
        text-align: center !important;
        color: #fff !important;
    }

    td{
        font-size: 15px !important;
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
                            echo "<img src='$logo' alt='Logo' class='logo-circle' style='width: 80px; height: 80px;'>";
                            echo "</div>";
                        }
                    } else {
                        // If walay logong makita, display the default logo nganii para di empty yung logo
                        echo "<div class='logo-item'>";
                        echo "<img src='img/rosariologo.png' alt='Default Logo' class='logo-circle' style='width: 80px; height: 80px;'>";
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
                <a href="trips.php" class="nav-link active">
                    <i class="fa-solid fa-bus"></i> Scheduled Field Trips
                </a>
            </li>
            <li>
                <a href="history.php" class="nav-link">
                    <i class="fa-solid fa-clock-rotate-left"></i> History
                </a>
            </li>
            <li>
                <a href="settings.php" class="nav-link">
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
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="scheduleTabs">
            <li class="nav-item tabs">
                <a class="nav-link" id="tab1" data-bs-toggle="tab" href="trips.php">Scheduled Trips</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link active" id="tab2" data-bs-toggle="tab" href="add-visitor.php">Add Visitor</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link" id="tab3" data-bs-toggle="tab" href="trip-info.php">Trip Info</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane fade show active">
                <div class="row">
                    <?php 
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student'])) {
                            $firstname = htmlspecialchars($_POST['firstname']);
                            $lastname = htmlspecialchars($_POST['lastname']);
                            $guardian = htmlspecialchars($_POST['guardian']);
                            $contact = htmlspecialchars($_POST['contact']);
                            $gender = $_POST['gender'];
                            $school = $_POST['school'];
                            $grade_level = $_POST['grade_name'];
                            
                            // Validate that all fields have data
                            if (empty($firstname) || empty($lastname) || empty($guardian) || empty($contact)) {
                                $_SESSION['message'] = "All fields are required!";
                                $_SESSION['message_type'] = "Error";
                                $_SESSION['icon'] = "error";
                                echo '<script type="text/javascript">
                                    alert("All fields are required!"); // Show an alert message
                                    window.location = "trips.php"; // Redirect to trips.php
                                </script>';

                                exit();
                            }
                        
                            // Prepare SQL query for insertion
                            $sql_insert = "INSERT INTO `student_tbl` (`scheduled_id`, `firstname`, `lastname`, `guardian`, `contact`, `gender`,`grade_level`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                            if ($stmt = $conn->prepare($sql_insert)) {
                                $stmt->bind_param("issssss", $school, $firstname, $lastname, $guardian, $contact, $gender, $grade_level);   
                                // Execute the query and check if successful
                                if ($stmt->execute()) {
                                    $_SESSION['message'] = "Student added successfully!";
                                    $_SESSION['message_type'] = "Success";
                                    $_SESSION['icon'] = "success";
                                } else {
                                    $_SESSION['message'] = "Error: " . $stmt->error;
                                    $_SESSION['message_type'] = "Error";
                                    $_SESSION['icon'] = "error";
                                }
                    
                                $stmt->close();
                                echo '<script type="text/javascript">
                                    window.location = "add-visitor.php";
                                </script>';
                                exit();
                            } else {
                                $_SESSION['message'] = "Error preparing the query.";
                                $_SESSION['message_type'] = "Error";
                                $_SESSION['icon'] = "error";
                                echo '<script type="text/javascript">
                                    alert("Error: ' . $conn->error . '"); // Error message
                                    window.location = "add-visitor.php"; 
                                </script>';
                                exit();
                            }
                        }
                    ?>
                    <div class="col-md-8">
                        <form method="POST">
                            <h3 class="header-title">Add a Student</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Enter Student First Name</label>
                                    <input type="text" name="firstname" class="form-control" required>
                                    <label>Enter Student Last Name</label>
                                    <input type="text" name="lastname" class="form-control" required>
                                    <label>Enter Student's Guardian</label>
                                    <input type="text" name="guardian" class="form-control" required>
                                    <label>Enter Contact No.</label>
                                    <input type="number" name="contact" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label input-label">Gender</label>
                                    <select id="gender" name="gender" class="form-select" required>
                                        <option value="" disabled selected hidden>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <label for="school">School/Company Name</label>
                                    <select id="school" name="school" class="form-control" required>
                                        <option value="" hidden selected>Select a school</option>
                                        <?php
                                            // Query to get the cities
                                            $sql = "SELECT scheduled_id, name FROM scheduled_tbl WHERE status = 'Upcoming' OR status ='Ongoing'"; 
                                            $result = $conn->query($sql);
                                        
                                            // Loop through the results and display them as options
                                            if ($result->num_rows > 0) {
                                                while($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row["scheduled_id"] . '">' . ucwords($row["name"]) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No cities found</option>';
                                            }
                                        ?>
                                    </select>
                                    <label for="grade">Grade Level</label>
                                    <select id="grade" name="grade_name" class="form-control" required>
                                        <option value="" hidden selected>Select grade level</option>
                                        <?php
                                            // Query to get the cities
                                            $sql1 = "SELECT grade_name FROM gradelvl_tbl"; 
                                            $result1 = $conn->query($sql1);
                                        
                                            // Loop through the results and display them as options
                                            if ($result1->num_rows > 0) {
                                                while($row = $result1->fetch_assoc()) {
                                                    echo '<option value="' . $row["grade_name"] . '">' . ucwords($row["grade_name"]) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No cities found</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="submit" name="student">Submit</button>
                        </form>
                    </div>
                    <div class="col-md-4">
                        <div class="excel-container">
                        <h3 class="header-title">Excel Upload</h3>
                        <form method="POST" enctype="multipart/form-data">
                            <label for="school">School/Company Name</label>
                            <select id="school" name="school" class="form-control" required>
                                <option value="" hidden selected>Select a school</option>
                            </select>

                            <!-- Excel File Upload -->
                            <label for="excel_file" class="mt-3">Upload Excel File</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xls,.xlsx" required>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                        </div>
                    </div>

                    <hr class="mt-3">

                    <div class="supervisor-container">
                    <h3 class="header-title">Add a Supervisor</h3>
                        <form method="POST">
                            <label>Enter First Name</label>
                            <input type="text" name="firstname" class="form-control" required>
                            <label>Enter Last Name</label>
                            <input type="text" name="lastname" class="form-control" required>
                            <label for="position">Position</label>
                            <select id="position" name="position" class="form-control" required>
                                <option value="" hidden selected>Select a position</option>
                            </select>
                            <label>Enter Contact No.</label>
                            <input type="number" name="contact" class="form-control" required>

                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        
    </script>
</body>

</html>