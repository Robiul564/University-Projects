<?php
include_once '../helper/db_connection.php';
$db = $mm_conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = [];
    header('Content-Type: application/json');
    try {

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $profile_pic = "assets/img/coding.png";


        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception("All fields are required.", 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.", 400);
        }


        $check_email_query = "SELECT * FROM user_type WHERE Email = ?";
        $stmt = mysqli_prepare($db, $check_email_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            throw new Exception("Email already exists.", 409);
        }


        $hash = password_hash($password, PASSWORD_BCRYPT);


        $insert_query = "INSERT INTO user_type (Name, Email, Password,Picture) VALUES (?, ?, ?,?)";
        $stmt = mysqli_prepare($db, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hash, $profile_pic);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to register user. Please try again.", 500);
        }

        $response['statusCode'] = 200;
        $response['message'] = "Sign-up successful!";
    } catch (Exception $e) {
        $response['statusCode'] = $e->getCode() ?: 500;
        $response['message'] = $e->getMessage();
        error_log($e->getMessage());
    }

    echo json_encode($response);
}
?>