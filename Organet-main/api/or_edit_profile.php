<?php
session_start();
if (!isset($_POST['user_id']) || !isset($_SESSION['id']) || $_POST['user_id'] != $_SESSION['id']) {
    header("Location: login.php");
    exit;
}

include_once 'helper/db_connection.php';
$db = $mm_conn;

$user_id = $_POST['user_id'];
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);


if (empty($name) || empty($email)) {
    echo "Name and email are required!";
    exit;
}


if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $query = "UPDATE user_type SET Name = ?, Email = ?, Password = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
} else {
    $query = "UPDATE user_type SET Name = ?, Email = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssi", $name, $email, $user_id);
}

if ($stmt->execute()) {
    header("Location: profile.php?status=success");
    exit;
} else {
    echo "Error updating profile: " . $db->error;
}
?>
