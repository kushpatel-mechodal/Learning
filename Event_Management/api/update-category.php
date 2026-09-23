<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$update_id = $_POST["id"] ?? $_POST["category_id"] ?? '';
$update_category_name = $_POST["category_name"] ?? $_POST["name"] ?? '';
$update_category_status = $_POST["category_status"] ?? $_POST["status"] ?? '';

if (empty($update_id)) {
    http_response_code(400);
    echo json_encode(["message" => "Category ID is required", "status" => false]);
    exit;
}

if (empty(trim($update_category_name))) {
    http_response_code(400);
    echo json_encode(["message" => "Category name is required", "status" => false]);
    exit;
}

$sql_update = "UPDATE categories SET category_name = ?, status = ? WHERE id = ?";
$stmt_update = mysqli_prepare($conn, $sql_update);

if (!$stmt_update) {
    http_response_code(500);
    echo json_encode(["message" => "Failed to prepare query", "status" => false]);
    exit;
}

mysqli_stmt_bind_param($stmt_update, "ssi", $update_category_name, $update_category_status, $update_id);

if (mysqli_stmt_execute($stmt_update)) {
    http_response_code(200);
    $response = ["message" => "Category updated successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to update category data", "status" => false, "error" => mysqli_error($conn)];
}

echo json_encode($response);

