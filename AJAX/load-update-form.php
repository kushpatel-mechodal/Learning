<?php

require_once __DIR__ . "/connection.php";

$student_id = isset($_POST["stud_id"]) ? intval($_POST["stud_id"]) : 0;
$username = isset($_POST["uname"]) ? $_POST["uname"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$phone = isset($_POST["phone"]) ? $_POST["phone"] : "";
$salary = isset($_POST["salary"]) ? $_POST["salary"] : "";
$role = isset($_POST["role"]) ? $_POST["role"] : "";

$update = "UPDATE employees SET username = ?, email = ?, password = ?, phone = ?, salary = ?, role = ? WHERE id = ?";

$stmt_update = mysqli_prepare($conn, $update);

if ($stmt_update) {
    mysqli_stmt_bind_param($stmt_update, "ssssdsi", $username, $email, $password, $phone, $salary, $role, $student_id);

    if (mysqli_stmt_execute($stmt_update)) {
        echo 1;
    } else {
        echo 0;
    }

    mysqli_stmt_close($stmt_update);
} else {
    echo 0;
}
?>
