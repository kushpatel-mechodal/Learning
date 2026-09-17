<?php

session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    if (isset($_SESSION["name"])) {

        echo "Fetch Data is: " . "<br/><br/>";
        echo "The name is: " . $_SESSION["name"] . "<br/>";
        echo "The age is: " . $_SESSION["age"] . "<br/>";
        echo "The age is: " . $_SESSION["city"];
    } else {
        echo "Session not found";
    }

    // print_r($_SESSION);
    ?>
</body>

</html>