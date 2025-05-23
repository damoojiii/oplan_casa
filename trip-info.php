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

        <div class="container mt-4">
            <h2 class="text-center">Scheduled Trips</h2>
            <ol class="schedule-list">
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

                    while ($row = $result->fetch_assoc()) {
                        $formattedDate = date("F j, Y", strtotime($row['date']));
                        $formattedTime = date("h:i A", strtotime($row['time']));
                        $statusClass = strtolower($row['status']); // Convert status to lowercase for class name
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
        </div>

        
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
        
    </script>
</body>

</html>