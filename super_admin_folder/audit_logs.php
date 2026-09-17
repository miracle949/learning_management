<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Trail / Logs</title>
    <link rel="stylesheet" href="../css_folder/audit_logs.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <main class="main">

                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Audit Trail / Logs</h2>
                        <p>A combined feed of enrollments, submissions, content changes, and system events</p>
                    </div>
                </div>

                <div class="main-body">

                    <!-- STAT CARDS -->
                    <div class="al-stats-grid">
                        <div class="al-stat-card">
                            
                            <div>
                                <div class="al-stat-label">Total logged events</div>
                                <div class="al-stat-value"><?= (int) $stats['total'] ?></div>
                                <div class="al-stat-desc">All recorded system activity</div>
                            </div>
                            <div class="al-stat-icon al-stat-icon-blue"><i class="fa fa-list-check"></i></div>
                        </div>
                        <div class="al-stat-card">
                            
                            <div>
                                <div class="al-stat-label">Successful</div>
                                <div class="al-stat-value"><?= (int) $stats['success'] ?></div>
                                <div class="al-stat-desc">Events completed without errors</div>
                            </div>
                            <div class="al-stat-icon al-stat-icon-green"><i class="fa fa-circle-check"></i></div>
                        </div>
                        <div class="al-stat-card">
                            
                            <div>
                                <div class="al-stat-label">Needs review</div>
                                <div class="al-stat-value"><?= (int) $stats['flagged'] ?></div>
                                <div class="al-stat-desc">Events flagged for attention</div>
                            </div>
                            <div
                                class="al-stat-icon <?= $stats['flagged'] > 0 ? 'al-stat-icon-red' : 'al-stat-icon-gray' ?>">
                                <i class="fa fa-triangle-exclamation"></i>
                            </div>
                        </div>
                        <div class="al-stat-card">
                            
                            <div>
                                <div class="al-stat-label">Events today</div>
                                <div class="al-stat-value"><?= (int) $stats['today'] ?></div>
                                <div class="al-stat-desc">Activity logged in the last 24 hours</div>
                            </div>
                            <div class="al-stat-icon al-stat-icon-purple"><i class="fa fa-calendar-day"></i></div>
                        </div>
                    </div>

                    <!-- LOG TABLE -->
                    <div class="al-card">
                        <div class="al-card-header">
                            <h3>Activity log</h3>
                            <p>Filter by role, status, or search by description and user</p>
                        </div>

                        <form method="GET" action="/learning_management/public/" class="al-filters">
                            <input type="hidden" name="url" value="audit_logs">
                            <input type="text" name="search" class="al-search-input"
                                placeholder="Search description or user..." value="<?= htmlspecialchars($search) ?>">

                            <select name="role" class="al-select">
                                <option value="">All roles</option>
                                <?php foreach (['student', 'teacher', 'admin', 'superadmin', 'system', 'unknown'] as $r): ?>
                                    <option value="<?= $r ?>" <?= $role === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <select name="status" class="al-select">
                                <option value="">All statuses</option>
                                <?php foreach (['Success', 'Review', 'Flagged'] as $s): ?>
                                    <option value="<?= $s ?>" <?= strtolower($status) === strtolower($s) ? 'selected' : '' ?>>
                                        <?= $s ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="al-filter-btn"><i class="fa fa-filter me-1"></i>
                                Filter</button>
                            <?php if ($search !== '' || $role !== '' || $status !== ''): ?>
                                <a href="/learning_management/public/?url=audit_logs" class="al-clear-link">Clear</a>
                            <?php endif; ?>
                        </form>

                        <?php if (empty($logs)): ?>
                            <div class="al-empty">
                                <i class="fa fa-inbox"></i>
                                <p>No matching activity found.</p>
                            </div>
                        <?php else: ?>
                            <div class="al-table-wrap">
                                <table class="al-table">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>When</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($logs as $log): ?>
                                            <?php
                                            $statusLower = strtolower($log['status']);
                                            $statusClass = $statusLower === 'success' ? 'al-badge-success'
                                                : ($statusLower === 'review' ? 'al-badge-review' : 'al-badge-flagged');
                                            ?>
                                            <tr>
                                                <td class="al-description"><?= htmlspecialchars($log['description']) ?></td>
                                                <td><?= htmlspecialchars($log['user_name']) ?></td>
                                                <td><span
                                                        class="al-badge al-badge-role"><?= htmlspecialchars(ucfirst($log['role'])) ?></span>
                                                </td>
                                                <td><span
                                                        class="al-badge <?= $statusClass ?>"><?= htmlspecialchars($log['status']) ?></span>
                                                </td>
                                                <td class="al-time"><?= htmlspecialchars($log['time_ago']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- PAGINATION (matches the student page's style) -->
                    <?php
                    $baseQuery = array_filter([
                        'url' => 'audit_logs',
                        'search' => $search,
                        'role' => $role,
                        'status' => $status,
                    ]);
                    $startRecord = $totalLogs > 0 ? (($page - 1) * $limit) + 1 : 0;
                    $endRecord = min($page * $limit, $totalLogs);
                    ?>
                    <div class="al-pagination-bar">
                        <div class="al-showing-text">
                            Showing
                            <?= $endRecord ?> of
                            <?= $totalLogs ?> events
                        </div>
                        <div class="al-pagination">
                            <a class="al-page-nav <?= $page <= 1 ? 'disabled' : '' ?>"
                                href="<?= $page > 1 ? '/learning_management/public/?' . http_build_query(array_merge($baseQuery, ['page' => $page - 1])) : '#' ?>">
                                <i class="fa fa-chevron-left"></i>
                            </a>

                            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                <a href="/learning_management/public/?<?= http_build_query(array_merge($baseQuery, ['page' => $p])) ?>"
                                    class="al-page-btn <?= $p === $page ? 'active' : '' ?>">
                                    <?= $p ?>
                                </a>
                            <?php endfor; ?>

                            <a class="al-page-nav <?= $page >= $totalPages ? 'disabled' : '' ?>"
                                href="<?= $page < $totalPages ? '/learning_management/public/?' . http_build_query(array_merge($baseQuery, ['page' => $page + 1])) : '#' ?>">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                </div><!-- /main-body -->

            </main>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>