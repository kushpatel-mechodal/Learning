<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>

<body>
    <form method="post" action="upload.php" enctype="multipart/form-data">
        Select Your File:
        <input type="file" name="file" id="file"><br />
        <button type="submit" name="submit" value="Upload file">
            Upload File</button>
    </form>
</body>

</html>