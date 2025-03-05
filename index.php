<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if required fields exist before accessing them
        $fullName = isset($_POST['fullName']) ? $_POST['fullName'] : null;
        $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
        $visitReason = isset($_POST['reason']) ? $_POST['reason'] : null;
        $time = date("Y-m-d H:i:s");

        // Validate inputs to prevent NULL database values
        if (!$fullName || !$gender || !$visitReason) {
            $_SESSION['message'] = "Error: Missing required fields!";
            $_SESSION['message_type'] = "danger";
            header("Location: index.php");
            exit();
        }

        // Handle photo upload
        if (!empty($_POST['photo'])) {
            $photoData = $_POST['photo'];
            $photoData = str_replace("data:image/png;base64,", "", $photoData);
            $photoData = base64_decode($photoData);
            $fileName = "uploads/" . uniqid() . ".png";
            file_put_contents($fileName, $photoData);
        } else {
            $fileName = null; // No photo uploaded
        }

        // Insert into database
        $sql = "INSERT INTO visitors (fullName, gender, reason, time, photo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fullName, $gender, $visitReason, $time, $fileName);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Visitor added successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
        }

        $stmt->close();
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitor's Log</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background: url('img/casabg.jpg') no-repeat center center/cover;
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #5D9C5933;
            z-index: 1;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            color: white;
            padding: 15px 20px;
            padding-left: 90px;
            padding-right: 90px;
            display: flex;
            align-items: center;
            z-index: 10;
        }

        .logo {
            height: 50px;
            width: 50px;  
            border-radius: 50%;
            object-fit: cover;
        }

        .header h4 {
            margin: 0;
        }

        .card {
            position: relative;
            background: white;
            border-radius: 10px;
            padding: 20px;
            z-index: 10;
        }

        .table {
            margin-top: 20px;
        }

        .empty-row td {
            height: 41px;
        }

        #camera-container, #captured-photo {
            display: none; /* Hidden initially */
            margin-top: 10px;
        }
        video, img {
            width: 100%;
            max-width: 300px;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        .dataTables_paginate {
            text-align: right !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 2px;
            border: 1px solid #28a745;  /* Green border */
            border-radius: 5px;
            background-color: white;
            color: #28a745;
            transition: 0.3s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #28a745; /* Green on hover */
            color: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #28a745; /* Green for active page */
            color: white;
        }

    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="header d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center">
            <img src="img/rosariologo.png" alt="Municipality Logo" class="logo">
            <h4 class="mb-0 ms-3 text-white">Tourism Office - Municipality of Rosario</h4>
        </div>

        <button class="btn btn-success">Login</button>
    </div>

    <?php
        if (isset($_SESSION['message'])) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Success!',
                            text: '" . $_SESSION['message'] . "',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
            unset($_SESSION['message']); 
        }
    ?>
    <div class="container mt-5">

        <div class="card p-4">
            <h4 class="mb-3">Visitor’s Log</h4>
            
            <form id="visitorForm" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Enter your Full Name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="visitReason" class="form-label">Purpose for Visit</label>
                        <select id="visitReason" name="reason" class="form-select" required>
                            <option value="" disabled selected>Select Reason</option>
                            <option value="Ocular Visit">Ocular Visit</option>
                            <option value="Business">Business</option>
                            <option value="Tourism">Tourism</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-select" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <!-- Hidden input to store captured photo -->
                    <input type="hidden" id="photoData" name="photo">

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" id="submitBtn" class="btn btn-success w-100">Submit</button>
                    </div>
                </div>
            </form>

            
            <table id="visitorTable" class="table table-bordered text-center">
                <thead class="bg-success text-white">
                    <tr>
                        <th>Visitor No.</th>
                        <th>Visitor Name</th>
                        <th>Gender</th>
                        <th>Purpose for Visit</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="visitorTable">
                    <?php
                        $currentDate = date("Y-m-d");

                        $sql = "SELECT * FROM visitors WHERE DATE(time) = ? ORDER BY visitor_id DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $currentDate);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $formattedTime = date("h:i A", strtotime($row['time']));
                            echo "<tr>
                                <td>{$row['visitor_id']}</td>
                                <td>{$row['fullName']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['reason']}</td>
                                <td>{$formattedTime}</td>
                            </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById("visitorForm").addEventListener("submit", function (event) {
            event.preventDefault();

            localStorage.setItem("fullName", document.getElementById("fullName").value);
            localStorage.setItem("reason", document.getElementById("visitReason").value);
            localStorage.setItem("gender", document.getElementById("gender").value);

            window.location.href = "camera.php";
        });

        window.onload = function () {
            let photoData = localStorage.getItem("photo");
            
            if (photoData) {
                document.getElementById("photoData").value = photoData;

                document.getElementById("fullName").value = localStorage.getItem("fullName");
                document.getElementById("visitReason").value = localStorage.getItem("reason");
                document.getElementById("gender").value = localStorage.getItem("gender");

                localStorage.removeItem("photo");
                localStorage.removeItem("fullName");
                localStorage.removeItem("reason");
                localStorage.removeItem("gender");

                document.getElementById("visitorForm").submit();
            }
        };

        $(document).ready(function () {
            $('#visitorTable').DataTable({
                "paging": true,
                "searching": true,
                "lengthChange": false,
                "pageLength": 5,
                "ordering": false,
                "info": false,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    },
                    "search": "🔍 Search:"
                },
                "dom": '<"top"f>rt<"bottom"p><"clear">'
            });
        });
    </script>

</body>
</html>
