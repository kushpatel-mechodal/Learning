<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    $file = fopen("newfile.txt", "r") or die("File Not Found");
    $txt = fread($file, filesize("newfile.txt"));
    echo $txt;
    fclose($file);
    
    $myFile = fopen("newfile.txt", "w") or die("Unable to create the file");
    $txt = "I learn the PHP\n";
    fwrite($myFile, $txt);
    $txt = "This file was created\n";
    fwrite($myFile, $txt);
    fclose($myFile);
    ?>
</body>

</html>