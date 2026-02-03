<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css_folder/login.css">

    <!-- bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <div class="container-fluid p-0">

        <form action="?url=login" method="post">

            <div class="login-parent">
                <div class="form-extension image-container">
                    <img src="../images/login-bg.jpg" alt="">
                </div>
                <div class="form-extension form">

                    <div class="form-logo">
                        <div class="parent-logo">
                            <div class="logo"></div>
                            <p>Computer System Servicing</p>
                        </div>
                        <h2>Let's the learning journey begin.</h2>
                        <p>Unlock a world of learning with just one click. Log in to get started.</p>
                    </div>

                    <div class="text-box">
                        <label>Username or Email</label>
                        <input type="text" class="form-control" placeholder="Enter username or email" name="email" id="" required>
                    </div>

                    <div class="text-box">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Enter password" name="password" id="" required>
                    </div>

                    <div class="forgot text-end">
                        <a href="#">Forgot Password</a>
                    </div>

                    <div class="button">
                        <button class="submit">Login</button>
                    </div>

                    <h4>Or Login with</h4>

                    <div class="accounts">
                        <a href="#">
                            <i class="fa-brands fa-google"></i>
                            <p class="m-0">Google</p>
                        </a>

                        <a href="#">
                            <i class="fa-brands fa-facebook"></i>
                            <p class="m-0">Facebook</p>
                        </a>
                    </div>

                    <div class="change">
                        <label>Don't have an account? <a href="/learning_management/public/?url=signup">Signup</a> </label>
                    </div>

                </div>

            </div>
        </form>
    </div>

    <!-- bootstrap link javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>