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
                        <a class="nav-link" href="event.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="category.php">Categories</a>
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
            <button type="button" class="btn btn-primary" id="addCategoryBtn">
                <i class="bi bi-plus-lg me-1"></i> Add Category
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-primary table-bordered text-center align-middle">
                <thead class="table table-dark">
                    <tr>
                        <th>Seiral No</th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="event_category_body"></tbody>
            </table>
        </div>
    </div>

    <!-- Category Modal (Add / Edit) -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="categoryModalTitle">Add Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="category_id">

                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" id="category_name" class="form-control" placeholder="Enter category name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <input type="text" id="category_status" class="form-control" placeholder="e.g. Active / Inactive" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCategoryBtn">
                        <i class="bi bi-plus-lg me-1"></i> Add Category
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // Load category table data
            function loadCategoryData() {
                $.ajax({
                    url: "../../api/get-category.php",
                    type: "GET",
                    success: function(response) {
                        let output = "";

                        if (response.status && response.data && response.data.length > 0) {
                            $.each(response.data, function(index, category_data) {
                                let serial_no = index + 1;
                                output += `
                                <tr>
                                    <td>${serial_no}</td>
                                    <td>${category_data.category_name}</td>
                                    <td>${category_data.status}</td>
                                    <td>
                                        <button class="btn btn-warning edit-btn" data-eid="${category_data.id}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger delete-btn" data-id="${category_data.id}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>`;
                            });
                        }

                        $("#event_category_body").html(output);
                    }
                });
            }

            // Load data when page opens
            loadCategoryData();

            // Clear category modal form
            function clearCategoryForm() {
                $("#category_id").val("");
                $("#category_name").val("");
                $("#category_status").val("");
                $("#categoryModalTitle").text("Add Category");
                $("#saveCategoryBtn").html(`<i class="bi bi-plus-lg me-1"></i> Add Category`);
            }

            // Open Add Category Modal
            $(document).on("click", "#addCategoryBtn", function() {
                clearCategoryForm();
                $("#categoryModal").modal("show");
            });

            // Save Category (Add or Update)
            $(document).on("click", "#saveCategoryBtn", function() {
                let id = $("#category_id").val();
                let category_name = $("#category_name").val();
                let category_status = $("#category_status").val();

                if (category_name === "" || category_status === "") {
                    alert("Please fill in all fields.");
                    return;
                }

                // Update category if id exists
                if (id) {
                    $.ajax({
                        url: "../../api/update-category.php",
                        type: "POST",
                        data: {
                            id: id,
                            category_name: category_name,
                            category_status: category_status
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                $("#categoryModal").modal("hide");
                                loadCategoryData();
                                clearCategoryForm();
                            }
                            alert(response.message);
                        }
                    });
                }
                // Add new category
                else {
                    $.ajax({
                        url: "../../api/add-category.php",
                        type: "POST",
                        data: {
                            category_name: category_name,
                            category_status: category_status
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status) {
                                $("#categoryModal").modal("hide");
                                loadCategoryData();
                                clearCategoryForm();
                            }
                            alert(response.message);
                        }
                    });
                }
            });

            // Open Edit Category Modal
            $(document).on("click", ".edit-btn", function() {
                let id = $(this).data("eid");

                $.ajax({
                    url: "../../api/get-category.php",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        if (response.status && response.data.length > 0) {
                            let category = response.data[0];

                            $("#category_id").val(category.id);
                            $("#category_name").val(category.category_name);
                            $("#category_status").val(category.status);
                            $("#categoryModalTitle").text("Edit Category");
                            $("#saveCategoryBtn").html(`<i class="bi bi-pencil-square me-1"></i> Update Category`);
                            $("#categoryModal").modal("show");
                        }
                    }
                });
            });

            // Delete Category
            $(document).on("click", ".delete-btn", function() {
                if (!confirm("Are you sure you want to delete this category?")) {
                    return;
                }

                let delete_id = $(this).data("id");
                let delete_btn = this;

                $.ajax({
                    url: "../../api/delete-category.php",
                    type: "POST",
                    data: {
                        id: delete_id
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status) {
                            $(delete_btn).closest("tr").fadeOut(300, function() {
                                $(this).remove();
                                loadCategoryData();
                            });
                        }
                        alert(response.message);
                    }
                });
            });

            // Logout
            $(document).on("click", "#logout-btn", function() {
                if (confirm("Are you sure you want to logout?")) {
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