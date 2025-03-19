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
    .date.inactive{
        color: #d2d2d2;
    }
    .date.inactive:hover{
        color: #fff;
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
                <img src="logo.png" alt="Logo" class="img-fluid">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script>
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
    <script>
        const ctx1 = document.getElementById('visitorChart').getContext('2d');
        new Chart(ctx1, { type: 'line', data: { labels: [0,1,2,3,4], datasets: [{ data: [2000, 3000, 1000, 4000, 2500], borderColor: 'blue' }] }});
        
        const ctx2 = document.getElementById('reasonChart').getContext('2d');
        new Chart(ctx2, { type: 'doughnut', data: { labels: ['A', 'B', 'C'], datasets: [{ data: [418, 994, 547], backgroundColor: ['#d9534f', '#5bc0de', '#5cb85c'] }] }});
        
        const ctx3 = document.getElementById('fieldTripsChart').getContext('2d');
        new Chart(ctx3, { type: 'bar', data: { labels: [0,1,2,3,4,5,6,7,8,9], datasets: [{ data: [1500, 3200, 2000, 1800, 3000, 5000], backgroundColor: 'orange' }] }});
    </script>
</body>

</html>