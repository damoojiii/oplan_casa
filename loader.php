<!-- Add this right after the <body> tag -->
    <div class="loader-wrapper">
        <div class="loader">
            <?php
            $db = new mysqli('localhost', 'root', '', 'casadb');
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }

            $sql = "SELECT logo_path FROM site_settings WHERE id = 1";
            $result = $db->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $logo = !empty($row['logo_path']) ? $row['logo_path'] : 'img/rosariologo.png'; // Use default if empty
                    echo "<div class='loader-logo'>";
                    echo "<img src='$logo' alt='Logo' class='logo-circle' style='width: 150px; height: 150px; border-radius: 50%; border: none;'>";
                    echo "</div>";
                }
            } else {
                // If no logo found, display the default logo
                echo "<div class='loader-logo'>";
                echo "<img src='img/rosariologo.png' class='logo-circle' alt='Default Logo' style='width: 150px; height: 150px; border-radius: 50%; border: none;'>";
                echo "</div>";
            }
            ?>
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
            }, 5000);

            // Remove loader after animation
            setTimeout(() => {
                loaderWrapper.style.display = 'none';
            }, 1000);
        });
    </script>