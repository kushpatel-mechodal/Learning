<?php
$name = "username";
$value = "Kush patel";
setcookie("test_cookie", "test", time() + 3600, "/");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookies</title>
</head>

<body>
    <?php
    if (isset($_COOKIE[$name])) {
        echo "cookie " . $name . " is set <br />";
        echo "value is: " . $_COOKIE[$name] . " is set <br />";
    } else {
        echo "The cookie is" . $name . "is not set";
    }

    if (count($_COOKIE) > 0) {
        echo "Cookie is store";
    } else {
        echo "Cookie is not store";
    }
    ?>
</body>

</html>