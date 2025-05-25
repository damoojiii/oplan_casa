<?php
    include 'connection.php';

    header('Content-Type: application/json');

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM scheduled_tbl WHERE scheduled_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $schedule = $result->fetch_assoc();

        echo json_encode($schedule);
    }
?>
