<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="stylesheet" href="../css_folder/all_subjects.css">
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
            <h2>Your Subjects</h2>

            <?php

            if ($_SESSION['grade_level'] == "Grade 12") {
                ?>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Introduction to Philosophy of Human Person</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=philosophy"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Understanding Culture Society and Politics</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=ucsp"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Computer System Servicing</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=css"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Physical Education</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=pe"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Inquiries, Investigation and Immersion</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=3i"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Entrepreneurship</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=entrep"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <div class="card-box-name">
                            <div class="card-box-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="card-box-subject">
                                <p class="m-0">Work Immersion</p>
                                <span><?= $_SESSION['section'] ?></span>
                            </div>
                        </div>

                        <div class="card-box-link">
                            <a href="/learning_management/public/?url=subjects&subject=work_immersion"><i
                                    class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <?php
            } else if ($_SESSION['grade_level'] == "Grade 11") {
                ?>

                        <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Media Information Literatures</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=media_info_literature"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Physical Education</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=p_e"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Computer System Servicing</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=css_11"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Pagbasa at Pagsusuri</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=pagbasa_pagsusuri"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Reading and Writing</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=reading_writing"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Practical Research</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=practical_research"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Physical Science</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=physical_science"><i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-box-name">
                                <div class="card-box-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div class="card-box-subject">
                                    <p class="m-0">Statistics and Probability</p>
                                    <span><?= $_SESSION['section'] ?></span>
                                </div>
                            </div>

                            <div class="card-box-link">
                                <a href="/learning_management/public/?url=subjects&subject=statistics_probability"><i class="fa fa-arrow-right"></i></a>
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
</body>

</html>