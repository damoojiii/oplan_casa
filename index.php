<?php
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullName = $_POST['fullName'];
        $gender = $_POST['gender'];
        $visitReason = $_POST['visitReason'];
        $time = date("Y-m-d H:i:s");

        // Insert into database
        $sql = "INSERT INTO visitors (fullName, gender, reason, time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullName, $gender, $visitReason, $time);

        if ($stmt->execute()) {
            echo "<script>alert('Visitor added successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitor's Log</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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

    
    <div class="container mt-5">
        <div class="card p-4">
            <h4 class="mb-3">Visitor’s Log</h4>
            
            <form id="visitorForm" method="POST">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Enter your Full Name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="visitReason" class="form-label">Purpose for Visit</label>
                        <select id="visitReason" name="visitReason" class="form-select" required>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Submit</button>
                    </div>
                </div>
            </form>

            
            <table class="table table-bordered text-center">
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
                    $sql = "SELECT * FROM visitors ORDER BY visitor_id DESC LIMIT 5"; 
                    $result = $conn->query($sql);

                    $rowCount = 0;

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $formattedTime = date("h:i A", strtotime($row['time']));
                            echo "<tr>
                                <td>{$row['visitor_id']}</td>
                                <td>{$row['fullName']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['reason']}</td>
                                <td>{$formattedTime}</td>
                            </tr>";
                            $rowCount++; 
                        }
                    }

                    for ($i = $rowCount; $i < 5; $i++) {
                        echo "<tr class='empty-row'>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
