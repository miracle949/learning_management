<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css_folder/style.css">
    <link rel="stylesheet" href="../css_folder/components.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>

        <?php include("../components/navbar.php"); ?>

        <div class="rightbar">

            <?php

            if ($_SESSION['grade_level'] == "Grade 12" && in_array($_SESSION['section'], ['CSS 12-1', 'CSS 12-2'])) {
                ?>

                <!-- <div class="welcoming">
                    <h2>Hello Welcome, <?= $_SESSION['grade_level'] ?> Student!</h2>
                </div> -->

                <div class="welcome-user">
                    <div class="welcome-icon">
                        <i class="fa fa-user-circle"></i>
                    </div>
                    <div class="welcome-text">
                        <h2>Welcome</h2>
                        <div class="d-flex gap-2">
                            <h4>
                                <?= $_SESSION['firstname'] ?>
                            </h4>
                            <h4>
                                <?= $_SESSION['lastname'] ?>!
                            </h4>
                        </div>
                    </div>
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
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <!-- <script>
        const welcoming = document.querySelector(".welcoming");

        setTimeout(() => {
            welcoming.style.display = "none";
        }, 2000);
    </script> -->
</body>

</html>