<?php

namespace Html;

class Table
{
    public $title = "";
    public $rows = 0;

    public function intro()
    {
        echo "Title is: " . $this->title . "<br/>" . "Rows is: " . $this->rows;
        echo "<br/>";
    }
}

$table = new \Html\Table();
$table->title = "Employees";
$table->rows = 10;

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
    $table->intro();
    ?>
</body>

</html>