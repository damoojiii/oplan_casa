@font-face {
        font-family: 'Inter';
        src: url('fonts/Inter/Inter-VariableFont_opsz\,wght.ttf') format('truetype');
        font-weight: 100 900;
        font-stretch: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Karla';
        src: url('fonts/Karla/Karla-VariableFont_wght.ttf') format('truetype');
        font-weight: 100 900;
        font-stretch: normal;
        font-style: normal;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    *,
    p {
        margin: 0;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    #sidebar .font-logo {
        font-family: 'nautigal';
        font-size: 20px !important;
    }

    #sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        background: #273E26;
        z-index: 199;
    }

    /* Logo Circle */
    .logo-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 10px auto;
    }

    .logo-circle img {
        width: 100%;
    }

    /* Sidebar Links */
    #sidebar .nav-link {
        font-family: 'Karla', sans-serif;
        color: #fff;
        padding: 10px;
        border-radius: 4px;
        transition: color 0.3s;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }

    #sidebar .nav-link i {
        font-size: 18px;
    }

    /* Active Page */
    #sidebar .nav-link.active {
        color: #fff !important; /* White text */
        font-weight: bold;
    }
    #sidebar .nav-link.active::before {
        content: "";
        position: absolute;
        left: -10px;
        top: 0;
        height: 100%;
        width: 5px;
        background-color: #B71C1C;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }
    #sidebar .nav-link.active i {
        color: #B71C1C !important;
    }

    /* Hover Effect */
    #sidebar .nav-link:hover {
        color: #dddddd; /* Lighten text color on hover */
    }

    header {
        position: none;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 50px;
        transition: margin-left 0.3s ease;
        align-items: center;
        display: flex;
        /* Smooth transition for header */
    }

    #header {
        transition: margin-left 0.3s ease, width 0.3s ease;
    }

    #hamburger {
        border: none;
        background: none;
        cursor: pointer;
        margin-left: 15px;
        /* Space from the left edge */
        display: none;
        /* Initially hide the hamburger button */
    }

    #main-content {
        transition: margin-left 0.3s ease;
        margin-left: 250px;
        max-width: 80%;
        font-family: 'Inter';
    }

    hr {
        background-color: #ffff;
        height: 1.5px;
    }

    #sidebar .collapse {
        transition: height 0.3s ease-out, opacity 0.3s ease-out;
    }

    #sidebar .collapse.show {
        height: auto !important;
        opacity: 1;
    }

    #sidebar .collapse:not(.show) {
        height: 0;
        opacity: 0;
        overflow: hidden;
    }

    .navcircle {
        font-size: 7px;
        text-align: justify;
    }

    .main-menu {
        font-family: 'Karla';
        margin-bottom: 10px;
    }