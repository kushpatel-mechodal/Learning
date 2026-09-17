<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions</title>
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
                <h2>Edit Question</h2>
                <table border="1" cellspacing="0" cellpadding="10px">
                    <tr>
                        <td>Question Name</td>
                        <td><input type="text" id="edit-question"></td>
                    </tr>
                    <tr>
                        <td>Option 1</td>
                        <td><input type="text" id="edit-option1"></td>
                    </tr>
                    <tr>
                        <td>Option 2</td>
                        <td><input type="text" id="edit-option2"></td>
                    </tr>
                    <tr>
                        <td>Option 3</td>
                        <td><input type="text" id="edit-option3"></td>
                    </tr>
                    <tr>
                        <td>Option 4</td>
                        <td><input type="text" id="edit-option4"></td>
                    </tr>
                    <tr>
                        <td>Correct Answer</td>
                        <td><input type="text" id="edit-correct-answer"></td>
                    </tr>
                    <tr>
                        <td>Chapter id</td>
                        <td><input type="text" id="edit-chapter-id"></td>
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
    <script type="text/javascript" src="js/jquery.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let current_page = 1;

            function loadTable(page) {
                current_page = page;

                let limit = $("#limit").val();

                $.ajax({
                    url: "ajax-question.php",
                    type: "POST",
                    data: {
                        page_no: page,
                        limit: limit
                    },
                    success: function(data) {
                        $("#table-data").html(data);
                    }
                });
            }
            loadTable(1);

            $(document).on("click", ".btn-danger", function() {
                if (confirm("Are you Sure for delete")) {

                    let question_id = $(this).data("id");
                    let element = this;

                    $.ajax({
                        url: "question-delete.php",
                        type: "POST",
                        data: {
                            id: question_id
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
            $(document).on("click",".btn-primary",function (){
                $("#model").show();

                let question_id = $(this).data("eid");

                $.ajax({
                    url: "question-update-model.php",
                    type: "POST",
                    data: {
                        id: question_id
                    },
                    success: function (data){
                        $("#model-box table").html(data);
                        
                    }
                });
            });

            //update question

            $(document).on("click","#edit-submit",function (){

                let question_id = $("#edit-id").val();
                let question_name = $("#edit-question").val();
                let option1 = $("#edit-option1").val();
                let option2 = $("#edit-option2").val();
                let option3 = $("#edit-option3").val();
                let option4 = $("#edit-option4").val();
                let correct_answer = $("#edit-correct-answer").val();
                let chapter_id = $("#edit-chapter-id").val();

                $.ajax({
                    url: "question-update-form.php",
                    type: "POST",
                    data: {
                        id: question_id,
                        question_name: question_name,
                        option1: option1,
                        option2: option2,
                        option3: option3,
                        option4: option4,
                        correct_answer: correct_answer,
                        chapter_id: chapter_id
                    },
                    success: function (data){
                        if(data == 1){
                            $("#model").hide();
                            loadTable(current_page);
                        }
                    }
                });
            });
            $(document).on("change", "#limit", function() {
                loadTable(1);
            });

            $(document).on("click", "#pagination a", function() {
                let page_id = $(this).attr("id");
                loadTable(page_id);
            });

            $(document).on("click","#close-btn",function(){
                $("#model").hide();
            });
        });
    </script>
</body>

</html>