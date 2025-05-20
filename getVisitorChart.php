<?php
include "connection.php";

$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$sql = "SELECT DATE_FORMAT(time, '%M') as month, COUNT(visitor_id) as count 
        FROM visitors 
        WHERE YEAR(time) = $year
        GROUP BY MONTH(time)
        ORDER BY MONTH(time) ASC";

$result = $conn->query($sql);

$months = [];
$counts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $counts[] = $row['count'];
    }
}

echo json_encode([
    'months' => $months,
    'counts' => $counts
]);
?>
