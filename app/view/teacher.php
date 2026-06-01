<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <link rel="stylesheet" href="../css_folder/teacher.css">
    <!-- <link rel="stylesheet" href="../css_folder/components.css"> -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../teacher_folder/sidebar.php") ?>

        <div class="rightbar">
            <!-- <nav>
                <div class="nav-logo">
                    <h2>Teacher <b>Dashboard</b></h2>
                </div>
                <form action="?url=logout" method="post">
                    <button><i class="fa fa-sign-out"></i> Logout</button>
                </form>
            </nav> -->


            <!-- <div class="classes-section-grade">
                                    <?php foreach ($sections as $section): ?>
                                        <div class="count section">
                                            <span><?= htmlspecialchars(trim($section)) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div> -->

            <!-- <div class="view-classes">
                                        <p>Created 1/5/2026</p>
                                        <a
                                            href="?url=teacher_class&id=<?= (int) $class['subject_id'] ?>&grade_id=<?= (int) $class['grade_level_id'] ?>">
                                            View class <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div> -->

            <?php include("../teacher_folder/nav.php"); ?>

            <main>

                <div class="welcome-banner">
                    <h2>Welcome, <?= htmlspecialchars($teacherInfo['name']) ?>! 👋</h2>
                    <p>Good Day Teacher!</p>
                </div>

                <div class="parent-card">

                    <!-- TOTAL CLASSES -->
                    <div class="card-box">
                        <a href="/learning_management/public/?url=classes_teacher">
                            <div class="card-text">
                                <span>Total Classes</span>
                                <p>
                                    <?= (int) ($stats['total_classes'] ?? 0) ?>
                                </p>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                        </a>
                    </div>

                    <!-- TOTAL STUDENTS -->
                    <div class="card-box">
                        <div class="card-text">
                            <span>Total Students</span>
                            <p><?= (int)($totalStudents ?? 0) ?></p>
                        </div>
                        <div class="card-icon">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>

                    <!-- SUBMITTED ASSIGNMENTS -->
                    <div class="card-box">
                        <div class="card-text">
                            <span>Submitted Assignments</span>
                            <p><?= (int) ($submittedCount ?? 0) ?></p>
                        </div>
                        <div class="card-icon">
                            <i class="fa fa-book-open"></i>
                        </div>
                    </div>

                </div>


                <div class="parent-box-classes">
                    <div class="sub-student">
                        <div class="student-assignment">
                            <div class="header">
                                <i class="fa fa-hourglass-half"></i>
                                <p>Upcoming Deadlines</p>
                            </div>
                            <div class="student-names">
                                <?php if (empty($upcomingAssignments)): ?>
                                    <p style="font-size:13px;color:#9ca3af;text-align:center;padding:1rem 0;">
                                        No upcoming assignments.
                                    </p>
                                <?php else: ?>
                                    <?php foreach ($upcomingAssignments as $asgn): ?>
                                        <div class="student-box">
                                            <p>
                                                <?= htmlspecialchars($asgn['subject_name']) ?> -
                                                (<?= htmlspecialchars($asgn['type'] ?? 'Assignment') ?>)
                                            </p>
                                            <p><?= htmlspecialchars($asgn['title']) ?></p>
                                            <div class="grade-section">
                                                <!-- Show actual section name from teacher_assignments join -->
                                                <span><?= htmlspecialchars($asgn['section_name'] ?? '') ?></span>

                                                <?php if (!empty($asgn['due_date'])): ?>
                                                    <?php
                                                    $dueDateFormatted = date('M d, Y', strtotime($asgn['due_date']));
                                                    $dueTimeRaw = !empty($asgn['due_time']) ? $asgn['due_time'] : '23:59:00';
                                                    $dueTimeFormatted = date('h:i A', strtotime($dueTimeRaw));
                                                    ?>
                                                    <span style="color:#ef4444;font-weight:600;">
                                                        Due: <?= $dueDateFormatted ?> at <?= $dueTimeFormatted ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span style="color:#9ca3af;">No due date</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="sub-assignment">
                            <div class="header">
                                <i class="fa fa-question-circle"></i>
                                <p>Announcements</p>
                            </div>
                            <div class="assignment-complete">
                                <?php if (empty($teacherAnnouncements)): ?>
                                    <p style="font-size:13px;color:#9ca3af;text-align:center;padding:1rem 0;">
                                        No announcements yet.
                                    </p>
                                <?php else: ?>
                                    <?php foreach ($teacherAnnouncements as $ann): ?>
                                        <div class="assignment-box">
                                            <!-- <p>
                                                <?= htmlspecialchars($ann['teacher_name']) ?>
                                            </p> -->
                                            <p><?= htmlspecialchars($ann['subject_name']) ?>
                                            </p>
                                            <p>Title: <?= htmlspecialchars($ann['title']) ?></p>

                                            <p><?= nl2br(htmlspecialchars($ann['message'])) ?></p>

                                            <div class="announcement-footer">
                                                <p>
                                                    <?= htmlspecialchars($ann['section_name']) ?>
                                                </p>
                                                <span>Date Posted:
                                                    <?= date('F j, Y', strtotime($ann['created_at'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="classes-assignment">
                        <div class="sub-classes">
                            <h5>Your Classes</h5>
                            <div class="parent-classes">
                                <?php if (empty($classes)): ?>
                                    <p>No classes assigned yet. Please contact your administrator.</p>
                                <?php else: ?>

                                    <?php foreach ($classes as $index => $class):
                                        $delay = $index * 0.1;
                                        ?>

                                        <div class="classes" style="animation-delay: <?= $delay ?>">
                                            <div class="class-accent"></div>

                                            <div class="classes-name">
                                                <h3><?= htmlspecialchars($class['subject_name']) ?></h3>
                                                <p>
                                                    <!-- <?= htmlspecialchars($class['grade_name'] ?? '') ?>
                                                    <?php if (!empty($class['section'])): ?>
                                                        · <?= htmlspecialchars($class['section']) ?>
                                                    <?php endif; ?> -->

                                                    <!-- <?= htmlspecialchars($class['grade_name'] ?? '') ?> -->
                                                    <?php if (!empty($class['section'])): ?>
                                                        <?= htmlspecialchars($class['section']) ?>
                                                    <?php endif; ?>
                                                </p>
                                            </div>

                                            <div class="classes-student-module">
                                                <div class="students">
                                                    <h4><?= (int) ($class['student_count'] ?? 0) ?></h4>
                                                    <p>Students</p>
                                                </div>
                                                <div class="modules">
                                                    <h4><?= (int) ($class['module_count'] ?? 0) ?></h4>
                                                    <p>Modules</p>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endforeach; ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>