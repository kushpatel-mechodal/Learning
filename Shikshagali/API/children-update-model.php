<?php

require("./connection.php");

$children_id = $_POST["id"];

$sql_update = "SELECT c.*,st.state_name AS state_name,d.district_name AS district_name
,t.taluka_name AS taluka_name,s.name AS standard_name FROM children c 
LEFT JOIN states st ON c.state_id = st.id
LEFT JOIN districts d ON c.district_id = d.id
LEFT JOIN talukas t ON c.taluka_id = t.id
LEFT JOIN standards s ON c.standard = s.id WHERE c.id='$children_id'";

$stmt_update = mysqli_prepare($conn, $sql_update);

if (mysqli_stmt_execute($stmt_update)) {

    $output = "";
    $res_update = mysqli_stmt_get_result($stmt_update);

    if (mysqli_num_rows($res_update) > 0) {

        while ($rows = mysqli_fetch_assoc($res_update)) {

            $output .= "
                    <tr>
                        <td>Child Name</td>
                        <td>
                        <input type='text' id='edit-name' value='{$rows['child_name']}'>
                        <input type='hidden' id='edit-id' value='{$rows['id']}'>
                        </td>
                    </tr>
                    <tr>
                        <td>State Name</td>
                        <td>
                        
                        <input type='text' id='edit-state' value='{$rows['state_id']}'>
                        </td>
                    </tr>
                     <tr>
                        <td>District Name</td>
                        <td>
                            <input type='text' id='edit-district' value='{$rows['district_id']}'>
                        </td>
                    </tr>
                    <tr>
                        <td>Taluka Name</td>
                        <td> 
                        <input type='text' id='edit-taluka' value='{$rows['taluka_id']}'>
                        </td>
                    </tr>
                    <tr>
                        <td>School Name</td>
                        <td><input type='text' id='edit-school-name' value='{$rows['school_name']}'></td>
                    </tr>
                    <tr>
                        <td>standard Name</td>
                        <td>
                        <input type='text' id='edit-standard' value='{$rows['standard']}'>
                        </td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><input type='text' id='edit-gender' value='{$rows['gender']}'></td>
                    </tr>
                    <tr>
                        <td>Reward</td>
                        <td><input type='text' id='edit-reward' value='{$rows['reward']}'></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type='submit' id='edit-submit' value='Update'></td>
                    </tr>";
        }
        echo $output;
        mysqli_stmt_close($stmt_update);
    } else {
        echo "Data not found";
    }
} else {
    echo "Query Failed";
}
