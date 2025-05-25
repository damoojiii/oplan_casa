<?php
require 'vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $schoolId = $_POST['school']; // The selected school from the form

    try {
        $spreadsheet = IOFactory::load($file);

        // Read student data from first sheet
        $studentSheet = $spreadsheet->getSheet(0);
        $studentData = $studentSheet->toArray();

        // Read supervisor data from second sheet
        $supervisorSheet = $spreadsheet->getSheet(1);
        $supervisorData = $supervisorSheet->toArray();

        $conn = new mysqli("localhost", "root", "", "casadb");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        for ($i = 2; $i < count($studentData); $i++) {
            $row = $studentData[$i];

            if (empty(array_filter($row))) continue;

            $firstname = $conn->real_escape_string(trim($row[0] ?? ''));
            $lastname = $conn->real_escape_string(trim($row[1] ?? ''));
            $guardian = $conn->real_escape_string(trim($row[2] ?? ''));
            $contact = $conn->real_escape_string(trim($row[3] ?? ''));
            $gender = $conn->real_escape_string(trim($row[4] ?? ''));
            $grade = $conn->real_escape_string(trim($row[5] ?? ''));

            if ($firstname === '' || $lastname === '' || $gender === '' || $grade === '') continue;

            if (strtolower($guardian) === 'n/a' || strtolower($guardian) === 'none') {
                $guardian = 'N/A';
            }

            $sql = "INSERT INTO student_tbl (scheduled_id, firstname, lastname, guardian, contact, gender, grade_level)
                    VALUES ('$schoolId', '$firstname', '$lastname', '$guardian', '$contact', '$gender', '$grade')";
            $conn->query($sql);
        }

        // Insert Supervisors
        for ($j = 2; $j < count($supervisorData); $j++) {
            $row = $supervisorData[$j];

            if (empty(array_filter($row))) continue;

            $firstname = $conn->real_escape_string(trim($row[0] ?? ''));
            $lastname = $conn->real_escape_string(trim($row[1] ?? ''));
            $position = $conn->real_escape_string(trim($row[2] ?? ''));
            $contact = $conn->real_escape_string(trim($row[3] ?? ''));
            $gender = $conn->real_escape_string(trim($row[4] ?? ''));

            if ($firstname === '' || $lastname === '' || $position === '' || $gender === '') continue;

            $sql = "INSERT INTO supervisor_tbl (scheduled_id, firstname, lastname, position, contact, gender)
                    VALUES ('$schoolId', '$firstname', '$lastname', '$position', '$contact', '$gender')";
            $conn->query($sql);
        }

        echo "<script>alert('Data imported successfully!'); window.location.href='add-visitor.php';</script>";

    } catch (Exception $e) {
        echo "Error reading Excel file: " . $e->getMessage();
    }
}
?>
