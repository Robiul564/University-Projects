<?php
include_once '../helper/db_connection.php';

$db = $mm_conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $response = [];
    header('Content-Type: application/json');

    try {

        $email = trim($_POST['email']);
        $password = $_POST['password'];


        if (empty($email) || empty($password)) {
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
            $data = mysqli_fetch_assoc($result);
            $database_password = $data['Password'];
            $id = $data['id'];


            if (password_verify($password, $database_password)) {

                $_SESSION['id'] = $id;

                if ($email === 'admin@g.com') {
                    $response['statusCode'] = 200;
                    $response['message'] = "Admin login successful";
                } else {
                    $response['statusCode'] = 200;
                    $response['message'] = "Login successful";
                }
            } else {
                throw new Exception("Incorrect password", 401);
            }
        } else {
            throw new Exception("User not found", 404);
        }
    } catch (Exception $e) {

        $response['statusCode'] = $e->getCode() ?: 500;
        $response['message'] = $e->getMessage();
        error_log($e->getMessage());
    }


    echo json_encode($response);
}
?>
