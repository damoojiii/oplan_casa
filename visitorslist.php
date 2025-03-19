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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
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
                <a href="visitorslist.php" class="nav-link text-white active target"><i class="fa-solid fa-user-group"></i> Visitor's List</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white chat"><i class="fa-solid fa-bus"></i> Scheduled Field Trips</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
            </li>
            <li>
                <a href="settings.php" class="nav-link text-white"><i class="fa-solid fa-gear"></i> Settings</a>
            </li>
        </ul>
        <hr>
        <div class ="logout">
            <a href="logout.php" class="nav-link text-white"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
        </div>
    </div>

    <div id="main-content" class="container d-flex justify-content-center align-items-center vh-100">
        <div id="table-container" class="w-100 container-fluid">
            <table id="visitorTable" class="table table-bordered text-center">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Visitor No.</th>
                        <th>Visitor Name</th>
                        <th>City</th>
                        <th>Gender</th>
                        <th>Purpose for Visit</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT visitor_id, fullName, city, gender, reason, time, photo FROM visitors ORDER BY visitor_id ASC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $formattedDate = date("Y-m-d", strtotime($row['time']));
                            $formattedTime = date("h:i A", strtotime($row['time']));

                            echo "<tr>
                                <td>{$row['visitor_id']}</td>
                                <td>" . ucwords(strtolower($row['fullName'])) . "</td>
                                <td>{$row['city']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['reason']}</td>
                                <td>{$formattedDate}</td>
                                <td>{$formattedTime}</td>
                                <td>
                                    <a href='#' 
                                        class='btn btn-info btn-sm view-btn' 
                                        data-id='{$row['visitor_id']}' 
                                        data-name='" . htmlspecialchars($row['fullName'], ENT_QUOTES, 'UTF-8') . "' 
                                        data-city='" . htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8') . "' 
                                        data-gender='{$row['gender']}' 
                                        data-reason='" . htmlspecialchars($row['reason'], ENT_QUOTES, 'UTF-8') . "' 
                                        data-time='{$formattedTime}' 
                                        data-photo='" . (!empty($row['photo']) ? $row['photo'] : 'default.jpg') . "' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#viewVisitorModal'>
                                        <i class='fa-solid fa-eye'></i>
                                    </a>
                                    <a href='#' 
                                        class='btn btn-warning btn-sm edit-btn' 
                                        data-id='{$row['visitor_id']}' 
                                        data-name='" . htmlspecialchars($row['fullName'], ENT_QUOTES, 'UTF-8') . "' 
                                        data-city='" . htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8') . "' 
                                        data-gender='{$row['gender']}' 
                                        data-reason='" . htmlspecialchars($row['reason'], ENT_QUOTES, 'UTF-8') . "' 
                                        data-bs-toggle='modal' 
                                        data-bs-target='#editVisitorModal'>
                                        <i class='fa-solid fa-pen-to-square'></i>
                                    </a>
                                    <a href='deleteVisitor.php?id={$row['visitor_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'><i class='fa-solid fa-trash'></i></a>
                                </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- View Visitor Modal -->
        <div class="modal fade" id="viewVisitorModal" tabindex="-1" aria-labelledby="viewVisitorModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content" style="margin-left: auto; margin-right: auto;">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewVisitorModalLabel">Visitor Profile</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row text-center">
                            <!-- Centered Image above information -->
                            <div class="col-12 mb-3">
                                <img id="visitorPhoto" src="uploads/default.jpg" alt="Visitor Photo" class="img-fluid" style="width: 150px; height: 150px; border-radius: 10px; border: 2px solid #ddd;">
                            </div>
                            <div class="col-12">
                                <p><strong>Full Name:</strong> <span id="viewFullName"></span></p>
                                <p><strong>City:</strong> <span id="viewCity"></span></p>
                                <p><strong>Gender:</strong> <span id="viewGender"></span></p>
                                <p><strong>Purpose for Visit:</strong> <span id="viewReason"></span></p>
                                <p><strong>Time:</strong> <span id="viewTime"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="margin-left: auto; margin-right: auto;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Visitor Modal -->
        <div class="modal fade" id="editVisitorModal" tabindex="-1" aria-labelledby="editVisitorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editVisitorModalLabel">Edit Visitor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editVisitorForm">
                            <input type="hidden" id="editVisitorId" name="visitor_id">
                            <div class="form-group">
                                <label for="editFullName">Full Name</label>
                                <input type="text" class="form-control" id="editFullName" name="fullName" required>
                            </div>
                            <div class="form-group">
                                <label for="editCity">City</label>
                                <select class="form-control" id="editCity" name="city" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editGender">Gender</label>
                                <select class="form-control" id="editGender" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editReason">Reason</label>
                                <input type="text" class="form-control" id="editReason" name="reason" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#visitorTable').DataTable({
                    "paging": true,
                    "searching": false,
                    "lengthChange": false,
                    "pageLength": 10,
                    "ordering": false,
                    "info": false,
                    "language": {
                        "paginate": {
                            "previous": "<i class='fas fa-chevron-left'></i>",
                            "next": "<i class='fas fa-chevron-right'></i>"
                        }
                    },
                    "dom": '<"top"f>rt<"bottom"p><"clear">'
                });
            }, 500);
        });

        $(".view-btn").click(function () {
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
            if (photo && !photo.includes("uploads/")) {
                imagePath = "OPLAN_CASA-1/uploads/" + photo;
            } else {
                imagePath = "/" + photo;
            }

            // Use default image if empty
            if (!photo || photo.trim() === "") {
                imagePath = "/default.jpg";
            }

            console.log("Final Image Path:", imagePath); // Debugging log

            $("#visitorPhoto").attr("src", imagePath);

            $("#viewVisitorModal").modal("show");
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
