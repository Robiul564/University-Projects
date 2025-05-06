<?php
include_once '../helper/db_connection.php';
$db = $mm_conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = $_POST['task_id'];
    $task_status = $_POST['task_status'];
    $comment = $_POST['comment'];
    $project_id = $_POST['project_id'];
    $user_id = $_POST['user_id'];


    $update_query = "
        UPDATE task
        SET 
            task_done = ?,
            comment = JSON_ARRAY_APPEND(
                IFNULL(comment, '[]'),
                '$',
                JSON_OBJECT(
                    'user_id', ?, 
                    'project_id', ?, 
                    'comment', ?
                )
            )
        WHERE id = ? AND Assigned_user = ? AND Project_ID = ?
    ";

    $stmt = $db->prepare($update_query);
    if (!$stmt) {
        $error_message = urlencode('Failed to prepare the update statement.');
        header("Location: ../user_project.php?id=$project_id&status=error&message=$error_message");
        exit;
    }

    $stmt->bind_param(
        "iiisiii",
        $task_status,
        $user_id,
        $project_id,
        $comment,
        $task_id,
        $user_id,
        $project_id
    );

    if ($stmt->execute()) {
        $success_message = 'Task updated successfully.';
        echo "<script>
        alert('$success_message');
        window.location.href = '../user_project.php?id=$project_id';
    </script>";
    } else {
        $error_message = 'Failed to update the task.';
        echo "<script>
        alert('$error_message');
        window.location.href = '../user_project.php?id=$project_id';
    </script>";
    }


    $stmt->close();
}
?>
