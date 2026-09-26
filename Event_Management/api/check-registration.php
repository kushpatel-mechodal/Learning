<?php

session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET");

require("../config/connection.php");

$user_id = $_SESSION["id"] ?? '';
$event_id = $_POST["event_id"] ?? $_GET["event_id"] ?? '';

if (empty($user_id)) {
    echo json_encode([
        "status" => false,
        "registered" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

if (empty($event_id)) {
    echo json_encode([
        "status" => false,
        "registered" => false,
        "message" => "Event ID is required"
    ]);
    exit;
}

$sql_read = "SELECT id, status FROM register_events WHERE user_id = ? AND event_id = ? AND status != 'rejected'";

$stmt_read = mysqli_prepare($conn, $sql_read);

mysqli_stmt_bind_param($stmt_read, "ii", $user_id, $event_id);

if (mysqli_stmt_execute($stmt_read)) {

    $res_check = mysqli_stmt_get_result($stmt_read);

    if (mysqli_num_rows($res_check) > 0) {
        echo json_encode([
            "status" => true,
            "registered" => true,
            "message" => "You have already registered for this event"
        ]);
        exit;
    } else {
        echo json_encode([
            "status" => true,
            "registered" => false,
            "message" => "Not registered"
        ]);
        exit;
    }
} else {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "registered" => false,
        "message" => "Database error"
    ]);
    exit;
}
