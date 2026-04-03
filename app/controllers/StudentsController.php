<?php

require_once "../core/Model.php";
require_once "../app/models/subjects.php";
require_once "../app/models/Students.php";

class StudentsController
{

    public function unsubmit_assignment()
    {
        header('Content-Type: application/json');

        $studentId = $_SESSION['student_id'] ?? 0;

        // Fallback: look up student by user_id
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        if (!$studentId) {
            echo json_encode(['success' => false, 'message' => 'Not logged in.']);
            exit;
        }

        $assignmentId = (int) ($_POST['assignment_id'] ?? 0);
        if (!$assignmentId) {
            echo json_encode(['success' => false, 'message' => 'Invalid assignment.']);
            exit;
        }

        $studentModel = new Students();
        $result = $studentModel->deleteAssignmentSubmission($assignmentId, $studentId);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Could not delete submission.']);
        }
        exit;
    }

    public function submit_assignment()
    {
        header('Content-Type: application/json');

        $studentId = $_SESSION['student_id'] ?? 0;

        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $assignmentId = (int) ($_POST['assignment_id'] ?? 0);
        $message = trim($_POST['comment'] ?? '');

        if (!$studentId) {
            echo json_encode(['success' => false, 'message' => 'Not logged in.']);
            exit;
        }

        $filePath = null;

        // Handle file upload
        if (!empty($_FILES['submission_file']['name'])) {
            $uploadDir = '../uploads/submissions/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0777, true);

            $originalName = basename($_FILES['submission_file']['name']);
            $uniqueName = uniqid() . '_' . $originalName;
            $destination = $uploadDir . $uniqueName;

            if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $destination)) {
                $filePath = 'uploads/submissions/' . $uniqueName;
            } else {
                echo json_encode(['success' => false, 'message' => 'File upload failed.']);
                exit;
            }
        }

        if (!$filePath && !$message) {
            echo json_encode(['success' => false, 'message' => 'Please attach a file or add a message.']);
            exit;
        }

        $studentModel = new Students();

        // Check if already submitted
        $existing = $studentModel->getAssignmentSubmission($assignmentId, $studentId);
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'You have already submitted this assignment.']);
            exit;
        }

        $result = $studentModel->saveAssignmentSubmission($assignmentId, $studentId, $filePath, $message);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
        }
        exit;
    }

    public function assignments_view()
    {
        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $studentModel = new Students();
        $completedAssignments = $studentId ? $studentModel->getCompletedAssignments($studentId) : [];
        $pendingAssignments = $studentId ? $studentModel->getPendingAssignments($studentId) : [];
        $completedCount = $studentId ? $studentModel->countCompletedAssignments($studentId) : 0;
        $pendingCount = $studentId ? $studentModel->countPendingAssignments($studentId) : 0;
        $gradedCount = 1; // static for now as requested

        require "../app/view/assignments.php";
    }

    // ── INTERACTIVE MODULES ────────────────────────────────────
    public function modules()
    {
        $studentModel = new Students();
        $subject = $_GET['subject'] ?? null;
        $subjectInfo = $subject ? $studentModel->getSubjectBySlug($subject) : null;
        $modules = $subject ? $studentModel->getInteractiveModules($subject) : [];
        $studentId = $_SESSION['student_id'] ?? 0;

        // Ensure student_id is set
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $lessonCounts = [];
        $startedModuleIds = $studentId ? $studentModel->getStartedModuleIds($studentId) : [];
        foreach ($modules as $mod) {
            $lessonCounts[$mod['id']] = $studentModel->countIMlessons($mod['id']);
        }
        require "../app/view/modules.php";
    }

    // ── MARK MODULE STARTED (AJAX) ─────────────────────────────
    public function mark_module_started()
    {
        header('Content-Type: application/json');

        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $moduleId = (int) ($_POST['module_id'] ?? 0);
        if ($studentId && $moduleId) {
            $studentModel = new Students();
            $studentModel->markModuleStarted($moduleId, $studentId);
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'student_id' => $studentId, 'module_id' => $moduleId]);
        }
        exit;
    }

    // ── SUBJECT LESSONS ────────────────────────────────────────
    // ?url=subject_lessons&subject=philosophy&id=1&lesson=7
    public function subject_lessons()
    {
        $studentModel = new Students();

        $moduleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $subject = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $lessonId = isset($_GET['lesson']) ? (int) $_GET['lesson'] : 0;
        $studentId = $_SESSION['student_id'] ?? 0;

        // Module + all lessons with counts
        $module = $moduleId ? $studentModel->getInteractiveModuleById($moduleId) : null;
        $lessons = $module ? $studentModel->getIMLessonsWithCounts($moduleId) : [];

        // Default to first lesson
        if (!$lessonId && !empty($lessons)) {
            $lessonId = $lessons[0]['id'];
        }

        // Active lesson content
        $lesson = $lessonId ? $studentModel->getIMLessonById($lessonId) : null;
        $images = $lessonId ? $studentModel->getLessonImages($lessonId) : [];
        $videos = $lessonId ? $studentModel->getLessonVideos($lessonId) : [];
        $flashcards = $lessonId ? $studentModel->getLessonFlashcards($lessonId) : [];
        $activities = $lessonId ? $studentModel->getLessonActivities($lessonId) : [];
        $quizzes = $lessonId ? $studentModel->getLessonQuizzes($lessonId) : [];

        // Per-activity: questions + submission
        $activityData = [];
        foreach ($activities as $act) {
            $activityData[$act['id']] = [
                'activity' => $act,
                'questions' => $studentModel->getIMActivityQuestions($act['id']),
                'submission' => $studentId
                    ? $studentModel->getIMActivitySubmission($act['id'], $studentId)
                    : null,
            ];
        }

        // Per-quiz: questions + result
        $quizData = [];
        foreach ($quizzes as $qz) {
            $quizData[$qz['id']] = [
                'quiz' => $qz,
                'questions' => $studentModel->getIMQuizQuestions($qz['id']),
                'result' => $studentId
                    ? $studentModel->getIMQuizResult($qz['id'], $studentId)
                    : null,
            ];
        }

        // Completion status per lesson for sidebar checkmarks
        // A lesson shows checkmark ONLY if:
        //   - It has been visited (lesson_visits table) OR
        //   - Student passed quiz / submitted activity
        // Future lessons always show circle (green dot), never checkmark
        $lessonCompletion = [];
        $foundCurrent = false;
        foreach ($lessons as $l) {
            if ($l['id'] == $lessonId) {
                // Current active lesson — always circle
                $lessonCompletion[$l['id']] = false;
                $foundCurrent = true;
            } elseif (!$foundCurrent) {
                // Lessons BEFORE current — checkmark if visited or completed
                $lessonCompletion[$l['id']] = $studentId
                    ? ($studentModel->isLessonVisited($l['id'], $studentId) ||
                        $studentModel->isLessonCompleted($l['id'], $studentId))
                    : false;
            } else {
                // Lessons AFTER current — always circle
                $lessonCompletion[$l['id']] = false;
            }
        }

        // Progress percentage
        $completedCount = count(array_filter($lessonCompletion));
        $totalLessons = count($lessons);

        // Load the correct subject view file
        $subjectViewMap = [
            "philosophy" => "../Grade_12/philosophy/philosophy_lessons.php",
            "ucsp" => "../Grade_12/ucsp/ucsp_lessons.php",
            "css" => "../Grade_12/css/css_lessons.php",
            "pe" => "../Grade_12/pe/pe_lessons.php",
            "3i" => "../Grade_12/3i/3i_lessons.php",
            "entrep" => "../Grade_12/entrep/entrep_lessons.php",
            "work_immersion" => "../Grade_12/work_immersion/work_immersion_lessons.php",
            "media_info_literature" => "../Grade_11/media_info_literature/media_info_literature_lessons.php",
            "p_e" => "../Grade_11/p_e/pe_lessons.php",
            "css_11" => "../Grade_11/css_11/css_lessons.php",
            "reading_writing" => "../Grade_11/reading_writing/reading_writing_lessons.php",
            "pagbasa_pagsusuri" => "../Grade_11/pagbasa_pagsusuri/pagbasa_pagsusuri_lessons.php",
            "practical_research" => "../Grade_11/practical_research/practical_research_lessons.php",
            "physical_science" => "../Grade_11/physical_science/physical_science_lessons.php",
            "statistics_probability" => "../Grade_11/statistics_probability/statistics_probability_lessons.php",
        ];

        $viewFile = $subjectViewMap[$subject] ?? null;
        if ($viewFile && file_exists($viewFile)) {
            // subject_lessons.php is the HTML wrapper with CSS/JS links
            // It checks $viewFile which is already set above
            require "../app/view/subject_lessons.php";
        } else {
            echo "<h3>Lesson view not found for: " . htmlspecialchars($subject) . "</h3>";
        }
    }

    // ── SAVE LESSON ANSWERS (AJAX — called on Next/Finish) ────
    // Saves activity answers + quiz results for a lesson from POST data
    // Returns JSON: {"ok":true}
    public function save_lesson_answers()
    {
        header('Content-Type: application/json');
        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId) {
            echo json_encode(['ok' => false, 'msg' => 'not logged in']);
            exit;
        }

        $studentModel = new Students();
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            echo json_encode(['ok' => false, 'msg' => 'no data']);
            exit;
        }

        // ── Save activity answers ──────────────────────────────
        if (!empty($data['activities'])) {
            foreach ($data['activities'] as $actId => $answers) {
                $actId = (int) $actId;
                if (!$actId)
                    continue;
                $existing = $studentModel->getIMActivitySubmission($actId, $studentId);
                if (!$existing) {
                    $studentModel->saveIMActivitySubmission($actId, $studentId, json_encode($answers));
                }
            }
        }

        // ── Save quiz results ──────────────────────────────────
        if (!empty($data['quizzes'])) {
            foreach ($data['quizzes'] as $qzId => $info) {
                $qzId = (int) $qzId;
                $passingScore = (int) ($info['passing_score'] ?? 75);
                $answers = $info['answers'] ?? [];
                if (!$qzId || empty($answers))
                    continue;

                $existing = $studentModel->getIMQuizResult($qzId, $studentId);
                if (!$existing) {
                    $questions = $studentModel->getIMQuizQuestions($qzId);
                    $score = 0;
                    $total = count($questions);
                    foreach ($questions as $q) {
                        $submitted = strtolower(trim($answers[$q['id']] ?? ''));
                        $correct = strtolower(trim($q['correct_ans']));
                        if ($submitted === $correct)
                            $score++;
                    }
                    $studentModel->saveIMQuizResult($qzId, $studentId, $score, $total, $passingScore);
                }
            }
        }

        // ── Mark lesson as visited ─────────────────────────────
        if (!empty($data['lesson_id'])) {
            $studentModel->markLessonVisited((int) $data['lesson_id'], $studentId);
        }

        echo json_encode(['ok' => true]);
        exit;
    }

    // ── MARK SUBJECT STARTED (AJAX) ────────────────────────────
    public function mark_subject_started()
    {
        header('Content-Type: application/json');

        // Get student_id — try session first, then look up from user_id
        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $slug = trim($_POST['slug'] ?? '');
        if ($studentId && $slug) {
            $studentModel = new Students();
            $studentModel->markSubjectStarted($slug, $studentId);
            echo json_encode(['ok' => true, 'student_id' => $studentId, 'slug' => $slug]);
        } else {
            echo json_encode([
                'ok' => false,
                'student_id' => $studentId,
                'slug' => $slug,
                'session' => [
                    'user_id' => $_SESSION['user_id'] ?? 'NOT SET',
                    'student_id' => $_SESSION['student_id'] ?? 'NOT SET',
                    'grade' => $_SESSION['grade_level'] ?? 'NOT SET',
                ]
            ]);
        }
        exit;
    }

    // ── MARK LESSON VISITED (AJAX) ─────────────────────────────
    // Called by JS when student navigates away from a lesson
    // Returns JSON: {"ok":true}
    public function mark_lesson_visited()
    {
        header('Content-Type: application/json');
        $studentId = $_SESSION['student_id'] ?? 0;
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);

        if ($studentId && $lessonId) {
            $studentModel = new Students();
            $studentModel->markLessonVisited($lessonId, $studentId);
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false]);
        }
        exit;
    }

    // ── SAVE LESSON ACTIVITY ───────────────────────────────────
    public function save_lesson_activity()
    {
        $studentModel = new Students();
        $studentId = $_SESSION['student_id'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $studentId) {
            $activityId = (int) ($_POST['activity_id'] ?? 0);
            $existing = $studentModel->getIMActivitySubmission($activityId, $studentId);
            if (!$existing && $activityId) {
                $answers = [];
                foreach ($_POST as $key => $val) {
                    if (strpos($key, 'answer_') === 0) {
                        $answers[str_replace('answer_', '', $key)] = trim($val);
                    }
                }
                $studentModel->saveIMActivitySubmission($activityId, $studentId, json_encode($answers));
            }
        }

        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);
        $subject = $_POST['subject'] ?? '';
        header("Location: /learning_management/public/?url=subject_lessons&subject={$subject}&id={$moduleId}&lesson={$lessonId}");
        exit;
    }

    // ── SAVE LESSON QUIZ ───────────────────────────────────────
    public function save_lesson_quiz()
    {
        $studentModel = new Students();
        $studentId = $_SESSION['student_id'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $studentId) {
            $quizId = (int) ($_POST['quiz_id'] ?? 0);
            $passingScore = (int) ($_POST['passing_score'] ?? 75);
            $existing = $studentModel->getIMQuizResult($quizId, $studentId);

            if (!$existing && $quizId) {
                $questions = $studentModel->getIMQuizQuestions($quizId);
                $score = 0;
                $totalQ = count($questions);
                foreach ($questions as $q) {
                    $submitted = strtolower(trim($_POST['answer_' . $q['id']] ?? ''));
                    $correct = strtolower(trim($q['correct_ans']));
                    if ($submitted === $correct)
                        $score++;
                }
                $studentModel->saveIMQuizResult($quizId, $studentId, $score, $totalQ, $passingScore);
            }
        }

        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);
        $subject = $_POST['subject'] ?? '';
        header("Location: /learning_management/public/?url=subject_lessons&subject={$subject}&id={$moduleId}&lesson={$lessonId}");
        exit;
    }

    // ── MODULE DETAIL ──────────────────────────────────────────
    public function module_detail()
    {
        $studentModel = new Students();
        $moduleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $subject = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $studentId = $_SESSION['student_id'] ?? 0;

        $module = $moduleId ? $studentModel->getInteractiveModuleById($moduleId) : null;
        $lessons = $module ? $studentModel->getIMLessons($moduleId) : [];
        $flashcards = $module ? $studentModel->getIMFlashcards($moduleId) : [];
        $activity = $module ? $studentModel->getIMActivity($moduleId) : null;
        $quiz = $module ? $studentModel->getIMQuiz($moduleId) : null;
        $activityDone = ($activity && $studentId) ? $studentModel->getIMActivitySubmission($activity['id'], $studentId) : null;
        $quizDone = ($quiz && $studentId) ? $studentModel->getIMQuizResult($quiz['id'], $studentId) : null;
        $activityQuestions = $activity ? $studentModel->getIMActivityQuestions($activity['id']) : [];
        $quizQuestions = $quiz ? $studentModel->getIMQuizQuestions($quiz['id']) : [];

        require "../app/view/subject_lessons.php";
    }

    // ── LESSON VIEW ────────────────────────────────────────────
    public function lesson_view()
    {
        $studentModel = new Students();
        $lessonId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $lesson = $lessonId ? $studentModel->getIMLessonById($lessonId) : null;
        $pdfs = $lesson ? $studentModel->getIMLessonPdfs($lessonId) : [];
        $adjacent = $lesson
            ? $studentModel->getAdjacentIMLessons($lessonId, $lesson['module_id'])
            : ['prev' => null, 'next' => null];
        require "../app/view/lesson_view.php";
    }

    // ── SAVE ACTIVITY (module-level) ───────────────────────────
    public function save_activity()
    {
        $studentModel = new Students();
        $studentId = $_SESSION['student_id'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $studentId) {
            $activityId = (int) ($_POST['activity_id'] ?? 0);
            $existing = $studentModel->getIMActivitySubmission($activityId, $studentId);
            if (!$existing && $activityId) {
                $answers = [];
                foreach ($_POST as $key => $val) {
                    if (strpos($key, 'answer_') === 0) {
                        $answers[str_replace('answer_', '', $key)] = trim($val);
                    }
                }
                $studentModel->saveIMActivitySubmission($activityId, $studentId, json_encode($answers));
            }
        }
        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $subject = $_POST['subject'] ?? '';
        header("Location: /learning_management/public/?url=module_detail&subject={$subject}&id={$moduleId}&tab=activity");
        exit;
    }

    // ── SAVE QUIZ (module-level) ───────────────────────────────
    public function save_quiz()
    {
        $studentModel = new Students();
        $studentId = $_SESSION['student_id'] ?? 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $studentId) {
            $quizId = (int) ($_POST['quiz_id'] ?? 0);
            $passingScore = (int) ($_POST['passing_score'] ?? 75);
            $existing = $studentModel->getIMQuizResult($quizId, $studentId);
            if (!$existing && $quizId) {
                $questions = $studentModel->getIMQuizQuestions($quizId);
                $score = 0;
                $total = count($questions);
                foreach ($questions as $q) {
                    if (strtolower($_POST['answer_' . $q['id']] ?? '') === strtolower($q['correct_ans']))
                        $score++;
                }
                $studentModel->saveIMQuizResult($quizId, $studentId, $score, $total, $passingScore);
            }
        }
        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $subject = $_POST['subject'] ?? '';
        header("Location: /learning_management/public/?url=module_detail&subject={$subject}&id={$moduleId}&tab=quiz");
        exit;
    }

    // ── EXISTING METHODS ───────────────────────────────────────
    public function module_view()
    {
        $studentModel = new Students();
        $moduleId = isset($_GET['id']) ? (int) trim($_GET['id']) : 0;
        $subjectSlug = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $module = $studentModel->getModuleByIdAndSlug($moduleId, $subjectSlug);
        $attachments = $module ? $studentModel->getModuleMaterials($moduleId) : [];
        require_once "../app/view/module_view.php";
    }

    public function assignment_view()
    {
        date_default_timezone_set('Asia/Manila');
        $studentModel = new Students();
        $assignmentId = isset($_GET['id']) ? (int) trim($_GET['id']) : 0;
        $subjectSlug = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $assignment = $studentModel->getAssignmentByIdAndSlug($assignmentId, $subjectSlug);
        $templates = $assignment ? $studentModel->getAssignmentTemplates($assignmentId) : [];

        // ← ADD THIS
        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId && !empty($_SESSION['user_id'])) {
            require_once "../app/models/subjects.php";
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }
        $existingSubmission = ($assignment && $studentId)
            ? $studentModel->getAssignmentSubmission($assignmentId, $studentId)
            : null;

        require_once "../app/view/assignment_view.php";
    }

    public function announcement_view()
    {
        $studentModel = new Students();
        $announcementId = isset($_GET['id']) ? (int) trim($_GET['id']) : 0;
        $subjectSlug = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $announcement = $studentModel->getAnnouncementByIdAndSlug($announcementId, $subjectSlug);
        require_once "../app/view/announcement_view.php";
    }

    public function classes()
    {
        $subjectModel = new subjects();
        $grade_level_id = ($_SESSION['grade_level'] == "Grade 11") ? 1 : 2;
        $user_id = $_SESSION['user_id'];
        $studentRow = $subjectModel->getStudentByUserId($user_id);
        if (!$studentRow)
            die("Student record not found. Please contact your administrator.");

        $student_id = $studentRow['id'];
        $section_id = $studentRow['section_id'];
        $_SESSION['student_id'] = $student_id;
        $_SESSION['section_id'] = $section_id;

        if (isset($_GET['enroll']) && isset($_GET['subject_id']) && isset($_GET['subject_slug'])) {
            $subject_id = (int) $_GET['subject_id'];
            $subject_slug = $_GET['subject_slug'];
            if (!$subjectModel->isEnrolled($student_id, $subject_id)) {
                $correct_section_id = $subjectModel->getSectionForSubject($student_id, $subject_id);
                $subjectModel->enrollStudent($student_id, $subject_id, $correct_section_id ?? $section_id);
            }
            header("Location: /learning_management/public/?url=subjects&subject=" . urlencode($subject_slug));
            exit();
        }

        $subjects = $subjectModel->getSubjectsByGradeLevel($grade_level_id);
        $enrolledSubjectIds = $subjectModel->getEnrolledSubjectIds($student_id);
        require "../app/view/classes.php";
    }

    public function module_all()
    {
        $subjectModel = new subjects();
        $grade_level_id = ($_SESSION['grade_level'] == "Grade 11") ? 1 : 2;
        $user_id = $_SESSION['user_id'];
        $studentRow = $subjectModel->getStudentByUserId($user_id);
        if (!$studentRow)
            die("Student record not found. Please contact your administrator.");

        $student_id = $studentRow['id'];
        $section_id = $studentRow['section_id'];
        $_SESSION['student_id'] = $student_id;
        $_SESSION['section_id'] = $section_id;

        if (isset($_GET['enroll']) && isset($_GET['subject_id']) && isset($_GET['subject_slug'])) {
            $subject_id = (int) $_GET['subject_id'];
            $subject_slug = $_GET['subject_slug'];
            if (!$subjectModel->isEnrolled($student_id, $subject_id)) {
                $correct_section_id = $subjectModel->getSectionForSubject($student_id, $subject_id);
                $subjectModel->enrollStudent($student_id, $subject_id, $correct_section_id ?? $section_id);
            }
            header("Location: /learning_management/public/?url=subjects&subject=" . urlencode($subject_slug));
            exit();
        }

        $subjects = $subjectModel->getSubjectsByGradeLevel($grade_level_id);
        $grade11Subjects = $subjectModel->getGrade11Subjects();
        $grade12Subjects = $subjectModel->getGrade12Subjects();
        $enrolledSubjectIds = $subjectModel->getEnrolledSubjectIds($student_id);
        $studentModel = new Students();
        $startedSlugs = $studentModel->getStartedSubjectSlugs($student_id);
        require "../app/view/module_all.php";
    }

    public function join_class()
    {
        ob_start(); // catch any stray output
        header('Content-Type: application/json');

        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
                $_SESSION['section_id'] = $studentRow['section_id'];
            }
        }

        if (!$studentId) {
            ob_end_clean();
            echo json_encode(['ok' => false, 'msg' => 'Not logged in.']);
            exit;
        }

        $code = trim($_POST['subject_code'] ?? '');
        if (!$code) {
            ob_end_clean();
            echo json_encode(['ok' => false, 'msg' => 'Please enter a class code.']);
            exit;
        }

        $subjectModel = new subjects();
        $subject = $subjectModel->getSubjectByCode($code);

        if (!$subject) {
            ob_end_clean();
            echo json_encode(['ok' => false, 'msg' => 'No class found with that code. Ask your teacher for the correct code.']);
            exit;
        }

        $subjectId = (int) $subject['id'];

        if ($subjectModel->isEnrolled($studentId, $subjectId)) {
            ob_end_clean();
            echo json_encode([
                'ok' => false,
                'already_enrolled' => true,
                'redirect_url' => '/learning_management/public/?url=subjects&subject=' . urlencode($subject['subject_code']),
            ]);
            exit;
        }

        $sectionId = (int) ($subject['section_id']
            ?? $subjectModel->getSectionForSubject($studentId, $subjectId)
            ?? ($_SESSION['section_id'] ?? 0));

        $subjectModel->enrollStudent($studentId, $subjectId, $sectionId);

        ob_end_clean();
        echo json_encode([
            'ok' => true,
            'subject_name' => $subject['subject_name'],
            'redirect_url' => '/learning_management/public/?url=subjects&subject=' . urlencode($subject['subject_code']),
        ]);
        exit;
    }
}