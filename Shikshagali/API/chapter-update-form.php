<?php

require("./connection.php");

$chapter_id = $_POST["chapter_id"] ?? '';
$chapter_no = $_POST["chapter_no"] ?? '';
$chapter_name = $_POST["chapter_name"] ?? '';
$subject = $_POST["subject"] ?? '';
$standard = $_POST["standard"] ?? '';

$update = "UPDATE chapters SET chapter_no = '{$chapter_no}', name = '{$chapter_name}',
subject = '{$subject}', standard = '{$standard}' WHERE id='{$chapter_id}'";

$stmt_form = mysqli_prepare($conn, $update);

if (mysqli_stmt_execute($stmt_form)) {
    echo 1;
} else {
    echo 0;
}

mysqli_stmt_close($stmt_form);
