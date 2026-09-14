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
        .dash-header {
            margin: 0 0 1.5rem;
        }

        .dash-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 5px;
        }

        .dash-header p {
            font-size: 14.5px;
            color: var(--text-dim);
            margin: 0;
        }

        /* ── Stat cards (top row) ── */
        .dash-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .dash-stat-card {
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            padding: 18px 20px;
        }

        .dash-stat-card .dash-stat-label {
            font-size: 12.5px;
            color: #6b7280;
            font-weight: 600;
        }

        .dash-stat-card .dash-stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a2e;
            margin-top: 6px;
        }

        .dash-stat-card .dash-stat-delta {
            font-size: 11.5px;
            margin-top: 6px;
        }

        .dash-stat-delta.ok {
            color: #00a040;
        }

        .dash-stat-delta.warn {
            color: #b5762c;
        }

        .dash-stat-delta.err {
            color: #d92d20;
        }

        /* ── Shared card shell for the four sections below ── */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .chart-card {
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            padding: 20px 22px;
            width: 100%;
        }

        .chart-card-title {
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0 0 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-card-title i {
            color: #00C950;
            font-size: 14px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .header a {
            font-size: 12.5px;
            font-weight: 600;
            color: #00a040;
            text-decoration: none;
            white-space: nowrap;
        }

        /* ── Recent System Activity (table) ── */
        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }

        .activity-table th {
            text-align: left;
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 8px 6px;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
        }

        .activity-table td {
            font-size: 12.8px;
            color: #1a1a2e;
            padding: 11px 6px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .activity-table tr:last-child td {
            border-bottom: none;
        }

        .role-pill {
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .role-pill.student {
            background: #eff6ff;
            color: #3b82f6;
        }

        .role-pill.teacher {
            background: #ecfdf5;
            color: #10b981;
        }

        .role-pill.superadmin,
        .role-pill.admin {
            background: #fffbeb;
            color: #b5762c;
        }

        /* ── Quick Actions ── */
        .quick-action-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            cursor: pointer;
        }

        .quick-action-row:last-child {
            border-bottom: none;
        }

        .quick-action-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #ecfdf5;
            color: #00a040;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .quick-action-row .qa-t {
            font-size: 12.8px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .quick-action-row .qa-d {
            font-size: 11.5px;
            color: #9ca3af;
            margin-top: 1px;
        }

        /* ── Alerts ── */
        .alert-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .alert-row:last-child {
            border-bottom: none;
        }

        .alert-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            margin-top: 5px;
            flex-shrink: 0;
        }

        .alert-dot.warn {
            background: #b5762c;
        }

        .alert-dot.err {
            background: #d92d20;
        }

        .alert-row .al-t {
            font-size: 12.8px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .alert-row .al-d {
            font-size: 11.8px;
            color: #6b7280;
            margin-top: 1px;
        }

        .alert-row a {
            margin-left: auto;
            font-size: 11.5px;
            font-weight: 600;
            color: #00a040;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* ── Enrollment by strand bars (reused pattern) ── */
        .section-bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 0;
        }

        .section-bar-label {
            font-size: 13.5px;
            color: #374151;
            font-weight: 600;
            flex-shrink: 0;
            width: 64px;
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
            width: 30px;
            text-align: right;
            flex-shrink: 0;
        }

        .empty-state {
            padding: 20px;
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
        }
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
                <div class="charts-row" style="align-items:stretch;">

                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-file"></i> Recent System Activity</p>
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
                                            <th>Role</th>
                                            <th>When</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($activityLogs, 0, 6) as $log): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($log['user_name']) ?></td>
                                                <td><?= htmlspecialchars($log['description']) ?></td>
                                                <td><span
                                                        class="role-pill <?= htmlspecialchars($log['role']) ?>"><?= htmlspecialchars($log['role']) ?></span>
                                                </td>
                                                <td><?= human_time_diff_dash($log['created_at']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-bolt"></i> Quick Actions</p>
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
                <div class="charts-row" style="align-items:stretch;">

                    <div class="chart-card">
                        <div class="header">
                            <p class="chart-card-title"><i class="fa fa-triangle-exclamation"></i> Alerts That Need
                                Attention</p>
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
                            <p class="chart-card-title"><i class="fa fa-signal"></i> Enrollment by Strand</p>
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