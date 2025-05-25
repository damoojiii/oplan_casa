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
    .schedule-list {
        max-width: 800px;
        margin: auto;
    }
    .schedule-item {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
        border-left: 6px solid limegreen;
        transition: all 0.3s ease;
    }
    .schedule-item:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    .schedule-header {
        font-weight: bold;
        font-size: 18px;
    }
    .schedule-details {
        font-size: 14px;
        color: #555;
    }
    .status-badge {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 5px;
    }
    .upcoming { background-color: #ffc107; color: #000; }
    .ongoing { background-color: #28a745; color: #fff; }
    .completed { background-color: #6c757d; color: #fff; }

    .active>.page-link, .page-link.active {
        background-color: #28a745;
        border-color: #28a745;
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
                <a class="nav-link" id="tab2" data-bs-toggle="tab" href="add-visitor.php">Add Visitor</a>
            </li>
            <li class="nav-item tabs">
                <a class="nav-link active" id="tab3" data-bs-toggle="tab" href="trip-info.php">Trip Info</a>
            </li>
        </ul>

        <?php
        $limit = 5; // Number of records per page
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Count total upcoming schedules
        $count_query = "SELECT COUNT(*) AS total FROM scheduled_tbl WHERE status = 'Upcoming'";
        $count_result = $conn->query($count_query);
        $total_rows = $count_result->fetch_assoc()['total'];
        $total_pages = ceil($total_rows / $limit);

        // Fetch paginated records
        $sql = "SELECT s.*, 
                (
                    SELECT COUNT(*) FROM student_tbl st WHERE st.scheduled_id = s.scheduled_id
                ) +
                (
                    SELECT COUNT(*) FROM supervisor_tbl sv WHERE sv.scheduled_id = s.scheduled_id
                ) AS num_visitors
                FROM scheduled_tbl s
                WHERE s.status = 'Upcoming'
                ORDER BY s.scheduled_id ASC
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <div class="container mt-4">
            <h2 class="text-center">Scheduled Trips</h2>
            <ol class="schedule-list">
                <?php while ($row = $result->fetch_assoc()) {
                    $formattedDate = date("F j, Y", strtotime($row['date']));
                    $formattedTime = date("h:i A", strtotime($row['time']));
                    $statusClass = strtolower($row['status']);
                ?>
                <li>
                    <a href="view-students.php?scheduled_id=<?php echo $row['scheduled_id']; ?>" class="text-decoration-none text-dark">
                        <div class="schedule-item">
                            <div class="schedule-header"><?php echo $row['name']; ?></div>
                            <div class="schedule-details">
                                <strong>Date:</strong> <?php echo $formattedDate; ?> |
                                <strong>Time:</strong> <?php echo $formattedTime; ?> |
                                <strong>Visitors:</strong> <?php echo $row['num_visitors']; ?> |
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($row['status']); ?></span>
                            </div>
                        </div>
                    </a>
                </li>
                <?php } ?>
            </ol>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">&laquo; Prev</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next &raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
    

    <script src="vendor/fontawesome-free/js/all.min.js"></script>
    <script src="vendor/fontawesome-free/js/fontawesome.min.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        
    </script>
</body>

</html>