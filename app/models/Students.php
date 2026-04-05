<?php

require_once "../core/Model.php";

class Students extends Model
{

    public function getGradedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT a.id, a.task, a.due_date, a.points AS total_points,
               s.subject_code, sub.submitted_at, sub.graded_at,
               sub.points_earned, sub.feedback
        FROM assignments a
        JOIN assignment_submissions sub ON sub.assignment_id = a.id
        JOIN subjects s ON a.subject_id = s.id
        WHERE sub.student_id = ? AND sub.points_earned IS NOT NULL
        ORDER BY sub.graded_at DESC
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countGradedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total FROM assignment_submissions
        WHERE student_id = ? AND points_earned IS NOT NULL
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    // ============================================================
    // MODULE VIEW
    // ============================================================
    public function getModuleByIdAndSlug($moduleId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT m.id, m.title, m.description, m.posted_at, s.subject_name, s.subject_code
            FROM modules m JOIN subjects s ON m.subject_id = s.id
            WHERE m.id = ? AND s.subject_code = ? LIMIT 1
        ");
        $stmt->bind_param("is", $moduleId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getModuleMaterials($moduleId)
    {
        $stmt = $this->db->prepare("SELECT id, file_name, file_path, file_type FROM modules WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row && !empty($row['file_name'])) {
            return [['id' => $row['id'], 'file_name' => $row['file_name'], 'file_path' => $row['file_path'], 'file_type' => $row['file_type']]];
        }
        return [];
    }

    public function getAssignmentSubmission($assignmentId, $studentId)
    {
        $stmt = $this->db->prepare("
        SELECT id, submitted_at FROM assignment_submissions
        WHERE assignment_id = ? AND student_id = ? LIMIT 1
    ");
        $stmt->bind_param("ii", $assignmentId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveAssignmentSubmission($assignmentId, $studentId, $filePath, $message)
    {
        $stmt = $this->db->prepare("
        INSERT INTO assignment_submissions 
            (assignment_id, student_id, file_path, message, submitted_at, status)
        VALUES (?, ?, ?, ?, NOW(), 'submitted')
    ");
        $stmt->bind_param("iiss", $assignmentId, $studentId, $filePath, $message);
        return $stmt->execute();
    }

    // ============================================================
    // ASSIGNMENT VIEW
    // ============================================================
    public function deleteAssignmentSubmission($assignmentId, $studentId)
    {
        $stmt = $this->db->prepare("
        DELETE FROM assignment_submissions
        WHERE assignment_id = ? AND student_id = ?
        LIMIT 1
    ");
        $stmt->bind_param("ii", $assignmentId, $studentId);
        return $stmt->execute();
    }

    public function getAssignmentByIdAndSlug($assignmentId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT a.id, a.title, a.description, a.task, a.instructions, a.posted_at, a.points, a.due_date, s.subject_name, s.subject_code
            FROM assignments a JOIN subjects s ON a.subject_id = s.id
            WHERE a.id = ? AND s.subject_code = ? LIMIT 1
        ");
        $stmt->bind_param("is", $assignmentId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAssignmentTemplates($assignmentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_name, file_path, file_type
            FROM assignments WHERE id = ?
        ");
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // ANNOUNCEMENT VIEW
    // ============================================================
    public function getAnnouncementByIdAndSlug($announcementId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT an.id, an.title, an.body, an.posted_at,
                   u.name AS teacher_name, s.subject_name, s.slug
            FROM announcements an
            JOIN subjects s ON an.subject_id = s.id
            JOIN users    u ON an.posted_by  = u.id
            WHERE an.id = ? AND s.slug = ? LIMIT 1
        ");
        $stmt->bind_param("is", $announcementId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ============================================================
    // SUBJECT FEED
    // ============================================================
    public function getSubjectFeed($subjectSlug)
    {
        $stmt = $this->db->prepare("
            (SELECT 'module' AS type, m.id, 'New Material' AS label,
                    m.title AS heading, m.description AS subtext, m.posted_at AS date
             FROM modules m JOIN subjects s ON m.subject_id = s.id WHERE s.subject_code = ?)
            UNION ALL
            (SELECT 'assignment', a.id, 'New Assignment',
                    a.title, a.description, a.posted_at
             FROM assignments a JOIN subjects s ON a.subject_id = s.id WHERE s.subject_code = ?)
            UNION ALL
            (SELECT 'announcement', an.id, 'Announcement',
                    an.title, an.body, an.posted_at
             FROM announcements an JOIN subjects s ON an.subject_id = s.id WHERE s.subject_code = ?)
            ORDER BY date DESC
        ");
        $stmt->bind_param("sss", $subjectSlug, $subjectSlug, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // INTERACTIVE MODULES
    // ============================================================
    public function getSubjectBySlug($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT id, subject_name, subject_code, subject_description
            FROM subjects WHERE subject_code = ? LIMIT 1
        ");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getInteractiveModules($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description
            FROM interactive_modules im
            JOIN subjects s ON im.subject_id = s.id
            WHERE s.subject_code = ?
            ORDER BY im.id ASC
        ");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countIMlessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total FROM lessons WHERE interactive_module_id = ?
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getInteractiveModuleById($id)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description, s.subject_name, s.subject_code
            FROM interactive_modules im
            JOIN subjects s ON im.subject_id = s.id
            WHERE im.id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMLessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, topic
            FROM lessons
            WHERE interactive_module_id = ?
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // UPDATED: uses interactive_contents instead of
    //          activities, quizzes, im_flashcards
    // ============================================================
    public function getIMLessonsWithCounts($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT
                l.id, l.title, l.topic,
                (SELECT COUNT(*) FROM interactive_contents WHERE lesson_id = l.id AND type = 'activity')  AS activity_count,
                (SELECT COUNT(*) FROM interactive_contents WHERE lesson_id = l.id AND type = 'quiz')      AS quiz_count,
                (SELECT COUNT(*) FROM interactive_contents WHERE lesson_id = l.id AND type = 'flashcard') AS flashcard_count
            FROM lessons l
            WHERE l.interactive_module_id = ?
            ORDER BY l.id ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMLessonById($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT l.id, l.title, l.topic, l.content,
                   im.id AS module_id, im.title AS module_title,
                   s.subject_name, s.subject_code
            FROM lessons l
            JOIN interactive_modules im ON l.interactive_module_id = im.id
            JOIN subjects s ON im.subject_id = s.id
            WHERE l.id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMLessonPdfs($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT mm.id, mm.file_name, mm.file_path, mm.file_type
            FROM im_lesson_pdfs lp
            JOIN module_materials mm ON lp.module_material_id = mm.id
            WHERE lp.lesson_id = ?
            ORDER BY lp.id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdjacentIMLessons($lessonId, $interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title FROM lessons
            WHERE interactive_module_id = ?
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        $lessons = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $prev = null;
        $next = null;
        foreach ($lessons as $i => $l) {
            if ($l['id'] == $lessonId) {
                $prev = $lessons[$i - 1] ?? null;
                $next = $lessons[$i + 1] ?? null;
                break;
            }
        }
        return ['prev' => $prev, 'next' => $next];
    }

    public function getLessonImages($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_path, file_name
            FROM interactive_contents
            WHERE lesson_id = ? AND type = 'image'
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonVideos($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_path, title
            FROM interactive_contents
            WHERE lesson_id = ? AND type = 'video'
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonActivities($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, total_points
            FROM interactive_contents
            WHERE lesson_id = ? AND type = 'activity'
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonQuizzes($lessonId)
    {
        $stmt = $this->db->prepare("
        SELECT MIN(id) AS id, title, instructions, passing_score
        FROM interactive_contents
        WHERE lesson_id = ? AND type = 'quiz'
        GROUP BY title, instructions, passing_score
        ORDER BY MIN(id) ASC
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonFlashcards($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, card_type, card_front, card_back
            FROM interactive_contents
            WHERE lesson_id = ? AND type = 'flashcard'
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function isLessonCompleted($lessonId, $studentId)
    {
        if (!$studentId)
            return false;

        // Get quiz groups (same MIN(id) logic as getLessonQuizzes)
        $stmt = $this->db->prepare("
        SELECT MIN(id) AS quiz_id
        FROM interactive_contents
        WHERE lesson_id = ? AND type = 'quiz'
        GROUP BY title
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $quizGroups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Check activity count
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT title) AS acount
        FROM interactive_contents
        WHERE lesson_id = ? AND type = 'activity'
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $acount = (int) $stmt->get_result()->fetch_assoc()['acount'];

        // No quiz and no activity — complete just by visiting
        if (empty($quizGroups) && $acount === 0) {
            return $this->isLessonVisited($lessonId, $studentId);
        }

        // Check each quiz group has a saved result
        foreach ($quizGroups as $group) {
            $stmt = $this->db->prepare("
            SELECT id FROM quiz_results
            WHERE content_id = ? AND student_id = ? LIMIT 1
        ");
            $stmt->bind_param("ii", $group['quiz_id'], $studentId);
            $stmt->execute();
            if (!$stmt->get_result()->fetch_assoc())
                return false;
        }

        // Check activity submission exists (by lesson, not by specific content_id)
        if ($acount > 0) {
            $stmt = $this->db->prepare("
            SELECT s.id FROM activity_submissions s
            JOIN interactive_contents ic ON s.content_id = ic.id
            WHERE ic.lesson_id = ? AND ic.type = 'activity'
            AND s.student_id = ? LIMIT 1
        ");
            $stmt->bind_param("ii", $lessonId, $studentId);
            $stmt->execute();
            if (!$stmt->get_result()->fetch_assoc())
                return false;
        }

        return true;
    }

    // ============================================================
    // MODULE-LEVEL (flashcards, activity, quiz)
    // ============================================================
    public function getIMFlashcards($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT ic.id, ic.card_type, ic.card_front, ic.card_back
            FROM interactive_contents ic
            JOIN lessons l ON ic.lesson_id = l.id
            WHERE l.interactive_module_id = ? AND ic.type = 'flashcard'
            ORDER BY ic.id ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMActivity($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT ic.id, ic.title, ic.instructions, ic.total_points
            FROM interactive_contents ic
            JOIN lessons l ON ic.lesson_id = l.id
            WHERE l.interactive_module_id = ? AND ic.type = 'activity'
            ORDER BY ic.id ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMActivityQuestions($activityId)
    {
        $stmt = $this->db->prepare("
            SELECT id, question_type, question, model_answer,
                   choice_a, choice_b, choice_c, choice_d, correct_ans, total_points AS points
            FROM interactive_contents
            WHERE id = ? AND type = 'activity'
            LIMIT 1
        ");
        $stmt->bind_param("i", $activityId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? [$row] : [];
    }

    public function getIMActivitySubmission($activityId, $studentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, answers, submitted_at FROM activity_submissions
            WHERE content_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $activityId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveIMActivitySubmission($activityId, $studentId, $answersJson)
    {
        $stmt = $this->db->prepare("
            INSERT INTO activity_submissions (content_id, student_id, answers, submitted_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $activityId, $studentId, $answersJson);
        return $stmt->execute();
    }

    public function getIMQuiz($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT ic.id, ic.title, ic.instructions, ic.passing_score
            FROM interactive_contents ic
            JOIN lessons l ON ic.lesson_id = l.id
            WHERE l.interactive_module_id = ? AND ic.type = 'quiz'
            ORDER BY ic.id ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMQuizQuestions($quizId)
    {
        // Get the title and lesson_id of the quiz group
        $stmt = $this->db->prepare("
        SELECT title, lesson_id FROM interactive_contents 
        WHERE id = ? AND type = 'quiz' LIMIT 1
    ");
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        $quiz = $stmt->get_result()->fetch_assoc();
        if (!$quiz)
            return [];

        // Fetch ALL questions with the same title in the same lesson
        $stmt = $this->db->prepare("
        SELECT id, question, choice_a, choice_b, choice_c, choice_d, 
               correct_ans, total_points AS points
        FROM interactive_contents
        WHERE lesson_id = ? AND type = 'quiz' AND title = ?
        ORDER BY id ASC
    ");
        $stmt->bind_param("is", $quiz['lesson_id'], $quiz['title']);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMQuizResult($quizId, $studentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, score, total, passed, answers_json, taken_at FROM quiz_results WHERE content_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $quizId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveIMQuizResult($quizId, $studentId, $score, $total, $passingScore, $answersJson = null)
    {
        $passed = ($total > 0 && (($score / $total) * 100) >= $passingScore) ? 1 : 0;
        $stmt = $this->db->prepare("
        INSERT INTO quiz_results (content_id, student_id, score, total, passed, answers_json, taken_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param("iiiiss", $quizId, $studentId, $score, $total, $passed, $answersJson);
        return $stmt->execute();
    }

    public function markLessonVisited($lessonId, $studentId)
    {
        if (!$studentId || !$lessonId)
            return false;

        $stmt = $this->db->prepare("
            INSERT IGNORE INTO lesson_visits (lesson_id, student_id, visited_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ii", $lessonId, $studentId);
        return $stmt->execute();
    }

    public function isLessonVisited($lessonId, $studentId)
    {
        if (!$studentId || !$lessonId)
            return false;

        $stmt = $this->db->prepare("
            SELECT id FROM lesson_visits
            WHERE lesson_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $lessonId, $studentId);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }

    public function markSubjectStarted($subjectSlug, $studentId)
    {
        if (!$studentId || !$subjectSlug)
            return false;

        $stmt = $this->db->prepare("SELECT id FROM subjects WHERE subject_code = ? LIMIT 1");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row)
            return false;
        $subjectId = (int) $row['id'];

        $stmt = $this->db->prepare("
            INSERT IGNORE INTO subject_starts (subject_id, student_id, started_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ii", $subjectId, $studentId);
        return $stmt->execute();
    }

    public function getStartedSubjectSlugs($studentId)
    {
        if (!$studentId)
            return [];

        $stmt = $this->db->prepare("
            SELECT s.subject_code
            FROM subject_starts ss
            JOIN subjects s ON ss.subject_id = s.id
            WHERE ss.student_id = ?
        ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'subject_code');
    }

    public function markModuleStarted($moduleId, $studentId)
    {
        if (!$moduleId || !$studentId)
            return false;
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO module_starts (interactive_modules_id, student_id, started_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ii", $moduleId, $studentId);
        return $stmt->execute();
    }

    public function getStartedModuleIds($studentId)
    {
        if (!$studentId)
            return [];
        $stmt = $this->db->prepare("
            SELECT interactive_modules_id FROM module_starts WHERE student_id = ?
        ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'interactive_modules_id');
    }

    public function getCompletedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT a.id, a.task, a.due_date, s.subject_code, sub.submitted_at
        FROM assignments a
        JOIN assignment_submissions sub ON sub.assignment_id = a.id
        JOIN subjects s ON a.subject_id = s.id
        WHERE sub.student_id = ?
        ORDER BY sub.submitted_at DESC
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT a.id, a.task, a.due_date, s.subject_code
        FROM assignments a
        JOIN subjects s ON a.subject_id = s.id
        JOIN student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE a.id NOT IN (
            SELECT assignment_id FROM assignment_submissions WHERE student_id = ?
        )
        ORDER BY a.due_date ASC
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countCompletedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total FROM assignment_submissions WHERE student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countPendingAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM assignments a
        JOIN subjects s ON a.subject_id = s.id
        JOIN student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE a.id NOT IN (
            SELECT assignment_id FROM assignment_submissions WHERE student_id = ?
        )
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }
}