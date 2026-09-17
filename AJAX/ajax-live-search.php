<?php

require("../CRUD_Application/connection.php");

$search_bar = $_POST["search"];

$search = "SELECT * FROM employees WHERE username LIKE '%{$search_bar}%'";

$stmt_read = mysqli_prepare($conn, $search);

    mysqli_stmt_execute($stmt_read);

    $result = mysqli_stmt_get_result($stmt_read);

    $output = "";

    if (mysqli_num_rows($result) > 0) {

        $output = '<table border="1" width="100%" cellspacing="0" cellpadding="10px">
                    <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Salary</th>
                    <th>Role</th>
                    <th>Edit</th>
                <th>Delete</th>';

        while ($rows = mysqli_fetch_assoc($result)) {
            $output .= "<tr><td>{$rows["id"]}</td><td>{$rows["username"]}</td><td>{$rows["email"]}</td><td>{$rows["phone"]}</td> <td>{$rows["salary"]}</td>
            <td>{$rows["role"]}</td><td><button class = 'edit-btn' data-eid={$rows["id"]}>Edit</button></td><td><button class='delete-btn' data-id='{$rows["id"]}'>Delete</button></td></tr>";
        }

        $output .= "</table>";

        echo $output;

    mysqli_stmt_close($stmt_read);
} else {
    echo "<h2>Data not found</h2>";
}
?>