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
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th class="text-nowrap">Start Time</th>
                        <th class="text-nowrap">End Time</th>
                        <th>Capacity</th>
                        <th class="text-nowrap" style="min-width: 130px;">Event Date</th>
                        <th class="text-nowrap" style="min-width: 140px;">Action</th>
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
                                Event Date
                            </label>
                            <input type="date" id="event_date" class="form-control" required>
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

    </div> <!-- eventModal END -->


    <!-- View Registration Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">
                        Event Registrations
                    </h5>

                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <h5 id="registrationEventTitle"></h5>

                    <div class="mb-3">
                        <span class="badge bg-primary">
                            Registered:
                            <span id="totalRegistrations">0</span>
                        </span>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover text-center align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody id="registrationTableBody">
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
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
                            let eventDateRaw = event_data.event_date || event_data.register_deadline || '';
                            let eventDate = eventDateRaw ? eventDateRaw.substring(0, 10) : '-';

                            output += `
                            <tr>
                                <td>${serial_no}</td>
                                <td class="fw-semibold">${event_data.title}</td>
                                <td>${event_data.category_name || ''}</td>
                                <td>${event_data.description}</td>
                                <td class="text-nowrap">${event_data.start_time}</td>
                                <td class="text-nowrap">${event_data.end_time}</td>
                                <td>${event_data.capacity}</td>
                                <td class="text-nowrap">
                                    <span class="badge bg-transparent text-dark border border-dark-subtle px-2 py-1">
                                        <i class="bi bi-calendar-event text-primary me-1"></i>${eventDate}
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="d-inline-flex justify-content-center align-items-center gap-1">
                                        <button class="btn btn-dark view-btn" data-id="${event_data.id}" data-title="${event_data.title}">
                                            <i class="bi bi-eye"></i>
                                        </button>
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
                                    </div>
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
                $("#event_date").val("");
                $("#image").val("");
                $("#eventModalTitle").text("Add Event");
                $("#saveEvent").html(`
                <i class="bi bi-plus-lg me-1"></i>
                Add Event
                `);
                $("#eventModal").modal("show");
            });

            $(document).on("click", ".view-btn", function() {

                let event_id = $(this).data("id");
                let event_title = $(this).data("title") || "";

                $("#registrationEventTitle").text(event_title ? "Event: " + event_title : "");
                $("#registrationTableBody").html('<tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>');
                $("#totalRegistrations").text("0");

                $.ajax({

                    url: "../../api/get-event-registration.php",
                    type: "GET",

                    data: {
                        event_id: event_id
                    },

                    dataType: "json",

                    success: function(response) {

                        let output = "";

                        if (response.status && response.data && response.data.length > 0) {

                            $.each(response.data, function(index, user) {

                                output += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.phone}</td>
                                        <td>${user.status}</td>
                                    </tr> `;
                            });

                            $("#registrationTableBody").html(output);
                            $("#totalRegistrations").text(response.data.length);
                        } else {
                            $("#registrationTableBody").html(`
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No registrations found</td>
                                </tr>
                            `);
                            $("#totalRegistrations").text(0);
                        }

                        $("#registrationModal").modal("show");
                    },

                    error: function(err) {

                        // When API returns 404 (Data not found / no registrations)

                        $("#registrationTableBody").html(`
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No registrations found</td>
                                </tr>
                            `);
                        $("#totalRegistrations").text(0);
                        $("#registrationModal").modal("show");
                    }
                });
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
                let event_date = $("#event_date").val();
                let image = $("#image")[0].files[0];

                let formData = new FormData();

                formData.append("event_title", title);
                formData.append("event_description", description);
                formData.append("event_category_id", category_id);
                formData.append("event_venus", venus);
                formData.append("event_start_time", start_time);
                formData.append("event_end_time", end_time);
                formData.append("event_capacity", capacity);
                formData.append("event_date", event_date);
                formData.append("event_register_deadline", event_date);

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
                $("#event_date").val("");
                $("#image").val("");
                $("#eventModalTitle").text("Add Event");
                $("#saveEvent").html(`
                <i class="bi bi-plus-lg me-1"></i>
                Add Event
            `);
            }

            //delete event
            $(document).on("click", ".delete-btn", function() {

                if (confirm("Are you sure you want to delete this event?")) {

                    let delete_id = $(this).data("id");
                    let delete_btn = this;

                    $.ajax({

                        url: "../../api/delete-event.php",
                        type: "POST",
                        dataType: "json",

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

                        error: function(err) {
                            console.log(err.responseText);
                        }
                    });
                }

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
                            $("#event_date").val(event_data.event_date);
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

                    error: function(err) {
                        console.log(err.responseText);
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
                        error: function(err) {
                            console.log(err.responseText);
                        }
                    });
                }

            });

        });
    </script>
</body>

</html>