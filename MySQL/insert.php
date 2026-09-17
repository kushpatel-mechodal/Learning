<?php

require_once __DIR__ . "/connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];

    //Insert the data
    $sql = "INSERT INTO employees (firstname, lastname, email) VALUES(?,?,?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Prepare failed" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "sss", $firstname, $lastname, $email);

    if (mysqli_stmt_execute($stmt)) {
        // $last_id = mysqli_insert_id($conn);
        echo "Insert Data Successfully ";
    } else {
        echo "Error for insert the data" . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <form method="post" class="container">
        <h2>Employee Registration</h2>
        First Name <input type="text" name="firstname" placeholder="Enter Your First Name" required><br />
        last Name <input type="text" name="lastname" placeholder="Enter Your last Name" required><br />
        Email Address <input type="email" name="email" placeholder="Enter Your email adddress" required><br />
        <button type="submit" name="submit">Registration</button>
    </form>
</body>

</html>