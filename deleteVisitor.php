<?php
    include 'connection.php';

    if (isset($_GET['id'])) {
        $visitor_id = $_GET['id'];

        // Prepare and execute the delete statement
        $stmt = $conn->prepare("DELETE FROM visitors WHERE visitor_id = ?");
        $stmt->bind_param("i", $visitor_id);

        if ($stmt->execute()) {
            // Redirect back with success message
            header("Location: visitorslist.php?msg=Record deleted successfully");
            exit();
        } else {
            // Redirect back with error message
            header("Location: visitorslist.php?msg=Error deleting record");
            exit();
        }

        $stmt->close();
    }

    $conn->close();
?>
