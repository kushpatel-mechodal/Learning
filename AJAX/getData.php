<?php

$q = intval($_GET["q"]);

$con = mysqli_connect("localhost", "root", "", "employee_db");

if (!$con) {
    die("Could not connect: " . mysqli_connect_error());
}

$sql = "SELECT * FROM employees WHERE id = $q";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

    if (mysqli_num_rows($result) > 0) {

        echo "<table border='1' cellpadding='10'>";

        echo "<tr>";
    echo "<th>Firstname</th>";
    echo "<th>Lastname</th>";
        echo "<th>Email Address</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<tr>";

        echo "<td>" . $row["firstname"] . "</td>";

        echo "<td>" . $row["lastname"] . "</td>";

        echo "<td>" . $row["email"] . "</td>";

            echo "</tr>";
        }

        echo "</table>";

} else {

    echo "No employee found.";

}

mysqli_close($con);

?>