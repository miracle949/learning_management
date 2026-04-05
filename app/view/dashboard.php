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


        <?php include("../components/sidebar.php"); ?>

        <?php include("../components/navbar.php"); ?>

        <div class="rightbar">

            <?php if (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 12'): ?>

                <!-- <h4><?= htmlspecialchars($_SESSION["name"]) ?></h4> -->

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <span>—</span>
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                            <!-- <span>Here's what's happening with your learning today</span> -->
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="#">Browse Courses <i class="fa fa-arrow-right"></i></a>

                            <a href="#">View Progress</a>
                        </div>
                    </div>
                </div>

                <div class="card-parent-box">
                    <div class="card-box">
                        <div class="data_text">
                            <span>Enrolled Subjects</span>
                            <p>4</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-book-open"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Pending Task</span>
                            <p>8</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-award"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Study Time</span>
                            <p>15h</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Progress</span>
                            <p>15h</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="parent-performance">
                    <div class="update subject-performance">
                        <div class="header">
                            <h3>Upcoming Tasks</h3>
                            <a href="#">View all</a>
                        </div>
                        <div class="body">
                            <div class="update-box">
                                <p>Build a Personal Portfolio</p>
                                <span>Due: 3/15/2026</span>
                                <span>7 days left</span>
                            </div>

                            <div class="update-box">
                                <p>Build a Personal Portfolio</p>
                                <span>Due: 3/15/2026</span>
                                <span>7 days left</span>
                            </div>

                            <div class="update-box">
                                <p>Build a Personal Portfolio</p>
                                <span>Due: 3/15/2026</span>
                                <span>7 days left</span>
                            </div>
                        </div>
                    </div>
                    <div class="perform performance-trend">
                        <h2>Hello</h2>
                    </div>
                    <div class="perform enrolled-subjects">
                        <h2>Hello</h2>
                    </div>
                </div>

            <?php elseif (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 11'): ?>

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <span>—</span>
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                            <!-- <span>Here's what's happening with your learning today</span> -->
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="#">Browse Courses <i class="fa fa-arrow-right"></i></a>

                            <a href="#">View Progress</a>
                        </div>
                    </div>
                </div>

                <div class="card-parent-box">
                    <div class="card-box">
                        <div class="data_text">
                            <span>Enrolled Subjects</span>
                            <p>4</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-book-open"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Pending Task</span>
                            <p>8</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-award"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Study Time</span>
                            <p>15h</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Progress</span>
                            <p>15h</p>
                            <span>Active this semester</span>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="parent-performance">
                    <div class="update subject-performance">
                        <div class="header">
                            <h3>Upcoming Tasks</h3>
                            <a href="#">View all</a>
                        </div>
                        <div class="body">
                            <div class="update-box">
                                <p>Build a Personal Portfolio</p>
                                <span>Due: 3/15/2026</span>
                                <span>7 days left</span>
                            </div>

                            <div class="update-box">
                                <p>Build a Personal Portfolio</p>
                                <span>Due: 3/15/2026</span>
                                <span>7 days left</span>
                            </div>

                            <div class="update-box">
                                <p>Build a Personal Portfolio</p>
                                <span>Due: 3/15/2026</span>
                                <span>7 days left</span>
                            </div>
                        </div>
                    </div>
                    <div class="perform performance-trend">
                        <h2>Hello</h2>
                    </div>
                    <div class="perform enrolled-subjects">
                        <h2>Hello</h2>
                    </div>
                </div>

            <?php else: ?>

                <div class="welcoming">
                    <h2>No grade level or section assigned to your account. Please contact your administrator.</h2>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

</body>

</html>