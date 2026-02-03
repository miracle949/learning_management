<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../css_folder/signup.css">

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

        <form action="?url=signup" method="post">

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
                        <h2>Start your learning journey today.</h2>
                        <p>Create an account and start learning today.</p>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-box">
                                <label>Student ID / LRN</label>
                                <input type="text" class="form-control" placeholder="e.g. 123456789012" name="student_id" id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter student id or LRN" name="" id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="text-box">
                                <label>Firstname</label>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter firstname" name="" id="">
                                </div> -->
                                <input type="text" class="form-control" placeholder="e.g. Juan" name="firstname" id="" required>
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="text-box">
                                <label>M.I</label>
                                <input type="text" class="form-control" placeholder="e.g. D" name="middle" id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter firstname" name="" id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="text-box">
                                <label>Lastname</label>
                                <input type="text" class="form-control" placeholder="e.g. Dela Cruz" name="lastname" id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter lastname" name="" id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="e.g. juandelacruz@gmail.com" name="email" id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    <input type="email" class="form-control" placeholder="Enter email" name="" id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="e.g. juan_dc12" name="username" id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" placeholder="Enter username" name="" id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="At least 8 characters" name="password"
                                        id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="Enter password" name=""
                                        id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Re-enter your password" name="confirm_password"
                                        id="" required>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" placeholder="Confirm password" name=""
                                        id="">
                                </div> -->
                            </div>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <div class="text-box">
                                <label>Grade Level</label>
                                <select name="grade_level" id="" class="form-select" required>
                                    <option value="" disabled selected>Select Grade Level</option>
                                    <option value="Grade 12">Grade 12</option>
                                    <option value="Grade 11">Grade 11</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <div class="text-box">
                                <label>Section</label>
                                <select name="section" class="form-select" id="" required>
                                    <option value="" disabled selected>Select Section</option>
                                    <option value="CSS 12-1">CSS 12-1</option>
                                    <option value="CSS 12-2">CSS 12-2</option>
                                    <option value="CSS 11-1">CSS 11-1</option>
                                    <option value="CSS 11-2">CSS 11-2</option>
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="e.g. CSS 11-A" name="section" id="" required> -->
                            </div>
                        </div>

                    </div>

                    <div class="button">
                        <button type="submit">Signup</button>
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
                        <label>Already have an account? <a href="/learning_management/public/?url=login">Login</a>
                        </label>
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