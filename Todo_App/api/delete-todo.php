<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require("../config/connection.php");

$response = [];
$delete_id = $_POST['id'];


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $response = ["message" => "Only delete method can allowed", "status" => false];
    exit;
}

$sql_delete = "DELETE FROM todos WHERE id='$delete_id'";

$stmt_delete = mysqli_prepare($conn, $sql_delete);

if (mysqli_stmt_execute($stmt_delete)) {

    $affected_row = mysqli_affected_rows($conn);

    if ($affected_row > 0) {

        http_response_code(200);
        $response = ["message" => "Todo deleted successfully", "status" => true];
    } else {
        http_response_code(404);
        $response = ["message" => "Todo not found", "status" => false];
    }
} else {
    http_response_code(500);
    $response = ["message" => "Failed to delete todo", "status" => false];
}

echo json_encode($response);
