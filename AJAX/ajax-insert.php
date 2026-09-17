<?php

$conn = mysqli_connect("localhost", "root", "", "emp_db");

if (!$conn) {
    die("Connection Failed");
}

$user_name = $_POST["user_name"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$phone = $_POST["phone"];
$salary = $_POST["salary"];
$role = $_POST["role"];

$insert = "INSERT INTO employees (username, password, email, phone, salary, role) VALUES (?, ?, ?, ?, ?, ?)";

$stmt_insert = mysqli_prepare($conn, $insert);

if (!$stmt_insert) {
    die("Query Failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_insert,"ssssds",$user_name,$password,$email,$phone,$salary,$role);

if (mysqli_stmt_execute($stmt_insert)) {
    echo 1;
} else {
    echo 0;
}

mysqli_stmt_close($stmt_insert);
mysqli_close($conn);

?>