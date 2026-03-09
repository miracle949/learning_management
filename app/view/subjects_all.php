<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="stylesheet" href="../css_folder/all_subjects.css">
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

        <?php include("../components/sidebar.php"); ?>

        <!-- <div class="sidebar-subjects">
            <h2>Learning Catalog</h2>

            <div class="subjects-catalog">
                <ul>
                    <li>
                        <span>Introduction to Philosophy of Human Person</span>
                    </li>
                    <li>
                        <span>Understanding Culture and Politics</span>
                    </li>
                    <li>
                        <span>Computer System Servicing</span>
                    </li>
                    <li>
                        <span>Physical Education</span>
                    </li>
                    <li>
                        <span>Inquiries, Investigation and Immersion</span>
                    </li>
                    <li>
                        <span>Entrepreneurship</span>
                    </li>
                    <li>
                        <span>Work Immersion</span>
                    </li>
                </ul>
            </div>
        </div> -->

        <div class="rightbar">
            <!-- <h2>Enrolled Subjects</h2> -->

            <?php

            if ($_SESSION['grade_level'] === "Grade 12") {
                ?>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->
                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Introduction to Philosophy of Human Person</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=philosophy">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->

                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Understanding Culture Society and Politics</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=ucsp">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->

                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Computer System Servicing</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=css">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->

                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Physical Education</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=pe">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->
                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Inquiries, Investigation and Immersion</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=3i">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->
                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Entrepreneurship</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=entrep">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-box-parent">
                    <div class="card-box">
                        <!-- <div class="card-box-name">
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
                        </div> -->
                        <div class="card-box-picture">

                        </div>
                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p>Work Immersion</p>
                                <span>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ducimus cum, iusto similique
                                    quos mollitia laboriosam!</span>
                            </div>

                            <div class="card-body-enrolled">
                                <a href="/learning_management/public/?url=subjects&subject=work_immersion">
                                    <span>Enroll Now</span>
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            } else if ($_SESSION['grade_level'] === "Grade 11") {
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
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>