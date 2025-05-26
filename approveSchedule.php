<?php
    include 'connection.php';

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "UPDATE scheduled_tbl SET status = 'Completed', updated_at = NOW() WHERE scheduled_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: trips.php?msg=approved");
        } else {
            echo "Error approving.";
        }
    }
?>
