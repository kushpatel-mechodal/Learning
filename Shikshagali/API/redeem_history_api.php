<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Auhtorized, X-Requested-With");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';

if ($action === "create_redeem") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    } else {

        $child_id = $_POST["child_id"] ?? '';
        $item_id = $_POST["item_id"] ?? '';

        if (empty($child_id) || empty($item_id)) {
            http_response_code(500);
            $response = ["message" => "All fields required", "status" => false];
        } else {

            $sql = "SELECT reward FROM children WHERE id=?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "i", $child_id);

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {

                $child = mysqli_fetch_assoc($result);

                $sql = "SELECT point, item_name FROM store_items WHERE id=?";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param($stmt, "i", $item_id);

                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {

                    $item = mysqli_fetch_assoc($result);

                    if ($child["reward"] >= $item["point"]) {

                        $insert = "INSERT INTO redeem_history (child_id,item_id,item_name,point,status,created_at) 
                        VALUES(?,?,?,?,'pending',NOW())";

                        $stmt = mysqli_prepare($conn, $insert);

                        mysqli_stmt_bind_param($stmt, "ssss", $child_id, $item_id, $item["item_name"], $item["point"]);

                        if (mysqli_stmt_execute($stmt)) {
                            http_response_code(201);
                            $response = ["message" => "Redeem history created successfully", "status" => true];
                        } else {
                            http_response_code(500);
                            $response = ["message" => "Failed to create Redeem history", "status" => false];
                        }
                    } else {
                        http_response_code(500);
                        $response = ["message" => "Failed to create Redeem history", "status" => false];
                    }
                } else {
                    http_response_code(404);
                    $response = ["message" => "Item reward not found", "status" => false];
                }
            } else {
                http_response_code(404);
                $response = ["message" => "Child reward not found", "status" => false];
            }
        }
    }
} else if ($action === "update_redeem") {


    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $id = $_POST["id"];
    $status = $_POST["status"] ?? '';

    if (empty($id) || empty($status)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {

        $update = "UPDATE redeem_history SET status=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $update);

        mysqli_stmt_bind_param($stmt, "si", $status, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Status update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update status", "status" => false];
        }
    }
} else if ($action === "delete_redeem") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only delete method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';

    $delete = "DELETE FROM redeem_history WHERE id='$id'";

    $stmt = mysqli_prepare($conn, $delete);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "redeem item data deleted", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete redeem item data", "status" => false];
    }
} else if ($action === "read_redeem") {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $response = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';


    if (!empty($id)) {

        $read = "SELECT rh.child_id,rh.item_id,rh.item_name,rh.point,rh.status FROM redeem_history rh
        LEFT JOIN children c ON rh.child_id = c.id
        LEFT JOIN store_items si ON rh.item_id = si.id WHERE rh.id='$id'";

    } else {

        $read = "SELECT rh.child_id,rh.item_id,rh.item_name,rh.point,rh.status FROM redeem_history rh
        LEFT JOIN children c ON rh.child_id = c.id
        LEFT JOIN store_items si ON rh.item_id = si.id";
    }

    $stmt = mysqli_prepare($conn, $read);

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "Redeem item data fetch successfully", "status" => true, "data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch redeem item data", "status" => false];
    }
}

echo json_encode($response);
?>