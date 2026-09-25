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
    <title>Event register_users</title>

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
                        <a class="nav-link" href="category.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register_users.php">Registrations</a>
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
                Event register users
            </h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">

                    <table
                        class="table table-hover table-bordered text-center align-middle mb-0">
                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Event</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="register_userTableBody">
                        </tbody>
                    </table>
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


            function lodaRegisterData() {

                let output = "";

                $.ajax({
                    url: "../../api/get-register-event.php",
                    type: "GET",
                    success: function(response) {

                        $.each(response.data, function(index, register_user) {

                            let action = "";

                            //show buttons only when status pending
                            if (register_user.status === "pending") {

                                action = `

                                 <button
                                        type="button"
                                        class="btn btn-success btn-sm approve-btn" data-id="${register_user.id}">
                                        <i class="bi bi-check-lg"></i>
                                        Approve
                                    </button>

                                    <button
                                        type="button" class="btn btn-danger btn-sm reject-btn"
                                        data-id="${register_user.id}">
                                        <i class="bi bi-x-lg"></i>
                                        Reject
                                    </button>
                                `;
                            } else {

                                action = "";
                            }

                            output += `

                             <tr>

                                <td>
                                    ${index + 1}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        ${register_user.name}
                                    </div>
                                </td>

                                <td>
                                    ${register_user.email}
                                </td>

                                <td>
                                    ${register_user.phone}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        ${register_user.event_title}
                                    </span>
                                </td>

                                <td>
                                    ${register_user.status}
                                </td>

                                <td>
                                    ${action}
                                </td>
                            </tr>`;
                        });

                        $("#register_userTableBody").html(output);
                    }
                });
            }

            lodaRegisterData();

            $(document).on("click", ".approve-btn", function() {

                let id = $(this).data("id");
                let button = $(this);
                $.ajax({
                    url: "../../api/update-register-status.php",
                    type: "POST",
                    data: {
                        id: id,
                        status: "approved"
                    },
                    dataType: "json",
                    success: function(response) {

                        if (response.status) {

                            lodaRegisterData();
                        } else {
                            alert(response.message)
                        }
                    }
                });
            });

            $(document).on("click", ".reject-btn", function() {

                let id = $(this).data("id");
                let button = $(this);
                $.ajax({
                    url: "../../api/update-register-status.php",
                    type: "POST",
                    data: {
                        id: id,
                        status: "rejected"
                    },
                    dataType: "json",
                    success: function(response) {

                        if (response.status) {

                            lodaRegisterData();
                        } else {
                            alert(response.message)
                        }
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
                        },
                    });
                }
            });
        });
    </script>
</body>

</html>