<?php
include_once '../helper/db_connection.php';
$db = $mm_conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    header('Content-Type: application/json');

    try {
        $user_ids = $_POST['user_ids'];
        $task_name = htmlspecialchars($_POST['task_name']);
        $task_description = htmlspecialchars($_POST['taskDescription']);
        $task_end_date = htmlspecialchars($_POST['task_end_date']);
        $task_start_date = htmlspecialchars($_POST['task_start_date']);
        $project_id = (int)$_POST['project_id'];

        if (!is_array($user_ids) || empty($user_ids)) {
            $response['statusCode'] = 400;
            $response['message'] = "No users selected for task assignment.";
            echo json_encode($response);
            exit;
        }

        $query2 = "SELECT Start_Date, End_Date FROM project WHERE id = ?";
        if ($stmt2 = mysqli_prepare($db, $query2)) {
            mysqli_stmt_bind_param($stmt2, "i", $project_id);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_bind_result($stmt2, $project_start_date, $project_end_date);
            mysqli_stmt_fetch($stmt2);
            mysqli_stmt_close($stmt2);

            if (strtotime($task_start_date) < strtotime($project_start_date)) {
                $response['statusCode'] = 400;
                $response['message'] = "Task start date cannot be earlier than the project's start date.";
                echo json_encode($response);
                exit;
            }

            if (strtotime($task_end_date) > strtotime($project_end_date)) {
                $response['statusCode'] = 400;
                $response['message'] = "Task end date cannot be later than the project's end date.";
                echo json_encode($response);
                exit;
            }
        }

        $query = "INSERT INTO task (Task_Name, Assigned_user, Start_Date, Complete_Date, Project_ID, Description, task_done, comment)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($db, $query);
        if (!$stmt) {
            $response['statusCode'] = 500;
            $response['message'] = "Failed to prepare the statement. Please check your database connection.";
            error_log("MySQL Error: " . mysqli_error($db));
            echo json_encode($response);
            exit;
        }

        foreach ($user_ids as $user_id) {
            $user_id = (int)$user_id;
            $task_done = 0;
            $comment = NULL;

            mysqli_stmt_bind_param($stmt, "sissisis", $task_name, $user_id, $task_start_date, $task_end_date, $project_id, $task_description, $task_done, $comment);

            if (!mysqli_stmt_execute($stmt)) {
                $response['statusCode'] = 211;
                $response['message'] = "Failed to assign task to user ID: $user_id. Please try again.";
                error_log("MySQL Error: " . mysqli_stmt_error($stmt));
                echo json_encode($response);
                exit;
            }
        }


        mysqli_stmt_close($stmt);
        $response['statusCode'] = 200;
        $response['message'] = "Task assigned successfully to all selected users!";
    } catch (Exception $e) {
        $response['statusCode'] = $e->getCode() ?: 500;
        $response['message'] = $e->getMessage();
        error_log($e->getMessage());
    }

   
    echo json_encode($response);
}
?>
