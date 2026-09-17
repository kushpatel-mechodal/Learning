<?php

require("./connection.php");

$limit_per_page = 5;

$page = "";

if (isset($_POST["page_no"])) {
    $page = $_POST["page_no"];
} else {
    $page = 1;
}

$offset = ($page - 1) * $limit_per_page;

$sql_subject = "SELECT s.*,st.name AS standard_name FROM subjects s 
LEFT JOIN standards st ON s.standard = st.id LIMIT {$offset},{$limit_per_page}";

$stmt_subject = mysqli_prepare($conn, $sql_subject);

mysqli_stmt_execute($stmt_subject);

$res_subject = mysqli_stmt_get_result($stmt_subject);

$output = "";

if (mysqli_num_rows($res_subject) > 0) {
    $output = "
            <table border='1' cellspacing='0' cellpadding='10px'>
                <tr>
                    <th>id</th>
                    <th>name</th>
                    <th>english_name</th>
                    <th>standard</th>
                </tr>";

    while ($rows = mysqli_fetch_assoc($res_subject)) {
        $output .= "<tr>
                    <td>{$rows["id"]}</td>
                    <td>{$rows["name"]}</td>
                    <td>{$rows["english_name"]}</td>
                    <td>{$rows["standard_name"]}</td>
                </tr>";
    }
    $output .= "</table>";

    $sql_total = "SELECT s.*,st.name AS standard_name FROM subjects s 
    LEFT JOIN standards st ON s.standard = st.id";

    $stmt_total = mysqli_prepare($conn, $sql_total);

    mysqli_stmt_execute($stmt_total);

    $res_total = mysqli_stmt_get_result($stmt_total);

    $total_record = mysqli_num_rows($res_total);

    $total_pages = ceil($total_record / $limit_per_page);

    $output .= "<div id='pagination'>";

    if ($page <= 2) {
        for ($i = 1; $i <= 3; $i++) {

            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}' href=''>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id='{$total_pages}'>{$total_pages}</a>";
    } else if ($page >= $total_pages - 1) {
        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $total_pages - 2; $i <= $total_pages; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}' href=''>{$i}</a>";
        }
    } else {
        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $page - 1; $i <= $page + 1; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}' href=''>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id='{$total_pages}'>{$total_pages}</a>";
    }

    $output .= "</div>";
    echo $output;
} else {
    echo "Data not found";
}
