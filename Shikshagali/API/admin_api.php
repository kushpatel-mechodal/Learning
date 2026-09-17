<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Auhtorized, X-Requested-With");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';

if ($action === "approve_redeem") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "only post method can allowed", "status" => false];
    } else {

        $id = $_POST["id"] ?? '';
        $status = $_POST["status"] ?? '';

        if (empty($id)) {

            http_response_code(500);
            $response = ["message" => "All fields are required", "status" => false];
        } else {

            $sql = "SELECT child_id,item_id,point,status FROM redeem_history WHERE id=?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "i", $id);

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {

                $redeem = mysqli_fetch_assoc($result);
                print_r($redeem);

                if ($redeem["status"] !== "pending") {
                    $response = ["message" => "Reward is already redeemed", "status" => false];
                } else {

                    if ($status === "approve") {

                        $sql = "UPDATE children SET reward = reward - ? WHERE id=?";

                        $stmt = mysqli_prepare($conn, $sql);

                        mysqli_stmt_bind_param($stmt, "ii", $redeem["point"], $redeem["child_id"]);

                        if (mysqli_stmt_execute($stmt)) {

                            $sql = "UPDATE redeem_history SET status = 'approved' WHERE id=?";

                            $stmt = mysqli_prepare($conn, $sql);

                            mysqli_stmt_bind_param($stmt, "i", $id);

                            if (mysqli_stmt_execute($stmt)) {
                                http_response_code(200);
                                $response = ["message" => "Redeem point deducted", "status" => true];
                            } else {
                                http_response_code(500);
                                $response = ["message" => "Redeem point not deducted", "status" => false];
                            }
                        } else {
                            http_response_code(400);
                            $response = ["message" => " Failed to redeem point", "status" => false];
                        }
                    } else if ($status === "reject") {

                        $sql = "UPDATE redeem_history SET status = 'rejected' WHERE id=?";

                        $stmt = mysqli_prepare($conn, $sql);

                        mysqli_stmt_bind_param($stmt, "i", $id);

                        if (mysqli_stmt_execute($stmt)) {

                            http_response_code(200);
                            $response = ["message" => "Redeem Rejected Successfully", "status" => true];
                        } else {
                            http_response_code(500);
                            $response = ["message" => "Failed to reject redeem", "status" => false];
                        }
                    }
                }
            }
        }
    }
}

echo json_encode($response);

?>