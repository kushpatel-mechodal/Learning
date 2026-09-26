<?php

session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require("../config/connection.php");

$user_id = $_SESSION["id"] ?? '';

$response = [];

if (empty($user_id)) {

    $response = ["message" => "User id required", "status" => false];
    exit;
}

$sql_user = "SELECT re.id, re.id AS registration_id, re.event_id, e.title, re.phone, re.status, e.venus, e.start_time, e.end_time, e.event_date, e.description, e.image 
FROM register_events re 
LEFT JOIN events e ON re.event_id = e.id
WHERE re.user_id = ? ORDER BY re.id DESC";

$stmt_user = mysqli_prepare($conn, $sql_user);
mysqli_stmt_bind_param($stmt_user, "i", $user_id);

if (mysqli_stmt_execute($stmt_user)) {

    $result = mysqli_stmt_get_result($stmt_user);

    if (mysqli_num_rows($result) > 0) {

        $data = [];

        while ($rows = mysqli_fetch_assoc($result)) {
            $data[] = $rows;
        }

        http_response_code(200);
        $response = ["message" => "User registration fetch successfully", "status" => true, "data" => $data];
    } else {
        http_response_code(404);
        $response = ["message" => "Data not found", "status" => false, "data" => []];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to fetch registration data", "status" => false];
}

echo json_encode($response);
