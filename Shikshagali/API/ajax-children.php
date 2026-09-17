<?php

require("./connection.php");

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

$sql_children = "SELECT c.*, st.state_name AS state_name, d.district_name AS district_name,
t.taluka_name AS taluka_name,std.name AS standard_name FROM children c 
LEFT JOIN states st ON c.state_id = st.id
LEFT JOIN districts d ON c.district_id = d.id
LEFT JOIN talukas t ON c.taluka_id = t.id
LEFT JOIN standards std ON c.standard = std.id LIMIT {$offset},{$limit_per_page}";

$sql_children = mysqli_prepare($conn, $sql_children);

mysqli_stmt_execute($sql_children);

$res_children = mysqli_stmt_get_result($sql_children);

$output = "";
if (mysqli_num_rows($res_children) > 0) {

    $output .= "
                
                <table border='1' cellspacing='0' cellpadding='10px'>
                <tr>
                    <th>id</th>
                    <th>child_name</th>
                    <th>state_name</th>
                    <th>district_name</th>
                    <th>school_name</th>
                    <th>taluka_name</th>
                    <th>standard_name</th>
                    <th>gender</th>
                    <th>reward</th>
                    <th>Action</th>
                </tr>";

    $serial_no = $offset + 1;
    while ($rows = mysqli_fetch_assoc($res_children)) {

        $output .= "<tr>
                    <td>{$serial_no}</td>
                    <td>{$rows["child_name"]}</td>
                    <td>{$rows["state_name"]}</td>
                    <td>{$rows["district_name"]}</td>
                    <td>{$rows["school_name"]}</td>
                    <td>{$rows["taluka_name"]}</td>
                    <td>{$rows["standard_name"]}</td>
                    <td>{$rows["gender"]}</td>
                    <td>{$rows["reward"]}</td>
                    <td>
                        <button class='btn btn-primary' data-eid='{$rows["id"]}'>Edit</button>
                        <button class='btn btn-danger' data-id='{$rows["id"]}'>Delete</button>
                    </td>
                </tr>";
        $serial_no++;
    }
    $output .= "</table>";

    $sql_total = "SELECT c.*, st.state_name AS state_name, d.district_name AS district_name,
    t.taluka_name AS taluka_name,std.name AS standard_name FROM children c 
    LEFT JOIN states st ON c.state_id = st.id
    LEFT JOIN districts d ON c.district_id = d.id
    LEFT JOIN talukas t ON c.taluka_id = t.id
    LEFT JOIN standards std ON c.standard = std.id";

    $stmt_total = mysqli_prepare($conn, $sql_total);

    mysqli_stmt_execute($stmt_total);

    $res_total = mysqli_stmt_get_result($stmt_total);

    $total_record = mysqli_num_rows($res_total);

    $total_pages = ceil($total_record / $limit_per_page);

    $output .= "<div id='pagination'>";

    if ($page <= 4) {

        for ($i = 1; $i <= 5; $i++) {

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
