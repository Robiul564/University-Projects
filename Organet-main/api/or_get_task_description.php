<?php
header('Content-Type: application/json');

include_once '../helper/db_connection.php';

$response = ["success" => false, "task" => null];

if (!isset($_GET['task_id'])) {
    echo json_encode(["success" => false, "message" => "Task ID is required."]);
    exit;
}

$task_id = intval($_GET['task_id']);

try {
    $db = $mm_conn;

    $query = "SELECT Description FROM task WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        $response["success"] = true;
        $response["task"] = $task;
    } else {
        $response["message"] = "Task not found.";
    }
} catch (Exception $e) {
    $response["message"] = "An error occurred: " . $e->getMessage();
}

echo json_encode($response);
