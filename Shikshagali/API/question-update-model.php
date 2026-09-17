<?php

require("./connection.php");

$question_id = $_POST["id"];

$sql_update = "SELECT q.*,c.name AS chapter_name FROM questions q 
LEFT JOIN chapters c ON q.chapter_id = c.id WHERE q.id='$question_id'";

$stmt_update = mysqli_prepare($conn, $sql_update);

if (mysqli_stmt_execute($stmt_update)) {

    $output = "";
    $res_question = mysqli_stmt_get_result($stmt_update);

    if (mysqli_num_rows($res_question) > 0) {

        while ($rows = mysqli_fetch_assoc($res_question)) {

            $output .= "<tr>
                        <td>Question Name</td>
                        <td>
                        <input type='text' id='edit-question' value='{$rows['question']}'>
                        <input type='hidden' id='edit-id' value='{$rows['id']}'>
                        </td>
                    </tr>
                    <tr>
                        <td>Option 1</td>
                        <td><input type='text' id='edit-option1' value='{$rows['option1']}'></td>
                    </tr>
                    <tr>
                        <td>Option 2</td>
                        <td><input type='text' id='edit-option2' value='{$rows['option2']}'></td>
                    </tr>
                    <tr>
                        <td>Option 3</td>
                        <td><input type='text' id='edit-option3' value='{$rows['option3']}'></td>
                    </tr>
                    <tr>
                        <td>Option 4</td>
                        <td><input type='text' id='edit-option4' value='{$rows['option4']}'></td>
                    </tr>
                    <tr>
                        <td>Correct Answer</td>
                        <td><input type='text' id='edit-correct-answer' value='{$rows['correct_answer']}'></td>
                    </tr>
                    <tr>
                        <td>Chapter id</td>
                        <td><input type='text' id='edit-chapter-id' value='{$rows['chapter_id']}'></td>
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
    die("Query Failed");
}
