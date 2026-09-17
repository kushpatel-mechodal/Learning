<?php

header('Content-type: application/json');
header('Access-control-allow-origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorized, X-Requested-With');

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(array("message" => "Only POST Mehtod can allowed", "status" => false));
    exit;
}

$search = $data["search"];

require_once __DIR__ . "/connection.php";

$sql = "SELECT id,username,email,phone,salary,reg_date,role FROM employees where username LIKE '%{$search}%' ";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {

    http_response_code(200);
    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($output);

} else {
    http_response_code(404);
    echo json_encode(array('message' => 'No Record Found', 'Status' => false));
}

?>