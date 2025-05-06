<?php

include_once '../helper/db_connection.php';
$db = $mm_conn;
$projectName = $startDate = $endDate = $priority = $projectDescription = '';
$developerUsers = [];
$adminUser = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = $_POST['projectName'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $priority = $_POST['priority'];
    $projectDescription = $_POST['projectDescription'];



    if (isset($_POST['users'])) {
        $developerUsers = $_POST['users'];
    }


    if (isset($_POST['admin'])) {
        $adminUser = $_POST['admin'];
    }


    mysqli_autocommit($db, false);

    try {
        if ($projectName && $projectDescription && $startDate && $endDate && !empty($developerUsers) && $adminUser) {


            $insert_project_data_query = "INSERT INTO project (Name, Start_Date, End_Date, Priority, Project_Description,is_Active) VALUES (?, ?, ?, ?, ?,?)";
            $stmt = mysqli_prepare($db, $insert_project_data_query);
            mysqli_stmt_bind_param($stmt, "sssssi", $projectName, $startDate, $endDate, $priority, $projectDescription, $is_active);
            $is_active = 1;
            mysqli_stmt_execute($stmt);


            $project_id = mysqli_insert_id($db);

            // Insert assigned developers
            $insert_assigned_dev_query = "INSERT INTO assigned_dev (Project_ID, User_id, assigned_as) VALUES (?, ?, ?)";
            $stmt_assigned_dev = mysqli_prepare($db, $insert_assigned_dev_query);
            foreach ($developerUsers as $developer) {
                $assigned_as = "Developer";
                mysqli_stmt_bind_param($stmt_assigned_dev, "iis", $project_id, $developer, $assigned_as);
                mysqli_stmt_execute($stmt_assigned_dev);
            }

            // Insert assigned admin
            $insert_assigned_admin_query = "INSERT INTO assigned_dev (Project_ID, User_id, assigned_as) VALUES (?, ?, ?)";
            $stmt_assigned_admin = mysqli_prepare($db, $insert_assigned_admin_query);
            $assigned_as = "Project Manager";
            mysqli_stmt_bind_param($stmt_assigned_admin, "iis", $project_id, $adminUser, $assigned_as);
            mysqli_stmt_execute($stmt_assigned_admin);


            mysqli_commit($db);
            echo "<script>
    alert('Project and users assigned successfully.');
    window.location.href = '/dashboard.php';
</script>";
            exit;
        } else {
            throw new Exception("Missing required fields.");
        }
    } catch (Exception $e) {
        // Rollback if any exception occurs
        mysqli_rollback($db);
        $response['statusCode'] = 8;
        $response['message'] = "An error occurred: " . $e->getMessage();
        echo $response['message'];
    } finally {
        mysqli_autocommit($db, true);
    }
}



