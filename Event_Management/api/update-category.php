<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$update_id = $_POST["id"];
$update_category_name = $_POST["category_name"];
$update_category_status = $_POST["category_status"];

$sql_update = "UPDATE categories SET category_name='$update_category_name',status='$update_category_status' 
WHERE id='$update_id'";

$stmt_update = mysqli_prepare($conn, $sql_update);

if (mysqli_stmt_execute($stmt_update)) {

    http_response_code(200);
    $response = ["message" => "Category data upadated successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to update Category data", "status" => false];
}

echo json_encode($response);
