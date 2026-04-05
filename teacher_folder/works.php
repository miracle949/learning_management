<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Work</title>
    <link rel="stylesheet" href="../css_folder/works.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <?php
    $assignment_id = isset($_GET['assignment_id']) ? (int) $_GET['assignment_id'] : 0;
    $subject_id = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
    $student_index = isset($_GET['student_index']) ? (int) $_GET['student_index'] : 0;

    require_once "../app/models/Teacher.php";
    $teacherModel = new Teacher();
    $assignmentInfo = $assignment_id ? $teacherModel->getAssignmentById($assignment_id) : null;
    $submissions = $assignment_id ? $teacherModel->getSubmissions($assignment_id) : [];

    $totalStudents = count($submissions);
    $student_index = max(0, min($student_index, $totalStudents - 1));
    $currentSub = $submissions[$student_index] ?? null;

    $submission_id = $currentSub['id'] ?? 0;
    $savedGrade = $currentSub['points_earned'] ?? '';
    $savedFeedback = $currentSub['feedback'] ?? '';

    // Build file path
    $filePath = $currentSub['file_path'] ?? '';
    $fileName = basename($filePath);
    $cleanName = preg_replace('/^[a-f0-9]+_/', '', $fileName);
    if ($filePath && !str_starts_with($filePath, '/') && !str_starts_with($filePath, 'http')) {
        $filePath = '/learning_management/' . $filePath;
    }

    $initials = strtoupper(substr($currentSub['student_name'] ?? 'S', 0, 1));
    $submittedAt = !empty($currentSub['submitted_at'])
        ? date('M d, g:i A', strtotime($currentSub['submitted_at']))
        : '—';
    $status = ucfirst($currentSub['status'] ?? 'submitted');
    $points = (int) ($assignmentInfo['points'] ?? 100);

    // Prev / Next indexes
    $prevIndex = $student_index > 0 ? $student_index - 1 : null;
    $nextIndex = $student_index < $totalStudents - 1 ? $student_index + 1 : null;

    $baseUrl = "/learning_management/public/?url=works&assignment_id={$assignment_id}&subject_id={$subject_id}&student_index=";
    ?>

    <div class="container-fluid p-0">
        <?php include("sidebar.php"); ?>

        <div class="rightbar">
            <?php include("nav.php"); ?>

            <main>
                <div class="name-student">
                    <div class="student-sub-header">
                        <div class="student-header">
                            <div class="icon">
                                <p><?= $initials ?></p>
                            </div>
                            <p><?= htmlspecialchars($currentSub['student_name'] ?? '—') ?></p>
                        </div>

                        <div class="next-student">
                            <p><?= $status ?></p>
                            <div class="change-buttons">
                                <?php if ($prevIndex !== null): ?>
                                    <button onclick="window.location.href='<?= $baseUrl . $prevIndex ?>'">
                                        <i class="fa fa-chevron-left"></i>
                                    </button>
                                <?php else: ?>
                                    <button disabled><i class="fa fa-chevron-left"></i></button>
                                <?php endif; ?>

                                <?php if ($nextIndex !== null): ?>
                                    <button onclick="window.location.href='<?= $baseUrl . $nextIndex ?>'">
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                <?php else: ?>
                                    <button disabled><i class="fa fa-chevron-right"></i></button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="return">
                        <a
                            href="/learning_management/public/?url=student_works&assignment_id=<?= $assignment_id ?>&subject_id=<?= $subject_id ?>">Return</a>
                    </div>
                </div>

                <div class="passed-grade">
                    <div class="passed-pdf">
                        <?php if ($filePath): ?>
                            <iframe src="<?= htmlspecialchars($filePath) ?>" width="100%" height="100%"
                                style="border:none;min-height:600px;">
                            </iframe>
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:center;height:400px;color:#9ca3af;">
                                <p>No file submitted.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="grade-pdf">
                        <h5>Files</h5>
                        <p>Submitted on <?= $submittedAt ?></p>

                        <div class="submit-pdf">
                            <?php if ($cleanName): ?>
                                <a href="<?= htmlspecialchars($filePath) ?>" target="_blank"
                                    style="text-decoration:none;color:inherit;">
                                    <div style="border-radius:8px;display:flex;align-items:center;gap:8px;">
                                        <i class="fa fa-file-pdf" style="color:#e53e3e;font-size:1.2rem;"></i>
                                        <span
                                            style="font-size:13px;font-weight:600;"><?= htmlspecialchars($cleanName) ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>

                        <form method="POST" action="/learning_management/public/?url=save_grade">

                            <div class="input-grade">

                                <input type="hidden" name="submission_id" value="<?= $submission_id ?>">
                                <input type="hidden" name="assignment_id" value="<?= $assignment_id ?>">
                                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                                <input type="hidden" name="student_index" value="<?= $student_index ?>">

                                <div class="input-grade">
                                    <p>Grade</p>
                                    <input type="number" name="points_earned" min="0" max="<?= $points ?>"
                                        value="<?= htmlspecialchars($savedGrade) ?>" placeholder="/<?= $points ?>">
                                </div>



                            </div>

                            <div class="private-comments">
                                <p>Private Comments</p>
                                <input type="text" name="feedback" value="<?= htmlspecialchars($savedFeedback) ?>"
                                    placeholder="Add private comment...">
                                <div class="post">
                                    <button type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>