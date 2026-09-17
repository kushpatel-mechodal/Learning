<?php

require("./connection.php");

$id = $_POST["id"];

$sql_delete = "DELETE FROM chapters WHERE id='$id'";

$stmt_delete = mysqli_prepare($conn, $sql_delete);

if(mysqli_stmt_execute($stmt_delete)){
    echo 1;
}else{
    echo 0;
}

mysqli_stmt_close($stmt_delete);
?>
