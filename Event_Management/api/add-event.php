<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$title = $_POST["event_title"];
$category_id = $_POST["event_category_id"];
$description = $_POST["event_description"];
$venus = $_POST["event_venus"];
$start_time = $_POST["event_start_time"];
$end_time = $_POST["event_end_time"];
$capacity = $_POST["event_capacity"];
$register_deadline = $_POST["event_register_deadline"];

$sql_insert = "INSERT INTO events (title,category_id,description,venus,start_time,end_time,capacity,register_deadline)
VALUES (?,?,?,?,?,?,?,?)";

$stmt_insert = mysqli_prepare($conn, $sql_insert);

mysqli_stmt_bind_param(
    $stmt_insert,
    "sissssis",
    $title,
    $category_id,   
    $description,
    $venus,
    $start_time,
    $end_time,
    $capacity,
    $register_deadline
);

if (mysqli_stmt_execute($stmt_insert)) {

    http_response_code(201);
    $response = ["message" => "Event Register Successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to Register Event ", "status" => false];
}

echo json_encode($response);
