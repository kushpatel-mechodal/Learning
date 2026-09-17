<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,OPTIONS");
header("Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Authorized");

require("./connection.php");

$response = [];
$action = $_POST["action"] ?? '';
$type = $_POST["type"] ?? '';
$child_id = $_POST["child_id"] ?? '';

if ($type === "school") {

    $sql = "SELECT school_id FROM children WHERE id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $child_id);

    if (mysqli_stmt_execute($stmt)) {

        $result = mysqli_stmt_get_result($stmt);

        $child = mysqli_fetch_assoc($result);

        $school_id = $child["school_id"];
        // print_r($school_id);

        $ids = [];

        $get_id = mysqli_query($conn, "select id,school_name from children where school_id = '$school_id'");
        // print_r($get_id);
        if (mysqli_num_rows($get_id) > 0) {
            $fetch = mysqli_fetch_all($get_id, MYSQLI_ASSOC);
            // print_r($fetch);

            foreach ($fetch as $data) {
                $ids[] = $data["id"];

                $id_string = implode(",", $ids);
                // echo $data["id"] . "<br/>";
                // echo $data["school_name"];
                $response = ["message" => "School leaderboard data fetch successfully", "status" => true, "Data" => $fetch];
            }
            // print_r($ids);

        }

        $current_date = date("Y-m-d");
        $current_month = date("Y-m-01");
        $total_days = date("t");

        // print_r($current_date);
        // print_r($current_month);
        // print_r($total_days);

        $quiz = "SELECT c.id AS child_id, c.child_name,c.school_name, COALESCE(SUM(dq.marks), 0) AS total_marks FROM children c
         LEFT JOIN daily_quiz dq ON c.id = dq.child_id AND dq.quiz_date >= '$current_month' AND dq.quiz_date <= '$current_date'
         AND dq.status = 'completed' WHERE c.school_id = '$school_id' GROUP BY c.id, c.child_name ORDER BY total_marks DESC";

        $stmt_quiz = mysqli_prepare($conn, $quiz);

        if (mysqli_stmt_execute($stmt_quiz)) {

            $result = mysqli_stmt_get_result($stmt_quiz);

            if (mysqli_num_rows($result) > 0) {

                // echo "Rows" . mysqli_num_rows($result);

                $school_data = [];
                $rank = 1;
                $previous_marks = null;

                while ($data = mysqli_fetch_assoc($result)) {

                    $points = $data["total_marks"];

                    if ($points !== $previous_marks) {
                        $current_rank = $rank;
                    }

                    $data["school_rank"] = $current_rank;
                    $school_data[] = $data;
                    $previous_marks = $data["total_marks"]; //same marks get the same school rank
                    $rank++;
                }

                http_response_code(200);
                $response = ["message" => "Leaderboard data fetch successfully", "status" => true, "leaderboard_data" => $school_data];
            } else {
                http_response_code(404);
                $response = ["message" => "Data not found", "status" => false];
            }
        } else {
            http_response_code(500);
            $response = ["message" => "Failed to fetch leaderbaord data", "status" => false];
        }
    }
} else if ($type === "open") {

    $current_date = date("Y-m-d");
    $current_month = date("Y-m-01");
    // print_r($current_date);
    // print_r($current_month);

    $quiz = "SELECT c.id AS child_id, c.child_name,c.school_name, COALESCE(SUM(dq.marks), 0) AS total_marks FROM children c
         LEFT JOIN daily_quiz dq ON c.id = dq.child_id AND dq.quiz_date >= '$current_month' AND dq.quiz_date <= '$current_date'
         AND dq.status = 'completed' GROUP BY c.id, c.child_name ORDER BY total_marks DESC";
    // print_r($quiz);

    $stmt_quiz = mysqli_prepare($conn, $quiz);

    if (mysqli_stmt_execute($stmt_quiz)) {

        $result = mysqli_stmt_get_result($stmt_quiz);

        if (mysqli_num_rows($result) > 0) {

            // echo "Rows" . mysqli_num_rows($result);

            $world_rank = [];
            $rank = 0;
            $previous_marks = null;

            while ($data = mysqli_fetch_assoc($result)) {

                $points = $data["total_marks"];

                if ($points !== $previous_marks) {
                    $current_marks = $rank;
                }

                $data["World rank"] = $current_marks;
                $world_rank[] = $data;
                $previous_marks = $data["total_marks"]; //same marks get the same world rank
                $rank++;
            }

            http_response_code(200);
            $output = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $response = ["message" => "Leaderboard data fetch successfully", "status" => true, "Leaderboard_data" => $world_rank];
        } else {
            http_response_code(404);
            $response = ["message" => "Data not found", "status" => false];
        }
    } else {
        http_response_code(500);
        $response = ["message" => "Failed to fetch leaderbaord data", "status" => false];
    }
}

echo json_encode($response);

?>