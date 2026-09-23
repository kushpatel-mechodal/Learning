<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

$update_id        = $_POST["event_id"] ?? '';
$title            = $_POST["event_title"] ?? '';
$category_id      = $_POST["event_category_id"] ?? '';
$description      = $_POST["event_description"] ?? '';
$venus            = $_POST["event_venus"] ?? '';
$start_time       = $_POST["event_start_time"] ?? '';
$end_time         = $_POST["event_end_time"] ?? '';
$capacity         = $_POST["event_capacity"] ?? '';
$register_deadline = $_POST["event_register_deadline"] ?? '';
$image            = $_FILES["image"] ?? null;

if (empty($update_id)) {
    http_response_code(400);
    $response = ["message" => "Event ID is required", "status" => false];
    echo json_encode($response);
    exit;
}

// Image upload 
$upload_path = null;
$sql_update = "UPDATE events SET title=?, category_id=?, description=?, venus=?, start_time=?, end_time=?, capacity=?, register_deadline=?";

if (!empty($image) && isset($image["name"])) {

    $upload_dir = "./upload/event_images/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $orignal_name = pathinfo($image["name"], PATHINFO_FILENAME);
    $orignal_name = preg_replace("/[^A-Za-z0-9_-]/", "_", $orignal_name);

    $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
    $file_name = "event_" . time() . "_" . $orignal_name . "." . $ext;
    $upload_path = $upload_dir . $file_name;

    $sql_update .= " ,image=? WHERE id=?";

    $stmt_update = mysqli_prepare($conn, $sql_update);

    mysqli_stmt_bind_param($stmt_update, "sissssissi", $title, $category_id, $description, $venus, $start_time, $end_time, $capacity, $register_deadline,$upload_path, $update_id);

    if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
        http_response_code(500);
        $response = ["message" => "Failed to upload event image", "status" => false];
        echo json_encode($response);
        exit;
    }
} else {
    $sql_update .= " WHERE id=?";

    $stmt_update = mysqli_prepare($conn, $sql_update);

    mysqli_stmt_bind_param($stmt_update, "sissssisi", $title, $category_id, $description, $venus, $start_time, $end_time, $capacity, $register_deadline, $update_id);
}

// if image is updated then execute the if condition otherwise execute else condition

if (mysqli_stmt_execute($stmt_update)) {
    http_response_code(200);
    $response = ["message" => "Event updated successfully", "status" => true];
} else {
    http_response_code(500);
    $response = ["message" => "Failed to update event", "status" => false, "error" => mysqli_error($conn)];
}

echo json_encode($response);
