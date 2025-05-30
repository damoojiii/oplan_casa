<?php
    include "session.php";
    include("connection.php");

    date_default_timezone_set("Asia/Manila");
    $today = date('Y-m-d');

    $sql = "SELECT date FROM scheduled_tbl";
    $result = $conn->query($sql);
    $blocked_dates = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $blocked_dates[] = $row['date'];
        }
    }

    $sql2 = "SELECT DATE(date) as scheduled_date, status FROM scheduled_tbl";
    $result = $conn->query($sql2);

    $scheduledDates = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $scheduledDates[] = [
                'date' => $row['scheduled_date'], 
                'status' => strtolower($row['status'])
            ];
        }
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
    <link rel="stylesheet" href="css/style.css">
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
    .calendar-container{
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .calendar{
        width: 100%;
        height: auto;
        display: flex;
        flex-direction: column;
        padding: 10px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    .header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
    }
    .monthYear{
        text-align: center;
        width: 150px;
    }
    .header button{
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 50%;
        background: #fff;
        cursor: pointer;
        width: 40px;
        height: 40px;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
    }
    .days{
        display: grid;
        grid-template-columns: repeat(7,1fr);
    }
    .day{
        text-align: center;
        padding: 5px;
        color: #999FA6;
        font-weight: 500;
    }
    .dates{
        display: grid;
        grid-template-columns: repeat(7,1fr);
        gap: 5px;
    }
    .date{
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 10px;
        margin: auto;
        cursor: pointer;
        font-weight: 400;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        transition: 0.2s;
    }

    .date:hover, .date.active{
        background: #273E26;
        color: #fff;
    }
    .date.inactive{
        color: #d2d2d2;
    }
    .date.inactive:hover{
        color: #fff;
    }

    .date.active {
        background-color: #103E13; /* Today */
        color: white;
    }
    .date.upcoming {
        background-color: #FBBF24; /* Yellow */
        color: white;
    }
    .date.completed {
        background-color: #22C55E; /* Green */
        color: white;
    }
    .date.inactive {
        color: #ccc;
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
    
    #searchInput {
        max-width: 300px;
        margin-bottom: 15px !important;
        border: 2px solid #5D9C59;
    }

    #paginationControls button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
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
                <a class="nav-link active" id="tab1" href="trips.php">Scheduled Trips</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link" id="tab2" href="add-visitor.php">Add Visitor</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link" id="tab3" href="trip-info.php">Trip Info</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Tab 1: Scheduled Trips -->
            <div class="tab-pane fade show active" id="scheduledTrips">
                <div class="row">
                    <?php 
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
                            // Sanitize input data
                            $schoolname = htmlspecialchars($_POST['name']);
                            $date = $_POST['date'];
                            $time = $_POST['time'];
                            $num_bus = filter_var($_POST['num_bus'], FILTER_SANITIZE_NUMBER_INT); // Sanitize number of buses
                            $status = "Upcoming";
                            
                            // Validate that all fields have data
                            if (empty($schoolname) || empty($date) || empty($time) || empty($num_bus)) {
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
                            $sql = "INSERT INTO `scheduled_tbl` (`name`, `date`, `time`, `num_bus`, `status`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("sssis", $schoolname, $date, $time, $num_bus, $status);
                        
                                // Execute the query and check if successful
                                if ($stmt->execute()) {
                                    $_SESSION['message'] = "Schedule created successfully!";
                                    $_SESSION['message_type'] = "Success";
                                    $_SESSION['icon'] = "success";
                                } else {
                                    $_SESSION['message'] = "Error: " . $stmt->error;
                                    $_SESSION['message_type'] = "Error";
                                    $_SESSION['icon'] = "error";
                                }
                    
                                $stmt->close();
                                echo '<script type="text/javascript">
                                    window.location = "trips.php"; // Redirect to trips.php
                                </script>';
                                exit();
                            } else {
                                $_SESSION['message'] = "Error preparing the query.";
                                $_SESSION['message_type'] = "Error";
                                $_SESSION['icon'] = "error";
                                echo '<script type="text/javascript">
                                    alert("Error: ' . $conn->error . '"); // Error message
                                    window.location = "trips.php"; // Redirect to trips.php
                                </script>';
                                exit();
                            }
                        }
                    ?>
                    <!-- Left Side: Schedule Form -->
                    <div class="col-md-8">
                        <form method="POST">
                            <h3 class="header-title">Create a Schedule</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Enter School/Company Name</label>
                                    <input type="text" name="name" class="form-control input-box filters" required>
                                    <label>Choose Date</label>
                                    <input type="date" name="date" class="form-control input-box filters" id="date" required>
                                    <label for="time">Choose Time</label>
                                    <select id="time" name="time" class="form-control input-box filters" required>
                                        <option value="" hidden selected>Select a time</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Enter Number of Bus(es)</label>
                                    <input type="number" name="num_bus" class="form-control input-box filters" min="1" max="4" required>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="submit" name="create">Submit</button>
                        </form>
                    </div>

                    <!-- Right Side: Calendar -->
                    <div class="col-md-4">
                        <div class="border p-3">
                            <div class="calendar">
                                <div class="legend mt-2 px-2 d-flex align-items-center justify-content-between" style="gap: 5px; flex-wrap: wrap;">
                                    <div class="d-flex align-items-center">
                                        <span style="width: 13px; height: 13px; background-color: #273E26; border-radius: 50%; margin-right: 5px;"></span>
                                        <span style="font-size: 13px;">Today</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span style="width: 13px; height: 13px; background-color: #f0ad4e; border-radius: 50%; margin-right: 5px;"></span>
                                        <span style="font-size: 13px;">Upcoming Trip</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span style="width: 13px; height: 13px; background-color: #5cb85c; border-radius: 50%; margin-right: 5px;"></span>
                                        <span style="font-size: 13px;">Completed Trip</span>
                                    </div>
                                </div>
                                <div class="header">
                                    <button id="prevBtn"><i class="fa-solid fa-chevron-left"></i></button>
                                    <div class="monthYear header-title" id="monthYear"></div>
                                    <button id="nextBtn"><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                                <div class="days">
                                    <div class="day">Mon</div>
                                    <div class="day">Tue</div>
                                    <div class="day">Wed</div>
                                    <div class="day">Thu</div>
                                    <div class="day">Fri</div>
                                    <div class="day">Sat</div>
                                    <div class="day">Sun</div>
                                </div>
                                <div class="dates" id="dates"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Schedule Table -->
                <h3 class="header-title">View Schedule</h3>
                <input type="text" id="searchInput" placeholder="Search by name, date, time, etc." class="form-control mb-3 input-box filters">
                <table class="table table-bordered text-center">
                    <thead class="table-header">
                        <tr>
                            <th>School/Company Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>No. of Bus(es)</th>
                            <th>No. of Visitors</th>
                            <th>Status</th>
                            <th>Action</th>
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
                            WHERE s.status = 'Upcoming'
                            GROUP BY s.scheduled_id
                            ORDER BY s.updated_at DESC";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()){
                                $formattedDate = date("F j, Y", strtotime($row['date']));
                                $formattedTime = date("h:i A", strtotime($row['time']));
                        ?>
                        <tr>
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
                            <td>
                                <button class="btn btn-success btn-sm approve-btn" data-id="<?php echo $row['scheduled_id']; ?>">
                                    <i class="fa-solid fa-check"></i> Approve
                                </button>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['scheduled_id']; ?>">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['scheduled_id']; ?>">
                                    <i class="fa-solid fa-x"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div id="paginationControls" class="d-flex justify-content-center mt-3">
                    <button id="prevPage" class="btn btn-primary me-2"><i class="fa-solid fa-chevron-left"></i></button>
                    <span id="pageNumber" class="align-self-center mx-2">Page 1</span>
                    <button id="nextPage" class="btn btn-primary ms-2"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search and Pagination Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = Array.from(document.querySelectorAll('tbody tr'));
            let currentPage = 1;
            const rowsPerPage = 5;
            let filteredRows = [...tableRows];

            function updateTableDisplay() {
                // Hide all rows
                tableRows.forEach(row => row.style.display = 'none');
                
                // Show current page rows
                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;
                filteredRows.slice(start, end).forEach(row => {
                    row.style.display = '';
                });

                // Update page info
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                document.getElementById('pageNumber').textContent = `Page ${currentPage} of ${totalPages}`;
                
                // Toggle button states
                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === totalPages || totalPages === 0;
            }

            // Search input handler
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filteredRows = tableRows.filter(row => {
                    const cells = Array.from(row.cells).slice(0, -1); // Exclude Action column
                    return cells.some(cell => 
                        cell.textContent.toLowerCase().includes(searchTerm)
                    );
                });
                currentPage = 1;
                updateTableDisplay();
            });

            // Pagination controls
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    updateTableDisplay();
                }
            });

            document.getElementById('nextPage').addEventListener('click', () => {
                const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    updateTableDisplay();
                }
            });

            // Initial setup
            updateTableDisplay();
        });
        </script>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" method="POST" action="updateSchedule.php">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <!-- Hidden ID -->
                <input type="hidden" name="scheduled_id" id="editScheduledId">
                
                <div class="mb-3">
                    <label for="editName" class="form-label">School/Company Name</label>
                    <input type="text" class="form-control" name="name" id="editName" required>
                </div>
                <div class="mb-3">
                    <label for="editDate" class="form-label">Date</label>
                    <input type="date" class="form-control" name="date" id="editDate" required>
                </div>
                <div class="mb-3">
                    <label for="editTime" class="form-label">Time</label>
                    <select class="form-select" name="time" id="editTime" required>
                        <?php
                        $start = strtotime("06:00");
                        $end = strtotime("16:00");
                        for ($t = $start; $t <= $end; $t += 60 * 60) { // 60 minutes
                            $value = date("H:i", $t); // 24-hour format
                            $label = date("g:i A", $t); // 12-hour display format
                            echo "<option value=\"$value\">$label</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="editNumBus" class="form-label">No. of Bus(es)</label>
                    <input type="number" class="form-control" name="num_bus" min="1" max="5" id="editNumBus" required>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="vendor/fontawesome-free/js/all.min.js"></script>
    <script src="vendor/fontawesome-free/js/fontawesome.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Disable specific dates based on the database data
        document.addEventListener('DOMContentLoaded', function() {
            const today = '<?php echo $today; ?>';
            const blockedDates = <?php echo json_encode($blocked_dates); ?>;
            
            const addDateInput = document.getElementById('date');      // For Add Schedule
            const editDateInput = document.getElementById('editDate'); // For Edit Schedule
            const editForm = document.getElementById('editForm');

            let currentEditDate = null; // store the original date being edited

            // Set minimum dates
            if (addDateInput) addDateInput.setAttribute('min', today);
            if (editDateInput) editDateInput.setAttribute('min', today);

            // Blocked date logic
            function isDateBlocked(date, excludeDate = null) {
                return blockedDates.includes(date) && date !== excludeDate;
            }

            // Add form date blocking
            if (addDateInput) {
                addDateInput.addEventListener('input', function () {
                    const selected = this.value;
                    if (isDateBlocked(selected)) {
                        alert("This date is already booked. Please choose another date.");
                        this.value = '';
                    }
                });
            }

            // Edit form date blocking
            if (editDateInput) {
                editDateInput.addEventListener('input', function () {
                    const selected = this.value;
                    if (isDateBlocked(selected, currentEditDate)) {
                        alert("This date is already booked. Please choose another date.");
                        this.value = '';
                    }
                });
            }

            // Handle edit button click and fetch existing data
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');

                    fetch('getSchedule.php?id=' + id)
                        .then(response => response.json())
                        .then(data => {
                            // Populate modal inputs
                            document.getElementById('editScheduledId').value = data.scheduled_id;
                            document.getElementById('editName').value = data.name;
                            document.getElementById('editDate').value = data.date;
                            document.getElementById('editTime').value = data.time.slice(0, 5);
                            document.getElementById('editNumBus').value = data.num_bus;

                            // Store original date for blocking exception
                            currentEditDate = data.date;

                            // Show modal
                            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                            editModal.show();
                        })
                        .catch(error => {
                            alert('Error fetching schedule data.');
                            console.error(error);
                        });
                });
            });
            
            // This is an additional enhancement to show blocked dates visually
            const blockedDatesString = blockedDates.join(',');

            // Apply the blocked dates visually by disabling them in the input
            dateInput.setAttribute('data-blocked-dates', blockedDatesString);

            // For browsers that support modern input types (like Firefox), block out the dates
            if (dateInput.type === "date") {
                dateInput.setAttribute("style", "pointer-events: auto;");

                // Use the input's min and max to block outside of today's date
                const blocked = blockedDates.map(date => {
                    return `option[value="${date}"] { background-color: red; color: white; }`;
                }).join("\n");

                const style = document.createElement('style');
                style.innerHTML = blocked;
                document.head.appendChild(style);
            }
        });
        
        function convertTo12HourFormat(hour) {
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12; // Converts 0 to 12 (midnight case)
            return `${hour12}:00 ${ampm}`;
        }

        // Populate the dropdown with valid times from 6:00 AM to 4:00 PM
        const select = document.getElementById('time');
        const times = [
            6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16
        ];

        // Create options for the dropdown with 12-hour format
        times.forEach(hour => {
            const option = document.createElement('option');
            const timeIn12HourFormat = convertTo12HourFormat(hour);
            option.value = `${hour}:00`; 
            option.textContent = timeIn12HourFormat; 
            select.appendChild(option);
        });

        const monthYearElement = document.getElementById('monthYear');
        const datesElement = document.getElementById('dates');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const scheduledDates = <?php echo json_encode($scheduledDates); ?>;

        let currentDate = new Date();

        const updateCalendar = () => {
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth();

            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const totalDays = lastDay.getDate();
            const firstDayIndex = firstDay.getDay(); 
            const lastDayIndex = lastDay.getDay();

            const monthYearString = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
            monthYearElement.textContent = monthYearString;

            let datesHTML = '';

            // Fill previous month inactive dates
            let prevMonthDays = (firstDayIndex + 6) % 7;
            const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
            for (let i = prevMonthDays; i > 0; i--) {
                datesHTML += `<div class="date inactive">${prevMonthLastDay - i + 1}</div>`;
            }

            // Fill current month days
            for (let i = 1; i <= totalDays; i++) {
                const date = new Date(currentYear, currentMonth, i);
                function formatLocalDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }

                const dateString = formatLocalDate(date); 
                let extraClass = '';
                const todayString = formatLocalDate(new Date());

                if (dateString === todayString) {
                    extraClass = 'active';
                } else {
                    const scheduled = scheduledDates.find(s => s.date === dateString);
                    if (scheduled) {
                        if (scheduled.status === 'upcoming') {
                            extraClass = 'upcoming'; 
                        } else if (scheduled.status === 'completed') {
                            extraClass = 'completed'; 
                        }
                    }
                }

                datesHTML += `<div class="date ${extraClass}">${i}</div>`;
            }

            // Fill next month inactive dates
            for (let i = 1; i <= (7 - ((firstDayIndex + totalDays - 1) % 7) - 1); i++) {
                datesHTML += `<div class="date inactive">${i}</div>`;
            }

            // ✅ Now update the page!
            datesElement.innerHTML = datesHTML;
        };  

        prevBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendar();
            togglePrevButton();
        });

        nextBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth()+1);
            updateCalendar();
            togglePrevButton();
        });
        
        
        updateCalendar();

        document.addEventListener('DOMContentLoaded', function() {
            // Approve
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    if(confirm("Approve this schedule?")) {
                        window.location.href = 'approveSchedule.php?id=' + id;
                    }
                });
            });

            // Delete
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    if(confirm("Are you sure you want to cancel this schedule?")) {
                        window.location.href = 'deleteSchedule.php?id=' + id;
                    }
                });
            });
        });

        
    </script>
</body>

</html>