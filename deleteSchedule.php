<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $status = "Cancelled";
    
    $sql = "UPDATE scheduled_tbl SET status = ?, updated_at = NOW() WHERE scheduled_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        header("Location: trips.php?msg=cancelled");
        exit;
    } else {
        echo "Error updating status.";
    }
}
?>

