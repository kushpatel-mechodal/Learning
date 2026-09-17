<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "callbeex_shikshagali";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("connection failed" . mysqli_connect_error());
}

?>