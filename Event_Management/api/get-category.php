<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];
$output = []; //use for store fetch data and pass data to json 

$edit_id = $_GET["id"] ?? '';

if (!empty($edit_id)) {

    $sql_fetch = "SELECT * FROM categories WHERE id='$edit_id'";

    $stmt_fetch = mysqli_prepare($conn, $sql_fetch);

    if (mysqli_stmt_execute($stmt_fetch)) {

        $res_fetch = mysqli_stmt_get_result($stmt_fetch);

        if (mysqli_num_rows($res_fetch)>0) {

            while ($rows = mysqli_fetch_assoc($res_fetch)) {
                $output[] = $rows;

                http_response_code(200);
                $response = ["message" => "Category Date fetch successfully", "status" => true, "data" => $output];
            }
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch category data", "status" => false];
    }
} else {

    $sql_read = "SELECT * FROM categories";

    $stmt_read = mysqli_prepare($conn, $sql_read);

    if (!$stmt_read) {
        $response = ["message" => "Failed to prepare", "status" => false];
    }

    if (mysqli_stmt_execute($stmt_read)) {

        $res_category = mysqli_stmt_get_result($stmt_read);

        if (mysqli_num_rows($res_category) > 0) {

            while ($rows = mysqli_fetch_assoc($res_category)) {

                $output[] = $rows;

                http_response_code(200);
                $response = ["message" => "Category Date fetch successfully", "status" => true, "data" => $output];
            }
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch category data", "status" => false];
    }
}

echo json_encode($response);
