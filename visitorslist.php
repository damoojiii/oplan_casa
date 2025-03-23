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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="img/rosariologo.png">
    
    <style>
        <?php include 'sidebarcss.php'; ?>

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
    <div id="sidebar" class="d-flex flex-column p-3 vh-100">
        <!-- Logo/Icon -->
        <div class="text-center">
            <div class="logo-circle">
                <?php
                    $db = new mysqli('localhost', 'root', '', 'casadb');

                    if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                    }

                    $sql = "SELECT logo FROM logo_tbl";
                    $result = $db->query($sql);

                    while($row = $result->fetch_assoc()) {
                        echo "<div class='logo-item'>";
                        echo "<img src='{$row['logo']}' alt='Logo'>";
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
                <a href="visitorslist.php" class="nav-link active">
                    <i class="fa-solid fa-user-group"></i> Visitor's List
                </a>
            </li>
            <li>
                <a href="scheduled-field-trips.php" class="nav-link">
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

    <div id="main-content" class="container">
        <!-- Filtering Section -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="yearFilter" class="form-label">Filter by Year:</label>
                <select id="yearFilter" class="form-select">
                    <option value="">All Years</option>
                    <?php
                        $yearQuery = "SELECT DISTINCT YEAR(time) AS year FROM visitors ORDER BY year DESC";
                        $yearResult = $conn->query($yearQuery);
                        while ($yearRow = $yearResult->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($yearRow['year'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($yearRow['year'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="monthFilter" class="form-label">Filter by Month:</label>
                <select id="monthFilter" class="form-select">
                    <option value="">All Months</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="cityFilter" class="form-label">Filter by City:</label>
                <select id="cityFilter" class="form-select">
                    <option value="">All Cities</option>
                    <?php
                        $cityQuery = "SELECT DISTINCT city FROM visitors ORDER BY city ASC";
                        $cityResult = $conn->query($cityQuery);
                        while ($cityRow = $cityResult->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($cityRow['city'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($cityRow['city'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="purposeFilter" class="form-label">Filter by Purpose:</label>
                <select id="purposeFilter" class="form-select">
                    <option value="">All Purposes</option>
                    <?php
                        $purposeQuery = "SELECT DISTINCT reason FROM visitors ORDER BY reason ASC";
                        $purposeResult = $conn->query($purposeQuery);
                        while ($purposeRow = $purposeResult->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($purposeRow['reason'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($purposeRow['reason'], ENT_QUOTES, 'UTF-8') . "</option>";
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- Table Section -->
        <div id="table-container" class="container-fluid">
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
                            $formattedDate = date("F j, Y", strtotime($row['time']));
                            $formattedTime = date("h:i A", strtotime($row['time']));
                            $month = date("m", strtotime($row['time']));

                            echo "<tr data-month='$month' data-city='" . htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8') . "' data-reason='" . htmlspecialchars($row['reason'], ENT_QUOTES, 'UTF-8') . "'>
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
        $(document).ready(function () {
            setTimeout(function () {
                let table = $('#visitorTable').DataTable({
                    "paging": true,
                    "searching": true, // Keep DataTables' built-in search
                    "lengthChange": false,
                    "pageLength": 10,
                    "ordering": false, // Allow sorting
                    "info": false,
                    "language": {
                        "paginate": {
                            "previous": "<i class='fas fa-chevron-left'></i>",
                            "next": "<i class='fas fa-chevron-right'></i>"
                        }
                    },
                    "dom": '<"top"f>rt<"bottom"p><"clear">'
                });

                // Custom filtering function
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    let row = table.row(dataIndex).node(); // Get the actual row element
                    let selectedMonth = $("#monthFilter").val();
                    let selectedCity = $.trim($("#cityFilter").val().toLowerCase());
                    let selectedPurpose = $.trim($("#purposeFilter").val().toLowerCase());

                    let rowMonth = $(row).attr("data-month") || "";
                    let rowCity = ($(row).attr("data-city") || "").toLowerCase().trim();
                    let rowPurpose = ($(row).attr("data-reason") || "").toLowerCase().trim();

                    let monthMatch = selectedMonth === "" || rowMonth === selectedMonth;
                    let cityMatch = selectedCity === "" || rowCity.includes(selectedCity);
                    let purposeMatch = selectedPurpose === "" || rowPurpose.includes(selectedPurpose);

                    return monthMatch && cityMatch && purposeMatch;
                });

                // Apply filter on change
                $("#monthFilter, #cityFilter, #purposeFilter").on("change", function () {
                    table.draw(); // Redraw table with filters applied
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

            if (photo && photo.trim() !== "" && photo !== "default.jpg") {
                imagePath = "" + photo;
            } else {
                imagePath = "/default.jpg"; // Corrected default image path
            }

            console.log("Final Image Path:", imagePath); // Debugging log

            $("#visitorPhoto").attr("src", imagePath); // Prevent browser cache issues

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
