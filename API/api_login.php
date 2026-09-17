<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("message" => "Only Post method allowed", "status" => false));
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["eemail"];
$password = $data["epassword"];

$sql = "SELECT id,username,email,password,phone,salary,role FROM employees WHERE email=?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(array("message" => "Failed prepare", "status" => false));
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$rows = mysqli_fetch_assoc($result);

if ($rows && password_verify($password, $rows["password"])) {
    http_response_code(200);

    echo json_encode(array(
        "message" => "Employee Login Successfully",
        "status" => true,
        "data" => [
            "id" => $rows['id'],
            "username" => $rows['username'],
            "email" => $rows['email'],
            "phone" => $rows['phone'],
            "salary" => $rows['salary'],
            "role" => $rows['role'],
        ]
    ));
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Invalid Email or password", "status" => false));
}

?>