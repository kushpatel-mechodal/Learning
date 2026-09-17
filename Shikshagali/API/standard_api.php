<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("./connection.php");

$data = json_decode(file_get_contents("php://input"), true);

$action = $data["action"] ?? '';

if ($action === "create_standard") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $reponse = ["message" => "Only post method can allowed", "status" => false];
        exit;
    }

    $standard_name = $data["standard_name"];
    $sql = "INSERT INTO standards (name) VALUES(?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed To Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $standard_name);

    if (mysqli_stmt_execute($stmt)) {

        $standard_id = mysqli_insert_id($conn);
        http_response_code(201);
        $reponse = ["message" => "standard created Successfully", "status" => true, "standard_id" => $standard_id];
        exit;
    } else {
        http_response_code(500);
        $reponse = ["message" => "Error for Create standard", "status" => false];
        exit;
    }

} else if ($action === "read_standard") {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $reponse = ["message" => "Only get method can allowed", "status" => false];
        exit;
    }

    $sql = "SELECT * FROM standards";

    $id = $data["id"] ?? '';

    if (!empty($id)) {
        $sql = "SELECT * FROM schools where id='$id'";
    } else {
        $sql = "SELECT * FROM schools";
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
            $reponse = ["message" => "Standard Data Fetch Successfully ", "status" => true, "data" => $output];
            exit;
        } else {
            http_response_code(404);
            $reponse = ["message" => "Data Not Found", "status" => false];
            exit;
        }
    } else {
        http_response_code(500);
        $reponse = ["message" => "Failed to Fetch standard data ", "status" => false];
        exit;
    }


} else if ($action === "delete_standard") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $reponse = ["message" => "Only delete method can allowed", "status" => false];
        exit;
    }

    $id = $data["id"];

    $sql = "DELETE FROM standards WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {
            http_response_code(200);
            $reponse = ["message" => "Standard data deleted successfully", "status" => true];
            exit;
        } else {
            http_response_code(404);
            $reponse = ["message" => "Data not found", "status" => false];
            exit;
        }
    } else {
        http_response_code(500);
        $reponse = ["message" => "Failed to delete data", "status" => false];
        exit;
    }
} else if ($action === "update_standard") {

    if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
        $reponse = ["message" => "Only put method can allowed", "status" => false];
        exit;
    }

    $id = $data["id"];
    $standard_name = $data["standard_name"];

    $sql = "UPDATE standards SET name=? where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "si", $standard_name, $id);

    if (mysqli_stmt_execute($stmt)) {
        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {
            http_response_code(200);
            $reponse = ["message" => "Standard Data Update successfully", "status" => true];
        } else {
            http_response_code(404);
            $reponse = ["message" => "Data Not Found", "status" => false];
        }
    } else {
        http_response_code(500);
        $reponse = ["message" => "Standard Data Not Update", "status" => false];
    }
} else {

    http_response_code(400);
    $reponse = ["message" => "Invalid Input", "status" => false];
}

?>