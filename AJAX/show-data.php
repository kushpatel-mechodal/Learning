<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Data</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <table id="main" border="0" cellspacing="0">
        <tr>
            <td id=header>
                <h3>PHP with Ajax</h3>
            </td>
        </tr>
        <tr>
            <td id="table-load">
                <button type="button" id="load-button" value="Load Data">Load Data</button>
            </td>
        </tr>
        <tr>
            <td id="table-data"></td>
        </tr>
    </table>

    <script type="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function () { //ensure to execute the javascript code only after dom is fully loaded and parse
            $("#load-button").on("click", function (e) {
                $.ajax({
                    url: "ajax-load.php",
                    type: "POST",
                    // success can be use to AJAX request is complete after send response from the backend 
                    success: function (data) {
                        $("#table-data").html(data);
                    }
                });
            });
        });  
    </script>
</body>

</html>