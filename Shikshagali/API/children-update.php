<?php

require("./connection.php");

$id = $_POST["id"];
$child_name = $_POST["child_name"];
$state = $_POST["state_id"];
$district = $_POST["district_id"];
$taluka = $_POST["taluka_id"];
$school_name = $_POST["school_name"];
$standard = $_POST["standard"];
$gender = $_POST["gender"];
$reward = $_POST["reward"];

$sql_update = "UPDATE children SET child_name='$child_name',state_id='$state'
,district_id = '$district',taluka_id='$taluka',school_name='$school_name',standard = '$standard',
gender='$gender',reward='$reward' WHERE id='$id'";

$stmt_children = mysqli_prepare($conn,$sql_update);

if(mysqli_stmt_execute($stmt_children)){
    echo 1;    
}else{
    echo 0;
}

mysqli_stmt_close($stmt_children);
?>