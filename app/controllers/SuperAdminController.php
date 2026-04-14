<?php

require_once "../app/models/SuperAdmin.php";

class SuperAdminController
{
    private $superAdminModel;

    public function __construct()
    {
        $this->superAdminModel = new SuperAdmin();
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
    // SAVE INTERACTIVE MODULE (moved from TeacherController)
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
        $videoDir = $baseUpload . 'lessons/videos/';

        foreach ([$imageDir, $videoDir] as $dir) {
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
        }

        // ── INTERACTIVE MODULES ──
        $moduleTitles = $_POST['module_title'] ?? [];
        $moduleContents = $_POST['module_content'] ?? [];

        foreach ($moduleTitles as $modIdx => $modTitle) {
            if (empty(trim($modTitle)))
                continue;

            $existingIMCount = $this->superAdminModel->countInteractiveModules($subject_id);
            $imModuleNumber = $existingIMCount + $modIdx + 1;
            $numberedIMTitle = 'Module ' . $imModuleNumber . ': ' . trim($modTitle);

            $created_by = $_SESSION['user_id'] ?? null;

            $imResult = $this->superAdminModel->insertInteractiveModule(
                $subject_id,
                $numberedIMTitle,
                trim($moduleContents[$modIdx] ?? '')
                // no more $created_by — teacher_id is always NULL for super admin
            );
            $interactiveModuleId = $imResult['id'] ?? null;
            if (!$interactiveModuleId)
                continue;
            if ($imResult['existed'])
                $skipped['im_modules'][] = $numberedIMTitle;

            $lessonTitles = $_POST['lesson_title'][$modIdx] ?? [];
            $lessonTopics = $_POST['lesson_topic'][$modIdx] ?? [];
            $lessonContents = $_POST['lesson_content'][$modIdx] ?? [];

            foreach ($lessonTitles as $lesIdx => $lesTitle) {
                if (empty(trim($lesTitle)))
                    continue;

                $existingLesCount = $this->superAdminModel->countLessons($interactiveModuleId);
                $lessonNumber = $existingLesCount + $lesIdx + 1;
                $numberedLesTitle = 'Lesson ' . $lessonNumber . ': ' . trim($lesTitle);

                $lesResult = $this->superAdminModel->insertLesson(
                    $interactiveModuleId,
                    $numberedLesTitle,
                    trim($lessonTopics[$lesIdx] ?? ''),
                    trim($lessonContents[$lesIdx] ?? '')
                );
                $lessonId = $lesResult['id'] ?? null;
                if (!$lessonId)
                    continue;
                if ($lesResult['existed']) {
                    $skipped['lessons'][] = $numberedLesTitle . ' (in ' . $numberedIMTitle . ')';
                }

                // ── VIDEOS ──
                $videoTitles = $_POST['video_title'][$modIdx][$lesIdx] ?? [];
                $videoUrls = $_POST['video_url'][$modIdx][$lesIdx] ?? [];
                foreach ($videoTitles as $vIdx => $vTitle) {
                    if (empty(trim($vTitle)) || empty(trim($videoUrls[$vIdx] ?? '')))
                        continue;
                    $this->superAdminModel->insertInteractiveContent($lessonId, 'video', [
                        'title' => trim($vTitle),
                        'file_path' => trim($videoUrls[$vIdx]),
                        'file_type' => 'url',
                    ]);
                }

                // ── IMAGES ──
                $imageFiles = $_FILES['image_file'] ?? [];
                $imageTitles = $_POST['image_title'][$modIdx][$lesIdx] ?? [];
                $fileNames = $imageFiles['name'][$modIdx][$lesIdx] ?? [];
                $fileTmps = $imageFiles['tmp_name'][$modIdx][$lesIdx] ?? [];
                $fileErrors = $imageFiles['error'][$modIdx][$lesIdx] ?? [];
                $fileSizes = $imageFiles['size'][$modIdx][$lesIdx] ?? [];

                foreach ($fileNames as $iIdx => $imgFileName) {
                    if (($fileErrors[$iIdx] ?? 1) !== UPLOAD_ERR_OK)
                        continue;
                    if (empty($imgFileName) || ($fileSizes[$iIdx] ?? 0) > 5 * 1024 * 1024)
                        continue;
                    $ext = strtolower(pathinfo($imgFileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        continue;
                    $uniqueName = uniqid('img_') . '.' . $ext;
                    if (move_uploaded_file($fileTmps[$iIdx], $imageDir . $uniqueName)) {
                        $this->superAdminModel->insertInteractiveContent($lessonId, 'image', [
                            'title' => trim($imageTitles[$iIdx] ?? ''),
                            'file_path' => '/learning_management/uploads/lessons/images/' . $uniqueName,
                            'file_name' => $imgFileName,
                            'file_type' => $ext,
                        ]);
                    }
                }

                // ── ACTIVITIES ──
                $actTitles = $_POST['activity_title'][$modIdx][$lesIdx] ?? [];
                $actInstructions = $_POST['activity_instructions'][$modIdx][$lesIdx] ?? [];
                $actPoints = $_POST['activity_points'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($actTitles) as $aIdx) {
                    $aTitle = $actTitles[$aIdx];
                    if (empty(trim($aTitle)))
                        continue;

                    $qTypes = $_POST['activity_question_type'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qTexts = $_POST['activity_question_text'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qAnswers = $_POST['activity_essay_answer'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceA = $_POST['activity_choice_a'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceB = $_POST['activity_choice_b'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceC = $_POST['activity_choice_c'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceD = $_POST['activity_choice_d'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qCorrect = $_POST['activity_correct_answer'][$modIdx][$lesIdx][$aIdx] ?? [];

                    foreach ($qTexts as $qIdx => $qText) {
                        if (empty(trim($qText)))
                            continue;
                        $qType = $qTypes[$qIdx] ?? 'essay';
                        $this->superAdminModel->insertInteractiveContent($lessonId, 'activity', [
                            'title' => trim($aTitle),
                            'instructions' => trim($actInstructions[$aIdx] ?? ''),
                            'total_points' => (int) ($actPoints[$aIdx] ?? 0),
                            'question' => trim($qText),
                            'question_type' => $qType,
                            'model_answer' => $qType === 'essay'
                                ? (trim($qAnswers[$qIdx] ?? '') ?: null) : null,
                            'choice_a' => $qType === 'multiple_choice'
                                ? (trim($qChoiceA[$qIdx] ?? '') ?: null) : null,
                            'choice_b' => $qType === 'multiple_choice'
                                ? (trim($qChoiceB[$qIdx] ?? '') ?: null) : null,
                            'choice_c' => $qType === 'multiple_choice'
                                ? (trim($qChoiceC[$qIdx] ?? '') ?: null) : null,
                            'choice_d' => $qType === 'multiple_choice'
                                ? (trim($qChoiceD[$qIdx] ?? '') ?: null) : null,
                            'correct_ans' => $qType === 'multiple_choice'
                                ? (strtolower($qCorrect[$qIdx] ?? 'a') ?: null) : null,
                        ]);
                    }
                }

                // ── QUIZZES ──
                $quizTitles = $_POST['quiz_title'][$modIdx][$lesIdx] ?? [];
                $quizInstruct = $_POST['quiz_instructions'][$modIdx][$lesIdx] ?? [];
                $quizPassing = $_POST['quiz_passing_score'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($quizTitles) as $qzIdx) {
                    $qzTitle = $quizTitles[$qzIdx];
                    if (empty(trim($qzTitle)))
                        continue;

                    $qqTexts = $_POST['question_text'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceA = $_POST['choice_a'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceB = $_POST['choice_b'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceC = $_POST['choice_c'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceD = $_POST['choice_d'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqCorrect = $_POST['correct_answer'][$modIdx][$lesIdx][$qzIdx] ?? [];

                    foreach ($qqTexts as $qqIdx => $qqText) {
                        if (empty(trim($qqText)))
                            continue;
                        $this->superAdminModel->insertInteractiveContent($lessonId, 'quiz', [
                            'title' => trim($qzTitle),
                            'instructions' => trim($quizInstruct[$qzIdx] ?? ''),
                            'passing_score' => (int) ($quizPassing[$qzIdx] ?? 75),
                            'question' => trim($qqText),
                            'question_type' => 'multiple_choice',
                            'choice_a' => trim($qqChoiceA[$qqIdx] ?? '') ?: null,
                            'choice_b' => trim($qqChoiceB[$qqIdx] ?? '') ?: null,
                            'choice_c' => trim($qqChoiceC[$qqIdx] ?? '') ?: null,
                            'choice_d' => trim($qqChoiceD[$qqIdx] ?? '') ?: null,
                            'correct_ans' => strtolower($qqCorrect[$qqIdx] ?? 'a'),
                        ]);
                    }
                }

                // ── FLASHCARDS ──
                $fcFronts = $_POST['flashcard_front'][$modIdx][$lesIdx] ?? [];
                $fcBacks = $_POST['flashcard_back'][$modIdx][$lesIdx] ?? [];
                $fcTypes = $_POST['flashcard_type'][$modIdx][$lesIdx] ?? [];

                foreach ($fcFronts as $fcIdx => $fcFront) {
                    if (empty(trim($fcFront)) || empty(trim($fcBacks[$fcIdx] ?? '')))
                        continue;
                    $this->superAdminModel->insertInteractiveContent($lessonId, 'flashcard', [
                        'card_type' => $fcTypes[$fcIdx] ?? 'term_definition',
                        'card_front' => trim($fcFront),
                        'card_back' => trim($fcBacks[$fcIdx]),
                    ]);
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

        // Handle image upload
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
            // UPDATE
            $this->superAdminModel->updateSubject($subjectId, $name, $code, $description, $gradeLevelId, $imagePath);
        } else {
            // CREATE
            $this->superAdminModel->createSubject($name, $code, $description, $gradeLevelId, $imagePath);
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=activities");
        exit;
    }
}