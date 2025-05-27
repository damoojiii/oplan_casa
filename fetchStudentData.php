<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $query = "SELECT s.firstname, s.lastname, sc.date
              FROM student_tbl s
              LEFT JOIN scheduled_tbl sc ON s.scheduled_id = sc.scheduled_id
              WHERE s.student_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    echo json_encode($data);
}
?>
