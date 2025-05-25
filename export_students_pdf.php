<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('connection.php');

$scheduled_id = $_GET['id'] ?? 0;

// Fetch scheduled details
$scheduled_query = "SELECT name, date FROM scheduled_tbl WHERE scheduled_id = '$scheduled_id'";
$scheduled_result = $conn->query($scheduled_query);

$school_name = 'School Name';
$scheduled_date = date('F j, Y');

if ($scheduled_result->num_rows > 0) {
    $row = $scheduled_result->fetch_assoc();
    $school_name = $row['name'];
    $scheduled_date = date('F j, Y', strtotime($row['date']));
}

// Fetch student data grouped and sorted
$students_query = "SELECT * FROM student_tbl WHERE scheduled_id = '$scheduled_id' ORDER BY FIELD(grade_level,
    'Kindergarten','SPED','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6',
    'Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12','College'), lastname ASC";
$result = $conn->query($students_query);

// Group students by grade level
$students_by_grade = [];

while ($row = $result->fetch_assoc()) {
    $grade = $row['grade_level'];
    if (!isset($students_by_grade[$grade])) {
        $students_by_grade[$grade] = [];
    }
    $students_by_grade[$grade][] = $row;
}

// Initialize PDF
$pdf = new TCPDF();
$pdf->SetTitle("Student Masterlist");
$pdf->SetMargins(15, 20, 15);

// Loop through each grade level
foreach ($students_by_grade as $grade => $students) {
    $pdf->AddPage();
    
    // Header
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, $school_name, 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Schedule Date: ' . $scheduled_date, 0, 1, 'C');
    $pdf->Ln(3);

    // Grade Level Title
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, "Grade Level: $grade", 0, 1);

    // Table Header
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(15, 8, 'No.', 1, 0, 'C');
    $pdf->Cell(130, 8, 'Name (Last, First)', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Signature', 1, 1, 'C');

    // Students List
    $pdf->SetFont('helvetica', '', 11);
    $count = 1;
    foreach ($students as $student) {
        $fullname = $student['lastname'] . ', ' . $student['firstname'];
        $pdf->Cell(15, 8, $count++, 1);
        $pdf->Cell(130, 8, $fullname, 1);
        $pdf->Cell(35, 8, '', 1); // Signature column
        $pdf->Ln();
    }
}

// Output the PDF for download
$filename = str_replace(' ', '_', $school_name) . "_student_masterlist.pdf";
$pdf->Output($filename, 'D');
