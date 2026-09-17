<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorized,X-requested-With");

require("./connection.php");

$response = [];
$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? '';

if ($action === "create_chapter") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $chapter_no = $data["chapter_no"] ?? '';
    $chapter_name = $data["chapter_name"] ?? '';
    $subject = $data["subject"] ?? '';
    $standard = $data["standard"] ?? '';

    if (empty($chapter_no) || empty($chapter_name) || empty($subject) || empty($standard)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {

        $sql = "INSERT INTO chapters (chapter_no,name,subject,standard) VALUES(?,?,?,?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $response = ["message" => "Failed to Prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "isii", $chapter_no, $chapter_name, $subject, $standard);

        if (mysqli_stmt_execute($stmt)) {

            http_response_code(201);
            $response = ["message" => "Chapter create successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create chapter", "status" => false];
        }
    }
} else if ($action === "update_chapter") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $id = $data["id"];
    $chapter_no = $data["chapter_no"] ?? '';
    $chapter_name = $data["chapter_name"] ?? '';
    $subject = $data["subject"] ?? '';
    $standard = $data["standard"] ?? '';

    if (empty($chapter_no) || empty($chapter_name) || empty($subject) || empty($standard)) {

        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];

    } else {
        $sql = "UPDATE chapters SET chapter_no=?,name=?,subject=?,standard=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        $subject = $data["subject"];
        mysqli_stmt_bind_param($stmt, "isiii", $chapter_no, $chapter_name, $subject, $standard, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Chapter data update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update chapter data", "status" => false];
        }
    }
} else if ($action === "read_chapter") {

    $id = $data["id"] ?? '';

    if (!empty($id)) {

        $sql = "SELECT c.id,c.chapter_no,c.name,c.subject,s.name AS subject_name,s.english_name,c.standard,st.name AS standard_name FROM chapters c
        LEFT JOIN subjects s ON c.subject = s.id
        LEFT JOIN standards st ON c.standard = st.id WHERE c.id = '$id'";

    } else {
        $sql = "SELECT c.id,c.chapter_no,c.name,c.subject,s.name AS subject_name,s.english_name,c.standard,st.name AS standard_name FROM chapters c
        LEFT JOIN subjects s ON c.subject = s.id
        LEFT JOIN standards st ON c.standard = st.id";
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
} else if ($action === "delete_chapter") {
    $id = $data["id"] ?? '';

    $sql = "DELETE FROM chapters WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Chapters data delete successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }

    } else {

        http_response_code(500);
        $response = ["message" => "Failed to chapters delete data", "status" => false];
    }
}

echo json_encode($response);
?>