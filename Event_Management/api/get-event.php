<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];
$edit_id = $_GET["id"] ?? '';
$output = [];

if (!empty($edit_id)) {

    $sql_fetch = "SELECT * FROM events WHERE id='$edit_id'";

    $stmt_fetch = mysqli_prepare($conn, $sql_fetch);

    if (mysqli_stmt_execute($stmt_fetch)) {

        $res_fetch = mysqli_stmt_get_result($stmt_fetch);

        if (mysqli_num_rows($res_fetch) > 0) {

            while ($rows = mysqli_fetch_assoc($res_fetch)) {

                $output[] = $rows;

                http_response_code(200);
                $response = ["message" => "Events data fetch successfully", "status" => true, "data" => $output];
            }
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch event data", "status" => false];
    }
} else {
    $sql_read = "SELECT e.*, c.category_name FROM events e 
    LEFT JOIN categories c ON e.category_id = c.id";

    $stmt_read = mysqli_prepare($conn, $sql_read);

    if (mysqli_stmt_execute($stmt_read)) {

        $result = mysqli_stmt_get_result($stmt_read);
        
        if (mysqli_num_rows($result) > 0) {

            while ($rows = mysqli_fetch_assoc($result)) {

                $output[] = $rows;

                http_response_code(200);
                $response = ["message" => "Events data fetch successfully", "status" => true, "data" => $output];
            }
        } else {
            http_response_code(404);
            $response = ["message" => "Events data not found", "status" => false];
        }
    } else {

        http_response_code(500);
        $response = ["message" => "Failed to fetch event data", "status" => false];
    }
}

echo json_encode($response);
