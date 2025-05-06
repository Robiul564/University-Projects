<?php
header('Content-Type: application/json');

include_once '../helper/db_connection.php';
$db = $mm_conn;

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $id = $_SESSION['id'];


    $sql = "SELECT project.id, project.Name
            FROM project
            JOIN assigned_dev ON project.id = assigned_dev.project_id
            WHERE assigned_dev.user_ID = ?
            AND project.Name LIKE ?
            LIMIT 3";

    $stmt = $db->prepare($sql);

    $likeQuery = '%' . $query . '%';
    $stmt->bind_param('is', $id, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['id'],
            'name' => $row['Name']
        ];
    }

    echo json_encode($suggestions);
}
?>