<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response = ["message" => "Only get method can allowed", "status" => false];
    exit;
}

$todo_title = $_POST["title"];
$todo_description = $_POST["description"];

$sql_insert = "INSERT INTO todos (title,description) VALUES(?,?)";

$stmt_insert = mysqli_prepare($conn, $sql_insert);

mysqli_stmt_bind_param($stmt_insert, "ss", $todo_title, $todo_description);

if (mysqli_stmt_execute($stmt_insert)) {

    http_response_code(201);
    $response = ["message" => "Todo Add successfully", "status" => true];
    // exit;
} else {
    http_response_code(500);
    $response = ["message" => "Failed to create Todo", "status" => false];
    // exit;
}

echo json_encode($response);
