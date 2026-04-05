<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Works</title>
    <link rel="stylesheet" href="../css_folder/student_works.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <?php
    // Get parameters passed from records.php
    $assignment_id = isset($_GET['assignment_id']) ? (int) $_GET['assignment_id'] : 0;
    $subject_id = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;

    // Reuse your existing TeacherController/model
    require_once "../app/models/Teacher.php";
    $teacherModel = new Teacher();

    // Fetch assignment info and its submissions
    $assignmentInfo = $assignment_id ? $teacherModel->getAssignmentById($assignment_id) : null;
    $submissions = $assignment_id ? $teacherModel->getSubmissions($assignment_id) : [];
    ?>

    <div class="container-fluid p-0">
        <?php include("sidebar.php"); ?>

        <div class="rightbar">
            <?php include("nav.php"); ?>

            <main>
                <div class="sidebar-works">
                    <div class="student-sub-header">
                        <i class="fa fa-users"></i>
                        <p>All Students</p>
                    </div>

                    <h5>Submitted Works</h5>

                    <div class="sidebar-list">
                        <?php foreach ($submissions as $loop_index => $sub):
                            $initials = strtoupper(substr($sub['student_name'] ?? 'S', 0, 1));
                            ?>
                            <div class="student"
                                onclick="window.location.href='/learning_management/public/?url=works&assignment_id=<?= $assignment_id ?>&subject_id=<?= $subject_id ?>&student_index=<?= $loop_index ?>'">
                                <div class="name">
                                    <div class="icon"><span><?= $initials ?></span></div>
                                    <p><?= htmlspecialchars($sub['student_name'] ?? '—') ?></p>
                                </div>
                                <div class="grade">
                                    <p>
                                        <?= $sub['points_earned'] !== null && $sub['points_earned'] !== ''
                                            ? (int) $sub['points_earned']
                                            : '__' ?>
                                        /<?= (int) ($assignmentInfo['points'] ?? 100) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($submissions)): ?>
                            <p style="font-size:13px;color:#9ca3af;padding:1rem;">No submissions yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="rightbar-works">
                    <div class="assignment-name">
                        <h5><?= htmlspecialchars($assignmentInfo['title'] ?? 'Assignment') ?></h5>
                    </div>
                    <div class="rightbar-submit">
                        <p>
                            <?php if (!empty($assignmentInfo['due_date'])): ?>
                                Submission closed
                                <?= date('M d, h:i A', strtotime($assignmentInfo['due_date'])) ?>
                            <?php else: ?>
                                <?= htmlspecialchars($assignmentInfo['title'] ?? 'Assignment') ?>
                            <?php endif; ?>
                        </p>
                        <i class="fa fa-pen"></i>
                    </div>

                    <div class="parent-submit-file">
                        <?php foreach ($submissions as $loop_index => $sub):
                            $initials = strtoupper(substr($sub['student_name'] ?? 'S', 0, 1));
                            $filePath = $sub['file_path'] ?? '';
                            $fileName = basename($filePath);
                            $cleanName = preg_replace('/^[a-f0-9]+_/', '', $fileName);
                            if ($filePath && !str_starts_with($filePath, '/') && !str_starts_with($filePath, 'http')) {
                                $filePath = '/learning_management/' . $filePath;
                            }
                            $worksUrl = "/learning_management/public/?url=works&assignment_id={$assignment_id}&subject_id={$subject_id}&student_index={$loop_index}";
                            ?>
                            <div class="student-submit" style="cursor:pointer"
                                onclick="window.location.href='<?= $worksUrl ?>'">
                                <div class="student-header">
                                    <div class="icon"><span><?= $initials ?></span></div>
                                    <p><?= htmlspecialchars($sub['student_name'] ?? '—') ?></p>
                                </div>
                                <div class="student-body">
                                    <div class="file-box">
                                        <i class="fa fa-file-pdf" style="font-size:2rem;color:#e53e3e;"></i>
                                    </div>
                                    <p><?= htmlspecialchars($cleanName) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($submissions)): ?>
                            <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#9ca3af;">
                                <i class="fa fa-inbox"
                                    style="font-size:2rem;opacity:.3;display:block;margin-bottom:1rem;"></i>
                                <p>No submissions yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>

    </div>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

</body>

</html>