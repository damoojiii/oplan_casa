<?php
    include 'connection.php';

    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

    $sql = "
        SELECT reason, COUNT(*) AS total
        FROM visitors
        WHERE YEAR(time) = ?
        GROUP BY reason
        ORDER BY total DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'reason' => $row['reason'],
            'total' => (int)$row['total']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($data);
?>
