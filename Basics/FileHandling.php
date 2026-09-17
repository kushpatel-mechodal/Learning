<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Handling</title>
</head>

<body>
    <?php

    //file open and read
    $file = fopen("test.txt", "r") or die("File Not Found");
    echo $file;
    echo "<br/>";
    $result = fread($file, filesize("test.txt"));
    echo $result;
    fclose($file);

    //file open and write
    

    ?>
</body>

</html>