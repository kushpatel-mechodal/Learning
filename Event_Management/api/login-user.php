<?php

session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$email = $_POST["user_email"];
$password = $_POST["user_password"];

$sql_login = "SELECT id,email,password,role FROM users WHERE email='$email'";

$stmt_login = mysqli_prepare($conn, $sql_login);

mysqli_stmt_execute($stmt_login);

$result = mysqli_stmt_get_result($stmt_login);

$rows = mysqli_fetch_assoc($result);

if ($rows && password_verify($password, $rows["password"])) {

    if ($rows["role"] !== "admin") {
        http_response_code(403);
        $response = ["message" => "Access Denied only admin can access", "status" => false];
        
    } else {
        $_SESSION["id"] = $rows["id"];
        $_SESSION["email"] = $rows["email"];
        $_SESSION["role"] = $rows["role"];

        http_response_code(200);
        $response = ["message" => "Login successfully", "status" => true];
    }
} else {

    http_response_code(401);
    $response = ["message" => "Invalid email or password", "status" => false];
}

echo json_encode($response);
