<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods, Authorized");

require("./connection.php");

$response = [];

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    $response = ["message" => "Only get method can allowed", "status" => false];
}

$child_id = $_POST["child_id"] ?? '';

$sql = "SELECT school_id FROM children WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $child_id);

if (mysqli_stmt_execute($stmt)) {

    $result = mysqli_stmt_get_result($stmt);

    $child = mysqli_fetch_assoc($result);

    $school_id = $child["school_id"];

    $ids = [];

    $get_id = mysqli_query($conn, "SELECT id from children WHERE school_id='$school_id'");

    if (mysqli_num_rows($get_id) > 0) {

        $fetch = mysqli_fetch_all($get_id, MYSQLI_ASSOC);

        foreach ($fetch as $data) {

            $ids[] = $data["id"];
            $id_string = implode(",", $ids);

        }
    }
}

$current_month = date("Y-m-01");
$current_date = date("Y-m-d");
// $total_days = date("t");

$school_read = "SELECT c.id,c.child_name,c.standard,c.reward,c.school_id, COALESCE(SUM(dq.marks),0) AS total_marks FROM children c 
    LEFT JOIN daily_quiz dq ON c.id = dq.child_id AND dq.quiz_date >= '$current_month' AND dq.quiz_date <= '$current_date' 
    AND dq.status = 'completed' WHERE c.school_id = ? GROUP BY c.id,c.child_name,c.standard,c.reward,c.school_id ORDER by total_marks DESC ";

$stmt_school = mysqli_prepare($conn, $school_read);

mysqli_stmt_bind_param($stmt_school, "i", $school_id);

mysqli_stmt_execute($stmt_school);

$result = mysqli_stmt_get_result($stmt_school);

if (mysqli_num_rows($result) > 0) {

    //school rank
    $school_data = [];
    $school_rank = 0;
    $rank = 1;
    $previous_marks = null;

    while ($data = mysqli_fetch_assoc($result)) {

        $points = $data["total_marks"];

        if ($points != $previous_marks) {
            $current_rank = $rank;
        }

        if ($data["id"] == $child_id) {
            $school_rank = $current_rank;
            $child_data = $data;
            $child_data["school_rank"] = $school_rank;
            break;
        }
        $previous_marks = $points;
        $rank++;
    }


    //world rank
    $world_read = "SELECT c.id,c.child_name,c.standard,c.reward,c.school_id, COALESCE(SUM(dq.marks),0) AS total_marks FROM children c
    LEFT JOIN daily_quiz dq ON c.id = dq.child_id AND dq.quiz_date >= $current_month AND dq.quiz_date <= $current_date AND dq.status = 'completed'
    GROUP BY c.id ORDER BY total_marks DESC";

    $stmt_world = mysqli_prepare($conn, $world_read);

    mysqli_stmt_execute($stmt_world);

    $result = mysqli_stmt_get_result($stmt_world);

    $world_data = [];
    $world_rank = 0;
    $rank = 1;
    $previous_marks = null;

    while ($data = mysqli_fetch_assoc($result)) {

        $points = $data["total_marks"];

        if ($data["id"] == $child_id) {

            $world_rank = $rank;
            $world_data["world_rank"] = $world_rank;
        }
        $previous_marks = $points;
        $rank++;
    }

    $total_day = "SELECT COUNT(DISTINCT (quiz_date)) AS total_quiz_day FROM daily_quiz WHERE child_id = ?
    AND quiz_date >= '$current_month' AND quiz_date<='$current_date' AND status='completed' ORDER BY quiz_date DESC";

    $stmt_day = mysqli_prepare($conn, $total_day);

    mysqli_stmt_bind_param($stmt_day, "i", $child_id);
    mysqli_stmt_execute($stmt_day);

    $result = mysqli_stmt_get_result($stmt_day);

    $day_data = mysqli_fetch_assoc($result);
    $total_days_in_month = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));

    http_response_code(200);
    $response = [
        "message" => "Children data fetch successsfully",
        "status" => true,
        "child_profile data" => [
            "id" => $child_data["id"],
            "child_name" => $child_data["child_name"],
            "stnadard" => $child_data["standard"],
            "reward" => $child_data["reward"],
            "total marks" => $child_data["total_marks"],
            "school id" => $child_data["school_id"],
            "school_rank" => $child_data["school_rank"],
            "world_rank" => $world_data["world_rank"],
            "total_quiz_day" => $day_data["total_quiz_day"],
            "total_month_day" => $total_days_in_month
        ]
    ];
} else {
    http_response_code(404);
    $response = ["message" => "Data not found", "status" => false];
}

echo json_encode($response);
?>