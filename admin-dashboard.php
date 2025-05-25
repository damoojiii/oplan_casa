<?php
    include "session.php";
    include("connection.php");
    include "loader.php";

    $dateToday = date("l, F j, Y");

    //Todays Visitor
    $todaysVisitor = "SELECT COUNT(*) AS todays_visitor FROM visitors WHERE DATE(time) = CURDATE()";
    $result = $conn->query($todaysVisitor);
    $row = $result->fetch_assoc();

    $todays_count = $row['todays_visitor'];

    //Overall Visitor
    $overallVisitor = "SELECT COUNT(*) AS overall_visitor FROM visitors WHERE DATE(time)";
    $result = $conn->query($overallVisitor);
    $row = $result->fetch_assoc();

    $overall_count = $row['overall_visitor'];

    //Fetch total student
    $overallStudent = "SELECT COUNT(*) AS overall_student FROM student_tbl";
    $result = $conn->query($overallStudent);
    $row = $result->fetch_assoc();

    $overall_student = $row['overall_student'];

    //Sum of overall visitors and students nganiii
    $total = $overall_count + $overall_student;

    //Overall Scheduled Trips
    $scheduledTrips = "SELECT COUNT(*) AS overall_trips FROM scheduled_tbl";
    $result = $conn->query($scheduledTrips);
    $row = $result->fetch_assoc();

    $total_trips = $row['overall_trips'];
    
    $sql2 = "
        SELECT name, date, status
        FROM scheduled_tbl
        WHERE date >= CURDATE()
        ORDER BY date ASC
        LIMIT 5
    ";

    $result = $conn->query($sql2);

    $schedules = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $schedules[] = $row;
        }
    }

    $sql3 = "SELECT reason, COUNT(visitor_id) as total FROM visitors GROUP BY reason ORDER BY total DESC";
    $result = $conn->query($sql3);

    $reasons = [];
    $totals = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reasons[] = $row['reason']; 
            $totals[] = $row['total'];  
        }
    }

    $sql4 = "SELECT DATE(date) as scheduled_date, status FROM scheduled_tbl";
    $result = $conn->query($sql4);

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
    .upcom::before {
        content: '';
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #1b311b; /* dark green */
        border-radius: 50%;
        margin-right: 10px;
        vertical-align: middle !important;
    }
    .card-header.header-title{
        color: white;
        background-color: #273E26;
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
            <p>Today is <?php echo $dateToday ?></p>
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- Statistic Cards -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3><?php echo $todays_count ?></h3>
                                    <p>Today's Visitor</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3><?php echo $total_trips ?></h3>
                                    <p>Scheduled Trips</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3><?php echo $total ?></h3>
                                    <p>Overall Visitors</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Inner Column (Upcoming Schedules) -->
                        <div class="col-md-5">
                            <div class="card mt-3">
                                <div class="card-header header-title">Upcoming Schedules</div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <?php if (count($schedules) > 0): ?>
                                            <?php foreach ($schedules as $row): ?>
                                                <?php
                                                    $dateFormatted = date('M d, Y', strtotime($row['date']));
                                                    $status = strtolower($row['status']);

                                                    // Badge color logic
                                                    $statusClass = 'text-secondary';
                                                    if ($status === 'ongoing') $statusClass = 'text-success';
                                                    elseif ($status === 'upcoming') $statusClass = 'text-warning';
                                                    elseif ($status === 'cancelled') $statusClass = 'text-danger';
                                                ?>
                                                <li class="mb-3 d-flex align-items-center justify-content-evenly upcom">
                                                    <div class="">
                                                        <div class="fw-semibold"><?= htmlspecialchars($row['name']) ?></div>
                                                        <small class="text-muted"><?= $dateFormatted ?></small>
                                                    </div>
                                                    <div class="mt-1">
                                                        <span class="<?= $statusClass ?> fw-semibold"><?= ucfirst($status) ?></span>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>No upcoming schedules.</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Right Inner Column (Charts) -->
                        <div class="col-md-7">
                            <div class="card mt-3">
                                <div class="card-header header-title">Visitor Chart</div>
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                                        <select name="year" id="yearSelect" class="form-select w-50 me-2">
                                            <?php
                                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : '';
                                            $query = "SELECT DISTINCT YEAR(time) AS year FROM visitors ORDER BY year DESC";
                                            $result = mysqli_query($conn, $query);

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $year = $row['year'];
                                                $selected = ($selectedYear == $year) ? 'selected' : '';
                                                echo "<option value='$year' $selected>$year</option>";
                                            }
                                            ?>
                                        </select>

                                        <button type="button" class="btn btn-primary w-50" id="printReportBtn">
                                            <i class="fa-solid fa-print"></i> Print Report
                                        </button>
                                    </div>
                                <div class="card-body">
                                    <canvas id="visitorChart"></canvas>
                                </div>
                            </div>
                            <div class="card mt-3">
                                <div class="card-header header-title">Appointed Field Trips</div>
                                <div class="mx-2 pt-3 d-flex justify-content-end">
                                    <label for="apptYearSelect" class="me-1 py-2">Filter by Year:</label>
                                    <select name="apptYear" id="apptYearSelect" class="form-select d-inline-block w-auto">
                                        <?php
                                        $currentYear = date('Y');
                                        for ($y = $currentYear; $y >= $currentYear - 10; $y--) {
                                            echo "<option value='$y'>$y</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
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
                            <div class="legend mt-2 d-flex align-items-center" style="gap: 20px; flex-wrap: wrap;">
                                <div class="d-flex align-items-center">
                                    <span style="width: 15px; height: 15px; background-color: #273E26; border-radius: 50%; margin-right: 5px;"></span>
                                    <span style="font-size: 12px;">Today</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span style="width: 15px; height: 15px; background-color: #f0ad4e; border-radius: 50%; margin-right: 5px;"></span>
                                    <span style="font-size: 12px;">Upcoming Trip</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span style="width: 15px; height: 15px; background-color: #5cb85c; border-radius: 50%; margin-right: 5px;"></span>
                                    <span style="font-size: 12px;">Completed Trip</span>
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
        </div>
    </div>
    

    <script src="vendor/chart.js/Chart.min.js"></script>
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
    </script>
    <script>
        let chart;

        document.getElementById('yearSelect').addEventListener('change', function () {
            const selectedYear = this.value;

            fetch('getVisitorChart.php?year=' + selectedYear)
                .then(response => response.json())
                .then(data => {
                    updateChart(data.months, data.counts);
                });
        });

        function createChart(months, counts) {
            const ctx = document.getElementById('visitorChart').getContext('2d');
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Visitors per Month',
                        data: counts,
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
        }

        function updateChart(months, counts) {
            chart.data.labels = months;
            chart.data.datasets[0].data = counts;
            chart.update();
        }

        // Initialize chart on page load
        window.addEventListener('DOMContentLoaded', () => {
            fetch('getVisitorChart.php?year=' + document.getElementById('yearSelect').value)
                .then(response => response.json())
                .then(data => {
                    createChart(data.months, data.counts);
                });
        });

        let appointmentChart;

        document.getElementById('apptYearSelect').addEventListener('change', function () {
            const selectedYear = this.value;
            fetch('getAppointmentData.php?year=' + selectedYear)
                .then(response => response.json())
                .then(data => {
                    updateAppointmentChart(data.months, data.counts);
                });
        });

        function createAppointmentChart(months, counts) {
            const ctx = document.getElementById('fieldTripsChart').getContext('2d');
            appointmentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Number of Appointments',
                        data: counts,
                        backgroundColor: 'orange'
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Appointments'
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
        }

        function updateAppointmentChart(months, counts) {
            appointmentChart.data.labels = months;
            appointmentChart.data.datasets[0].data = counts;
            appointmentChart.update();
        }

        // Load initial chart on page load
        window.addEventListener('DOMContentLoaded', () => {
            const year = document.getElementById('apptYearSelect').value;
            fetch('getAppointmentData.php?year=' + year)
                .then(response => response.json())
                .then(data => {
                    createAppointmentChart(data.months, data.counts);
                });
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

        document.getElementById('printReportBtn').addEventListener('click', function () {
            const canvas = document.getElementById('visitorChart');
            const dataUrl = canvas.toDataURL();

            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print Visitor Chart</title>
                    <style>
                        @media print {
                            body, html {
                                margin: 0;
                                padding: 0;
                                width: 100%;
                                height: 100%;
                            }
                            img {
                                width: 100vw;
                                height: 100vh;
                                object-fit: contain;
                            }
                        }

                        body, html {
                            margin: 0;
                            padding: 0;
                            width: 100%;
                            height: 100%;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            background: white;
                        }

                        img {
                            max-width: 100%;
                            max-height: 100%;
                        }
                    </style>
                </head>
                <body>
                    <img src="${dataUrl}" alt="Visitor Chart"/>
                    <script>
                        window.onload = function () {
                            window.print();
                            window.onafterprint = function () { window.close(); };
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        });
    </script>
</body>

</html>