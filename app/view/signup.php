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
                <div class="form-extension form">

                    <div class="form-logo">
                        <div class="parent-logo">
                            <!-- <div class="logo"></div> -->
                            <div class="logo-icon">
                                <i class="fa-solid fa-lightbulb"></i>
                            </div>
                            <div class="logo-text">
                                <p><b>i</b>Learn</p>
                            </div>
                        </div>
                        <h2 class="fw-semibold">Start your <b>learning</b> journey today.</h2>
                        <p>Create an account and start learning today.</p>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-box">
                                <label>Student ID / LRN</label>
                                <input type="text" name="student_id" class="form-control"
                                    placeholder="e.g. 123456789012"
                                    oninput="this.value = this.value.replace(/[^0-9-]/g, '')" required>
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="text-box">
                                <label>Firstname</label>
                                <input type="text" class="form-control" placeholder="e.g. Juan" name="firstname" id=""
                                    oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="text-box">
                                <label>M.I</label>
                                <input type="text" class="form-control" placeholder="e.g. D" name="middle" id=""
                                    oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                            </div>
                        </div>

                        <div class="col-lg-4 mt-3">
                            <div class="text-box">
                                <label>Lastname</label>
                                <input type="text" class="form-control" placeholder="e.g. Dela Cruz" name="lastname"
                                    id="" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')" required>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Email</label>
                                <input type="email" class="form-control" placeholder="e.g. juandelacruz@gmail.com"
                                    name="email" id="" required>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Username</label>
                                <input type="text" class="form-control" placeholder="e.g. juan_dc12" name="username"
                                    id="" required>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Password</label>
                                <input type="password" class="form-control" placeholder="At least 8 characters"
                                    maxlength="8" name="password" id="" required>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="text-box">
                                <label>Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Re-enter your password"
                                    maxlength="8" name="confirm_password" id="" required>
                            </div>
                        </div>

                        <div class="col-lg-6 mt-3">
                            <div class="text-box">
                                <label>Grade Level</label>
                                <select name="grade_level_id" id="grade_level_select" class="form-select" required>
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
                                <select name="section_id" id="section_select" class="form-select" required>
                                    <option value="">Select section</option>
                                    <?php foreach ($sections as $section): ?>
                                        <option value="<?= $section['id']; ?>"
                                            data-grade-id="<?= $section['grade_level_id']; ?>" class="section-option">
                                            <?= $section['section_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>



                    </div>

                    <div class="button">
                        <button type="submit">Signup</button>
                    </div>

                    <!-- <h4>Or Login with</h4>

                    <div class="accounts">
                        <a href="#">
                            <i class="fa-brands fa-google"></i>
                            <p class="m-0">Google</p>
                        </a>

                        <a href="#">
                            <i class="fa-brands fa-facebook"></i>
                            <p class="m-0">Facebook</p>
                        </a>
                    </div> -->

                    <div class="change">
                        <label>Already have an account? <a href="/learning_management/public/?url=login">Login</a>
                        </label>
                    </div>

                </div>

            </div>
        </form>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

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