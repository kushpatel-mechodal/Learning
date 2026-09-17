<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Children</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">
</head>

<body>
    <div id="main">

        <select id="limit">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>

        <div id="table-data">
        </div>

        <div id="model">
            <div id="model-box">
                <h2>Edit Children</h2>
                <table border="1" cellspacing="0" cellpadding="10px">

                    <tr>
                        <td>Child Name</td>
                        <td><input type="text" id="edit-name"></td>
                    </tr>
                    <tr>
                        <td>State Name</td>
                        <td><input type="text" id="edit-state"></td>
                    </tr>
                    <tr>
                        <td>District Name</td>
                        <td><input type="text" id="edit-district"></td>
                    </tr>
                    <tr>
                        <td>Taluka Name</td>
                        <td><input type="text" id="edit-taluka"></td>
                    </tr>
                    <tr>
                        <td>School Name</td>
                        <td><input type="text" id="edit-school-name"></td>
                    </tr>
                    <tr>
                        <td>Standard Name</td>
                        <td><input type="text" id="edit-standard"></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><input type="text" id="edit-gender"></td>
                    </tr>
                    <tr>
                        <td>Reward</td>
                        <td><input type="text" id="edit-reward"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" id="edit-submit" value="Update"></td>
                    </tr>
                </table>
                <div>
                    <button type="button" id="close-btn">X</button>
                </div>
            </div>
        </div>
    </div>

    <script text="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            let current_page = 1;

            function loadTable(page) {

                current_page = page;

                let limit = $("#limit").val();

                $.ajax({
                    url: "ajax-children.php",
                    type: "POST",
                    data: {
                        page_no: page,
                        limit: limit
                    },
                    success: function(data) {
                        $("#table-data").html(data);
                    },
                });
            }
            loadTable(1);

            $(document).on("click", "#pagination a", function(e) {
                e.preventDefault();

                let page_id = $(this).attr("id");
                loadTable(page_id);
            });

            $(document).on("change", "#limit", function() {
                loadTable(1);
            });

            //delete children
            $(document).on("click", ".btn-danger", function() {

                if (confirm("Are you sre to delete")) {

                    let children_id = $(this).data("id");
                    let element = this;

                    $.ajax({
                        url: "children-delete.php",
                        type: "POST",
                        data: {
                            id: children_id
                        },
                        success: function(data) {
                            if (data == 1) {
                                $(element).closest("tr").fadeOut(200, function() {
                                    $(this).remove();
                                    loadTable(current_page);
                                });
                            }
                        }
                    });
                }
            });

            //show model box
            $(document).on("click", ".btn-primary", function() {
                $("#model").show();

                let children_id = $(this).data("eid");

                $.ajax({
                    url: "children-update-model.php",
                    type: "POST",
                    data: {
                        id: children_id
                    },
                    success: function(data) {
                        $("#model-box table").html(data);
                    }
                });
            });

            $(document).on("click", "#edit-submit", function() {

                let children_id = $("#edit-id").val();
                let child_name = $("#edit-name").val();
                let state_id = $("#edit-state").val();
                let district_id = $("#edit-district").val();
                let taluka_id = $("#edit-taluka").val();
                let school_name = $("#edit-school-name").val();
                let standard = $("#edit-standard").val();
                let gender = $("#edit-gender").val();
                let reward = $("#edit-reward").val();

                $.ajax({
                    url: "children-update.php",
                    type: "POST",
                    data: {
                        id: children_id,
                        child_name: child_name,
                        state_id: state_id,
                        district_id: district_id,
                        taluka_id: taluka_id,
                        school_name: school_name,
                        standard: standard,
                        gender: gender,
                        reward: reward
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