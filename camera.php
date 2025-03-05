<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Photo</title>
    <style>
        #preview {
            display: none;
            margin-top: 20px;
        }
        #buttons {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <video id="video" autoplay></video>
    <button id="capturePhoto">Capture</button>

    <div id="preview">
        <img id="previewImage" src="" alt="Captured Image" style="max-width: 100%; height: auto;">
    </div>
    <div id="buttons">
        <button id="retake">Retake</button>
        <button id="save">Save</button>
    </div>

    <canvas id="canvas" style="display:none;"></canvas>

    <script>
        let video = document.getElementById("video");
        let canvas = document.getElementById("canvas");
        let captureButton = document.getElementById("capturePhoto");
        let preview = document.getElementById("preview");
        let previewImage = document.getElementById("previewImage");
        let buttons = document.getElementById("buttons");
        let retakeButton = document.getElementById("retake");
        let saveButton = document.getElementById("save");

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => video.srcObject = stream)
            .catch(err => console.error("Camera access denied", err));

        captureButton.addEventListener("click", function () {
            let context = canvas.getContext("2d");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            let photoData = canvas.toDataURL("image/png");

            preview.style.display = "block";
            previewImage.src = photoData;

            buttons.style.display = "block";

            video.style.display = "none";
            captureButton.style.display = "none";

            localStorage.setItem("photo", photoData);
        });

        retakeButton.addEventListener("click", function () {
            preview.style.display = "none";
            buttons.style.display = "none";

            video.style.display = "block";
            captureButton.style.display = "block";
        });

        saveButton.addEventListener("click", function () {
            window.location.href = "index.php";
        });
    </script>
</body>
</html>
