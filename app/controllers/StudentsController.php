<?php

require_once "../core/Model.php";
require_once "../app/models/subjects.php";
require_once "../app/models/Students.php";  // ← only new line added

class StudentsController
{

    // ── INTERACTIVE MODULES ────────────────────────────────────
    // ?url=modules&subject=philosophy
    // Fixed: now fetches $subjectInfo, $modules, $lessonCounts
    // before loading modules.php so philosophy_modules.php
    // has all the variables it needs
    public function modules()
    {
        $studentModel = new Students();

        $subject = $_GET['subject'] ?? null;
        $subjectInfo = $subject ? $studentModel->getSubjectBySlug($subject) : null;
        $modules = $subject ? $studentModel->getInteractiveModules($subject) : [];

        // Count lessons per module for the card display
        $lessonCounts = [];
        foreach ($modules as $mod) {
            $lessonCounts[$mod['id']] = $studentModel->countIMlessons($mod['id']);
        }

        require "../app/view/modules.php";
    }

    // ── MODULE DETAIL ──────────────────────────────────────────
    // ?url=module_detail&subject=philosophy&id=1
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
    // ?url=lesson_view&id=1
    public function lesson_view()
    {
        $studentModel = new Students();

        $lessonId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $lesson = $lessonId ? $studentModel->getIMLessonById($lessonId) : null;
        $pdfs = $lesson ? $studentModel->getIMLessonPdfs($lessonId) : [];
        $adjacent = $lesson ? $studentModel->getAdjacentIMLessons($lessonId, $lesson['module_id']) : ['prev' => null, 'next' => null];

        require "../app/view/lesson_view.php";
    }

    // ── SAVE ACTIVITY ──────────────────────────────────────────
    // POST ?url=save_activity
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

    // ── SAVE QUIZ ──────────────────────────────────────────────
    // POST ?url=save_quiz
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
                    if (strtolower($_POST['answer_' . $q['id']] ?? '') === strtolower($q['correct_ans'])) {
                        $score++;
                    }
                }
                $studentModel->saveIMQuizResult($quizId, $studentId, $score, $total, $passingScore);
            }
        }

        $moduleId = (int) ($_POST['module_id'] ?? 0);
        $subject = $_POST['subject'] ?? '';
        header("Location: /learning_management/public/?url=module_detail&subject={$subject}&id={$moduleId}&tab=quiz");
        exit;
    }

    // ── EXISTING METHODS — exactly as you wrote them ───────────

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
        $studentModel = new Students();

        $assignmentId = isset($_GET['id']) ? (int) trim($_GET['id']) : 0;
        $subjectSlug = isset($_GET['subject']) ? trim($_GET['subject']) : '';

        $assignment = $studentModel->getAssignmentByIdAndSlug($assignmentId, $subjectSlug);
        $templates = $assignment ? $studentModel->getAssignmentTemplates($assignmentId) : [];

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

        // ✅ Use the model to fetch student record by user_id
        $user_id = $_SESSION['user_id'];
        $studentRow = $subjectModel->getStudentByUserId($user_id);

        if (!$studentRow) {
            die("Student record not found. Please contact your administrator.");
        }

        $student_id = $studentRow['id'];
        $section_id = $studentRow['section_id'];

        // Store in session for future use
        $_SESSION['student_id'] = $student_id;
        $_SESSION['section_id'] = $section_id;

        // ✅ Handle enroll action
        if (isset($_GET['enroll']) && isset($_GET['subject_id']) && isset($_GET['subject_slug'])) {
            $subject_id = (int) $_GET['subject_id'];
            $subject_slug = $_GET['subject_slug'];

            if (!$subjectModel->isEnrolled($student_id, $subject_id)) {
                $correct_section_id = $subjectModel->getSectionForSubject($student_id, $subject_id);
                $enroll_section_id = $correct_section_id ?? $section_id;
                $subjectModel->enrollStudent($student_id, $subject_id, $enroll_section_id);
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

        if (!$studentRow) {
            die("Student record not found. Please contact your administrator.");
        }

        $student_id = $studentRow['id'];
        $section_id = $studentRow['section_id'];

        $_SESSION['student_id'] = $student_id;
        $_SESSION['section_id'] = $section_id;

        if (isset($_GET['enroll']) && isset($_GET['subject_id']) && isset($_GET['subject_slug'])) {
            $subject_id = (int) $_GET['subject_id'];
            $subject_slug = $_GET['subject_slug'];

            if (!$subjectModel->isEnrolled($student_id, $subject_id)) {
                $correct_section_id = $subjectModel->getSectionForSubject($student_id, $subject_id);
                $enroll_section_id = $correct_section_id ?? $section_id;
                $subjectModel->enrollStudent($student_id, $subject_id, $enroll_section_id);
            }

            header("Location: /learning_management/public/?url=subjects&subject=" . urlencode($subject_slug));
            exit();
        }

        $subjects = $subjectModel->getSubjectsByGradeLevel($grade_level_id);

        // ✅ Fetch both grade levels for the Learning Catalog
        $grade11Subjects = $subjectModel->getGrade11Subjects();
        $grade12Subjects = $subjectModel->getGrade12Subjects();

        $enrolledSubjectIds = $subjectModel->getEnrolledSubjectIds($student_id);

        require "../app/view/module_all.php";
    }
}