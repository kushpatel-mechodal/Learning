<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorized");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($action === "add_syllabus") {

        $standard_id = $_POST["standard_id"];
        $subject_id = $_POST["subject_id"];
        $chapters = $_POST["chapters"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        if (empty($standard_id) || empty($subject_id) || empty($chapters) || empty($start_date) || empty($end_date)) {
            http_response_code(500);
            $response = ["message" => "All fields are required", "status" => false];
        } else {
            $insert = "INSERT INTO syllabus (standard_id,subject_id,chapters,start_date,end_date,created_at) VALUES(?,?,?,?,?,NOW())";

            $stmt_insert = mysqli_prepare($conn, $insert);

            mysqli_stmt_bind_param($stmt_insert, "iisss", $standard_id, $subject_id, $chapters, $start_date, $end_date);

            if (mysqli_stmt_execute($stmt_insert)) {

                http_response_code(201);
                $response = ["message" => "Chapters data inserted successfully", "status" => true];
            } else {
                http_response_code(500);
                $response = ["message" => "Failed to create chapters data", "status" => false];
            }
        }
    } else if ($action === "update_syllabus") {

        $syllabus_id = $_POST["syllabus_id"];
        $standard_id = $_POST["standard_id"];
        $subject_id = $_POST["subject_id"];
        $chapters = $_POST["chapters"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        if (empty($syllabus_id) || empty($standard_id) || empty($subject_id) || empty($chapters) || empty($start_date) || empty($end_date)) {
            http_response_code(500);
            $response = ["message" => "All fields are required", "status" => false];
        } else {
            $update = "UPDATE syllabus SET standard_id=?,subject_id=?,chapters=?,start_date=?,end_date=? WHERE id=?";

            $stmt_update = mysqli_prepare($conn, $update);

            mysqli_stmt_bind_param($stmt_update, "iisssi", $standard_id, $subject_id, $chapters, $start_date, $end_date, $syllabus_id);

            if (mysqli_stmt_execute($stmt_update)) {

                $affected_row = mysqli_affected_rows($conn);

                if ($affected_row > 0) {

                    http_response_code(200);
                    $response = ["message" => "Syllabus update successfully", "status" => true];
                } else {
                    http_response_code(404);
                    $response = ["message" => "Data not found", "status" => false];
                }
            } else {
                http_response_code(500);
                $response = ["message" => "Failed to update syllabus data ", "status" => false];
            }
        }
    } else if ($action === "read_syllabus") {
        $syllabus_id = $_POST["syllabus_id"];

        if (empty($syllabus_id)) {
            http_response_code(500);
            $response = ["message" => "All fields are required", "status" => false];
        } else {

            $read = "SELECT * FROM syllabus WHERE id='$syllabus_id'";

            $stmt_read = mysqli_prepare($conn, $read);

            if (mysqli_stmt_execute($stmt_read)) {

                $result = mysqli_stmt_get_result($stmt_read);

                if (mysqli_num_rows($result) > 0) {

                    http_response_code(200);
                    $output = mysqli_fetch_assoc($result);
                    $response = [
                        "message" => "Chapters data fetch successfully",
                        "status" => true,
                        "data" => [
                            "syllabus_id" => $output["id"],
                            "standard_id" => $output["standard_id"],
                            "subject_id" => $output["subject_id"],
                            "chapters" => $output["chapters"],
                            "start_date" => $output["start_date"],
                            "end_date" => $output["end_date"],
                        ]
                    ];
                } else {
                    http_response_code(404);
                    $response = ["message" => "Data not found", "status" => false];
                }
            } else {
                http_response_code(500);
                $response = ["message" => "Failed to fetch data", "status" => false];
            }
        }
    } else if ($action === "delete_syllabus") {

        $syllabus_id = $_POST["syllabus_id"];

        $delete = "DELETE FROM syllabus WHERE id='$syllabus_id'";

        $stmt_delete = mysqli_prepare($conn, $delete);

        if (mysqli_stmt_execute($stmt_delete)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {

                http_response_code(200);
                $response = ["message" => "Syllabus data deleted successfully", "status" => true];
            } else {
                http_response_code(500);
                $response = ["message" => "Failed to delete syllabus data", "status" => false];
            }
        }
    }
} else {
    http_response_code(500);
    $response = ["message" => "Only post method can allowed", "status" => false];
}
echo json_encode($response);

?>