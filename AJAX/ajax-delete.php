<?php

$conn = mysqli_connect("localhost", "root", "", "emp_db");

$student_id = $_POST["id"];

$delete = "DELETE FROM employees WHERE id='$student_id'";

$stmt_delete = mysqli_prepare($conn,$delete);

if(mysqli_stmt_execute($stmt_delete)){
        echo 1;
}else{
        echo 0;
    }

    mysqli_stmt_close($stmt_delete);
?>