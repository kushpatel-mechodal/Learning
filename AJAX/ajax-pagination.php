<?php

require("../CRUD_Application/connection.php");

$limit_per_page = 3;

$page = "";

if (isset($_POST["page_no"])) {

    $page = $_POST["page_no"];
} else {
    $page = 1;
}

$offset = ($page - 1) * $limit_per_page;

$pagination_sql = "SELECT * FROM employees LIMIT {$offset},{$limit_per_page}";

$stmt_pagination = mysqli_prepare($conn, $pagination_sql);

    mysqli_stmt_execute($stmt_pagination);

    $output = "";
    $result = mysqli_stmt_get_result($stmt_pagination);


    if (mysqli_num_rows($result) > 0) {
        $output .= "
        <table border='1' width='100%' cellspacing='0' cellpadding='10px'>
                    <tr>
                        <th>Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Salary</th>
                        <th>Role</th>
                    </tr>";

        while ($rows = mysqli_fetch_assoc($result)) {
            $output .= "
                    <tr>
                        <td>{$rows["id"]}</td>
                        <td>{$rows["username"]}</td>
                        <td>{$rows["email"]}</td>
                        <td>{$rows["phone"]}</td>
                        <td>{$rows["salary"]}</td>
                        <td>{$rows["role"]}</td>
                    </tr>";
        }

        $output .= "</table>";

    $sql_total = "SELECT * FROM employees";

    $stmt_total = mysqli_prepare($conn, $sql_total);

        mysqli_stmt_execute($stmt_total);

    $result = mysqli_stmt_get_result($stmt_total);

    $total_record = mysqli_num_rows($result);

        $total_pages = ceil($total_record / $limit_per_page);

        $output .= "<div id='pagination'>";

        for ($i = 1; $i <= $total_pages; $i++) {

        if($i == $page){
            $active_class = "active";
        }else{
            $active_class = "";
        }
        $output .= "<a class='$active_class' id='{$i}' href=''>{$i}</a>";
        }
        $output .= "</div>";

        echo $output;
    } else {
        echo "Data not found";
    }
