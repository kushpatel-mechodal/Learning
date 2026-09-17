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

$update_id = $_POST["id"];
$update_title = $_POST["title"];
$update_description = $_POST["description"];

$sql_update = "UPDATE todos SET title='$update_title',description='$update_description'
WHERE id='$update_id'";

$stmt_update = mysqli_prepare($conn, $sql_update);

if (mysqli_stmt_execute($stmt_update)) {

    $affected_row = mysqli_affected_rows($conn);

    if ($affected_row > 0) {

        http_response_code(200);
        $response = ["message" => "Todo data update successfully", "status" => true];
    } else {
        http_response_code(404);
        $response = ["message" => "Data not found", "status" => false];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to update data", "status" => false];
}

echo json_encode($response);