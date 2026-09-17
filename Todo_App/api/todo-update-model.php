<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require("../config/connection.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response = ["message" => "Only get method can allowed", "status" => false];
    exit;
}

$response = [];

$edit_id = $_POST["id"];

$sql_edit = "SELECT * FROM todos WHERE id='$edit_id'";

$stmt_edit = mysqli_prepare($conn, $sql_edit);

if (mysqli_stmt_execute($stmt_edit)) {

    $result = mysqli_stmt_get_result($stmt_edit);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        http_response_code(200);
        $response = ["message" => "Todo data fetch successfully", "status" => true, "data" => $row];
    } else {
        http_response_code(404);
        $response = ["message" => "Data not found", "status" => false];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to fetch data", "status" => false];
}

echo json_encode($response);
