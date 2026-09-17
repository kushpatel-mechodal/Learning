<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter</title>
</head>

<body>
    <?php

    $email = "kushpatel@gmail.com";

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "$email valid email";
    } else {
        echo "$email invalid email";
    }

    ?>
</body>

</html>