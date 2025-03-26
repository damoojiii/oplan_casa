<?php
// Database connection
include "connection.php";

// Fetch visitor's name
$visitor_id = $_GET['id'] ?? 1; // Default to 1 if no ID is provided
$sql = "SELECT fullName FROM visitors WHERE visitor_id = $visitor_id";
$result = $conn->query($sql);
$name = "Visitor";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['fullName'];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Visitation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'March Rough';
            src: url('fonts/March_Rough/March_Rough.ttf') format('truetype');
        }
        .img-fluid{
            height: 100vh !important;
        }
        .certificate-container {
            position: relative;
            display: inline-block;
        }

        .visitor-name {
            position: absolute;
            top: 53%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: "March Rough", Arial, sans-serif;
            font-size: 70px;
            font-weight: normal;
            max-width: 80%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .certificate-container {
                width: 100%;
                max-width: 1000px;
                height: auto;
                page-break-inside: avoid;
            }

            .certificate-container img {
                width: 100%;
                height: auto;
            }
        }
    </style>
    <script>
        function printCertificate() {
            var printContents = document.getElementById('certificate').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body class="text-center">
    <div class="container text-center">
        <div class="certificate-container" id="certificate">
            <img src="img/cert.png" alt="Certificate" class="img-fluid">
            <div class="visitor-name"><?php echo htmlspecialchars(ucwords($name)); ?></div>
        </div>
        <button class="btn btn-primary mt-3" onclick="printCertificate()">Print Certificate</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
