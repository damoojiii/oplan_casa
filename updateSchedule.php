<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['scheduled_id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $num_bus = $_POST['num_bus'];

    $sql = "UPDATE scheduled_tbl SET name=?, date=?, time=?, num_bus=? WHERE scheduled_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $name, $date, $time, $num_bus, $id);

    if ($stmt->execute()) {
        header("Location: trips.php?msg=updated");
    } else {
        echo "Update failed.";
    }
}
?>
