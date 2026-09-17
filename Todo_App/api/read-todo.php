<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require("../config/connection.php");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    $response = ["message" => "Only get method can allowed", "status" => false];
    exit;
}

$sql_read = "SELECT id, title,description,status FROM todos";

$stmt_read = mysqli_prepare($conn, $sql_read);

$output = "";
$response = [];

if (mysqli_stmt_execute($stmt_read)) {

    $res_todo = mysqli_stmt_get_result($stmt_read);

    if (mysqli_num_rows($res_todo) > 0) {

        $output = [];
        while ($rows = mysqli_fetch_assoc($res_todo)) {
            $output[] = $rows;
        }

        http_response_code(200);
        $response = ["message" => "Todo Data fetch successfully", "status" => true, "data" => $output];
    } else {
        http_response_code(404);
        $response = ["message" => "Data not found", "status" => false];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to fetch todo data", "status" => false];
}

echo json_encode($response);
