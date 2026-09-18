<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$update_id = $_POST["id"];
$title = $_POST["update_title"];
$description = $_POST["update_description"];
$start_time = $_POST["update_start_time"];
$end_time = $_POST["update_end_time"];
$venus = $_POST["update_venus"];
$capacity = $_POST["update_capacity"];
$register_deadline = $_POST["update_register_deadline"];

$sql_update = "UPDATE events SET title='$title', description = '$description', start_time = '$start_time',
end_time = '$end_time',venus = '$venus', capacity = '$capacity', register_deadline = '$register_deadline'
WHERE id='$update_id'";

$stmt_update = mysqli_prepare($conn, $sql_update);

if (mysqli_stmt_execute($stmt_update)) {

    http_response_code(200);
    $response = ["message" => "Event update successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to update event", "status" => false];
}

echo json_encode($response);