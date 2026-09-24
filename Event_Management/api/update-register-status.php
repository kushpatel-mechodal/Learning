<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$status_id = $_POST["id"];
$status = $_POST["status"];

$sql_update = "UPDATE register_events SET status = ? WHERE id=?";

$stmt_update = mysqli_prepare($conn, $sql_update);

mysqli_stmt_bind_param($stmt_update, "si", $status, $status_id);

if (mysqli_stmt_execute($stmt_update)) {

    http_response_code(200);
    $response = ["message" => "Status updated successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to update status", "status" => false];
}

echo json_encode($response);
