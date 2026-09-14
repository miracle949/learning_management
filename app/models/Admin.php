<?php
require_once "../core/Model.php";

class Admin extends Model
{
    // ─── NEW: private helper for activity log timestamps ───
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
    // ACTIVITY LOGS — pulls from existing tables, no new table needed
    // (moved here from SuperAdmin.php so the Admin dashboard can use it)
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

    ) AS combined
    WHERE created_at IS NOT NULL
    ORDER BY created_at DESC
    LIMIT ?
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($logs as &$log) {
            $log['time_ago'] = $this->timeAgo($log['created_at']);
        }

        return $logs;
    }

    public function countStudentsByStatus(string $status): int
    {
        // No approval workflow exists in this schema — every student row is active.
        return 0;
    }

    public function getTotalStudents()
    {
        $result = $this->db->query("
        SELECT COUNT(*) AS total 
        FROM tbl_students s 
        JOIN tbl_users u ON s.user_id = u.id 
        WHERE u.role = 'student'
    ");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalTeachers()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM tbl_teachers t JOIN tbl_users u ON t.user_id = u.id WHERE u.role = 'teacher'");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalPendingApprovals()
    {
        // No approval workflow exists in this schema — nothing is ever pending.
        return 0;
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
        // No approval workflow exists in this schema.
        return [];
    }

    public function importMasterlistFromCSV(string $filePath, string $schoolYear): array
    {
        $inserted = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $gradeSectionCounts = []; // NEW — tracks grade_level_id + section_id pairs actually written

        try {
            if (($handle = fopen($filePath, "r")) === false) {
                return ['success' => false, 'message' => 'Could not open uploaded file.'];
            }

            $header = fgetcsv($handle);
            $expected = ['student_LRN', 'first_name', 'last_name'];
            $headerLower = array_map('strtolower', $header);

            foreach ($expected as $col) {
                if (!in_array(strtolower($col), $headerLower)) {
                    fclose($handle);
                    return ['success' => false, 'message' => "Missing required column: $col"];
                }
            }

            $colIndex = array_flip($headerLower);

            $stmt = $this->db->prepare("
            INSERT INTO tbl_master_lrn 
                (student_LRN, first_name, last_name, middle_name, grade_level_id, section_id, strand, school_year, enrollment_status, imported_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active', NOW())
            ON DUPLICATE KEY UPDATE
                first_name = VALUES(first_name),
                last_name = VALUES(last_name),
                middle_name = VALUES(middle_name),
                grade_level_id = VALUES(grade_level_id),
                section_id = VALUES(section_id),
                strand = VALUES(strand),
                school_year = VALUES(school_year),
                updated_at = NOW()
        ");

            if (!$stmt) {
                fclose($handle);
                return ['success' => false, 'message' => 'Prepare failed: ' . $this->db->error];
            }

            while (($row = fgetcsv($handle)) !== false) {
                $lrn = trim($row[$colIndex['student_lrn']] ?? '');

                if (!preg_match('/^\d{12}$/', $lrn)) {
                    $skipped++;
                    $errors[] = "Skipped invalid LRN: $lrn";
                    continue;
                }

                $firstName = trim($row[$colIndex['first_name']] ?? '');
                $lastName = trim($row[$colIndex['last_name']] ?? '');
                $middleName = array_key_exists('middle_name', $colIndex) ? trim($row[$colIndex['middle_name']] ?? '') : '';
                $gradeLevel = array_key_exists('grade_level_id', $colIndex) ? (trim($row[$colIndex['grade_level_id']] ?? '') ?: null) : null;
                $sectionId = array_key_exists('section_id', $colIndex) ? (trim($row[$colIndex['section_id']] ?? '') ?: null) : null;
                $strand = array_key_exists('strand', $colIndex) ? trim($row[$colIndex['strand']] ?? '') : '';

                $stmt->bind_param(
                    "ssssiiss",
                    $lrn,
                    $firstName,
                    $lastName,
                    $middleName,
                    $gradeLevel,
                    $sectionId,
                    $strand,
                    $schoolYear
                );

                $ok = $stmt->execute();

                if (!$ok) {
                    $skipped++;
                    $errors[] = "Row with LRN $lrn failed: " . $stmt->error;
                    continue;
                }

                if ($stmt->affected_rows === 1) {
                    $inserted++;
                } elseif ($stmt->affected_rows === 2) {
                    $updated++;
                }

                // NEW — only tally grade/section for rows that actually wrote (insert or update),
                // and only when both IDs are present. Skips a wasted count for rows that
                // technically matched but had NULL grade/section.
                if (($stmt->affected_rows === 1 || $stmt->affected_rows === 2) && $gradeLevel && $sectionId) {
                    $key = $gradeLevel . '_' . $sectionId;
                    if (!isset($gradeSectionCounts[$key])) {
                        $gradeSectionCounts[$key] = [
                            'grade_level_id' => (int) $gradeLevel,
                            'section_id' => (int) $sectionId,
                            'count' => 0,
                        ];
                    }
                    $gradeSectionCounts[$key]['count']++;
                }
            }

            fclose($handle);

            return [
                'success' => true,
                'inserted' => $inserted,
                'updated' => $updated,
                'skipped' => $skipped,
                'errors' => $errors,
                'grade_section_counts' => array_values($gradeSectionCounts), // NEW
            ];

        } catch (\Throwable $e) {
            // Catches mysqli exceptions, TypeErrors, anything — guarantees valid JSON always comes back
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ];
        }
    }

    public function matchStudentAgainstMasterlist(string $lrn): ?array
    {
        $stmt = $this->db->prepare("
        SELECT * FROM tbl_master_lrn 
        WHERE student_LRN = ? AND is_matched = 0
    ");
        $stmt->bind_param("s", $lrn);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(); // null if no match
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
               COUNT(DISTINCT CONCAT(ta.subject_id, '-', ta.section_id)) AS class_count
        FROM tbl_teachers t
        JOIN tbl_users u ON t.user_id = u.id
        INNER JOIN tbl_teacher_assignments ta ON ta.teacher_id = t.id
        WHERE u.role = 'teacher'
        GROUP BY t.id, u.name
        HAVING class_count > 0
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
    // TEACHERS
    // ============================================================
    public function createTeacher($name, $username, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO tbl_users (name, username, password, role) VALUES (?, ?, ?, 'teacher')");
        $stmt->bind_param("sss", $name, $username, $hashed);
        $stmt->execute();
        $user_id = $this->db->insert_id;

        $stmt2 = $this->db->prepare("INSERT INTO tbl_teachers (user_id) VALUES (?)");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        return $this->db->insert_id;
    }

    private function generateJoinCode($length = 7)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM tbl_teacher_assignments WHERE join_code = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $count = (int) $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();
        } while ($count > 0);

        return $code;
    }

    public function assignPairs(int $teacher_id, array $pairs): void
    {
        foreach ($pairs as $section_id => $subject_ids) {
            $section_id = (int) $section_id;

            $stmt = $this->db->prepare("SELECT grade_level_id FROM tbl_sections WHERE id = ?");
            $stmt->bind_param("i", $section_id);
            $stmt->execute();
            $sec = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$sec)
                continue;
            $grade_level_id = (int) $sec['grade_level_id'];

            foreach ($subject_ids as $subject_id) {
                $subject_id = (int) $subject_id;

                $exist = $this->db->prepare("
                SELECT id, join_code FROM tbl_teacher_assignments
                WHERE teacher_id = ? AND subject_id = ? AND section_id = ?
            ");
                $exist->bind_param("iii", $teacher_id, $subject_id, $section_id);
                $exist->execute();
                $existing = $exist->get_result()->fetch_assoc();
                $exist->close();

                if ($existing) {
                    if (empty($existing['join_code'])) {
                        $code = $this->generateJoinCode(7);
                        $upd = $this->db->prepare("UPDATE tbl_teacher_assignments SET join_code = ? WHERE id = ?");
                        $upd->bind_param("si", $code, $existing['id']);
                        $upd->execute();
                        $upd->close();
                    }
                } else {
                    $join_code = $this->generateJoinCode(7);
                    $insert = $this->db->prepare("
                    INSERT INTO tbl_teacher_assignments 
                        (teacher_id, subject_id, grade_level_id, section_id, join_code)
                    VALUES (?, ?, ?, ?, ?)
                ");
                    $insert->bind_param("iiiis", $teacher_id, $subject_id, $grade_level_id, $section_id, $join_code);
                    $insert->execute();
                    $insert->close();
                }
            }
        }
    }

    public function backfillJoinCodes()
    {
        $stmt = $this->db->prepare("SELECT id FROM tbl_teacher_assignments WHERE join_code IS NULL OR join_code = ''");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $join_code = $this->generateJoinCode(7);
            $upd = $this->db->prepare("UPDATE tbl_teacher_assignments SET join_code = ? WHERE id = ?");
            $upd->bind_param("si", $join_code, $row['id']);
            $upd->execute();
            $upd->close();
        }
    }

    public function updateTeacherInfo(int $teacher_id, string $name, string $username): void
    {
        $stmt = $this->db->prepare("SELECT user_id FROM tbl_teachers WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$row)
            return;
        $user_id = (int) $row['user_id'];

        $stmt = $this->db->prepare("UPDATE tbl_users SET name = ?, username = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $username, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteTeacherAssignments(int $teacher_id): void
    {
        $stmt = $this->db->prepare("DELETE FROM tbl_teacher_assignments WHERE teacher_id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $stmt->close();
    }

    public function getTeacherStatusCounts(): array
    {
        $result = $this->db->query("
        SELECT t.id AS teacher_id, COUNT(DISTINCT ta.id) AS class_count
        FROM tbl_teachers t
        JOIN tbl_users u ON t.user_id = u.id
        LEFT JOIN tbl_teacher_assignments ta ON ta.teacher_id = t.id
        WHERE u.role = 'teacher'
        GROUP BY t.id
    ");

        $active = 0;
        $inactive = 0;
        while ($row = $result->fetch_assoc()) {
            if ((int) $row['class_count'] > 0) {
                $active++;
            } else {
                $inactive++;
            }
        }

        return [
            'total' => $active + $inactive,
            'active' => $active,
            'inactive' => $inactive,
        ];
    }

    public function getEnrolledStudentsBySubject($subject_id, $teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT u.name,
               gl.name AS grade_level,
               sec.section_name,
               sec.id AS section_id,
               ta.join_code
        FROM tbl_student_enrollments se
        JOIN tbl_students st  ON st.id  = se.student_id
        JOIN tbl_users u      ON u.id   = st.user_id
        JOIN tbl_sections sec ON sec.id = se.section_id
        JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
        LEFT JOIN tbl_teacher_assignments ta
            ON ta.subject_id  = se.subject_id
            AND ta.section_id  = se.section_id
            AND ta.teacher_id  = ?
        WHERE se.subject_id = ?
        ORDER BY sec.section_name ASC, u.name ASC
    ");
        $stmt->bind_param("ii", $teacher_id, $subject_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['grade_level'] . ' - ' . $row['section_name'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'section_label' => $key,
                    'join_code' => $row['join_code'] ?? '',
                    'students' => []
                ];
            }
            $grouped[$key]['students'][] = $row;
        }

        return array_values($grouped);
    }

    public function getMasterlistStatusCounts(): array
    {
        $result = $this->db->query("
        SELECT
            SUM(CASE WHEN is_matched = 1 THEN 1 ELSE 0 END) AS enrolled,
            SUM(CASE WHEN is_matched = 0 THEN 1 ELSE 0 END) AS pending
        FROM tbl_master_lrn
    ");
        $row = $result->fetch_assoc();
        return [
            'enrolled' => (int) ($row['enrolled'] ?? 0),
            'pending' => (int) ($row['pending'] ?? 0),
        ];
    }

    public function getRecentPendingMasterlist(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
        SELECT ml.student_LRN, ml.first_name, ml.last_name, ml.middle_name,
               gl.name AS grade_level, sec.section_name
        FROM tbl_master_lrn ml
        LEFT JOIN tbl_grade_level gl ON ml.grade_level_id = gl.id
        LEFT JOIN tbl_sections sec ON ml.section_id = sec.id
        WHERE ml.is_matched = 0
        ORDER BY ml.imported_at DESC
        LIMIT ?
    ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllTeachersFilteredPaginated(
        string $search = '',
        string $grade = '',
        string $section = '',
        string $status = '',
        int $limit = 10,
        int $offset = 0
    ): array {
        $innerSQL = "
            SELECT
                t.id   AS teacher_id,
                u.name,
                u.username,
                COUNT(DISTINCT ta.id) AS class_count,
                GROUP_CONCAT(
                    DISTINCT CONCAT(s.id, '~~', s.subject_name)
                    ORDER BY s.subject_name SEPARATOR '||'
                ) AS subjects_raw,
                GROUP_CONCAT(
                    DISTINCT sec.section_name
                    ORDER BY gl.name, sec.section_name SEPARATOR '||'
                ) AS sections_raw,
                GROUP_CONCAT(
                    DISTINCT gl.name
                    ORDER BY gl.name SEPARATOR '||'
                ) AS grade_levels_raw,
                GROUP_CONCAT(
                    DISTINCT LOWER(gl.name)
                    ORDER BY gl.name SEPARATOR '|'
                ) AS grades_raw
            FROM tbl_teachers t
            JOIN  tbl_users u  ON t.user_id = u.id
            LEFT JOIN tbl_teacher_assignments ta ON ta.teacher_id = t.id
            LEFT JOIN tbl_subjects s             ON ta.subject_id   = s.id
            LEFT JOIN tbl_sections sec           ON ta.section_id   = sec.id
            LEFT JOIN tbl_grade_level gl         ON ta.grade_level_id = gl.id
            WHERE u.role = 'teacher'
            GROUP BY t.id, u.name, u.username
        ";

        $outerWhere = [];
        $params = [];
        $types = '';

        if ($search !== '') {
            $like = '%' . $search . '%';
            $outerWhere[] = "(name LIKE ?)";
            $params[] = $like;
            $types .= 's';
        }
        if ($grade !== '') {
            $outerWhere[] = "FIND_IN_SET(LOWER(?), REPLACE(LOWER(COALESCE(grades_raw,'')), '|', ',')) > 0";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($section !== '') {
            $outerWhere[] = "LOWER(COALESCE(sections_raw,'')) LIKE ?";
            $params[] = '%' . strtolower($section) . '%';
            $types .= 's';
        }

        $outerWhereClause = $outerWhere ? 'WHERE ' . implode(' AND ', $outerWhere) : '';

        $sql = "
        SELECT *
        FROM ({$innerSQL}) AS teacher_agg
        {$outerWhereClause}
        ORDER BY name ASC
    ";

        if ($params) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($sql);
        }

        $all = [];
        while ($row = $result->fetch_assoc()) {
            $isActive = (int) $row['class_count'] > 0;
            $row['status_label'] = $isActive ? 'Active' : 'Not Active';

            if ($status !== '' && strtolower($status) !== strtolower($row['status_label'])) {
                continue;
            }

            if (!empty($row['subjects_raw'])) {
                $pairs = explode('||', $row['subjects_raw']);
                $row['subjects'] = array_map(function ($pair) {
                    $parts = explode('~~', $pair, 2);
                    return ['id' => $parts[0] ?? '', 'name' => $parts[1] ?? '', 'join_code' => ''];
                }, $pairs);
            } else {
                $row['subjects'] = [];
            }

            $row['sections'] = !empty($row['sections_raw'])
                ? explode('||', $row['sections_raw'])
                : [];

            $row['grade_levels'] = !empty($row['grade_levels_raw'])
                ? explode('||', $row['grade_levels_raw'])
                : [];

            unset($row['subjects_raw'], $row['sections_raw'], $row['grade_levels_raw'], $row['grades_raw']);

            $all[] = $row;
        }

        return array_slice($all, $offset, $limit);
    }

    public function getSectionStats(): array
    {
        $result = $this->db->query("
        SELECT sec.id, gl.name AS grade_level
        FROM tbl_sections sec
        JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
    ");
        $total = 0;
        $g11 = 0;
        $g12 = 0;
        while ($row = $result->fetch_assoc()) {
            $total++;
            if (stripos($row['grade_level'], '11') !== false)
                $g11++;
            if (stripos($row['grade_level'], '12') !== false)
                $g12++;
        }
        return ['total' => $total, 'grade11' => $g11, 'grade12' => $g12];
    }

    public function getAllSectionsWithDetails(string $search = '', string $grade = '', int $limit = 10, int $offset = 0): array
    {
        $where = ["1=1"];
        $params = [];
        $types = '';

        if ($search !== '') {
            $where[] = "sec.section_name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        if ($grade !== '') {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        $whereClause = implode(' AND ', $where);

        $sql = "
        SELECT sec.id, sec.section_name, gl.id AS grade_level_id, gl.name AS grade_level,
               (SELECT COUNT(*) FROM tbl_students s WHERE s.section_id = sec.id) AS student_count,
               GROUP_CONCAT(DISTINCT u.name ORDER BY u.name SEPARATOR ', ') AS teacher_names
        FROM tbl_sections sec
        JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
        LEFT JOIN tbl_teacher_assignments ta ON ta.section_id = sec.id
        LEFT JOIN tbl_teachers t ON t.id = ta.teacher_id
        LEFT JOIN tbl_users u ON u.id = t.user_id
        WHERE $whereClause
        GROUP BY sec.id, sec.section_name, gl.name
        ORDER BY gl.name ASC, sec.section_name ASC
        LIMIT ? OFFSET ?
    ";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllSectionsFiltered(string $search = '', string $grade = ''): int
    {
        $where = ["1=1"];
        $params = [];
        $types = '';

        if ($search !== '') {
            $where[] = "sec.section_name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        if ($grade !== '') {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        $whereClause = implode(' AND ', $where);

        $sql = "
        SELECT COUNT(*) AS total
        FROM tbl_sections sec
        JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
        WHERE $whereClause
    ";

        if ($params) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return (int) $stmt->get_result()->fetch_assoc()['total'];
        }
        return (int) $this->db->query($sql)->fetch_assoc()['total'];
    }

    public function createSection(string $name, int $gradeLevelId): void
    {
        $stmt = $this->db->prepare("
        INSERT INTO tbl_sections (section_name, grade_level_id)
        VALUES (?, ?)
    ");
        $stmt->bind_param("si", $name, $gradeLevelId);
        $stmt->execute();
    }

    public function updateSection(int $id, string $name, int $gradeLevelId): void
    {
        $stmt = $this->db->prepare("
        UPDATE tbl_sections
        SET section_name = ?, grade_level_id = ?
        WHERE id = ?
    ");
        $stmt->bind_param("sii", $name, $gradeLevelId, $id);
        $stmt->execute();
    }

    public function deleteSection(int $id): bool
    {
        $check = $this->db->prepare("SELECT COUNT(*) AS cnt FROM tbl_students WHERE section_id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $count = (int) $check->get_result()->fetch_assoc()['cnt'];
        if ($count > 0) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM tbl_sections WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }

    public function getAllTeachersForDropdown(): array
    {
        $result = $this->db->query("
        SELECT t.id, u.name
        FROM tbl_teachers t
        JOIN tbl_users u ON u.id = t.user_id
        WHERE u.role = 'teacher'
        ORDER BY u.name ASC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllTeachersFiltered(
        string $search = '',
        string $grade = '',
        string $section = '',
        string $status = ''
    ): int {
        $innerSQL = "
        SELECT
            t.id AS teacher_id,
            u.name,
            COUNT(DISTINCT ta.id) AS class_count,
            GROUP_CONCAT(
                DISTINCT LOWER(gl.name)
                ORDER BY gl.name SEPARATOR '|'
            ) AS grades_raw,
            GROUP_CONCAT(
                DISTINCT CONCAT(gl.name, ' - ', sec.section_name)
                ORDER BY gl.name, sec.section_name SEPARATOR '||'
            ) AS sections_raw
        FROM tbl_teachers t
        JOIN  tbl_users u  ON t.user_id = u.id
        LEFT JOIN tbl_teacher_assignments ta ON ta.teacher_id = t.id
        LEFT JOIN tbl_subjects s             ON ta.subject_id   = s.id
        LEFT JOIN tbl_sections sec           ON ta.section_id   = sec.id
        LEFT JOIN tbl_grade_level gl         ON ta.grade_level_id = gl.id
        WHERE u.role = 'teacher'
        GROUP BY t.id, u.name
    ";

        $outerWhere = [];
        $params = [];
        $types = '';

        if ($search !== '') {
            $like = '%' . $search . '%';
            $outerWhere[] = "(name LIKE ?)";
            $params[] = $like;
            $types .= 's';
        }
        if ($grade !== '') {
            $outerWhere[] = "FIND_IN_SET(LOWER(?), REPLACE(LOWER(COALESCE(grades_raw,'')), '|', ',')) > 0";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($section !== '') {
            $outerWhere[] = "LOWER(COALESCE(sections_raw,'')) LIKE ?";
            $params[] = '%' . strtolower($section) . '%';
            $types .= 's';
        }

        $outerWhereClause = $outerWhere ? 'WHERE ' . implode(' AND ', $outerWhere) : '';

        $sql = "
        SELECT *
        FROM ({$innerSQL}) AS teacher_agg
        {$outerWhereClause}
        ORDER BY name ASC
    ";

        if ($params) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($sql);
        }

        $count = 0;
        while ($row = $result->fetch_assoc()) {
            $isActive = (int) $row['class_count'] > 0;
            $statusLabel = $isActive ? 'Active' : 'Not Active';
            if ($status !== '' && strtolower($status) !== strtolower($statusLabel)) {
                continue;
            }
            $count++;
        }
        return $count;
    }

    // ============================================================
    // STUDENTS
    // ============================================================
    public function getAllStudentsFiltered($limit, $offset, $search = '', $grade = '', $section = '', $status = '', $dateFrom = '', $dateTo = '')
    {
        $where = ["u.role = 'student'"];
        $params = [];
        $types = '';

        if ($search) {
            $where[] = "(u.name LIKE ? OR s.student_LRN LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $types .= 'ss';
        }
        if ($grade) {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($section) {
            $where[] = "LOWER(sec.section_name) = ?";
            $params[] = strtolower($section);
            $types .= 's';
        }
        if ($dateFrom !== '' && $dateTo !== '') {                    // ADD
            $where[] = "DATE(s.created_at) BETWEEN ? AND ?";         // ADD
            $params[] = $dateFrom;                                   // ADD
            $params[] = $dateTo;                                     // ADD
            $types .= 'ss';                                          // ADD
        }                                                             // ADD

        $whereClause = implode(' AND ', $where);
        $sql = "
        SELECT s.id AS student_id, s.user_id, s.grade_level_id, s.section_id,
        s.student_LRN, u.name,
        u.id AS id,
        gl.name AS grade_level, sec.section_name,
        s.created_at AS date_enrolled
        FROM tbl_students s
        JOIN tbl_users u ON s.user_id = u.id
        JOIN tbl_grade_level gl ON s.grade_level_id = gl.id
        JOIN tbl_sections sec ON s.section_id = sec.id
        WHERE $whereClause
        ORDER BY u.id ASC
        LIMIT ? OFFSET ?
    ";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllStudentsFiltered($search = '', $grade = '', $section = '', $status = '', $dateFrom = '', $dateTo = '')
    {
        $where = ["u.role = 'student'"];
        $params = [];
        $types = '';

        if ($search) {
            $where[] = "(u.name LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $types .= 's';
        }
        if ($grade) {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($section) {
            $where[] = "LOWER(sec.section_name) = ?";
            $params[] = strtolower($section);
            $types .= 's';
        }
        if ($dateFrom !== '' && $dateTo !== '') {                    // ADD
            $where[] = "DATE(s.created_at) BETWEEN ? AND ?";         // ADD
            $params[] = $dateFrom;                                   // ADD
            $params[] = $dateTo;                                     // ADD
            $types .= 'ss';                                          // ADD
        }                                                             // ADD

        $whereClause = implode(' AND ', $where);
        $sql = "
        SELECT COUNT(*) AS total
        FROM tbl_students s
        JOIN tbl_users u ON s.user_id = u.id
        JOIN tbl_grade_level gl ON s.grade_level_id = gl.id
        JOIN tbl_sections sec ON s.section_id = sec.id
        WHERE $whereClause
    ";

        if ($params) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return (int) $stmt->get_result()->fetch_assoc()['total'];
        }

        return (int) $this->db->query($sql)->fetch_assoc()['total'];
    }

    public function getAllGradeLevels()
    {
        $result = $this->db->query("SELECT id, name FROM tbl_grade_level ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllSections()
    {
        $result = $this->db->query("
        SELECT sec.id, sec.section_name, gl.id AS grade_level_id, gl.name AS grade_name
        FROM tbl_sections sec
        JOIN tbl_grade_level gl ON gl.id = sec.grade_level_id
        ORDER BY gl.name ASC, sec.section_name ASC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStudent($user_id, $name, $grade_level_id, $section_id, $student_LRN, $student_id)
    {
        $stmt = $this->db->prepare("UPDATE tbl_users SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt2 = $this->db->prepare("
        UPDATE tbl_students 
        SET grade_level_id = ?, section_id = ?, student_LRN = ?, updated_at = NOW()
        WHERE id = ?
    ");
        $stmt2->bind_param("iisi", $grade_level_id, $section_id, $student_LRN, $student_id);
        $stmt2->execute();
        $stmt2->close();
    }

    // ============================================================
    // MASTERLIST
    // ============================================================
    public function getMasterlistFiltered($limit, $offset, $search = '', $grade = '', $section = '', $strand = '', $schoolYear = '', $status = '', $dateFrom = '', $dateTo = '')
    {
        $where = ["1=1"];
        $params = [];
        $types = '';

        if ($search) { /* unchanged */
            $where[] = "(ml.student_LRN LIKE ? OR ml.first_name LIKE ? OR ml.last_name LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= 'sss';
        }
        if ($grade) {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($section) {
            $where[] = "LOWER(sec.section_name) = ?";
            $params[] = strtolower($section);
            $types .= 's';
        }
        if ($strand) {
            $where[] = "LOWER(ml.strand) = ?";
            $params[] = strtolower($strand);
            $types .= 's';
        }
        if ($schoolYear) {
            $where[] = "ml.school_year = ?";
            $params[] = $schoolYear;
            $types .= 's';
        }
        if ($status !== '') {
            $where[] = "ml.is_matched = ?";
            $params[] = (strtolower($status) === 'enrolled') ? 1 : 0;
            $types .= 'i';
        }
        if ($dateFrom !== '' && $dateTo !== '') {                    // ADD
            $where[] = "DATE(ml.imported_at) BETWEEN ? AND ?";       // ADD
            $params[] = $dateFrom;                                   // ADD
            $params[] = $dateTo;                                     // ADD
            $types .= 'ss';                                          // ADD
        }                                                             // ADD

        $whereClause = implode(' AND ', $where);
        $sql = "
    SELECT ml.id, ml.student_LRN, ml.first_name, ml.last_name, ml.middle_name,
           ml.strand, ml.school_year, ml.is_matched, ml.matched_student_id,
           ml.grade_level_id, ml.section_id, ml.imported_at,
           gl.name AS grade_level, sec.section_name
    FROM tbl_master_lrn ml
    LEFT JOIN tbl_grade_level gl ON ml.grade_level_id = gl.id
    LEFT JOIN tbl_sections sec ON ml.section_id = sec.id
    WHERE $whereClause
    ORDER BY ml.last_name ASC, ml.first_name ASC
    LIMIT ? OFFSET ?
";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countMasterlistFiltered($search = '', $grade = '', $section = '', $strand = '', $schoolYear = '', $status = '', $dateFrom = '', $dateTo = '')
    {
        $where = ["1=1"];
        $params = [];
        $types = '';

        if ($search) {
            $where[] = "(ml.student_LRN LIKE ? OR ml.first_name LIKE ? OR ml.last_name LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= 'sss';
        }
        if ($grade) {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($section) {
            $where[] = "LOWER(sec.section_name) = ?";
            $params[] = strtolower($section);
            $types .= 's';
        }
        if ($strand) {
            $where[] = "LOWER(ml.strand) = ?";
            $params[] = strtolower($strand);
            $types .= 's';
        }
        if ($schoolYear) {
            $where[] = "ml.school_year = ?";
            $params[] = $schoolYear;
            $types .= 's';
        }
        if ($status !== '') {
            $where[] = "ml.is_matched = ?";
            $params[] = (strtolower($status) === 'enrolled') ? 1 : 0;
            $types .= 'i';
        }

        if ($dateFrom !== '' && $dateTo !== '') {                    // ADD
            $where[] = "DATE(ml.imported_at) BETWEEN ? AND ?";       // ADD
            $params[] = $dateFrom;                                   // ADD
            $params[] = $dateTo;                                     // ADD
            $types .= 'ss';                                          // ADD
        }                                                             // ADD

        $whereClause = implode(' AND ', $where);
        $sql = "
    SELECT COUNT(*) AS total
    FROM tbl_master_lrn ml
    LEFT JOIN tbl_grade_level gl ON ml.grade_level_id = gl.id
    LEFT JOIN tbl_sections sec ON ml.section_id = sec.id
    WHERE $whereClause
";

        if ($params) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return (int) $stmt->get_result()->fetch_assoc()['total'];
        }
        return (int) $this->db->query($sql)->fetch_assoc()['total'];
    }

    public function getDistinctMasterlistStrands(): array
    {
        $result = $this->db->query("
        SELECT DISTINCT strand FROM tbl_master_lrn
        WHERE strand IS NOT NULL AND strand != ''
        ORDER BY strand ASC
    ");
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'strand');
    }

    public function getDistinctMasterlistSchoolYears(): array
    {
        $result = $this->db->query("
        SELECT DISTINCT school_year FROM tbl_master_lrn
        WHERE school_year IS NOT NULL AND school_year != ''
        ORDER BY school_year DESC
    ");
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'school_year');
    }


}