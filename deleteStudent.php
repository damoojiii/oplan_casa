<?php
    include 'connection.php';

    if (isset($_GET['id']) && isset($_GET['scheduled_id'])) {
        $visitor_id = $_GET['id'];
        $scheduled_id = $_GET['scheduled_id'];

        $stmt = $conn->prepare("DELETE FROM student_tbl WHERE student_id = ?");
        $stmt->bind_param("i", $visitor_id);

        if ($stmt->execute()) {
            header("Location: view-students.php?scheduled_id=$scheduled_id&msg=Record deleted successfully");
            exit();
        } else {
            header("Location: view-students.php?scheduled_id=$scheduled_id&msg=Error deleting record");
            exit();
        }

        $stmt->close();
    }


    $conn->close();
?>
