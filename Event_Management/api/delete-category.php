<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$delete_id = $_POST["id"];

$sql_delete = "DELETE FROM categories WHERE id='$delete_id'";

$stmt_delete = mysqli_prepare($conn, $sql_delete);

if (!$stmt_delete) {
    $response = ["message" => "Failed to prepare", "status" => false];
}

if (mysqli_stmt_execute($stmt_delete)) {

    http_response_code(200);
    $response = ["message" => "Category Deleted successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to delete category", "status" => false];
}

echo json_encode($response);
