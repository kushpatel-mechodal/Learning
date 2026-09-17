<?php

require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id"];

    $sql = "DELETE FROM employees where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Failed to prepare" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Record Deleted Successfully";
    } else {
        echo "Error for deleting data" . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

?>