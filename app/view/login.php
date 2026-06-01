<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css_folder/login.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <form action="?url=login" method="post">
            <div class="login-parent">
                <!-- <div class="form-logo">
                    <div class="parent-logo">
                        <div class="logo-icon">
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>
                        <div class="logo-text">
                            <p><b>i</b>Learn</p>
                        </div>
                    </div>
                    <h2>Let's the learning journey begin.</h2>
                    <p>Unlock a world of learning with just one click. Log in to get started.</p>
                </div> -->
                <div class="image-form">
                    <div class="image-parent">
                        <div class="image-container">
                            <div class="image-nav">
                                <i class="fa fa-star"></i>
                                <span>Education Platform</span>
                            </div>

                            <h3>Start your <b>learning journey</b> today and take the first step toward achieving your
                                goals.</h3>

                            <p>Join thousands of students unlocking their potential through quality education, gaining
                                new
                                skills, building confidence, and achieving their academic goals every day.</p>
                        </div>
                        <div class="image-footer">
                            <div class="footer">
                                <p>10k+</p>
                                <span>Students</span>
                            </div>

                            <div class="footer">
                                <p>500+</p>
                                <span>Courses</span>
                            </div>

                            <div class="footer">
                                <p>98%</p>
                                <span>Satisfaction</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field-form">
                    <div class="form-extension form">

                        <!-- <p>Just one step away from your courses—step in and start learning.</p> -->

                        <div class="form-logo">
                            <div class="parent-logo">
                                <a href="/learning_management/public/?url=landingpage">
                                    <div class="logo-icon">
                                        <i class="fa-solid fa-lightbulb"></i>
                                    </div>
                                </a>
                                <div class="logo-text">
                                    <p><b>i</b>Learn</p>
                                </div>
                            </div>
                            <h2>Let's the learning journey begin.</h2>
                            <p>Unlock a world of learning with just one click. Log in to get started.</p>
                        </div>


                        <div class="text-box">
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3"
                                    style="border-radius: 28px; padding: 10px 15px" role="alert">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                    <span style="font-size: 15px;"><?= htmlspecialchars($error) ?></span>
                                </div>
                            <?php endif; ?>

                            <label>Email</label>
                            <input type="text" class="form-control mt-2" placeholder="Enter email" name="email"
                                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>

                        <div class="text-box" style="position: relative;">
                            <div class="forgot">
                                <label>Password</label>
                                <a href="#">Forgot Password?</a>
                            </div>
                            <input type="password" id="login-password" class="form-control mt-2" placeholder="Enter password"
                                name="password" style="padding-right: 40px;"  id="" required>
                            <span onclick="toggleLoginPassword()"
                                style="position: absolute; right: 12px; top: 71%; transform: translateY(-50%); cursor: pointer; color: #888;">
                                <i class="fa fa-eye" id="eye-login"></i>
                            </span>
                        </div>

                        <!-- <div class="forgot text-end">
                        <a href="#">Forgot Password</a>
                    </div> -->

                        <div class="button">
                            <button class="submit"
                                class="<?= $current_url === 'dashboard' ? 'active' : '' ?>">Login</button>
                        </div>

                        <div class="change">
                            <label>Don't have an account? <a href="/learning_management/public/?url=signup">Sign up</a>
                            </label>
                        </div>

                    </div>

                    <div class="change">

                        <label>Need help? Contact us at <a href="#">helloilearn@gmail.com</a></label>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        function toggleLoginPassword() {
            const input = document.getElementById('login-password');
            const icon = document.getElementById('eye-login');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>