<?php
session_start();
require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT id,email,password,role FROM employees where email=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Failed Prepare" . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_assoc($result);

    if ($rows && password_verify($password, $rows["password"])) {

        $_SESSION["id"] = $rows["id"];
        $_SESSION["email"] = $rows["email"];
        $_SESSION["role"] = $rows["role"];

        header("location: read.php");
        exit;
    } else {
        mysqli_stmt_close($stmt);
        echo "<script>alert('Invalid email or password');
        window.location.href = 'login.php'</script>";
    }


}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form method="post" class="container">
        <h2>Employee Login</h2>
        Email Address<input type="email" name="email" placeholder="Enter Your Email Address" required><br />
        Password <input type="password" name="password" placeholder="Enter Your Password" required><br />
        have a no account?<a href="create.php"> Go Register Page</a>

        <button type="submit" name="submit">Login</button>

    </form>
</body>

</html>