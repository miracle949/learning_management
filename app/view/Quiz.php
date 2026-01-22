<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="../css_folder/Quiz.css">
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
            <div class="navbar-quiz">
                <div class="navbar-title">
                    <a href="/learning_management/public/?url=lessons">
                        <i class="fa fa-arrow-left"></i>
                        <span>Back to Module</span>
                    </a>
                </div>

                <div class="navbar-complete-time">
                    <!-- <div class="time">
                        <i class="fa fa-clock"></i>
                        <p>0:00</p>
                    </div> -->
                    <div class="answered">
                        <p id="answered-count">0 / 10 answered</p>
                    </div>
                </div>
            </div>

            <div class="body-quiz">
                <div class="quiz-section">

                    <div class="quiz-progress-bar">
                        <div class="quiz-title">
                            <p id="question-count"></p>
                            <span id="question-percent"></span>
                        </div>

                        <div class="quiz-parent-progress">
                            <div class="quiz-progress" id="quizProgress"></div>
                        </div>
                    </div>

                    <div class="answer-quiz">
                        <div class="multiple">
                            <span>Multiple Choice</span>
                        </div>

                        <h3 id="quiz-question"></h3>

                        <div class="choose-answer mt-4">
                            <div class="options" id="quiz-options"></div>
                        </div>
                    </div>

                    <div class="quiz-footer">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" id="prevBtn">
                                        <i class="fa fa-chevron-left"></i>
                                        <span>Previous</span>
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link" href="#" id="nextBtn">
                                        <span>Next Question</span>
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

    <script src="../javascript_folder/quiz.js"></script>
</body>

</html>