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

// If checking registration status before opening modal
if (isset($_POST["action"]) && $_POST["action"] === "check") {

    $sql_check = "SELECT id, status FROM register_events WHERE user_id = ? AND event_id = ?";

    $stmt_check = mysqli_prepare($conn, $sql_check);

    mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $event_id);

    if (mysqli_stmt_execute($stmt_check)) {

        $res_check = mysqli_stmt_get_result($stmt_check);

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
    }
}

if (empty($phone)) {
    echo json_encode([
        "status" => false,
        "message" => "Phone is required"
    ]);
    exit;
}

// 1. Check event exists & get event capacity
$sql_event = "SELECT capacity, start_time, end_time FROM events WHERE id = ?";

$stmt_event = mysqli_prepare($conn, $sql_event);

mysqli_stmt_bind_param($stmt_event, "i", $event_id);

mysqli_stmt_execute($stmt_event);

$res_event = mysqli_stmt_get_result($stmt_event);

if (mysqli_num_rows($res_event) === 0) {
    echo json_encode([
        "status" => false,
        "message" => "Event not found"
    ]);
    exit;
}

$event_data = mysqli_fetch_assoc($res_event);
$capacity = (int)$event_data["capacity"];

//count total registrations
$sql_count = "SELECT COUNT(*) AS registered_count FROM register_events WHERE event_id = ? AND (status = 'registered' OR status = 'approved' OR status = 'pending')";
$stmt_count = mysqli_prepare($conn, $sql_count);
mysqli_stmt_bind_param($stmt_count, "i", $event_id);
mysqli_stmt_execute($stmt_count);
$res_count = mysqli_stmt_get_result($stmt_count);
$row_count = mysqli_fetch_assoc($res_count);
$registered_count = (int)$row_count["registered_count"];

//Compare register count and capacity 
if ($registered_count >= $capacity) {
    echo json_encode([
        "status" => false,
        "message" => "Event capacity is full"
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
