<?php

require_once "../app/models/User.php";
require_once "../app/models/Teacher.php";

class TeacherController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $user_id = $_SESSION['user_id'];
        $teacher_id = $this->user->getTeacherId($user_id);
        $teacherInfo = $this->user->getTeacherInfo($user_id);
        $teacherModel = new Teacher();
        $classes = $teacherModel->getTeacherClassesPerSection($teacher_id);
        $stats = $this->user->getTeacherStats($teacher_id);
        extract(['teacherInfo' => $teacherInfo, 'classes' => $classes, 'stats' => $stats]);
        require "../app/view/teacher.php";
    }

    // ============================================================
    // VIEW CLASS
    // ============================================================
    public function viewClass()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);

        $teacherModel = new Teacher();

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $classInfo = $teacherModel->getClassInfo($subject_id, $grade_level_id, $section_id);

        $cfModules = $teacherModel->getModules($subject_id, $teacher_id);
        foreach ($cfModules as &$mod) {
            $mod['materials'] = $teacherModel->getMaterialsByModule($mod['id']);
        }
        unset($mod);

        $imModules = $teacherModel->getInteractiveModulesWithCount($subject_id, $teacher_id);
        foreach ($imModules as &$im) {
            $im['lessons'] = $teacherModel->getLessonsByModule($im['id']);
        }
        unset($im);

        $studentCount = $teacherModel->getStudentCountBySection($subject_id, $section_id);

        $totalLessons = 0;
        foreach ($imModules as $im) {
            $totalLessons += (int) $im['lesson_count'];
        }

        extract([
            'subject_id' => $subject_id,
            'grade_level_id' => $grade_level_id,
            'section_id' => $section_id,
            'classInfo' => $classInfo,
            'cfModules' => $cfModules,
            'imModules' => $imModules,
            'studentCount' => $studentCount,
            'totalLessons' => $totalLessons,
            'teacherModel' => $teacherModel,
        ]);
        require "../teacher_folder/records.php";
    }

    public function lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);

        $teacherModel = new Teacher();
        $classInfo = $teacherModel->getClassInfo($subject_id, $grade_level_id, $section_id);

        extract([
            'subject_id' => $subject_id,
            'grade_level_id' => $grade_level_id,
            'section_id' => $section_id,
            'classInfo' => $classInfo,
        ]);

        require "../teacher_folder/lessons.php";
    }

    public function announce()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);
        header("Location: /learning_management/public/?url=teacher_class&id=$subject_id&grade_id=$grade_level_id&section_id=$section_id");
        exit;
    }

    public function save_announcement()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        if ($subject_id && $title && $body && $teacher_id) {
            $teacherModel->insertAnnouncement($subject_id, $teacher_id, $title, $body);
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=teacher_class&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}");
        exit;
    }

    public function save_assignment()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $titles = $_POST['assignment_title'] ?? [];
        $descriptions = $_POST['assignment_description'] ?? [];
        $tasks = $_POST['assignment_task'] ?? [];
        $instructions = $_POST['assignment_instructions'] ?? [];
        $types = $_POST['assignment_type'] ?? [];
        $due_dates = $_POST['assignment_due_date'] ?? [];
        $points_arr = $_POST['assignment_points'] ?? [];

        // Restructure $_FILES for easier per-index access
        $files = $_FILES['assignment_file'] ?? [];

        foreach ($titles as $i => $title) {
            if (empty(trim($title)))
                continue;

            // Handle file upload per assignment index
            $fileName = null;
            $filePath = null;
            $fileType = null;

            if (!empty($files['tmp_name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = basename($files['name'][$i]);
                $fileType = $files['type'][$i];
                $uploadDir = __DIR__ . '/../../uploads/assignments/';

                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);

                $uniqueName = uniqid() . '_' . $originalName;
                $destPath = $uploadDir . $uniqueName;

                if (move_uploaded_file($files['tmp_name'][$i], $destPath)) {
                    $fileName = $originalName;
                    $filePath = 'uploads/assignments/' . $uniqueName;
                }
            }

            $teacherModel->insertAssignment(
                $subject_id,
                $teacher_id,
                trim($types[$i] ?? 'seatwork'),
                trim($title),
                trim($descriptions[$i] ?? ''),
                trim($tasks[$i] ?? ''),
                trim($instructions[$i] ?? ''),
                trim($due_dates[$i] ?? '') ?: null,
                (int) ($points_arr[$i] ?? 100),
                $fileName,
                $filePath,
                $fileType
            );
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=teacher_class&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}");
        exit;
    }

    public function upload()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);
        header("Location: /learning_management/public/?url=lessons&id=$subject_id&grade_id=$grade_level_id&section_id=$section_id");
        exit;
    }

    // ============================================================
    // SAVE LESSONS
    // All content (quiz, activity, flashcard, video, image)
    // now goes into interactive_contents table
    // ============================================================
    public function save_lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        // Instantiate FIRST before using it
        $teacherModel = new Teacher();

        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        if (!$teacher_id) {
            die("Teacher record not found. Please contact your administrator.");
        }

        // Now get posted_by (user_id) from teacher_id
        $posted_by = $teacherModel->getUserIdByTeacherId($teacher_id);

        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }
        if (!$teacher_id) {
            die("Teacher record not found. Please contact your administrator.");
        }

        if (!$subject_id) {
            header("Location: /learning_management/public/?url=lessons&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}");
            exit;
        }

        $skipped = ['cf_modules' => [], 'im_modules' => [], 'lessons' => []];
        $baseUpload = dirname(__DIR__, 2) . '/uploads/';
        $pdfDir = $baseUpload . 'modules/pdfs/';
        $imageDir = $baseUpload . 'lessons/images/';
        $videoDir = $baseUpload . 'lessons/videos/';

        foreach ([$pdfDir, $imageDir, $videoDir] as $dir) {
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
        }

        // ── PART 1: CLASSES FEED MODULES ──
        $cfTitles = $_POST['cf_module_title'] ?? [];
        $cfDescriptions = $_POST['cf_module_description'] ?? [];

        foreach ($cfTitles as $cfIdx => $cfTitle) {
            if (empty(trim($cfTitle)))
                continue;

            $existingCFCount = $teacherModel->countModules($subject_id);
            $cfModuleNumber = $existingCFCount + $cfIdx + 1;
            $numberedCFTitle = 'Module ' . $cfModuleNumber . ': ' . trim($cfTitle);

            $fileName = $filePath = $fileType = null;
            $fileSize = 0;

            $pdfFiles = $_FILES['cf_module_pdf'] ?? [];
            $pdfNames = $pdfFiles['name'][$cfIdx] ?? [];
            $pdfTmps = $pdfFiles['tmp_name'][$cfIdx] ?? [];
            $pdfErrors = $pdfFiles['error'][$cfIdx] ?? [];
            $pdfSizes = $pdfFiles['size'][$cfIdx] ?? [];

            foreach ($pdfNames as $pIdx => $pdfName) {
                if (($pdfErrors[$pIdx] ?? 1) !== UPLOAD_ERR_OK)
                    continue;
                if (empty($pdfName) || ($pdfSizes[$pIdx] ?? 0) > 50 * 1024 * 1024)
                    continue;
                $ext = strtolower(pathinfo($pdfName, PATHINFO_EXTENSION));
                if (!in_array($ext, ['pdf', 'ppt', 'pptx', 'doc', 'docx']))
                    continue;
                $uniqueName = uniqid('mod_') . '.' . $ext;
                if (move_uploaded_file($pdfTmps[$pIdx], $pdfDir . $uniqueName)) {
                    $fileName = $pdfName;
                    $filePath = '/learning_management/uploads/modules/pdfs/' . $uniqueName;
                    $fileType = $ext;
                    $fileSize = (int) ($pdfSizes[$pIdx] ?? 0);
                    break;
                }
            }

            $modResult = $teacherModel->insertModule(
                $subject_id,
                $numberedCFTitle,
                trim($cfDescriptions[$cfIdx] ?? ''),
                $teacher_id,
                $fileName,
                $filePath,
                $fileType,
                $fileSize
            );

            if ($modResult['existed']) {
                $skipped['cf_modules'][] = $numberedCFTitle;
            }
        }

        // ── PART 2: INTERACTIVE MODULES ──
        $moduleTitles = $_POST['module_title'] ?? [];
        $moduleContents = $_POST['module_content'] ?? [];

        foreach ($moduleTitles as $modIdx => $modTitle) {
            if (empty(trim($modTitle)))
                continue;

            $existingIMCount = $teacherModel->countInteractiveModules($subject_id);
            $imModuleNumber = $existingIMCount + $modIdx + 1;
            $numberedIMTitle = 'Module ' . $imModuleNumber . ': ' . trim($modTitle);

            $imResult = $teacherModel->insertInteractiveModule(
                $subject_id,
                $numberedIMTitle,
                trim($moduleContents[$modIdx] ?? ''),
                $imModuleNumber,
                $teacher_id
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

                $existingLesCount = $teacherModel->countLessons($interactiveModuleId);
                $lessonNumber = $existingLesCount + $lesIdx + 1;
                $numberedLesTitle = 'Lesson ' . $lessonNumber . ': ' . trim($lesTitle);

                $lesResult = $teacherModel->insertLesson(
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

                // ── VIDEOS → interactive_contents ──
                $videoTitles = $_POST['video_title'][$modIdx][$lesIdx] ?? [];
                $videoUrls = $_POST['video_url'][$modIdx][$lesIdx] ?? [];

                foreach ($videoTitles as $vIdx => $vTitle) {
                    if (empty(trim($vTitle)) || empty(trim($videoUrls[$vIdx] ?? '')))
                        continue;
                    $teacherModel->insertInteractiveContent($lessonId, 'video', [
                        'title' => trim($vTitle),
                        'file_path' => trim($videoUrls[$vIdx]),
                        'file_type' => 'url',
                    ]);
                }

                // ── IMAGES → interactive_contents ──
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
                        $teacherModel->insertInteractiveContent($lessonId, 'image', [
                            'title' => trim($imageTitles[$iIdx] ?? ''),
                            'file_path' => '/learning_management/uploads/lessons/images/' . $uniqueName,
                            'file_name' => $imgFileName,
                            'file_type' => $ext,
                        ]);
                    }
                }

                // ── ACTIVITIES → interactive_contents ──
                $actTitles = $_POST['activity_title'][$modIdx][$lesIdx] ?? [];
                $actInstructions = $_POST['activity_instructions'][$modIdx][$lesIdx] ?? [];
                $actPoints = $_POST['activity_points'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($actTitles) as $loopIdx => $aIdx) {
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

                        $teacherModel->insertInteractiveContent($lessonId, 'activity', [
                            'title' => trim($aTitle),
                            'instructions' => trim($actInstructions[$aIdx] ?? ''),
                            'total_points' => (int) ($actPoints[$aIdx] ?? 0),
                            'question' => trim($qText),
                            'question_type' => $qType,
                            'model_answer' => $qType === 'essay'
                                ? (trim($qAnswers[$qIdx] ?? '') ?: null)
                                : null,
                            'choice_a' => $qType === 'multiple_choice'
                                ? (trim($qChoiceA[$qIdx] ?? '') ?: null)
                                : null,
                            'choice_b' => $qType === 'multiple_choice'
                                ? (trim($qChoiceB[$qIdx] ?? '') ?: null)
                                : null,
                            'choice_c' => $qType === 'multiple_choice'
                                ? (trim($qChoiceC[$qIdx] ?? '') ?: null)
                                : null,
                            'choice_d' => $qType === 'multiple_choice'
                                ? (trim($qChoiceD[$qIdx] ?? '') ?: null)
                                : null,
                            'correct_ans' => $qType === 'multiple_choice'
                                ? (strtolower($qCorrect[$qIdx] ?? 'a') ?: null)
                                : null,
                        ]);
                    }
                }

                // ── QUIZZES → interactive_contents ──
                $quizTitles = $_POST['quiz_title'][$modIdx][$lesIdx] ?? [];
                $quizInstruct = $_POST['quiz_instructions'][$modIdx][$lesIdx] ?? [];
                $quizPassing = $_POST['quiz_passing_score'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($quizTitles) as $loopIdx => $qzIdx) {
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

                        $teacherModel->insertInteractiveContent($lessonId, 'quiz', [
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

                // ── FLASHCARDS → interactive_contents ──
                $fcFronts = $_POST['flashcard_front'][$modIdx][$lesIdx] ?? [];
                $fcBacks = $_POST['flashcard_back'][$modIdx][$lesIdx] ?? [];
                $fcTypes = $_POST['flashcard_type'][$modIdx][$lesIdx] ?? [];

                foreach ($fcFronts as $fcIdx => $fcFront) {
                    if (empty(trim($fcFront)) || empty(trim($fcBacks[$fcIdx] ?? '')))
                        continue;
                    $teacherModel->insertInteractiveContent($lessonId, 'flashcard', [
                        'card_type' => $fcTypes[$fcIdx] ?? 'term_definition',
                        'card_front' => trim($fcFront),
                        'card_back' => trim($fcBacks[$fcIdx]),
                    ]);
                }
            }
        }

        $hasSkipped = !empty($skipped['cf_modules']) || !empty($skipped['im_modules']) || !empty($skipped['lessons']);
        if ($hasSkipped)
            $_SESSION['save_skipped'] = $skipped;
        $_SESSION['save_success'] = true;

        header("Location: /learning_management/public/?url=teacher_class&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}");
        exit;
    }
}