<?php

require_once "../core/Model.php";

class Students extends Model
{

    // ============================================================
    // MODULE VIEW
    // ============================================================
    public function getModuleByIdAndSlug($moduleId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT m.id, m.title, m.description, m.posted_at, s.subject_name, s.slug
            FROM modules m JOIN subjects s ON m.subject_id = s.id
            WHERE m.id = ? AND s.slug = ? LIMIT 1
        ");
        $stmt->bind_param("is", $moduleId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getModuleMaterials($moduleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_name, file_path, file_type
            FROM module_materials WHERE module_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // ASSIGNMENT VIEW
    // ============================================================
    public function getAssignmentByIdAndSlug($assignmentId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT a.id, a.title, a.description, a.posted_at, a.due_date, s.subject_name, s.slug
            FROM assignments a JOIN subjects s ON a.subject_id = s.id
            WHERE a.id = ? AND s.slug = ? LIMIT 1
        ");
        $stmt->bind_param("is", $assignmentId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAssignmentTemplates($assignmentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_name, file_path, file_type
            FROM assignment_templates WHERE assignment_id = ?
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
             FROM modules m JOIN subjects s ON m.subject_id = s.id WHERE s.slug = ?)
            UNION ALL
            (SELECT 'assignment', a.id, 'New Assignment',
                    a.title, a.description, a.posted_at
             FROM assignments a JOIN subjects s ON a.subject_id = s.id WHERE s.slug = ?)
            UNION ALL
            (SELECT 'announcement', an.id, 'Announcement',
                    an.title, an.body, an.posted_at
             FROM announcements an JOIN subjects s ON an.subject_id = s.id WHERE s.slug = ?)
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
            SELECT id, subject_name, slug, subject_description
            FROM subjects WHERE slug = ? LIMIT 1
        ");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getInteractiveModules($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description, im.sort_order
            FROM interactive_modules im JOIN subjects s ON im.subject_id = s.id
            WHERE s.slug = ? ORDER BY im.sort_order ASC, im.created_at ASC
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
            SELECT im.id, im.title, im.description, im.sort_order, s.subject_name, s.slug
            FROM interactive_modules im JOIN subjects s ON im.subject_id = s.id
            WHERE im.id = ? LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMLessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, topic, sort_order FROM lessons
            WHERE interactive_module_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── NEW: Lessons with counts for sidebar badges ───────────
    public function getIMLessonsWithCounts($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT
                l.id, l.title, l.topic, l.sort_order,
                (SELECT COUNT(*) FROM lesson_videos    WHERE lesson_id = l.id) AS video_count,
                (SELECT COUNT(*) FROM lesson_images    WHERE lesson_id = l.id) AS image_count,
                (SELECT COUNT(*) FROM activities       WHERE lesson_id = l.id) AS activity_count,
                (SELECT COUNT(*) FROM quizzes          WHERE lesson_id = l.id) AS quiz_count,
                (SELECT COUNT(*) FROM im_flashcards    WHERE lesson_id = l.id) AS flashcard_count
            FROM lessons l
            WHERE l.interactive_module_id = ?
            ORDER BY l.sort_order ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

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

    public function getIMLessonPdfs($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT mm.id, mm.file_name, mm.file_path, mm.file_type
            FROM im_lesson_pdfs lp
            JOIN module_materials mm ON lp.module_material_id = mm.id
            WHERE lp.lesson_id = ? ORDER BY lp.sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdjacentIMLessons($lessonId, $interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title FROM lessons
            WHERE interactive_module_id = ? ORDER BY sort_order ASC
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

    // ── NEW: Lesson-level content ─────────────────────────────

    public function getLessonImages($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, image_path, caption, sort_order
            FROM lesson_images WHERE lesson_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonVideos($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, video_url, sort_order
            FROM lesson_videos WHERE lesson_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonActivities($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, total_points, time_limit, sort_order
            FROM activities WHERE lesson_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonQuizzes($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, time_limit, passing_score, sort_order
            FROM quizzes WHERE lesson_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── NEW: Flashcards per lesson ────────────────────────────
    public function getLessonFlashcards($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, card_type, front, back, sort_order
            FROM im_flashcards WHERE lesson_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── NEW: Check if lesson is "completed" ───────────────────
    // A lesson is completed when:
    //   - If it has a quiz: student passed at least one quiz
    //   - If no quiz but has activity: student submitted activity
    //   - If neither: always considered done
    public function isLessonCompleted($lessonId, $studentId)
    {
        if (!$studentId)
            return false;

        // Check quiz pass
        $stmt = $this->db->prepare("
            SELECT q.id FROM quizzes q
            JOIN quiz_results qr ON qr.quiz_id = q.id
            WHERE q.lesson_id = ? AND qr.student_id = ? AND qr.passed = 1
            LIMIT 1
        ");
        $stmt->bind_param("ii", $lessonId, $studentId);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc())
            return true;

        // Check activity submission
        $stmt = $this->db->prepare("
            SELECT a.id FROM activities a
            JOIN activity_submissions s ON s.activity_id = a.id
            WHERE a.lesson_id = ? AND s.student_id = ?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $lessonId, $studentId);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc())
            return true;

        // No quiz or activity — check if lesson even has them
        $stmt = $this->db->prepare("
            SELECT
                (SELECT COUNT(*) FROM quizzes    WHERE lesson_id = ?) AS qcount,
                (SELECT COUNT(*) FROM activities WHERE lesson_id = ?) AS acount
        ");
        $stmt->bind_param("ii", $lessonId, $lessonId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row['qcount'] == 0 && $row['acount'] == 0)
            return true;

        return false;
    }

    // ============================================================
    // MODULE-LEVEL (flashcards, activity, quiz)
    // ============================================================
    public function getIMFlashcards($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, card_type, front, back, sort_order
            FROM im_flashcards WHERE interactive_module_id = ?
            ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMActivity($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, total_points, time_limit
            FROM activities WHERE interactive_module_id = ?
            ORDER BY sort_order ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMActivityQuestions($activityId)
    {
        $stmt = $this->db->prepare("
            SELECT id, question_type, question, model_answer,
                   choice_a, choice_b, choice_c, choice_d, correct_ans, points, sort_order
            FROM activity_questions WHERE activity_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $activityId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMActivitySubmission($activityId, $studentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, answers, submitted_at FROM activity_submissions
            WHERE activity_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $activityId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveIMActivitySubmission($activityId, $studentId, $answersJson)
    {
        $stmt = $this->db->prepare("
            INSERT INTO activity_submissions (activity_id, student_id, answers, submitted_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $activityId, $studentId, $answersJson);
        return $stmt->execute();
    }

    public function getIMQuiz($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title, instructions, time_limit, passing_score
            FROM quizzes WHERE interactive_module_id = ?
            ORDER BY sort_order ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMQuizQuestions($quizId)
    {
        $stmt = $this->db->prepare("
            SELECT id, question, choice_a, choice_b, choice_c, choice_d, correct_ans, points
            FROM quiz_questions WHERE quiz_id = ? ORDER BY sort_order ASC
        ");
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMQuizResult($quizId, $studentId)
    {
        $stmt = $this->db->prepare("
            SELECT id, score, total, passed, taken_at FROM quiz_results
            WHERE quiz_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $quizId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveIMQuizResult($quizId, $studentId, $score, $total, $passingScore)
    {
        $passed = ($total > 0 && (($score / $total) * 100) >= $passingScore) ? 1 : 0;
        $stmt = $this->db->prepare("
            INSERT INTO quiz_results (quiz_id, student_id, score, total, passed, taken_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiiii", $quizId, $studentId, $score, $total, $passed);
        return $stmt->execute();
    }

    // ── Mark a lesson as visited (for sidebar checkmarks) ─────
    // Creates the table if needed, then inserts if not already visited
    public function markLessonVisited($lessonId, $studentId)
    {
        if (!$studentId || !$lessonId)
            return false;

        // Create table on first use (safe to call every time)
        $this->db->query("
            CREATE TABLE IF NOT EXISTS lesson_visits (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                lesson_id  INT NOT NULL,
                student_id INT NOT NULL,
                visited_at DATETIME DEFAULT NOW(),
                UNIQUE KEY uq_visit (lesson_id, student_id)
            )
        ");

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

        // Table might not exist yet
        $check = $this->db->query("SHOW TABLES LIKE 'lesson_visits'");
        if (!$check || $check->num_rows === 0)
            return false;

        $stmt = $this->db->prepare("
            SELECT id FROM lesson_visits
            WHERE lesson_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $lessonId, $studentId);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }

    // ── Track subject starts ───────────────────────────────────
    public function markSubjectStarted($subjectSlug, $studentId)
    {
        if (!$studentId || !$subjectSlug)
            return false;

        // Get subject_id from slug
        $stmt = $this->db->prepare("SELECT id FROM subjects WHERE slug = ? LIMIT 1");
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
            SELECT s.slug
            FROM subject_starts ss
            JOIN subjects s ON ss.subject_id = s.id
            WHERE ss.student_id = ?
        ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'slug');
    }

    // ── Track module starts ────────────────────────────────────
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
}