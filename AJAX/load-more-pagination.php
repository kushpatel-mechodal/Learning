<?php

require_once __DIR__ . "/connection.php";

$limit = 3;
$page = isset($_POST["page_no"]) ? intval($_POST["page_no"]) : 0;

$pagination = "SELECT * FROM employees LIMIT ?, ?";

$stmt_more_pagination = mysqli_prepare($conn, $pagination);

if ($stmt_more_pagination) {
    mysqli_stmt_bind_param($stmt_more_pagination, "ii", $page, $limit);
    mysqli_stmt_execute($stmt_more_pagination);

    $result = mysqli_stmt_get_result($stmt_more_pagination);

    if (mysqli_num_rows($result) > 0) {
        $output = "";
        $output .= "<tbody>";

        while ($rows = mysqli_fetch_assoc($result)) {
            $output .= " <tr>
                        <td align='center'>{$rows["id"]}</td><td>{$rows["username"]}</td>
                        <td>{$rows["email"]}</td><td>{$rows["phone"]}</td><td>{$rows["salary"]}</td>
                        <td>{$rows["role"]}</td>
                    </tr>";
        }

        $next_id = $page + $limit;
        
        $output .= "</tbody>
                    <tbody id='pagination'>
                        <tr>
                            <td colspan='6'>
                                <button id='ajaxbtn' data-id='{$next_id}'>Load more</button>
                            </td>
                        </tr>
                    </tbody>";

        echo $output;
    } else {
        echo "";
    }

    mysqli_stmt_close($stmt_more_pagination);
} else {
    echo "";
}
?>