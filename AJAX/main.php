<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert data</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <table id="main" border="0" cellspacing="0">
        <tr>
            <td id="header">
                <h3>AJAX Insert Data</h3>
                <div id="search-bar">
                    search <input type="text" id="search" autocomplete="off">
                </div>
            </td>
        </tr>

        <tr>
            <td id="table-form">
                <form id="insert-form">
                    username <input type="text" id="uname">
                    email <input type="text" id="email">
                    password <input type="password" id="password">
                    phone <input type="text" id="phone">
                    salary <input type="text" id="salary">
                    role <input type="text" id="role">
                    <input type="submit" id="submit-button" value="Save">
                </form>
            </td>
        </tr>
        <tr>
            <td id="table-data"></td>
        </tr>
    </table>
    <div id="error-message"></div>
    <div id="success-message"></div>
    <div id="modal">
        <div id="modal-box">
            <h2>Edit Form</h2>
            <table cellpadding="10px" width="100%">
                <tr>
                    <td>Username</td>
                    <td><input type="text" id="edit-uname"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" id="edit-email"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" id="edit-password"></td>
                </tr>
                <tr>
                    <td>phone</td>
                    <td><input type="text" id="edit-phone"></td>
                </tr>
                <tr>
                    <td>Salary</td>
                    <td><input type="text" id="edit-salary"></td>
                </tr>
                <tr>
                    <td>Role</td>
                    <td><input type="text" id="edit-role"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="edit-submit" value="save"></td>
                </tr>
            </table>
            <div>
                <button type="button" id="close-btn">X</button>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            //Load table record
            function loadTable() {

                $.ajax({
                    url: "ajax-load.php",
                    type: "POST",

                    success: function(data) {
                        $("#table-data").html(data);
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

            }

            loadTable();

            //Insert record
            $("#submit-button").on("click", function(e) {
                e.preventDefault();

                let uname = $("#uname").val();
                let email = $("#email").val();
                let password = $("#password").val();
                let phone = $("#phone").val();
                let salary = $("#salary").val();
                let role = $("#role").val();

                if (uname == "" || email == "" || password == "" || phone == "" || salary == "" || role == "") {
                    $("#error-message").html("All Fields are required").slideDown();
                    $("#success-message").slideup();
                } else {
                    $.ajax({
                        url: "ajax-insert.php",
                        type: "POST",
                        data: {
                            user_name: uname,
                            email: email,
                            password: password,
                            phone: phone,
                            salary: salary,
                            role: role
                        },
                        success: function(data) {
                            console.log(data);
                            if (data == 1) {
                                loadTable();
                                $("#success-message").html("Data saved successfully").slideDown();
                                $("#error-message").slidup();
                            } else {
                                $("#error-message").html("Failed to save data").slideDown();
                                $("#success-message").slidup();
                            }
                        },
                    });
                }
            });

            //delete record
            $(document).on("click", ".delete-btn", function() {
                if (confirm("Are you sure to delete")) {

                    let student_id = $(this).data("id");
                    let element = this;
                    $.ajax({
                        url: "ajax-delete.php",
                        type: "POST",
                        data: {
                            id: student_id,
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(element).closest("tr").fadeOut(150, function() {
                                    $(this).remove();
                                });
                            } else {
                                $("#error-message").html("Failed to delete the data").slideDown();
                                $("$success-message").slideUp();
                            }
                        }
                    });
                }
            });

            //show model box
            $(document).on("click", ".edit-btn", function() {
                $("#modal").show();
                let student_id = $(this).data("eid");

                $.ajax({
                    url: "load-update-modal.php",
                    type: "POST",
                    data: {
                        id: student_id
                    },
                    success: function(data) {
                        $("#modal-box table").html(data);
                    }
                });
            });

            // Update form record
            $("#edit-submit").on("click", function() {

                let student_id = $("#edit-id").val();
                let username = $("#edit-uname").val();
                let email = $("#edit-email").val();
                let password = $("#edit-password").val();
                let phone = $("#edit-phone").val();
                let salary = $("#edit-salary").val();
                let role = $("#edit-role").val();

                $.ajax({
                    url: "load-update-form.php",
                    type: "POST",
                    data: {
                        stud_id: student_id,
                        uname: username,
                        email: email,
                        password: password,
                        phone: phone,
                        salary: salary,
                        role: role,
                    },
                    success: function(data) {
                        if (data == 1) {
                            $("#modal").hide();
                            loadTable();
                        }
                    }
                });
            });

            // Hide close button
            $("#close-btn").on("click", function() {
                $("#modal").hide();
            });


            $("#search").on("keyup", function() {

                let search_bar = $(this).val();

                $.ajax({
                    url: "ajax-live-search.php",
                    type: "POST",
                    data: {
                        search: search_bar
                    },
                    success: function(data) {
                        $("#table-data").html(data);
                    }
                });
            });
        });
    </script>
</body>

</html>