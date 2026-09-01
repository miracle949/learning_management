<?php

require_once "../core/Model.php";

class Students extends Model
{

    public function getGradedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT a.id, a.task, a.type, a.due_date, a.points AS total_points,
           s.subject_code, sub.submitted_at, sub.graded_at,
           sub.points_earned, sub.feedback
        FROM tbl_assignments a
        JOIN tbl_assignment_submissions sub ON sub.assignment_id = a.id
        JOIN tbl_subjects s ON a.subject_id = s.id
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
        SELECT COUNT(*) AS total FROM tbl_assignment_submissions
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
            FROM tbl_modules m JOIN tbl_subjects s ON m.subject_id = s.id
            WHERE m.id = ? AND s.subject_code = ? LIMIT 1
        ");
        $stmt->bind_param("is", $moduleId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getModuleMaterials($moduleId)
    {
        $stmt = $this->db->prepare("SELECT id, file_name, file_path, file_type FROM tbl_modules WHERE id = ? LIMIT 1");
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
        SELECT id, submitted_at, file_path, points_earned, feedback
        FROM tbl_assignment_submissions
        WHERE assignment_id = ? AND student_id = ? LIMIT 1
    ");
        $stmt->bind_param("ii", $assignmentId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveAssignmentSubmission($assignmentId, $studentId, $filePath, $message)
    {
        $stmt = $this->db->prepare("
        INSERT INTO tbl_assignment_submissions 
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
        DELETE FROM tbl_assignment_submissions
        WHERE assignment_id = ? AND student_id = ?
        LIMIT 1
    ");
        $stmt->bind_param("ii", $assignmentId, $studentId);
        return $stmt->execute();
    }

    public function getAssignmentByIdAndSlug($assignmentId, $subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT a.id, a.title, a.description, a.task, a.instructions, a.posted_at, a.points, a.due_date, a.due_time, s.subject_name, s.subject_code
            FROM tbl_assignments a JOIN tbl_subjects s ON a.subject_id = s.id
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
            FROM tbl_assignments WHERE id = ?
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
        SELECT n.id, n.title, n.message AS body, n.created_at AS posted_at,
               u.name AS teacher_name, s.subject_name, s.subject_code AS slug
        FROM tbl_notifications n
        JOIN tbl_subjects s ON n.subject_id = s.id
        JOIN tbl_users u ON n.sender_id = u.id
        WHERE n.id = ? AND s.subject_code = ? AND n.type = 'announcement'
        LIMIT 1
    ");
        $stmt->bind_param("is", $announcementId, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ============================================================
    // SUBJECT FEED
    // ============================================================
    public function getSubjectFeed($subjectSlug, $studentId = null)
    {
        $studentId = $studentId ?? $_SESSION['student_id'] ?? 0;

        $stmt = $this->db->prepare("
        (SELECT 'module' AS type, m.id, 'New Material' AS label,
                m.title AS heading, m.description AS subtext, m.posted_at AS date,
                NULL AS total_points, NULL AS points_earned
         FROM tbl_modules m
         JOIN tbl_subjects s ON m.subject_id = s.id
         WHERE s.subject_code = ?)

        UNION ALL

        (SELECT 'assignment', a.id, 'New Assignment',
                a.task, a.description, a.posted_at,
                a.points AS total_points,
                sub.points_earned AS points_earned
         FROM tbl_assignments a
         JOIN tbl_subjects s ON a.subject_id = s.id
         LEFT JOIN tbl_assignment_submissions sub
                ON sub.assignment_id = a.id
                AND sub.student_id = ?
         WHERE s.subject_code = ?)

        UNION ALL

        (SELECT 'announcement', n.id, 'Announcement',
                n.title, n.message, n.created_at,
                NULL AS total_points, NULL AS points_earned
         FROM tbl_notifications n
         JOIN tbl_subjects s ON n.subject_id = s.id
         WHERE s.subject_code = ? AND n.type = 'announcement')

        ORDER BY date DESC
    ");

        $stmt->bind_param("siss", $subjectSlug, $studentId, $subjectSlug, $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // INTERACTIVE MODULES
    // ============================================================
    public function getSubjectBySlug($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT id, subject_name, subject_image, subject_code, subject_description
            FROM tbl_subjects WHERE subject_code = ? LIMIT 1
        ");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAssignmentDueDate($assignmentId)
    {
        $stmt = $this->db->prepare("
        SELECT due_date, due_time
        FROM tbl_assignments
        WHERE id = ?
        LIMIT 1
    ");
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getInteractiveModules($subjectSlug)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description
            FROM tbl_interactive_modules im
            JOIN tbl_subjects s ON im.subject_id = s.id
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
            SELECT COUNT(*) AS total FROM tbl_lessons WHERE interactive_module_id = ?
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getInteractiveModuleById($id)
    {
        $stmt = $this->db->prepare("
            SELECT im.id, im.title, im.description, s.subject_name, s.subject_code
            FROM tbl_interactive_modules im
            JOIN tbl_subjects s ON im.subject_id = s.id
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
            FROM tbl_lessons
            WHERE interactive_module_id = ?
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMLessonsWithCounts($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT
                l.id, l.title, l.topic,
                (SELECT COUNT(*) FROM tbl_interactive_contents WHERE lesson_id = l.id AND type = 'activity')  AS activity_count,
                (SELECT COUNT(*) FROM tbl_interactive_contents WHERE lesson_id = l.id AND type = 'quiz')      AS quiz_count,
                (SELECT COUNT(*) FROM tbl_interactive_contents WHERE lesson_id = l.id AND type = 'flashcard') AS flashcard_count
            FROM tbl_lessons l
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
        SELECT l.id, l.title, l.topic,
               im.id AS module_id, im.title AS module_title,
               s.subject_name, s.subject_code
        FROM tbl_lessons l
        JOIN tbl_interactive_modules im ON l.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        WHERE l.id = ? LIMIT 1
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLessonContentBlocks($lessonId)
    {
        $stmt = $this->db->prepare("
        SELECT id, type, title, body, key_idea, file_path, file_name
        FROM tbl_interactive_contents
        WHERE lesson_id = ? AND type IN ('text', 'image', 'video')
        ORDER BY id ASC
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdjacentIMLessons($lessonId, $interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, title FROM tbl_lessons
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
            FROM tbl_interactive_contents
            WHERE lesson_id = ? AND type = 'image'
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getIMActivityById($activityId)
    {
        $stmt = $this->db->prepare("
        SELECT ic.id, ic.title, ic.instructions, ic.total_points, ic.lesson_id,
               l.title AS lesson_title, l.interactive_module_id AS module_id,
               im.title AS module_title, s.subject_name, s.subject_code
        FROM tbl_interactive_contents ic
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        JOIN tbl_interactive_modules im ON l.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        WHERE ic.id = ? AND ic.type = 'activity'
        LIMIT 1
    ");
        $stmt->bind_param("i", $activityId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLessonVideos($lessonId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_path, title
            FROM tbl_interactive_contents
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
            FROM tbl_interactive_contents
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
        FROM tbl_interactive_contents
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
            FROM tbl_interactive_contents
            WHERE lesson_id = ? AND type = 'flashcard'
            ORDER BY id ASC
        ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonDragDrops($lessonId)
    {
        $stmt = $this->db->prepare("
    SELECT title, instructions,
           COALESCE(dragdrop_item_label, card_front)         AS item_label,
           COALESCE(dragdrop_item_subtitle, key_idea)         AS item_subtitle,
           COALESCE(dragdrop_item_image, file_path)           AS item_image,
           COALESCE(dragdrop_category, card_back)             AS category,
           dragdrop_category_description                     AS category_description
    FROM tbl_interactive_contents
    WHERE lesson_id = ? AND type = 'drag_drop'
    ORDER BY id ASC
");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['title'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'game' => ['title' => $row['title'], 'instructions' => $row['instructions']],
                    'categories' => [],
                    'category_hints' => [],
                    'items' => [],
                ];
            }

            if ($row['category'] !== null && !in_array($row['category'], $grouped[$key]['categories'], true)) {
                $grouped[$key]['categories'][] = $row['category'];
            }

            if (
                $row['category'] !== null
                && !empty(trim((string) $row['category_description']))
                && !isset($grouped[$key]['category_hints'][$row['category']])
            ) {
                $grouped[$key]['category_hints'][$row['category']] = trim($row['category_description']);
            }

            $grouped[$key]['items'][] = [
                'label' => $row['item_label'],
                'subtitle' => $row['item_subtitle'],
                'category' => $row['category'],
                'image' => $row['item_image'],   // ← NEW
            ];
        }
        return $grouped;
    }

    public function getLessonArrangeStepsData($lessonId)
    {
        $stmt = $this->db->prepare("
        SELECT title, instructions, question AS step_text, step_order
        FROM tbl_interactive_contents
        WHERE lesson_id = ? AND type = 'arrange_steps'
        ORDER BY id ASC
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['title'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'game' => [
                        'title' => $row['title'],
                        'instructions' => $row['instructions'],
                    ],
                    'steps' => [],
                ];
            }
            $grouped[$key]['steps'][] = [
                'text' => $row['step_text'],
                'order' => $row['step_order'],
            ];
        }

        foreach ($grouped as &$game) {
            usort($game['steps'], fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        }
        unset($game);

        return $grouped;
    }

    public function getDragDropSubmission($lessonId, $gameTitle, $studentId)
    {
        if (!$studentId)
            return null;
        $stmt = $this->db->prepare("
        SELECT item_label, student_answer, correct_answer, is_correct, completed_at
        FROM tbl_dragdrop_results
        WHERE lesson_id = ? AND game_title = ? AND student_id = ?
        ORDER BY id ASC
    ");
        $stmt->bind_param("isi", $lessonId, $gameTitle, $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if (!$rows)
            return null;

        $answers = [];
        $correctness = [];
        foreach ($rows as $r) {
            $answers[$r['item_label']] = $r['student_answer'];
            $correctness[$r['item_label']] = (int) $r['is_correct'];
        }
        return [
            'answers' => $answers,
            'correctness' => $correctness,
            'completed_at' => $rows[0]['completed_at'],
        ];
    }

    public function saveDragDropSubmission($lessonId, $gameTitle, $studentId, array $answers)
    {
        // Look up each item's real category so we can store correctness alongside it
        $allGames = $this->getLessonDragDrops($lessonId);
        $correctMap = [];
        if (isset($allGames[$gameTitle])) {
            foreach ($allGames[$gameTitle]['items'] as $it) {
                $correctMap[$it['label']] = $it['category'];
            }
        }

        $stmt = $this->db->prepare("
        INSERT INTO tbl_dragdrop_results
            (lesson_id, game_title, student_id, item_label, student_answer, correct_answer, is_correct, completed_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            student_answer = VALUES(student_answer),
            correct_answer = VALUES(correct_answer),
            is_correct     = VALUES(is_correct),
            completed_at   = VALUES(completed_at)
    ");

        if (!$stmt) {
            error_log('[saveDragDropSubmission] prepare failed: ' . $this->db->error);
            return false;
        }

        $ok = true;
        foreach ($answers as $itemLabel => $chosenCategory) {
            $correctCategory = $correctMap[$itemLabel] ?? null;
            $isCorrect = ($correctCategory !== null && strcasecmp($chosenCategory, $correctCategory) === 0) ? 1 : 0;

            $stmt->bind_param(
                "isisssi",
                $lessonId,
                $gameTitle,
                $studentId,
                $itemLabel,
                $chosenCategory,
                $correctCategory,
                $isCorrect
            );
            if (!$stmt->execute()) {
                error_log('[saveDragDropSubmission] execute failed for item "' . $itemLabel . '": ' . $stmt->error);
                $ok = false;
            }
        }
        return $ok;
    }

    public function isLessonCompleted($lessonId, $studentId)
    {
        if (!$studentId)
            return false;

        $stmt = $this->db->prepare("
        SELECT MIN(id) AS quiz_id
        FROM tbl_interactive_contents
        WHERE lesson_id = ? AND type = 'quiz'
        GROUP BY title
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $quizGroups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT title) AS acount
        FROM tbl_interactive_contents
        WHERE lesson_id = ? AND type = 'activity'
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        $acount = (int) $stmt->get_result()->fetch_assoc()['acount'];

        // No quiz and no activity — check module_progress instead
        if (empty($quizGroups) && $acount === 0) {
            return $this->isLessonVisitedViaProgress($lessonId, $studentId);
        }

        foreach ($quizGroups as $group) {
            $stmt = $this->db->prepare("
            SELECT id FROM tbl_quiz_results
            WHERE content_id = ? AND student_id = ? LIMIT 1
        ");
            $stmt->bind_param("ii", $group['quiz_id'], $studentId);
            $stmt->execute();
            if (!$stmt->get_result()->fetch_assoc())
                return false;
        }

        if ($acount > 0) {
            $stmt = $this->db->prepare("
            SELECT s.id FROM tbl_activity_submissions s
            JOIN tbl_interactive_contents ic ON s.content_id = ic.id
            WHERE ic.lesson_id = ? AND ic.type = 'activity'
            AND s.student_id = ? LIMIT 1
        ");
            $stmt->bind_param("ii", $lessonId, $studentId);
            $stmt->execute();
            if (!$stmt->get_result()->fetch_assoc())
                return false;
        }

        $dragDropGames = $this->getLessonDragDrops($lessonId);

        // update this line:
        if (empty($quizGroups) && $acount === 0 && empty($dragDropGames)) {
            return $this->isLessonVisitedViaProgress($lessonId, $studentId);
        }

        // ...after the activity check block, add:
        foreach ($dragDropGames as $ddTitle => $ddData) {
            if (!$this->getDragDropSubmission($lessonId, $ddTitle, $studentId)) {
                return false;
            }
        }

        return true;
    }

    public function countIMdragdrops($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT ic.title) AS total
        FROM tbl_interactive_contents ic
        INNER JOIN tbl_lessons l ON l.id = ic.lesson_id
        WHERE l.interactive_module_id = ?
        AND ic.type = 'drag_drop'
    ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int) $row['total'];
    }

    // ============================================================
    // LESSON PROGRESS (uses tbl_student_progress as fallback
    // since lesson_visits does not exist in the DB)
    // ============================================================
    public function isLessonVisitedViaProgress($lessonId, $studentId)
    {
        if (!$studentId || !$lessonId)
            return false;

        $stmt = $this->db->prepare("
        SELECT id FROM tbl_student_progress
        WHERE content_id = ? AND student_id = ? AND content_type = 'lesson'
        LIMIT 1
    ");
        $stmt->bind_param("ii", $lessonId, $studentId);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }

    public function markLessonVisited($lessonId, $studentId)
    {
        if (!$studentId || !$lessonId)
            return false;

        $stmt = $this->db->prepare("
        INSERT IGNORE INTO tbl_student_progress 
            (student_id, content_id, content_type, status, started_at)
        VALUES (?, ?, 'lesson', 'completed', NOW())
    ");
        $stmt->bind_param("ii", $studentId, $lessonId);
        return $stmt->execute();
    }


    public function isLessonVisited($lessonId, $studentId)
    {
        return $this->isLessonVisitedViaProgress($lessonId, $studentId);
    }

    // ============================================================
    // MODULE-LEVEL (flashcards, activity, quiz)
    // ============================================================
    public function getIMFlashcards($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT ic.id, ic.card_type, ic.card_front, ic.card_back
            FROM tbl_interactive_contents ic
            JOIN tbl_lessons l ON ic.lesson_id = l.id
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
            FROM tbl_interactive_contents ic
            JOIN tbl_lessons l ON ic.lesson_id = l.id
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
            FROM tbl_interactive_contents
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
            SELECT id, answers, submitted_at FROM tbl_activity_submissions
            WHERE content_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $activityId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveIMActivitySubmission($activityId, $studentId, $answersJson)
    {
        $stmt = $this->db->prepare("
            INSERT INTO tbl_activity_submissions (content_id, student_id, answers, submitted_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iis", $activityId, $studentId, $answersJson);
        return $stmt->execute();
    }

    public function getIMQuiz($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT ic.id, ic.title, ic.instructions, ic.passing_score
            FROM tbl_interactive_contents ic
            JOIN tbl_lessons l ON ic.lesson_id = l.id
            WHERE l.interactive_module_id = ? AND ic.type = 'quiz'
            ORDER BY ic.id ASC LIMIT 1
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getIMQuizQuestions($quizId)
    {
        $stmt = $this->db->prepare("
        SELECT title, lesson_id FROM tbl_interactive_contents 
        WHERE id = ? AND type = 'quiz' LIMIT 1
    ");
        $stmt->bind_param("i", $quizId);
        $stmt->execute();
        $quiz = $stmt->get_result()->fetch_assoc();
        if (!$quiz)
            return [];

        $stmt = $this->db->prepare("
        SELECT id, question, choice_a, choice_b, choice_c, choice_d, 
               correct_ans, total_points AS points
        FROM tbl_interactive_contents
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
            SELECT id, score, total, passed, answers_json, taken_at 
            FROM tbl_quiz_results 
            WHERE content_id = ? AND student_id = ? LIMIT 1
        ");
        $stmt->bind_param("ii", $quizId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveIMQuizResult($quizId, $studentId, $score, $total, $passingScore, $answersJson = null)
    {
        $passed = ($total > 0 && (($score / $total) * 100) >= $passingScore) ? 1 : 0;
        $stmt = $this->db->prepare("
        INSERT INTO tbl_quiz_results (content_id, student_id, score, total, passed, answers_json, taken_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param("iiiiss", $quizId, $studentId, $score, $total, $passed, $answersJson);
        return $stmt->execute();
    }

    // ============================================================
    // SUBJECT & MODULE PROGRESS
    // (subject_starts and module_starts replaced with
    //  tbl_student_progress and tbl_module_progress)
    // ============================================================
    public function markSubjectStarted($subjectSlug, $studentId)
    {
        if (!$studentId || !$subjectSlug)
            return false;

        $stmt = $this->db->prepare("SELECT id FROM tbl_subjects WHERE subject_code = ? LIMIT 1");
        $stmt->bind_param("s", $subjectSlug);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row)
            return false;
        $subjectId = (int) $row['id'];

        $stmt = $this->db->prepare("
        INSERT IGNORE INTO tbl_student_progress 
            (student_id, subject_id, content_type, status, started_at)
        VALUES (?, ?, 'lesson', 'not_started', NOW())
    ");
        $stmt->bind_param("ii", $studentId, $subjectId);
        return $stmt->execute();
    }

    // ── DASHBOARD STAT HELPERS ──────────────────────────────────
    public function countDueSoonAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_assignments a
        JOIN tbl_subjects s ON a.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE a.id NOT IN (
            SELECT assignment_id FROM tbl_assignment_submissions WHERE student_id = ?
        )
        AND a.due_date IS NOT NULL
        AND a.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countPendingDueThisWeek($studentId)
    {
        return $this->countDueSoonAssignments($studentId); // same window, used for "Pending Tasks" stat line
    }

    public function countCompletedThisWeek($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_assignment_submissions
        WHERE student_id = ?
        AND submitted_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countTotalAssignmentsForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_assignments a
        JOIN tbl_subjects s ON a.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getOverallProgressForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total FROM tbl_interactive_modules im
        JOIN tbl_subjects s ON im.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $totalModules = (int) $stmt->get_result()->fetch_assoc()['total'];

        $moduleProgress = $this->getOverallModuleProgress($studentId, $totalModules);
        $modulePct = $moduleProgress['percentage'];

        $totalAssignments = $this->countTotalAssignmentsForStudent($studentId);
        $completedAssignments = $this->countCompletedAssignments($studentId);
        $assignmentPct = $totalAssignments > 0 ? ($completedAssignments / $totalAssignments) * 100 : 0;

        if ($totalModules > 0 && $totalAssignments > 0) {
            $overall = round(($assignmentPct + $modulePct) / 2);
        } elseif ($totalModules > 0) {
            $overall = $modulePct;
        } elseif ($totalAssignments > 0) {
            $overall = round($assignmentPct);
        } else {
            $overall = 0;
        }

        return (int) $overall;
    }

    public function getOverallProgressDeltaThisWeek($studentId)
    {
        $totalAssignments = $this->countTotalAssignmentsForStudent($studentId);
        if ($totalAssignments === 0)
            return 0;
        $completedThisWeek = $this->countCompletedThisWeek($studentId);
        return (int) round(($completedThisWeek / $totalAssignments) * 100);
    }

    // ── MODULES PAGE STAT HELPERS (global, across all enrolled classes) ──
    public function countTotalModulesForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT im.id) AS total
        FROM tbl_interactive_modules im
        JOIN tbl_subjects s ON im.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countTotalQuizzesForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT ic.title, ic.lesson_id) AS total
        FROM tbl_interactive_contents ic
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        JOIN tbl_interactive_modules im ON l.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE ic.type = 'quiz'
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countCompletedQuizzesForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_quiz_results qr
        JOIN tbl_interactive_contents ic ON qr.content_id = ic.id
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        JOIN tbl_interactive_modules im ON l.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE qr.student_id = ?
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countModulesInProgressForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_module_progress
        WHERE student_id = ? AND status = 'in_progress'
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countTotalActivitiesForStudent($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_interactive_contents ic
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        JOIN tbl_interactive_modules im ON l.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE ic.type = 'activity'
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countActivitiesCompletedThisWeek($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_activity_submissions asub
        JOIN tbl_interactive_contents ic ON asub.content_id = ic.id
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        JOIN tbl_interactive_modules im ON l.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE asub.student_id = ?
        AND asub.submitted_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    // ── DASHBOARD: in-progress modules across ALL enrolled subjects ──
    public function getInProgressModulesForStudent($studentId, $limit = 6)
    {
        if (!$studentId)
            return [];

        $stmt = $this->db->prepare("
        SELECT mp.interactive_module_id AS id,
               im.title,
               mp.completion_percentage,
               mp.total_lessons,
               mp.completed_lessons,
               mp.last_accessed_at,
               s.subject_code,
               s.subject_name
        FROM tbl_module_progress mp
        JOIN tbl_interactive_modules im ON mp.interactive_module_id = im.id
        JOIN tbl_subjects s ON im.subject_id = s.id
        WHERE mp.student_id = ?
          AND mp.is_finished = 0
          AND mp.completion_percentage > 0
        ORDER BY mp.last_accessed_at DESC
        LIMIT ?
    ");
        $stmt->bind_param("ii", $studentId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── DASHBOARD: which lesson comes next in a module, by position ──
// $completedCount is used as a 0-indexed offset: if 3 lessons are
// done, the next lesson is the 4th one (offset 3).
    public function getLessonByPosition($moduleId, $offset)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, topic
        FROM tbl_lessons
        WHERE interactive_module_id = ?
        ORDER BY id ASC
        LIMIT 1 OFFSET ?
    ");
        $stmt->bind_param("ii", $moduleId, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Kept as a thin wrapper so nothing else that calls this breaks.
    public function getNextLessonForModule($moduleId, $completedCount)
    {
        return $this->getLessonByPosition($moduleId, $completedCount);
    }

    // ── DASHBOARD: upcoming deadlines within a day window ──
    public function getUpcomingDeadlines($studentId, $days = 30, $limit = 10)
    {
        if (!$studentId)
            return [];

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

    public function getStartedSubjectSlugs($studentId)
    {
        if (!$studentId)
            return [];

        $stmt = $this->db->prepare("
            SELECT s.subject_code
            FROM tbl_student_progress sp
            JOIN tbl_subjects s ON sp.subject_id = s.id
            WHERE sp.student_id = ?
            GROUP BY s.subject_code
        ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'subject_code');
    }

    public function getStudentGradeAndSection($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT gl.name, sec.section_name
        FROM tbl_students st
        JOIN tbl_grade_level gl ON st.grade_level_id = gl.id
        JOIN tbl_sections sec ON st.section_id = sec.id
        WHERE st.id = ? LIMIT 1
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // public function markModuleStarted($moduleId, $studentId)
    // {
    //     if (!$moduleId || !$studentId)
    //         return false;
    //     $stmt = $this->db->prepare("
    //         INSERT IGNORE INTO tbl_module_progress (interactive_modules_id, student_id, started_at)
    //         VALUES (?, ?, NOW())
    //     ");
    //     $stmt->bind_param("ii", $moduleId, $studentId);
    //     return $stmt->execute();
    // }

    public function markModuleStarted($moduleId, $studentId)
    {
        if (!$moduleId || !$studentId)
            return false;

        // Get subject_id from the module
        $stmt = $this->db->prepare("
        SELECT subject_id FROM tbl_interactive_modules WHERE id = ? LIMIT 1
    ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $subjectId = $row['subject_id'] ?? null;

        // Count total lessons
        $stmt2 = $this->db->prepare("
        SELECT COUNT(*) AS total FROM tbl_lessons WHERE interactive_module_id = ?
    ");
        $stmt2->bind_param("i", $moduleId);
        $stmt2->execute();
        $totalLessons = (int) $stmt2->get_result()->fetch_assoc()['total'];

        // Insert or update on duplicate
        $stmt3 = $this->db->prepare("
        INSERT INTO tbl_module_progress 
            (student_id, interactive_module_id, subject_id, status,
             completion_percentage, total_lessons, completed_lessons,
             is_finished, started_at, last_accessed_at, created_at)
        VALUES (?, ?, ?, 'in_progress', 0, ?, 0, 0, NOW(), NOW(), NOW())
        ON DUPLICATE KEY UPDATE last_accessed_at = NOW()
    ");
        $stmt3->bind_param("iiii", $studentId, $moduleId, $subjectId, $totalLessons);
        return $stmt3->execute();
    }

    public function finishModule($moduleId, $studentId)
    {
        if (!$moduleId || !$studentId)
            return false;

        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total FROM tbl_lessons WHERE interactive_module_id = ?
    ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $totalLessons = (int) $stmt->get_result()->fetch_assoc()['total'];

        $completedLessons = $this->countCompletedLessonsInModule($moduleId, $studentId);

        $percentage = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 100;

        $stmt3 = $this->db->prepare("
        INSERT INTO tbl_module_progress 
            (student_id, interactive_module_id, subject_id, status,
             completion_percentage, total_lessons, completed_lessons,
             is_finished, started_at, last_accessed_at, created_at)
        SELECT ?, ?, subject_id, 'completed',
               ?, ?, ?,
               1, NOW(), NOW(), NOW()
        FROM tbl_interactive_modules WHERE id = ?
        ON DUPLICATE KEY UPDATE
            status                = 'completed',
            completion_percentage = ?,
            completed_lessons     = ?,
            is_finished           = 1,
            last_accessed_at      = NOW(),
            completed_at          = NOW()
    ");
        if (!$stmt3) {
            error_log('[finishModule] prepare failed: ' . $this->db->error);
            return false;
        }

        // 8 values, 8 'i' characters — this is the count that was wrong before.
        $stmt3->bind_param(
            "iiiiiiii",
            $studentId,
            $moduleId,
            $percentage,
            $totalLessons,
            $completedLessons,
            $moduleId,
            $percentage,
            $completedLessons
        );

        $ok = $stmt3->execute();
        if (!$ok) {
            error_log('[finishModule] execute failed: ' . $stmt3->error);
        }
        return $ok;
    }

    public function countCompletedLessonsInModule($moduleId, $studentId)
    {
        $lessons = $this->getIMLessons($moduleId);
        $completed = 0;
        foreach ($lessons as $l) {
            if ($this->isLessonCompleted($l['id'], $studentId)) {
                $completed++;
            }
        }
        return $completed;
    }

    public function updateModuleProgress($moduleId, $studentId)
    {
        if (!$moduleId || !$studentId)
            return false;

        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total FROM tbl_lessons WHERE interactive_module_id = ?
    ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $totalLessons = (int) $stmt->get_result()->fetch_assoc()['total'];

        $completedLessons = $this->countCompletedLessonsInModule($moduleId, $studentId);

        $percentage = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        // ✅ Self-healing: don't depend on the separate "Finish" button's
        // AJAX call to set is_finished. The moment every lesson in the
        // module is done, mark it finished right here — in the exact same
        // request that completed the last lesson. If finish_module() also
        // fires afterward, fine, it's just redundant; if it fails or never
        // fires, the module is still correctly marked finished.
        $isFinished = ($totalLessons > 0 && $completedLessons >= $totalLessons) ? 1 : 0;
        $status = $isFinished ? 'completed' : 'in_progress';

        $stmt2 = $this->db->prepare("
        UPDATE tbl_module_progress
        SET completion_percentage = ?,
            total_lessons         = ?,
            completed_lessons     = ?,
            status                = ?,
            is_finished           = ?,
            completed_at          = CASE WHEN ? = 1 AND completed_at IS NULL THEN NOW() ELSE completed_at END,
            last_accessed_at      = NOW()
        WHERE interactive_module_id = ? AND student_id = ?
    ");
        if (!$stmt2) {
            error_log('[updateModuleProgress] prepare failed: ' . $this->db->error);
            return false;
        }

        // 8 placeholders: d i i s i i i i
        $stmt2->bind_param(
            "diisiiii",
            $percentage,
            $totalLessons,
            $completedLessons,
            $status,
            $isFinished,
            $isFinished,
            $moduleId,
            $studentId
        );

        $ok = $stmt2->execute();
        if (!$ok) {
            error_log('[updateModuleProgress] execute failed: ' . $stmt2->error);
        }
        return $ok;
    }

    public function getModuleProgressMap($studentId)
    {
        if (!$studentId)
            return [];

        $stmt = $this->db->prepare("
        SELECT interactive_module_id, status, completion_percentage, is_finished
        FROM tbl_module_progress
        WHERE student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $map = [];
        foreach ($rows as $r) {
            $map[(int) $r['interactive_module_id']] = [
                'status' => $r['status'],
                'completion_percentage' => (float) $r['completion_percentage'],
                'is_finished' => (int) $r['is_finished'],
            ];
        }
        return $map;
    }

    public function getOverallModuleProgress($studentId, $totalModules)
    {
        if (!$studentId || !$totalModules) {
            return ['percentage' => 0, 'completed' => 0, 'total' => (int) $totalModules];
        }

        $stmt = $this->db->prepare("
        SELECT completion_percentage, is_finished
        FROM tbl_module_progress
        WHERE student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $sumPercentage = 0;
        $completed = 0;

        foreach ($rows as $r) {
            $sumPercentage += (float) $r['completion_percentage'];
            if ((int) $r['is_finished'] === 1) {
                $completed++;
            }
        }

        // Modules never started count as 0%, so we divide by the TOTAL
        // module count, not just the ones with a progress row.
        $percentage = $totalModules > 0 ? round($sumPercentage / $totalModules) : 0;

        return [
            'percentage' => (int) $percentage,
            'completed' => $completed,
            'total' => (int) $totalModules,
        ];
    }

    public function getStartedModuleIds($studentId)
    {
        if (!$studentId)
            return [];

        $stmt = $this->db->prepare("
        SELECT interactive_module_id FROM tbl_module_progress WHERE student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'interactive_module_id');
    }

    public function getTeacherBySubjectId($subjectId)
    {
        $stmt = $this->db->prepare("
        SELECT u.name
        FROM tbl_teacher_assignments ta
        JOIN tbl_teachers t ON ta.teacher_id = t.id
        JOIN tbl_users u ON t.user_id = u.id
        WHERE ta.subject_id = ?
        LIMIT 1
    ");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ── DASHBOARD: recent activity feed (submissions, quizzes, finished modules) ──
    public function getRecentActivity($studentId, $limit = 6)
    {
        if (!$studentId)
            return [];

        $stmt = $this->db->prepare("
        SELECT activity_type, title, subject_name, created_at
        FROM tbl_recent_activity
        WHERE student_id = ?
        ORDER BY created_at DESC
        LIMIT ?
    ");
        $stmt->bind_param("ii", $studentId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function getCompletedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
SELECT a.id, a.task, a.type, a.description, a.due_date, a.due_time, a.points AS total_points,
       s.subject_code, s.subject_name, sub.submitted_at, sub.points_earned, sub.graded_at
FROM tbl_assignments a
JOIN tbl_assignment_submissions sub ON sub.assignment_id = a.id
JOIN tbl_subjects s ON a.subject_id = s.id
WHERE sub.student_id = ?
ORDER BY sub.submitted_at DESC
");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countIMimages($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_interactive_contents ic
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        WHERE l.interactive_module_id = ? AND ic.type = 'image'
    ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    // ============================================================
// RECENT ACTIVITY LOG
// ============================================================

    // Logs one activity row, but skips if the same student did the exact
// same thing in the last $dedupeMinutes — otherwise refreshing a lesson
// page or re-opening the same module spams the feed.
    public function logActivity($studentId, $type, $title, $subjectName = null, $dedupeMinutes = 5)
    {
        if (!$studentId || !$title)
            return false;

        $stmt = $this->db->prepare("
        SELECT id FROM tbl_recent_activity
        WHERE student_id = ? AND activity_type = ? AND title = ?
        AND created_at >= DATE_SUB(NOW(), INTERVAL ? MINUTE)
        LIMIT 1
    ");
        $stmt->bind_param("issi", $studentId, $type, $title, $dedupeMinutes);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return true; // already logged recently, don't duplicate
        }

        $stmt = $this->db->prepare("
        INSERT INTO tbl_recent_activity (student_id, activity_type, title, subject_name, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param("isss", $studentId, $type, $title, $subjectName);
        return $stmt->execute();
    }

    public function countIMvideos($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_interactive_contents ic
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        WHERE l.interactive_module_id = ? AND ic.type = 'video'
    ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countIMactivities($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_interactive_contents ic
        JOIN tbl_lessons l ON ic.lesson_id = l.id
        WHERE l.interactive_module_id = ? AND ic.type = 'activity'
    ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countIMquizzes($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT ic.title) AS total
        FROM tbl_interactive_contents ic
        INNER JOIN tbl_lessons l ON l.id = ic.lesson_id
        WHERE l.interactive_module_id = ?
        AND ic.type = 'quiz'
    ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int) $row['total'];
    }

    public function getPendingAssignments($studentId)
    {
        $stmt = $this->db->prepare("
    SELECT a.id, a.task, a.type, a.description, a.due_date, a.due_time, a.points AS total_points,
           s.subject_code, s.subject_name
    FROM tbl_assignments a
    JOIN tbl_subjects s ON a.subject_id = s.id
    JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
    WHERE a.id NOT IN (SELECT assignment_id FROM tbl_assignment_submissions WHERE student_id = ?)
    AND (a.due_date IS NULL OR a.due_date >= CURDATE())
    ORDER BY a.due_date ASC
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countCompletedAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total FROM tbl_assignment_submissions WHERE student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countPendingAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_assignments a
        JOIN tbl_subjects s ON a.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE a.id NOT IN (
            SELECT assignment_id FROM tbl_assignment_submissions WHERE student_id = ?
        )
        AND (a.due_date IS NULL OR a.due_date >= CURDATE())
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countEnrolledClasses($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT se.subject_id) AS total 
        FROM tbl_student_enrollments se
        JOIN tbl_subjects s ON se.subject_id = s.id
        WHERE se.student_id = ?
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getDashboardAnnouncements($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT n.id, n.title, n.message, n.created_at,
               s.subject_name, s.subject_code,
               u.name AS teacher_name
        FROM tbl_notifications n
        JOIN tbl_subjects s ON n.subject_id = s.id
        JOIN tbl_student_enrollments se ON se.subject_id = s.id AND se.student_id = ?
        JOIN tbl_users u ON n.sender_id = u.id
        WHERE n.type = 'announcement'
        ORDER BY n.created_at DESC
        LIMIT 5
    ");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudentIdByUserId($userId)
    {
        $stmt = $this->db->prepare("
        SELECT id FROM tbl_students WHERE user_id = ? LIMIT 1
    ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? (int) $row['id'] : 0;
    }

    public function getMissingAssignments($studentId)
    {
        $stmt = $this->db->prepare("
SELECT a.id, a.task, a.type, a.description, a.due_date, a.due_time, a.points AS total_points,
       s.subject_code, s.subject_name
FROM tbl_assignments a
JOIN tbl_subjects s ON a.subject_id = s.id
JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
WHERE a.id NOT IN (SELECT assignment_id FROM tbl_assignment_submissions WHERE student_id = ?)
AND a.due_date IS NOT NULL AND a.due_date < CURDATE()
ORDER BY a.due_date ASC
");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countMissingAssignments($studentId)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM tbl_assignments a
        JOIN tbl_subjects s ON a.subject_id = s.id
        JOIN tbl_student_enrollments e ON e.subject_id = s.id AND e.student_id = ?
        WHERE a.id NOT IN (
            SELECT assignment_id FROM tbl_assignment_submissions WHERE student_id = ?
        )
        AND a.due_date IS NOT NULL AND a.due_date < CURDATE()
    ");
        $stmt->bind_param("ii", $studentId, $studentId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }
}