<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        integrity="sha384-Bk5cbLkZQ5raZ0+H2/+VbfYx3WpvxvQK4zqXZr7sYODuaX7bKXoSOnipQxkaS8sv" crossorigin="anonymous">
</head>

<body>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Todo App</h1>
            <p class="text-muted">Manage Your Daily Task</p>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">Add New Todo</h4>

                <form id="todoForm" method="POST">

                    <input type="hidden" id="todo_id">

                    <div class="mb-3">

                        <label for="title" class="form-label fw-medium">Todo Title</label>
                        <input type="text" class="form-control" name="title" id="title"
                            placeholder="Enter todo title" required>

                    </div>

                    <div class="mb-3">

                        <label for="description" class="form-label fw-medium">Description</label>
                        <textarea type="text" class="form-control" name="description" id="description"
                            placeholder="Enter todo description" required></textarea>

                    </div>

                    <button type="submit" class="btn btn-primary" id="submitTodo">Add Todo</button>
                </form>

                <div id="todolist" class="mt-4">

                    <h4 class="mb-3 text-center fw-bold">Todo List</h4>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">

                                <input type="text" class="form-control" name="searchTodo" id="searchTodo"
                                    placeholder="Search Todo">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="toast-container position-fixed top-0 end-0 p-3">

                        <div class="toast fade border-0 shadow rounded-3 " id="statusToast">

                            <div class="toast-body d-flex align-items-center gap-3 px-3 py-3">

                                <!-- <i class="bi bi-check-circle-fill text-success fs-5"></i> -->
                                <span id="toastMessage" class="fw-medium flex-grow-1"></span>
                                <button class="border-0 bg-transparent p-0" type="button" name="btn-close" data-bs-dismiss="toast"
                                    aria-label="Close"><i class="bi bi-x-circle-fill fs-5"></i></button>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>description</th>
                                    <th>status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="todo-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="response"></div>
            </div>
        </div>
    </div>

    <!-- Edit Todo Modal -->
    <!-- <div class="modal fade" id="editTodoModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Todo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="edit_title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit_description" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="updateTodo">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#todoForm").on("submit", function(e) {
                e.preventDefault();

                let id = $("#todo_id").val(); //use for edit todo to get todo_id value
                let title = $("#title").val();
                let description = $("#description").val();

                $.ajax({
                    url: "api/todo_api.php",
                    type: "POST",
                    data: {
                        id: id, //use for edit todo 
                        title: title,
                        description: description
                    },
                    success: function(response) {

                        if (response.status) {
                            loadTodoData();

                            $("#todo_id").val();
                            $("#title").val("");
                            $("#description").val("");
                            $("#submitTodo").text("Add Todo");
                        }
                    }
                });
            });

            function loadTodoData() {

                $.ajax({
                    url: "api/read-todo.php",
                    type: "GET",
                    success: function(response) {
                        console.log(response);

                        let output = "";
                        $.each(response.data, function(index, todos) {
                            output += `
                                <tr>
                                    <td>${todos.id}</td>
                                    <td>${todos.title}</td>
                                    <td>${todos.description}</td>
                                    <td>
                                        <select class="form-select status-select" data-id = "${todos.id}">
                                              <option value="Pending" ${todos.status === "Pending" ? "selected" : ""}>Pending</option>
                                            <option value="Completed" ${todos.status === "Completed" ? "selected" : ""}>Completed</option>
                                         </select>
                                    </td>
                                    <td>
                                        <button class='btn btn-warning btn-sm edit-btn' data-eid = ${todos.id}><i class='bi bi-pencil-square'></i></button>
                                        <button class='btn btn-danger btn-sm delete-btn' data-id = ${todos.id}><i class='bi bi-trash'></i></button>
                                    </td>
                            </tr>`;
                        });

                        $("#todo-table-body").html(output);
                    }
                });
            }

            loadTodoData();

            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are you sure to delete todo")) {
                    let delete_id = $(this).data("id");
                    let element = this;

                    $.ajax({
                        url: "api/delete-todo.php",
                        type: "POST",
                        data: {
                            id: delete_id
                        },
                        success: function(data) {

                            if (data.status) {

                                $(element).closest("tr").fadeOut(500, function() {
                                    $(this).remove();
                                    loadTodoData();
                                });
                            }
                        }
                    });
                }
            });

            //update data model
            $(document).on("click", ".edit-btn", function() {

                let edit_id = $(this).data("eid");

                $.ajax({
                    url: "api/todo_api.php",
                    type: "GET",
                    data: {
                        id: edit_id
                    },
                    success: function(response) {

                        if (response.status) {
                            $("#todo_id").val(response.data.id);
                            $("#title").val(response.data.title);
                            $("#description").val(response.data.description);
                            $("#submitTodo").text("Update Todo");
                        }
                    }
                });
            });

            let searchTimer; //store the timer
            //search todo
            $("#searchTodo").on("keyup", function() {

                let search = $(this).val();

                clearTimeout(searchTimer); //previous timer cancel to use

                //execute setimeout() and wait 500ms second for each word
                searchTimer = setTimeout(() => {

                    $.ajax({
                        url: "api/search-todo.php",
                        type: "GET",
                        data: {
                            search: search
                        },
                        success: function(response) {

                            console.log(response);

                            let output = "";

                            if (response.status) {

                                if (response.data.length > 0) {
                                    /*use each function to fetch search row one by one and use += for fetch all search
                                    row */
                                    $.each(response.data, function(index, todo) {
                                        output += `
                                    <tr>
                                        <td>${todo.id}</td>
                                        <td>${todo.title}</td>
                                        <td>${todo.description}</td>
                                        <td>
                                        <select class="form-select status-select" data-id = "${todo.id}">
                                            <option value="Pending" ${todo.status === "Pending" ? "selected" : ""}>Pending</option>
                                            <option value="Completed" ${todo.status === "Completed" ? "selected" : ""}>Completed</option>
                                         </select>
                                        </td>
                                    <td>
                                        <button class='btn btn-warning btn-sm edit-btn' data-eid = ${todo.id}><i class='bi bi-pencil-square'></i></button>
                                        <button class='btn btn-danger btn-sm delete-btn' data-id = ${todo.id}><i class='bi bi-trash'></i></button>
                                    </td>
                                </tr>`;
                                        $("#todo-table-body").html(output);
                                    });
                                } else {
                                    $("#todo-table-body").html(`
                                    <tr>
                                        <td colspan = 5>No Todos Found</td>
                                    </tr>`);
                                }
                            }
                        }
                    });
                }, 500); 
            });

            $(document).on("change", ".status-select", function() {

                let id = $(this).data("id");
                let status = $(this).val();

                $.ajax({
                    url: "api/update-status.php",
                    type: "POST",
                    data: {
                        id: id,
                        status: status
                    },
                    success: function(response) {
                        if (response.status) {
                            loadTodoData();

                            $("#toastMessage").text(response.message); // show the  toast message

                            let toastElement = document.getElementById("statusToast"); //get the statustoast id html

                            //use bootstrap.toast javascript class for handle toast 
                            let toast = new bootstrap.Toast(toastElement, {
                                delay: 2000,
                            });
                            toast.show(); //show toast message
                        }
                    }
                });
            });

            /*$("#updateTodo").on("click", function() {

                let id = $("#edit_id").val();
                let title = $("#edit_title").val();
                let description = $("#edit_description").val();

                $.ajax({
                    url: "api/update-todo.php",
                    type: "POST",
                    data: {
                        id: id,
                        title: title,
                        description: description
                    },
                    success: function(data) {
                        if (data.status) {
                            loadTodoData();
                            $("#editTodoModal").modal("hide");
                        }
                    }
                });
            });*/
        });
    </script>
</body>

</html>