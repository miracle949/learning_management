<?php

require_once "../app/models/SuperAdmin.php";

class SuperAdminController
{
    private $superAdminModel;

    public function __construct()
    {
        $this->superAdminModel = new SuperAdmin();
    }

    public function super_index()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        require_once "../app/models/Admin.php";
        $adminModel = new Admin();

        $totalStudents = $adminModel->getTotalStudents();
        $totalTeachers = $adminModel->getTotalTeachers();
        $totalSubjects = $adminModel->getTotalSubjects();
        $totalSections = $adminModel->getTotalSections();
        $totalPendingApprovals = $adminModel->getTotalPendingApprovals();
        $pendingStudents = $adminModel->getPendingStudents();
        $recentEnrollments = $adminModel->getRecentEnrollments(5);
        $announcements = $adminModel->getRecentAnnouncements(5);
        $teacherWorkload = $adminModel->getTeacherWorkload();
        $enrollmentByGrade = $adminModel->getEnrollmentByGrade();
        $approvedCount = $adminModel->countStudentsByStatus('Approved');
        $pendingCount = $adminModel->countStudentsByStatus('Pending');

        $activityLogs = $this->superAdminModel->getActivityLogs(15);

        extract([
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalSubjects' => $totalSubjects,
            'totalSections' => $totalSections,
            'totalPendingApprovals' => $totalPendingApprovals,
            'pendingStudents' => $pendingStudents,
            'recentEnrollments' => $recentEnrollments,
            'announcements' => $announcements,
            'teacherWorkload' => $teacherWorkload,
            'enrollmentByGrade' => $enrollmentByGrade,
            'activityLogs' => $activityLogs,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
        ]);

