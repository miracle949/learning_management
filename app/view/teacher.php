<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <link rel="stylesheet" href="../css_folder/teacher.css">
    <link rel="stylesheet" href="../css_folder/components.css">
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

            <main>

                <div class="welcome-nav">
                    <div class="welcome-nav-text">
                        <h2>Teacher Dashboard</h2>
                        <p>Wednesday, June 18, 2026 · CSS Batch 2026</p>
                    </div>
                    <div class="welcome-nav-acc">

                    </div>
                </div>

                <div class="welcome-banner">
                    <div class="welcome-banner-text">
                        <h2>Hello, Welcome Teacher
                            <?= htmlspecialchars($teacherInfo['name']) ?> - Good Day!👋
                        </h2>
                        <p>Your guidance today can make a difference in every student's learning journey.</p>
                    </div>
                </div>

                <div class="parent-card">

                    <!-- TOTAL STUDENTS -->
                    <div class="card-box">
                        <a href="/learning_management/public/?url=classes_teacher">
                            <div class="card-text">
                                <span>Total Students</span>
                                <p>
                                    <?= (int) ($stats['total_classes'] ?? 0) ?>
                                </p>
                                <div class="stat-data">
                                    Across all classes
                                </div>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                        </a>
                    </div>

                    <!-- Pending Submission -->
                    <div class="card-box">
                        <a href="#">
                            <div class="card-text">
                                <span>Pending Submission</span>
                                <p>
                                    <?= (int) ($totalStudents ?? 0) ?>
                                </p>
                                <div class="stat-data">
                                    Awaiting your review
                                </div>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-users"></i>
                            </div>
                        </a>
                    </div>

                    <!-- UPCOMING ACTIVITIES -->
                    <div class="card-box">
                        <a href="#">
                            <div class="card-text">
                                <span>Upcoming Dues</span>
                                <p>
                                    <?= (int) ($submittedCount ?? 0) ?>
                                </p>
                                <div class="stat-data">
                                    Due next 7 days
                                </div>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                        </a>
                    </div>

                    <!-- OVER-ALL CLASS AVERAGE -->
                    <div class="card-box">
                        <a href="/learning_management/public/?url=classes_teacher">
                            <div class="card-text">
                                <span>Overall Class Average</span>
                                <p>
                                    <?= (int) ($stats['total_classes'] ?? 0) ?>
                                </p>
                                <div class="stat-data">
                                    Across all classes
                                </div>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                        </a>
                    </div>

                </div>


                <div class="dashboard-grid">

                    <!-- STUDENT PROGRESS OVERVIEW -->
                    <div class="dashboard-card">
                        <div class="dc-header">
                            <div class="dc-text">
                                <h3>Student Progress Overview</h3>
                                <p>Track your students' learning progress</p>
                            </div>
                            <select class="class-filter">
                                <option>All Classes</option>
                            </select>
                        </div>
                        <div class="progress-chart-body">
                            <?php
                            $trend = $progressTrend ?? [];
                            $count = count($trend);
                            ?>
                            <div class="progress-chart-svg-wrap">
                                <?php if ($count < 2): ?>
                                    <p class="dc-empty">Not enough graded data yet.</p>
                                <?php else: ?>
                                    <?php
                                    $w = 400;
                                    $h = 160;
                                    $padX = 20;
                                    $padTop = 15;
                                    $padBottom = 15;
                                    $chartH = $h - $padTop - $padBottom;
                                    $step = ($w - $padX * 2) / ($count - 1);

                                    $points = [];
                                    foreach ($trend as $i => $pt) {
                                        $x = $padX + $i * $step;
                                        $val = max(0, min(100, (float) $pt['avg_percentage']));
                                        $y = $padTop + $chartH - ($val / 100 * $chartH);
                                        $points[] = ['x' => $x, 'y' => $y];
                                    }
                                    $linePoints = implode(' ', array_map(fn($p) => round($p['x'], 1) . ',' . round($p['y'], 1), $points));
                                    $areaPoints = $linePoints
                                        . ' ' . round($points[$count - 1]['x'], 1) . ',' . ($padTop + $chartH)
                                        . ' ' . round($points[0]['x'], 1) . ',' . ($padTop + $chartH);
                                    ?>
                                    <svg viewBox="0 0 <?= $w ?> <?= $h ?>" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="progressFill" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25" />
                                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0" />
                                            </linearGradient>
                                        </defs>
                                        <!-- gridlines -->
                                        <?php foreach ([0, 25, 50, 75, 100] as $g):
                                            $gy = $padTop + $chartH - ($g / 100 * $chartH); ?>
                                            <line x1="<?= $padX ?>" y1="<?= $gy ?>" x2="<?= $w - $padX ?>" y2="<?= $gy ?>"
                                                stroke="#f1f3f5" stroke-width="1" />
                                        <?php endforeach; ?>
                                        <polygon points="<?= $areaPoints ?>" fill="url(#progressFill)" />
                                        <polyline points="<?= $linePoints ?>" fill="none" stroke="#3b82f6"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <?php foreach ($points as $p): ?>
                                            <circle cx="<?= $p['x'] ?>" cy="<?= $p['y'] ?>" r="3.5" fill="#ffffff"
                                                stroke="#3b82f6" stroke-width="2" />
                                        <?php endforeach; ?>
                                    </svg>
                                    <div class="progress-chart-labels">
                                        <?php foreach ($trend as $pt): ?>
                                            <span><?= date('M d', strtotime($pt['week_start'])) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="progress-stats">
                                <div class="progress-stat-item">
                                    <span class="stat-label">Average Progress</span>
                                    <span class="stat-value avg"><?= (int) ($progressStats['avg_pct'] ?? 0) ?>%</span>
                                </div>
                                <div class="progress-stat-item">
                                    <span class="stat-label">Highest Progress</span>
                                    <span class="stat-value high"><?= (int) ($progressStats['max_pct'] ?? 0) ?>%</span>
                                </div>
                                <div class="progress-stat-item">
                                    <span class="stat-label">Lowest Progress</span>
                                    <span class="stat-value low"><?= (int) ($progressStats['min_pct'] ?? 0) ?>%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RECENT SUBMISSIONS -->
                    <div class="dashboard-card">
                        <div class="dc-header">
                            <div class="dc-text">
                                <h3>Recent Submissions</h3>
                                <p>Review the latest student submissions</p>
                            </div>
                            <a class="view-all" href="/learning_management/public/?url=works">View All</a>
                        </div>
                        <div class="dc-body">
                            <?php if (empty($submittedAssignments)): ?>
                                <p class="dc-empty">No recent submissions.</p>
                            <?php else: ?>
                                <?php
                                $typeIcon = ['seatwork' => ['fa-file-alt', 'blue'], 'activity' => ['fa-tasks', 'orange'], 'quiz' => ['fa-circle-question', 'purple'], 'project' => ['fa-diagram-project', 'pink']];
                                ?>
                                <?php foreach (array_slice($submittedAssignments, 0, 5) as $sub): ?>
                                    <?php [$icon, $color] = $typeIcon[strtolower($sub['type'] ?? '')] ?? ['fa-file-alt', 'blue']; ?>
                                    <div class="dc-list-item">
                                        <div class="dc-list-icon <?= $color ?>"><i class="fa <?= $icon ?>"></i></div>
                                        <div class="dc-list-body">
                                            <div class="dc-list-title">
                                                <p><?= htmlspecialchars($sub['assignment_title']) ?></p>
                                                <span class="status-badge submitted">Submitted</span>
                                            </div>
                                            <p class="dc-list-sub">
                                                <?= htmlspecialchars($sub['task']) ?>
                                            </p>
                                            <div class="dc-list-meta">
                                                <span><?= htmlspecialchars($sub['section_name']) ?> ·
                                                    <?= htmlspecialchars($sub['student_name']) ?></span>
                                                <span><?= date('M d, Y g:i A', strtotime($sub['submitted_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- RECENT LEARNING MATERIALS -->
                    <div class="dashboard-card">
                        <div class="dc-header">
                            <div class="dc-text">
                                <h3>Recent Learning Materials</h3>
                                <p>View your recently added learning materials</p>
                            </div>
                            <a class="view-all" href="/learning_management/public/?url=modules">View All</a>
                        </div>
                        <div class="dc-body">
                            <?php if (empty($recentMaterials)): ?>
                                <p class="dc-empty">No learning materials yet.</p>
                            <?php else: ?>
                                <?php
                                $materialIcon = [
                                    'pdf' => ['fa-file-pdf', 'blue'],
                                    'video' => ['fa-video', 'pink'],
                                    'image' => ['fa-image', 'orange'],
                                    'module' => ['fa-file-alt', 'green'],
                                ];
                                ?>
                                <?php foreach ($recentMaterials as $mat): ?>
                                    <?php
                                    $ft = strtolower($mat['file_type'] ?? '');
                                    if (str_contains($ft, 'pdf')) {
                                        $matType = 'PDF Material';
                                        [$icon, $color] = $materialIcon['pdf'];
                                    } elseif (str_contains($ft, 'video') || in_array($ft, ['mp4', 'mov', 'avi'])) {
                                        $matType = 'Video';
                                        [$icon, $color] = $materialIcon['video'];
                                    } elseif (in_array($ft, ['jpg', 'jpeg', 'png', 'gif'])) {
                                        $matType = 'Image';
                                        [$icon, $color] = $materialIcon['image'];
                                    } else {
                                        $matType = 'Module';
                                        [$icon, $color] = $materialIcon['module'];
                                    }
                                    ?>
                                    <div class="dc-list-item">
                                        <div class="dc-list-icon <?= $color ?>"><i class="fa <?= $icon ?>"></i></div>
                                        <div class="dc-list-body">
                                            <div class="dc-list-title">
                                                <p><?= htmlspecialchars($mat['title']) ?></p>
                                            </div>
                                            <p class="dc-list-sub">
                                                <?= htmlspecialchars($matType) ?> ·
                                                <?= htmlspecialchars(($mat['grade_level'] ?? '') . ' - ' . ($mat['section_name'] ?? '')) ?>
                                            </p>
                                            <div class="dc-list-meta">
                                                <span></span>
                                                <span>Uploaded on <?= date('M d, Y', strtotime($mat['posted_at'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ANNOUNCEMENTS -->
                    <div class="dashboard-card">
                        <div class="dc-header">
                            <div class="dc-text">
                                <h3>Announcements</h3>
                                <p>Share and manage important class updates</p>
                            </div>
                            <a class="view-all" href="/learning_management/public/?url=classes_teacher">View All</a>
                        </div>
                        <div class="dc-body">
                            <?php if (empty($teacherAnnouncements)): ?>
                                <p class="dc-empty">No announcements yet.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($teacherAnnouncements, 0, 5) as $i => $ann): ?>
                                    <div class="dc-list-item">
                                        <div class="dc-list-icon <?= $i % 2 === 0 ? 'blue' : 'orange' ?>"><i
                                                class="fa fa-bullhorn"></i></div>
                                        <div class="dc-list-body">
                                            <p class="dc-list-title">
                                                <?= htmlspecialchars($ann['title']) ?>
                                            </p>
                                            <p class="dc-list-message">
                                                <?= nl2br(htmlspecialchars($ann['message'])) ?>
                                            </p>
                                            <div class="dc-list-meta">
                                                <span>
                                                    <?= htmlspecialchars($ann['subject_name'] . ' · ' . $ann['section_name']) ?>
                                                </span>
                                                <span>
                                                    <?= date('M d, Y', strtotime($ann['created_at'])) ?>
                                                </span>
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