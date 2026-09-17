<?php

require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $salary = $_POST["salary"];
    $role = $_POST["role"];

    $sql = "INSERT INTO employees (username,password,email,phone,salary,role) VALUES (?,?,?,?,?,?)";

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Prepare Failed" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "ssssds", $username, $hashed, $email, $phone, $salary, $role);

    if (mysqli_stmt_execute($stmt)) {

        header("location: create.php?success=1");
        exit;
    } else {
        echo "Error for Inserting Record" . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

if (isset($_GET["success"])) {
    echo "<script>alert('Insert data successfully');
    window.location.href = 'create.php';
    </script>";
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
        <h2>Employee Registration</h2>
        Username <input type="text" name="username" placeholder="Enter Your Username" required><br />
        Password <input type="password" name="password" placeholder="Enter Your Password" required><br />
        Email Address<input type="text" name="email" placeholder="Enter Your Email Address" required><br />
        Phone Number<input type="text" name="phone" placeholder="Enter Your Phone Number" maxlength="10" required><br />
        Salary <input type="text" name="salary" placeholder="Enter Your Salary" required><br />
        Role <select name="role">
            <option value="">Select Your Role</option>
            <option name="role" value="employee">Employee</option>
            <option name="role" value="admin">Admin</option>
        </select><br />

        Already have an account?<a href="login.php"> Go to Login Page</a>

        <button type="submit" name="submit">Registration</button>

    </form>
</body>

</html>