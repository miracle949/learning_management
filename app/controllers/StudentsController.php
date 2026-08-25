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

    public function get_assignment_due()
    {
        header('Content-Type: application/json');
        $assignmentId = (int) ($_GET['id'] ?? 0);
        if (!$assignmentId) {
            echo json_encode(['due_date' => null]);
            exit;
        }
        $studentModel = new Students();
        $row = $studentModel->getAssignmentDueDate($assignmentId);
        echo json_encode([
            'due_date' => $row['due_date'] ?? null,
            'due_time' => $row['due_time'] ?? null,
        ]);
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
            echo json_encode([
                'success' => true,
                'file_path' => $filePath,     // e.g. "uploads/submissions/abc123_file.pdf"
                'file_name' => $originalName, // e.g. "ILMS CHAPTER 3 FINAL.pdf"
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error. Please try again.']);
        }
        exit;
    }

    public function assignment_view()
    {
        date_default_timezone_set('Asia/Manila');
        $studentModel = new Students();
        $assignmentId = isset($_GET['id']) ? (int) trim($_GET['id']) : 0;
        $subjectSlug = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $assignment = $studentModel->getAssignmentByIdAndSlug($assignmentId, $subjectSlug);
        $templates = $assignment ? $studentModel->getAssignmentTemplates($assignmentId) : [];

        $studentId = $_SESSION['student_id'] ?? 0;
        if (!$studentId && !empty($_SESSION['user_id'])) {
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

    // ── INTERACTIVE MODULES ────────────────────────────────────
    public function modules()
    {
        $studentModel = new Students();
        $subject = $_GET['subject'] ?? null;
        $subjectInfo = $subject ? $studentModel->getSubjectBySlug($subject) : null;
        $modules = $subject ? $studentModel->getInteractiveModules($subject) : [];
        $studentId = $_SESSION['student_id'] ?? 0;

        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $startedModuleIds = $studentId ? $studentModel->getStartedModuleIds($studentId) : [];
        $moduleProgress = $studentId ? $studentModel->getModuleProgressMap($studentId) : [];

        // ✅ NEW — real overall progress across ALL modules in this subject
        $overallProgress = $studentModel->getOverallModuleProgress($studentId, count($modules));

        $lessonCounts = [];
        $imageCounts = [];
        $videoCounts = [];
        $activityCounts = [];
        $quizCounts = [];

        foreach ($modules as $mod) {
            $lessonCounts[$mod['id']] = $studentModel->countIMlessons($mod['id']);
            $imageCounts[$mod['id']] = $studentModel->countIMimages($mod['id']);
            $videoCounts[$mod['id']] = $studentModel->countIMvideos($mod['id']);
            $activityCounts[$mod['id']] = $studentModel->countIMactivities($mod['id']);
            $quizCounts[$mod['id']] = $studentModel->countIMquizzes($mod['id']);
        }

        $totalModulesAll = $studentId ? $studentModel->countTotalModulesForStudent($studentId) : 0;
        $enrolledClassesCount = $studentId ? $studentModel->countEnrolledClasses($studentId) : 0;
        $totalQuizzesAll = $studentId ? $studentModel->countTotalQuizzesForStudent($studentId) : 0;
        $completedQuizzesAll = $studentId ? $studentModel->countCompletedQuizzesForStudent($studentId) : 0;
        $inProgressModulesAll = $studentId ? $studentModel->countModulesInProgressForStudent($studentId) : 0;
        $upcomingDeadlines = $studentId ? $studentModel->getUpcomingDeadlines($studentId, 60) : [];
        $totalActivitiesAll = $studentId ? $studentModel->countTotalActivitiesForStudent($studentId) : 0;
        $activitiesThisWeek = $studentId ? $studentModel->countActivitiesCompletedThisWeek($studentId) : 0;

        // Build a simple list of in-progress modules (for "Your Current Progress")
        $inProgressModules = [];
        foreach ($modules as $mod) {
            $prog = $moduleProgress[$mod['id']] ?? null;
            if ($prog && (int) $prog['is_finished'] !== 1 && (float) $prog['completion_percentage'] > 0) {
                $inProgressModules[] = [
                    'id' => $mod['id'],
                    'title' => $mod['title'],
                    'percentage' => (float) $prog['completion_percentage'],
                ];
            }
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

            // ✅ NEW — log "opened a module" for the recent activity feed
            $moduleInfo = $studentModel->getInteractiveModuleById($moduleId);
            if ($moduleInfo) {
                $studentModel->logActivity(
                    $studentId,
                    'module_opened',
                    $moduleInfo['title'],
                    $moduleInfo['subject_name'],
                    30 // don't re-log the same module for 30 min
                );
            }

            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'student_id' => $studentId, 'module_id' => $moduleId]);
        }
        exit;
    }

    // ── SUBJECT LESSONS — add markModuleStarted safety net ────
    public function subject_lessons()
    {
        $studentModel = new Students();

        $moduleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $subject = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $lessonId = isset($_GET['lesson']) ? (int) $_GET['lesson'] : 0;
        $studentId = $_SESSION['student_id'] ?? 0;

        $module = $moduleId ? $studentModel->getInteractiveModuleById($moduleId) : null;
        $lessons = $module ? $studentModel->getIMLessonsWithCounts($moduleId) : [];

        $subjectModulesList = $subject ? $studentModel->getInteractiveModules($subject) : [];
        $moduleTotal = count($subjectModulesList);
        $modulePosition = 0;
        foreach ($subjectModulesList as $idx => $m) {
            if ((int) $m['id'] === $moduleId) {
                $modulePosition = $idx + 1;
                break;
            }
        }

        if (!$lessonId && !empty($lessons)) {
            $lessonId = $lessons[0]['id'];
        }

        // ✅ safety net: always mark started when student opens a lesson
        if ($studentId && $moduleId) {
            $studentModel->markModuleStarted($moduleId, $studentId);
        }

        $isModuleStarted = ($studentId && $moduleId);

        // ... rest unchanged
        $lesson = $lessonId ? $studentModel->getIMLessonById($lessonId) : null;

        // ✅ NEW — log "opened a lesson"
        if ($studentId && $lesson) {
            $studentModel->logActivity(
                $studentId,
                'lesson_opened',
                $lesson['title'],
                $lesson['subject_name']
            );
        }

        // Ordered text/image/video blocks (builder order via sort_order) —
        // replaces the old separate $lesson['content'] / $images / $videos
        // sections, since text no longer lives on tbl_lessons.content.
        $contentBlocks = $lessonId ? $studentModel->getLessonContentBlocks($lessonId) : [];

        $flashcards = $lessonId ? $studentModel->getLessonFlashcards($lessonId) : [];
        $activities = $lessonId ? $studentModel->getLessonActivities($lessonId) : [];
        $quizzes = $lessonId ? $studentModel->getLessonQuizzes($lessonId) : [];

        // ✅ Self-healing: resync tbl_module_progress every time this page
        // loads, so the stored percentage can never drift from what
        // isLessonCompleted() actually says. This is safe — it only
        // recomputes from real completion state, it never marks anything
        // as visited by itself.
        if ($studentId && $moduleId) {
            $studentModel->updateModuleProgress($moduleId, $studentId);
        }

        $activityData = [];
        foreach ($activities as $act) {
            $activityData[$act['id']] = [
                'activity' => $act,
                'questions' => $studentModel->getIMActivityQuestions($act['id']),
                'submission' => $studentId ? $studentModel->getIMActivitySubmission($act['id'], $studentId) : null,
            ];
        }

        $quizData = [];
        foreach ($quizzes as $qz) {
            $quizData[$qz['id']] = [
                'quiz' => $qz,
                'questions' => $studentModel->getIMQuizQuestions($qz['id']),
                'result' => $studentId ? $studentModel->getIMQuizResult($qz['id'], $studentId) : null,
            ];
        }

        $lessonCompletion = [];
        $totalLessons = count($lessons);
        $completedCount = 0;
        foreach ($lessons as $l) {
            $done = $studentId ? $studentModel->isLessonCompleted($l['id'], $studentId) : false;
            $lessonCompletion[$l['id']] = $done;
            if ($done)
                $completedCount++;
        }

        $currentIndex = 0;
        $prevLessonId = null;
        $nextLessonId = null;
        foreach ($lessons as $i => $l) {
            if ($l['id'] == $lessonId) {
                $currentIndex = $i + 1;
                $prevLessonId = $lessons[$i - 1]['id'] ?? null;
                $nextLessonId = $lessons[$i + 1]['id'] ?? null;
                break;
            }
        }

        $studentName = $_SESSION['student_name'] ?? null;
        $studentLrn = $_SESSION['student_lrn'] ?? null;

        require "../app/view/subject_lessons.php";
    }

    // ✅ ADD THIS NEW METHOD — called when student clicks Finish
    public function finish_module()
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

        if (!$studentId || !$moduleId) {
            echo json_encode(['ok' => false, 'msg' => 'Missing student or module']);
            exit;
        }

        $studentModel = new Students();
        $studentModel->finishModule($moduleId, $studentId);

        echo json_encode(['ok' => true]);
        exit;
    }


    public function subject_activity()
    {
        $studentModel = new Students();

        $activityId = isset($_GET['activity']) ? (int) $_GET['activity'] : 0;
        $subject = isset($_GET['subject']) ? trim($_GET['subject']) : '';
        $studentId = $_SESSION['student_id'] ?? 0;

        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId($_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $activity = $activityId ? $studentModel->getIMActivityById($activityId) : null;

        if (!$activity) {
            header("Location: /learning_management/public/?url=modules&subject=" . urlencode($subject));
            exit;
        }

        $lessonId = (int) $activity['lesson_id'];
        $moduleId = (int) $activity['module_id'];

        $questions = $studentModel->getIMActivityQuestions($activityId);
        $submission = $studentId ? $studentModel->getIMActivitySubmission($activityId, $studentId) : null;

        // ── Handle answer submission ───────────────────────────
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $studentId && !$submission) {
            $answers = [];
            foreach ($questions as $q) {
                $key = 'answer_' . $q['id'];
                if (isset($_POST[$key])) {
                    $answers[$q['id']] = trim($_POST[$key]);
                }
            }
            $studentModel->saveIMActivitySubmission($activityId, $studentId, json_encode($answers));
            $studentModel->markLessonVisited($lessonId, $studentId);
            $studentModel->updateModuleProgress($moduleId, $studentId);
            header("Location: /learning_management/public/?url=subject_activity&subject={$subject}&id={$moduleId}&activity={$activityId}");
            exit;
        }

        $module = $studentModel->getInteractiveModuleById($moduleId);
        $lessons = $module ? $studentModel->getIMLessonsWithCounts($moduleId) : [];
        $lesson = $studentModel->getIMLessonById($lessonId);

        // ── Build sidebar context: every activity/quiz across the whole module ──
        $allModuleActivities = [];
        $allModuleQuizzes = [];
        foreach ($lessons as $l) {
            foreach ($studentModel->getLessonActivities($l['id']) as $act) {
                $allModuleActivities[$act['id']] = [
                    'activity' => $act,
                    'questions' => $studentModel->getIMActivityQuestions($act['id']),
                    'submission' => $studentId ? $studentModel->getIMActivitySubmission($act['id'], $studentId) : null,
                ];
            }
            foreach ($studentModel->getLessonQuizzes($l['id']) as $qz) {
                $allModuleQuizzes[$qz['id']] = [
                    'quiz' => $qz,
                    'questions' => $studentModel->getIMQuizQuestions($qz['id']),
                    'result' => $studentId ? $studentModel->getIMQuizResult($qz['id'], $studentId) : null,
                ];
            }
        }

        $lessonCompletion = [];
        $totalLessons = count($lessons);
        $completedCount = 0;
        foreach ($lessons as $l) {
            $done = $studentId ? $studentModel->isLessonCompleted($l['id'], $studentId) : false;
            $lessonCompletion[$l['id']] = $done;
            if ($done)
                $completedCount++;
        }

        $currentActivityId = $activityId;
        $isSubmitted = ($submission !== null);

        $studentName = $_SESSION['student_name'] ?? null;
        $studentLrn = $_SESSION['student_lrn'] ?? null;

        require "../app/view/subject_activity.php";
    }

    // ── SUBJECT LESSONS ────────────────────────────────────────
    // ?url=subject_lessons&subject=philosophy&id=1&lesson=7
    // public function subject_lessons()
    // {
    //     $studentModel = new Students();

    //     $moduleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    //     $subject = isset($_GET['subject']) ? trim($_GET['subject']) : '';
    //     $lessonId = isset($_GET['lesson']) ? (int) $_GET['lesson'] : 0;
    //     $studentId = $_SESSION['student_id'] ?? 0;

    //     // Module + all lessons with counts
    //     $module = $moduleId ? $studentModel->getInteractiveModuleById($moduleId) : null;
    //     $lessons = $module ? $studentModel->getIMLessonsWithCounts($moduleId) : [];

    //     // Default to first lesson
    //     if (!$lessonId && !empty($lessons)) {
    //         $lessonId = $lessons[0]['id'];
    //     }

    //     // Active lesson content
    //     $lesson = $lessonId ? $studentModel->getIMLessonById($lessonId) : null;
    //     $images = $lessonId ? $studentModel->getLessonImages($lessonId) : [];
    //     $videos = $lessonId ? $studentModel->getLessonVideos($lessonId) : [];
    //     $flashcards = $lessonId ? $studentModel->getLessonFlashcards($lessonId) : [];
    //     $activities = $lessonId ? $studentModel->getLessonActivities($lessonId) : [];
    //     $quizzes = $lessonId ? $studentModel->getLessonQuizzes($lessonId) : [];

    //     // Per-activity: questions + submission
    //     $activityData = [];
    //     foreach ($activities as $act) {
    //         $activityData[$act['id']] = [
    //             'activity' => $act,
    //             'questions' => $studentModel->getIMActivityQuestions($act['id']),
    //             'submission' => $studentId
    //                 ? $studentModel->getIMActivitySubmission($act['id'], $studentId)
    //                 : null,
    //         ];
    //     }

    //     // Per-quiz: questions + result
    //     $quizData = [];
    //     foreach ($quizzes as $qz) {
    //         $quizData[$qz['id']] = [
    //             'quiz' => $qz,
    //             'questions' => $studentModel->getIMQuizQuestions($qz['id']),
    //             'result' => $studentId
    //                 ? $studentModel->getIMQuizResult($qz['id'], $studentId)
    //                 : null,
    //         ];
    //     }

    //     // Completion status per lesson for sidebar checkmarks
    //     $lessonCompletion = [];
    //     $totalLessons = count($lessons);
    //     $completedCount = 0;

    //     foreach ($lessons as $l) {
    //         $done = $studentId ? $studentModel->isLessonCompleted($l['id'], $studentId) : false;
    //         $lessonCompletion[$l['id']] = $done;
    //         if ($done)
    //             $completedCount++;
    //     }

    //     // ── ADD THESE LINES ──────────────────────────────────────
    //     $currentIndex = 0;
    //     $prevLessonId = null;
    //     $nextLessonId = null;
    //     foreach ($lessons as $i => $l) {
    //         if ($l['id'] == $lessonId) {
    //             $currentIndex = $i + 1;
    //             $prevLessonId = $lessons[$i - 1]['id'] ?? null;
    //             $nextLessonId = $lessons[$i + 1]['id'] ?? null;
    //             break;
    //         }
    //     }
    //     // ─────────────────────────────────────────────────────────

    //     // Also get student name for sidebar display
    //     $studentName = $_SESSION['student_name'] ?? null;
    //     $studentLrn = $_SESSION['student_lrn'] ?? null;

    //     require "../app/view/subject_lessons.php";
    // }

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

        if (!empty($data['activities'])) {
            foreach ($data['activities'] as $actId => $answers) {
                $actId = (int) $actId;
                if (!$actId)
                    continue;
                $existing = $studentModel->getIMActivitySubmission($actId, $studentId);
                if (!$existing) {
                    $studentModel->saveIMActivitySubmission($actId, $studentId, json_encode($answers));
                    $actInfo = $studentModel->getIMActivityById($actId);
                    if ($actInfo) {
                        $studentModel->logActivity($studentId, 'activity_completed', $actInfo['title'], $actInfo['subject_name']);
                    }
                }
            }
        }

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
                    $studentModel->saveIMQuizResult($qzId, $studentId, $score, $total, $passingScore, json_encode($answers));

                    $quizTitle = $questions[0]['title'] ?? 'Quiz';
                    $quizLessonId = (int) ($data['lesson_id'] ?? 0);
                    $quizLessonRow = $quizLessonId ? $studentModel->getIMLessonById($quizLessonId) : null;
                    $studentModel->logActivity($studentId, 'quiz_completed', $quizTitle, $quizLessonRow['subject_name'] ?? null);
                }
            }
        }

        $lessonId = (int) ($data['lesson_id'] ?? 0);
        if ($lessonId) {
            $studentModel->markLessonVisited($lessonId, $studentId);
        }

        $moduleId = 0;
        if ($lessonId) {
            $lessonRow = $studentModel->getIMLessonById($lessonId);
            if ($lessonRow) {
                $moduleId = (int) $lessonRow['module_id'];
                $studentModel->updateModuleProgress($moduleId, $studentId);
            }
        }

        $completedCount = 0;
        $totalLessons = 0;
        if ($lessonId) {
            $lesson = $studentModel->getIMLessonById($lessonId);
            if ($lesson) {
                $moduleId = (int) $lesson['module_id'];
                $allLessons = $studentModel->getIMLessons($moduleId);
                $totalLessons = count($allLessons);
                foreach ($allLessons as $l) {
                    if ($studentModel->isLessonCompleted($l['id'], $studentId)) {
                        $completedCount++;
                    }
                }
            }
        }

        echo json_encode([
            'ok' => true,
            'completed_count' => $completedCount,
            'total_lessons' => $totalLessons,
        ]);
        exit;
    }

    public function mark_flashcards_viewed()
    {
        header('Content-Type: application/json');
        $studentId = $_SESSION['student_id'] ?? 0;
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);

        if ($studentId && $lessonId) {
            $studentModel = new Students();
            $lesson = $studentModel->getIMLessonById($lessonId);
            if ($lesson) {
                $studentModel->logActivity(
                    $studentId,
                    'flashcards_viewed',
                    $lesson['title'] . ' — Flashcards',
                    $lesson['subject_name'],
                    15
                );
            }
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false]);
        }
        exit;
    }

    public function submit_activity()
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

        if (!$studentId) {
            echo json_encode(['ok' => false, 'msg' => 'not logged in']);
            exit;
        }

        $activityId = (int) ($_POST['activity_id'] ?? 0);
        $lessonId = (int) ($_POST['lesson_id'] ?? 0);

        if (!$activityId) {
            echo json_encode(['ok' => false, 'msg' => 'no activity_id']);
            exit;
        }

        $studentModel = new Students();

        $existing = $studentModel->getIMActivitySubmission($activityId, $studentId);
        if (!$existing) {
            $answers = $_POST['answers'] ?? [];
            $studentModel->saveIMActivitySubmission($activityId, $studentId, json_encode($answers));

            // ✅ NEW — update progress right here, don't depend on save_lesson_answers()
            $actInfo = $studentModel->getIMActivityById($activityId);
            if ($actInfo) {
                $studentModel->logActivity($studentId, 'activity_completed', $actInfo['title'], $actInfo['subject_name']);
            }

            if ($lessonId) {
                $studentModel->markLessonVisited($lessonId, $studentId);
                $lessonRow = $studentModel->getIMLessonById($lessonId);
                if ($lessonRow) {
                    $studentModel->updateModuleProgress((int) $lessonRow['module_id'], $studentId);
                }
            }
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

    public function getUpcomingDeadlines($studentId, $days = 30, $limit = 10)
    {
        $stmt = $this->db->prepare("
        SELECT a.id, a.task, a.due_date, a.due_time, s.subject_code, s.subject_name
        FROM tbl_assignments a
        JOIN tbl_subjects s ON a.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE a.id NOT IN (
            SELECT assignment_id FROM tbl_assignment_submissions WHERE student_id = ?
        )
        AND a.due_date IS NOT NULL
        AND a.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
        ORDER BY a.due_date ASC, a.due_time ASC
        LIMIT ?
    ");
        $stmt->bind_param("iiii", $studentId, $studentId, $days, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function dashboardView()
    {
        date_default_timezone_set('Asia/Manila');

        $user_id = (int) ($_SESSION['user_id'] ?? 0);

        if (!$user_id) {
            header("Location: ?url=login");
            exit;
        }

        $studentModel = new Students();
        $studentId = $studentModel->getStudentIdByUserId($user_id);

        if ($studentId) {
            $_SESSION['student_id'] = $studentId;
            $gradeSection = $studentModel->getStudentGradeAndSection($studentId);
            if ($gradeSection) {
                $_SESSION['grade_level'] = $gradeSection['name'];
                $_SESSION['section'] = $gradeSection['section_name'];
            }
        }

        $pendingAssignments = $studentId ? $studentModel->getPendingAssignments($studentId) : [];
        $missingAssignments = $studentId ? $studentModel->getMissingAssignments($studentId) : [];
        $upcomingDeadlines = $studentId ? $studentModel->getUpcomingDeadlines($studentId, 60) : []; // ✅ ADDED — fixes empty "Upcoming deadlines" card
        $pendingCount = $studentId ? $studentModel->countPendingAssignments($studentId) : 0;
        $enrolledCount = $studentId ? $studentModel->countEnrolledClasses($studentId) : 0;
        $announcements = $studentId ? $studentModel->getDashboardAnnouncements($studentId) : [];
        $completedCount = $studentId ? $studentModel->countCompletedAssignments($studentId) : 0;

        // ✅ NEW — this was never being fetched, so "My Recent Activity" was always empty
        $recentActivities = $studentId ? $studentModel->getRecentActivity($studentId, 15) : [];

        $dueSoonCount = $studentId ? $studentModel->countDueSoonAssignments($studentId) : 0;
        $pendingDueThisWeek = $studentId ? $studentModel->countPendingDueThisWeek($studentId) : 0;
        $completedThisWeek = $studentId ? $studentModel->countCompletedThisWeek($studentId) : 0;
        $overallProgressPercent = $studentId ? $studentModel->getOverallProgressForStudent($studentId) : 0;
        $overallProgressDelta = $studentId ? $studentModel->getOverallProgressDeltaThisWeek($studentId) : 0;

        // ✅ NEW — was missing, this is why "Your Current Progress" was always empty
        $inProgressModules = $studentId ? $studentModel->getInProgressModulesForStudent($studentId) : [];

        // ✅ NEW — attach "next up" lesson info, and the current topic, for each in-progress module
        foreach ($inProgressModules as &$ip) {
            $completed = (int) ($ip['completed_lessons'] ?? 0);

            // Next up = the lesson right after the last completed one
            $nextLesson = $studentModel->getLessonByPosition((int) $ip['id'], $completed);
            $rawNextTitle = $nextLesson['title'] ?? null;
            $ip['next_lesson_title'] = $rawNextTitle
                ? preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $rawNextTitle)
                : null;
            $ip['next_lesson_number'] = $completed + 1;

            // Current topic = the topic of the last completed lesson (or the
            // 1st lesson's topic if nothing is completed yet)
            $currentOffset = $completed > 0 ? $completed - 1 : 0;
            $currentLesson = $studentModel->getLessonByPosition((int) $ip['id'], $currentOffset);
            $ip['current_topic'] = $currentLesson['topic'] ?? null;
        }
        unset($ip);

        require "../app/view/dashboard.php";
    }

    public function assignments_view()
    {
        date_default_timezone_set('Asia/Manila');

        $studentId = 0;
        if (!empty($_SESSION['student_id'])) {
            $studentId = (int) $_SESSION['student_id'];
        }
        if (!$studentId && !empty($_SESSION['user_id'])) {
            $subjectModel = new subjects();
            $studentRow = $subjectModel->getStudentByUserId((int) $_SESSION['user_id']);
            if ($studentRow) {
                $studentId = (int) $studentRow['id'];
                $_SESSION['student_id'] = $studentId;
            }
        }

        $studentModel = new Students();

        $pendingAssignments = $studentId ? $studentModel->getPendingAssignments($studentId) : [];
        $missingAssignments = $studentId ? $studentModel->getMissingAssignments($studentId) : [];
        $completedAssignments = $studentId ? $studentModel->getCompletedAssignments($studentId) : [];

        $pendingCount = count($pendingAssignments);
        $missingCount = count($missingAssignments);
        $completedCount = count($completedAssignments);
        $gradedCount = 0;

        // ---- Tag every assignment with one status ----
        $allItems = [];

        foreach ($pendingAssignments as $item) {
            $daysLeft = !empty($item['due_date']) ? (int) ceil((strtotime($item['due_date']) - time()) / 86400) : null;
            $urgency = 'normal';
            if ($daysLeft !== null) {
                if ($daysLeft <= 1)
                    $urgency = 'urgent';
                elseif ($daysLeft <= 3)
                    $urgency = 'soon';
            }
            $allItems[] = array_merge($item, [
                'status' => 'pending',
                'urgency' => $urgency,
                'days_left' => $daysLeft,
                'points_earned' => null,
            ]);
        }

        foreach ($missingAssignments as $item) {
            $daysOverdue = null;
            $overdueLabel = null;

            if (!empty($item['due_date'])) {
                $dueDateObj = new DateTime($item['due_date']);
                $now = new DateTime();
                $interval = $now->diff($dueDateObj); // calendar-aware — respects real month lengths

                $daysOverdue = (int) $interval->days; // kept for anything else that still needs a raw day count

                $parts = [];
                if ($interval->y > 0) {
                    $parts[] = $interval->y . ' year' . ($interval->y === 1 ? '' : 's');
                }
                if ($interval->m > 0) {
                    $parts[] = $interval->m . ' month' . ($interval->m === 1 ? '' : 's');
                }
                // Only show days once we're below a month, or if nothing else applies
                if ($interval->d > 0 && $interval->y === 0) {
                    $parts[] = $interval->d . ' day' . ($interval->d === 1 ? '' : 's');
                }
                if (empty($parts)) {
                    $parts[] = 'less than a day';
                }

                $overdueLabel = implode(', ', $parts) . ' overdue';
            }

            $allItems[] = array_merge($item, [
                'status' => 'missing',
                'urgency' => 'urgent',
                'days_overdue' => $daysOverdue,
                'overdue_label' => $overdueLabel,   // ✅ NEW
                'points_earned' => null,
            ]);
        }

        foreach ($completedAssignments as $item) {
            $isGraded = isset($item['points_earned']) && $item['points_earned'] !== null;
            if ($isGraded)
                $gradedCount++;
            $allItems[] = array_merge($item, [
                'status' => $isGraded ? 'graded' : 'completed',
                'urgency' => 'normal',
            ]);
        }

        // ✅ ADD THIS — completed-but-not-yet-graded, separate from graded
        $submittedOnlyCount = $completedCount - $gradedCount;

        // ---- Group by subject ----
        $subjectGroups = [];
        foreach ($allItems as $item) {
            $code = $item['subject_code'] ?? 'general';
            if (!isset($subjectGroups[$code])) {
                $subjectGroups[$code] = [
                    'subject_code' => $code,
                    'subject_name' => $item['subject_name'] ?? $code,
                    'items' => [],
                ];
            }
            $subjectGroups[$code]['items'][] = $item;
        }

        foreach ($subjectGroups as &$grp) {
            usort($grp['items'], function ($a, $b) {
                $rank = ['missing' => 0, 'pending' => 1, 'completed' => 2, 'graded' => 2];
                $ra = $rank[$a['status']] ?? 3;
                $rb = $rank[$b['status']] ?? 3;
                if ($ra !== $rb)
                    return $ra <=> $rb;
                return strtotime($a['due_date'] ?? $a['submitted_at'] ?? 'now') <=> strtotime($b['due_date'] ?? $b['submitted_at'] ?? 'now');
            });

            $total = count($grp['items']);
            $done = 0;
            foreach ($grp['items'] as $it) {
                if (in_array($it['status'], ['completed', 'graded'], true))
                    $done++;
            }
            $grp['total'] = $total;
            $grp['done'] = $done;
            $grp['percentage'] = $total > 0 ? round(($done / $total) * 100) : 0;
        }
        unset($grp);

        // ---- Top stats ----
        $totalAssignments = count($allItems);
        $urgentCount = 0;
        $scoreSum = 0;
        $scoreCount = 0;
        $typeCounts = [];

        foreach ($allItems as $it) {
            if ($it['urgency'] === 'urgent' && in_array($it['status'], ['pending', 'missing'], true)) {
                $urgentCount++;
            }
            if ($it['status'] === 'graded' && (int) $it['total_points'] > 0) {
                $scoreSum += ($it['points_earned'] / $it['total_points']) * 100;
                $scoreCount++;
            }
            $slug = strtolower(str_replace(' ', '-', trim((string) ($it['type'] ?? ''))));
            if ($slug !== '')
                $typeCounts[$slug] = ($typeCounts[$slug] ?? 0) + 1;
        }
        $avgScore = $scoreCount > 0 ? round($scoreSum / $scoreCount, 1) : null;

        require "../app/view/assignments.php";
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
        $studentModel = new Students(); // add this
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

        // Build a teacher map: subject_id => teacher data
        $teacherMap = [];
        foreach ($subjects as $subject) {
            $teacher = $studentModel->getTeacherBySubjectId((int) $subject['id']);
            $teacherMap[$subject['id']] = $teacher;
        }

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