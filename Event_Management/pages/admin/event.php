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
                    <li class="nav-item">
                        <a class="nav-link" href="registrations.php">Registrations</a>
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
            <button type="button" class="btn btn-primary" id="addEventBtn">
                <i class="bi bi-plus-lg me-1"></i>
                Add Event
            </button>
            
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-primary table-bordered text-center align-middle">

                <thead class="table table-dark">
                    <tr>
                        <th>id</th>
                        <th>title</th>
                        <th>Category Name</th>
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

    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="eventModalTitle">
                        Add Event
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id">

                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Title</label>
                            <input type="text" id="title" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select id="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Capacity</label>
                            <input type="number" id="capacity" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea id="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="datetime-local" id="start_time" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="datetime-local" id="end_time" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Venue</label>
                            <input type="text" id="venus" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Registration Deadline
                            </label>
                            <input type="date" id="register_deadline" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Event Image</label>
                            <input type="file" id="image" class="form-control" accept="image/*">

                            <small class="text-muted">
                                Leave empty while editing if you don't want to change the image.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-primary" id="saveEvent">
                        <i class="bi bi-plus-lg me-1"></i>
                        Add Event
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // load table data
            function loadTableData() {

                $.ajax({
                    url: "../../api/get-event.php",
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
                                <td>${event_data.category_name}</td>
                                <td>${event_data.description}</td>
                                <td>${event_data.start_time}</td>
                                <td>${event_data.end_time}</td>
                                <td>${event_data.capacity}</td>
                                <td>${event_data.register_deadline}</td>

                                <td>
                                    <button
                                        class="btn btn-warning edit-btn"
                                        data-eid="${event_data.id}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button
                                        class="btn btn-danger delete-btn"
                                        data-id="${event_data.id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                        });

                        $("#eventTablebody").html(output);
                    }
                });
            }


            //load categories
            function loadCategories() {

                $.ajax({
                    url: "../../api/get-category.php",
                    type: "GET",

                    success: function(response) {

                        let output = `
                        <option value="">Select Category</option>`;

                        $.each(response.data, function(index, category) {

                            output += `
                            <option value="${category.id}">
                                ${category.category_name}
                            </option>
                        `;
                        });

                        $("#category_id").html(output);
                    }
                });
            }


            // Load data when page opens
            loadTableData();
            loadCategories();

            //Add event btn to open modal
            $(document).on("click", "#addEventBtn", function() {

                // Clear form
                $("#id").val("");
                $("#title").val("");
                $("#description").val("");
                $("#category_id").val("");
                $("#start_time").val("");
                $("#end_time").val("");
                $("#venus").val("");
                $("#capacity").val("");
                $("#register_deadline").val("");
                $("#image").val("");
                $("#eventModalTitle").text("Add Event");
                $("#saveEvent").html(`
                <i class="bi bi-plus-lg me-1"></i>
                Add Event
                `);
                $("#eventModal").modal("show");
            });

            // Same button can handles ADD + UPDATE

            $(document).on("click", "#saveEvent", function() {

                let event_id = $("#id").val();

                let title = $("#title").val();
                let description = $("#description").val();
                let category_id = $("#category_id").val();
                let venus = $("#venus").val();
                let start_time = $("#start_time").val();
                let end_time = $("#end_time").val();
                let capacity = $("#capacity").val();
                let register_deadline = $("#register_deadline").val();
                let image = $("#image")[0].files[0];

                let formData = new FormData();

                formData.append("event_title", title);
                formData.append("event_description", description);
                formData.append("event_category_id", category_id);
                formData.append("event_venus", venus);
                formData.append("event_start_time", start_time);
                formData.append("event_end_time", end_time);
                formData.append("event_capacity", capacity);
                formData.append("event_register_deadline", register_deadline);

                // Event update if event_id is received
                if (event_id) {

                    formData.append("event_id", event_id);

                    if (image) {
                        formData.append("image", image);
                    }

                    $.ajax({

                        url: "../../api/update-event.php",
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        processData: false,
                        contentType: false,

                        success: function(response) {

                            console.log(response);
                            if (response.status) {

                                $("#eventModal").modal("hide");
                                loadTableData();
                                loadCategories();
                                clearEventForm();
                            }
                            alert(response.message);
                        },
                    });
                }

                // Save data
                else {

                    if (image) {
                        formData.append("image", image);
                    }

                    $.ajax({

                        url: "../../api/add-event.php",
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);

                            if (response.status) {
                                $("#eventModal").modal("hide");
                                loadTableData();
                                clearEventForm();
                            }
                            alert(response.message);
                        },
                    });
                }
            });

            //Clear form
            function clearEventForm() {

                $("#id").val("");
                $("#title").val("");
                $("#description").val("");
                $("#category_id").val("");
                $("#start_time").val("");
                $("#end_time").val("");
                $("#venus").val("");
                $("#capacity").val("");
                $("#register_deadline").val("");
                $("#image").val("");
                $("#eventModalTitle").text("Add Event");
                $("#saveEvent").html(`
                <i class="bi bi-plus-lg me-1"></i>
                Add Event
            `);
            }

            //delete event
            $(document).on("click", ".delete-btn", function() {

                let delete_id = $(this).data("id");
                let delete_btn = this;

                $.ajax({

                    url: "../../api/delete-event.php",
                    type: "POST",

                    data: {
                        id: delete_id
                    },

                    success: function(response) {

                        console.log(response);

                        if (response.status) {

                            $(delete_btn).closest("tr").fadeOut(300, function() {
                                $(this).remove();
                                loadTableData();
                            });
                        }
                        alert(response.message);
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });


            // edit event modal open
            $(document).on("click", ".edit-btn", function() {

                let id = $(this).data("eid");

                $.ajax({

                    url: "../../api/get-event.php",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {

                        console.log(response);

                        if (response.status && response.data.length > 0) {
                            let event_data = response.data[0];

                            $("#id").val(event_data.id);
                            $("#title").val(event_data.title);
                            $("#category_id").val(event_data.category_id);
                            $("#description").val(event_data.description);
                            $("#start_time").val(event_data.start_time);
                            $("#end_time").val(event_data.end_time);
                            $("#venus").val(event_data.venus);
                            $("#capacity").val(event_data.capacity);
                            $("#register_deadline").val(
                                event_data.register_deadline
                            );

                            $("#image").val("");

                            $("#eventModalTitle").text("Edit Event");

                            $("#saveEvent").html(`
                            <i class="bi bi-pencil-square me-1"></i>
                            Update Event
                        `);

                            // Open same modal
                            $("#eventModal").modal("show");
                        }
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });

            });

            $(document).on("click", "#logout-btn", function() {
                if (confirm("Are You sure you want to logout?")) {

                    $.ajax({

                        url: "../../api/logout-user.php",
                        type: "POST",
                        success: function() {
                            window.location.href = "../login.php";
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                        }
                    });
                }

            });

        });
    </script>
</body>

</html>