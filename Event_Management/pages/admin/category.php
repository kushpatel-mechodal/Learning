<?php

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        integrity="sha384-Bk5cbLkZQ5raZ0+H2/+VbfYx3WpvxvQK4zqXZr7sYODuaX7bKXoSOnipQxkaS8sv" crossorigin="anonymous">
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">EMS Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse gap-2" id="navbarNavDropdown">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="event.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category.php">Categories</a>
                    </li>
                </ul>
                <div class="mt-auto text-end">
                    <button id="logout-btn" class="btn btn-danger w-100 float-end">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Categories</h2>
            <a href="../register_category.php" class="btn btn-primary">+ Add Category</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-primary table-bordered text-center align-middle">
                <thead class="table table-dark">
                    <tr>
                        <th>Id</th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="event_category_body"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade mt-5" id="editEventModal">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg border-0 rounded-3 mt-5">

                <div class="modal-header  bg-primary text-white position-relative">
                    <h5 class="modal-title w-100 text-center">Edit Registrations</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="edit_id">

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" id="edit_name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <textarea id="edit_status" class="form-control"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary w-100" id="updateCategory">
                            Update Category
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
    </script>

    <script type="text/javascript">
            $(document).ready(function() {


                //get category data

                function loadCategoryDate() {

                    $.ajax({
                        url: "../../api/get-category.php",
                        type: "GET",
                        success: function(response) {

                            console.log(response);
                            let output = "";

                            $.each(response.data, function(index, category_data) {

                                let serial_no = index + 1;

                                output += `
                                 <tr>
                                     <td>${serial_no}</td>
                                    <td>${category_data.category_name}</td>
                                     <td>${category_data.status}</td>
                                     <td>
                                         <button class="btn btn-warning edit-btn" data-eid = ${category_data.id}><i class='bi bi-pencil-square'></i></button>
                                        <button class="btn btn-danger delete-btn" data-id = ${category_data.id}><i class='bi bi-trash'></i></button>
                                    </td>
                                 </tr>`;
                            });

                            $("#event_category_body").html(output);
                        }
                    });
                }

                loadCategoryDate();

                //delete category

                $(document).on("click", ".delete-btn", function() {

                    if (confirm("Are you sure to delete category")) {

                        let delete_id = $(this).data("id");
                        let delete_btn = this;

                        $.ajax({
                            url: "../../api/delete-category.php",
                            type: "POST",
                            data: {
                                id: delete_id
                            },
                            success: function(response) {

                                if (response.status) {
                                    $(delete_btn).closest("tr").fadeOut(300, function() {
                                        $(this).remove();
                                        loadCategoryDate();
                                    });
                                }
                            }
                        });
                    }
                });

                $(document).on("click", ".edit-btn", function() {

                    let edit_id = $(this).data("eid");

                    $.ajax({
                        url: "../../api/get-category.php",
                        type: "GET",
                        data: {
                            id: edit_id
                        },
                        success: function(response) {

                            $("#edit_id").val(response.data[0].id);
                            $("#edit_name").val(response.data[0].category_name);
                            $("#edit_status").val(response.data[0].status);

                            $("#editEventModal").modal("show");
                        }
                    });
                });

                //update data
                $(document).on("click", "#updateCategory", function() {

                    let id = $("#edit_id").val();
                    let category_name = $("#edit_name").val();
                    let category_status = $("#edit_status").val();

                    $.ajax({
                        url: "../../api/update-category.php",
                        type: "POST",
                        data: {
                            id: id,
                            category_name: category_name,
                            category_status: category_status
                        },
                        success: function(response) {
                            if (response.status) {
                                $("#editEventModal").modal("hide");
                                loadCategoryDate();
                            }
                        }
                    });
                });

                // Logout
                $(document).on("click", "#logout-btn", function() {

                    if (confirm("Are Your sure for logout")) {
                        $.ajax({
                            url: "../../api/logout-user.php",
                            type: "POST",
                            success: function() {
                                window.location.href = "../login.php";
                            }
                        });
                    }
                });
            });
        </script>

</body>

</html>