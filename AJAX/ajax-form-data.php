<?php

require("../CRUD_Application/connection.php");

$username = $_POST["username"];
$email = $_POST["email"];
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$phone = $_POST["phone"];
$salary = $_POST["salary"];
$role = $_POST["role"];

if (empty($username) || empty($email) || empty($password) || empty($phone) || empty($salary) || empty($role)) {
    echo "All fields are required";
} else {

    $insert = "INSERT INTO employees (username,email,password,phone,salary,role) VALUES('$username','$email',
    '$password','$phone','$salary','$role')";

    $stmt_insert = mysqli_prepare($conn, $insert);

        if (mysqli_stmt_execute($stmt_insert)) {
            echo "{$username} Your record is saved";
        } else {
        echo "Failed to saved record";
    }
}
?>
