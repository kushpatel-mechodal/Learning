<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-with");

require("./connection.php");

$response = [];
$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? '';

if ($action === "create_profile") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {

        $response = ["message" => "Only Put method can allowed", "status" => false];
    }

    $name = $data["name"];
    $mobile = $data["mobile"];
    $email = $data["email"];
    $password = password_hash($data["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO parents (name,mobile,email,password) VALUES (?,?,?,?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed To Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ssss", $name, $mobile, $email, $password);

    if (mysqli_stmt_execute($stmt)) {

        $id = mysqli_insert_id($conn);
        http_response_code(201);
        $reponse = ["message" => "User Account Created Successfully", "status" => true, "parent_id" => $id];
    } else {
        http_response_code(500);
        $reponse = ["message" => "User Account Created Successfully", "status" => false];
    }
} else if ($action === "edit_profile") {

    header("Access-Control-Allow-Methods: POST,OPTIONS");

    if ($_SERVER["REQUEST_METHOD"] !== "PUT") {

        $response = ["message" => "Only Put method can allowed", "status" => false];
    }

    $id = $data["id"];
    $name = $data["name"];
    $mobile = $data["mobile"];
    $email = $data["email"];

    $sql = "UPDATE parents SET name=?,mobile=?,email=? WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed to prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sssi", $name, $mobile, $email, $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Parents profile Update Successfully", "status" => true];
        } else {
            http_response_code(200);
            $response = ["message" => "Data Not Found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to Update parents profile", "status" => false];
    }
}

echo json_encode($response);

?>