<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/lessons.css">
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

        <div class="rightbar">
            <div class="navbar-lessons">
                <div class="navbar-title">
                    <a href="/learning_management/public/?url=subjects">
                        <i class="fa fa-arrow-left"></i>
                        <span>Back</span>
                    </a>

                    <span>|</span>

                    <p>Introduction to Philosophy of Human Person</p>
                </div>

                <div class="navbar-progress">
                    <div class="progress-title">
                        <p>Module Progress</p>
                        <span id="progressPercent">0%</span>
                    </div>

                    <div class="parent-progress">
                        <div class="progress-lesson" id="progressBar"></div>
                    </div>
                </div>
            </div>

            <div class="body-lessons">
                <h2>Introduction to Philosophy of Human Person</h2>

                <div class="sidebar-lessons">
                    <h4>Lessons</h4>

                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="fa fa-check"></i>
                                    <span>What is Computer Network?</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <hr>

                    <div class="take-quiz">
                        <a href="/learning_management/public/?url=subject_quiz" class="btn">Take Quiz</a>
                    </div>
                </div>

                <div class="view-lessons">
                    <div class="view-title">
                        <i class="fa fa-book-open"></i>
                        <span id="lesson-count">Lesson 1 of 5</span>
                    </div>

                    <h3 id="lesson-title"></h3>
                    <hr>

                    <div class="view-lessons-body" id="lesson-body"></div>

                    <hr>

                    <div class="view-lessons-footer">
                        <nav aria-label="Page navigation">
                            <ul class="pagination m-0">
                                <li class="page-item">
                                    <a class="page-link" href="#" id="prevBtn">
                                        <i class="fa fa-chevron-left"></i>
                                        <span>Previous Lesson</span>
                                    </a>
                                </li>

                                <li class="page-item disabled">
                                    <span id="page-indicator">1 / 5</span>
                                </li>

                                <li class="page-item">
                                    <a class="page-link" href="#" id="nextBtn">
                                        <span>Next Lesson</span>
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- bootstrap link javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script src="../javascript_folder/philosophy.js"></script>
</body>

</html>