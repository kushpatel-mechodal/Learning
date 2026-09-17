<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require_once __DIR__ . "/connection.php";

if($_SERVER["REQUEST_METHOD"]!=="PUT"){
    echo json_encode(array("message" => "Only Put method can allowed", "status" => false));
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["eid"];
$username = $data["eusername"];
$email = $data["eemail"];
$password = password_hash($data["epassword"], PASSWORD_DEFAULT);
$phone = $data["ephone"];
$salary = $data["esalary"];
$role = $data["erole"];

$sql = "UPDATE employees SET username=?,email=?,password=?,phone=?,salary=?,role=? WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(array("message" => "Failed Prepare", "status" => false));
    exit;
}

mysqli_stmt_bind_param($stmt, "ssssdsi", $username, $email, $password, $phone, $salary, $role, $id);

if (mysqli_stmt_execute($stmt)) {

    http_response_code(200);
    echo json_encode(array("message" => "Employee Record Updated", "status" => true));

} else {
    http_response_code(500);
    echo json_encode(array("message" => "Employee Record Not Updated", "status" => false));
}

mysqli_stmt_close($stmt);


?>