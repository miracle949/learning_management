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

    public function insertLesson($interactiveModuleId, $title, $topic)
    {
        $existingId = $this->getLessonByTitle($interactiveModuleId, $title);
        if ($existingId)
            return ['id' => $existingId, 'existed' => true];

        $stmt = $this->db->prepare("
        INSERT INTO tbl_lessons (interactive_module_id, title, topic)
        VALUES (?, ?, ?)
    ");
        $stmt->bind_param("iss", $interactiveModuleId, $title, $topic);
        $stmt->execute();
        return ['id' => $this->db->insert_id, 'existed' => false];
    }

    // ============================================================
// LESSON CONTENT — replaces the old per-block 'text' rows in
// tbl_interactive_contents. All text blocks for a lesson are
// concatenated (in block order) and stored on the lesson itself.
// ============================================================
    public function updateLessonContent($lessonId, $content)
    {
        $stmt = $this->db->prepare("UPDATE tbl_lessons SET content = ? WHERE id = ?");
        $stmt->bind_param("si", $content, $lessonId);
        $stmt->execute();
    }

    // ============================================================
    // INTERACTIVE CONTENTS (moved from Teacher.php)
    // ------------------------------------------------------------
    // FIX (see notes below the class): the bind_param() type string
    // previously had 3 'i's in a row ("...iii...") instead of 2,
    // which shifted $cardFront (a string) into an integer slot —
    // silently corrupting/zeroing flashcard front text on every
    // insert. Also added explicit prepare()/execute() error checks
    // so a schema mismatch throws instead of failing silently and
    // still reporting "save_success" back to the admin.
    // ============================================================
    public function insertInteractiveContent($lessonId, $type, $data = [])
    {
        $title = $data['title'] ?? null;
        $body = $data['body'] ?? null;
        $keyIdea = $data['key_idea'] ?? null;
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
            lesson_id, type, title, body, key_idea, instructions,
            question, question_type,
            choice_a, choice_b, choice_c, choice_d,
            correct_ans, model_answer,
            passing_score, total_points,
            card_front, card_back, card_type,
            file_path, file_name, file_type,
            created_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?,
            ?, ?, ?, ?,
            ?, ?,
            ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            NOW()
        )
    ");

        if (!$stmt) {
            throw new \RuntimeException(
                'insertInteractiveContent: prepare() failed — ' . $this->db->error
            );
        }

        // lessonId(i), type(s), title(s), body(s), key_idea(s), instructions(s),
        // question(s), question_type(s), choice_a(s), choice_b(s),
        // choice_c(s), choice_d(s), correct_ans(s), model_answer(s)
        //   -> 1 i + 13 s
        // passing_score(i), total_points(i)              -> 2 i
        // card_front(s), card_back(s), card_type(s),
        // file_path(s), file_name(s), file_type(s)        -> 6 s
        // Total: 22 params: "i" + "s"x13 + "i"x2 + "s"x6
        $stmt->bind_param(
            "isssssssssssssiissssss",
            $lessonId,
            $type,
            $title,
            $body,
            $keyIdea,
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

        if (!$stmt->execute()) {
            throw new \RuntimeException(
                'insertInteractiveContent: execute() failed — ' . $stmt->error
            );
        }

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

    // ============================================================
    // ADMIN ACCOUNTS
    // ------------------------------------------------------------
    // ASSUMPTION: sub-admins (distinct from the logged-in Super Admin)
    // are stored in tbl_users with role = 'admin'. If your app tracks
    // admins in a separate tbl_admins table instead, swap the query
    // below to count from that table.
    // ============================================================
    public function getTotalAdmins()
    {
        $result = @$this->db->query("
            SELECT COUNT(*) AS total FROM tbl_users WHERE role = 'admin'
        ");
        return $result ? (int) $result->fetch_assoc()['total'] : 0;
    }

    // ============================================================
    // STRANDS
    // ------------------------------------------------------------
    // ASSUMPTION: strand (STEM, ABM, HUMSS, GAS, TVL, etc.) is stored
    // as a `strand` column on tbl_sections. If you instead have a
    // dedicated tbl_strands table, point these two methods at it —
    // they're written defensively (the @ + null check) so a missing
    // column/table returns an empty result instead of a fatal error.
    // ============================================================
    public function getStrandsOffered()
    {
        $result = @$this->db->query("
            SELECT DISTINCT strand
            FROM tbl_sections
            WHERE strand IS NOT NULL AND strand <> ''
            ORDER BY strand ASC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getEnrollmentByStrand()
    {
        $result = @$this->db->query("
            SELECT sec.strand AS strand, COUNT(se.student_id) AS total
            FROM tbl_student_enrollments se
            JOIN tbl_sections sec ON sec.id = se.section_id
            WHERE sec.strand IS NOT NULL AND sec.strand <> ''
            GROUP BY sec.strand
            ORDER BY sec.strand ASC
        ");
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ============================================================
    // BACKUPS
    // ------------------------------------------------------------
    // ASSUMPTION: whatever job runs your backups writes a row to
    // tbl_system_backups(created_at, status, type, size_mb) each time
    // it runs. Point this at your real backup log table/columns, or
    // create that table if backups aren't logged anywhere yet.
    // ============================================================
    public function getLastBackup()
    {
        try {
            $result = $this->db->query("
            SELECT created_at, status, type, size_mb
            FROM tbl_system_backups
            ORDER BY created_at DESC
            LIMIT 1
        ");
            return $result ? $result->fetch_assoc() : null;
        } catch (\mysqli_sql_exception $e) {
            // Table doesn't exist yet — no backups logged. Fail gracefully.
            return null;
        }
    }

    // ============================================================
    // ACTIVE SESSIONS
    // ------------------------------------------------------------
    // ASSUMPTION: tbl_users has a `last_activity` DATETIME column
    // that gets touched on every authenticated request (e.g. in your
    // auth middleware). "Active" = activity within $minutesThreshold
    // minutes. If you track sessions in a separate table instead,
    // swap the query to read from there.
    // ============================================================
    public function getActiveSessionsSummary($minutesThreshold = 5)
    {
        $summary = ['total' => 0, 'student' => 0, 'teacher' => 0, 'admin' => 0, 'superadmin' => 0];

        try {
            $result = $this->db->query("
            SELECT role, COUNT(*) AS total
            FROM tbl_users
            WHERE last_activity IS NOT NULL
              AND last_activity >= DATE_SUB(NOW(), INTERVAL {$minutesThreshold} MINUTE)
            GROUP BY role
        ");

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $role = $row['role'];
                    if (!isset($summary[$role])) {
                        $summary[$role] = 0;
                    }
                    $summary[$role] = (int) $row['total'];
                    $summary['total'] += (int) $row['total'];
                }
            }
        } catch (\mysqli_sql_exception $e) {
            // last_activity column doesn't exist yet — return zeroed summary
        }

        return $summary;
    }

    // ============================================================
    // SYSTEM ALERTS (Dashboard "Alerts That Need Attention")
    // ------------------------------------------------------------
    // Built from real, already-available signals (pending approvals)
    // plus two optional signals (failed backups, repeated failed
    // logins) that only fire if those tables exist — safe to leave
    // in even if you haven't built backup/login-attempt logging yet.
    // ============================================================
    public function getSystemAlerts($pendingApprovalsCount = 0)
    {
        $alerts = [];

        if ($pendingApprovalsCount > 0) {
            $alerts[] = [
                'severity' => 'warn',
                'title' => $pendingApprovalsCount . ' student' . ($pendingApprovalsCount === 1 ? '' : 's') . ' awaiting approval',
                'detail' => 'New enrollments are waiting for a super admin to approve.',
                'link' => '/learning_management/public/?url=super_admin_student_users',
                'action' => 'Review',
            ];
        }

        $lastBackup = $this->getLastBackup();
        if ($lastBackup && strtolower($lastBackup['status'] ?? '') === 'failed') {
            $alerts[] = [
                'severity' => 'err',
                'title' => 'Automated backup failed',
                'detail' => 'Last run on ' . date('M j, Y g:i A', strtotime($lastBackup['created_at'])) . ' did not complete.',
                'link' => '#',
                'action' => 'Retry',
            ];
        }

        // ASSUMPTION: failed login attempts are logged to
        // tbl_login_attempts(user_email, success, created_at).
        // This block silently no-ops if that table doesn't exist.
        $failedLogins = null;
        try {
            $failedLogins = $this->db->query("
        SELECT user_email, COUNT(*) AS attempts, MAX(created_at) AS last_attempt
        FROM tbl_login_attempts
        WHERE success = 0 AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
        GROUP BY user_email
        HAVING attempts >= 3
    ");
        } catch (\mysqli_sql_exception $e) {
            // tbl_login_attempts doesn't exist yet — skip this alert type
        }

        if ($failedLogins) {
            while ($row = $failedLogins->fetch_assoc()) {
                $alerts[] = [
                    'severity' => 'err',
                    'title' => 'Repeated failed logins',
                    'detail' => $row['attempts'] . ' failed attempts for ' . $row['user_email']
                        . ' (last at ' . date('g:i A', strtotime($row['last_attempt'])) . ')',
                    'link' => '#',
                    'action' => 'Review',
                ];
            }
        }

        return $alerts;
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

    // ============================================================
    // ACTIVITY LOGS — pulls from existing tables, no new table needed
    // ============================================================
    public function getActivityLogs($limit = 15)
    {
        $sql = "
    SELECT * FROM (

        SELECT
            'enrollment' AS action,
            CONCAT('Enrolled in ', s.subject_name, ' · ', sec.section_name) AS description,
            'student' AS role,
            u.name AS user_name,
            'tbl_student_enrollments' AS target,
            'Success' AS status,
            se.enrolled_at AS created_at
        FROM tbl_student_enrollments se
        JOIN tbl_students st  ON st.id  = se.student_id
        JOIN tbl_users u      ON u.id   = st.user_id
        JOIN tbl_subjects s   ON s.id   = se.subject_id
        JOIN tbl_sections sec ON sec.id = se.section_id

        UNION ALL

        SELECT
            'module_created' AS action,
            CONCAT('Created module ', im.title, ' in ', s.subject_name) AS description,
            'teacher' AS role,
            COALESCE(u.name, 'Unknown') AS user_name,
            'tbl_interactive_modules' AS target,
            'Success' AS status,
            im.created_at AS created_at
        FROM tbl_interactive_modules im
        JOIN tbl_subjects s      ON s.id = im.subject_id
        LEFT JOIN tbl_teachers t ON t.id = im.teacher_id
        LEFT JOIN tbl_users u    ON u.id = t.user_id

        UNION ALL

        SELECT
            'activity_submitted' AS action,
            CONCAT('Submitted assignment ', a.title, ' in ', s.subject_name) AS description,
            'student' AS role,
            u.name AS user_name,
            'tbl_assignment_submissions' AS target,
            'Success' AS status,
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
                CASE WHEN qr.passed = 1 THEN 'Passed' ELSE 'Submitted' END,
                ' quiz in ', s.subject_name,
                ' (', qr.score, '/', qr.total, ')'
            ) AS description,
            'student' AS role,
            u.name AS user_name,
            'tbl_quiz_results' AS target,
            CASE WHEN qr.passed = 1 THEN 'Success' ELSE 'Review' END AS status,
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
            CONCAT('Submitted activity in ', s.subject_name) AS description,
            'student' AS role,
            u.name AS user_name,
            'tbl_activity_submissions' AS target,
            'Success' AS status,
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
            'subject_created' AS action,
            CONCAT('Created subject ', s.subject_name, ' · ', COALESCE(gl.name, '')) AS description,
            'superadmin' AS role,
            'Super Admin' AS user_name,
            'tbl_subjects' AS target,
            'Success' AS status,
            s.created_at AS created_at
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE s.created_at IS NOT NULL

        UNION ALL

        SELECT
            'subject_updated' AS action,
            CONCAT('Updated subject ', s.subject_name, ' · ', COALESCE(gl.name, '')) AS description,
            'superadmin' AS role,
            'Super Admin' AS user_name,
            'tbl_subjects' AS target,
            'Success' AS status,
            s.updated_at AS created_at
        FROM tbl_subjects s
        LEFT JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE s.updated_at IS NOT NULL
          AND s.updated_at <> s.created_at

    ) AS combined
    WHERE created_at IS NOT NULL
    ORDER BY created_at DESC
    LIMIT ?
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Optional extra feed sources — no-op if those tables don't exist yet
        $logs = array_merge($logs, $this->getBackupActivityLogs());
        $logs = array_merge($logs, $this->getFailedLoginActivityLogs());

        usort($logs, fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));
        $logs = array_slice($logs, 0, $limit);

        foreach ($logs as &$log) {
            $log['time_ago'] = $this->timeAgo($log['created_at']);
        }

        return $logs;
    }

    private function getBackupActivityLogs($limit = 5)
    {
        try {
            $result = $this->db->query("SELECT created_at, status FROM tbl_system_backups ORDER BY created_at DESC LIMIT {$limit}");
            if (!$result)
                return [];
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $ok = strtolower($row['status'] ?? '') !== 'failed';
                $rows[] = [
                    'action' => 'automated_backup',
                    'description' => 'Automated backup',
                    'role' => 'system',
                    'user_name' => 'system',
                    'target' => 'database',
                    'status' => $ok ? 'Success' : 'Flagged',
                    'created_at' => $row['created_at'],
                ];
            }
            return $rows;
        } catch (\mysqli_sql_exception $e) {
            return [];
        }
    }

    private function getFailedLoginActivityLogs($limit = 5)
    {
        try {
            $result = $this->db->query("SELECT user_email, created_at FROM tbl_login_attempts WHERE success = 0 ORDER BY created_at DESC LIMIT {$limit}");
            if (!$result)
                return [];
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = [
                    'action' => 'failed_login',
                    'description' => 'Failed login attempt',
                    'role' => 'unknown',
                    'user_name' => $row['user_email'],
                    'target' => 'tbl_users',
                    'status' => 'Flagged',
                    'created_at' => $row['created_at'],
                ];
            }
            return $rows;
        } catch (\mysqli_sql_exception $e) {
            return [];
        }
    }
}