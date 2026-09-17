<?php

require_once __DIR__ . "/connection.php";

//Create the database
$sql = "CREATE DATABASE IF NOT EXISTS emp_db";

if (mysqli_query($conn, $sql)) {
    echo "Database created successfully";
    echo "<br/>";
} else {
    echo "Error for creating database" . mysqli_error($conn);
}

//create the tables

$sql = "CREATE TABLE IF NOT EXISTS employees (
 id int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username varchar(30) NOT NULL,
 password varchar(100) NOT NULL,
 email varchar(30) NOT NULL,
 phone int(10) NOT NULL,
 salary DECIMAL(10,2) NOT NULL,
 role varchar(20) NOT NULL,
 reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

if (mysqli_query($conn, $sql)) {
    echo "Table created successfully";

} else {
    echo "Error for creating table" . mysqli_error($conn);
}
?>