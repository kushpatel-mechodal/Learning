<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Method,Authorization, X-Request-With');

require_once __DIR__ . "/connection.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['eusername'];
$email = $data['eemail'];
$password = password_hash($data['epassword'], PASSWORD_DEFAULT);
$phone = $data['ephone'];
$salary = $data['esalary'];
$role = $data['erole'];

$sql = "INSERT INTO employees (username,email,password,phone,salary,role) VALUES (?,?,?,?,?,?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(array("message" => "Failed prepare","status"=> false));
    exit;
}

mysqli_stmt_bind_param($stmt, "ssssds", $username, $email, $password, $phone, $salary, $role);

if (mysqli_stmt_execute($stmt)) {

    http_response_code(201);
    echo json_encode(array('message' => 'Employeee Data Inserted Successfully', 'status' => true));

} else {

    http_response_code(500);
    echo json_encode(array('message' => 'Employeee Data Not Inserted Successfully', 'status' => false));
}
?>