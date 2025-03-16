<?php
include "session.php";
include("connection.php");

// Initialize response array
$response = array(
    'success' => false,
    'message' => ''
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userID'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Validate inputs
    if (empty($currentPassword)) {
        $response['message'] = 'Current password is required';
    } elseif (empty($newPassword)) {
        $response['message'] = 'New password is required';
    } elseif (empty($confirmPassword)) {
        $response['message'] = 'Please confirm your new password';
    } elseif ($newPassword !== $confirmPassword) {
        $response['message'] = 'New password and confirmation do not match';
    } else {
        try {
            // Verify current password
            $sql = "SELECT password FROM users WHERE userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $storedPassword = $row['password'];
                
                // Verify the current password
                if (password_verify($currentPassword, $storedPassword)) {
                    // Hash the new password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    
                    // Update the password
                    $updateSql = "UPDATE users SET password = ? WHERE userID = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("si", $hashedPassword, $userId);
                    
                    if ($updateStmt->execute()) {
                        $response['success'] = true;
                        $response['message'] = 'Password updated successfully';
                    } else {
                        $response['message'] = 'Failed to update password';
                    }
                    
                    $updateStmt->close();
                } else {
                    $response['message'] = 'Current password is incorrect';
                }
            } else {
                $response['message'] = 'User not found';
            }
            
            $stmt->close();
        } catch (Exception $e) {
            $response['message'] = 'An error occurred: ' . $e->getMessage();
        }
    }
}

// Return JSON response for AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Redirect with appropriate message for form submissions
    $redirectUrl = 'settings.php';
    if ($response['success']) {
        $redirectUrl .= '?password_success=1';
    } else {
        $redirectUrl .= '?password_error=' . urlencode($response['message']);
    }
    header("Location: $redirectUrl");
    exit;
}
?>
