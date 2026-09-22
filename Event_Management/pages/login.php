<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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

                        <h3 class="text-center mb-4">EMS Login</h3>

                        <div class="toast-container position-fixed top-0 end-0 p-3">

                            <div class="toast fade border-0 shadow rounded-3 " id="loginToast">

                                <div class="toast-body d-flex align-items-center gap-3 px-3 py-3">

                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <span id="toastMessage" class="fw-medium flex-grow-1"></span>
                                    <button class="border-0 bg-transparent p-0" type="button" name="btn-close" data-bs-dismiss="toast"
                                        aria-label="Close"><i class="bi bi-x-circle-fill fs-5"></i></button>
                                </div>
                            </div>
                        </div>

                        <form id="loginForm">

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control mb-3" name="email" id="email" placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">password</label>
                                <input type="password" class="form-control mb-3" name="password" id="password" placeholder="Enter your password" required>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100" name="submit-btn" id="submit-btn">Register</button>
                            </div>

                            <div class="text-center mt-3">
                                Not have Account? <a href="register.php">Register</a>
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

            $("#loginForm").on("submit", function(e) {

                e.preventDefault();

                let email = $("#email").val();
                let password = $("#password").val();

                $.ajax({
                    url: "../api/login-user.php",
                    type: "POST",
                    data: {
                        user_email: email,
                        user_password: password
                    },
                    success: function(response) {

                        console.log(response);
                        console.log("status:", response.status);
                        console.log("role:", response.role);


                        $("#toastMessage").text(response.message);

                        let toastElement = document.getElementById("loginToast");
                        let toast = new bootstrap.Toast(toastElement, {
                            delay: 2000
                        });

                        toast.show();

                        if (response.status) {
                            setTimeout(function() {

                                if (response.role === "admin") {
                                    window.location.href = "admin/event.php";
                                } else if (response.role === "user") {
                                    window.location.href = "user/user_dashboard.php"
                                }
                            }, 2000);
                        }
                    },
                    error: function(err) {

                        let response = JSON.parse(err.responseText); //read the error response

                        $("#toastMessage").text(response.message);

                        let errElement = document.getElementById("loginToast");
                        let toast = new bootstrap.Toast(errElement, {
                            delay: 2000
                        });

                        toast.show();
                    }
                });
            });
        });
    </script>
</body>

</html>