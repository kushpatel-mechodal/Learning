<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$name = $_POST["user_name"];
$email = $_POST["user_email"];
$password = password_hash($_POST["user_password"], PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO users (name,email,password) VALUES(?,?,?)";

$stmt_insert = mysqli_prepare($conn, $sql_insert);

mysqli_stmt_bind_param($stmt_insert, "sss", $name, $email, $password);

if (mysqli_stmt_execute($stmt_insert)) {

    http_response_code(201);
    $response = ["message" => "User register Successfully", "status" => true];
    
} else {
    http_response_code(500);
    $response = ["message" => "Failed to register", "status" => false];
}

echo json_encode($response);
