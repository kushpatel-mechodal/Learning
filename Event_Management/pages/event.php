<?php

session_start();

if($_SESSION["role"] !== "admin"){
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Dashboard</title>
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

            <h2>Events</h2>
            <a href="registration.php" class="btn btn-primary">Add Event</a>

        </div>

        <div class="table-responsive">
            <table class="table table-hover table-primary table-bordered text-center align-middle">

                <thead class="table table-dark">
                    <tr>
                        <th>id</th>
                        <th>title</th>
                        <th>Description</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Capacity</th>
                        <th>Register Deadline</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="eventTablebody"></tbody>
            </table>
        </div>
    </div>

    <div class="modal fade mt-5" id="editEventModal">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg border-0 rounded-3 mt-5 ">

                <div class="modal-header bg-primary text-white position-relative">
                    <h5 class="modal-title w-100 text-center">Edit Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">

                    <input type="hidden" id="edit_id">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="edit_title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit_description" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" id="edit_start_time" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" id="edit_end_time" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Venus</label>
                        <input type="text" id="edit_venus" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Capacity</label>
                        <input type="number" id="edit_capacity" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Registration Deadline</label>
                        <input type="date" id="edit_register_deadline" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" id="updateEvent">
                        Update
                    </button>
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

            //get event data
            function loadTableData() {

                $.ajax({
                    url: "../api/get-event.php",
                    type: "GET",
                    success: function(response) {
                        console.log(response);

                        let output = "";

                        $.each(response.data, function(index, event_data) {

                            let serial_no = index + 1;
                            output += `
                            <tr>
                                <td>${serial_no}</td>
                                <td>${event_data.title}</td>
                                <td>${event_data.description}</td>
                                <td>${event_data.start_time}</td>
                                <td>${event_data.end_time}</td>
                                <td>${event_data.capacity}</td>
                                <td>${event_data.register_deadline}</td>
                                <td>
                                    <button class="btn btn-warning edit-btn" data-eid = ${event_data.id}><i class='bi bi-pencil-square'></i></button>
                                    <button class="btn btn-danger delete-btn" data-id = ${event_data.id}><i class='bi bi-trash'></i></button>
                                </td>
                            </tr>`;
                        });

                        $("#eventTablebody").html(output);
                    }
                });
            }

            loadTableData();


            //delete event
            $(document).on("click", ".delete-btn", function() {

                let delete_id = $(this).data("id");
                let delete_btn = this;

                $.ajax({
                    url: "../api/delete-event.php",
                    type: "POST",
                    data: {
                        id: delete_id
                    },
                    success: function(response) {

                        if (response.status) {

                            $(delete_btn).closest("tr").fadeOut(300, function() {
                                $(this).remove();
                                loadTableData();
                            });
                        }
                    }
                });
            });

            //edit data

            $(document).on("click", ".edit-btn", function() {

                let edit_id = $(this).data("eid");

                $.ajax({

                    url: "../api/get-event.php",
                    type: "GET",
                    data: {
                        id: edit_id
                    },
                    success: function(response) {

                        $("#edit_id").val(response.data[0].id);
                        $("#edit_title").val(response.data[0].title);
                        $("#edit_description").val(response.data[0].description);
                        $("#edit_start_time").val(response.data[0].start_time);
                        $("#edit_end_time").val(response.data[0].end_time);
                        $("#edit_venus").val(response.data[0].venus);
                        $("#edit_capacity").val(response.data[0].capacity);
                        $("#edit_register_deadline").val(response.data[0].register_deadline);

                        $("#editEventModal").modal("show");
                    }
                });


                $("#updateEvent").on("click", function() {

                    let update_id = $("#edit_id").val();

                    let title = $("#edit_title").val();
                    let description = $("#edit_description").val();
                    let start_time = $("#edit_start_time").val();
                    let end_time = $("#edit_end_time").val();
                    let venus = $("#edit_venus").val();
                    let capacity = $("#edit_capacity").val();
                    let register_deadline = $("#edit_register_deadline").val();

                    $.ajax({
                        url: "../api/update-event.php",
                        type: "POST",
                        data: {
                            id: update_id,
                            update_title: title,
                            update_description: description,
                            update_start_time: start_time,
                            update_end_time: end_time,
                            update_venus: venus,
                            update_capacity: capacity,
                            update_register_deadline: register_deadline
                        },
                        success: function(response) {
                            if (response.status) {
                                $("#editTodoModal").modal("hide");
                                loadTableData();
                            }
                        }
                    });
                });
            });
            $(document).on("click", "#logout-btn", function() {

                if (confirm("Are Your sure for logout")) {
                    $.ajax({
                        url: "../api/logout-user.php",
                        type: "POST",
                        success: function() {
                            window.location.href = "login.php";
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>