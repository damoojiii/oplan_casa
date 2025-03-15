<?php
    include 'connection.php'; // Ensure you include the database connection

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $visitor_id = $_POST['visitor_id'];
        $fullName = htmlspecialchars($_POST['fullName'], ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
        $gender = $_POST['gender'];
        $reason = htmlspecialchars($_POST['reason'], ENT_QUOTES, 'UTF-8');

        $sql = "UPDATE visitors SET fullName = ?, city = ?, gender = ?, reason = ? WHERE visitor_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $fullName, $city, $gender, $reason, $visitor_id);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update visitor."]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
    }
?>
