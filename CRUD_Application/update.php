<?php

session_start();

require_once __DIR__ . "/connection.php";

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "SELECT * FROM employees where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Prepare failed" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$rows) {
        echo "No data found";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $salary = $_POST["salary"];
    $role = $_POST["role"];

    $sql = "UPDATE employees SET username=?,password=?,email=?,phone=?,salary=?,role=? WHERE id=?";

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Prepare failed" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "ssssdsi", $username, $hashed, $email, $phone, $salary, $role, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("location: read.php?update=1");
        exit;
    } else {
        echo "Error for Updating data" . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

if (isset($_GET["update"])) {
    echo "<script>alert('Update data successfully');
    window.location.href = 'read.php';
    </script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form method="post" class="container">
        <h2>Employee Registration</h2>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        Username <input type="text" name="username" value="<?php echo $rows["username"] ?>"
            placeholder="Enter Your Username" required><br />
        password <input type="text" name="password" value="<?php echo $rows["password"]; ?>"
            placeholder="Enter Your password" required><br />
        Email Address<input type="text" name="email" value="<?php echo $rows["email"] ?>"
            placeholder="Enter Your Email Address" required><br />
        Phone Number<input type="text" name="phone" value="<?php echo $rows["phone"] ?>"
            placeholder="Enter Your Phone Number" maxlength="10" required><br />
        Salary <input type="text" name="salary" value="<?php echo $rows["salary"] ?>" placeholder="Enter Your Salary"
            required><br />

        <?php
        if ($_SESSION["role"] === "admin") {
            ?>
            Role <select name="role">
                <option name="role" <?php ($rows["role"] == "employee") ? 'selected' : ''; ?>>Employee</option>
                <option name="role" <?php ($rows["role"] == "admin") ? 'selected' : ''; ?>>Admin</option>
            </select>
            <?php
        } else {
            ?>
            <input type="text" value="<?php echo $rows["role"]; ?>" readonly>
            <?php
        }
        ?>
        <br />

        <button type="submit" name="submit">Edit Data</button>

    </form>
</body>

</html>