<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if required fields exist before accessing them
        $fullName = isset($_POST['fullName']) ? $_POST['fullName'] : null;
        $city = isset($_POST['city']) ? $_POST['city'] : null;
        $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
        $visitReason = isset($_POST['reason']) ? $_POST['reason'] : null;
        $time = date("Y-m-d H:i:s");

        // Validate inputs to prevent NULL database values
        if (!$fullName || !$city || !$gender || !$visitReason) {
            $_SESSION['message'] = "Error: Missing required fields!";
            $_SESSION['message_type'] = "Error";
            $_SESSION['icon'] = "error";
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
        $sql = "INSERT INTO visitors (fullName, city, gender, reason, time, photo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $fullName, $city, $gender, $visitReason, $time, $fileName);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Visitor added successfully!";
            $_SESSION['message_type'] = "Success";
            $_SESSION['icon'] = "success";
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
            $_SESSION['message_type'] = "Error";
            $_SESSION['icon'] = "error";
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
    <link rel="icon" href="img/rosariologo.png">

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    @font-face {
        font-family: 'Inter';
        src: url('fonts/Inter/Inter-VariableFont_opsz\,wght.ttf') format('truetype');
        font-weight: 100 900;
        font-stretch: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Source';
        src: url('fonts/Source_Serif_4/static/SourceSerif4-SemiBold.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;

    }

    body {
        font-family: 'Inter', Arial;
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
        padding-inline: 70px !important;
        padding-left: 90px;
        padding-right: 90px;
        display: flex;
        align-items: center;
        z-index: 10;
    }

    .login {
        padding-inline: 15px;
    }

    .logo {
        height: 50px;
        width: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .header h4 {
        margin: 0;
        font-family: 'Source';
    }

    .visitor {
        font-family: 'Inter', Arial;
        font-weight: 400;
        font-size: 30px;
    }

    .card {
        position: relative;
        background: white;
        border-radius: 10px;
        padding: 20px;
        z-index: 10;
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

    #camera-container,
    #captured-photo {
        display: none;
        margin-top: 10px;
    }

    video,
    img {
        width: 100%;
        max-width: 300px;
        height: 300px;
        object-fit: cover;
        border: 2px solid #ddd;
        border-radius: 5px;
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

    .btn-success {
        --bs-btn-bg: #5D9C59 !important;
        --bs-btn-border-color: #5D9C59 !important;
        --bs-btn-hover-bg: #5D9C59 !important;
    }

    .input-label {
        font-size: 15px;
    }

    #captureBtn {
        display: block;
        margin: 0 auto;
    }
    </style>
</head>

<body>

    <div class="overlay"></div>
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

    <div class="header d-flex align-items-center justify-content-between p-3">
        <div class="d-flex align-items-center">
            <img src="img/rosariologo.png" alt="Municipality Logo" class="logo">
            <h4 class="mb-0 ms-3 text-white">Tourism Office - Municipality of Rosario</h4>
        </div>

        <a href="login.php"><button class="btn btn-success login">Login</button></a>
    </div>


    <?php
        if (isset($_SESSION['message'])) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: '". $_SESSION['message_type'] ."',
                            text: '" . $_SESSION['message'] . "',
                            icon: '". $_SESSION['icon'] ."',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
            unset($_SESSION['message']); 
        }
    ?>
    <div class="container mt-5">

        <div class="card p-4">
            <h4 class="mb-3 visitor"><strong>Visitor’s Log</strong></h4>

            <form id="visitorForm" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="fullName" class="form-label input-label">Name</label>
                        <input type="text" id="fullName" name="fullName" class="form-control"
                            placeholder="Enter your Name" required>
                    </div>
                    <div class="col-md-2">
                        <label for="city" class="form-label input-label">City/Municipality</label>
                        <select id="city" name="city" class="form-select" required>
                            <option value="" disabled selected hidden>Select City/Municipality</option>
                            <option value="a">a</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="visitReason" class="form-label input-label">Purpose for Visit</label>
                        <select id="visitReason" name="reason" class="form-select" required>
                            <option value="" disabled selected hidden>Select Reason</option>
                            <option value="Ocular Visit">Ocular Visit</option>
                            <option value="Business">Business</option>
                            <option value="Tourism">Tourism</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="gender" class="form-label input-label">Gender</label>
                        <select id="gender" name="gender" class="form-select" required>
                            <option value="" disabled selected hidden>Select Gender</option>
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
                <thead class="text-white">
                    <tr>
                        <th>Visitor No.</th>
                        <th>Visitor Name</th>
                        <th>City</th>
                        <th>Gender</th>
                        <th>Purpose for Visit</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody id="visitorTable">
                    <?php
                    $currentDate = date("Y-m-d");

                    $sql = "SELECT visitor_id, fullName, city, gender, reason, time, RANK() OVER (ORDER BY time ASC) AS daily_counter
                            FROM visitors 
                            WHERE DATE(time) = ? 
                            ORDER BY time ASC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $currentDate);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $formattedTime = date("h:i A", strtotime($row['time']));
                        echo "<tr>
                            <td>{$row['daily_counter']}</td>
                            <td>" . ucwords(strtolower($row['fullName'])) . "</td>
                            <td>{$row['city']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['reason']}</td>
                            <td>{$formattedTime}</td>
                        </tr>";
                    }
                ?>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="photoModalLabel">Take Visitor Photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="d-flex flex-row align-items-center">
                            <div class="flex-grow-1 text-center">
                                <video id="video" class="rounded shadow" style="width: 100%; height: auto;"
                                    autoplay></video>
                                <button id="captureBtn" class="btn btn-primary mt-2 w-100">
                                    <i class="fa-solid fa-camera"></i> Capture Photo
                                </button>
                            </div>

                            <div id="photoPreviewContainer" class="ms-3" style="display: none; width: 50%;">
                                <img id="photoPreview" class="img-fluid rounded shadow" src="" alt="Captured Photo">
                                <div class="mt-3 text-center">
                                    <button class="btn btn-secondary w-100" id="retakePhoto">
                                        <i class="fa-solid fa-rotate-right"></i> Retake
                                    </button>
                                    <button class="btn btn-success w-100 mt-2" id="confirmPhoto">
                                        <i class="fa-solid fa-check"></i> Confirm & Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('fetch_cities.php') // Call the PHP script
            .then(response => response.json()) // Convert response to JSON
            .then(data => {
                const citySelect = document.getElementById("city");

                data.forEach(city => {
                    let option = document.createElement("option");
                    option.value = city.city_name;
                    option.textContent = city.city_name;
                    citySelect.appendChild(option);
                });
            })
            .catch(error => console.error("Error fetching cities:", error));
    });

    const video = document.getElementById("video");
    const captureBtn = document.getElementById("captureBtn");
    const photoPreviewContainer = document.getElementById("photoPreviewContainer");
    const photoPreview = document.getElementById("photoPreview");
    const retakePhotoBtn = document.getElementById("retakePhoto");
    const confirmPhotoBtn = document.getElementById("confirmPhoto");
    const photoDataInput = document.getElementById("photoData");
    const visitorForm = document.getElementById("visitorForm");

    function validateForm() {
        const form = document.getElementById('visitorForm');
        let isValid = true;

        // Trigger HTML5 validation
        if (!form.checkValidity()) {
            form.reportValidity(); // Show native validation messages
            isValid = false;
        }

        // Custom validation for Full Name
        if (!validateFullName()) {
            isValid = false;
        }

        return isValid;
    }

    document.getElementById("submitBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission

        // Validate the form before showing the modal
        if (!validateForm()) {
            return; // Stop if validation fails
        }

        // Show the photo modal only if validation passes
        const photoModal = new bootstrap.Modal(document.getElementById("photoModal"));
        photoModal.show();

        // Access the camera after modal is shown
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Camera access error:", err);
                alert("Camera access denied. Please allow camera permission to proceed.");
                photoModal.hide();
            });
    });
    // Capture Photo
    captureBtn.addEventListener("click", function() {
        const canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext("2d");
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL("image/png");
        photoDataInput.value = imageData;
        photoPreview.src = imageData;
        photoPreviewContainer.style.display = "block";

        captureBtn.style.display = "none";
    });

    // Retake
    retakePhotoBtn.addEventListener("click", function() {
        photoPreviewContainer.style.display = "none";
        photoDataInput.value = "";
        captureBtn.style.display = "block";
        /* captureBtn.style.margin-left = "auto";
        captureBtn.style.margin-right = "auto"; */
    });

    // Confirm Photo & Submit Form
    confirmPhotoBtn.addEventListener("click", function() {
        if (photoDataInput.value) {
            new bootstrap.Modal(document.getElementById("photoModal")).hide();
            visitorForm.submit();
        } else {
            alert("Please capture a photo before confirming.");
        }
    });

    $(document).ready(function() {
        $('#visitorTable').DataTable({
            "paging": true,
            "searching": false,
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

    function validateFullName() {
        const fullNameInput = document.getElementById("fullName");
        const fullNameError = document.getElementById("fullNameError");
        const name = fullNameInput.value.trim();
        const nameRegex = /^[A-Za-z\s]+$/;

        if (name === "" || !nameRegex.test(name)) {
            fullNameError.style.display = "block";
            return false;
        }
        fullNameError.style.display = "none";
        return true;
    }
    document.addEventListener("DOMContentLoaded", function() {

        // Add an event listener for the form submission
        const form = document.getElementById("visitorForm");
        form.addEventListener("submit", function(event) {
            if (!validateFullName()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Prevent entry of special characters or numbers while typing
        fullNameInput.addEventListener("input", function(event) {
            const inputValue = fullNameInput.value;

            // Remove any non-alphabetical characters (including numbers and special characters)
            const cleanedValue = inputValue.replace(/[^A-Za-z\s]/g, "");

            if (inputValue !== cleanedValue) {
                fullNameInput.value =
                    cleanedValue; // Update the input value to remove invalid characters
            }

            // Dynamically validate the input on every keystroke
            validateFullName();
        });
    });
    </script>

</body>

</html>