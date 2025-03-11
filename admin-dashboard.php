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

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    *,
    p {
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
        display: flex;
        /* Smooth transition for header */
    }

    #header {
        transition: margin-left 0.3s ease, width 0.3s ease;
    }

    #hamburger {
        border: none;
        background: none;
        cursor: pointer;
        margin-left: 15px;
        /* Space from the left edge */
        display: none;
        /* Initially hide the hamburger button */
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

    #sidebar .drop {
        height: 50px;
    }

    .caret-icon .fa-caret-down {
        display: inline-block;
        font-size: 20px;
    }

    .navcircle {
        font-size: 7px;
        text-align: justify;
    }

    .main-menu {
        font-family: 'Karla';
        margin-bottom: 10px;
    }

    #sidebar .nav-link:hover,
    #sidebar .nav-link.active {
        background-color: #fff !important;
        color: #000 !important;
    }

    .dropdown-item {
        color: #fff !important;
        margin-bottom: 10px;
    }

    .dropdown-item:hover {
        background-color: #fff !important;
        color: #000 !important;
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
                <a href="admin-dashboard.php" class="nav-link active text-white"><i class="fa-brands fa-flipboard"></i>
                    Dashboard</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white target"><i class="fa-solid fa-user-group"></i> Visitor's
                    List</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white chat"><i class="fa-solid fa-bus"></i> Scheduled Field
                    Trips</a>
            </li>
            <li>
                <a href="#.php" class="nav-link text-white"><i class="fa-solid fa-clock-rotate-left"></i> History</a>
            </li>
            <li>
                <a href="settings.php" class="nav-link text-white"><i class="fa-solid fa-gear"></i> Settings</a>
            </li>
        </ul>
        <hr>
        <div class="logout">
            <a href="logout.php" class="nav-link text-white"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log
                out</a>
        </div>
    </div>
    <!-- Add this right after the <body> tag -->
    <div class="loader-wrapper">
        <div class="loader">
            <img src="img/rosariologo.png" alt="Loading..." class="loader-logo">
        </div>
    </div>

    <!-- Add this CSS inside the <style> section -->
    <style>
    .loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.95);
        z-index: 2000;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: opacity 0.5s ease;
    }

    .loader-logo {
        width: 150px;
        height: 150px;
        animation:
            spin 2s linear infinite,
            bounce 1.5s ease-in-out infinite,
            pulse 1.5s infinite ease-in-out;
        transform-origin: center center;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg) scale(1);
        }

        50% {
            transform: rotate(180deg) scale(1.2);
        }

        100% {
            transform: rotate(360deg) scale(1);
        }
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes pulse {
        0% {
            opacity: 0.8;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0.8;
        }
    }

    .loader-wrapper.hidden {
        opacity: 0;
        pointer-events: none;
    }
    </style>

    <!-- Add this JavaScript at the end of your existing script section -->
    <script>
    window.addEventListener('load', function() {
        const loaderWrapper = document.querySelector('.loader-wrapper');
        // Add slight delay for smooth transition
        setTimeout(() => {
            loaderWrapper.classList.add('hidden');
        }, 500);

        // Remove loader after animation
        setTimeout(() => {
            loaderWrapper.style.display = 'none';
        }, 1000);
    });
    </script>

    <div id="main-content" class="container mt-1">

    </div>


    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
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