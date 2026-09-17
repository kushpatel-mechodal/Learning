<?php

require("./connection.php");

$limit_per_page = 10;

$page = "";

if (isset($_POST["page_no"])) {
    $page = $_POST["page_no"];
} else {
    $page = 1;
}

if (isset($_POST["limit"])) {
    $limit_per_page = $_POST["limit"];
} else {
    $limit_per_page = 10;
}

$offset = ($page - 1) * $limit_per_page;

$sql_question = "SELECT q.*,c.name AS chapter_name FROM questions q 
LEFT JOIN chapters c ON q.chapter_id = c.id LIMIT {$offset},{$limit_per_page}";

$stmt_question = mysqli_prepare($conn, $sql_question);

mysqli_stmt_execute($stmt_question);

$res_question = mysqli_stmt_get_result($stmt_question);

$output = "";

if (mysqli_num_rows($res_question) > 0) {

    $output .= "
         <table class='table table-bordered' border='1' cellspacing='0' cellpadding='10px'>
                <tr>
                    <th>id</th>
                    <th>Question</th>
                    <th>Option 1</th>
                    <th>Option 2</th>
                    <th>Option 3</th>
                    <th>Option 4</th>
                    <th>Correct Answer</th>
                    <th>Chapter_id</th>
                    <th>Action</th>
                </tr>";

    $serial_no = $offset + 1;

    while ($rows = mysqli_fetch_assoc($res_question)) {
        $output .= "
         <tr>
                    <td>{$serial_no}</td>
                    <td>{$rows['question']}</td>
                    <td>{$rows['option1']}</td>
                    <td>{$rows['option2']}</td>
                    <td>{$rows['option3']}</td>
                    <td>{$rows['option4']}</td>
                    <td>{$rows['correct_answer']}</td>
                    <td>{$rows['chapter_name']}</td>
                    <td>
                        <button class='btn btn-primary' data-eid='{$rows["id"]}'>Edit</button>
                        <button class='btn btn-danger' data-id='{$rows["id"]}'>Delete</button>
                    </td>
                </tr>";
        $serial_no++;
    }

    $output .= "</table>";

    $sql_total = "SELECT q.*,c.name AS chapter_name FROM questions q 
    LEFT JOIN chapters c ON q.chapter_id = c.id";

    $stmt_total = mysqli_prepare($conn, $sql_total);

    mysqli_stmt_execute($stmt_total);

    $res_total = mysqli_stmt_get_result($stmt_total);

    $total_record = mysqli_num_rows($res_total);

    $total_pages = ceil($total_record / $limit_per_page);

    $output .= "<div id='pagination'>";

    if ($page <= 4) {

        for ($i = 1; $i <= 5; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}'>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id='{$total_pages}'>{$total_pages}</a>";
    } else if ($page >= $total_pages - 3) {

        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $total_pages - 4; $i <= $total_pages; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}'>{$i}</a>";
        }
    } else {
        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $page - 1; $i <= $page + 1; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}'>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id={$total_pages}>{$total_pages}</a>";
    }
    $output .= "</div>";
    echo $output;
} else {
    echo "Data not found";
}
