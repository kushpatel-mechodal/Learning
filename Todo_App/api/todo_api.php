<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("../config/connection.php");

$response = [];

//get todo data

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $id = $_GET["id"] ?? '';

    if (empty($id)) {

        $response = ["message" => "ID is required ", "status" => false];
    } else {

        $sql_edit = "SELECT * FROM todos WHERE id='$id'";

        $stmt_edit = mysqli_prepare($conn, $sql_edit);

        if (mysqli_stmt_execute($stmt_edit)) {

            $res_edit = mysqli_stmt_get_result($stmt_edit);

            if (mysqli_num_rows($res_edit) > 0) {

                $data = mysqli_fetch_assoc($res_edit);

                http_response_code(200);
                $response = ["message" => "Todo data fetch successfully ", "status" => true, "data" => $data];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found ", "status" => false];
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //add and update todo
    $id = $_POST["id"];
    $title = $_POST["title"];
    $description = $_POST["description"];

    //update todo
    if (!empty($id)) {

        $sql_update = "UPDATE todos SET title='$title',description='$description'
        WHERE id='$id'";

        $stmt_update = mysqli_prepare($conn, $sql_update);

        if (mysqli_stmt_execute($stmt_update)) {

            http_response_code(200);
            $response = ["message" => "Todo data updated successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update data ", "status" => false];
        }
    } else {
        $sql_insert = "INSERT INTO todos (title,description) VALUES(?,?)";

        $stmt_insert = mysqli_prepare($conn, $sql_insert);

        mysqli_stmt_bind_param($stmt_insert, "ss", $title, $description);

        if (mysqli_stmt_execute($stmt_insert)) {

            http_response_code(200);
            $response = ["message" => "Todo data inserted successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to insert data ", "status" => false];
        }
    }
}
echo json_encode($response);
