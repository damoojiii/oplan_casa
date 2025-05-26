<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");
    
    $query = "SELECT * FROM gradelvl_tbl ORDER BY grade_id ASC";
    $result = $conn->query($query);

    $grades = [];
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }

    // Convert to JSON for AJAX
    echo json_encode($grades);
?>