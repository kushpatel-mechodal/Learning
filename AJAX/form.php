<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Form Data</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="main">
        <div id="header">
            <h1>AJAX Serialized Form Data</h1>
        </div>

        <div id="table-data">
            <form id="form-data">
                <table width="100%" cellspacing="0" cellpadding="10px" border="1">
                    <tr>
                        <td>
                            <label for="uname">Username</label>
                            <input type="text" name="username" id="uname">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="email">email</label>
                            <input type="email" name="email" id="email">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="password">password</label>
                            <input type="password" name="password" id="password">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="phone">phone</label>
                            <input type="text" name="phone" id="phone" maxlength="10">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="salary">salary</label>
                            <input type="text" name="salary" id="salary">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="role">role</label>
                            <input type="text" name="role" id="role">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button type="submit" name="submit" id="submit" value="submit">Submit</button>
                        </td>
                    </tr>
                </table>
            </form>
            <div id="response"></div>
        </div>
    </div>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#submit").click(function(e) {
                e.preventDefault();

                let username = $("#uname").val();
                let email = $("#email").val();
                let password = $("#password").val();
                let phone = $("#phone").val();
                let salary = $("#salary").val();
                let role = $("#role").val();

                if (username == "" || email == "" || password == "" || phone == "" || salary == "" || role == "") {
                    $("#response").fadeIn();
                    $("#response").removeClass("success-msg").addClass("error-msg").html("All fields are required");
                }

                $.ajax({
                    url: "ajax-form-data.php",
                    type: "POST",
                    data: $("#form-data").serialize(), //submit all form data into one string 
                    beforeSend: function() { 
                        $("#response").fadeIn();
                        $("#response").removeClass("success-msg error-msg").addClass("error-msg").html("Loading Data...");
                    },
                    success: function(data) {
                        $("#form-data").trigger("reset");
                        $("#response").fadeIn();
                        $("#response").removeClass("error-msg").addClass("success-msg").html(data);
                        setTimeout(function() {
                            $("#response").fadeOut("slow");
                        }, 4000);
                    }
                });
            });
        });
    </script>
</body>

</html>