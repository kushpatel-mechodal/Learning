<?php

include "html.php";
use html as h;

$table = new h\Table();
$table->title = "My table";
$table->numrow = 30;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php $table->message(); ?>
</body>

</html>