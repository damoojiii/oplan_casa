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