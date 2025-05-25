<?php
    include 'connection.php';

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "DELETE FROM scheduled_tbl WHERE scheduled_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            header("Location: trips.php?msg=deleted");
        } else {
            echo "Error deleting.";
        }
    }
?>
