<?php

require("../CRUD_Application/connection.php");

$student_id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

$read = "SELECT * FROM employees WHERE id = ?";

$stmt_read = mysqli_prepare($conn, $read);

if ($stmt_read) {
    mysqli_stmt_bind_param($stmt_read, "i", $student_id);
    mysqli_stmt_execute($stmt_read);

    $result = mysqli_stmt_get_result($stmt_read);

    $output = "";

    if (mysqli_num_rows($result) > 0) {

        while ($rows = mysqli_fetch_assoc($result)) {
            $id = htmlspecialchars($rows["id"] ?? '');
            $username = htmlspecialchars($rows["username"] ?? '');
            $email = htmlspecialchars($rows["email"] ?? '');
            $password = htmlspecialchars($rows["password"] ?? '');
            $phone = htmlspecialchars($rows["phone"] ?? '');
            $salary = htmlspecialchars($rows["salary"] ?? '');
            $role = htmlspecialchars($rows["role"] ?? '');

            $output .= "
            <tr>
                <td>Username</td>
                <td>
                    <input type='text' id='edit-uname' value='{$username}'>
                    <input type='text' id='edit-id' hidden value='{$id}'>
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type='text' id='edit-email' value='{$email}'></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type='password' id='edit-password' value='{$password}'></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><input type='text' id='edit-phone' value='{$phone}'></td>
            </tr>
            <tr>
                <td>Salary</td>
                <td><input type='text' id='edit-salary' value='{$salary}'></td>
            </tr>
            <tr>
                <td>Role</td>
                <td><input type='text' id='edit-role' value='{$role}'></td>
            </tr>
            <tr>
                <td></td>
                <td><input type='submit' id='edit-submit' value='save'></td>
            </tr>";
        }

        echo $output;

        mysqli_stmt_close($stmt_read);
    } else {
        echo "<h2>Data not found</h2>";
    }
} else {
    echo "<h2>Query failed</h2>";
}
?>
