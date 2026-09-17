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

    //unset all the session variable
    session_unset();

    //Destroy the session
    session_destroy();

    echo "Session has destroyed";
    ?>
</body>

</html>