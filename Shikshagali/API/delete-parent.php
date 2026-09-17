<?php

require("./connection.php");

$id = $_POST["id"];

$sql_delete = "DELETE FROM parents WHERE id='$id'";

$stmt_parents = mysqli_prepare($conn,$sql_delete);

if(mysqli_stmt_execute($stmt_parents)){
    echo 1;
}else{
    echo 0;
}

mysqli_stmt_close($stmt_parents);

?>