<?php

require_once "../core/Model.php";

class SuperAdmin extends Model
{
    private function timeAgo($datetime)
    {
        if (!$datetime)
            return 'just now';

        $now = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $ago = new DateTime($datetime, new DateTimeZone('Asia/Manila'));
        $diff = $now->getTimestamp() - $ago->getTimestamp();

        if ($diff < 0)
            $diff = 0;
        if ($diff < 60)
            return $diff . 's ago';
        if ($diff < 3600)
            return floor($diff / 60) . 'm ago';
        if ($diff < 86400)
            return floor($diff / 3600) . 'h ago';
        if ($diff < 604800)
            return floor($diff / 86400) . 'd ago';
        return $ago->format('M j, Y');
    }

    // ============================================================
    // SUBJECTS
    // ============================================================
    public function getAllSubjects()
    {
        $result = $this->db->query("
        SELECT s.id, s.subject_name, s.subject_description, s.subject_code,
               s.subject_image, s.grade_level_id,
               gl.name AS grade_name
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        ORDER BY gl.name ASC, s.subject_name ASC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSubjectsByGradeLevel($gradeLevelId)
    {
        $stmt = $this->db->prepare("
        SELECT s.id, s.subject_name, s.subject_description, s.subject_code,
               s.subject_image, s.grade_level_id,
               gl.name AS grade_name
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE s.grade_level_id = ?
        ORDER BY s.subject_name ASC
    ");
        $stmt->bind_param("i", $gradeLevelId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSubjectById($id)
    {
        $stmt = $this->db->prepare("
        SELECT s.id, s.subject_name, s.subject_description, s.subject_code,
               s.subject_image, s.grade_level_id,
               gl.name AS grade_name
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE s.id = ?
        LIMIT 1
    ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ============================================================
    // GRADE LEVELS
    // ============================================================
    public function getAllGradeLevels()
    {
        $result = $this->db->query("SELECT id, name FROM tbl_grade_level ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // INTERACTIVE MODULES (moved from Teacher.php)
    // ============================================================
    public function countInteractiveModules($subjectId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total FROM tbl_interactive_modules WHERE subject_id = ?
        ");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getInteractiveModuleByTitle($subjectId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM tbl_interactive_modules
            WHERE subject_id = ? AND title = ? LIMIT 1
        ");
        $stmt->bind_param("is", $subjectId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function insertInteractiveModule($subjectId, $title, $description, $sortOrder = 0, $createdBy = null)
    {
        $existingId = $this->getInteractiveModuleByTitle($subjectId, $title);
        if ($existingId)
            return ['id' => $existingId, 'existed' => true];

        $teacherId = null;

        $stmt = $this->db->prepare("
        INSERT INTO tbl_interactive_modules (subject_id, teacher_id, title, description, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param("iiss", $subjectId, $teacherId, $title, $description);
        $stmt->execute();
        return ['id' => $this->db->insert_id, 'existed' => false];
    }

    // ============================================================
    // LESSONS (moved from Teacher.php)
    // ============================================================
    public function countLessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total FROM tbl_lessons WHERE interactive_module_id = ?
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getLessonByTitle($interactiveModuleId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM tbl_lessons
            WHERE interactive_module_id = ? AND title = ? LIMIT 1
        ");
        $stmt->bind_param("is", $interactiveModuleId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function insertLesson($interactiveModuleId, $title, $topic, $content)
    {
        $existingId = $this->getLessonByTitle($interactiveModuleId, $title);
        if ($existingId)
            return ['id' => $existingId, 'existed' => true];

        $stmt = $this->db->prepare("
            INSERT INTO tbl_lessons (interactive_module_id, title, topic, content)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isss", $interactiveModuleId, $title, $topic, $content);
        $stmt->execute();
        return ['id' => $this->db->insert_id, 'existed' => false];
    }

    // ============================================================
    // INTERACTIVE CONTENTS (moved from Teacher.php)
    // ============================================================
    public function insertInteractiveContent($lessonId, $type, $data = [])
    {
        $title = $data['title'] ?? null;
        $instructions = $data['instructions'] ?? null;
        $question = $data['question'] ?? null;
        $questionType = $data['question_type'] ?? null;
        $choiceA = $data['choice_a'] ?? null;
        $choiceB = $data['choice_b'] ?? null;
        $choiceC = $data['choice_c'] ?? null;
        $choiceD = $data['choice_d'] ?? null;
        $correctAns = $data['correct_ans'] ?? null;
        $modelAnswer = $data['model_answer'] ?? null;
        $passingScore = $data['passing_score'] ?? null;
        $totalPoints = $data['total_points'] ?? null;
        $cardFront = $data['card_front'] ?? null;
        $cardBack = $data['card_back'] ?? null;
        $cardType = $data['card_type'] ?? null;
        $filePath = $data['file_path'] ?? null;
        $fileName = $data['file_name'] ?? null;
        $fileType = $data['file_type'] ?? null;

        $stmt = $this->db->prepare("
            INSERT INTO tbl_interactive_contents (
                lesson_id, type, title, instructions,
                question, question_type,
                choice_a, choice_b, choice_c, choice_d,
                correct_ans, model_answer,
                passing_score, total_points,
                card_front, card_back, card_type,
                file_path, file_name, file_type,
                created_at
            ) VALUES (
                ?, ?, ?, ?,
                ?, ?,
                ?, ?, ?, ?,
                ?, ?,
                ?, ?,
                ?, ?, ?,
                ?, ?, ?,
                NOW()
            )
        ");

        $stmt->bind_param(
            "isssssssssssiiisssss",
            $lessonId,
            $type,
            $title,
            $instructions,
            $question,
            $questionType,
            $choiceA,
            $choiceB,
            $choiceC,
            $choiceD,
            $correctAns,
            $modelAnswer,
            $passingScore,
            $totalPoints,
            $cardFront,
            $cardBack,
            $cardType,
            $filePath,
            $fileName,
            $fileType
        );

        $stmt->execute();
        return $this->db->insert_id;
    }

    public function createSubject($name, $code, $description, $gradeLevelId, $imagePath = null)
    {
        $stmt = $this->db->prepare("
        INSERT INTO tbl_subjects (subject_name, subject_code, subject_description, grade_level_id, subject_image)
        VALUES (?, ?, ?, ?, ?)
    ");
        $stmt->bind_param("sssis", $name, $code, $description, $gradeLevelId, $imagePath);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function updateSubject($id, $name, $code, $description, $gradeLevelId, $imagePath = null)
    {
        if ($imagePath) {
            $stmt = $this->db->prepare("
            UPDATE tbl_subjects
            SET subject_name = ?, subject_code = ?, subject_description = ?,
                grade_level_id = ?, subject_image = ?
            WHERE id = ?
        ");
            $stmt->bind_param("sssisi", $name, $code, $description, $gradeLevelId, $imagePath, $id);
        } else {
            $stmt = $this->db->prepare("
            UPDATE tbl_subjects
            SET subject_name = ?, subject_code = ?, subject_description = ?,
                grade_level_id = ?
            WHERE id = ?
        ");
            $stmt->bind_param("sssii", $name, $code, $description, $gradeLevelId, $id);
        }
        $stmt->execute();
    }

    public function getTotalStudents()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM tbl_students s JOIN tbl_users u ON s.user_id = u.id WHERE u.role = 'student'");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalTeachers()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM tbl_teachers t JOIN tbl_users u ON t.user_id = u.id WHERE u.role = 'teacher'");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalPendingApprovals()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM tbl_students WHERE status = 'Pending'");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalSubjects()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM tbl_subjects");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalSections()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM tbl_sections");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getPendingStudents()
    {
        $result = $this->db->query("
        SELECT u.name, u.email, s.status,
               gl.name AS grade_level, sec.section_name
        FROM tbl_users u
        JOIN tbl_students s ON s.user_id = u.id
        JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        JOIN tbl_sections sec ON sec.id = s.section_id
        WHERE u.role = 'student' AND s.status = 'Pending'
        ORDER BY u.id DESC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentEnrollments($limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT u.name, s.subject_name, sec.section_name,
               gl.name AS grade_level, se.enrolled_at
        FROM tbl_student_enrollments se
        JOIN tbl_students st ON st.id = se.student_id
        JOIN tbl_users u ON u.id = st.user_id
        JOIN tbl_subjects s ON s.id = se.subject_id
        JOIN tbl_sections sec ON sec.id = se.section_id
        JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
        ORDER BY se.enrolled_at DESC LIMIT ?
    ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentAnnouncements($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT n.title, n.message, n.created_at,
                   s.subject_name, u.name AS teacher_name
            FROM tbl_notifications n
            JOIN tbl_subjects s ON s.id = n.subject_id
            JOIN tbl_users u ON u.id = n.sender_id
            WHERE n.type = 'announcement'
            ORDER BY n.created_at DESC LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTeacherWorkload()
    {
        $result = $this->db->query("
            SELECT u.name AS teacher_name,
                   COUNT(DISTINCT ta.subject_id) AS class_count
            FROM tbl_teachers t
            JOIN tbl_users u ON t.user_id = u.id
            LEFT JOIN tbl_teacher_assignments ta ON ta.teacher_id = t.id
            WHERE u.role = 'teacher'
            GROUP BY t.id, u.name
            ORDER BY class_count DESC
            LIMIT 5
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getEnrollmentByGrade()
    {
        $result = $this->db->query("
            SELECT gl.name AS grade_level, COUNT(se.student_id) AS total
            FROM tbl_student_enrollments se
            JOIN tbl_sections sec ON sec.id = se.section_id
            JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
            GROUP BY gl.id, gl.name
            ORDER BY gl.name ASC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStudentApproval($studentId, $gradeLevelId, $sectionId, $studentLRN, $status, $reason, $approvedBy, $userId, $name, $email)
    {
        // Update tbl_users table
        $stmt = $this->db->prepare("UPDATE tbl_users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $userId);
        $stmt->execute();
        $stmt->close();

        // Always save approved_by — never skip it
        $approvedBy = (int) $approvedBy;
        $stmt2 = $this->db->prepare("
        UPDATE tbl_students 
        SET grade_level_id = ?, section_id = ?, student_LRN = ?,
            status = ?, reason = ?, approved_by = ?, updated_at = NOW()
        WHERE id = ?
    ");
        $stmt2->bind_param(
            "iisssii",
            $gradeLevelId,
            $sectionId,
            $studentLRN,
            $status,
            $reason,
            $approvedBy,
            $studentId
        );
        $stmt2->execute();
        $stmt2->close();
    }

    // ============================================================
    // ACTIVITY LOGS — pulls from existing tables, no new table needed
    // ============================================================
    public function getActivityLogs($limit = 15)
    {
        $sql = "
    SELECT * FROM (

        SELECT
            'enrollment' AS action,
            CONCAT(u.name, ' enrolled in ', s.subject_name, ' · ', sec.section_name) AS description,
            'student' AS role,
            u.name AS user_name,
            se.enrolled_at AS created_at
        FROM tbl_student_enrollments se
        JOIN tbl_students st  ON st.id  = se.student_id
        JOIN tbl_users u      ON u.id   = st.user_id
        JOIN tbl_subjects s   ON s.id   = se.subject_id
        JOIN tbl_sections sec ON sec.id = se.section_id

        UNION ALL

        SELECT
            'pending' AS action,
            CONCAT(u.name, ' registration is pending approval · ', gl.name, ' ', sec.section_name) AS description,
            'student' AS role,
            u.name AS user_name,
            u.created_at AS created_at
        FROM tbl_users u
        JOIN tbl_students s     ON s.user_id  = u.id
        JOIN tbl_grade_level gl ON gl.id       = s.grade_level_id
        JOIN tbl_sections sec   ON sec.id      = s.section_id
        WHERE s.status = 'Pending'

        UNION ALL

        SELECT
            'module_created' AS action,
            CONCAT(COALESCE(u.name, 'A teacher'), ' created module ', im.title, ' in ', s.subject_name) AS description,
            'teacher' AS role,
            COALESCE(u.name, 'Unknown') AS user_name,
            im.created_at AS created_at
        FROM tbl_interactive_modules im
        JOIN tbl_subjects s      ON s.id = im.subject_id
        LEFT JOIN tbl_teachers t ON t.id = im.teacher_id
        LEFT JOIN tbl_users u    ON u.id = t.user_id

        UNION ALL

        SELECT
            'activity_submitted' AS action,
            CONCAT(u.name, ' submitted assignment ', a.title, ' in ', s.subject_name) AS description,
            'student' AS role,
            u.name AS user_name,
            asub.submitted_at AS created_at
        FROM tbl_assignment_submissions asub
        JOIN tbl_assignments a ON a.id  = asub.assignment_id
        JOIN tbl_subjects s    ON s.id  = a.subject_id
        JOIN tbl_students st   ON st.id = asub.student_id
        JOIN tbl_users u       ON u.id  = st.user_id

        UNION ALL

        SELECT
            CASE WHEN qr.passed = 1 THEN 'quiz_passed' ELSE 'quiz_submitted' END AS action,
            CONCAT(
                u.name,
                CASE WHEN qr.passed = 1 THEN ' passed' ELSE ' submitted' END,
                ' quiz in ', s.subject_name,
                ' (', qr.score, '/', qr.total, ')'
            ) AS description,
            'student' AS role,
            u.name AS user_name,
            qr.taken_at AS created_at
        FROM tbl_quiz_results qr
        JOIN tbl_interactive_contents ic ON ic.id = qr.content_id
        JOIN tbl_lessons l               ON l.id  = ic.lesson_id
        JOIN tbl_interactive_modules im  ON im.id = l.interactive_module_id
        JOIN tbl_subjects s              ON s.id  = im.subject_id
        JOIN tbl_students st             ON st.id = qr.student_id
        JOIN tbl_users u                 ON u.id  = st.user_id

        UNION ALL

        SELECT
            'activity_submitted' AS action,
            CONCAT(u.name, ' submitted activity in ', s.subject_name) AS description,
            'student' AS role,
            u.name AS user_name,
            act_sub.submitted_at AS created_at
        FROM tbl_activity_submissions act_sub
        JOIN tbl_interactive_contents ic ON ic.id = act_sub.content_id
        JOIN tbl_lessons l               ON l.id  = ic.lesson_id
        JOIN tbl_interactive_modules im  ON im.id = l.interactive_module_id
        JOIN tbl_subjects s              ON s.id  = im.subject_id
        JOIN tbl_students st             ON st.id = act_sub.student_id
        JOIN tbl_users u                 ON u.id  = st.user_id

        UNION ALL

        SELECT
            'invite_accepted' AS action,
            CONCAT(COALESCE(approver.name, 'Admin'), ' approved student ', stu_u.name, ' · ', gl.name, ' ', sec.section_name) AS description,
            COALESCE(approver.role, 'superadmin') AS role,
            COALESCE(approver.name, 'Super Admin') AS user_name,
            stu.updated_at AS created_at
        FROM tbl_students stu
        JOIN tbl_users stu_u         ON stu_u.id    = stu.user_id
        JOIN tbl_grade_level gl      ON gl.id        = stu.grade_level_id
        JOIN tbl_sections sec        ON sec.id       = stu.section_id
        LEFT JOIN tbl_users approver ON approver.id  = stu.approved_by
        WHERE stu.status = 'Approved'
          AND stu.updated_at IS NOT NULL

        UNION ALL

        SELECT
            'subject_created' AS action,
            CONCAT('Subject ', s.subject_name, ' was created · ', COALESCE(gl.name, '')) AS description,
            'superadmin' AS role,
            'Super Admin' AS user_name,
            s.created_at AS created_at
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE s.created_at IS NOT NULL

        UNION ALL

        SELECT
            'subject_updated' AS action,
            CONCAT('Subject ', s.subject_name, ' was updated · ', COALESCE(gl.name, '')) AS description,
            'superadmin' AS role,
            'Super Admin' AS user_name,
            s.updated_at AS created_at
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE s.updated_at IS NOT NULL
          AND s.updated_at <> s.created_at

        UNION ALL

        SELECT
            'invite_sent' AS action,
            CONCAT('Teacher account created for ', u.name, ' (', u.email, ')') AS description,
            'superadmin' AS role,
            'Super Admin' AS user_name,
            u.created_at AS created_at
        FROM tbl_users u
        JOIN tbl_teachers t ON t.user_id = u.id
        WHERE u.role = 'teacher'
          AND u.created_at IS NOT NULL

        UNION ALL

        SELECT
            'invite_declined' AS action,
            CONCAT(
                COALESCE(approver.name, 'Admin'), ' declined student ', stu_u.name,
                CASE WHEN stu.reason IS NOT NULL AND stu.reason <> ''
                     THEN CONCAT(' · Reason: ', LEFT(stu.reason, 60))
                     ELSE ''
                END
            ) AS description,
            COALESCE(approver.role, 'superadmin') AS role,
            COALESCE(approver.name, 'Admin') AS user_name,
            stu.updated_at AS created_at
        FROM tbl_students stu
        JOIN tbl_users stu_u         ON stu_u.id  = stu.user_id
        JOIN tbl_grade_level gl      ON gl.id      = stu.grade_level_id
        JOIN tbl_sections sec        ON sec.id     = stu.section_id
        LEFT JOIN tbl_users approver ON approver.id = stu.approved_by
        WHERE stu.status = 'Rejected'
          AND stu.approved_by IS NOT NULL
          AND stu.updated_at IS NOT NULL

    ) AS combined
    WHERE created_at IS NOT NULL
    ORDER BY created_at DESC
    LIMIT ?
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Add time_ago to each log
        foreach ($logs as &$log) {
            $log['time_ago'] = $this->timeAgo($log['created_at']);
        }

        return $logs;
    }
}