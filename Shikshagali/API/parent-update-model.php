<?php
require("./connection.php");
$parent_id = $_POST["id"] ?? '';

$read = "SELECT * FROM parents WHERE id='$parent_id'";

$stmt_model = mysqli_prepare($conn, $read);

if (mysqli_stmt_execute($stmt_model)) {
    $res_model = mysqli_stmt_get_result($stmt_model);
    $output = "";

    if (mysqli_num_rows($res_model) > 0) {
        while ($rows = mysqli_fetch_assoc($res_model)) {
            $output .= "
                    <tr>
                        <td>Parent Name</td>
                        <td>
                            <input type='text' id='edit-name' value='{$rows['name']}' >
                            <input type='hidden' id='edit-id' value='{$rows['id']}'>
                        </td>
                    </tr>

                    <tr>
                        <td>Mobile No</td>
                        <td>
                            <input type='text' id='edit-mobile-no' value='{$rows['mobile']}'>
                        </td>
                    </tr>

                    <tr>
                        <td>Email</td>
                        <td>
                            <input type='text' id='edit-email' value='{$rows['email']}'>
                        </td>
                    </tr>

                      <tr>
                        <td>Password</td>
                        <td>
                            <input type='password' id='edit-password' value='{$rows['password']}'>
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' id='edit-submit' value='Save' >
                        </td>
                    </tr>";
        }
        echo $output;
        mysqli_stmt_close($stmt_model);
    } else {
        echo "<tr><td>Data not found</td></tr>";
    }
} else {
    die("Query Failed");
}
