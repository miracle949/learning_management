<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/assignments.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="parent-card">
                <div class="box-card">
                    <div class="box-text">
                        <p>Pending</p>

                        <h4><?= $pendingCount ?></h4>
                    </div>
                    <div class="box-icon">
                        <i class="fa fa-clock"></i>
                    </div>
                </div>

                <div class="box-card">
                    <div class="box-text">
                        <p>Completed</p>

                        <h4><?= $completedCount ?></h4>
                    </div>
                    <div class="box-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>

                <div class="box-card">
                    <div class="box-text">
                        <p>Graded</p>

                        <h4><?= $gradedCount ?></h4>
                    </div>
                    <div class="box-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>

            <div class="parent-pending">
                <div class="complete-grade">
                    <div class="complete-parent">
                        <div class="header">
                            <h3>Completed Task</h3>

                            <a href="#">View all</a>
                        </div>

                        <div class="complete">
                            <?php foreach ($completedAssignments as $item): ?>
                                <div class="complete-box">
                                    <h5><?= htmlspecialchars($item['task']) ?></h5>
                                    <p>Date Submitted:
                                        <?= !empty($item['submitted_at']) ? date('F j, Y', strtotime($item['submitted_at'])) : 'N/A' ?>
                                    </p>
                                    <span>Completed</span>
                                    <a
                                        href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                        View Task <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="graded">
                        <div class="header">
                            <h3>Graded Task</h3>
                            <a href="#">View all</a>
                        </div>

                        <div class="graded-parent">
                            <?php if (empty($gradedAssignments)): ?>
                                <p style="color:#888; padding: 10px;">No graded tasks yet.</p>
                            <?php else: ?>
                                <?php foreach ($gradedAssignments as $item): ?>
                                    <div class="graded-box">
                                        <h5><?= htmlspecialchars($item['task']) ?></h5>
                                        <p>Graded:
                                            <?= !empty($item['graded_at']) ? date('F j, Y', strtotime($item['graded_at'])) : 'N/A' ?>
                                        </p>
                                        <?php
                                        $percent = $item['total_points'] > 0
                                            ? ($item['points_earned'] / $item['total_points']) * 100
                                            : 0;
                                        $scoreColor = $percent >= 75 ? '#4CAF7D' : '#C82525';
                                        ?>
                                        <span style="color: <?= $scoreColor ?>;">
                                            <?= (int) $item['points_earned'] ?> / <?= (int) $item['total_points'] ?>
                                        </span>
                                        <!-- <?php if (!empty($item['feedback'])): ?>
                                            <p style="font-size:0.8rem; color:#555; margin-top:4px;">
                                                "<?= htmlspecialchars($item['feedback']) ?>"
                                            </p>
                                        <?php endif; ?> -->
                                        <a
                                            href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                            View Task <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="pendings">
                    <div class="header">
                        <h3>Pending Submissions</h3>

                        <a href="#">View all</a>
                    </div>

                    <div class="pending-parent-card">
                        <?php foreach ($pendingAssignments as $item):
                            $daysLeft = '';
                            if (!empty($item['due_date'])) {
                                $diff = (int) ceil((strtotime($item['due_date']) - time()) / 86400);
                                $daysLeft = $diff > 0 ? $diff . ' days left' : ($diff === 0 ? 'Due today' : abs($diff) . ' days overdue');
                            }
                            ?>
                            <div class="pending-card">
                                <h5><?= htmlspecialchars($item['task']) ?></h5>
                                <p>Due Date:
                                    <?= !empty($item['due_date']) ? date('F j, Y', strtotime($item['due_date'])) : 'No due date' ?>
                                </p>
                                <?php if ($daysLeft): ?><span><?= htmlspecialchars($daysLeft) ?></span><?php endif; ?>
                                <a
                                    href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                    View Task <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>