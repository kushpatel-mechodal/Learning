<?php

require("./connection.php");

$limit_per_page = 10;

$page = "";

if (isset($_POST["page_no"])) {
    $page = $_POST["page_no"];
} else {
    $page = 1;
}

$offset = ($page - 1) * $limit_per_page;

$read_chapters = "SELECT c.*,s.english_name AS english_name,st.name AS standard_name  FROM chapters c 
LEFT JOIN subjects s ON c.subject = s.id
LEFT JOIN standards st ON c.standard = st.id
LIMIT {$offset},{$limit_per_page}";

$stmt_chapters = mysqli_prepare($conn, $read_chapters);

mysqli_stmt_execute($stmt_chapters);

$res_chapters = mysqli_stmt_get_result($stmt_chapters);

$output = "";
if (mysqli_num_rows($res_chapters) > 0) {

    $output .= "<table class='table-bordered' border='1' width= '50%' cellspacing='0' cellpadding='10px'>
                <tr>
                    <th>id</th>
                    <th>chapter_no</th>
                    <th>chapter_name</th>
                    <th>subject</th>
                    <th>standard</th>
                    <th>Action</th>
                </tr>";

    while ($rows = mysqli_fetch_assoc($res_chapters)) {
        $output .= "<tr>
                    <td>{$rows['id']}</td>
                    <td>{$rows['chapter_no']}</td>
                    <td>{$rows['name']}</td>
                    <td>{$rows['english_name']}</td>
                    <td>{$rows['standard_name']}</td>
                    <td>
                        <button class='btn btn-primary' data-eid='{$rows["id"]}'>Edit</button>
                        <button class='btn btn-danger' data-id='{$rows["id"]}'>Delete</button>
                    </td>
                </tr>";
    }
    $output .= "</table>";

    $sql_total = "SELECT c.*,s.english_name AS english_name,st.name AS standard_name  FROM chapters c 
    LEFT JOIN subjects s ON c.subject = s.id
    LEFT JOIN standards st ON c.standard = st.id";

    $stmt_total = mysqli_prepare($conn, $sql_total);

    mysqli_stmt_execute($stmt_total);

    $res_total = mysqli_stmt_get_result($stmt_total);

    $total_record = mysqli_num_rows($res_total);

    $total_pages = ceil($total_record / $limit_per_page);

    $output .= "<div id='pagination'>";
    if ($page <= 3) {

        for ($i = 1; $i <= 4; $i++) {

            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='{$active_class}' id='{$i}' href=''>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id='{$total_pages}'>{$total_pages}</a>";
    } else if ($page >= $total_pages - 3) {
        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $total_pages - 4; $i <= $total_pages; $i++) {
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
