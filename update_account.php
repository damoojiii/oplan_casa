<?php
include "session.php";
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userID'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        header("Location: settings.php?error=invalid_email");
        exit;
    }
    
    try {
        $sql = "UPDATE users SET email = ? WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $userId);
        
        if ($stmt->execute()) {
            header("Location: settings.php?account_success=1");
        } else {
            header("Location: settings.php?error=update_failed");
        }
        
        $stmt->close();
    } catch (Exception $e) {
        header("Location: settings.php?error=exception&message=" . urlencode($e->getMessage()));
    }
    
    exit;
}

header("Location: settings.php");
exit;
?>