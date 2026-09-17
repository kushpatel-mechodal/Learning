<?php


require_once __DIR__ . "/connection.php";

//Create the database

$sql = "CREATE DATABASE IF NOT EXISTS employee_db";

if (mysqli_query($conn, $sql)) {
    echo "Database Created Successfully";
} else {
    echo "Error for creating database" . mysqli_error($conn);
}

mysqli_select_db($conn, "employee_db");

// Create the table

$sql = "CREATE TABLE IF NOT EXISTS employees( id int(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(50) NOT NULL,
        lastname VARCHAR(50) NOT NULL,
        email VARCHAR(50),
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

echo "<br/>";
if (mysqli_query($conn, $sql)) {
    echo "Table Created Successfully";
} else {
    echo "Error for creating table" . mysqli_error($conn);
}
echo "<br/>";
?>