<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");

require("../config/connection.php");

$response = [];
$output = [];

$sql_read = "SELECT re.*,s.name,s.email,e.title AS event_title FROM register_events re
LEFT JOIN users s ON re.user_id = s.id
LEFT JOIN events e ON re.event_id = e.id";

$stmt_read = mysqli_prepare($conn, $sql_read);

if (mysqli_stmt_execute($stmt_read)) {

    $result = mysqli_stmt_get_result($stmt_read);

    if (mysqli_num_rows($result) > 0) {

        while ($rows = mysqli_fetch_assoc($result)) {
            $output[] = $rows;

            http_response_code(200);
            $response = ["message" => "Registration data fetch successfully", "status" => true, "data" => $output];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch registration data", "status" => false, "data" => []];
    }
}

echo json_encode($response);
