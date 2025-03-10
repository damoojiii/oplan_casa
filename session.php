<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['userID']) || empty($_SESSION['userID'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>