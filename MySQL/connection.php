<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "employee_db";

$conn = mysqli_connect($servername, $username, $password, $database);


if (!$conn) {
    die("Connection failed" . mysqli_connect_error());
}

echo "<br/>";

?>