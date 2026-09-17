<?php
require("./connection.php");
$chapter_id = $_POST["id"] ?? '';

$read = "SELECT c.*,s.english_name AS subject_name,st.name AS standard_name  FROM chapters c 
        LEFT JOIN subjects s ON c.subject = s.id 
        LEFT JOIN standards st ON c.standard = st.id WHERE c.id='$chapter_id'";

$stmt_model = mysqli_prepare($conn, $read);

if (mysqli_stmt_execute($stmt_model)) {
    $res_model = mysqli_stmt_get_result($stmt_model);
    $output = "";

    if (mysqli_num_rows($res_model) > 0) {
        while ($rows = mysqli_fetch_assoc($res_model)) {
            $output .= "
                <tr>
                  <td>Chapter no</td>
                    <td>
                        <input type='text' id='edit-chapter' value='{$rows['chapter_no']}'>
                        <input type='hidden' id='edit-id' value='{$rows['id']}'>
                    </td>
                </tr>

                <tr>
                    <td>Chapter Name</td>
                    <td>
                        <input type='text' id='edit-name' value='{$rows['name']}'>
                    </td>
                </tr>

                <tr>
                    <td>Subject</td>
                    <td>
                        <input type='text' id='edit-subject' value='{$rows['subject_name']}'>
                    </td>
                </tr>

                <tr>
                    <td>Standard</td>
                    <td>
                        <input type='text' id='edit-standard' value='{$rows['standard_name']}'>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input type='submit' id='edit-submit' value='Save'>
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
