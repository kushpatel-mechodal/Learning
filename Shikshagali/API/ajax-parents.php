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

$sql_parents = "SELECT * FROM parents LIMIT {$offset},{$limit_per_page}";

$stmt_parents = mysqli_prepare($conn, $sql_parents);

mysqli_stmt_execute($stmt_parents);

$output = "";

$result = mysqli_stmt_get_result($stmt_parents);

if (mysqli_num_rows($result) > 0) {

    $output .= "
            <table class='table-bordered' border='1' width='50%' cellspacing='0' cellpadding='10px'>
                <tr>
                    <th>id</th>
                    <th>Parents_name</th>
                    <th>mobile</th>
                    <th>email</th>
                    <th>Action</th>
                </tr>";

    while ($rows = mysqli_fetch_assoc($result)) {
        $output .= "<tr>
                    <td>{$rows["id"]}</td>
                    <td>{$rows["name"]}</td>
                    <td>{$rows["mobile"]}</td>
                    <td>{$rows["email"]}</td>
                    <td>
                        <button class='btn btn-primary' data-eid='{$rows["id"]}'>Edit</button>
                        <button class='btn btn-danger' data-id='{$rows["id"]}'>Delete</button>
                    </td>
                </tr>";
    }

    $output .= "</table>";

    $sql_total = "SELECT id,name,mobile,email FROM parents";

    $stmt_total = mysqli_prepare($conn, $sql_total);

    mysqli_stmt_execute($stmt_total);

    $res_total = mysqli_stmt_get_result($stmt_total);

    $total_record = mysqli_num_rows($res_total);

    $total_pages = ceil($total_record / $limit_per_page);

    $output .= "<div id='pagination'>";

    //less then 5 page to execute this condition
    if ($page <= 4) {

        // Starting pages

        for ($i = 1; $i <= 5; $i++) {

            $active_class = ($i == $page) ? "active" : "";
            $output .= " <a class='$active_class' id='{$i}' href=''>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id='{$total_pages}' href=''>{$total_pages}</a>";  //get total pages at the last

    } else if ($page >= $total_pages - 4) {

        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $total_pages - 5; $i <= $total_pages; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= " <a class='$active_class' id='{$i}' href=''>{$i}</a>";
        }
    } else {
        $output .= "<a id='1'>1</a>";
        $output .= "...";

        for ($i = $page - 1; $i <= $page + 2; $i++) {
            $active_class = ($i == $page) ? "active" : "";
            $output .= "<a class='$active_class' id='{$i}' href=''>{$i}</a>";
        }
        $output .= "...";
        $output .= "<a id={$total_pages}>{$total_pages}</a>";
    }

    $output .= "</div>";
    echo $output;
} else {
    echo "Data not found";
}
