<?php

require_once __DIR__ . "/connection.php";

if (isset($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "SELECT firstname,lastname,email from employees where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Prepared Failed" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "i", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$rows) {
        echo "Data not found";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];

    $sql = "UPDATE employees SET firstname = ? ,lastname = ? ,email = ? where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        echo "Failed Prepare" . mysqli_error($conn);
    }

    mysqli_stmt_bind_param($stmt, "sssi", $firstname, $lastname, $email, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "Update Data Successfully";
    } else {
        echo "Error for update the data" . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Form</title>
</head>

<body>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        firstName: <input type="text" name="firstname" value="<?php echo $rows["firstname"]; ?>"><br />
        LastName: <input type="text" name="lastname" value="<?php echo $rows["lastname"]; ?>"><br />
        Email Addrress: <input type="email" name="email" value="<?php echo $rows["email"]; ?>"><br />

        <button type="submit" name="submit">Update</button>
        <button type="button" onclick="goBack()">Back</button>

    </form>

    <script>
        function goBack(){
            window.location.href = "select.php";
        }
    </script>
</body>

</html>