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

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">

                        <h3 class="text-center mb-4">Event</h3>

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

                        <div id="eventRegisterForm">

                            <div class="mb-3">
                                <label class="form-label">Event Id</label>
                                <select id="event_id" class="form-select">
                                    <option value="">Select Event Id</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">User Id</label>
                                <select id="user_id" class="form-select">
                                    <option value="">Select User Id</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="number" id="phone" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>