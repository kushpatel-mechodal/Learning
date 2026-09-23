<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$title = $_POST["event_title"] ?? '';
$category_id = $_POST["event_category_id"] ?? '';
$description = $_POST["event_description"] ?? '';
$venus = $_POST["event_venus"] ?? '';
$start_time = $_POST["event_start_time"] ?? '';
$end_time = $_POST["event_end_time"] ?? '';
$capacity = $_POST["event_capacity"] ?? '';
$register_deadline = $_POST["event_register_deadline"] ?? '';
$image = $_FILES["image"] ?? null;

$upload_path = "";

if (!empty($image) && isset($image["name"]) && !empty($image["tmp_name"]) && $image["error"] === UPLOAD_ERR_OK) {
    $upload_dir = "./upload/event_images/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $orignal_name = pathinfo($image["name"], PATHINFO_FILENAME);
    $orignal_name = preg_replace("/[^A-Za-z0-9_-]/", "_", $orignal_name);

    $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
    $file_name = "event_" . time() . "_" . $orignal_name . "." . $ext;
    $upload_path = $upload_dir . $file_name;

    if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
        http_response_code(500);
        $response = ["message" => "Failed to upload event image", "status" => false];
        echo json_encode($response);
        exit;
    }
}

$sql_insert = "INSERT INTO events (title,category_id,description,venus,start_time,end_time,capacity,register_deadline,image)
VALUES (?,?,?,?,?,?,?,?,?)";

$stmt_insert = mysqli_prepare($conn, $sql_insert);

mysqli_stmt_bind_param(
    $stmt_insert,
    "sissssiss",
    $title,
    $category_id,
    $description,
    $venus,
    $start_time,
    $end_time,
    $capacity,
    $register_deadline,
    $upload_path
);

if (mysqli_stmt_execute($stmt_insert)) {
    http_response_code(201);
    $response = ["message" => "Event Register Successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to Register Event", "status" => false, "error" => mysqli_error($conn)];
}

echo json_encode($response);
