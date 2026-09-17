<?php

require("./connection.php");

$question_id = $_POST["id"];

$sql_delete = "DELETE FROM questions WHERE id='$question_id'";

$stmt_question = mysqli_prepare($conn,$sql_delete);

if(mysqli_stmt_execute($stmt_question)){
    echo 1;
}else{
    echo 0;
}

mysqli_stmt_close($stmt_question);
?>