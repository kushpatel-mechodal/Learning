<?php

//session start
session_start();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session</title>
</head>

<body>
    <?php
    $_SESSION["name"] = "Kush patel";
    $_SESSION["age"] = "23";
    $_SESSION["city"] = "Ahemdabad";

    // echo "Session are set";
    echo "Username is: " . $_SESSION["name"] . "<br/>";
    echo "Age is: " . $_SESSION["age"]."<br/>";
    echo "City is: " . $_SESSION["city"];
    ?>
</body>

</html>