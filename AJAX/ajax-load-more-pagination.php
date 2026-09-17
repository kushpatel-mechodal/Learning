<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination</title>
    <link rel="stylesheet" href="style.css">
    <style>
        #ajaxbtn:disabled {
            background-color: #ccc;
            color: blue;
            cursor: not-allowed;
            opacity: 10;
        }
    </style>
</head>

<body>
    <div id="main">
        <div id="header">
            <h1>AjAX AND Load More pagination</h1>
        </div>

        <div id="table-data">

            <table id="loadData" border="1" cellspacing="0" cellpadding="10px" width="100%">
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>salary</th>
                    <th>role</th>
                </tr>
            </table>
        </div>
    </div>

    <script type="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            function loadTable(page) {

                $.ajax({
                    url: "load-more-pagination.php",
                    type: "POST",
                    data: {
                        page_no: page
                    },
                    success: function(data) {

                        if (data) {
                            $("#pagination").remove();
                            $("#loadData").append(data);
                        } else {
                            $("#ajaxbtn").html("Finished");
                            $("#ajaxbtn").prop("disabled", true);
                        }
                    }
                });
            }
            loadTable();

            $(document).on("click", "#ajaxbtn", function() {
                $("#ajaxbtn").html("Loading...");
                let pid = $(this).data("id");
                loadTable(pid);
            });
        });
    </script>
</body>

</html>