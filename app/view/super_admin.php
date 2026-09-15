<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../css_folder/super_admin.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>

    </style>
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">

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

            // Strand bar colors, cycled in order.
            $strandBarColors = ['#00C950', '#1976d2', '#e65100', '#7f77dd', '#BA7517', '#0891b2'];
            $maxStrandTotal = !empty($enrollmentByStrand)
                ? max(array_column($enrollmentByStrand, 'total'))
                : 1;
            if ($maxStrandTotal <= 0) {
                $maxStrandTotal = 1;
            }
            ?>

            <main class="dash-main">

                <div class="dash-header">
                    <h2>System Overview</h2>
                    <p>Monitor every school, admin, and system process from a single console.</p>
                </div>

                <!-- ══════════════ TOP STAT CARDS ══════════════
                     Admin Accounts · Teachers · Students · Strands Offered ·
                     Last Backup · Active Sessions Right Now
                -->
                <div class="dash-stats-grid">
                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Admin Accounts</div>
                        <div class="dash-stat-value"><?= (int) $totalAdmins ?></div>
                        <div class="dash-stat-delta ok">All active</div>
                    </div>

                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Teachers</div>
                        <div class="dash-stat-value"><?= (int) $totalTeachers ?></div>
                        <div class="dash-stat-delta ok">Registered accounts</div>
                    </div>

                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Students</div>
                        <div class="dash-stat-value"><?= (int) $totalStudents ?></div>
                        <div class="dash-stat-delta ok"><?= (int) $approvedCount ?> approved ·
                            <?= (int) $pendingCount ?> pending
                        </div>
                    </div>

                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Strands Offered</div>
                        <div class="dash-stat-value"><?= count($strandsOffered) ?></div>
                        <div class="dash-stat-delta ok">
                            <?= !empty($strandsOffered)
                                ? htmlspecialchars(implode(' · ', array_column($strandsOffered, 'strand')))
                                : 'None configured yet' ?>
                        </div>
                    </div>

                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Last Backup</div>
                        <div class="dash-stat-value">
                            <?= $lastBackup ? human_time_diff_dash($lastBackup['created_at']) : '—' ?>
                        </div>
                        <div
                            class="dash-stat-delta <?= ($lastBackup && strtolower($lastBackup['status'] ?? '') === 'failed') ? 'err' : 'warn' ?>">
                            <?= $lastBackup
                                ? ucfirst(htmlspecialchars($lastBackup['status'] ?? 'unknown'))
                                : 'No backups logged yet' ?>
                        </div>
                    </div>

                    <div class="dash-stat-card">
                        <div class="dash-stat-label">Active Sessions Right Now</div>
                        <div class="dash-stat-value"><?= (int) $activeSessions['total'] ?></div>
                        <div class="dash-stat-delta ok">
                            <?= (int) $activeSessions['student'] ?> students ·
                            <?= (int) $activeSessions['teacher'] ?> teachers ·
                            <?= (int) $activeSessions['admin'] ?> admins
                        </div>
                    </div>
                </div>

                <!-- ══════════════ ROW 1: Recent System Activity | Quick Actions ══════════════ -->
                <div class="charts-row charts-1" style="align-items:stretch;">

                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <p class="chart-card-title">Recent System Activity</p>
                                <p class="chart-card-desc">Recent actions across the system</p>
                            </div>
                            <a href="#">View audit trail</a>
                        </div>
                        <div class="body">
                            <?php if (empty($activityLogs)): ?>
                                <div class="empty-state">No activity recorded yet.</div>
                            <?php else: ?>
                                <table class="activity-table">
                                    <thead>
                                        <tr>
                                            <th>Actor</th>
                                            <th>Action</th>
                                            <th>Target</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($activityLogs, 0, 6) as $log):
                                            $statusClass = strtolower($log['status'] ?? 'success');
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($log['user_name']) ?></td>
                                                <td><?= htmlspecialchars($log['description']) ?></td>
                                                <td><span
                                                        class="target-cell"><?= htmlspecialchars($log['target'] ?? '—') ?></span>
                                                </td>
                                                <td><span
                                                        class="status-pill <?= htmlspecialchars($statusClass) ?>"><?= htmlspecialchars($log['status'] ?? 'Success') ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- <?php foreach (array_slice($activityLogs, 0, 6) as $log): ?>
                                <div class="activity-log-item">
                                    <div class="activity-icon">
                                        <i class="fa fa-clock-rotate-left"></i>
                                    </div>
                                    <div class="activity-text">
                                        <p><?= htmlspecialchars($log['description']) ?></p>
                                        <span><?= htmlspecialchars($log['user_name']) ?> · <?= human_time_diff_dash($log['created_at']) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?> -->
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <p class="chart-card-title">Quick Actions</p>
                                <p class="chart-card-desc">Recent actions across the system</p>
                            </div>
                        </div>
                        <div class="body">
                            <!-- NOTE: adjust the ?url= targets below to match your actual routes -->
                            <a class="quick-action-row" href="/learning_management/public/?url=super_admin_admin_users">
                                <div class="quick-action-icon"><i class="fa fa-user-plus"></i></div>
                                <div>
                                    <div class="qa-t">Add a new admin account</div>
                                    <div class="qa-d">Create account &amp; assign responsibilities</div>
                                </div>
                            </a>
                            <a class="quick-action-row" href="/learning_management/public/?url=activities">
                                <div class="quick-action-icon"><i class="fa fa-book"></i></div>
                                <div>
                                    <div class="qa-t">Create a new subject or module</div>
                                    <div class="qa-d">Add subjects, lessons, and activities</div>
                                </div>
                            </a>
                            <a class="quick-action-row"
                                href="/learning_management/public/?url=super_admin_student_users">
                                <div class="quick-action-icon"><i class="fa fa-user-check"></i></div>
                                <div>
                                    <div class="qa-t">Review pending student approvals</div>
                                    <div class="qa-d"><?= (int) $totalPendingApprovals ?> waiting right now</div>
                                </div>
                            </a>
                            <a class="quick-action-row"
                                href="/learning_management/public/?url=super_admin_teacher_users">
                                <div class="quick-action-icon"><i class="fa fa-chalkboard-teacher"></i></div>
                                <div>
                                    <div class="qa-t">Manage teacher accounts</div>
                                    <div class="qa-d">View, edit, or deactivate teachers</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ══════════════ ROW 2: Alerts That Need Attention | Enrollment by Strand ══════════════ -->
                <div class="charts-row charts-2" style="align-items:stretch;">

                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <p class="chart-card-title">Alerts That Need
                                    Attention</p>
                                <p class="chart-card-desc"></p>
                            </div>
                        </div>
                        <div class="body">
                            <?php if (empty($systemAlerts)): ?>
                                <div class="empty-state">Nothing needs attention right now.</div>
                            <?php else: ?>
                                <?php foreach ($systemAlerts as $alert): ?>
                                    <div class="alert-row">
                                        <div class="alert-dot <?= htmlspecialchars($alert['severity']) ?>"></div>
                                        <div>
                                            <div class="al-t"><?= htmlspecialchars($alert['title']) ?></div>
                                            <div class="al-d"><?= htmlspecialchars($alert['detail']) ?></div>
                                        </div>
                                        <a href="<?= htmlspecialchars($alert['link']) ?>">
                                            <?= htmlspecialchars($alert['action']) ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <div class="header-left">
                                <p class="chart-card-title">Enrollment by Strand</p>
                                <p class="chart-card-desc"> </p>
                            </div>
                        </div>
                        <div class="body">
                            <?php if (empty($enrollmentByStrand)): ?>
                                <div class="empty-state">No strand-tagged sections yet.</div>
                            <?php else: ?>
                                <?php foreach ($enrollmentByStrand as $idx => $row):
                                    $pct = round(((int) $row['total'] / $maxStrandTotal) * 100);
                                    $color = $strandBarColors[$idx % count($strandBarColors)];
                                    ?>
                                    <div class="section-bar-row">
                                        <span class="section-bar-label"><?= htmlspecialchars($row['strand']) ?></span>
                                        <div class="section-bar-track">
                                            <div class="section-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>;">
                                            </div>
                                        </div>
                                        <span class="section-bar-val"><?= (int) $row['total'] ?></span>
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

</body>

</html>