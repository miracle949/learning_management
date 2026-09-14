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

        <main>

            <div class="main-sub-form-parent">
                <div class="main-image-form">
                    <div class="carousel-slide slide-1">
                        <img src="../images/login2.jpg" alt="">
                    </div>

                    <div class="carousel-slide slide-2">
                        <img src="../images/login5.jpg" alt="">
                    </div>

                    <div class="carousel-slide slide-3">
                        <img src="../images/login9.jpg" alt="">
                    </div>

                    <div class="wave-bottom">
                        <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                            <path
                                d="M0,40 C60,10 120,70 180,40 C240,10 300,70 360,40 C420,10 460,60 500,40 L500,150 L0,150 Z"
                                fill="#ffffff"></path>
                        </svg>
                    </div>

                    <div class="main-image-text">
                        <div class="main-image-logo">
                            <a href="/learning_management/public/?url=landingpage">
                                <img src="../images/logo2.png" alt="">
                                <h3>SHS Strand</h3>
                            </a>
                        </div>
                        <div class="main-image-tag">Learning Management System</div>
                        <h1>Unlock your learning potential with one login.</h1>
                    </div>
                </div>

                <div class="main-parent-form">
                    <form action="?url=login" method="post" id="login-form">

                        <!-- Tracks which form is active: "student" or "staff".
                             The controller reads this to decide loginByLRN() vs loginByIdentifier(). -->
                        <input type="hidden" name="login_type" id="login_type"
                            value="<?= (($_POST['login_type'] ?? 'student') === 'staff') ? 'staff' : 'student' ?>">

                        <div class="card-logo">
                            <a href="/learning_management/public/?url=landingpage">
                                <img src="../images/logo1.png" alt="">
                                <h3>SHS Strand</h3>
                            </a>
                        </div>

                        <h2>
                            <span id="login-heading-student" style="font-weight: bold;">Hi, Welcome
                                <a href="#" id="toggle-to-staff" title="Teacher / Admin / Super Admin login"
                                    style="color: inherit; text-decoration: none; font-weight: bold;">Back!</a>
                            </span>
                            <span id="login-heading-staff" style="display: none; font-weight: bold;">Staff Login</span>
                        </h2>

                        <p id="login-subtext-student">Unlock a world of learning with just one click. Log in to get
                            started and access your account.
                        </p>
                        <p id="login-subtext-staff" style="display: none;">
                            For Teachers, Admins, and Super Admins.
                            <a href="#" id="toggle-to-student">Back to student login</a>.
                        </p>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3"
                                style="border-radius: 12px; padding: 10px 15px" role="alert">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span style="font-size: 14px;"><?= htmlspecialchars($error) ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Student field: LRN -->
                        <div class="form-input form-input1" id="field-lrn">
                            <label>Learner Reference Number (LRN)</label>
                            <input type="text" name="lrn" id="lrn-input"
                                value="<?= htmlspecialchars($_POST['lrn'] ?? '') ?>" placeholder="Enter your LRN"
                                inputmode="numeric" pattern="\d{12}" maxlength="12" autocomplete="off"
                                title="LRN must be 12 digits">
                        </div>

                        <!-- Staff field: username -->
                        <div class="form-input form-input1" id="field-identifier" style="display: none;">
                            <label>Username</label>
                            <input type="text" name="identifier" id="identifier-input"
                                value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>"
                                placeholder="Enter your username" autocomplete="off">
                        </div>

                        <div class="form-input form-input2" style="position: relative;">
                            <label>Password</label>
                            <input type="password" id="login-password" name="password" placeholder="Enter your password"
                                style="padding-right: 40px;" required>
                            <span onclick="toggleLoginPassword()"
                                style="position: absolute; right: 12px; top: 75%; transform: translateY(-50%); cursor: pointer; color: #888;">
                                <i class="fa fa-eye" id="eye-login"></i>
                            </span>
                        </div>

                        <div class="form-forgot">
                            <a href="#">Forgot Password</a>
                        </div>

                        <button class="submit" class="<?= ($current_url ?? '') === 'dashboard' ? 'active' : '' ?>">Sign
                            In</button>

                        <div class="need-help">
                            <p>Need help signing in? Contact your school's
                                <br>
                                CSS Instructor or <b>visit the Help Center</b>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </main>

    </div>

    <script>
        const lrnInput = document.getElementById('lrn-input');
        const identifierInput = document.getElementById('identifier-input');
        const loginTypeField = document.getElementById('login_type');

        const fieldLrn = document.getElementById('field-lrn');
        const fieldIdentifier = document.getElementById('field-identifier');

        const headingStudent = document.getElementById('login-heading-student');
        const headingStaff = document.getElementById('login-heading-staff');
        const subtextStudent = document.getElementById('login-subtext-student');
        const subtextStaff = document.getElementById('login-subtext-staff');

        // Strip anything that isn't a digit as the user types — pattern="" alone
        // only validates on submit, it doesn't block keystrokes.
        lrnInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 12);
        });

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

        function showStaffLogin() {
            fieldLrn.style.display = 'none';
            fieldIdentifier.style.display = '';
            headingStudent.style.display = 'none';
            headingStaff.style.display = '';
            subtextStudent.style.display = 'none';
            subtextStaff.style.display = '';

            lrnInput.required = false;
            identifierInput.required = true;
            loginTypeField.value = 'staff';
        }

        function showStudentLogin() {
            fieldIdentifier.style.display = 'none';
            fieldLrn.style.display = '';
            headingStaff.style.display = 'none';
            headingStudent.style.display = '';
            subtextStaff.style.display = 'none';
            subtextStudent.style.display = '';

            identifierInput.required = false;
            lrnInput.required = true;
            loginTypeField.value = 'student';
        }

        document.getElementById('toggle-to-staff').addEventListener('click', function (e) {
            e.preventDefault();
            showStaffLogin();
        });

        document.getElementById('toggle-to-student').addEventListener('click', function (e) {
            e.preventDefault();
            showStudentLogin();
        });

        // If the page reloaded after a failed staff-login POST, stay on the staff form.
        if (loginTypeField.value === 'staff') {
            showStaffLogin();
        } else {
            showStudentLogin();
        }
    </script>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>