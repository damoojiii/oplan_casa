<?php
require 'vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $schoolId = $_POST['school']; // The selected school from the form

    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $conn = new mysqli("localhost", "root", "", "casadb");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        for ($i = 1; $i < count($data); $i++) {
            $row = $data[$i];
        
            // Skip empty rows (all cells empty)
            if (empty(array_filter($row))) {
                continue;
            }
        
            // Extract and sanitize fields
            $firstname = $conn->real_escape_string(trim($row[0] ?? ''));
            $lastname = $conn->real_escape_string(trim($row[1] ?? ''));
            $guardian = $conn->real_escape_string(trim($row[2] ?? ''));
            $contact = $conn->real_escape_string(trim($row[3] ?? ''));
            $gender = $conn->real_escape_string(trim($row[4] ?? ''));
            $school = $conn->real_escape_string($schoolId);
            $grade = $conn->real_escape_string(trim($row[5] ?? ''));
        
            // Validation: Check required fields
            if ($firstname === '' || $lastname === '' || $gender === '' || $grade === '') {
                echo "Skipping row $i: missing required fields.<br>";
                continue;
            }
        
            // Allow guardian to be N/A or none
            if (strtolower($guardian) === 'n/a' || strtolower($guardian) === 'none') {
                $guardian = 'N/A';
            }
        
            $sql = "INSERT INTO student_tbl (scheduled_id, firstname, lastname, guardian, contact, gender, grade_level) 
                    VALUES ('$school', '$firstname', '$lastname', '$guardian', '$contact', '$gender', '$grade')";
        
            if (!$conn->query($sql)) {
                echo "Error on row $i: " . $conn->error . "<br>";
            }
        }

        echo "<script>alert('Data imported successfully!'); window.location.href='add-visitor.php';</script>";

    } catch (Exception $e) {
        echo "Error reading Excel file: " . $e->getMessage();
    }
}
?>
