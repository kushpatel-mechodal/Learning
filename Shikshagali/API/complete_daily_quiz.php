<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorized");

require("./connection.php");

$response = [];

// validate Input's

$quiz_id = $_POST["quiz_id"] ?? 0;
$correct_question_ids_str = $_POST["correct_question_ids"] ?? '';
$marks = $_POST["marks"] ?? 0;

if ($quiz_id <= 0 || empty($correct_question_ids_str) || $marks <= 0) {

    $response = ["message" => "All fields are required", "status" => false];
}

// Convert correct question ids into array

$correct_question_ids = array_filter(array_map('intval', explode(",", $correct_question_ids_str)));

if (count($correct_question_ids) === 0) {

    $response = ["message" => "Question id must contain a valid id", "status" => false];
}

$correct_ids_str = implode(',', $correct_question_ids);
// Get quiz details

$get_question = "SELECT question_ids, child_id FROM daily_quiz WHERE id='$quiz_id'";

$stmt_question = mysqli_prepare($conn, $get_question);

mysqli_stmt_execute($stmt_question);

$qus_result = mysqli_stmt_get_result($stmt_question);

if (mysqli_num_rows($qus_result) > 0) {

    //quiz found
    $quiz_data = mysqli_fetch_assoc($qus_result);

    $question_ids_str = $quiz_data["question_ids"];
    $child_id = $quiz_data["child_id"];

    // Convert question ids into array

    $question_ids_arr = array_filter(array_map('intval', explode(',', $question_ids_str)));

    $question_ids = implode(',', $question_ids_arr);

    if (empty($question_ids)) {

        $response = ["message" => 'No valid question ids found for this quiz', "status" => false];
    }

    // Get child reward

    $get_child = "SELECT reward FROM children WHERE id='$child_id'";

    $stmt_child = mysqli_prepare($conn, $get_child);

    mysqli_stmt_execute($stmt_child);

    $child_result = mysqli_stmt_get_result($stmt_child);

    if (mysqli_num_rows($child_result) > 0) {

        //child found
        $child_data = mysqli_fetch_assoc($child_result);

        $current_reward = $child_data["reward"];

        // Calculate subject-wise correct counts
        $correct = "SELECT s.id AS subject_id,COUNT(*) AS correct_count FROM questions q
                     LEFT JOIN chapters c ON q.chapter_id = c.id
                     LEFT JOIN subjects s ON c.subject = s.id WHERE q.id IN ($correct_ids_str)
                    GROUP BY s.id";

        $stmt_correct = mysqli_prepare($conn, $correct);

        mysqli_stmt_execute($stmt_correct);

        $correct_result = mysqli_stmt_get_result($stmt_correct);

        $correct_per_subject = [];

        while ($row = mysqli_fetch_assoc($correct_result)) {

            $correct_per_subject[$row["subject_id"]] = $row["correct_count"];
        }

        //  Calculate total questions per subject for this quiz
        $total = "SELECT s.id AS subject_id,COUNT(*) AS total_questions FROM questions q
                  LEFT JOIN chapters c ON q.chapter_id = c.id
                  LEFT JOIN subjects s ON c.subject = s.id WHERE q.id IN ($question_ids_str)
                  GROUP BY s.id";

        $stmt_total = mysqli_prepare($conn, $total);

        mysqli_stmt_execute($stmt_total);

        $total_per_subject = [];

        $total_result = mysqli_stmt_get_result($stmt_total);

        while ($row = mysqli_fetch_assoc($total_result)) {
            $total_per_subject[$row['subject_id']] = $row['total_questions'];
        }

        //Calculate subject-wise average percentage
        $average_per_subject = [];

        foreach ($total_per_subject as $subject_id => $total) {
            $correct = $correct_per_subject[$subject_id] ?? 0; //get the correct questions of same subject
            $percentage = $total > 0 ? round(($correct / $total) * 100, 2) : 0; //calculate percentage per subject
            $average_per_subject[(string)$subject_id] = [ //pass the subject_id as a key
                "percentage" => $percentage,
                "correct" => $correct,
                "total" => $total
            ];
        }

        //prepare json string to store in database
        $average_json = mysqli_real_escape_string($conn, json_encode($average_per_subject));
        $correct_ids_json = mysqli_real_escape_string($conn, json_encode($correct_question_ids));
        $today = date("Y-m-d");

        //update child reward
        $new_reward = $current_reward + $marks;
        $update_child_reward = "UPDATE children SET reward='$new_reward' WHERE id='$child_id'";

        //update quiz
        $update_quiz = "UPDATE daily_quiz SET marks='$marks',status='completed',average='$average_json',correct_id='$correct_ids_json'
        WHERE id='$quiz_id'";

        //Insert reward record

        $insert_reward = "INSERT into rewards (child_id,type,point,balance,date) VALUES ($child_id,'quiz reward',$marks,$current_reward,
        '$tod=ay')";

        //Run in transection

        mysqli_begin_transaction($conn);

        try {

            $update_child_result = mysqli_prepare($conn, $update_child_reward);
            $update_quiz_result = mysqli_prepare($conn, $update_quiz);
            $update_reward_result = mysqli_prepare($conn, $insert_reward);

            if (!$update_child_result || !$update_quiz_result || !$update_reward_result) {
                throw new Exception("Database error" . mysqli_error($conn));
            }

            mysqli_stmt_execute($update_child_result);
            mysqli_stmt_execute($update_quiz_result);
            mysqli_stmt_execute($update_reward_result);

            mysqli_commit($conn); //commit all the transaction in database to save

            $response = [
                "message" => "quiz reward, child reward and reward log updated successfully",
                "status" => true,
                "new_reward_balance" => $new_reward,
                "average_per_sibject" => $average_per_subject
            ];
        } catch (Exception $error) {
            mysqli_rollback($conn);
            $response = ["message" => $error->getmessage(), "status" => false];
        }

        mysqli_stmt_close($stmt_correct);

    } else {

        $response = [
            "status" => false,
            "message" => "Child not found"
        ];
    }
    mysqli_stmt_close($stmt_child);

} else {

    $response = [
        "status" => false,
        "message" => "Quiz not found"
    ];
}

mysqli_stmt_close($stmt_question);

echo json_encode($response);

?>