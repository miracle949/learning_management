<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css_folder/super_admin.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">


        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <?php include("../super_admin_folder/nav.php") ?>

            <main>
                <h2>Super Admin Dashboard</h2>
                <p>Full platform overview - all system operational</p>

                <div class="card-parent-box">
                    <div class="card">
                        <p>Total Students</p>
                        <h4>248</h4>
                        <span><i class="fa fa-arrow-up"></i> 12 this week</span>
                    </div>

                    <div class="card">
                        <p>Total Teachers</p>
                        <h4>18</h4>
                        <span><i class="fa fa-arrow-up"></i> 2 new</span>
                    </div>

                    <div class="card">
                        <p>Total Subjects</p>
                        <h4>12</h4>
                        <span><i class="fa fa-arrow-up"></i> 3 in review</span>
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
                            <?php if (!empty($recentStudents)): ?>
                                <?php foreach ($recentStudents as $student): ?>
                                    <div class="data-card">
                                        <div class="data-text">
                                            <p>
                                                <?= htmlspecialchars($student['name']) ?>
                                            </p>
                                            <p>
                                                <?= htmlspecialchars($student['email']) ?>
                                            </p>

                                            <div class="data-button">
                                                <span>
                                                    <?= htmlspecialchars($student['grade_level_name']) ?>
                                                </span>
                                                <span>
                                                    <?= htmlspecialchars($student['section_name']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="padding: 1rem; color: gray;">No students found.</p>
                            <?php endif; ?>
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
    </div>


    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

</body>

</html>