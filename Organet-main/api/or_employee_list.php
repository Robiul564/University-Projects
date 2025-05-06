<?php
include_once '../helper/db_connection.php';
$db = $mm_conn;

header('Content-Type: application/json');

try {

    $query = "SELECT * FROM user_type";
    $result = mysqli_query($db, $query);

    if (!$result) {
        throw new Exception("Failed to fetch delivery men data.");
    }

    $employee = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $employee[] = [
            'id' => $row['id'],
            'name' => $row['Name'],
            'email' => $row['Email'],
            'picture' => $row['Picture'],

//            'lastSeen' => $row['last_seen'],
//            'lastTakenOrder' => $row['last_taken_order'],
        ];
    }

    echo json_encode(['statusCode' => 200, 'employee' => $employee]);
} catch (Exception $e) {
    echo json_encode(['statusCode' => 500, 'message' => $e->getMessage()]);
}
?>