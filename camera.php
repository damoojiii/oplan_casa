<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Photo</title>
</head>
<body>
    <video id="video" autoplay></video>
    <button id="capturePhoto">Capture</button>
    <canvas id="canvas" style="display:none;"></canvas>

    <script>
        let video = document.getElementById("video");
        let canvas = document.getElementById("canvas");
        let captureButton = document.getElementById("capturePhoto");

        // Start camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => console.error("Camera access denied", err));

        // Capture photo
        captureButton.addEventListener("click", function () {
            let context = canvas.getContext("2d");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            let photoData = canvas.toDataURL("image/png"); // Convert to base64 image

            // Store captured image in localStorage
            localStorage.setItem("photo", photoData);

            // Redirect back to the form page
            window.location.href = "index.php";
        });
    </script>
</body>
</html>
