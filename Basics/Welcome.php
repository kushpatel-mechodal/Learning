<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    Welcome <?php echo htmlspecialchars($_GET["name"]); ?><br />
    Your Email is <?php echo htmlspecialchars($_GET["email"]); ?>;


</body>

</html>