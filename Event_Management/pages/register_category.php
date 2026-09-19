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
            <a class="navbar-brand" href="#">EMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse gap-2" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="event.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="category.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registration.php">Registrations</a>
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

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">

                        <h3 class="text-center mb-4">Event Registration</h3>
                        <div class="toast-container position-fixed top-0 end-0 p-3">
                            <div class="toast fade border-0 shadow rounded-3 " id="categoryToast">
                                <div class="toast-body d-flex align-items-center gap-3 px-3 py-3">

                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <span id="toastMessage" class="fw-medium flex-grow-1"></span>
                                    <button class="border-0 bg-transparent p-0" type="button" name="btn-close" data-bs-dismiss="toast"
                                        aria-label="Close"><i class="bi bi-x-circle-fill fs-5"></i></button>

                                </div>
                            </div>
                        </div>

                        <form id="category-Form" class="form-control">

                            <div class="mt-3">
                                <label for="category_name" class="form-label">Category Name</label>
                                <input type="text" name="category_name" id="category_name" class="form-control">
                            </div>

                            <div class="mt-3">
                                <label for="category_status" class="form-label">Status</label>
                                <input type="text" name="category_status" id="category_status" class="form-control">
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary w-100">Add Category</button>
                            </div>
                        </form>
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


            $("#category-Form").on("submit", function(e) {

                e.preventDefault();

                let category_name = $("#category_name").val();
                let category_status = $("#category_status").val();

                $.ajax({
                    url: "../api/add-category.php",
                    type: "POST",
                    data: {
                        name: category_name,
                        status: category_status
                    },
                    success: function(response) {

                        $("#toastMessage").text(response.message);

                        let toastElement = document.getElementById("categoryToast");

                        let toast = new bootstrap.Toast(toastElement, {
                            delay: 1500
                        });

                        toast.show();

                        if (response.status) {

                            setTimeout(function (){
                                window.location.href="category.php";
                            },1500);
                        }
                    }
                });
            });

            // Logout
            $(document).on("click", "#logout-btn", function() {

                $.ajax({
                    url: "../api/logout-user.php",
                    type: "POST",
                    success: function(response) {

                        if (response.status) {
                            window.location.href = "login.php";
                        }
                    }
                });
            });
        });
    </script>