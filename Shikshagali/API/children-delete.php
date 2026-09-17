<?php

require("./connection.php");

$children_id = $_POST["id"];

$sql_delete = "DELETE FROM children WHERE id='$children_id'";

$stmt_children = mysqli_prepare($conn, $sql_delete);

if (mysqli_stmt_execute($stmt_children)) {
    echo 1;
} else {
    echo 0;
}

mysqli_stmt_close($stmt_children);
?>

