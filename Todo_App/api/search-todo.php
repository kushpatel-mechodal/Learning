<?php

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require("../config/connection.php");

$output = "";
$response = [];

$search_val = $_GET["search"] ?? '';

$sql_search = "SELECT id,title,description FROM todos WHERE title LIKE '%{$search_val}%' OR description LIKE '%{$search_val}%'";

$stmt_search = mysqli_prepare($conn, $sql_search);

if (mysqli_stmt_execute($stmt_search)) {

    $res_search = mysqli_stmt_get_result($stmt_search);

    if (mysqli_num_rows($res_search) > 0) {

        $output = [];
        while ($rows = mysqli_fetch_assoc($res_search)) {

            $output[] = $rows;
        }

        http_response_code(200);
        $response = ["message" => "Todo search successfully", "status" => true, "data" => $output];
    } else {
        http_response_code(200);
        $response = ["message" => "Todo not Found", "status" => true, "data" => []];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to search todo", "status" => false];
}

echo json_encode($response);
