<?php

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized, X-Requested-With");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? $_GET["action"] ?? '';

if ($action === "create_subject") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $name = $_POST["name"];
    $gujrati_name = $_POST["gujrati_name"];
    $english_name = $_POST["english_name"];
    $standard = $_POST["standard"];
    $image = $_FILES["image"];

    if (empty($name) || empty($gujrati_name) || empty($english_name) || empty($standard) || empty($image)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];

    } else {

        $countquery = mysqli_query($conn, "SELECT COUNT(*) AS total_row FROM subjects");
        $countrow = mysqli_fetch_assoc($countquery);
        $uniqueid = $countrow["total_row"] + 1;

        $upload_dir = "./upload/subject/";

        if (!file_exists($upload_dir)) {
            mkdir("./upload/subject/");
        }

        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        $file_name = "subject_" . $uniqueid . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
            http_response_code(500);
            $response = ["message" => "Filed to upload file", "status" => false];
        }

        $sql = "INSERT INTO subjects (name,gujrati_name,english_name,standard,image) VALUES (?,?,?,?,?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $response = ["message" => "Filed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "sssis", $name, $gujrati_name, $english_name, $standard, $upload_path);

        if (mysqli_stmt_execute($stmt)) {

            http_response_code(201);
            $response = ["message" => "Subject create successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create subject", "status" => false];
        }
    }
} else if ($action === "update_subject") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $id = $_POST["id"];
    $name = $_POST["name"];
    $gujrati_name = $_POST["gujrati_name"];
    $english_name = $_POST["english_name"];
    $standard = $_POST["standard"];
    $image = $_FILES["image"];

    if (empty($id) || empty($name) || empty($gujrati_name) || empty($english_name) || empty($standard)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {
        $upload_dir = "./upload/subject/";

        if (!file_exists($upload_dir)) {
            mkdir("./upload/subject/");
        }

        $ext = pathinfo($image["name"], PATHINFO_EXTENSION);
        $file_name = "subject_" . $id . "." . $ext;
        $upload_path = $upload_dir . $file_name;

        if (!move_uploaded_file($image["tmp_name"], $upload_path)) {
            http_response_code(500);
            $response = ["message" => "Failed to upload photo", "status" => false];
        }

        $sql = "UPDATE subjects SET name=?,gujrati_name=?,english_name=?,standard=?,image=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $response = ["message" => "Filed to prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "sssisi", $name, $gujrati_name, $english_name, $standard, $upload_path, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Subject Data upload successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "failed to update data", "status" => false];
        }
    }
} else if ($action === "read_subject") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {

        $response = [
            "message" => "Only post method can allowed",
            "status" => false
        ];

    } else {

        $child_id = $_POST["child_id"] ?? '';

        if (!empty($child_id)) {

            //Fetch child data
            $sql = "SELECT id, child_name, standard
                    FROM children
                    WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "i", $child_id);

            if (mysqli_stmt_execute($stmt)) {

                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {

                    $child = mysqli_fetch_assoc($result);

                    // Get subject data based on child standard
                    $sql = "SELECT * FROM subjects WHERE standard = ?";

                    $stmt = mysqli_prepare($conn, $sql);

                    mysqli_stmt_bind_param($stmt, "i", $child["standard"]);

                    if (mysqli_stmt_execute($stmt)) {

                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {

                            $subjects = mysqli_fetch_all($result, MYSQLI_ASSOC);

                            $response = [
                                "message" => "Subject data fetch successfully",
                                "status" => true,
                                "child" => [
                                    "id" => $child["id"],
                                    "name" => $child["child_name"],
                                    "standard" => $child["standard"]
                                ],
                                "subjects" => $subjects
                            ];
                        } else {

                            $response = ["message" => "Data not found", "status" => false];
                        }
                    }

                } else {
                    $response = ["message" => "Data not found", "status" => false];
                }
            }
        }
    }
} else if ($action === "delete_subject") {

    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
        $response = ["message" => "Only get method can allowed", "status" => false];
    }

    $id = $_POST["id"];

    $sql = "DELETE FROM subjects where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $response = ["message" => "Filed to prepare", "status" => false];
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Subject delete successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch subject data", "status" => false];
    }
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>