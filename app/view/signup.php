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
                                    <input type="text" name="student_id" class="form-control mt-2"
                                        placeholder="e.g. 123-4-56789012"
                                        oninput="this.value = this.value.replace(/[^0-9-]/g, '')" required>
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
                                    <input type="email" class="form-control mt-2"
                                        placeholder="e.g. juandelacruz@gmail.com" name="email" id="" required>
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
                                        name="password" id="" required>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="text-box">
                                    <label>Confirm Password</label>
                                    <input type="password" class="form-control mt-2"
                                        placeholder="Re-enter your password" name="confirm_password" id=""
                                        required>
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

                        <div class="change">
                            <label>Already have an account? <a href="/learning_management/public/?url=login">Sign in
                                    here to
                                    proceed</a>
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