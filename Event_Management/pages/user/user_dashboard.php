<?php

session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "user") {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user-Dashabord</title>
    <link rel="stylesheet" href="style.css">
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
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="user_dashboard.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Registration</a>
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

    <div class="container mt-5">

        <div class="row" id="eventContainer"></div>
    </div>

    <div class="modal fade" id="register-event-model" index="-1">
        <div class="modal-dialog modal-modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        Register Event
                    </h5>
                </div>
                <div class="modal-body">

                    <!-- User ID -->
                    <input type="hidden" id="register_user_id">

                    <!-- Event ID -->
                    <input type="hidden" id="register_event_id">

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>

                        <input
                            type="text"
                            id="register_phone"
                            class="form-control"
                            placeholder="Enter phone number"
                            maxlength="10"
                            required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-primary" id="confirmRegisterEvent">
                        Confirm Registration
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

            function loadEvent() {
                $.ajax({
                    url: "../../api/get-event.php",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        let output = "";

                        let eventImages = [
                            "../../Image/tech.png",
                            "../../Image/sports.png"
                        ];

                        if (response.status && response.data && response.data.length > 0) {
                            $.each(response.data, function(index, event) {
                                // DB ma image path: ./upload/event_images/file.jpg
                                // pages/user/ thi access: ../../api/upload/event_images/file.jpg
                                let imgSrc = event.image ?
                                    event.image.replace("./", "../../api/") :
                                    eventImages[index % eventImages.length];

                                output += `
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-1 shadow-lg">

                                        <!-- Event Image -->
                                        <div class="overflow-hidden">
                                            <img
                                                src="${imgSrc}"
                                                class="card-img-top"
                                                style="height: 190px; object-fit: cover;"
                                                alt="Event">
                                        </div>

                                        <div class="card-body">

                                            <div class="mb-2">
                                                <span class="badge bg-primary">
                                                    ${event.category_name ?? ''}
                                                </span>
                                            </div>

                                            <h5 class="fw-bold mb-2">
                                                ${event.title}
                                            </h5>

                                            <p class="text-muted small mb-3">
                                                ${event.description}
                                            </p>

                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-calendar3 text-primary"></i> Registration Deadline: 
                                                ${event.register_deadline}
                                            </div>

                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-clock text-primary"></i> Start: 
                                                ${event.start_time}
                                            </div>

                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-clock text-primary"></i> End: 
                                                ${event.end_time}
                                            </div>

                                            <div class="small text-muted mb-2">
                                                <i class="bi bi-geo-alt text-primary"></i> Venue: 
                                                ${event.venus}
                                            </div>

                                            <div class="small text-muted mb-3">
                                                <i class="bi bi-people text-primary"></i> Capacity: 
                                                ${event.capacity}
                                            </div>

                                            <button type="button" class="btn btn-primary w-100
                                            register-event-btn" data-id=${event.id}>
                                              <i class="bi bi-person-plus ms-1"></i>
                                              Register Event
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
                            });
                        } else {
                            output = `
                            <div class="col-12 text-center py-5">
                                <div class="alert alert-info shadow-sm">
                                    <i class="bi bi-info-circle me-2"></i> No upcoming events found.
                                </div>
                            </div>`;
                        }
                        $("#eventContainer").html(output);
                    },
                    error: function() {
                        $("#eventContainer").html(`
                            <div class="col-12 text-center py-5">
                                <div class="alert alert-danger shadow-sm">
                                    <i class="bi bi-exclamation-triangle me-2"></i> Failed to load events.
                                </div>
                            </div>`);
                    }
                });
            }
            loadEvent();

            $(document).on("click", ".register-event-btn", function() {

                let event_id = $(this).data("id");
                $("#register_event_id").val(event_id);
                $("#register-event-model").modal("show");
            });

            $(document).on("click", "#confirmRegisterEvent", function() {

                let event_id = $("#register_event_id").val();
                let phone = $("#register_phone").val();

                if (phone === "") {
                    alert("Phone number required");
                    exit;
                }

                $.ajax({
                    url: "../../api/register-event.php",
                    type: "POST",
                    data: {
                        event_id: event_id,
                        phone: phone
                    },
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);

                        if (response.status) {

                            $("#register-event-model").modal("hide");
                            $("register_phone").val();

                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                });

            });

            // Logout
            $("#logout-btn").on("click", function() {
                if (confirm("Are you sure for logout")) {
                    $.ajax({
                        url: "../../api/logout-user.php",
                        type: "GET",
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