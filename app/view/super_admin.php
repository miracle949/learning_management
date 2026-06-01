<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../css_folder/super_admin.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        .dash-main {
            padding: 24px 28px;
            background: #f8fafc;
            min-height: 100vh;
        }

        .dash-header {
            margin-bottom: 24px;
        }

        .dash-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 4px;
        }

        .dash-header p {
            font-size: 13.5px;
            color: #6b7280;
            margin: 0;
        }

        .body {
            margin-top: 0.5rem;
            scrollbar-width: none;
        }

        .header a {
            font-size: 13px;
            font-weight: 600;
            color: var(--green);
            text-decoration: none;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            margin-top: 1.5rem;
        }

        .charts-row-workload {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            margin-top: 1.5rem;
        }

        .charts-row.stretch-row {
            align-items: stretch;
        }

        .charts-row.stretch-row>.chart-card {
            display: flex;
            flex-direction: column;
        }

        .chart-card {
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            padding: 20px 22px;
            width: 100%;
            max-height: 100%;
        }

        .chart-card-title {
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0 0 0px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-card-title i {
            color: #00C950;
            font-size: 14px;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 12px;
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
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .workload-item:last-child {
            border-bottom: none;
        }

        .w-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
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
            font-size: 13.5px;
            color: #1a1a2e;
            font-weight: 500;
        }

        .w-badge {
            font-size: 12px;
            background: #e8f5e9;
            color: #00a040;
            border-radius: 20px;
            padding: 3px 10px;
            font-weight: 600;
        }

        .enroll-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .enroll-item:last-child {
            border-bottom: none;
        }

        .e-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #00C950;
            color: #fff;
            font-size: 11px;
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
            font-size: 13px;
            font-weight: 600;
            color: #1a1a2e;
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
            font-size: 11px;
            color: #9ca3af;
            flex-shrink: 0;
        }

        .pending-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .pending-item:last-child {
            border-bottom: none;
        }

        .p-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fff3e0;
            color: #e65100;
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
            color: #1a1a2e;
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

        .pending-card {
            display: flex;
            flex-direction: column;
            min-height: 350px;
        }

        .pending-card .pending-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
            border-left: 3px solid #00C950;
        }

        .ann-item:last-child {
            margin-bottom: 0;
        }

        .ann-subject {
            font-size: 13.5px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 3px;
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

        /* ── Activity Log styles (super admin only) ── */
        .activity-log-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .activity-log-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .activity-icon i {
            font-size: 15.5px;
        }

        .activity-text {
            flex: 1;
            min-width: 0;
        }

        .activity-text p {
            margin: 0;
            font-size: 13px;
            font-weight: 600;
            color: #111827;
            line-height: 1.4;
        }

        .activity-text span {
            font-size: 11px;
            color: #9ca3af;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <?php include("../super_admin_folder/nav.php") ?>

            <?php
            function human_time_diff_dash($timestamp)
            {
                if (!$timestamp)
                    return 'just now';
                $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
                $ago = new DateTime($timestamp, new DateTimeZone('Asia/Manila'));
                $diff = $now->getTimestamp() - $ago->getTimestamp();
                if ($diff < 0)
                    $diff = 0;
                if ($diff < 3600)
                    return floor($diff / 60) . 'm ago';
                if ($diff < 86400)
                    return floor($diff / 3600) . 'h ago';
                if ($diff < 604800)
                    return floor($diff / 86400) . 'd ago';
                return $ago->format('M j, Y');
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

            $enrollGradeLabels = [];
            $enrollGradeData = [];
            foreach ($enrollmentByGrade as $g) {
                $enrollGradeLabels[] = $g['grade_level'];
                $enrollGradeData[] = (int) $g['total'];
            }

            $chartTeachers = array_slice($teacherWorkload, 0, 5);
            $workloadLabels = [];
            $workloadData = [];
            foreach ($chartTeachers as $t) {
                $nameParts = explode(' ', $t['teacher_name']);
                $short = count($nameParts) >= 2
                    ? $nameParts[0] . ' ' . $nameParts[count($nameParts) - 1]
                    : $t['teacher_name'];
                $workloadLabels[] = $short;
                $workloadData[] = (int) $t['class_count'];
            }
            ?>

            <main class="dash-main">

                <div class="dash-header">
                    <h2>Super Admin Dashboard</h2>
                    <p>Full platform overview — real-time enrollment, teacher analytics &amp; activity logs</p>
                </div>

                <!-- ── Metric Cards ── -->
                <div class="parent-card">
                    <div class="card-box">
                        <a href="/learning_management/public/?url=super_admin_student_users">
                            <div class="card-text"><span>Pending Approvals</span>
                                <p><?= $totalPendingApprovals ?></p>
                            </div>
                            <div class="card-icon"><i class="fa fa-clock"></i></div>
                        </a>
                    </div>
                    <div class="card-box">
                        <a href="/learning_management/public/?url=super_admin_student_users">
                            <div class="card-text"><span>Total Students</span>
                                <p><?= $totalStudents ?></p>
                            </div>
                            <div class="card-icon"><i class="fa fa-users"></i></div>
                        </a>
                    </div>
                    <div class="card-box">
                        <a href="/learning_management/public/?url=super_admin_teacher_users">
                            <div class="card-text"><span>Total Teachers</span>
                                <p><?= $totalTeachers ?></p>
                            </div>
                            <div class="card-icon"><i class="fa fa-graduation-cap"></i></div>
                        </a>
                    </div>
                </div>

                <!-- ── Row 1: Enrollment by Grade + Student Status ── -->
                <div class="charts-row">
                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-chart-bar"></i> Enrollment by grade level</p>
                        </div>
                        <div class="body">
                            <div style="position:relative;height:220px;">
                                <canvas id="enrollGradeChart"></canvas>
                            </div>
                            <div class="chart-legend">
                                <span><span class="legend-dot" style="background:#00C950;"></span>Grade 11</span>
                                <span><span class="legend-dot" style="background:#1976d2;"></span>Grade 12</span>
                            </div>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-chart-pie"></i> Student status breakdown</p>
                        </div>
                        <div class="body">
                            <div style="position:relative;height:220px;">
                                <canvas id="studentStatusChart"></canvas>
                            </div>
                            <div class="chart-legend">
                                <span><span class="legend-dot" style="background:#00C950;"></span>Approved
                                    <?= $approvedCount ?></span>
                                <span><span class="legend-dot" style="background:#EF9F27;"></span>Pending
                                    <?= $pendingCount ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Row 2: Workload Chart | Teacher List + Enrollment Summary ── -->
                <div class="charts-row-workload">

                    <div class="chart-card">
                        <p class="chart-card-title"><i class="fa fa-list-check"></i> Teacher workload (classes assigned)
                        </p>
                        <div style="position:relative;height:<?= max(180, count($chartTeachers) * 44 + 60) ?>px;">
                            <canvas id="workloadChart"></canvas>
                        </div>
                    </div>

                    <div class="right-stack">
                        <div class="chart-card">
                            <div class="header d-flex justify-content-between align-items-center">
                                <p class="chart-card-title"><i class="fa fa-chalkboard-teacher"></i> Teacher list</p>
                                <a href="#">View all</a>
                            </div>
                            <div class="body">
                                <?php
                                $avatarColors = ['#00C950', '#1976d2', '#e65100', '#7f77dd', '#BA7517'];
                                foreach ($teacherWorkload as $idx => $t):
                                    $parts = explode(' ', $t['teacher_name']);
                                    $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice($parts, 0, 2)));
                                    $avatarColor = $avatarColors[$idx % count($avatarColors)];
                                    ?>
                                    <div class="workload-item">
                                        <div class="w-avatar" style="background:<?= $avatarColor ?>;">
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
                                <p class="chart-card-title"><i class="fa fa-signal"></i> Enrollment summary</p>
                            </div>
                            <div class="body">
                                <?php
                                $maxEnroll = max(array_column($enrollmentByGrade, 'total') ?: [1]);
                                $barColors = ['#00C950', '#1976d2', '#e65100', '#7f77dd'];
                                $ci = 0;
                                foreach ($enrollmentByGrade as $g):
                                    $pct = $maxEnroll > 0 ? round(($g['total'] / $maxEnroll) * 100) : 0;
                                    $color = $barColors[$ci % count($barColors)];
                                    $ci++;
                                    ?>
                                    <div class="section-bar-row">
                                        <span class="section-bar-label"><?= htmlspecialchars($g['grade_level']) ?></span>
                                        <div class="section-bar-track">
                                            <div class="section-bar-fill"
                                                style="width:<?= $pct ?>%;background:<?= $color ?>;"></div>
                                        </div>
                                        <span class="section-bar-val"><?= (int) $g['total'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Row 3: Pending Approvals + Recent Enrollments ── -->
                <div class="charts-row" style="margin-bottom:20px; align-items:stretch;">

                    <div class="chart-card pending-card">
                        <p class="chart-card-title"><i class="fa fa-clock"></i> Pending approvals</p>

                        <?php if (empty($pendingStudents)): ?>
                            <div class="pending-empty">
                                <i class="fa fa-clock" style="font-size:48px;color:#00C950;"></i>
                                <span>No pending approvals</span>
                            </div>
                        <?php else: ?>
                            <div class="pending-body">
                                <?php foreach ($pendingStudents as $s):
                                    $parts = explode(' ', $s['name']);
                                    $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice($parts, 0, 2)));
                                    ?>
                                    <div class="pending-item">
                                        <div class="p-avatar"><?= htmlspecialchars($initials) ?></div>
                                        <div class="p-info">
                                            <p class="p-name"><?= htmlspecialchars($s['name']) ?></p>
                                            <p class="p-section">
                                                <?= htmlspecialchars($s['section_name'] ?? 'No section') ?> ·
                                                <?= htmlspecialchars($s['grade_level'] ?? '') ?>
                                            </p>
                                        </div>
                                        <span class="p-badge">Pending</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-users"></i> Recent enrollments</p>
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
                                        <div class="e-avatar"><?= htmlspecialchars($initials) ?></div>
                                        <div class="e-info">
                                            <p class="e-name"><?= htmlspecialchars($e['name']) ?></p>
                                            <p class="e-subject"><?= htmlspecialchars($classLabel) ?></p>
                                        </div>
                                        <span class="e-time"><?= $timeAgo ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ── Row 4 (Super Admin Only): Activity Logs + Announcements ── -->
                <div class="charts-row" style="margin-bottom:20px; align-items:stretch;">

                    <div class="chart-card" style="height: 100%;">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-file"></i> Activity logs</p>
                        </div>
                        <div class="body" style="height: 690px; overflow-y: auto;">
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
                                                · <?= human_time_diff_dash($log['created_at']) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-bullhorn"></i> Recent announcements</p>
                        </div>
                        <div class="body">
                            <?php if (empty($announcements)): ?>
                                <div class="empty-state">No announcements yet.</div>
                            <?php else: ?>
                                <?php foreach ($announcements as $ann): ?>
                                    <div class="ann-item">
                                        <p class="ann-subject"><?= htmlspecialchars($ann['subject_name']) ?></p>
                                        <p class="ann-title"><?= htmlspecialchars($ann['title']) ?></p>
                                        <p class="ann-body">
                                            <?= nl2br(htmlspecialchars(mb_strimwidth($ann['message'], 0, 80, '...'))) ?>
                                        </p>
                                        <span class="ann-meta">
                                            <?= date('M j, Y', strtotime($ann['created_at'])) ?> ·
                                            <?= htmlspecialchars($ann['teacher_name']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
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
        new Chart(document.getElementById('enrollGradeChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($enrollGradeLabels) ?>,
                datasets: [{
                    label: 'Students',
                    data: <?= json_encode($enrollGradeData) ?>,
                    backgroundColor: <?= json_encode($enrollGradeLabels) ?>.map((_, i) => [GREEN, BLUE, ORANGE, PURPLE][i % 4]),
                    borderRadius: 8,
                    borderSkipped: false
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
                labels: ['Approved', 'Pending'],
                datasets: [{
                    data: [<?= (int) $approvedCount ?>, <?= (int) $pendingCount ?>],
                    backgroundColor: [GREEN, AMBER],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '68%',
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.label + ': ' + ctx.parsed } } }
            }
        });

        // 3. Teacher Workload — top 5 only
        new Chart(document.getElementById('workloadChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($workloadLabels) ?>,
                datasets: [{
                    label: 'Classes',
                    data: <?= json_encode($workloadData) ?>,
                    backgroundColor: <?= json_encode($workloadLabels) ?>.map((_, i) => [GREEN, BLUE, ORANGE, PURPLE, AMBER][i % 5]),
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.parsed.x + ' classes' } } },
                scales: {
                    x: { grid: { color: GRID }, ticks: { color: MUTED, font: { size: 11 }, stepSize: 1 }, beginAtZero: true },
                    y: { grid: { display: false }, ticks: { color: '#374151', font: { size: 12 } } }
                }
            }
        });
    </script>

</body>

</html>