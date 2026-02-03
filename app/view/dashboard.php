<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css_folder/style.css">
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

            <?php

            if ($_SESSION['grade_level'] == "Grade 12" && in_array($_SESSION['section'], ['CSS 12-1', 'CSS 12-2'])) {
                ?>

                <div class="welcoming">
                    <h2>Hello Welcome, <?= $_SESSION['grade_level'] ?> Student!</h2>
                </div>

                <div class="card-parent-box">
                    <div class="card-box">
                        <div class="data_text">
                            <span>Total Subjects</span>

                            <p>4</p>
                        </div>

                        <div class="data_icon">
                            <i class="fa fa-book-open"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Completed Lessons</span>

                            <p>8</p>
                        </div>

                        <div class="data_icon">
                            <i class="fa fa-award"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Study Time</span>

                            <p>15h</p>
                        </div>

                        <div class="data_icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="text-progress">
                    <h2>Subjects Progress</h2>
                </div>

                <div class="progress-parent-box">

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Introduction to Philosophy of Human Person</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Understanding Culture Society and Politics</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Computer System Servicing</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Physical Education</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Inquiries, Investigation and Immersion</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Entrepreneurship</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress-box">
                        <div class="progress-parent">
                            <div class="progress-title">
                                <div class="parent-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <span>Work Immersion</span>
                            </div>

                            <div class="progress-percent-parent">
                                <div class="progress-percent-title">
                                    <p>Subject Progress</p>

                                    <span>50%</span>
                                </div>
                                <div class="progress-percent">
                                    <div class="percent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            } else if ($_SESSION['grade_level'] == "Grade 11") {
                ?>

                    <div class="welcoming">
                        <h2>Hello Welcome, <?= $_SESSION['grade_level'] ?> Student!</h2>
                    </div>

                    <div class="card-parent-box">
                        <div class="card-box">
                            <div class="data_text">
                                <span>Total Subjects</span>

                                <p>4</p>
                            </div>

                            <div class="data_icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="data_text">
                                <span>Completed Lessons</span>

                                <p>8</p>
                            </div>

                            <div class="data_icon">
                                <i class="fa fa-award"></i>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="data_text">
                                <span>Study Time</span>

                                <p>15h</p>
                            </div>

                            <div class="data_icon">
                                <i class="fa fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="text-progress">
                        <h2>Subjects Progress</h2>
                    </div>

                    <div class="progress-parent-box">

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Introduction to Philosophy of Human Person</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Understanding Culture Society and Politics</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Computer System Servicing</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Physical Education</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Inquiries, Investigation and Immersion</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Entrepreneurship</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="progress-box">
                            <div class="progress-parent">
                                <div class="progress-title">
                                    <div class="parent-icon">
                                        <i class="fa fa-book-open"></i>
                                    </div>
                                    <span>Work Immersion</span>
                                </div>

                                <div class="progress-percent-parent">
                                    <div class="progress-percent-title">
                                        <p>Subject Progress</p>

                                        <span>50%</span>
                                    </div>
                                    <div class="progress-percent">
                                        <div class="percent"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
            }


            ?>

        </div>
    </div>


    <!-- bootstrap link javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <!-- <script>
        const welcoming = document.querySelector(".welcoming");

        setTimeout(() => {
            welcoming.style.display = "none";
        }, 2000);
    </script> -->
</body>

</html>