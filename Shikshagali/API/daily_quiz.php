<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorized");

require("./connection.php");

$response = [];
$today = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $child_id = $_POST["child_id"] ?? '';

    if (empty($child_id)) {
        http_response_code(500);
        $response = ["message" => "Child is required", "status" => false];
        exit;
    }

    $existquiz = "SELECT * FROM daily_quiz WHERE child_id = '$child_id' AND quiz_date = '$today'";

    $stmt_exist = mysqli_prepare($conn, $existquiz);

    mysqli_stmt_execute($stmt_exist);

    $get_quiz = mysqli_stmt_get_result($stmt_exist);

    if (mysqli_num_rows($get_quiz) > 0) {

        $quiz = mysqli_fetch_assoc($get_quiz);

        $questionids = explode(",", $quiz["question_ids"]);

        $question = [];

        foreach ($questionids as $ids) {
            $qid = trim($ids);

            $get_question = "SELECT * FROM questions WHERE id = '$qid' LIMIT 1";

            $stmt_question = mysqli_prepare($conn, $get_question);

            mysqli_stmt_execute($stmt_question);

            $get_question = mysqli_stmt_get_result($stmt_question);

            if (mysqli_num_rows($get_question) > 0) {
                $questions[] = mysqli_fetch_assoc($get_question);
            }
        }
        http_response_code(200);
        $response = ["message" => "Existing quiz loaded", "status" => true, "quiz_id" => $quiz["id"], "data" => $questions];
    } 

    //get child's standard
    $get_child = "SELECT standard FROM children WHERE id='$child_id'";

    $stmt_child = mysqli_prepare($conn, $get_child);

    mysqli_stmt_execute($stmt_child);

    $result = mysqli_stmt_get_result($stmt_child);

    if (mysqli_num_rows($result) > 0) {

        http_response_code(200);
        $child = mysqli_fetch_assoc($result);
        $standard_id = $child["standard"];
    } else {
        http_response_code(404);
        $response = ["message" => "Standard not found", "status" => false];
    }
} else {
    http_response_code(405);
    $response = ["message" => "Only post method can allowed", "status" => false];
}

$today = date("Y-m-d");

$get_syllabus = "SELECT chapters FROM syllabus WHERE standard_id = '$standard_id' AND start_date <= '$today'";

$stmt_syllabus = mysqli_prepare($conn, $get_syllabus);

mysqli_stmt_execute($stmt_syllabus);

$syllabus_result = mysqli_stmt_get_result($stmt_syllabus);

$allchapterids = [];

while ($syllabus = mysqli_fetch_assoc($syllabus_result)) {

    $chapters = explode(",", str_replace(" ", "", $syllabus["chapters"]));
    $allchapterids = array_merge($allchapterids, $chapters);
}

$allchapterids = array_filter($allchapterids); //filter only data and remove empty data
$allchapterids = array_unique($allchapterids); //return every unique ids

if (count($allchapterids) === 0) {
    $response = ["message" => "No valid chapter found for this standard", "status" => false];
}

$totalQuestionRequire = 10;
$allQuestion = [];
$questionids = [];

shuffle($allchapterids); //random questions

$avaliableQuestions = [];

foreach ($allchapterids as $chapterid) {

    $get_question = "SELECT * FROM questions WHERE chapter_id = '$chapterid'";

    $stmt_questions = mysqli_prepare($conn, $get_question);

    mysqli_stmt_execute($stmt_questions);

    $question_result = mysqli_stmt_get_result($stmt_questions);

    while ($q = mysqli_fetch_assoc($question_result)) {

        $avaliableQuestions[] = $q;

    }
}

if (count($avaliableQuestions) === 0) {
    $response = ["message" => "No question found to generate quiz", "status" => false];
}

shuffle($avaliableQuestions); //random questions


foreach ($avaliableQuestions as $q) {

    if (count($allQuestion) >= $totalQuestionRequire) {
        break;
    }

    if (!in_array($q["id"], $questionids)) {

        $allQuestion[] = $q;
        $questionids[] = $q["id"];
    }

}
$questionidsCSV = implode(",", $questionids);
$insert = "INSERT INTO daily_quiz (child_id,quiz_date,question_ids) VALUES('$child_id','$today','$questionidsCSV')";

$stmt_insert = mysqli_prepare($conn, $insert);

if (mysqli_stmt_execute($stmt_insert)) {

    $quiz_id = mysqli_insert_id($conn);
    http_response_code(201);
    $response = [
        "message" => "Quiz created successsfully",
        "status" => true,
        "quiz_id" => $quiz_id,
        "question_ids" => $questionids,
        "Questions data" => $allQuestion
    ];
} else {
    http_response_code(405);
    $response = ["message" => "Failed to create quiz", "status" => false];
}

echo json_encode($response);


?>