<?php
include_once 'helper/db_connection.php';
$db = $mm_conn;


if (isset($_POST['project_id']) && isset($_POST['is_active'])) {
    $project_id = intval($_POST['project_id']);
    $is_active = intval($_POST['is_active']);

    $query = "UPDATE project SET is_Active = $is_active WHERE id = $project_id";
    if (mysqli_query($db, $query)) {
        echo "Project status updated successfully.";
    } else {
        echo "Error updating project status: " . mysqli_error($db);
    }
} else {
    echo "Invalid parameters.";
}
