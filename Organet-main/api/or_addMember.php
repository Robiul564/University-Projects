<?php
include_once '../helper/db_connection.php';
$db = $mm_conn;


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $response = [];
    header('Content-Type: application/json');

    $user_id = $_POST['user_id'];
    $referral_id = $_SESSION['id'];

//    echo "user id--".$user_id;
//    echo  "referral id--".$referral_id;

    try {
        if ($user_id && $referral_id) {
            $sql_check = "SELECT * FROM referal WHERE user_id = $user_id AND referral_id = $referral_id";

            $result = mysqli_query($db, $sql_check);

            if (mysqli_num_rows($result) == 0) {
                $insert_referral_id_query = "INSERT INTO referal (user_id,referral_id) values ($user_id,$referral_id)";

                $result = mysqli_query($db, $insert_referral_id_query);

                if ($result) {
                    $response['statusCode'] = 200;
                    $response['message'] = "Add successfully";
                } else {
                    $response['statusCode'] = 211;
                    $response['message'] = "Something went wrong";
                }
            } else {
                $response['statusCode'] = 201;
                $response['message'] = "data already exits";

            }
        } else {
            $response['statusCode'] = 201;
            $response['message'] = "user_id or referral_id not found";
        }
    } catch (Exception $e) {
        $response['statusCode'] = 501;
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
}