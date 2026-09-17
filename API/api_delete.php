<?php

header('Content-type: application/json');
header('Access-control-allow-origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorized, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
    echo json_encode(array("message" => "Only DELETE Mehtod can allowed", "status" => false));
    exit;
}

$emp_id = $data["eid"];

require_once __DIR__ . "/connection.php";

$sql = "DELETE FROM employees where id = {$emp_id}";

$stmt = mysqli_prepare($conn, $sql);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_stmt_execute($stmt)) {

    $affected_row = mysqli_stmt_affected_rows($stmt);

    if ($affected_row > 0) {
        http_response_code(200);
        echo json_encode(array('message' => 'Employee Record Deleted ', 'Status' => true));
    } else {
        http_response_code(404);
        echo json_encode(array('message' => 'Employee Record Not Found ', 'Status' => false));
    }
} else {
    http_response_code(500);
    echo json_encode(array('message' => 'Employee Record not Delete', 'Status' => false));
}

?>