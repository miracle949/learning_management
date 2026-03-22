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
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { header("Location: ?url=login"); exit; }
        $user_id    = $_SESSION['user_id'];
        $teacher_id = $this->user->getTeacherId($user_id);
        $teacherInfo = $this->user->getTeacherInfo($user_id);
        $classes     = $this->user->getAssignedClasses($teacher_id);
        $stats       = $this->user->getTeacherStats($teacher_id);
        extract(['teacherInfo' => $teacherInfo, 'classes' => $classes, 'stats' => $stats]);
        require "../app/view/teacher.php";
    }

    public function viewClass()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { header("Location: ?url=login"); exit; }
        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);

        $teacherModel = new Teacher();
        $classInfo    = $this->user->getClassInfo($subject_id, $grade_level_id);

        // Fetch modules and interactive modules for this subject
        $cfModules  = $teacherModel->getModules($subject_id);
        $imModules  = $teacherModel->getInteractiveModulesWithCount($subject_id);
        $studentCount = $teacherModel->getStudentCount($subject_id);

        // Total lesson count across all interactive modules
        $totalLessons = 0;
        foreach ($imModules as $im) {
            $totalLessons += (int)$im['lesson_count'];
        }

        extract([
            'subject_id'     => $subject_id,
            'grade_level_id' => $grade_level_id,
            'classInfo'      => $classInfo,
            'cfModules'      => $cfModules,
            'imModules'      => $imModules,
            'studentCount'   => $studentCount,
            'totalLessons'   => $totalLessons,
        ]);
        require "../teacher_folder/records.php";
    }

    public function lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { header("Location: ?url=login"); exit; }
        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);
        extract(['subject_id' => $subject_id, 'grade_level_id' => $grade_level_id]);
        require "../teacher_folder/lessons.php";
    }

    public function announce()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { header("Location: ?url=login"); exit; }
        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title   = htmlspecialchars($_POST['title']   ?? '');
            $content = htmlspecialchars($_POST['content'] ?? '');
        }
        header("Location: /learning_management/public/?url=teacher_class&id=$subject_id&grade_id=$grade_level_id");
        exit;
    }

    public function upload()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { header("Location: ?url=login"); exit; }
        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);
        header("Location: /learning_management/public/?url=lessons&id=$subject_id&grade_id=$grade_level_id");
        exit;
    }

    // ============================================================
    // SAVE LESSONS — handles BOTH classes feed AND interactive modules
    // ============================================================
    public function save_lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') { header("Location: ?url=login"); exit; }

        $teacherModel   = new Teacher();
        $subject_id     = (int)($_POST['subject_id']     ?? 0);
        $grade_level_id = (int)($_POST['grade_level_id'] ?? 0);
        $teacher_id     = $_SESSION['teacher_id'] ?? 1;

        if (!$subject_id) {
            header("Location: /learning_management/public/?url=lessons&id={$subject_id}&grade_id={$grade_level_id}");
            exit;
        }

        // Track skipped (already existing) items to show in modal
        $skipped = ['cf_modules' => [], 'im_modules' => [], 'lessons' => []];

        // Upload folders
        $baseUpload = dirname(__DIR__, 2) . '/uploads/';
        $pdfDir     = $baseUpload . 'modules/pdfs/';
        $imageDir   = $baseUpload . 'lessons/images/';
        foreach ([$pdfDir, $imageDir] as $dir) {
            if (!is_dir($dir)) mkdir($dir, 0755, true);
        }

        // ============================================================
        // PART 1 — CLASSES FEED (modules + module_materials)
        // ============================================================
        $cfTitles       = $_POST['cf_module_title']       ?? [];
        $cfDescriptions = $_POST['cf_module_description'] ?? [];

        foreach ($cfTitles as $cfIdx => $cfTitle) {
            if (empty(trim($cfTitle))) continue;

            // Auto-number: count existing modules in DB + position in current form
            $existingCFCount = $teacherModel->countModules($subject_id);
            $cfModuleNumber  = $existingCFCount + $cfIdx + 1;
            $numberedCFTitle = 'Module ' . $cfModuleNumber . ': ' . trim($cfTitle);

            $modResult = $teacherModel->insertModule(
                $subject_id,
                $numberedCFTitle,
                trim($cfDescriptions[$cfIdx] ?? ''),
                $teacher_id,
                $cfModuleNumber
            );

            $moduleId = $modResult['id'] ?? null;
            if (!$moduleId) continue;

            // Track if this classes feed module already existed
            if ($modResult['existed']) {
                $skipped['cf_modules'][] = $numberedCFTitle;
            }

            $pdfFiles  = $_FILES['cf_module_pdf'] ?? [];
            $pdfNames  = $pdfFiles['name'][$cfIdx]     ?? [];
            $pdfTmps   = $pdfFiles['tmp_name'][$cfIdx] ?? [];
            $pdfErrors = $pdfFiles['error'][$cfIdx]    ?? [];
            $pdfSizes  = $pdfFiles['size'][$cfIdx]     ?? [];

            foreach ($pdfNames as $pIdx => $pdfName) {
                if (($pdfErrors[$pIdx] ?? 1) !== UPLOAD_ERR_OK) continue;
                if (empty($pdfName)) continue;
                if (($pdfSizes[$pIdx] ?? 0) > 50 * 1024 * 1024) continue;

                $ext = strtolower(pathinfo($pdfName, PATHINFO_EXTENSION));
                if (!in_array($ext, ['pdf', 'ppt', 'pptx', 'doc', 'docx'])) continue;

                $uniqueName = uniqid('mod_') . '.' . $ext;
                if (move_uploaded_file($pdfTmps[$pIdx], $pdfDir . $uniqueName)) {
                    $teacherModel->insertModuleMaterial(
                        $moduleId,
                        $pdfName,
                        '/learning_management/uploads/modules/pdfs/' . $uniqueName,
                        $ext,
                        $pdfSizes[$pIdx] ?? 0,
                        $pIdx + 1
                    );
                }
            }
        }

        // ============================================================
        // PART 2 — INTERACTIVE MODULES
        // Structure: module → lessons → [videos, images, activities, quizzes]
        // Activities and quizzes are now PER LESSON (use lesson_id)
        // ============================================================
        $moduleTitles   = $_POST['module_title']   ?? [];
        $moduleContents = $_POST['module_content'] ?? [];

        foreach ($moduleTitles as $modIdx => $modTitle) {
            if (empty(trim($modTitle))) continue;

            // Auto-number: count existing interactive modules in DB + position in current form
            $existingIMCount = $teacherModel->countInteractiveModules($subject_id);
            $imModuleNumber  = $existingIMCount + $modIdx + 1;
            $numberedIMTitle = 'Module ' . $imModuleNumber . ': ' . trim($modTitle);

            $imResult = $teacherModel->insertInteractiveModule(
                $subject_id,
                $numberedIMTitle,
                trim($moduleContents[$modIdx] ?? ''),
                $imModuleNumber
            );

            $interactiveModuleId = $imResult['id'] ?? null;
            if (!$interactiveModuleId) continue;

            // Track if this interactive module already existed
            if ($imResult['existed']) {
                $skipped['im_modules'][] = $numberedIMTitle;
            }

            $lessonTitles   = $_POST['lesson_title'][$modIdx]   ?? [];
            $lessonTopics   = $_POST['lesson_topic'][$modIdx]   ?? [];
            $lessonContents = $_POST['lesson_content'][$modIdx] ?? [];

            foreach ($lessonTitles as $lesIdx => $lesTitle) {
                if (empty(trim($lesTitle))) continue;

                // Auto-number: count existing lessons in this module + position in form
                $existingLesCount = $teacherModel->countLessons($interactiveModuleId);
                $lessonNumber     = $existingLesCount + $lesIdx + 1;
                $numberedLesTitle = 'Lesson ' . $lessonNumber . ': ' . trim($lesTitle);

                $lesResult = $teacherModel->insertLesson(
                    $interactiveModuleId,
                    $numberedLesTitle,
                    trim($lessonTopics[$lesIdx]   ?? ''),
                    trim($lessonContents[$lesIdx] ?? ''),
                    $lessonNumber
                );

                $lessonId = $lesResult['id'] ?? null;
                if (!$lessonId) continue;

                // Track if this lesson already existed
                if ($lesResult['existed']) {
                    $skipped['lessons'][] = $numberedLesTitle . ' (in ' . $numberedIMTitle . ')';
                }

                // ── VIDEOS ────────────────────────────────────
                $videoTitles = $_POST['video_title'][$modIdx][$lesIdx] ?? [];
                $videoUrls   = $_POST['video_url'][$modIdx][$lesIdx]   ?? [];
                foreach ($videoTitles as $vIdx => $vTitle) {
                    if (empty(trim($vTitle)) || empty(trim($videoUrls[$vIdx] ?? ''))) continue;
                    $teacherModel->insertLessonVideo($lessonId, trim($vTitle), trim($videoUrls[$vIdx]), $vIdx + 1);
                }

                // ── IMAGES ────────────────────────────────────
                $imageFiles  = $_FILES['image_file'] ?? [];
                $imageTitles = $_POST['image_title'][$modIdx][$lesIdx] ?? [];
                $fileNames    = $imageFiles['name'][$modIdx][$lesIdx]     ?? [];
                $fileTmpNames = $imageFiles['tmp_name'][$modIdx][$lesIdx] ?? [];
                $fileErrors   = $imageFiles['error'][$modIdx][$lesIdx]    ?? [];
                $fileSizes    = $imageFiles['size'][$modIdx][$lesIdx]     ?? [];

                foreach ($fileNames as $iIdx => $fileName) {
                    if (($fileErrors[$iIdx] ?? 1) !== UPLOAD_ERR_OK) continue;
                    if (empty($fileName)) continue;
                    if (($fileSizes[$iIdx] ?? 0) > 5 * 1024 * 1024) continue;
                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) continue;
                    $uniqueName = uniqid('img_') . '.' . $ext;
                    if (move_uploaded_file($fileTmpNames[$iIdx], $imageDir . $uniqueName)) {
                        $teacherModel->insertLessonImage(
                            $lessonId,
                            '/learning_management/uploads/lessons/images/' . $uniqueName,
                            trim($imageTitles[$iIdx] ?? ''),
                            $iIdx + 1
                        );
                    }
                }

                // ── ACTIVITIES — now per lesson (lessonId) ────
                $actTitles       = $_POST['activity_title'][$modIdx][$lesIdx]        ?? [];
                $actInstructions = $_POST['activity_instructions'][$modIdx][$lesIdx] ?? [];
                $actPoints       = $_POST['activity_points'][$modIdx][$lesIdx]       ?? [];
                $actTimes        = $_POST['activity_time'][$modIdx][$lesIdx]         ?? [];

                foreach (array_keys($actTitles) as $loopIdx => $aIdx) {
                    $aTitle = $actTitles[$aIdx];
                    if (empty(trim($aTitle))) continue;

                    // stores lesson_id + interactive_module_id
                    $activityId = $teacherModel->insertActivity(
                        $lessonId,
                        $interactiveModuleId,
                        trim($aTitle),
                        trim($actInstructions[$aIdx] ?? ''),
                        (int)($actPoints[$aIdx] ?? 0),
                        (int)($actTimes[$aIdx]  ?? 0),
                        $loopIdx + 1
                    );

                    if (!$activityId) continue;

                    $qTypes   = $_POST['activity_question_type'][$modIdx][$lesIdx][$aIdx]  ?? [];
                    $qTexts   = $_POST['activity_question_text'][$modIdx][$lesIdx][$aIdx]  ?? [];
                    $qAnswers = $_POST['activity_essay_answer'][$modIdx][$lesIdx][$aIdx]   ?? [];
                    $qChoiceA = $_POST['activity_choice_a'][$modIdx][$lesIdx][$aIdx]       ?? [];
                    $qChoiceB = $_POST['activity_choice_b'][$modIdx][$lesIdx][$aIdx]       ?? [];
                    $qChoiceC = $_POST['activity_choice_c'][$modIdx][$lesIdx][$aIdx]       ?? [];
                    $qChoiceD = $_POST['activity_choice_d'][$modIdx][$lesIdx][$aIdx]       ?? [];
                    $qCorrect = $_POST['activity_correct_answer'][$modIdx][$lesIdx][$aIdx] ?? [];

                    foreach ($qTexts as $qIdx => $qText) {
                        if (empty(trim($qText))) continue;
                        $qType = $qTypes[$qIdx] ?? 'essay';

                        if ($qType === 'multiple_choice') {
                            // ── FIXED: use actual values, not null for choices ──
                            $teacherModel->insertActivityQuestion(
                                $activityId, $qType, trim($qText),
                                null,                                        // model_answer = NULL
                                trim($qChoiceA[$qIdx] ?? '') ?: null,
                                trim($qChoiceB[$qIdx] ?? '') ?: null,
                                trim($qChoiceC[$qIdx] ?? '') ?: null,        // optional
                                trim($qChoiceD[$qIdx] ?? '') ?: null,        // optional
                                strtolower($qCorrect[$qIdx] ?? 'a') ?: null,
                                1, $qIdx + 1
                            );
                        } else {
                            // ── essay: model_answer filled, choices = NULL ──
                            $modelAns = trim($qAnswers[$qIdx] ?? '') ?: null;
                            $teacherModel->insertActivityQuestion(
                                $activityId, $qType, trim($qText),
                                $modelAns,
                                null, null, null, null, null,
                                1, $qIdx + 1
                            );
                        }
                    }
                }

                // ── QUIZZES — now per lesson (lessonId) ───────
                $quizTitles   = $_POST['quiz_title'][$modIdx][$lesIdx]         ?? [];
                $quizInstruct = $_POST['quiz_instructions'][$modIdx][$lesIdx]  ?? [];
                $quizTimes    = $_POST['quiz_time_limit'][$modIdx][$lesIdx]    ?? [];
                $quizPassing  = $_POST['quiz_passing_score'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($quizTitles) as $loopIdx => $qzIdx) {
                    $qzTitle = $quizTitles[$qzIdx];
                    if (empty(trim($qzTitle))) continue;

                    // stores lesson_id + interactive_module_id
                    $quizId = $teacherModel->insertQuiz(
                        $lessonId,
                        $interactiveModuleId,
                        trim($qzTitle),
                        trim($quizInstruct[$qzIdx] ?? ''),
                        (int)($quizTimes[$qzIdx]   ?? 30),
                        (int)($quizPassing[$qzIdx] ?? 75),
                        $loopIdx + 1
                    );

                    if (!$quizId) continue;

                    $qqTexts   = $_POST['question_text'][$modIdx][$lesIdx][$qzIdx]  ?? [];
                    $qqChoiceA = $_POST['choice_a'][$modIdx][$lesIdx][$qzIdx]       ?? [];
                    $qqChoiceB = $_POST['choice_b'][$modIdx][$lesIdx][$qzIdx]       ?? [];
                    $qqChoiceC = $_POST['choice_c'][$modIdx][$lesIdx][$qzIdx]       ?? [];
                    $qqChoiceD = $_POST['choice_d'][$modIdx][$lesIdx][$qzIdx]       ?? [];
                    $qqCorrect = $_POST['correct_answer'][$modIdx][$lesIdx][$qzIdx] ?? [];

                    foreach ($qqTexts as $qqIdx => $qqText) {
                        if (empty(trim($qqText))) continue;
                        $teacherModel->insertQuizQuestion(
                            $quizId,
                            trim($qqText),
                            trim($qqChoiceA[$qqIdx] ?? '') ?: null,
                            trim($qqChoiceB[$qqIdx] ?? '') ?: null,
                            trim($qqChoiceC[$qqIdx] ?? '') ?: null,
                            trim($qqChoiceD[$qqIdx] ?? '') ?: null,
                            strtolower($qqCorrect[$qqIdx] ?? 'a'),
                            1, $qqIdx + 1
                        );
                    }
                }

                // ── FLASHCARDS — now per lesson ───────────────
                $fcFronts = $_POST['flashcard_front'][$modIdx][$lesIdx] ?? [];
                $fcBacks  = $_POST['flashcard_back'][$modIdx][$lesIdx]  ?? [];
                $fcTypes  = $_POST['flashcard_type'][$modIdx][$lesIdx]  ?? [];

                foreach ($fcFronts as $fcIdx => $fcFront) {
                    if (empty(trim($fcFront)) || empty(trim($fcBacks[$fcIdx] ?? ''))) continue;
                    $teacherModel->insertFlashcard(
                        $lessonId,
                        $interactiveModuleId,
                        $fcTypes[$fcIdx] ?? 'term_definition',
                        trim($fcFront),
                        trim($fcBacks[$fcIdx]),
                        $fcIdx + 1
                    );
                }
            }

        }

        // Store skipped items in session so teacher_class page can show modal
        $hasSkipped = !empty($skipped['cf_modules']) || !empty($skipped['im_modules']) || !empty($skipped['lessons']);
        if ($hasSkipped) {
            $_SESSION['save_skipped'] = $skipped;
        }

        // Always set success flash — records.php will show the success modal
        $_SESSION['save_success'] = true;

        header("Location: /learning_management/public/?url=teacher_class&id={$subject_id}&grade_id={$grade_level_id}");
        exit;
    }
}