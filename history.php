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
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="vendor/fontawesome-free/css/fontawesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="icon" href="img/rosariologo.png">
    
    <style>
        <?php include 'sidebarcss.php'; ?>
        .header-title{
            font-weight: bolder;
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

        #historyTable tbody tr:hover {
            background-color:rgb(51, 109, 160) !important; /* Light blue highlight on hover */
            cursor: pointer;
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
        <h3 class="header-title">Field Trip History</h3>
        <!-- Table Section -->
        <div id="table-container" class="container-fluid">
            <input type="text" id="customSearchBox" class="form-control mb-3 search-box" placeholder="Search visitors...">
            <table id="historyTable" class="table table-bordered table-hover text-center">
                <thead class="table-header">
                    <tr>
                        <th>School/Company Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>No. of Bus(es)</th>
                        <th>No. of Visitors</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $sql = "SELECT s.*, 
                                (
                                    SELECT COUNT(*) FROM student_tbl st WHERE st.scheduled_id = s.scheduled_id
                                ) +
                                (
                                    SELECT COUNT(*) FROM supervisor_tbl sv WHERE sv.scheduled_id = s.scheduled_id
                                ) AS num_visitors
                                FROM scheduled_tbl s
                                LEFT JOIN student_tbl st ON s.scheduled_id = st.scheduled_id
                                GROUP BY s.scheduled_id
                                ORDER BY s.updated_at DESC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while($row = $result->fetch_assoc()){
                            $formattedDate = date("F j, Y", strtotime($row['date']));
                            $formattedTime = date("h:i A", strtotime($row['time']));
                    ?>
                    <tr onclick="window.location.href='view-trip.php?id=<?php echo $row['scheduled_id']; ?>'">
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $formattedDate ?></td>
                        <td><?php echo $formattedTime ?></td>
                        <td><?php echo $row['num_bus'] ?></td>
                        <td><?php echo $row['num_visitors'] ?? 0 ?></td>
                        <td>
                            <?php 
                                $status = $row['status'];
                                $badgeClass = match ($status) {
                                    'Cancelled' => 'bg-danger',
                                    'Completed' => 'bg-success',
                                    'Upcoming' => 'bg-warning text-dark',
                                    default     => 'bg-secondary'
                                };
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="vendor/fontawesome-free/js/all.min.js"></script>
    <script src="vendor/fontawesome-free/js/fontawesome.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize the DataTable and store it in a variable
            var table = $('#historyTable').DataTable({
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

            // Custom search for the DataTable
            $('#customSearchBox').on('keyup', function () {
                table.search(this.value).draw();  // Trigger the DataTable search and redraw
            });
        });

        

        $(".view-btn").click(function () {
            var id = $(this).data("id");
            var name = $(this).data("name");
            var city = $(this).data("city");
            var gender = $(this).data("gender");
            var reason = $(this).data("reason");
            var time = $(this).data("time");
            var photo = $(this).data("photo");

            console.log("Photo filename:", photo);

            $("#viewFullName").text(name);
            $("#viewCity").text(city);
            $("#viewGender").text(gender);
            $("#viewReason").text(reason);
            $("#viewTime").text(time);

            var imagePath;

            if (photo && photo.trim() !== "" && photo !== "default.jpg") {
                imagePath = "" + photo;
            } else {
                imagePath = "/default.jpg"; // Corrected default image path
            }

            console.log("Final Image Path:", imagePath); // Debugging log

            $("#visitorPhoto").attr("src", imagePath); // Prevent browser cache issues

            $("#generateCertificateBtn").attr("data-visitor-id", id);
            $("#generateCertificateBtn").attr("data-visitor-name", name);

            $("#viewVisitorModal").modal("show");
        });

        $("#generateCertificateBtn").click(function () {
            var visitorName = $(this).attr("data-visitor-name");

            if (!visitorName) {
                alert("Visitor name is missing!");
                return;
            }

            // Open a new print window
            var printWindow = window.open('', '', 'width=1200,height=850');

            // HTML content for the certificate
            printWindow.document.write('<html><head><title>Visitor Certificate</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('@page { size: A4 landscape; margin: 0; }'); 
            printWindow.document.write('body { margin: 0; padding: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }');
            printWindow.document.write('.certificate { position: relative; width: 100%; height: 100vh; overflow: hidden; }');
            printWindow.document.write('img { width: 100%; height: 100vh; object-fit: cover; }');
            printWindow.document.write('.name { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 70px; font-weight: bold; white-space: nowrap; }'); // Name position
            printWindow.document.write('</style></head><body>');

            // Certificate layout
            printWindow.document.write('<div class="certificate">');
            printWindow.document.write('<img src="img/cert.png" alt="Certificate Background">');
            printWindow.document.write('<div class="name">' + visitorName + '</div>');
            printWindow.document.write('</div>');

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for the certificate to load before printing
            printWindow.onload = function () {
                printWindow.print();
                printWindow.close();
            };
        });

        $(document).ready(function () {
            // Fetch cities and populate the dropdown
            function loadCities() {
                $.ajax({
                    url: "fetch_cities.php", 
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        var citySelect = $("#editCity");
                        citySelect.empty(); // Clear existing options
                        citySelect.append('<option value="">Select City</option>'); // Default option
                        
                        data.forEach(city => {
                            citySelect.append(`<option value="${city.city_name}">${city.city_name}</option>`);
                        });
                    },
                    error: function () {
                        console.error("Error fetching cities.");
                    }
                });
            }

            // Handle the edit button click
            $(".edit-btn").click(function () {
                var id = $(this).data("id");
                var name = $(this).data("name");
                var city = $(this).data("city");
                var gender = $(this).data("gender");
                var reason = $(this).data("reason");

                $("#editVisitorId").val(id);
                $("#editFullName").val(name);
                $("#editGender").val(gender);
                $("#editReason").val(reason);

                loadCities(); // Load cities before setting the value
                
                setTimeout(() => {
                    $("#editCity").val(city); // Set selected city
                }, 500); // Delay to ensure dropdown is populated

                $("#editVisitorModal").modal("show");
            });

            // Form submission handler
            $("#editVisitorForm").submit(function (e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "updateVisitor.php",
                    data: formData,
                    success: function (response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            alert("Visitor details updated successfully!");
                            location.reload();
                        } else {
                            alert("Error updating visitor: " + result.message);
                        }
                        $("#editVisitorModal").modal("hide");
                    },
                    error: function () {
                        alert("An error occurred while updating.");
                    }
                });
            });
        });

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
    </script>
</body>
</html>
