<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <link rel="stylesheet" href="../css_folder/teacher.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <nav>
            <div class="nav-logo">
                <img src="" alt="">
                <h2>Teacher <b>Dashboard</b></h2>
            </div>
            <form action="?url=logout" method="post">
                <button><i class="fa fa-sign-out"></i> Logout</button>
            </form>
        </nav>

        <main>

            <div class="welcome-banner">
                <h2>Welcome, <b><?= htmlspecialchars($teacherInfo['name']) ?>!</b></h2>
            </div>

            <div class="parent-card">
                <div class="card-box">
                    <div class="card-text">
                        <span>Total Classes</span>
                        <p><?= (int) ($stats['total_classes'] ?? 0) ?></p>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Total Students</span>
                        <p><?= (int) ($stats['total_students'] ?? 0) ?></p>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Total Modules</span>
                        <p><?= (int) ($stats['total_modules'] ?? 0) ?></p>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-book-open"></i>
                    </div>
                </div>
            </div>

            <h2 class="mt-5">Your Classes</h2>

            <div class="parent-classes">
                <?php if (empty($classes)): ?>
                    <p>No classes assigned yet. Please contact your administrator.</p>
                <?php else: ?>
                    <?php foreach ($classes as $index => $class):
                        $sections = array_unique(explode(', ', $class['sections']));
                        $delay = $index * 0.1;
                        ?>
                        <div class="classes" style="animation-delay: <?= $delay ?>s">
                            <div class="class-accent"></div>

                            <div class="classes-name">
                                <h3><?= htmlspecialchars($class['subject_name']) ?></h3>
                                <p><?= htmlspecialchars($class['grade_name']) ?></p>
                            </div>

                            <div class="classes-section-grade">
                                <?php foreach ($sections as $section): ?>
                                    <div class="count section">
                                        <span><?= htmlspecialchars(trim($section)) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="classes-student-module">
                                <div class="students">
                                    <h4><?= (int) ($class['student_count'] ?? 30) ?></h4>
                                    <p>Students</p>
                                </div>
                                <div class="modules">
                                    <h4><?= (int) ($class['module_count'] ?? 5) ?></h4>
                                    <p>Modules</p>
                                </div>
                            </div>

                            <div class="view-classes">
                                <p>Created 1/5/2026</p>
                                <a
                                    href="?url=teacher_class&id=<?= (int) $class['subject_id'] ?>&grade_id=<?= (int) $class['grade_level_id'] ?>">
                                    View class <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </main>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>