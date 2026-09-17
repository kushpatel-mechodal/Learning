<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorized,X-requested-With");

require("./connection.php");
mysqli_set_charset($conn, "utf8mb4");

$response = [];
$action = $_GET["action"] ?? $_POST["action"] ?? '';

if ($action === "create_campaign") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only Post method can allowed", "status" => false];
    }

    $campaign_title = $_POST["campaign_title"] ?? '';
    $description = $_POST["description"] ?? '';
    $start_date = $_POST["start_date"] ?? '';
    $end_date = $_POST["end_date"] ?? '';
    $message = $_POST["message"] ?? '';
    $image = $_FILES["image"] ?? '';
    $link = $_POST["link"] ?? '';
    print_r(empty($campaign_title));

    if (empty($campaign_title) || empty($description) || empty($start_date) || empty($end_date) || empty($message) || empty($link)) {
        $response = ["message" => "All fields are Required", "status" => false];

    } else {
        $upload_dir = "./upload/children/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $countquery = mysqli_query($conn, "SELECT COUNT(*) AS total_row FROM campaigns");
        $countrow = mysqli_fetch_assoc($countquery);
        $uniqueid = $countrow["total_row"] + 1;

        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        $file_name = "campaign_" . $uniqueid . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($image["tmp_name"], $upload_path)) {

            http_response_code(500);
            $response = ["message" => "Failed to upload photo", "status" => false];
        }

        $sql = "INSERT INTO campaigns (campaign_title,image,description,start_date,end_date,message,redirection_link) VALUES(?,?,?,?,?,?,?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $response = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "sssssss", $campaign_title, $upload_path, $description, $start_date, $end_date, $message, $link);

        if (mysqli_stmt_execute($stmt)) {

            http_response_code(201);
            $response = ["message" => "Campaign Create Successsfully", "status" => true];

        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create campaign", "status" => false];

        }
    }
} else if ($action === "update_campaign") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only put method can allowed", "status" => false];
    }

    $id = $_POST["id"] ?? '';
    $campaign_title = $_POST["campaign_title"] ?? '';
    $description = $_POST["description"] ?? '';
    $start_date = $_POST["start_date"] ?? '';
    $end_date = $_POST["end_date"] ?? '';
    $message = $_POST["message"] ?? '';
    $image = $_FILES["image"] ?? '';
    $link = $_POST["link"] ?? '';

    if (empty($id) || empty($campaign_title) || empty($description) || empty($start_date) || empty($end_date) || empty($message)) {
        $response = ["message" => "All fields are Required", "status" => false];
    } else {
        $upload_dir = "./upload/children/";

        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        $file_name = "campaign_" . $id . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        $sql = "UPDATE campaigns SET campaign_title=?,image=?,description=?,start_date=?,end_date=?,message=?,redirection_link=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $reponse = ["message" => "Failed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "sssssssi", $campaign_title, $upload_path, $description, $start_date, $end_date, $message, $link, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_stmt_affected_rows($stmt);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Campaign date update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update campaign", "status" => false];
        }
    }
} else if ($action === "read_campaign") {

    if ($_SERVER["REQUEST_METHOD"] !== "GET") {
        $response = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_GET["id"] ?? '';

    if (!empty($id)) {
        $sql = "SELECT * From campaigns WHERE id='$id'";
    } else {
        $sql = "SELECT * From campaigns";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $reponse = ["message" => "Failed to prepare", "status" => false];
    }

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "Campaign data fetch successfully", "status" => true, "campaign_data" => $output];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch the campaign data", "status" => false];
    }
} else if ($action === "delete_campaign") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only delete method can allowed", "status" => false];
    }

    $id = $_POST["id"];

    $sql = "DELETE FROM campaigns WHERE id = '$id'";

    $stmt = mysqli_prepare($conn, $sql);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_stmt_affected_rows($stmt);

        if ($affected_row > 0) {

            http_response_code(200);
            $response = ["message" => "Campaign data deleted successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to delete the campaign data", "status" => false];
    }


} else {
    http_response_code(400);
    $response = ["message" => "Invalid Inputs", "status" => false];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

?>