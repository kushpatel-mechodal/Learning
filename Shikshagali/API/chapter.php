<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>chapter</title>

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
                <h2>Edit Chapter Form</h2>
                <table border="1" cellspacing="0" cellpadding="10px">
                    <tr>
                        <td>Chapter no</td>
                        <td>
                            <input type="text" id="edit-chapter">
                        </td>
                    </tr>

                    <tr>
                        <td>Chapter Name</td>
                        <td>
                            <input type="text" id="edit-name">
                        </td>
                    </tr>

                    <tr>
                        <td>Subject</td>
                        <td>
                            <input type="text" id="edit-subject">
                        </td>
                    </tr>

                    <tr>
                        <td>Standard</td>
                        <td>
                            <input type="text" id="edit-standard">
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

            // Load Table
            function loadTable(page) {
                current_page = page;
                $.ajax({

                    url: "ajax-chapter.php",
                    type: "POST",
                    data: {
                        page_no: page
                    },
                    success: function(data) {

                        $("#table-data").html(data);
                    }
                });
            }
            loadTable(1);

            // Pagination
            $(document).on("click", "#pagination a", function(e) {

                e.preventDefault();
                let page_id = $(this).attr("id");
                loadTable(page_id);
            });


            // Delete
            $(document).on("click", ".btn-danger", function(e) {

                if (confirm("Are you Sure for Delete")) {

                    let delete_id = $(this).data("id");
                    let element = this;
                    $.ajax({

                        url: "delete-chapter.php",
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


            // Show Edit Modal
            $(document).on("click", ".btn-primary", function() {

                $("#model").show();
                let chapter_id = $(this).data("eid");
                $.ajax({
                    url: "chapter-update-model.php",
                    type: "POST",
                    data: {
                        id: chapter_id
                    },
                    success: function(data) {
                        $("#model-box table").html(data);
                    }
                });
            });

            $(document).on("click", "#edit-submit", function() {

                let chapter_id = $("#edit-id").val();
                let chapter_no = $("#edit-chapter").val();
                let chapter_name = $("#edit-name").val();
                let subject = $("#edit-subject").val();
                let standard = $("#edit-standard").val();

                $.ajax({
                    url: "chapter-update-form.php",
                    type: "POST",
                    data: {
                        chapter_id: chapter_id,
                        chapter_no: chapter_no,
                        chapter_name: chapter_name,
                        subject: subject,
                        standard: standard
                    },
                    success: function (data){
                        if(data == 1){
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