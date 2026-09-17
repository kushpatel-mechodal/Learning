<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">
</head>

<body>

    <div id="main">
        <div id="table-data">
        </div>
        <div id="model">
            <div id="model-box">
                <h2>Edit Parent Form</h2>
                <table border="1" cellspacing="0" cellpadding="10px">
                    <tr>
                        <td>Parent Name</td>
                        <td>
                            <input type="text" id="edit-name">
                        </td>
                    </tr>

                    <tr>
                        <td>Mobile No</td>
                        <td>
                            <input type="text" id="edit-mobile-no">
                        </td>
                    </tr>

                    <tr>
                        <td>Email</td>
                        <td>
                            <input type="text" id="edit-email">
                        </td>
                    </tr>

                    <tr>
                        <td>Password</td>
                        <td>
                            <input type="text" id="edit-password">
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" id="edit-submit" value="Save">
                        </td>
                    </tr>
                </table>
                <div>
                    <button type="button" id="close-btn">X</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            let current_page = 1;

            function loadTable(page) {

                current_page = page;

                $.ajax({
                    url: "ajax-parents.php",
                    type: "POST",
                    data: {
                        page_no: page
                    },
                    success: function(data) {
                        $("#table-data").html(data);
                    }
                });
            }
            loadTable();

            $(document).on("click", "#pagination a", function(e) {
                e.preventDefault();

                let page_id = $(this).attr("id");
                loadTable(page_id);
            });

            $(document).on("click", ".btn-danger", function() {

                if (confirm("Are you sure to delete")) {

                    let delete_id = $(this).data("id");
                    let element = this;
                    $.ajax({
                        url: "delete-parent.php",
                        type: "POST",
                        data: {
                            id: delete_id
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(element).closest("tr").fadeOut(150, function() {
                                    $(this).remove();
                                    loadTable(current_page);
                                });
                            }
                        }
                    });
                }
            });

            $(document).on("click", ".btn-primary", function() {

                $("#model").show();

                let parent_id = $(this).data("eid");

                $.ajax({
                    url: "parent-update-model.php",
                    type: "POST",
                    data: {
                        id: parent_id
                    },
                    success: function(data) {

                        $("#model-box table").html(data);
                    }
                });
            });

            $(document).on("click", "#edit-submit", function() {

                let parent_id = $("#edit-id").val();
                let parent_name = $("#edit-name").val();
                let mobile_no = $("#edit-mobile-no").val();
                let email = $("#edit-email").val();
                let password = $("#edit-password").val();

                $.ajax({
                    url: "parent-update-form.php",
                    type: "POST",
                    data: {
                        parent_id: parent_id,
                        parent_name: parent_name,
                        mobile_no: mobile_no,
                        email: email,
                        password: password
                    },
                    success: function(data) {
                        if (data == 1) {
                            $("#model").hide();
                            loadTable(current_page);
                        }
                    }
                });
            });

            $(document).on("click", "#close-btn", function() {
                $("#model").hide();
                
            });
        });
    </script>
</body>

</html>