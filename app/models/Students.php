<?php

require_once "../core/Model.php";

class Students extends Model
{

    // ============================================================
    // MODULE VIEW
    // Used by: module_view.php
    // Fetches a single module + verifies it belongs to the
    // correct subject using the slug from the URL
    // ============================================================

    public function getModuleByIdAndSlug($moduleId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT
                m.id,
                m.title,
                m.description,
                m.posted_at,
                s.subject_name,
                s.slug
            FROM modules m
            JOIN subjects s ON m.subject_id = s.id
            WHERE m.id   = ?
              AND s.slug  = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $moduleId, $subjectSlug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getModuleMaterials($moduleId)
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                file_name,
                file_path,
                file_type
            FROM module_materials
            WHERE module_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // ============================================================
    // ASSIGNMENT VIEW
    // Used by: assignment_view.php
    // Fetches a single assignment + its template files
    // ============================================================

    public function getAssignmentByIdAndSlug($assignmentId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT
                a.id,
                a.title,
                a.description,
                a.posted_at,
                a.due_date,
                s.subject_name,
                s.slug
            FROM assignments a
            JOIN subjects s ON a.subject_id = s.id
            WHERE a.id   = ?
              AND s.slug  = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $assignmentId, $subjectSlug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getAssignmentTemplates($assignmentId)
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                file_name,
                file_path,
                file_type
            FROM assignment_templates
            WHERE assignment_id = ?
        ");
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // ============================================================
    // ANNOUNCEMENT VIEW
    // Used by: announcement_view.php
    // Fetches a single announcement + teacher name
    // ============================================================

    public function getAnnouncementByIdAndSlug($announcementId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT
                an.id,
                an.title,
                an.body,
                an.posted_at,
                u.name  AS teacher_name,
                s.subject_name,
                s.slug
            FROM announcements an
            JOIN subjects s ON an.subject_id = s.id
            JOIN users    u ON an.posted_by  = u.id
            WHERE an.id  = ?
              AND s.slug  = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $announcementId, $subjectSlug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    // ============================================================
    // SUBJECT FEED
    // Used by: philosophy.php, ucsp.php, css.php, etc.
    // Fetches all modules + assignments + announcements for
    // a subject — combined into one feed sorted by date
    // ============================================================

    public function getSubjectFeed($subjectSlug)
    {
        $stmt = $this->db->prepare("
            (
                SELECT
                    'module'        AS type,
                    m.id            AS id,
                    'New Material'  AS label,
                    m.title         AS heading,
                    m.description   AS subtext,
                    m.posted_at     AS date
                FROM modules m
                JOIN subjects s ON m.subject_id = s.id
                WHERE s.slug = ?
            )
            UNION ALL
            (
                SELECT
                    'assignment'      AS type,
                    a.id              AS id,
                    'New Assignment'  AS label,
                    a.title           AS heading,
                    a.description     AS subtext,
                    a.posted_at       AS date
                FROM assignments a
                JOIN subjects s ON a.subject_id = s.id
                WHERE s.slug = ?
            )
            UNION ALL
            (
                SELECT
                    'announcement'  AS type,
                    an.id           AS id,
                    'Announcement'  AS label,
                    an.title        AS heading,
                    an.body         AS subtext,
                    an.posted_at    AS date
                FROM announcements an
                JOIN subjects s ON an.subject_id = s.id
                WHERE s.slug = ?
            )
            ORDER BY date DESC
        ");
        // bind 3 times — once for each UNION query
        $stmt->bind_param("sss", $subjectSlug, $subjectSlug, $subjectSlug);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // ============================================================
    // INTERACTIVE MODULES
    // Used by: modules.php, module_detail.php, lesson_view.php
    // ============================================================

    // Get subject info by slug
    public function getSubjectBySlug($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT id, subject_name, slug, subject_description
            FROM subjects WHERE slug = ? LIMIT 1
        ");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get all interactive modules for a subject
    public function getInteractiveModules($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description, im.sort_order
            FROM interactive_modules im
            JOIN subjects s ON im.subject_id = s.id
            WHERE s.slug = ?
            ORDER BY im.sort_order ASC, im.created_at ASC
        ");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Count lessons in an interactive module
    public function countIMlessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total FROM lessons
            WHERE interactive_module_id = ?
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    // Get a single interactive module with subject info
    public function getInteractiveModuleById($id)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description, im.sort_order,
                   s.subject_name, s.slug
            FROM interactive_modules im
            JOIN subjects s ON im.subject_id = s.id
            WHERE im.id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get all lessons for an interactive module
    public function getIMLessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, topic, sort_order
            FROM lessons
            WHERE interactive_module_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get a single lesson with module + subject info
    public function getIMLessonById($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT l.id, l.title, l.topic, l.content, l.sort_order,
                   im.id AS module_id, im.title AS module_title,
                   s.subject_name, s.slug
            FROM lessons l
            JOIN interactive_modules im ON l.interactive_module_id = im.id
            JOIN subjects             s ON im.subject_id            = s.id
            WHERE l.id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get PDFs linked to a lesson (via im_lesson_pdfs → module_materials)
    public function getIMLessonPdfs($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT mm.id, mm.file_name, mm.file_path, mm.file_type
            FROM im_lesson_pdfs lp
            JOIN module_materials mm ON lp.module_material_id = mm.id
            WHERE lp.lesson_id = ?
            ORDER BY lp.sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get prev/next lesson for navigation
    public function getAdjacentIMLessons($lessonId, $interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title FROM lessons
            WHERE interactive_module_id = ?
            ORDER BY sort_order ASC
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

    // Get flashcards for an interactive module
    public function getIMFlashcards($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, card_type, front, back, sort_order
            FROM im_flashcards
            WHERE interactive_module_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get activity for an interactive module
    public function getIMActivity($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, total_points, time_limit
            FROM activities
            WHERE interactive_module_id = ?
            ORDER BY sort_order ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get questions for an activity
    public function getIMActivityQuestions($activityId)
    {
        $stmt = $this->db->prepare("
            SELECT id, question_type, question, points, sort_order
            FROM activity_questions
            WHERE activity_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $activityId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Check if student already submitted activity (1 attempt only)
    public function getIMActivitySubmission($activityId, $studentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, answers, submitted_at
            FROM activity_submissions
            WHERE activity_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $activityId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Save activity submission (1 attempt only)
    public function saveIMActivitySubmission($activityId, $studentId, $answersJson)
    {
        $stmt = $this->db->prepare("
            INSERT INTO activity_submissions
                (activity_id, student_id, answers, submitted_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $activityId, $studentId, $answersJson);
        return $stmt->execute();
    }

    // Get quiz for an interactive module
    public function getIMQuiz($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, time_limit, passing_score
            FROM quizzes
            WHERE interactive_module_id = ?
            ORDER BY sort_order ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Get questions for a quiz
    public function getIMQuizQuestions($quizId)
    {
        $stmt = $this->db->prepare("
            SELECT id, question, choice_a, choice_b, choice_c, choice_d, correct_ans, points
            FROM quiz_questions
            WHERE quiz_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Check if student already took quiz (1 attempt only)
    public function getIMQuizResult($quizId, $studentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, score, total, passed, taken_at
            FROM quiz_results
            WHERE quiz_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $quizId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Save quiz result (1 attempt only)
    public function saveIMQuizResult($quizId, $studentId, $score, $total, $passingScore)
    {
        $passed = ($total > 0 && (($score / $total) * 100) >= $passingScore) ? 1 : 0;
        $stmt = $this->db->prepare("
            INSERT INTO quiz_results
                (quiz_id, student_id, score, total, passed, taken_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiiii", $quizId, $studentId, $score, $total, $passed);
        return $stmt->execute();
    }
}