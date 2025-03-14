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

        #table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
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

    <div id="main-content" class="container mt-1 d-flex justify-content-center">
        <div id="table-container" class="w-100">
            <table id="visitorTable" class="table table-bordered text-center">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Visitor No.</th>
                        <th>Visitor Name</th>
                        <th>City</th>
                        <th>Gender</th>
                        <th>Purpose for Visit</th>
                        <th>Time</th>
                        <th>Action</th> <!-- Added Action Column -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT visitor_id, fullName, city, gender, reason, time FROM visitors ORDER BY visitor_id ASC";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $formattedTime = date("h:i A", strtotime($row['time']));
                            echo "<tr>
                                <td>{$row['visitor_id']}</td>
                                <td>" . ucwords(strtolower($row['fullName'])) . "</td>
                                <td>{$row['city']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['reason']}</td>
                                <td>{$formattedTime}</td>
                                <td>
                                    <a href='view_visitor.php?id={$row['visitor_id']}' class='btn btn-info btn-sm'>View</a>
                                    <a href='edit_visitor.php?id={$row['visitor_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_visitor.php?id={$row['visitor_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#visitorTable').DataTable({
                "paging": true,
                "pageLength": 10, // Number of rows per page
                "lengthChange": false, // Hide "Show X entries"
                "info": false, // Hide "Showing X to Y of Z entries"
                "searching": true, // Enable search filter
                "ordering": true // Enable sorting
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
