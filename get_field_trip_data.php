<?php
    include "connection.php";

    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    $sql = "
        SELECT MONTH(date) AS month, COUNT(*) AS count
        FROM scheduled_tbl
        WHERE YEAR(date) = ?
        GROUP BY MONTH(date)
        ORDER BY MONTH(date)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    $monthsMap = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'];

    $monthCounts = array_fill(1, 12, 0);
    while ($row = $result->fetch_assoc()) {
        $monthCounts[(int)$row['month']] = (int)$row['count'];
    }

    foreach ($monthCounts as $m => $count) {
        $data[] = [
            'month' => $monthsMap[$m],
            'count' => $count
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>
