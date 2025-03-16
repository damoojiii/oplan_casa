<?php
include "session.php";
include("connection.php");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = trim($_POST['email']);
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $userId = $_SESSION['userID']; // Using userID from the users table
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: settings.php?error=invalid_email");
        exit;
    }
    
    // Verify current password
    $sql = "SELECT password FROM users WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $storedPassword = $row['password'];
        
        // Verify password (assuming passwords are stored with password_hash)
        if (!password_verify($currentPassword, $storedPassword)) {
            header("Location: settings.php?error=wrong_password");
            exit;
        }
        
        // Start transaction
        mysqli_begin_transaction($conn);
        
        try {
            // Update email
            $updateEmailSql = "UPDATE users SET email = ? WHERE userID = ?";
            $stmt = mysqli_prepare($conn, $updateEmailSql);
            mysqli_stmt_bind_param($stmt, "si", $email, $userId);
            $emailUpdated = mysqli_stmt_execute($stmt);
            
            // Update password if provided
            $passwordUpdated = true;
            if (!empty($newPassword)) {
                // Validate new password matches confirmation
                if ($newPassword !== $confirmPassword) {
                    header("Location: settings.php?error=password_mismatch");
                    exit;
                }
                
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update password
                $updatePasswordSql = "UPDATE users SET password = ? WHERE userID = ?";
                $stmt = mysqli_prepare($conn, $updatePasswordSql);
                mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userId);
                $passwordUpdated = mysqli_stmt_execute($stmt);
            }
            
            // If both operations were successful, commit the transaction
            if ($emailUpdated && $passwordUpdated) {
                mysqli_commit($conn);
                header("Location: settings.php?account_success=1");
                exit;
            } else {
                // Something went wrong, rollback
                mysqli_rollback($conn);
                header("Location: settings.php?error=update_failed");
                exit;
            }
        } catch (Exception $e) {
            // An exception occurred, rollback the transaction
            mysqli_rollback($conn);
            header("Location: settings.php?error=exception&message=" . urlencode($e->getMessage()));
            exit;
        }
    } else {
        header("Location: settings.php?error=user_not_found");
        exit;
    }
} else {
    // If not a POST request, redirect to settings page
    header("Location: settings.php");
    exit;
}
?>
