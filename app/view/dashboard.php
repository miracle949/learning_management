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

        <?php
        // Fallback defaults in case controller didn't pass these
        $enrolledCount = $enrolledCount ?? 0;
        $pendingCount = $pendingCount ?? 0;
        $pendingAssignments = $pendingAssignments ?? [];
        $announcements = $announcements ?? [];
        ?>

        <div class="rightbar">

            <?php if (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 12'): ?>

                <!-- <h4><?= htmlspecialchars($_SESSION["name"]) ?></h4> -->

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <!-- <span>—</span> -->
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                            <!-- <span>Here's what's happening with your learning today</span> -->
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="/learning_management/public/?url=classes">Browse Classes <i
                                    class="fa fa-arrow-right"></i></a>

                        </div>
                    </div>
                </div>

                <div class="card-parent-box">
                    <div class="card-box">
                        <a href="/learning_management/public/?url=classes">
                            <div class="data_text">
                                <span>Enrolled Classes</span>
                                <p>
                                    <?= $enrolledCount ?>
                                </p>
                            </div>
                            <div class="data_icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="/learning_management/public/?url=assignments">
                            <div class="data_text">
                                <span>Pending Tasks</span>
                                <p>
                                    <?= $pendingCount ?>
                                </p>
                            </div>
                            <div class="data_icon">
                                <i class="fa fa-clock"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Completed Task</span>
                            <p><?= $completedCount ?></p>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="parent-performance">
                    <div class="update subject-performance">
                        <div class="header">
                            <i class="fa fa-clock"></i>
                            <h3>Pending Tasks</h3>
                            <!-- <a href="#">View all</a> -->
                        </div>
                        <div class="body">
                            <?php if (!empty($pendingAssignments)): ?>
                                <?php foreach ($pendingAssignments as $item):
                                    $daysLeft = '';
                                    if (!empty($item['due_date'])) {
                                        $diff = (int) ceil((strtotime($item['due_date']) - time()) / 86400);
                                        if ($diff < 0)
                                            continue; // skip overdue
                                        $daysLeft = $diff > 0 ? $diff . ' days left' : 'Due today';
                                    }
                                    ?>
                                    <div class="update-box">
                                        <p><?= htmlspecialchars($item['task']) ?></p>
                                        <span>Due date:
                                            <?= !empty($item['due_date']) ? date('F j, Y', strtotime($item['due_date'])) : 'No due date' ?></span>
                                        <?php if ($daysLeft): ?><span><?= htmlspecialchars($daysLeft) ?></span><?php endif; ?>
                                        <a
                                            href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                            View Task <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted m-0" style="font-size: 14.5px;">No pending tasks.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="perform performance-trend">
                        <div class="header">
                            <i class="fa fa-question-circle"></i>
                            <h3>Announcements</h3>
                        </div>
                        <div class="body">
                            <?php if (!empty($announcements)): ?>
                                <?php foreach ($announcements as $ann): ?>
                                    <div class="progress-box">
                                        <h4><?= htmlspecialchars($ann['subject_name']) ?></h4>
                                        <p style="font-size:14.5px;font-weight:500;color:#111827;margin:0;">Title:
                                            <?= htmlspecialchars($ann['title']) ?>
                                        </p>
                                        <p style="font-size:14.5px;color:#212529;margin:0;">
                                            <?= nl2br(htmlspecialchars($ann['message'])) ?>
                                        </p>
                                        <div class="view" style="margin-top:8px;">
                                            <small style="color:#9ca3af;">
                                                <?= date('F j, Y', strtotime($ann['created_at'])) ?>
                                                · <?= htmlspecialchars($ann['teacher_name']) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted m-0" style="font-size:14.5px;">No announcements yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="perform enrolled-subjects">
                    <div class="header">
                        <i class="fa fa-book"></i>
                        <h3>My Modules</h3>
                    </div>

                    <div class="body">
                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="#">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 11'): ?>

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <!-- <span>—</span> -->
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                            <!-- <span>Here's what's happening with your learning today</span> -->
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="/learning_management/public/?url=classes">Browse Classes <i
                                    class="fa fa-arrow-right"></i></a>

                        </div>
                    </div>
                </div>

                <div class="card-parent-box">
                    <div class="card-box">
                        <a href="/learning_management/public/?url=classes">
                            <div class="data_text">
                                <span>Enrolled Classes</span>
                                <p>
                                    <?= $enrolledCount ?>
                                </p>
                            </div>
                            <div class="data_icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="/learning_management/public/?url=assignments">
                            <div class="data_text">
                                <span>Pending Tasks</span>
                                <p>
                                    <?= $pendingCount ?>
                                </p>
                            </div>
                            <div class="data_icon">
                                <i class="fa fa-clock"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Completed Task</span>
                            <p><?= $completedCount ?></p>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>

                    <!-- <div class="card-box">
                        <div class="data_text">
                            <span>Progress</span>
                            <p>Static</p>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-chart-line"></i>
                        </div>
                    </div> -->
                </div>

                <div class="parent-performance">
                    <div class="update subject-performance">
                        <div class="header">
                            <i class="fa fa-clock"></i>
                            <h3>Pending Tasks</h3>
                            <!-- <a href="#">View all</a> -->
                        </div>
                        <div class="body">
                            <?php if (!empty($pendingAssignments)): ?>
                                <?php foreach ($pendingAssignments as $item):
                                    $daysLeft = '';
                                    if (!empty($item['due_date'])) {
                                        $diff = (int) ceil((strtotime($item['due_date']) - time()) / 86400);
                                        if ($diff < 0)
                                            continue; // skip overdue
                                        $daysLeft = $diff > 0 ? $diff . ' days left' : 'Due today';
                                    }
                                    ?>
                                    <div class="update-box">
                                        <p><?= htmlspecialchars($item['task']) ?></p>
                                        <span>Due date:
                                            <?= !empty($item['due_date']) ? date('F j, Y', strtotime($item['due_date'])) : 'No due date' ?></span>
                                        <?php if ($daysLeft): ?><span><?= htmlspecialchars($daysLeft) ?></span><?php endif; ?>
                                        <a
                                            href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                            View Task <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted m-0" style="font-size: 14.5px;">No pending tasks.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="perform performance-trend">
                        <div class="header">
                            <i class="fa fa-question-circle"></i>
                            <h3>Announcements</h3>
                        </div>
                        <div class="body">
                            <?php if (!empty($announcements)): ?>
                                <?php foreach ($announcements as $ann): ?>
                                    <div class="progress-box">
                                        <h4><?= htmlspecialchars($ann['subject_name']) ?></h4>
                                        <p style="font-size:14.5px;font-weight:500;color:#111827;margin:0;">Title:
                                            <?= htmlspecialchars($ann['title']) ?>
                                        </p>
                                        <p style="font-size:14.5px;color:#212529;margin:0;">
                                            <?= nl2br(htmlspecialchars($ann['message'])) ?>
                                        </p>
                                        <div class="view" style="margin-top:8px;">
                                            <small style="color:#9ca3af;">
                                                <?= date('F j, Y', strtotime($ann['created_at'])) ?>
                                                · <?= htmlspecialchars($ann['teacher_name']) ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted m-0" style="font-size:14.5px;">No announcements yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="perform enrolled-subjects">
                    <div class="header">
                        <i class="fa fa-book"></i>
                        <h3>My Modules</h3>
                    </div>

                    <div class="body">
                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>
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