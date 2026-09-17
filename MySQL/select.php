<?php

require_once __DIR__ . "/connection.php";

$sql = "SELECT * FROM employees limit 10 OFFSET 5";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($rows = mysqli_fetch_assoc($result)) {
        echo "id: " . $rows["id"] . "<br/>" . "FirstName: " . $rows["firstname"] . "<br/>" . "LastName: " . $rows["lastname"] . "<br/>"."Email Address".$rows["email"]."<br/>";
        echo "<hr/>";

        ?>

        <form action="update.php" method="get">
            <input type="hidden" name="id" value="<?php echo $rows["id"]; ?>">
            <button type="submit">Update</button>
        </form>

        <form action="delete.php" method="post">
            <input type="hidden" name="id" value="<?php echo $rows["id"]; ?>">
            <button type="submit">Delete</button>
        </form>

        <hr>

        <?php
    }
} else {
    echo "No record Found";
}
?>