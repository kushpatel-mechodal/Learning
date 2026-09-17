<?php

require("./connection.php");

$question_id = $_POST["id"];
$question_name = $_POST["question_name"];
$option1 = $_POST["option1"];
$option2 = $_POST["option2"];
$option3 = $_POST["option3"];
$option4 = $_POST["option4"];
$correct_answer = $_POST["correct_answer"];
$chapter_id = $_POST["chapter_id"];

$sql_update = "UPDATE questions SET question = '$question_name',option1 = '$option1',
option2 = '$option2', option3='$option3',option4='$option4',correct_answer = '$correct_answer'
,chapter_id='$chapter_id' WHERE id='$question_id'";

$stmt_question = mysqli_prepare($conn,$sql_update);

if(mysqli_stmt_execute($stmt_question)){
    echo 1;
}else{
    echo 0;
}

mysqli_stmt_close($stmt_question);
?>