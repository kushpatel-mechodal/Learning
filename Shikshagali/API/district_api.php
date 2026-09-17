<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("./connection.php");

$data = json_decode(file_get_contents("php://input"), true);

$action = $data["action"] ?? '';
$response = [];

if ($action === "create_district") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
        exit;
    }

    $state_id = $data["state_id"];
    $district_name = $data["district_name"];

    $sql = "INSERT INTO districts (state_id,district_name) VALUES(?,?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed To Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "is", $state_id, $district_name);

    if ($stmt) {

        $district_id = mysqli_insert_id($conn);
        http_response_code(201);
        $response = ["message" => "District created Successfully", "status" => true, "district_id" => $district_id];
        exit;
    } else {
        http_response_code(500);
        $response = ["message" => "Error for Create District", "status" => false];
        exit;
    }

} else if ($action === "read_district") {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $response = ["message" => "Only get method can allowed", "status" => false];
        exit;
    }

    $id = $data["id"] ?? '';

    if (!empty($id)) {
        $sql = "SELECT * FROM districts where id='$id'";
    } else {
        $sql = "SELECT * FROM districts";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed To Prepare" . mysqli_error($conn));
    }

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "District Data Fetch Successfully ", "status" => true, "data" => $output];
            exit;
        } else {
            http_response_code(404);
            $response = ["message" => "Data Not Found", "status" => false];
            exit;
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to Fetch District data ", "status" => false];
        exit;
    }

} else if ($action === "delete_district") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only delete method can allowed", "status" => false];
        exit;
    }

    $id = $data["id"];

    $sql = "DELETE FROM districts WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "District data deleted successfully", "status" => true];
            exit;
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
            exit;
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete data", "status" => false];
        exit;
    }
} else if ($action === "update_district") {

    if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
        $response = ["message" => "Only put method can allowed", "status" => false];
        exit;
    }

    $id = $data["id"];
    $state_id = $data["state_id"];
    $district_name = $data["district_name"];

    $sql = "UPDATE districts SET state_id=?,district_name=? where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "isi", $state_id, $district_name, $id);

    if (mysqli_stmt_execute($stmt)) {
        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "District Data Update successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data Not Found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "District Data Not Update", "status" => false];
    }
} else {

    http_response_code(400);
    $response = ["message" => "Invalid Input", "status" => false];
}

?>