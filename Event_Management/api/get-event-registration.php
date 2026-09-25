<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require("../config/connection.php");

$event_id = $_GET["event_id"];

$output = [];
$response = [];

$sql_read = "SELECT re.id,re.phone,re.status,u.name,u.email FROM register_events re
LEFT JOIN users u ON re.user_id = u.id WHERE re.event_id = '$event_id'";

$stmt_read = mysqli_prepare($conn, $sql_read);

if (mysqli_stmt_execute($stmt_read)) {

    $result = mysqli_stmt_get_result($stmt_read);

    if (mysqli_num_rows($result)) {

        while ($rows = mysqli_fetch_assoc($result)) {

            $output[] = $rows;

            http_response_code(200);
            $response = ["message" => "Event Details fetch successfully", "status" => true, "data" => $output];
        }
    } else {
        http_response_code(404);
        $response = ["message" => "Data not found", "status" => false, "data" => []];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to fetch data", "status" => false];
}

echo json_encode($response);
