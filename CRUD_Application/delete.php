<?php

require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $id = $_POST["id"];

    $sql = "DELETE FROM employees where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Prepare Failed" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("location: read.php?delete=1");
        exit;
    } else {
        echo "Error for Deleting record" . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

if ($_GET["delete"]) {
    echo "<script>alert('Delete Data Successfully');
    window.location.href='read.php';
    </script>";
}
?>