<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../css_folder/signup.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <form action="?url=signup" method="post">

            <div class="login-parent">
                <!-- <div class="form-extension image-container">
                    <img src="../images/login-bg.jpg" alt="">
                    <h2>Signup</h2>
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

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="text-box">
                                    <label>Student LRN No</label>
                                    <input type="text" maxlength="11" name="student_id"
                                        class="form-control mt-2 <?= !empty($errors['student_id']) ? 'is-invalid' : '' ?>"
                                        placeholder="e.g. 1071234567"
                                        value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    <?php if (!empty($errors['student_id'])): ?>
                                        <div class="text-danger small mt-1"><?= $errors['student_id'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <div class="text-box">
                                    <label>Firstname</label>
                                    <input type="text" class="form-control mt-2" placeholder="e.g. Juan"
                                        name="firstname" id=""
                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <div class="text-box">
                                    <label>Middlename</label>
                                    <input type="text" class="form-control mt-2" placeholder="e.g. Ceraphin"
                                        name="middle" id="" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-4 mt-3">
                                <div class="text-box">
                                    <label>Lastname</label>
                                    <input type="text" class="form-control mt-2" placeholder="e.g. Dela Cruz"
                                        name="lastname" id=""
                                        oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="text-box">
                                    <label>Email <span>(We'll verify your email after you register)</span></label>
                                    <input type="email"
                                        class="form-control mt-2 <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                                        placeholder="e.g. juandelacruz@gmail.com" name="email"
                                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                    <?php if (!empty($errors['email'])): ?>
                                        <div class="text-danger small mt-1"><?= $errors['email'] ?></div>
                                    <?php endif; ?>
                                    <!-- <div class="reminder">
                                        <span>Enter a valid existing email</span>
                                    </div> -->
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="text-box">
                                    <label>Username</label>
                                    <input type="text" class="form-control mt-2" placeholder="e.g. juan_dc12"
                                        name="username" id="" required>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="text-box">
                                    <label>Password</label>
                                    <input type="password" class="form-control mt-2" placeholder="Enter your password"
                                        name="password" id="password" minlength="8" required>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="text-box">
                                    <label>Confirm Password</label>
                                    <input type="password"
                                        class="form-control mt-2 <?= !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                        placeholder="Re-enter your password" name="confirm_password" required>
                                    <?php if (!empty($errors['confirm_password'])): ?>
                                        <div class="text-danger small mt-1"><?= $errors['confirm_password'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <div class="text-box">
                                    <label>Grade Level</label>
                                    <select name="grade_level_id" id="grade_level_select" class="form-select mt-2"
                                        required>
                                        <option value="">Select grade level</option>
                                        <?php foreach ($grades as $grade): ?>
                                            <option value="<?= $grade['id']; ?>"
                                                data-name="<?= htmlspecialchars($grade['name']); ?>">
                                                <?= $grade['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 mt-3">
                                <div class="text-box">
                                    <label>Section</label>
                                    <select name="section_id" id="section_select"
                                        class="form-select <?= !empty($errors['section_id']) ? 'is-invalid' : '' ?>"
                                        required>
                                        <option value="">Select section</option>
                                        <?php foreach ($sections as $section): ?>
                                            <option value="<?= $section['id']; ?>"
                                                data-grade-id="<?= $section['grade_level_id']; ?>" class="section-option">
                                                <?= $section['section_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (!empty($errors['section_id'])): ?>
                                        <div class="text-danger small mt-1"><?= $errors['section_id'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                        <div class="button">
                            <button type="submit">Signup</button>
                        </div>

                        <div class="change">
                            <label>Already have an account? <a href="/learning_management/public/?url=login">Sign in</a>
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

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-4">

                <div class="modal-body">
                    <div class="mb-3">
                        <i class="fa-solid fa-circle-check" style="font-size: 60px; color: #28a745;"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Registration Successful!</h4>
                    <p class="text-muted mb-1">Your account has been created and is currently <strong>pending
                            approval</strong>.</p>
                    <p class="text-muted mt-2">Please wait while the admin reviews and activates your account. You'll be
                        able
                        to log in once it's approved.</p>
                </div>

                <div class="modal-footer justify-content-center border-0 pt-0">
                    <a href="/learning_management/public/?url=login" class="btn btn-success px-4">Go to Login</a>
                </div>

            </div>
        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        <?php if (!empty($_SESSION['signup_success'])): ?>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('successModal'));
                modal.show();
            });
            <?php unset($_SESSION['signup_success']); ?>
        <?php endif; ?>
    </script>

    <script>
        // Remove error state on valid input
        const fieldsToWatch = [
            { selector: 'input[name="student_id"]', errorKey: 'student_id' },
            { selector: 'input[name="email"]', errorKey: 'email' },
            { selector: 'input[name="confirm_password"]', errorKey: 'confirm_password' },
            { selector: 'select[name="section_id"]', errorKey: 'section_id' },
        ];

        fieldsToWatch.forEach(({ selector }) => {
            const field = document.querySelector(selector);
            if (!field) return;

            field.addEventListener('input', () => clearError(field));
            field.addEventListener('change', () => clearError(field));
        });

        function clearError(field) {
            if (field.value.trim() !== '') {
                // Remove red border
                field.classList.remove('is-invalid');

                // Remove the error message below the field
                const errorDiv = field.parentElement.querySelector('.text-danger');
                if (errorDiv) errorDiv.remove();
            }
        }

        // Special case: confirm password — re-check match live
        const passwordField = document.querySelector('input[name="password"]');
        const confirmPasswordField = document.querySelector('input[name="confirm_password"]');

        confirmPasswordField.addEventListener('input', () => {
            if (confirmPasswordField.value === passwordField.value && confirmPasswordField.value !== '') {
                confirmPasswordField.classList.remove('is-invalid');
                const errorDiv = confirmPasswordField.parentElement.querySelector('.text-danger');
                if (errorDiv) errorDiv.remove();
            } else if (confirmPasswordField.value !== passwordField.value) {
                confirmPasswordField.classList.add('is-invalid');
            }
        });

        // Password minimum 8 characters live validation
        const passwordInput = document.querySelector('input[name="password"]');

        passwordInput.addEventListener('input', () => {
            const existingError = passwordInput.parentElement.querySelector('.text-danger');

            if (passwordInput.value.length > 0 && passwordInput.value.length < 8) {
                passwordInput.classList.add('is-invalid');
                if (!existingError) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'text-danger small mt-1';
                    errorDiv.textContent = 'Password must be minimum of at least 8 characters.';
                    passwordInput.parentElement.appendChild(errorDiv);
                }
            } else {
                passwordInput.classList.remove('is-invalid');
                if (existingError) existingError.remove();
            }
        });
    </script>

    <script>
        const gradeSelect = document.getElementById('grade_level_select');
        const sectionSelect = document.getElementById('section_select');
        const allSectionOptions = Array.from(sectionSelect.querySelectorAll('.section-option'));

        gradeSelect.addEventListener('change', function () {
            const selectedGradeId = this.value;

            // Reset section
            sectionSelect.innerHTML = '<option value="">Select section</option>';

            if (selectedGradeId) {
                const filtered = allSectionOptions.filter(opt => opt.dataset.gradeId === selectedGradeId);

                if (filtered.length > 0) {
                    filtered.forEach(opt => sectionSelect.appendChild(opt.cloneNode(true)));
                } else {
                    sectionSelect.innerHTML = '<option value="">No sections available</option>';
                }
            }
        });
    </script>
</body>

</html>