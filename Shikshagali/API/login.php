<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("./connection.php");

$data = json_decode(file_get_contents("php://input"), true);
$reponse = [];

$mobile = $data["mobile"];
$password = $data["password"];

$sql = "SELECT p.id,p.name,p.mobile,p.email,p.password from parents p where p.mobile=?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Failed to prepare" . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $mobile);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$rows = mysqli_fetch_assoc($result);

if ($rows && password_verify($password, $rows["password"])) {

    $parent_id = $rows["id"];

    $sql = "SELECT c.id,c.child_name,c.gender,c.school_name,p.name,s.state_name,d.district_name,t.taluka_name FROM children c 
    LEFT JOIN parents p ON c.parent_id = p.id
    LEFT JOIN states s ON c.state_id = s.id
    LEFT JOIN districts d ON c.district_id = d.id
    LEFT JOIN talukas t ON c.taluka_id = t.id WHERE c.parent_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $parent_id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $output = mysqli_fetch_all($result, MYSQLI_ASSOC);

    http_response_code(200);
    $response = [
        "message" => "User Login Successfully",
        "status" => true,
        "data" => [
            "name" => $rows["name"],
            "mobile" => $rows["mobile"],
            "email" => $rows["email"],
            "children" => $output
        ]
    ];
} else {
    http_response_code(404);
    $response = ["message" => "User Not found", "status" => false];
}

echo json_encode($response);
?>