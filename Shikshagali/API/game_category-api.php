<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorized,X-requested-With");

require("./connection.php");

$response = [];
$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? '';

if ($action === "create_category") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $category_name = $data["category_name"] ?? '';

    $sql = "INSERT INTO game_category (category_name) VALUES(?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $response = ["message" => "Failed to Prepare", "status" => false];
    }

    mysqli_stmt_bind_param($stmt, "s", $category_name);

    if (mysqli_stmt_execute($stmt)) {

        http_response_code(201);
        $response = ["message" => "Game category create successfully", "status" => true];
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to create game category", "status" => false];
    }
} else if ($action === "update_category") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $id = $data["id"] ?? '';
    $category_name = $data["category_name"] ?? '';

    $sql = "UPDATE game_category SET category_name=? WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $category_name, $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Game category update successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to update game category", "status" => false];
    }
} else if ($action === "read_category") {

    $id = $data["id"] ?? '';

    if (!empty($id)) {

        $sql = "SELECT * FROM game_category WHERE id='$id'";

    } else {
        $sql = "SELECT * FROM game_category";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $response = ["message" => "Failed to prepare", "status" => false];
    }

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "All data fetch successsfully", "status" => true, "data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch the data", "status" => false];
    }
} else if ($action === "delete_category") {
    $id = $data["id"] ?? '';

    $sql = "DELETE FROM game_category WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Game category data delete successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }

    } else {

        http_response_code(500);
        $response = ["message" => "Failed to delete data", "status" => false];
    }
}

echo json_encode($response);
?>