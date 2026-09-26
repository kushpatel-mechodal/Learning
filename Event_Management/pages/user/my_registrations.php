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
    <title>My Registrations</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css"
        integrity="sha384-Bk5cbLkZQ5raZ0+H2/+VbfYx3WpvxvQK4zqXZr7sYODuaX7bKXoSOnipQxkaS8sv"
        crossorigin="anonymous">
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
                        <a class="nav-link" href="user_dashboard.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="my_registrations.php">My Registration</a>
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
            <h2 class="mb-0">
                My Registrations
            </h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table table-hover table-bordered text-center align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Event</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-nowrap" style="min-width: 170px;">Action</th>
                            </tr>
                        </thead>

                        <tbody id="register_userTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Loading registrations...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Event Modal -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEventTitle">Event Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                                               <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Registered Phone:</strong>
                            <span id="modalPhone" class="fw-semibold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Venue:</strong>
                            <span id="modalVenue"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Event Date:</strong>
                            <span id="event-date"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Description:</strong>
                            <p id="modalDescription" class="text-muted small mt-1 mb-0"></p>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            let registrationsData = [];

            function loadRegisterData() {

                let output = "";

                $.ajax({
                    url: "../../api/get-user-registration.php",
                    type: "GET",
                    success: function(response) {

                        if (response.data && response.data.length > 0) {
                            registrationsData = response.data;

                            $.each(response.data, function(index, register_user) {

                                let statusBadge = "";
                                let status = register_user.status;

                                if (status === "pending") {
                                    statusBadge = `<span class="badge bg-warning text-dark">Pending</span>`;
                                } else if (status === "approved") {
                                    statusBadge = `<span class="badge bg-success">Approved</span>`;
                                } else if (status === "rejected") {
                                    statusBadge = `<span class="badge bg-danger">Rejected</span>`;
                                } else {
                                    statusBadge = `<span class="badge bg-secondary">${register_user.status || '-'}</span>`;
                                }

                                let regId = register_user.id || register_user.registration_id;
                                let pdfButton = "";
                                if (status === "approved" && regId) {
                                    pdfButton = `
                                        <a href="../../api/generate-pdf.php?registration_id=${regId}" target="_blank" class="btn btn-danger btn-sm text-nowrap">
                                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
                                        </a>
                                    `;
                                }

                                let action = `
                                    <div class="d-inline-flex justify-content-center align-items-center gap-1">
                                        <button type="button" class="btn btn-dark btn-sm view-btn" data-index="${index}">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                        ${pdfButton}
                                    </div>
                                `;

                                output += `

                                <tr>
                                    <td>${index + 1}</td>
                                    <td>
                                        <div class="fw-semibold">
                                            ${register_user.title || '-'}
                                        </div>
                                    </td>

                                    <td>
                                        ${register_user.phone || '-'}
                                    </td>

                                    <td>
                                        ${statusBadge}
                                    </td>

                                    <td>
                                        ${action}
                                    </td>
                                </tr>`;
                            });

                            $("#register_userTableBody").html(output);
                        } else {
                            $("#register_userTableBody").html(`
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No registrations found</td>
                                </tr>
                            `);
                        }
                    },
                    error: function() {
                        $("#register_userTableBody").html(`
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No registrations found</td>
                            </tr>
                        `);
                    }
                });
            }

            loadRegisterData();

            $(document).on("click", ".view-btn", function() {

                let index = $(this).data("index");
                let data = registrationsData[index];

                $("#modalEventTitle").text(data.title);
                $("#modalPhone").text(data.phone);
                $("#modalVenue").text(data.venus);
                $("#event-date").text(data.event_date);
                $("#modalDescription").text(data.description);

                $("#eventDetailModal").modal("show");
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