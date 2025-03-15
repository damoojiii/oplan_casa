<?php
    include 'connection.php'; // Adjust to your actual database connection file

    if (isset($_GET['id'])) {
        $visitor_id = $_GET['id'];
        
        $stmt = $conn->prepare("SELECT * FROM visitors WHERE visitor_id = ?");
        $stmt->bind_param("i", $visitor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            echo json_encode($row);
        }
        
        $stmt->close();
    }

    $conn->close();
?>