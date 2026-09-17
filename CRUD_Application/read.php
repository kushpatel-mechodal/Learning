<?php

session_start();

require_once __DIR__ . "/connection.php";

//check the user logged in or not
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

//logged in user information

$id = $_SESSION["id"];
$role = $_SESSION["role"];

if ($role === "admin") {
    $sql = "SELECT id,username,email,phone,salary,role FROM employees";

    $stmt = mysqli_prepare($conn, $sql);
} else {
    $sql = "SELECT id,username,email,phone,salary,role FROM employees where id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);
}

if (!$stmt) {
    echo "Failed prepare" . mysqli_error($conn);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>

<body>
    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table  table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">UserName</th>
                        <th scope="col">Email Address</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Role</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($rows = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $rows['id'] ?></th>
                                <td><?php echo $rows['username'] ?></td>
                                <td><?php echo $rows['email'] ?></td>
                                <td><?php echo $rows['phone'] ?></td>
                                <td><?php echo $rows['salary'] ?></td>
                                <td><?php echo $rows['role']; ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3 ">

                                        <form action="update.php" method="get">
                                            <input type="hidden" name="id" value="<?php echo $rows["id"] ?>">
                                            <button type="submit" name="submit" class="btn btn-warning">Edit</button>
                                        </form>

                                        <?php if ($role === "admin") { ?>
                                            <form action="delete.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $rows["id"] ?>">
                                                <button type="submit" name="submit" class="btn btn-danger">Delete</button>
                                            </form>

                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>

                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5">No Record Found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>