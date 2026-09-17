<?php

require("./connection.php");

$parent_id = $_POST["parent_id"];
$parent_name = $_POST["parent_name"];
$mobile_no = $_POST["mobile_no"];
$email = $_POST["email"];
$password = password_hash($_POST["password"],PASSWORD_DEFAULT);

$update = "UPDATE parents SET name='$parent_name', mobile='$mobile_no',email='$email', password='$password'
WHERE id = '$parent_id'";

$stmt_parent_model = mysqli_prepare($conn,$update);

if(mysqli_stmt_execute($stmt_parent_model)){
    echo 1;
}else{
    echo 0;
}

mysqli_stmt_close($stmt_parent_model);

?>