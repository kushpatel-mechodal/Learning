<?php

session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");

require("../config/connection.php");

$user_id = $_SESSION["id"] ?? '';
$event_id = $_POST["event_id"] ?? '';
$phone = $_POST["phone"] ?? '';

if (empty($user_id)) {
    echo json_encode([
        "status" => false,
        "message" => "User ID not found in session",
        "session" => $_SESSION
    ]);
    exit;
}

if (empty($event_id)) {
    echo json_encode([
        "status" => false,
        "message" => "Event ID is required"
    ]);
    exit;
}

if (empty($phone)) {
    echo json_encode([
        "status" => false,
        "message" => "Phone is required"
    ]);
    exit;
}


$sql_insert = "INSERT INTO register_events (event_id,user_id,phone,status) VALUES (?,?,?,'pending')";

$stmt_insert = mysqli_prepare($conn, $sql_insert);

mysqli_stmt_bind_param($stmt_insert, "iis", $event_id, $user_id, $phone);

if (mysqli_stmt_execute($stmt_insert)) {

    http_response_code(201);
    $response = ["message" => "Event registered successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to Event registered", "status" => false];
}

echo json_encode($response);
