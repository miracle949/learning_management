<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../css_folder/admin.css">

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
                <h2>Admin Dashboard</h2>
            </div>

            <form action="?url=logout" method="post">
                <button><i class="fa fa-sign-out"></i> Logout</button>
            </form>
        </nav>

        <main>
            <div class="parent-card">
                <div class="card-box">
                    <div class="card-text">
                        <span>Total Students</span>

                        <p>4</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Total Teachers</span>

                        <p>4</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Active Students</span>

                        <p>4</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-circle"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Avg. Progress</span>

                        <p>81%</p>
                    </div>

                    <div class="card-icon">
                        <i class="fa fa-arrow-trend-up"></i>
                    </div>
                </div>
            </div>

            <div class="parent-recent-actions">
                <div class="recent-students">
                    <div class="recent-nav">
                        <h3>Recent Students</h3>

                        <a href="#">View all</a>
                    </div>

                    <hr>

                    <div class="parent-data">
                        <div class="data-card">
                            <div class="data-text">
                                <p>Sarah Williams</p>
                                <p>sarah.williams@gmail.com</p>

                                <div class="data-button">
                                    <span>Grade 11</span>

                                    <span>Section B</span>
                                </div>
                            </div>
                            <!-- <div class="data-progress">
                                <div class="progress-text">
                                    <p>Progress</p>

                                    <p>50%</p>
                                </div>
                                <div class="parent-progress">
                                    <div class="progress">

                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="data-card">
                            <div class="data-text">
                                <p>Sarah Williams</p>
                                <p>sarah.williams@gmail.com</p>

                                <div class="data-button">
                                    <span>Grade 11</span>

                                    <span>Section B</span>
                                </div>
                            </div>
                            <!-- <div class="data-progress">
                                <div class="progress-text">
                                    <p>Progress</p>

                                    <p>50%</p>
                                </div>
                                <div class="parent-progress">
                                    <div class="progress">

                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="data-card">
                            <div class="data-text">
                                <p>Sarah Williams</p>
                                <p>sarah.williams@gmail.com</p>

                                <div class="data-button">
                                    <span>Grade 11</span>

                                    <span>Section B</span>
                                </div>
                            </div>
                            <!-- <div class="data-progress">
                                <div class="progress-text">
                                    <p>Progress</p>

                                    <p>50%</p>
                                </div>
                                <div class="parent-progress">
                                    <div class="progress">

                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="data-card">
                            <div class="data-text">
                                <p>Sarah Williams</p>
                                <p>sarah.williams@gmail.com</p>

                                <div class="data-button">
                                    <span>Grade 11</span>

                                    <span>Section B</span>
                                </div>
                            </div>
                            <!-- <div class="data-progress">
                                <div class="progress-text">
                                    <p>Progress</p>

                                    <p>50%</p>
                                </div>
                                <div class="parent-progress">
                                    <div class="progress">

                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="data-card">
                            <div class="data-text">
                                <p>Sarah Williams</p>
                                <p>sarah.williams@gmail.com</p>

                                <div class="data-button">
                                    <span>Grade 11</span>

                                    <span>Section B</span>
                                </div>
                            </div>
                            <!-- <div class="data-progress">
                                <div class="progress-text">
                                    <p>Progress</p>

                                    <p>50%</p>
                                </div>
                                <div class="parent-progress">
                                    <div class="progress">

                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="actions-card">
                    <div class="actions-nav">
                        <h3>Quick Actions</h3>
                    </div>

                    <hr>

                    <div class="parent-manage">
                        <a href="/learning_management/public/?url=student_records">
                            <div class="manage-card">
                                <i class="fa fa-users"></i>

                                <div class="manage-text">
                                    <p>Manage Students</p>
                                    <span>View Progress & Details</span>
                                </div>
                            </div>
                        </a>

                        <a href="/learning_management/public/?url=teacher_records">
                            <div class="manage-card">
                                <i class="fa fa-graduation-cap"></i>

                                <div class="manage-text">
                                    <p>Manage Teachers</p>
                                    <span>Create & Assign Sections</span>
                                </div>
                            </div>
                        </a>

                        <a href="#">
                            <div class="manage-card">
                                <i class="fa fa-chart-line"></i>

                                <div class="manage-text">
                                    <p>View Reports</p>
                                    <span>Analytics & Insights</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </main>

    </div>


    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>