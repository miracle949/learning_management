<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction to Philosophy of Human Person</title>
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">

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

        <?php include("../components/offcanvas.php"); ?>

        <?php include("../components/navbar.php"); ?>

        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            
            <div class="module-title">
                <h1>Introduction to Philosophy of Human Person</h1>
                <p>CSS-1</p>
            </div>

            <div class="your-module">
                <h2>Your Modules</h2>
            </div>

            <div class="module-parent-progress">

                <div class="module-progress">
                    <h3>Introduction to Philosophy of Human Person</h3>

                    <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                        philosophical concepts.</p>

                    <div class="title-progress-bar">
                        <span id="lessonText">0 of 10 lessons</span>
                        <span id="lessonPercent">0%</span>
                    </div>

                    <div class="parent-progress-bar">
                        <div class="progress-bar">
                            <div class="progress" id="moduleProgress"></div>
                        </div>
                    </div>

                    <div class="footer-bar">
                        <a href="/learning_management/public/?url=subject_lessons">Continue learning <i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="module-progress">
                    <h3>Introduction to Philosophy of Human Person</h3>

                    <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                        philosophical concepts.</p>

                    <div class="title-progress-bar">
                        <span>6 of 10 lessons</span>

                        <span>60%</span>
                    </div>

                    <div class="parent-progress-bar">
                        <div class="progress-bar">
                            <div class="progress"></div>
                        </div>
                    </div>

                    <div class="footer-bar">
                        <a href="/learning_management/public/?url=lessons">Continue learning <i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                </div>

                <div class="module-progress">
                    <h3>Introduction to Philosophy of Human Person</h3>

                    <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                        philosophical concepts.</p>

                    <div class="title-progress-bar">
                        <span>6 of 10 lessons</span>

                        <span>60%</span>
                    </div>

                    <div class="parent-progress-bar">
                        <div class="progress-bar">
                            <div class="progress"></div>
                        </div>
                    </div>

                    <div class="footer-bar">
                        <a href="/learning_management/public/?url=lessons">Continue learning <i
                                class="fa fa-arrow-right"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        const completedLessons = localStorage.getItem("completedLessons") || 0;
        const totalLessons = localStorage.getItem("totalLessons") || 0;
        const percent = localStorage.getItem("lessonPercent") || 0;

        document.getElementById("lessonText").innerText =
            `${completedLessons} of ${totalLessons} lessons`;

        document.getElementById("lessonPercent").innerText =
            `${percent}%`;

        document.getElementById("moduleProgress").style.width =
            percent + "%";

    </script>


</body>

</html>