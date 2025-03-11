<?php
    session_start();
    include 'connection.php';
    date_default_timezone_set("Asia/Manila");
    
    $query = "SELECT cityID, city_name FROM cities ORDER BY city_name ASC";
    $result = $conn->query($query);

    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }

    // Convert to JSON for AJAX
    echo json_encode($cities);
?>