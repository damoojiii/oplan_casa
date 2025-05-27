<?php
    include "session.php";
    include("connection.php");
    include "loader.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql_name = "SELECT name FROM scheduled_tbl WHERE scheduled_id = ?";
    $stmt = $conn->prepare($sql_name);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $school_result = $stmt->get_result();
    $school_name = '';
    if ($row = $school_result->fetch_assoc()) {
        $school_name = $row['name'];
    }

    $sql_fetch = "SELECT * FROM student_tbl WHERE scheduled_id = ? ORDER BY lastname ASC";
    $stmt = $conn->prepare($sql_fetch);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student_result = $stmt->get_result();

    $sql_fetch1 = "SELECT * FROM supervisor_tbl WHERE scheduled_id = ?";
    $stmt = $conn->prepare($sql_fetch1);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $supervisor_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tourism</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="icon" href="img/rosariologo.png">
    
    <style>
        <?php include 'sidebarcss.php'; ?>
        .header-title{
            font-weight: bolder;
        }
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
        .table {
            margin-top: 30px !important;
        }

        thead,
        th {
            background-color: #5D9C59 !important;
            text-align: center !important;
            color: #fff !important;
        }

        .empty-row td {
            height: 41px;
        }

        .dataTables_paginate {
            text-align: right !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 2px;
            border: 1px solid #5D9C59;
            border-radius: 5px;
            background-color: white;
            color: #5D9C59;
            transition: 0.3s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #5D9C59;
            color: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #5D9C59;
            color: white;
        }

        .btn-success {
            --bs-btn-bg: #5D9C59 !important;
            --bs-btn-border-color: #5D9C59 !important;
            --bs-btn-hover-bg: #5D9C59 !important;
        }

        .search-box {
            border: 2px solid #5D9C59;
            width: 300px;
        }

        .search-box:focus {
            border: 2px solid green;
            box-shadow: 0 0 10px green;
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
                <a href="history.php" class="nav-link active">
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

    <div id="main-content" class="container">
        <div class="mt-2 ms-2">
            <a href="history.php" class="btn btn-secondary">Back</a>
        </div>
        <div class="container mt-4">
            <ul class="nav nav-tabs" id="dataTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">Students</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="supervisors-tab" data-bs-toggle="tab" data-bs-target="#supervisors" type="button" role="tab">Supervisors</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="dataTabsContent">
                <!-- Students Tab -->
                <div class="tab-pane fade show active" id="students" role="tabpanel">
                    <h4 class="text-center"><?php echo htmlspecialchars($school_name); ?>'s Students List</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <a href="export_students_pdf.php?id=<?php echo $scheduled_id?>" class="btn btn-danger">
                            <i class="fa fa-file-pdf"></i> Export to PDF
                        </a>
                        
                        <input type="text" id="customSearchBox" class="form-control search-box" placeholder="Search students">
                    </div>
                    <?php if ($student_result->num_rows > 0): ?>
                        <table id="studentTable" class="table text-center table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Grade Level</th>
                                    <th>Guardian</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $student_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['student_id'] ?></td>
                                        <td><?= $row['lastname'] ?></td>
                                        <td><?= $row['firstname'] ?></td>
                                        <td><?= $row['grade_level'] ?></td>
                                        <td><?= $row['guardian'] ?></td>
                                        <td><?= $row['contact'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">No student records found.</div>
                    <?php endif; ?>
                </div>

                <!-- Supervisors Tab -->
                <div class="tab-pane fade" id="supervisors" role="tabpanel">
                    <h4 class="text-center"><?php echo htmlspecialchars($school_name); ?>'s Supervisors List</h4>
                    <input type="text" id="customSearchBoxSupervisor" class="form-control search-box" placeholder="Search students" style="margin-bottom: 16px;">
                    <?php if ($supervisor_result->num_rows > 0): ?>
                        <table id="supervisorTable" class="table text-center table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Position</th>
                                    <th>Contact</th>
                                    <th>Gender</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $supervisor_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['supervisor_id'] ?></td>
                                        <td><?= $row['firstname'] ?></td>
                                        <td><?= $row['lastname'] ?></td>
                                        <td><?= $row['position'] ?></td>
                                        <td><?= $row['contact'] ?></td>
                                        <td><?= $row['gender'] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">No supervisor records found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/fontawesome-free/js/all.min.js"></script>
    <script src="vendor/fontawesome-free/js/fontawesome.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize the DataTable and store it in a variable
            var studentTable = $('#studentTable').DataTable({
                "paging": true, 
                "ordering": true, 
                "info": false, 
                "lengthChange": false,  
                "searching": true, 
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                },
                dom: 'rt<"bottom"p><"clear">'
            });

            // Initialize the Supervisor Table
            var supervisorTable = $('#supervisorTable').DataTable({
                "paging": true, 
                "ordering": true, 
                "info": false, 
                "lengthChange": false,  
                "searching": true, 
                "pageLength": 10,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                },
                dom: 'rt<"bottom"p><"clear">'
            });

            // Custom search for the Student Table
            $('#customSearchBox').on('keyup', function() {
                studentTable.search(this.value).draw();  // Trigger the DataTable search and redraw for student table
            });

            // Custom search for the Supervisor Table (if needed)
            $('#customSearchBoxSupervisor').on('keyup', function() {
                supervisorTable.search(this.value).draw();  // Trigger the DataTable search and redraw for supervisor table
            });
        });
    </script>
</body>
</html>
