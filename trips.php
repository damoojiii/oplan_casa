<?php
    include "session.php";
    include("connection.php");
    include "loader.php";

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
                <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="trips.php">Scheduled Trips</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link" id="tab2" data-bs-toggle="tab" href="add-visitor.php">Add Visitor</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link" id="tab3" data-bs-toggle="tab" href="trip-info.php">Trip Info</a>
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
                            $sql = "INSERT INTO `scheduled_tbl` (`name`, `date`, `time`, `num_bus`, `status`) VALUES (?, ?, ?, ?, ?)";
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
                                    <input type="text" name="name" class="form-control" required>
                                    <label>Choose Date</label>
                                    <input type="date" name="date" class="form-control" id="date" required>
                                    <label for="time">Choose Time</label>
                                    <select id="time" name="time" class="form-control" required>
                                        <option value="" hidden selected>Select a time</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>Enter Number of Bus(es)</label>
                                    <input type="number" name="num_bus" class="form-control" min="1" max="10" required>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-3" type="submit" name="create">Submit</button>
                        </form>
                    </div>

                    <!-- Right Side: Calendar -->
                    <div class="col-md-4">
                        <div class="border p-3">
                            <div class="calendar">
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
                <table class="table table-bordered text-center">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
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
                                            COALESCE(COUNT(st.student_id), 0) AS num_visitors 
                                    FROM scheduled_tbl s
                                    LEFT JOIN student_tbl st ON s.scheduled_id = st.scheduled_id
                                    GROUP BY s.scheduled_id
                                    ORDER BY s.scheduled_id ASC";

                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()){
                                $formattedDate = date("F j, Y", strtotime($row['date']));
                                $formattedTime = date("h:i A", strtotime($row['time']));
                        ?>

                        <tr>
                            <td><?php echo $row['scheduled_id'] ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $formattedDate ?></td>
                            <td><?php echo $formattedTime ?></td>
                            <td><?php echo $row['num_bus'] ?></td>
                            <td><?php echo $row['num_visitors'] ?? 0 ?></td>
                            <td><span class="badge bg-warning text-dark"><?php echo $row['status'] ?></span></td>
                            <td>
                                <button class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i></button>
                                <button class="btn btn-warning btn-sm"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-x"></i></button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        // Disable specific dates based on the database data
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('date').setAttribute('min', '<?php echo $today; ?>');
            const blockedDates = <?php echo json_encode($blocked_dates); ?>;
            
            const dateInput = document.getElementById('date');
            
            function disableBlockedDates() {
                const blockedSet = new Set(blockedDates); // Create a Set for faster lookup
                const date = dateInput.value; // Get the currently selected date
                
                // Check if the selected date is blocked
                if (blockedSet.has(date)) {
                    alert("This date is already booked. Please choose another date.");
                    dateInput.value = ''; // Reset the date input if blocked
                }
            }

            // Disable specific blocked dates visually and in the input
            dateInput.addEventListener('input', disableBlockedDates);
            
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

        let currentDate = new Date();

        const updateCalendar = () => {
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth();

            const firstDay = new Date(currentYear, currentMonth, 0);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const totalDays = lastDay.getDate();
            const firstDayIndex = firstDay.getDay();
            const lastDayIndex = lastDay.getDay();

            const monthYearString = currentDate.toLocaleString('default', {month: 'long', year: 'numeric'});
            monthYearElement.textContent = monthYearString;

            let datesHTML = '';

            for(let i = firstDayIndex; i > 0; i--){
                const prevDate = new Date(currentYear, currentMonth, 0 - i + 1);
                datesHTML += `<div class="date inactive">${prevDate.getDate()}</div>`;
            }

            for(let i = 1; i <= totalDays; i++){
                const date = new Date(currentYear, currentMonth, i);
                const activeClass = date.toDateString() === new Date().toDateString() ? 'active' : '';
                datesHTML += `<div class="date ${activeClass}">${i}</div>`;
            }

            for(let i = 1; i <= 7 - lastDayIndex; i++){
                const nextDate = new Date(currentYear, currentMonth + 1, i);
                datesHTML += `<div class="date inactive">${nextDate.getDate()}</div>`;
            }

            datesElement.innerHTML = datesHTML;
        }   

        prevBtn.addEventListener('click', () => {
            const today = new Date();
            if (
                currentDate.getFullYear() > today.getFullYear() ||
                (currentDate.getFullYear() === today.getFullYear() && currentDate.getMonth() > today.getMonth())
            ) {
                currentDate.setMonth(currentDate.getMonth() - 1);
                updateCalendar();
            }
            togglePrevButton();
        });
        nextBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth()+1);
            updateCalendar();
            togglePrevButton();
        });
        const togglePrevButton = () => {
            const today = new Date();
            if (
                currentDate.getFullYear() === today.getFullYear() &&
                currentDate.getMonth() === today.getMonth()
            ) {
                prevBtn.disabled = true; // Disable if it's the current month
            } else {
                prevBtn.disabled = false; // Enable if it's a future month
            }
        };

        togglePrevButton();
        updateCalendar();
    </script>
</body>

</html>