<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supervisor_id = filter_input(INPUT_POST, 'supervisor_id', FILTER_VALIDATE_INT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $position = $_POST['position'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];

    // Restriction: Names and guardian - only letters, spaces, hyphens
    $namePattern = "/^[a-zA-Z\s\-]+$/";

    // Input validation
    if (!$supervisor_id || !preg_match($namePattern, $firstname) || !preg_match($namePattern, $lastname)
        || !preg_match($namePattern, $position) || empty($contact)) {
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    // Prepared statement to update record
    $stmt = $conn->prepare("UPDATE supervisor_tbl SET firstname = ?, lastname = ?, position = ?, contact = ?, gender = ? WHERE supervisor_id = ?");
    $stmt->bind_param("sssssi", $firstname, $lastname, $position, $contact, $gender, $supervisor_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Supervisor updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update supervisor.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
