<?php

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require("../config/connection.php");

$response = [];

$id = $_POST["id"];
$status = $_POST["status"];

$sql_status = "UPDATE todos SET status = '$status' WHERE id = '$id'";

$stmt_status = mysqli_prepare($conn, $sql_status);

if (mysqli_stmt_execute($stmt_status)) {

    http_response_code(200);
    $response = ["message" => "Todo status change successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to change status", "status" => false];
}

echo json_encode($response);
?>