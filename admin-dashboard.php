<?php
    include "session.php";
    include("connection.php");
    include "loader.php";

    $sql1 = "SELECT DATE_FORMAT(time, '%M %Y') as month, COUNT(visitor_id) as count 
            FROM visitors 
            GROUP BY month 
            ORDER BY month ASC";

    $result = $conn->query($sql1);

    $months = [];
    $counts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $months[] = $row['month']; // Format: 2025-04
            $counts[] = $row['count'];
        }
    }

    $sql2 = "SELECT name, num_bus FROM scheduled_tbl ORDER BY date ASC";
    $result = $conn->query($sql2);

    $tripNames = [];
    $busCounts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tripNames[] = $row['name'];      // trip names
            $busCounts[] = $row['num_bus'];    // number of buses for each trip
        }
    }

    $sql3 = "SELECT reason, COUNT(visitor_id) as total FROM visitors GROUP BY reason ORDER BY total DESC";
    $result = $conn->query($sql3);

    $reasons = [];
    $totals = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reasons[] = $row['reason'];   // 'Tour', 'Business', 'Education', etc.
            $totals[] = $row['total'];      // how many visitors for each reason
        }
    }

    $sql4 = "SELECT DATE(date) as scheduled_date, status FROM scheduled_tbl";
    $result = $conn->query($sql4);

    $scheduledDates = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $scheduledDates[] = [
                'date' => $row['scheduled_date'], // e.g., '2025-05-15'
                'status' => strtolower($row['status']) // e.g., 'upcoming' or 'completed'
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="img/rosariologo.png">

    <style>
    <?php include 'sidebarcss.php'; ?>
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
        width: 380px;
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
        font-weight: 600;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        transition: 0.2s;
    }

    .date:hover, .date.active{
        background: #273E26;
        color: #fff;
    }
    .date.upcoming {
        background-color: #f0ad4e; /* orange for upcoming */
        color: white;
        border-radius: 50%;
    }
    .date.completed {
        background-color: #5cb85c; /* light green for completed */
        color: white;
        border-radius: 50%;
    }
    .date.inactive {
        opacity: 0.4;
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
                <a href="admin-dashboard.php" class="nav-link active">
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
        <div class="container-fluid">
            <h2 class="mt-3 header-title">Hello, Admin!</h2>
            <p>Lorem ipsum dolor</p>
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Statistic Cards -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3>0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3>0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3>0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Inner Column (Upcoming Schedules) -->
                        <div class="col-md-4">
                            <div class="card mt-3">
                                <div class="card-header header-title">Upcoming Schedules</div>
                                <div class="card-body">
                                    <ul>
                                        <li>School Name High School - Feb 23, 2025 <span class="text-success">Ongoing</span></li>
                                        <li>School Name High School - Feb 26, 2025 <span class="text-primary">Upcoming</span></li>
                                        <li>School Name High School - Feb 28, 2025 <span class="text-danger">Cancelled</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Right Inner Column (Charts) -->
                        <div class="col-md-8">
                            <div class="card mt-3">
                                <div class="card-header header-title">Visitor Chart</div>
                                <div class="card-body">
                                    <canvas id="visitorChart"></canvas>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header header-title">Appointed Field Trips</div>
                                <div class="card-body">
                                    <canvas id="fieldTripsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Reason for Visit Pie Chart -->
                    <div class="card">
                        <div class="card-header header-title">Reason for Visit</div>
                        <div class="card-body">
                            <canvas id="reasonChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Calendar -->
                    <div class="calendar-container">
                        <div class="calendar">
                            <div class="legend mt-2" style="margin-top: 10px;">
                                <span style="display: inline-block; width: 15px; height: 15px; background-color: #273E26; border-radius: 50%; margin-right: 5px;"></span> Today
                                <span style="display: inline-block; width: 15px; height: 15px; background-color: #f0ad4e; border-radius: 50%; margin-left: 15px; margin-right: 5px;"></span> Upcoming Trip
                                <span style="display: inline-block; width: 15px; height: 15px; background-color: #5cb85c; border-radius: 50%; margin-left: 15px; margin-right: 5px;"></span> Completed Trip
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
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        const monthYearElement = document.getElementById('monthYear');
        const datesElement = document.getElementById('dates');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const scheduledDates = <?php echo json_encode($scheduledDates); ?>;

        let currentDate = new Date();

        const updateCalendar = () => {
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth();

            const firstDay = new Date(currentYear, currentMonth, 1); // ❗ Fix: should be day 1, not 0
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const totalDays = lastDay.getDate();
            const firstDayIndex = firstDay.getDay(); // Monday = 1, Sunday = 0
            const lastDayIndex = lastDay.getDay();

            const monthYearString = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
            monthYearElement.textContent = monthYearString;

            let datesHTML = '';

            // Fill previous month inactive dates
            let prevMonthDays = (firstDayIndex + 6) % 7; // adjust for Monday as start
            const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
            for (let i = prevMonthDays; i > 0; i--) {
                datesHTML += `<div class="date inactive">${prevMonthLastDay - i + 1}</div>`;
            }

            // Fill current month days
            for (let i = 1; i <= totalDays; i++) {
                const date = new Date(currentYear, currentMonth, i);
                const dateString = date.toISOString().split('T')[0]; // 'YYYY-MM-DD'

                let extraClass = '';
                const todayString = new Date().toISOString().split('T')[0];

                if (dateString === todayString) {
                    extraClass = 'active'; // today highlight (blue)
                } else {
                    const scheduled = scheduledDates.find(s => s.date === dateString);
                    if (scheduled) {
                        if (scheduled.status === 'upcoming') {
                            extraClass = 'upcoming'; // orange
                        } else if (scheduled.status === 'completed') {
                            extraClass = 'completed'; // light green
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
    <script>
        const ctx1 = document.getElementById('visitorChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>, // X-axis = months
                datasets: [{
                    label: 'Visitors per Month',
                    data: <?php echo json_encode($counts); ?>, // Y-axis = visitor count
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Visitors'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('fieldTripsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tripNames); ?>, // X-axis = trip names
                datasets: [{
                    label: 'Number of Buses',
                    data: <?php echo json_encode($busCounts); ?>, // Y-axis = number of buses
                    backgroundColor: 'orange'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Buses'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Field Trip'
                        }
                    }
                }
            }
        });
        
        const ctx3 = document.getElementById('reasonChart').getContext('2d');
        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($reasons); ?>,
                datasets: [{
                    data: <?php echo json_encode($totals); ?>,
                    backgroundColor: [
                        '#d9534f',
                        '#5bc0de',
                        '#5cb85c',
                        '#f0ad4e',
                        '#0275d8',
                        '#292b2c'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>

</html>