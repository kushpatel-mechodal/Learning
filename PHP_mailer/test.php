<?php

$conn = mysqli_connect("localhost", "root", "", "emp_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql_read = "SELECT * FROM employees";

$stmt = mysqli_prepare($conn, $sql_read);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th {
            background-color: #eeeeee;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>

    <h1>Employee Data</h1>

    <table>

        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Salary</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($rows = mysqli_fetch_assoc($result)) { ?>

                <tr>
                    <td><?= $rows["username"] ?></td>
                    <td><?= $rows["email"] ?></td>
                    <td><?= $rows["phone"] ?></td>
                    <td><?= $rows["salary"] ?></td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
</body>
</html>