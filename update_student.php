<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_VALIDATE_INT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $grade_name = $_POST['grade_name'];
    $guardian = $_POST['guardian'];
    $contact = $_POST['contact'];

    // Restriction: Names and guardian - only letters, spaces, hyphens
    $namePattern = "/^[a-zA-Z\s\-]+$/";

    // Input validation
    if (!$student_id || !preg_match($namePattern, $firstname) || !preg_match($namePattern, $lastname)
        || !preg_match($namePattern, $guardian) || empty($grade_name)) {
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    // Prepared statement to update record
    $stmt = $conn->prepare("UPDATE student_tbl SET firstname = ?, lastname = ?, grade_level = ?, guardian = ?, contact = ? WHERE student_id = ?");
    $stmt->bind_param("sssssi", $firstname, $lastname, $grade_name, $guardian, $contact, $student_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Student updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update student.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
