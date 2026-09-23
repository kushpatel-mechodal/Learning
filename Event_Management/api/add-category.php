<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$category_name = $_POST["category_name"] ?? $_POST["name"] ?? '';
$category_status = $_POST["category_status"] ?? $_POST["status"] ?? '';

if (empty(trim($category_name))) {
    http_response_code(400);
    echo json_encode(["message" => "Category name is required", "status" => false]);
    exit;
}

$sql_insert = "INSERT INTO categories (category_name, status) VALUES (?, ?)";
$stmt_insert = mysqli_prepare($conn, $sql_insert);

if (!$stmt_insert) {
    http_response_code(500);
    echo json_encode(["message" => "Failed to prepare query", "status" => false]);
    exit;
}

mysqli_stmt_bind_param($stmt_insert, "ss", $category_name, $category_status);

if (mysqli_stmt_execute($stmt_insert)) {
    http_response_code(201);
    $response = ["message" => "Category Created Successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to create category", "status" => false, "error" => mysqli_error($conn)];
}

echo json_encode($response);

