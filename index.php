<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullName = $_POST['fullName'];
        $gender = $_POST['gender'];
        $visitReason = $_POST['visitReason'];
        $time = date("Y-m-d H:i:s");

        // Handle photo upload
        if (!empty($_POST['photoData'])) {
            $photoData = $_POST['photoData'];
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

    <!-- Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

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

    #camera-container,
    #captured-photo {
        display: none;
        /* Hidden initially */
        margin-top: 10px;
    }

    video,
    img {
        width: 100%;
        max-width: 300px;
        border: 2px solid #ddd;
        border-radius: 5px;
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

            <form id="visitorForm" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" id="fullName" name="fullName" class="form-control"
                            placeholder="Enter your Full Name" required>
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

                    <!-- Capture Photo Section -->
                    <div class="col-md-4">
                        <label class="form-label">Capture Photo</label><br>
                        <button type="button" id="openCamera" class="btn btn-primary">Open Camera</button>
                        <div id="liveness-instructions" class="mt-2 text-muted" style="display: none;">
                            Please turn your head slightly left and right
                        </div>
                    </div>

                    <!-- Camera Preview -->
                    <div class="col-md-4" id="camera-container">
                        <video id="video" autoplay></video>
                        <button type="button" id="capturePhoto" class="btn btn-warning mt-2">Capture</button>
                    </div>

                    <!-- Captured Image Preview -->
                    <div class="col-md-4" id="captured-photo">
                        <img id="photoPreview">
                        <input type="hidden" id="photoData" name="photoData">
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

    <script>
    let video = document.getElementById("video");
    let photoPreview = document.getElementById("photoPreview");
    let photoData = document.getElementById("photoData");

    $("#openCamera").click(function() {
        $("#camera-container").show();
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(err) {
                alert("Camera access denied: " + err);
            });
    });

    $("#capturePhoto").click(function() {
        if (!isLivenessConfirmed) {
            alert("Please perform liveness check first");
            return;
        }
        let canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let ctx = canvas.getContext("2d");
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        let imageData = canvas.toDataURL("image/png"); // Convert to Base64
        photoPreview.src = imageData;
        photoData.value = imageData;

        $("#camera-container").hide();
        $("#captured-photo").show();
    });

    // Load face-api models
    Promise.all([
        faceapi.nets.tinyFaceDetector.loadFromUri('models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('models')
    ]).then(startCamera);

    let faceMovementHistory = [];
    let isLivenessConfirmed = false;

    async function startCamera() {
        $("#openCamera").click(async function() {
            $("#liveness-instructions").show();
            $("#camera-container").show();

            const stream = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            video.srcObject = stream;

            // Start face detection loop
            detectLiveness();
        });
    }

    async function detectLiveness() {
        const detection = await faceapi.detectSingleFace(
            video,
            new faceapi.TinyFaceDetectorOptions()
        ).withFaceLandmarks();

        if (detection) {
            // Track head rotation using landmarks
            const landmarks = detection.landmarks;
            const nose = landmarks.getNose();
            const leftEye = landmarks.getLeftEye();
            const rightEye = landmarks.getRightEye();

            // Simple head movement detection
            trackHeadMovement(nose[0].x);

            // Blink detection
            const leftEyeHeight = faceapi.euclideanDistance(leftEye[1], leftEye[5]);
            const rightEyeHeight = faceapi.euclideanDistance(rightEye[1], rightEye[5]);

            if (leftEyeHeight < 5 && rightEyeHeight < 5) {
                // Eyes appear closed
                faceMovementHistory.push('blink');
            }
        }

        if (!isLivenessConfirmed) {
            setTimeout(() => detectLiveness(), 100);
        }
    }

    function trackHeadMovement(xPosition) {
        faceMovementHistory.push(xPosition);

        // Keep only last 20 positions
        if (faceMovementHistory.length > 20) {
            faceMovementHistory.shift();
        }

        // Check for sufficient movement variation
        const min = Math.min(...faceMovementHistory);
        const max = Math.max(...faceMovementHistory);

        if ((max - min) > 50 && faceMovementHistory.includes('blink')) {
            isLivenessConfirmed = true;
            $("#liveness-instructions").html("Liveness confirmed! You can now take photo");
            $("#capturePhoto").prop("disabled", false);
        }
    }
    </script>

</body>

</html>