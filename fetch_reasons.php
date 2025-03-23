<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");
    
    $query = "SELECT purpose_id, purpose FROM purpose_tbl ORDER BY purpose ASC";
    $result = $conn->query($query);

    $reason = [];
    while ($row = $result->fetch_assoc()) {
        $reason[] = $row;
    }

    // Convert to JSON for AJAX
    echo json_encode($reason);
?>