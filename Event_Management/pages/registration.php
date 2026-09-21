<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration</title>
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
                        <a class="nav-link" href="#">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="regis" href="registration.php">Registrations</a>
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

                            <div class="toast fade border-0 shadow rounded-3 " id="eventToast">

                                <div class="toast-body d-flex align-items-center gap-3 px-3 py-3">

                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <span id="toastMessage" class="fw-medium flex-grow-1"></span>
                                    <button class="border-0 bg-transparent p-0" type="button" name="btn-close" data-bs-dismiss="toast"
                                        aria-label="Close"><i class="bi bi-x-circle-fill fs-5"></i></button>
                                </div>
                            </div>
                        </div>

                        <form id="eventForm">

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control mb-3" name="title" id="title" placeholder="Event title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea type="text" class="form-control mb-3" name="description" id="description" placeholder="Event description" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <select id="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="start-time" class="form-label">Start Time</label>
                                <input type="datetime-local" class="form-control mb-3" name="start-time" id="start-time" placeholder="Event start time" required>
                            </div>

                            <div class="mb-3">
                                <label for="end-time" class="form-label">End Time</label>
                                <input type="datetime-local" class="form-control mb-3" name="end-time" id="end-time" placeholder="Event end time" required>
                            </div>

                            <div class="mb-3">
                                <label for="venus" class="form-label">Venus</label>
                                <input type="text" class="form-control mb-3" name="venus" id="venus" placeholder="Event venus/location" required>
                            </div>

                            <div class="mb-3">
                                <label for="capacity" class="form-label">Capacity</label>
                                <input type="number" class="form-control mb-3" name="capacity" id="capacity" placeholder="Event capacity" required>
                            </div>

                            <div class="mb-3">
                                <label for="register-deadline" class="form-label">register Deadline</label>
                                <input type="date" class="form-control mb-3" name="register-deadline" id="register-deadline" placeholder="Event register deadline" required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100" name="submit-btn" id="submit-btn">Register Event</button>
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

            $("#eventForm").on("submit", function(e) {

                e.preventDefault();

                let title = $("#title").val();
                let description = $("#description").val();
                let category_id = $("#category_id").val();
                let venus = $("#venus").val();
                let start_time = $("#start-time").val();
                let end_time = $("#end-time").val();
                let capacity = $("#capacity").val();
                let register_deadline = $("#register-deadline").val();

                $.ajax({
                    url: "../api/add-event.php",
                    type: "POST",
                    data: {
                        event_title: title,
                        event_category_id: category_id,
                        event_description: description,
                        event_venus: venus,
                        event_start_time: start_time,
                        event_end_time: end_time,
                        event_capacity: capacity,
                        event_register_deadline: register_deadline
                    },
                    success: function(response) {

                        $("#toastMessage").text(response.message);

                        let toastElement = document.getElementById("eventToast");

                        let toast = new bootstrap.Toast(toastElement, {
                            delay: 2000
                        });

                        toast.show();

                        if (response.status) {
                            setTimeout(function() {
                                window.location.href = "event.php"
                            }, 2000);
                        }
                    }
                });
            });

            //load categories
            function loadCategories() {

                $.ajax({
                    url: "../api/get-category.php",
                    type: "GET",
                    success: function(response) {

                        let output = `<option value="">Select Category</option>`;

                        $.each(response.data, function(index, category) {

                            output += `<option value="${category.id}">${category.category_name}</option>`;
                        });

                        $("#category_id").html(output);
                    }
                });
            }
            loadCategories();
        });
    </script>
</body>

</html>