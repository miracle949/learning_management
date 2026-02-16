<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <link rel="stylesheet" href="../css_folder/teacher.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <nav>
            <div class="nav-logo">
                <img src="" alt="">
                <h2>Teacher Dashboard</h2>
            </div>

            <form action="?url=logout" method="post">
                <button><i class="fa fa-sign-out"></i> Logout</button>
            </form>
        </nav>

        <main>
            <div class="parent-card">
                <div class="card-box">
                    <div class="card-text">
                        <span>Total Classes</span>

                        <p>4</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Total Students</span>

                        <p>60</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Total Modules</span>

                        <p>4</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-book-open"></i>
                    </div>
                </div>
            </div>

            <h2>Your Classes</h2>

            <div class="parent-classes">
                <a href="/learning_management/public/?url=records">
                    <div class="classes">
                        <div class="classes-header">
                            <h3>Introduction to Philosophy of Human Person</h3>
                            <p>Computer System Servicing</p>
                        </div>

                        <div class="classes-body">
                            <div class="grade-section">
                                <div class="grade">
                                    <span>Grade 12</span>
                                    <span>Grade 11</span>
                                </div>
                                <div class="section">
                                    <span>CSS 12-1</span>
                                    <span>CSS 11-1</span>
                                    <span>CSS 12-2</span>
                                </div>
                            </div>

                            <div class="parent-enrolled">
                                <span>Enrolled Students:</span>

                                <div class="parent-number">
                                    <div class="number-students">
                                        <p>30</p>
                                        <span>Students</span>
                                    </div>
                                    <div class="number-module">
                                        <p>5</p>
                                        <span>Modules</span>
                                    </div>
                                </div>
                            </div>

                            <div class="footer">
                                <span>Created 1/5/2026</span>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/learning_management/public/?url=records">
                    <div class="classes">
                        <div class="classes-header">
                            <h3>
                                Understanding Culture Society and Politics</h3>
                            <p>Computer System Servicing</p>
                        </div>

                        <div class="classes-body">
                            <div class="grade-section">
                                <div class="grade">
                                    <span>Grade 12</span>
                                    <span>Grade 11</span>
                                </div>
                                <div class="section">
                                    <span>CSS 12-1</span>
                                    <span>CSS 12-2</span>
                                    <span>CSS 11-2</span>
                                </div>
                            </div>

                            <div class="parent-enrolled">
                                <span>Enrolled Students:</span>

                                <div class="parent-number">
                                    <div class="number-students">
                                        <p>30</p>
                                        <span>Students</span>
                                    </div>
                                    <div class="number-module">
                                        <p>5</p>
                                        <span>Modules</span>
                                    </div>
                                </div>
                            </div>

                            <div class="footer">
                                <span>Created 1/5/2026</span>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/learning_management/public/?url=records">
                    <div class="classes">
                        <div class="classes-header">
                            <h3>
                                Physical Education</h3>
                            <p>Computer System Servicing</p>
                        </div>

                        <div class="classes-body">
                            <div class="grade-section">
                                <div class="grade">
                                    <span>Grade 12</span>
                                    <span>Grade 11</span>
                                </div>
                                <div class="section">
                                    <span>CSS 12-1</span>
                                    <span>CSS 12-2</span>
                                    <span>CSS 11-2</span>
                                </div>
                            </div>

                            <div class="parent-enrolled">
                                <span>Enrolled Students:</span>

                                <div class="parent-number">
                                    <div class="number-students">
                                        <p>30</p>
                                        <span>Students</span>
                                    </div>
                                    <div class="number-module">
                                        <p>5</p>
                                        <span>Modules</span>
                                    </div>
                                </div>
                            </div>

                            <div class="footer">
                                <span>Created 1/5/2026</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </main>
    </div>


    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>