        require "../app/view/super_admin.php";
    }

    public function activities()
    {
        $gradeLevels = $this->superAdminModel->getAllGradeLevels();
        $selectedGrade = isset($_GET['grade_id']) ? (int) $_GET['grade_id'] : 0;
        $subjects = $selectedGrade
            ? $this->superAdminModel->getSubjectsByGradeLevel($selectedGrade)
            : $this->superAdminModel->getAllSubjects();
        include "../super_admin_folder/activities.php";
    }

    public function create_activities()
    {
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
        $subject = $subjectId ? $this->superAdminModel->getSubjectById($subjectId) : null;
        include "../super_admin_folder/create_activities.php";
    }

    // ============================================================
    // SAVE INTERACTIVE MODULE
    // ------------------------------------------------------------
    // Every lesson now submits ONE ordered "blocks" array instead of
    // separate video/image/activity/quiz/flashcard arrays. Each block
    // carries its own type (text/image/video/quiz/activity/flashcard)
    // and its index in that array IS its sort_order — so whatever
    // order the admin arranged blocks in the builder UI is exactly
    // what gets saved and, later, exactly what gets rendered.
    // ============================================================
    public function save_interactive_module()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id = (int) ($_POST['subject_id'] ?? 0);

        if (!$subject_id) {
            header("Location: /learning_management/public/?url=activities");
            exit;
        }

        $skipped = ['im_modules' => [], 'lessons' => []];
        $baseUpload = dirname(__DIR__, 2) . '/uploads/';
        $imageDir = $baseUpload . 'lessons/images/';

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        $moduleTitles = $_POST['module_title'] ?? [];
        $moduleDescriptions = $_POST['module_description'] ?? [];
        $blocksData = $_POST['blocks'] ?? [];
        $blockImages = $_FILES['block_image'] ?? null;

        foreach ($moduleTitles as $modIdx => $modTitle) {
            if (empty(trim($modTitle)))
                continue;

            $existingIMCount = $this->superAdminModel->countInteractiveModules($subject_id);
            $imModuleNumber = $existingIMCount + $modIdx + 1;
            $numberedIMTitle = 'Module ' . $imModuleNumber . ': ' . trim($modTitle);

            $imResult = $this->superAdminModel->insertInteractiveModule(
                $subject_id,
                $numberedIMTitle,
                trim($moduleDescriptions[$modIdx] ?? '')
            );
            $interactiveModuleId = $imResult['id'] ?? null;
            if (!$interactiveModuleId)
                continue;
            if ($imResult['existed'])
                $skipped['im_modules'][] = $numberedIMTitle;

            $lessonTitles = $_POST['lesson_title'][$modIdx] ?? [];
            $lessonTopics = $_POST['lesson_topic'][$modIdx] ?? [];

            foreach ($lessonTitles as $lesIdx => $lesTitle) {
                if (empty(trim($lesTitle)))
                    continue;

                $existingLesCount = $this->superAdminModel->countLessons($interactiveModuleId);
                $lessonNumber = $existingLesCount + $lesIdx + 1;
                $numberedLesTitle = 'Lesson ' . $lessonNumber . ': ' . trim($lesTitle);

                $lesResult = $this->superAdminModel->insertLesson(
                    $interactiveModuleId,
                    $numberedLesTitle,
                    trim($lessonTopics[$lesIdx] ?? '')
                );
                $lessonId = $lesResult['id'] ?? null;
                if (!$lessonId)
                    continue;
                if ($lesResult['existed'])
                    $skipped['lessons'][] = $numberedLesTitle . ' (in ' . $numberedIMTitle . ')';

                $lessonBlocks = $blocksData[$modIdx][$lesIdx] ?? [];

                // Every block (text, image, video, quiz, activity, flashcard)
                // gets its own row in tbl_interactive_contents, tagged with
                // sort_order = its position in the builder — so the exact
                // order the admin arranged blocks in is preserved and can be
                // reconstructed later with ORDER BY sort_order.
                foreach ($lessonBlocks as $blockIdx => $block) {
                    $type = $block['type'] ?? '';
                    $sortOrder = (int) $blockIdx;

                    switch ($type) {

                        // ── TEXT ──
                        // 'heading' is optional — reuses the existing `title`
                        // column on tbl_interactive_contents (no schema change
                        // needed) as the section label, e.g. "The Software
                        // Layer", rendered above this block's body text.
                        case 'text':
                            $text = trim($block['text'] ?? '');
                            if ($text === '')
                                break;
                            $heading = trim($block['heading'] ?? '');
                            $this->superAdminModel->insertInteractiveContent($lessonId, 'text', [
                                'title' => $heading !== '' ? $heading : null,
                                'body' => $text,
                                'sort_order' => $sortOrder,
                            ]);
                            break;

                        // ── IMAGE ──
                        case 'image':
                            $file = [
                                'name' => $blockImages['name'][$modIdx][$lesIdx][$blockIdx] ?? null,
                                'tmp_name' => $blockImages['tmp_name'][$modIdx][$lesIdx][$blockIdx] ?? null,
                                'error' => $blockImages['error'][$modIdx][$lesIdx][$blockIdx] ?? UPLOAD_ERR_NO_FILE,
                                'size' => $blockImages['size'][$modIdx][$lesIdx][$blockIdx] ?? 0,
                            ];
                            if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || empty($file['name']))
                                break;
                            if (($file['size'] ?? 0) > 5 * 1024 * 1024)
                                break;
                            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                break;
                            $uniqueName = uniqid('img_') . '.' . $ext;
                            if (move_uploaded_file($file['tmp_name'], $imageDir . $uniqueName)) {
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'image', [
                                    'title' => trim($block['caption'] ?? ''),
                                    'file_path' => '/learning_management/uploads/lessons/images/' . $uniqueName,
                                    'file_name' => $file['name'],
                                    'file_type' => $ext,
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;

                        // ── VIDEO ──
                        case 'video':
                            $vTitle = trim($block['video_title'] ?? '');
                            $vUrl = trim($block['video_url'] ?? '');
                            if ($vTitle === '' || $vUrl === '')
                                break;
                            $this->superAdminModel->insertInteractiveContent($lessonId, 'video', [
                                'title' => $vTitle,
                                'file_path' => $vUrl,
                                'file_type' => 'url',
                                'sort_order' => $sortOrder,
                            ]);
                            break;

                        // ── QUIZ (one block = one quiz, N questions) ──
                        case 'quiz':
                            $qzTitle = trim($block['quiz_title'] ?? '');
                            if ($qzTitle === '')
                                break;
                            $questions = $block['questions'] ?? [];
                            foreach ($questions as $q) {
                                $qText = trim($q['text'] ?? '');
                                if ($qText === '')
                                    continue;
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'quiz', [
                                    'title' => $qzTitle,
                                    'instructions' => trim($block['quiz_instructions'] ?? ''),
                                    'passing_score' => (int) ($block['quiz_passing_score'] ?? 75),
                                    'question' => $qText,
                                    'question_type' => 'multiple_choice',
                                    'choice_a' => trim($q['choice_a'] ?? '') ?: null,
                                    'choice_b' => trim($q['choice_b'] ?? '') ?: null,
                                    'choice_c' => trim($q['choice_c'] ?? '') ?: null,
                                    'choice_d' => trim($q['choice_d'] ?? '') ?: null,
                                    'correct_ans' => strtolower($q['correct'] ?? 'a'),
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;

                        // ── ACTIVITY (one block = one activity, N questions) ──
                        case 'activity':
                            $actTitle = trim($block['activity_title'] ?? '');
                            if ($actTitle === '')
                                break;
                            $questions = $block['questions'] ?? [];
                            foreach ($questions as $q) {
                                $qText = trim($q['text'] ?? '');
                                if ($qText === '')
                                    continue;
                                $qType = $q['type'] ?? 'essay';
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'activity', [
                                    'title' => $actTitle,
                                    'instructions' => trim($block['activity_instructions'] ?? ''),
                                    'total_points' => (int) ($block['activity_points'] ?? 0),
                                    'question' => $qText,
                                    'question_type' => $qType,
                                    'model_answer' => $qType === 'essay' ? (trim($q['essay_answer'] ?? '') ?: null) : null,
                                    'choice_a' => $qType === 'multiple_choice' ? (trim($q['choice_a'] ?? '') ?: null) : null,
                                    'choice_b' => $qType === 'multiple_choice' ? (trim($q['choice_b'] ?? '') ?: null) : null,
                                    'choice_c' => $qType === 'multiple_choice' ? (trim($q['choice_c'] ?? '') ?: null) : null,
                                    'choice_d' => $qType === 'multiple_choice' ? (trim($q['choice_d'] ?? '') ?: null) : null,
                                    'correct_ans' => $qType === 'multiple_choice' ? (strtolower($q['correct'] ?? 'a') ?: null) : null,
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;

                        // ── FLASHCARD (one block = N cards) ──
                        case 'flashcard':
                            $cards = $block['cards'] ?? [];
                            foreach ($cards as $c) {
                                $front = trim($c['front'] ?? '');
                                $back = trim($c['back'] ?? '');
                                if ($front === '' || $back === '')
                                    continue;
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'flashcard', [
                                    'card_type' => $c['card_type'] ?? 'term_definition',
                                    'card_front' => $front,
                                    'card_back' => $back,
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;
                    }
                }
            }
        }

        $hasSkipped = !empty($skipped['im_modules']) || !empty($skipped['lessons']);
        if ($hasSkipped)
            $_SESSION['save_skipped'] = $skipped;
        $_SESSION['save_success'] = true;

        header("Location: /learning_management/public/?url=activities");
        exit;
    }

    public function save_subject()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $subjectId = (int) ($_POST['subject_id'] ?? 0);
        $name = trim($_POST['subject_name'] ?? '');
        $code = trim($_POST['subject_code'] ?? '');
        $description = trim($_POST['subject_description'] ?? '');
        $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);

        if (!$name || !$code || !$gradeLevelId) {
            header("Location: /learning_management/public/?url=activities");
            exit;
        }

        $imagePath = null;
        $file = $_FILES['subject_image'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $uploadDir = dirname(__DIR__, 2) . '/uploads/subjects/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);
                $uniqueName = uniqid('subj_') . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $uniqueName)) {
                    $imagePath = 'uploads/subjects/' . $uniqueName;
                }
            }
        }

        if ($subjectId) {
            $this->superAdminModel->updateSubject($subjectId, $name, $code, $description, $gradeLevelId, $imagePath);
        } else {
            $this->superAdminModel->createSubject($name, $code, $description, $gradeLevelId, $imagePath);
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=activities");
        exit;
    }
}