<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css_folder/admin.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        .dash-header {
            margin-bottom: 24px;
        }

        .dash-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 5px;
        }

        .dash-header p {
            font-size: 14.5px;
            /* color: #6b7280; */
            color: var(--text-dim);
            margin: 0;
        }

        .body {
            /* margin-top: 0.5rem; */
            /* padding: 18px 20px; */
            /* padding: 0 20px; */
            padding: 0 20px;
            height: 354px;
            overflow-y: auto;
        }

        .body.announcements {
            padding: 0 20px 14px;
        }

        .body.enrollment {
            padding: 14px 20px;
        }

        .body.breakdown {
            padding: 14px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .header a {
            font-size: 13px;
            font-weight: 600;
            color: var(--neon-cyan);
            text-decoration: none;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            /* margin-bottom: 20px; */
            margin-top: 1.5rem;
        }

        .chart-line {
            display: grid;
            grid-template-columns: 1fr;
        }

        .charts-row-workload {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            margin-top: 1.5rem;
        }

        /* Row 3: stretch both cards to the same height */
        .charts-row.stretch-row {
            align-items: stretch;
        }

        .charts-row.stretch-row>.chart-card {
            display: flex;
            flex-direction: column;
        }

        .chart-card {
            border-radius: 20px;
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04); */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            /* border: 1px solid #e2e8f0; */
            border: 1px solid var(--border);
            background-color: #ffffff;
            /* padding: 20px 22px; */
            width: 100%;
            max-height: 100%;
        }

        .chart-card .header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chart-card .header .header-left {
            width: 270px;
        }

        .chart-card-title {
            font-size: 15.5px;
            font-weight: 600;
            /* color: #6b7280; */
            color: #1a1a1a;
            /* text-transform: uppercase; */
            /* letter-spacing: .06em; */
            margin: 0 0 0px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-card-desc {
            font-size: 13.5px;
            color: var(--text-dim);
            margin: 5px 0px 0px;
        }

        .chart-card-title i {
            color: #00C950;
            font-size: 14px;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 20px;
            font-size: 12px;
            color: #6b7280;
        }

        .chart-legend span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .workload-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 0;
            /* border-bottom: 1px solid #f1f5f9; */
            border-bottom: 1px solid var(--border);
            /* margin-top: 1rem; */
        }

        .workload-item:last-child {
            border-bottom: none;
        }

        .w-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background-color: var(--neon-cyan);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .w-name {
            flex: 1;
            font-size: 14px;
            /* color: #1a1a2e; */
            color: #1a1a1a;
            font-weight: 600;
        }

        .w-badge {
            font-size: 12px;
            /* background: #e8f5e9; */
            background: rgba(0, 119, 204, 0.08);
            /* color: #00a040; */
            color: var(--neon-cyan);
            border-radius: 20px;
            padding: 3px 10px;
            font-weight: 600;
        }

        .enroll-item {
            display: flex;
            align-items: center;
            gap: 12px;
            /* padding: 8px 0; */
            padding: 14px 0;
            /* border-bottom: 1px solid #f1f5f9; */
            border-bottom: 1px solid var(--border);
        }

        .enroll-item:last-child {
            border-bottom: none;
        }

        .e-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            /* background: #00C950; */
            background-color: var(--neon-cyan);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .e-info {
            flex: 1;
            min-width: 0;
        }

        .e-info .e-name {
            font-size: 14px;
            font-weight: 600;
            /* color: #1a1a2e; */
            color: #1a1a1a;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .e-info .e-subject {
            font-size: 12px;
            color: #6b7280;
            margin: 1px 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .e-time {
            font-size: 12px;
            color: #9ca3af;
            flex-shrink: 0;
        }

        /* Pending items — taller row padding for a bigger card feel */
        .pending-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 0;
            /* border-bottom: 1px solid #f1f5f9; */
            border-bottom: 1px solid var(--border);
        }

        .pending-item:last-child {
            border-bottom: none;
        }

        .p-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fff3e0;
            /* background-color: var(--neon-cyan); */
            color: #e65100;
            /* color: #ffffff; */
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .p-info {
            flex: 1;
        }

        .p-info .p-name {
            font-size: 14px;
            font-weight: 600;
            /* color: #1a1a2e; */
            color: #1a1a1a;
            margin: 0;
        }

        .p-info .p-section {
            font-size: 12px;
            color: #6b7280;
            margin: 3px 0 0;
        }

        .p-badge {
            font-size: 11px;
            background: #fff3e0;
            color: #e65100;
            border-radius: 20px;
            padding: 4px 12px;
            font-weight: 600;
        }

        /* Pending card: always at least as tall as the enrollments card */
        .pending-card {
            display: flex;
            flex-direction: column;
            /* min-height: 480px; */
            min-height: 350px;
            /* ← increase this value if you want it even taller */
        }

        .pending-card .pending-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 0 20px;
        }

        /* When empty, center the icon vertically */
        .pending-card .pending-empty {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 13px;
            gap: 10px;
        }

        .ann-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 14px 16px;
            /* margin-bottom: 10px; */
            margin: 14px 0;
            /* border-left: 3px solid #00C950; */
            border-left: 3px solid var(--neon-cyan);
        }

        .ann-item:last-child {
            margin-bottom: 0;
        }

        .ann-subject {
            font-size: 14px;
            font-weight: 700;
            /* color: #1a1a2e; */
            color: #1a1a1a;
            margin: 0 0 5px;
        }

        .ann-title {
            font-size: 12.5px;
            color: #374151;
            margin: 0 0 3px;
        }

        .ann-body {
            font-size: 12px;
            color: #6b7280;
            margin: 0 0 6px;
        }

        .ann-meta {
            font-size: 11px;
            color: #9ca3af;
        }

        .section-bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .section-bar-label {
            font-size: 13.5px;
            color: #6b7280;
            /* width: 65px; */
            flex-shrink: 0;
            text-align: right;
        }

        .section-bar-track {
            flex: 1;
            height: 10px;
            background: #f1f5f9;
            border-radius: 5px;
            overflow: hidden;
        }

        .section-bar-fill {
            height: 100%;
            border-radius: 5px;
            transition: width .5s ease;
        }

        .section-bar-val {
            font-size: 12px;
            color: #374151;
            font-weight: 600;
            width: 24px;
            text-align: right;
            flex-shrink: 0;
        }

        .empty-state {
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
        }

        .right-stack {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../admin_folder/sidebar.php") ?>

        <div class="rightbar">

            <?php
            function human_time_diff_dash($timestamp)
            {
                $diff = time() - strtotime($timestamp);
                if ($diff < 60)
                    return $diff . 's ago';
                if ($diff < 3600)
                    return floor($diff / 60) . 'm ago';
                if ($diff < 86400)
                    return floor($diff / 3600) . 'h ago';
                return floor($diff / 86400) . 'd ago';
            }

            $enrollGradeLabels = [];
            $enrollGradeData = [];
            foreach ($enrollmentByGrade as $g) {
                $enrollGradeLabels[] = $g['grade_level'];
                $enrollGradeData[] = (int) $g['total'];
            }

            function activityIconMap($action)
            {
                $map = [
                    'enrollment' => ['fa-user-plus', '#22c55e', '#dcfce7'],
                    'pending' => ['fa-clock', '#f59e0b', '#fffbeb'],
                    'module_created' => ['fa-plus', '#10b981', '#ecfdf5'],
                    'activity_submitted' => ['fa-file-lines', '#3b82f6', '#eff6ff'],
                    'quiz_submitted' => ['fa-file-lines', '#3b82f6', '#eff6ff'],
                    'quiz_passed' => ['fa-trophy', '#f59e0b', '#fffbeb'],
                    'activity_passed' => ['fa-star', '#8b5cf6', '#f5f3ff'],
                    'invite_sent' => ['fa-paper-plane', '#6366f1', '#eef2ff'],
                    'invite_accepted' => ['fa-circle-check', '#22c55e', '#dcfce7'],
                    'invite_declined' => ['fa-circle-xmark', '#ef4444', '#fef2f2'],
                    'login' => ['fa-arrow-right-to-bracket', '#22c55e', '#dcfce7'],
                    'logout' => ['fa-arrow-right-from-bracket', '#94a3b8', '#f1f5f9'],
                    'subject_created' => ['fa-plus', '#10b981', '#ecfdf5'],
                    'subject_updated' => ['fa-pencil', '#3b82f6', '#eff6ff'],
                    'settings_changed' => ['fa-circle-dot', '#f59e0b', '#fffbeb'],
                    'module_disabled' => ['fa-trash', '#ef4444', '#fef2f2'],
                    'backup_created' => ['fa-database', '#f59e0b', '#fffbeb'],
                ];
                return $map[$action] ?? ['fa-circle-info', '#64748b', '#f8fafc'];
            }
            // Full list used for Teacher List panel
            ?>

            <main class="dash-main">

                <div class="dash-header">
                    <h2>Admin Dashboard</h2>
                    <p>System overview — real-time enrollment and teacher analytics</p>
                </div>

                <!-- Metric Cards -->
                <div class="parent-card">
                    <div class="card-box">
                        <a href="/learning_management/public/?url=student_users">
                            <div class="card-text"><span>Total Students</span>
                                <p><?= $totalStudents ?></p>
                                <div class="stat-data">Currently enrolled</div>
                            </div>
                            <div class="card-icon"><i class="fa fa-users"></i></div>
                        </a>
                    </div>
                    <div class="card-box">
                        <a href="/learning_management/public/?url=teacher_users">
                            <div class="card-text"><span>Total Teachers</span>
                                <p><?= $totalTeachers ?></p>
                                <div class="stat-data">All registered teacher</div>
                            </div>
                            <div class="card-icon"><i class="fa fa-graduation-cap"></i></div>
                        </a>
                    </div>
                    <div class="card-box">
                        <a href="/learning_management/public/?url=student_users&active_tab=masterlist">
                            <div class="card-text">
                                <span>Pending Masterlist</span>
                                <p><?= $pendingMasterlistCount ?></p>
                                <div class="stat-data">Not yet enrolled</div>
                            </div>
                            <div class="card-icon"><i class="fa fa-hourglass-half"></i></div>
                        </a>
                    </div>
                    <div class="card-box">
                        <a href="/learning_management/public/?url=Adminsections">
                            <div class="card-text"><span>Total Sections</span>
                                <p><?= $totalSections ?></p>
                                <div class="stat-data">Grade 11 & 12 combined</div>
                            </div>
                            <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                        </a>
                    </div>
                </div>

                <div class="charts-row">
                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <div class="chart-card-title">Activity Logs</div>
                                <div class="chart-card-desc">Recent actions across the system</div>
                            </div>
                            <div class="header-right">

                            </div>
                        </div>
                        <div class="body" style="max-height: 480px; overflow-y: auto;">
                            <?php if (empty($activityLogs)): ?>
                                <div class="empty-state">No activity recorded yet.</div>
                            <?php else: ?>
                                <?php foreach ($activityLogs as $log):
                                    [$faIcon, $iconColor, $iconBg] = activityIconMap($log['action']);
                                    ?>
                                    <div class="activity-log-item">
                                        <div class="activity-icon" style="background:<?= $iconBg ?>;">
                                            <i class="fa <?= $faIcon ?>" style="color:<?= $iconColor ?>;"></i>
                                        </div>
                                        <div class="activity-text">
                                            <p><?= htmlspecialchars($log['description']) ?></p>
                                            <span>
                                                <?= ucfirst(htmlspecialchars($log['role'])) ?>
                                                · <?= $log['time_ago'] ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Row 3: Recent Enrollments -->
                    <div class="chart-card">
                        <div class="header">
                            <!-- <i class="fa fa-users"></i> -->
                            <div class="header-left">
                                <div class="chart-card-title">Recent enrollments</div>
                                <div class="chart-card-desc">Newest student enrollments</div>
                            </div>
                            <div class="header-right">
                                <a href="/learning_management/public/?url=student_users">View all</a>
                            </div>
                        </div>
                        <div class="body">
                            <?php if (empty($recentEnrollments)): ?>
                                <div class="empty-state">No recent enrollments.</div>
                            <?php else: ?>
                                <?php foreach ($recentEnrollments as $e):
                                    $parts = explode(' ', $e['name']);
                                    $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice($parts, 0, 2)));
                                    $timeAgo = human_time_diff_dash($e['enrolled_at']);
                                    $classLabel = $e['subject_name'] . ' · ' . $e['section_name'];
                                    ?>
                                    <div class="enroll-item">
                                        <div class="e-avatar">
                                            <?= htmlspecialchars($initials) ?>
                                        </div>
                                        <div class="e-info">
                                            <p class="e-name">
                                                <?= htmlspecialchars($e['name']) ?>
                                            </p>
                                            <p class="e-subject">
                                                <?= htmlspecialchars($classLabel) ?>
                                            </p>
                                        </div>
                                        <span class="e-time">
                                            <?= $timeAgo ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>


                </div>

                <div class="charts-row">
                    <!-- Pending Masterlist Matches -->
                    <div class="chart-card pending-card">
                        <div class="header">
                            <div class="header-left">
                                <div class="chart-card-title"></i> Pending masterlist matches
                                </div>
                                <div class="chart-card-desc">Imported students not yet matched
                                </div>
                            </div>
                            <div class="header-right">
                                <a href="/learning_management/public/?url=student_users&active_tab=masterlist">View
                                    all</a>
                            </div>
                        </div>

                        <?php if (empty($pendingMasterlist)): ?>
                            <div class="pending-empty">
                                <i class="fa fa-check-circle" style="font-size:48px;color:#00C950;"></i>
                                <span>No pending masterlist records</span>
                            </div>
                        <?php else: ?>
                            <div class="pending-body">
                                <?php foreach ($pendingMasterlist as $s):
                                    $fullName = trim($s['last_name'] . ', ' . $s['first_name']);
                                    $initials = strtoupper(substr($s['first_name'], 0, 1) . substr($s['last_name'], 0, 1));
                                    ?>
                                    <div class="pending-item">
                                        <div class="p-avatar">
                                            <?= htmlspecialchars($initials) ?>
                                        </div>
                                        <div class="p-info">
                                            <p class="p-name">
                                                <?= htmlspecialchars($fullName) ?>
                                            </p>
                                            <p class="p-section">
                                                <?= htmlspecialchars($s['grade_level'] ?? 'No grade') ?> ·
                                                <?= htmlspecialchars($s['section_name'] ?? 'No section') ?> ·
                                                LRN
                                                <?= htmlspecialchars($s['student_LRN']) ?>
                                            </p>
                                        </div>
                                        <span class="p-badge">Pending</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Row 4: Announcements -->
                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <div class="chart-card-title">Recent announcements</div>
                                <div class="chart-card-desc">Latest updates from teachers</div>
                            </div>
                            <div class="header-right">

                            </div>
                        </div>
                        <div class="body announcements">
                            <?php if (empty($announcements)): ?>
                                <div class="empty-state">No announcements yet.</div>
                            <?php else: ?>
                                <div style="">
                                    <?php foreach ($announcements as $ann): ?>
                                        <div class="ann-item">
                                            <p class="ann-subject">
                                                <?= htmlspecialchars($ann['subject_name']) ?>
                                            </p>
                                            <p class="ann-title">
                                                <?= htmlspecialchars($ann['title']) ?>
                                            </p>
                                            <p class="ann-body">
                                                <?= nl2br(htmlspecialchars(mb_strimwidth($ann['message'], 0, 80, '...'))) ?>
                                            </p>
                                            <span class="ann-meta">
                                                <?= date('M j, Y', strtotime($ann['created_at'])) ?> ·
                                                <?= htmlspecialchars($ann['teacher_name']) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>


                <!-- Row 1: Enrollment by Grade + Student Status -->
                <div class="charts-row">
                    <div class="chart-card pending-card">
                        <div class="header d-flex justify-content-between align-items-center">
                            <div class="header-left">
                                <div class="chart-card-title">Teacher list</div>
                                <div class="chart-card-desc">Teachers and their class load</div>
                            </div>
                            <div class="header-right">
                                <a href="/learning_management/public/?url=teacher_users">View all</a>
                            </div>
                        </div>
                        <div class="pending-body">
                            <?php
                            $avatarColors = ['#00C950', '#1976d2', '#e65100', '#7f77dd', '#BA7517'];
                            foreach ($teacherWorkload as $idx => $t):
                                $parts = explode(' ', $t['teacher_name']);
                                $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice($parts, 0, 2)));
                                $avatarColor = $avatarColors[$idx % count($avatarColors)];
                                ?>
                                <div class="workload-item">
                                    <div class="w-avatar">
                                        <?= htmlspecialchars($initials) ?>
                                    </div>
                                    <span class="w-name"><?= htmlspecialchars($t['teacher_name']) ?></span>
                                    <span class="w-badge"><?= (int) $t['class_count'] ?> classes</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <div class="chart-card-title">Student status breakdown</div>
                                <div class="chart-card-desc">Enrolled vs. pending students
                                </div>
                            </div>
                            <div class="header-right">

                            </div>
                        </div>
                        <div class="body breakdown">
                            <div style="position:relative;height:280px;">
                                <canvas id="studentStatusChart"></canvas>
                            </div>
                            <div class="chart-legend">

                                <span><span class="legend-dot" style="background:#EF9F27;"></span>Pending
                                    <?= $pendingMasterlistCount ?>
                                </span>
                                <span><span class="legend-dot" style="background:#00C950;"></span>Enrolled
                                    <?= $enrolledMasterlistCount ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Teacher List + Pending Masterlist Matches -->
                <div class="charts-row chart-line" style="align-items:stretch;">

                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <div class="chart-card-title">Enrollment by grade level</div>
                                <div class="chart-card-desc">Student counts by grade</div>
                            </div>
                            <div class="header-right">

                            </div>
                        </div>
                        <div class="body enrollment">
                            <div style="position:relative;height:280px;">
                                <canvas id="enrollGradeChart"></canvas>
                            </div>
                            <div class="chart-legend">
                                <span><span class="legend-dot" style="background:#00C950;"></span>Grade 11</span>
                                <span><span class="legend-dot" style="background:#1976d2;"></span>Grade 12</span>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="chart-card">

                    </div> -->
                </div>

            </main>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        const GREEN = '#00C950';
        const BLUE = '#1976d2';
        const ORANGE = '#e65100';
        const AMBER = '#EF9F27';
        const PURPLE = '#7f77dd';
        const GRID = 'rgba(0,0,0,0.06)';
        const MUTED = '#9ca3af';

        // 1. Enrollment by Grade
        const gradeColorMap = {
            'Grade 11': GREEN,
            'Grade 12': BLUE
        };

        new Chart(document.getElementById('enrollGradeChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($enrollGradeLabels) ?>,
                datasets: [{
                    label: 'Students', data: <?= json_encode($enrollGradeData) ?>,
                    backgroundColor: <?= json_encode($enrollGradeLabels) ?>.map(label => gradeColorMap[label] || MUTED),
                    borderRadius: 8, borderSkipped: false
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' students' } } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: MUTED, font: { size: 12 } } },
                    y: { grid: { color: GRID }, ticks: { color: MUTED, font: { size: 11 }, stepSize: 1 }, beginAtZero: true }
                }
            }
        });

        // 2. Student Status Donut
        new Chart(document.getElementById('studentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Enrolled', 'Pending'],
                datasets: [{
                    data: [<?= (int) $enrolledMasterlistCount ?>, <?= (int) $pendingMasterlistCount ?>],
                    backgroundColor: [GREEN, AMBER], borderWidth: 3, borderColor: '#fff', hoverOffset: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '68%',
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed } } }
            }
        });
    </script>

</body>

</html>