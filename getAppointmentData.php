<?php
    include "connection.php";

    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    $monthNames = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    $sql = "
        SELECT 
            MONTH(date) AS month,
            COUNT(*) AS appointment_count
        FROM 
            scheduled_tbl
        WHERE 
            YEAR(date) = $year
        GROUP BY 
            MONTH(date)
        ORDER BY 
            MONTH(date) ASC
    ";

    $result = $conn->query($sql);

    $months = [];
    $appointmentCounts = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $months[] = $monthNames[$row['month']];
            $appointmentCounts[] = $row['appointment_count'];
        }
    }

    echo json_encode([
        'months' => $months,
        'counts' => $appointmentCounts
    ]);
?>
