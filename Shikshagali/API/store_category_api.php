<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Auhtorized, X-Requested-With");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';

if ($action === "create_category") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $category_name = $_POST["category_name"] ?? '';

    if (empty($category_name)) {
        http_response_code(500);
        $response = ["message" => "Category name required", "status" => false];
    } else {

        $insert = "INSERT INTO store_category (category_name) VALUES(?)";

        $stmt = mysqli_prepare($conn, $insert);

        if (!$stmt) {
            $response = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "s", $category_name);

        if (mysqli_stmt_execute($stmt)) {
            http_response_code(201);
            $response = ["message" => "Store category created successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create store category", "status" => false];
        }
    }
} elseif ($action === "update_category") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $id = $_POST["id"];
    $category_name = $_POST["category_name"] ?? '';

    if (empty($category_name)) {
        http_response_code(500);
        $response = ["message" => "Category name required", "status" => false];
    } else {

        $update = "UPDATE store_category SET category_name=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $update);

        if (!$stmt) {
            $response = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "si", $category_name, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Category update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update category", "status" => false];
        }
    }
} else if ($action === "read_category") {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $response = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';

    if (!empty($id)) {
        $read = "SELECT * FROM store_category WHERE id='$id'";
    } else {
        $read = "SELECT * FROM store_category";
    }

    $stmt = mysqli_prepare($conn, $read);

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "Category data fetch successfully", "status" => true, "category_data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed To fetch data", "status" => false];
    }
} else if ($action === "delete_category") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only delete method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';

    $delete = "DELETE FROM store_category WHERE id='$id'";

    $stmt = mysqli_prepare($conn, $delete);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Category delete successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete category", "status" => false];
    }

}
echo json_encode($response);
?>