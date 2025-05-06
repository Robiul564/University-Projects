<?php

include_once 'helper/db_connection.php';

$db = $mm_conn;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $project_id = intval($_POST['project_id']);
    $project_name = trim($_POST['project_name']);
    $project_description = trim($_POST['project_description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $priority = intval($_POST['priority']);
    $manager_id = $_POST['manager'];
    $selected_users = isset($_POST['users']) ? $_POST['users'] : [];


    echo "Project ID: $project_id<br>";
    echo "Project Name: $project_name<br>";
    echo "Project Description: $project_description<br>";
    echo "Start Date: $start_date<br>";
    echo "End Date: $end_date<br>";
    echo "Priority: $priority<br>";
    echo "Manager: " . $_POST['manager'] . "<br>";
    echo "Selected Users: " . implode(', ', $selected_users) . "<br>";


    if (empty($project_name) || empty($project_description) || empty($start_date) || empty($end_date) || empty($priority)) {
        echo "<script>alert('All fields are required.'); window.location.href='edit_project.php?project_id=$project_id';</script>";
        exit;
    }


    $db->begin_transaction();

    try {

        $query = "UPDATE project SET Name = ?, Project_Description = ?, Start_Date = ?, End_Date = ?, Priority = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ssssii", $project_name, $project_description, $start_date, $end_date, $priority, $project_id);
        $stmt->execute();
        $stmt->close();


        echo "Project details updated successfully.<br>";


        $deleteQuery = "DELETE FROM assigned_dev WHERE Project_ID = ?";
        $stmt = $db->prepare($deleteQuery);
        $stmt->bind_param("i", $project_id);
        $stmt->execute();
        $stmt->close();


        if (!empty($manager_id)) {
            $managerQuery = "INSERT INTO assigned_dev (User_ID, assigned_as, Project_ID) VALUES (?, 'Project Manager', ?)";
            $stmt = $db->prepare($managerQuery);
            $manager_id = intval($manager_id);
            $stmt->bind_param("ii", $manager_id, $project_id);
            $stmt->execute();
            $stmt->close();


            echo "Assigned User ID $manager_id to Project ID $project_id as Project Manager.<br>";
        }


        if (!empty($selected_users)) {
            $assignQuery = "INSERT INTO assigned_dev (User_ID, assigned_as, Project_ID) VALUES (?, 'Developer', ?)";
            $stmt = $db->prepare($assignQuery);

            foreach ($selected_users as $user_id) {
                $user_id = intval($user_id);
                $stmt->bind_param("ii", $user_id, $project_id);
                $stmt->execute();


                echo "Assigned User ID $user_id to Project ID $project_id as Developer.<br>";
            }

            $stmt->close();
        }


        $db->commit();

        echo "<script>alert('Project updated successfully.'); window.location.href='/dashboard.php';</script>";
    } catch (Exception $e) {

        $db->rollback();
        echo "<script>alert('An error occurred: " . $e->getMessage() . "'); window.location.href='/edit_project.php?project_id=$project_id';</script>";
    }

} else {
    echo "<script>alert('Invalid request.'); window.location.href='/dashboard.php';</script>";
    exit;
}
