<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorized,X-requested-With");

require("./connection.php");

$response = [];
$data = json_decode(file_get_contents("php://input"), true);
$action = $data["action"] ?? '';

if ($action === "create_question") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $question = $data["question"] ?? '';
    $option1 = $data["option1"] ?? '';
    $option2 = $data["option2"] ?? '';
    $option3 = $data["option3"] ?? '';
    $option4 = $data["option4"] ?? '';
    $correct_answer = $data["correct_answer"] ?? '';
    $chapter_id = $data["chapter_id"] ?? '';
    $added_by = $data["added_by"] ?? null;
    $hint = $data["hint"] ?? null;
    $description = $data["description"] ?? null;

    if (empty($question) || empty($option1) || empty($option2) || empty($option3) || empty($option4) || empty($correct_answer) || empty($chapter_id)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {

        $sql = "INSERT INTO questions (question,option1,option2,option3,option4,correct_answer,chapter_id,added_by,hint,description) VALUES(?,?,?,?,?,?,?,?,?,?)";

        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            $response = ["message" => "Failed to Prepare", "status" => false];
        }

        mysqli_stmt_bind_param($stmt, "ssssssiiss", $question, $option1, $option2, $option3, $option4, $correct_answer, $chapter_id, $added_by, $hint, $description);

        if (mysqli_stmt_execute($stmt)) {

            http_response_code(201);
            $response = ["message" => "Question create successfully", "status" => true];
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to create question", "status" => false];
        }
    }
} else if ($action === "update_question") {

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        $response = ["message" => "Only post method can allowed", "status" => false];
    }

    $id = $data["id"];
    $question = $data["question"] ?? '';
    $option1 = $data["option1"] ?? '';
    $option2 = $data["option2"] ?? '';
    $option3 = $data["option3"] ?? '';
    $option4 = $data["option4"] ?? '';
    $correct_answer = $data["correct_answer"] ?? '';
    $chapter_id = $data["chapter_id"] ?? '';
    $added_by = $data["added_by"] ?? null;
    $hint = $data["hint"] ?? null;
    $description = $data["description"] ?? null;

    if (empty($question) || empty($option1) || empty($option2) || empty($option3) || empty($option4) || empty($correct_answer) || empty($chapter_id)) {
        http_response_code(500);
        $response = ["message" => "All fields required", "status" => false];
    } else {

        $sql = "UPDATE questions SET question=?,option1=?,option2=?,option3=?,option4=?,correct_answer=?,chapter_id=?,added_by=?,hint=?,description=? WHERE id=?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ssssssiissi", $question, $option1, $option2, $option3, $option4, $correct_answer, $chapter_id, $added_by, $hint, $description, $id);

        if (mysqli_stmt_execute($stmt)) {

            $affected_row = mysqli_affected_rows($conn);

            if ($affected_row > 0) {
                http_response_code(200);
                $response = ["message" => "Questions data update successfully", "status" => true];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to update questions data", "status" => false];
        }
    }
} else if ($action === "read_question") {

    $id = $data["id"] ?? '';

    if (!empty($id)) {

        $sql = "SELECT q.id,q.question,q.option1,q.option2,q.option3,q.option4,q.correct_answer,q.chapter_id,c.name AS chapter_name FROM questions q
        LEFT JOIN chapters c ON q.chapter_id = c.id WHERE q.id = '$id'";

    } else {
        $sql = "SELECT q.id,q.question,q.option1,q.option2,q.option3,q.option4,q.correct_answer,q.chapter_id,c.name AS chapter_name FROM questions q
        LEFT JOIN chapters c ON q.chapter_id = c.id";
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
} else if ($action === "delete_question") {
    $id = $data["id"] ?? '';

    $sql = "DELETE FROM questions WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        $affected_row = mysqli_affected_rows($conn);

        if ($affected_row > 0) {
            http_response_code(200);
            $response = ["message" => "Questions data delete successfully", "status" => true];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {

        http_response_code(500);
        $response = ["message" => "Failed to questions delete data", "status" => false];
    }
}

echo json_encode($response);
?>