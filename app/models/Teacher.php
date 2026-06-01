<?php
require_once "../core/Model.php";

class Teacher extends Model
{

    public function getTeacherIdByUserId($user_id)
    {
        $stmt = $this->db->prepare("SELECT id AS teacher_id FROM teachers WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTeacherClasses($teacher_id)
    {
        $sql = "
    SELECT
        ta.subject_id,
        ta.grade_level_id,
        ta.section_id,
        s.subject_name,
        gl.name AS grade_name,
        sec.section_name AS section,
        COUNT(DISTINCT se.student_id) AS student_count,
        (
            SELECT COUNT(*) FROM modules m
            WHERE m.subject_id = ta.subject_id AND m.teacher_id = ta.teacher_id
        ) AS material_count,
        (
            SELECT COUNT(*) FROM notifications n
            WHERE n.subject_id = ta.subject_id 
            AND n.sender_id = ta.teacher_id
            AND n.type = 'announcement'
        ) AS announcement_count,
        (
            SELECT COUNT(*) FROM interactive_modules im
            WHERE im.subject_id = ta.subject_id AND im.teacher_id = ta.teacher_id
        ) AS interactive_module_count
    FROM teacher_assignments ta
    JOIN subjects s     ON ta.subject_id     = s.id
    JOIN grade_level gl  ON ta.grade_level_id = gl.id
    JOIN sections sec    ON ta.section_id     = sec.id
    LEFT JOIN student_enrollments se
        ON se.subject_id = ta.subject_id AND se.section_id = ta.section_id
    WHERE ta.teacher_id = ?
    GROUP BY ta.subject_id, ta.grade_level_id, ta.section_id,
             s.subject_name, gl.name, sec.section_name
    ORDER BY gl.name, s.subject_name, sec.section_name
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserIdByTeacherId($teacher_id)
    {
        $stmt = $this->db->prepare("SELECT user_id FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['user_id'] ?? null;
    }

    // ============================================================
    // Get class info for ONE specific section.
    // ============================================================
    public function getClassInfo($subjectId, $gradeLevelId, $sectionId = 0)
    {
        $sql = "
            SELECT
                s.subject_name,
                gl.name          AS grade,
                sec.section_name AS section,
                sec.id           AS section_id,
                gl.id            AS grade_level_id
            FROM subjects s
            JOIN grade_level gl  ON gl.id  = s.grade_level_id
            JOIN sections    sec ON sec.grade_level_id = gl.id
            WHERE s.id = ? AND gl.id = ?
        ";
        $params = [$subjectId, $gradeLevelId];
        $types = "ii";

        if ($sectionId > 0) {
            $sql .= " AND sec.id = ?";
            $params[] = $sectionId;
            $types .= "i";
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ============================================================
    // Count students enrolled in a specific subject + section.
    // ============================================================
    public function getStudentCountBySection($subjectId, $sectionId = 0)
    {
        if ($sectionId > 0) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) AS total FROM student_enrollments
                WHERE subject_id = ? AND section_id = ?
            ");
            $stmt->bind_param("ii", $subjectId, $sectionId);
        } else {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) AS total FROM student_enrollments WHERE subject_id = ?
            ");
            $stmt->bind_param("i", $subjectId);
        }
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTeacherStats($teacher_id)
    {
        $sql = "SELECT COUNT(*) AS total_classes, 0 AS total_modules
                FROM teacher_assignments ta WHERE ta.teacher_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getStudentCountPerClass($teacher_id)
    {
        $sql = "
            SELECT ta.subject_id, ta.section_id, COUNT(se.student_id) AS total_students
            FROM teacher_assignments ta
            LEFT JOIN student_enrollments se
                ON se.subject_id = ta.subject_id AND se.section_id = ta.section_id
            WHERE ta.teacher_id = ?
            GROUP BY ta.subject_id, ta.section_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentStudents($limit = 5)
    {
        $sql = "
            SELECT u.name, u.email, gl.name AS grade_level_name, sec.section_name
            FROM students s
            JOIN users u ON s.user_id = u.id
            JOIN grade_level gl ON s.grade_level_id = gl.id
            JOIN sections sec ON s.section_id = sec.id
            WHERE u.role = 'student' AND u.status = '1'
            ORDER BY s.id DESC LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $students = [];
        while ($row = $result->fetch_assoc())
            $students[] = $row;
        return $students;
    }

    public function createTeacher($name, $email, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'teacher')");
        $stmt->bind_param("sss", $name, $email, $hashed);
        $stmt->execute();
        $user_id = $this->db->insert_id;

        $stmt2 = $this->db->prepare("INSERT INTO teachers (user_id) VALUES (?)");
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
            // Check uniqueness in teacher_assignments now
            $stmt = $this->db->prepare("SELECT COUNT(*) AS cnt FROM teacher_assignments WHERE join_code = ?");
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $count = (int) $stmt->get_result()->fetch_assoc()['cnt'];
            $stmt->close();
        } while ($count > 0);

        return $code;
    }

    public function assignSubjectsAndSections($teacher_id, $assigned_subjects, $assigned_sections)
    {
        foreach ($assigned_subjects as $subject_id) {

            $stmt = $this->db->prepare("SELECT grade_level_id FROM subjects WHERE id = ?");
            $stmt->bind_param("i", $subject_id);
            $stmt->execute();
            $subject = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $grade_level_id = $subject['grade_level_id'] ?? null;

            foreach ($assigned_sections as $section_id) {
                $check = $this->db->prepare("SELECT id FROM sections WHERE id = ? AND grade_level_id = ?");
                $check->bind_param("ii", $section_id, $grade_level_id);
                $check->execute();
                $match = $check->get_result()->fetch_assoc();
                $check->close();

                if ($match) {
                    // ── Check if this exact assignment already exists ──
                    $exist = $this->db->prepare("
                    SELECT id, join_code FROM teacher_assignments
                    WHERE teacher_id = ? AND subject_id = ? AND section_id = ?
                ");
                    $exist->bind_param("iii", $teacher_id, $subject_id, $section_id);
                    $exist->execute();
                    $existing = $exist->get_result()->fetch_assoc();
                    $exist->close();

                    if ($existing) {
                        // Already assigned — generate code if missing
                        if (empty($existing['join_code'])) {
                            $join_code = $this->generateJoinCode(7);
                            $upd = $this->db->prepare("UPDATE teacher_assignments SET join_code = ? WHERE id = ?");
                            $upd->bind_param("si", $join_code, $existing['id']);
                            $upd->execute();
                            $upd->close();
                        }
                    } else {
                        // New assignment — generate fresh code
                        $join_code = $this->generateJoinCode(7);
                        $insert = $this->db->prepare("
                        INSERT INTO teacher_assignments (teacher_id, subject_id, grade_level_id, section_id, join_code)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                        $insert->bind_param("iiiis", $teacher_id, $subject_id, $grade_level_id, $section_id, $join_code);
                        $insert->execute();
                        $insert->close();
                    }
                }
            }
        }
    }

    public function getAllStudents($limit = 10, $offset = 0)
    {
        $sql = "
        SELECT 
            s.id AS student_id,
            s.user_id,
            s.grade_level_id,
            s.section_id,
            s.student_LRN,
            s.status,
            u.name,
            u.email,
            gl.name AS grade_level,
            sec.section_name
        FROM students s
        JOIN users u ON s.user_id = u.id
        JOIN grade_level gl ON s.grade_level_id = gl.id
        JOIN sections sec ON s.section_id = sec.id
        WHERE u.role = 'student'
        ORDER BY gl.name ASC, sec.section_name ASC, u.name ASC
        LIMIT ? OFFSET ?
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllStudents()
    {
        $sql = "
        SELECT COUNT(*) AS total
        FROM students s
        JOIN users u ON s.user_id = u.id
        WHERE u.role = 'student'
    ";
        $result = $this->db->query($sql);
        return (int) $result->fetch_assoc()['total'];
    }

    public function getAllTeachersFiltered(
        string $search = '',
        string $grade = '',
        string $section = '',
        string $status = ''
    ): array {

        /* Step 1 — inner query aggregates every teacher unconditionally */
        $innerSQL = "
            SELECT
                t.id   AS teacher_id,
                u.name,
                u.email,
                COUNT(DISTINCT ta.id) AS class_count,
                GROUP_CONCAT(
                    DISTINCT CONCAT(s.id, '~~', s.subject_name)
                    ORDER BY s.subject_name SEPARATOR '||'
                ) AS subjects_raw,
                GROUP_CONCAT(
                    DISTINCT CONCAT(gl.name, ' - ', sec.section_name)
                    ORDER BY gl.name, sec.section_name SEPARATOR '||'
                ) AS sections_raw,
                GROUP_CONCAT(
                    DISTINCT LOWER(gl.name)
                    ORDER BY gl.name SEPARATOR '|'
                ) AS grades_raw
            FROM teachers t
            JOIN  users u  ON t.user_id = u.id
            LEFT JOIN teacher_assignments ta ON ta.teacher_id = t.id
            LEFT JOIN subjects s             ON ta.subject_id   = s.id
            LEFT JOIN sections sec           ON ta.section_id   = sec.id
            LEFT JOIN grade_level gl         ON ta.grade_level_id = gl.id
            WHERE u.role = 'teacher'
            GROUP BY t.id, u.name, u.email
        ";

        /* Step 2 — outer query filters on the aggregated columns */
        $outerWhere = [];
        $params = [];
        $types = '';

        if ($search !== '') {
            $like = '%' . $search . '%';
            $outerWhere[] = "(name LIKE ? OR email LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $types .= 'ss';
        }

        if ($grade !== '') {
            /*
             * grades_raw = "grade 11|grade 12"
             * We need an exact pipe-delimited token match so "grade 1"
             * doesn't match "grade 11".
             * Use: FIND_IN_SET with '|' separator via REPLACE trick.
             */
            $outerWhere[] = "FIND_IN_SET(LOWER(?), REPLACE(LOWER(COALESCE(grades_raw,'')), '|', ',')) > 0";
            $params[] = strtolower($grade);
            $types .= 's';
        }

        if ($section !== '') {
            /*
             * sections_raw = "Grade 12 - CSS 12-1||Grade 11 - CSS 11-1"
             * We check if the section name part (after " - ") appears.
             */
            $outerWhere[] = "LOWER(COALESCE(sections_raw,'')) LIKE ?";
            $params[] = '%' . strtolower($section) . '%';
            $types .= 's';
        }

        $outerWhereClause = $outerWhere
            ? 'WHERE ' . implode(' AND ', $outerWhere)
            : '';

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

        $teachers = [];
        while ($row = $result->fetch_assoc()) {

            /* Parse subjects */
            if (!empty($row['subjects_raw'])) {
                $pairs = explode('||', $row['subjects_raw']);
                $row['subjects'] = array_map(function ($pair) {
                    $parts = explode('~~', $pair, 2);
                    return ['id' => $parts[0] ?? '', 'name' => $parts[1] ?? '', 'join_code' => ''];
                }, $pairs);
            } else {
                $row['subjects'] = [];
            }

            /* Parse sections */
            $row['sections'] = !empty($row['sections_raw'])
                ? explode('||', $row['sections_raw'])
                : [];

            /* Cleanup helper columns */
            unset($row['subjects_raw'], $row['sections_raw'], $row['grades_raw']);

            /* Derive status */
            $isActive = (int) $row['class_count'] > 0;
            $row['status_label'] = $isActive ? 'Active' : 'Not Active';

            /* Status post-filter (derived value, can't do in SQL) */
            if ($status !== '' && strtolower($status) !== strtolower($row['status_label'])) {
                continue;
            }

            $teachers[] = $row;
        }

        return $teachers;
    }

    public function updateTeacherInfo(int $teacher_id, string $name, string $email, string $password = ''): void
    {
        // Get the user_id linked to this teacher
        $stmt = $this->db->prepare("SELECT user_id FROM teachers WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row)
            return;
        $user_id = (int) $row['user_id'];

        if (!empty($password)) {
            // Update name, email, and password
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $hashed, $user_id);
        } else {
            // Update name and email only
            $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Delete all teacher_assignments rows for a teacher.
     */
    public function deleteTeacherAssignments(int $teacher_id): void
    {
        $stmt = $this->db->prepare("DELETE FROM teacher_assignments WHERE teacher_id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $stmt->close();
    }

    public function updateTeacherAssignments(int $teacher_id, array $subject_ids, array $section_ids): void
    {
        /* Delete all existing assignments for this teacher */
        $del = $this->db->prepare("DELETE FROM teacher_assignments WHERE teacher_id = ?");
        $del->bind_param("i", $teacher_id);
        $del->execute();
        $del->close();

        /* Re-assign using the existing assignSubjectsAndSections logic */
        if (!empty($subject_ids) && !empty($section_ids)) {
            $this->assignSubjectsAndSections($teacher_id, $subject_ids, $section_ids);
        }
    }

    public function getAllTeachers()
    {
        $sql = "
    SELECT t.id AS teacher_id, u.name, u.email,
        GROUP_CONCAT(DISTINCT CONCAT(s.id, '~~', s.subject_name)
            ORDER BY s.subject_name SEPARATOR '||') AS subjects,
        COUNT(DISTINCT ta.id) AS class_count,
        GROUP_CONCAT(DISTINCT CONCAT(gl.name, ' - ', sec.section_name)
            ORDER BY gl.name SEPARATOR '||') AS sections
    FROM teachers t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN teacher_assignments ta ON ta.teacher_id = t.id
    LEFT JOIN subjects s ON ta.subject_id = s.id
    LEFT JOIN sections sec ON ta.section_id = sec.id
    LEFT JOIN grade_level gl ON ta.grade_level_id = gl.id
    WHERE u.role = 'teacher'
    GROUP BY t.id, u.name, u.email ORDER BY u.name ASC
    ";

        $result = $this->db->query($sql);
        $teachers = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['subjects']) {
                $pairs = explode('||', $row['subjects']);
                $row['subjects'] = array_map(function ($pair) {
                    $parts = explode('~~', $pair, 2);
                    return [
                        'id' => $parts[0] ?? '',
                        'name' => $parts[1] ?? '',
                        'join_code' => ''  // fetched separately below
                    ];
                }, $pairs);
            } else {
                $row['subjects'] = [];
            }
            $row['sections'] = $row['sections'] ? explode('||', $row['sections']) : [];
            $teachers[] = $row;
        }
        return $teachers;
    }

    public function getEnrolledStudentsBySubject($subject_id, $teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT u.name, u.email,
               gl.name AS grade_level,
               sec.section_name,
               sec.id AS section_id,
               ta.join_code
        FROM student_enrollments se
        JOIN students st  ON st.id  = se.student_id
        JOIN users u      ON u.id   = st.user_id
        JOIN sections sec ON sec.id = se.section_id
        JOIN grade_level gl ON gl.id = sec.grade_level_id
        -- Join teacher_assignments to get the join_code for THIS teacher + subject + section
        LEFT JOIN teacher_assignments ta
            ON ta.subject_id  = se.subject_id
            AND ta.section_id  = se.section_id
            AND ta.teacher_id  = ?
        WHERE se.subject_id = ?
        ORDER BY sec.section_name ASC, u.name ASC
    ");
        $stmt->bind_param("ii", $teacher_id, $subject_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Group by section, include join_code per section
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

    // ============================================================
// NOTIFICATIONS (formerly announcements)
// ============================================================
    public function getAnnouncements($subjectId, $teacherId, $sectionId = 0)
    {
        $sql = "SELECT id, title, message AS body, created_at AS posted_at 
            FROM notifications 
            WHERE subject_id = ? AND type = 'announcement'";
        $params = [$subjectId];
        $types = "i";

        if ($sectionId > 0) {
            $sql .= " AND section_id = ?";
            $params[] = $sectionId;
            $types .= "i";
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertAnnouncement($subjectId, $sectionId, $teacherId, $title, $body)
    {
        $userId = $this->getUserIdByTeacherId($teacherId);
        if (!$userId)
            return null;

        $stmt = $this->db->prepare("
        INSERT INTO notifications (sender_id, subject_id, section_id, title, message, type, created_at)
        VALUES (?, ?, ?, ?, ?, 'announcement', NOW())
    ");
        $stmt->bind_param("iiiss", $userId, $subjectId, $sectionId, $title, $body);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // ============================================================
    // ASSIGNMENTS
    // ============================================================
    public function getAssignments($subjectId, $teacherId, $sectionId = 0)
    {
        $sql = "SELECT id, title, description, due_date, due_time, points, created_at,
                   file_name, file_path, file_type
            FROM assignments 
            WHERE subject_id = ? AND teacher_id = ?";

        $params = [$subjectId, $teacherId];
        $types = "ii";

        if ($sectionId > 0) {
            $sql .= " AND section_id = ?";
            $params[] = $sectionId;
            $types .= "i";
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertAssignment(
        $subjectId,
        $sectionId,
        $teacherId,
        $type,
        $title,
        $description,
        $task,
        $instructions,
        $dueDate,
        $due_time,
        $points,
        $fileName = null,
        $filePath = null,
        $fileType = null
    ) {
        $desc = $description ?? null;
        $task = $task ?? null;
        $instr = $instructions ?? null;
        $type = $type ?? 'seatwork';
        $due = $dueDate ?? null;
        $dTime = $due_time ?? '23:59:00';  // ← ADD THIS
        $fName = $fileName ?? null;
        $fPath = $filePath ?? null;
        $fType = $fileType ?? null;

        $stmt = $this->db->prepare("
        INSERT INTO assignments 
            (subject_id, section_id, teacher_id, type, title, description, task, instructions, 
             due_date, due_time, points, file_name, file_path, file_type, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param(
            "iiisssssssisss",
            $subjectId,
            $sectionId,
            $teacherId,
            $type,
            $title,
            $desc,
            $task,
            $instr,
            $due,
            $dTime,   // ← ADD THIS
            $points,
            $fName,
            $fPath,
            $fType
        );
        $stmt->execute();
        return $this->db->insert_id;
    }

    // ============================================================
    // STUDENT SUBMISSIONS
    // ============================================================
    public function getSubmissions($assignmentId)
    {
        $stmt = $this->db->prepare("
        SELECT
            asub.id,
            asub.student_id,
            asub.file_path,
            asub.submitted_at,
            asub.status,
            asub.points_earned,
            asub.feedback,
            u.name AS student_name
        FROM assignment_submissions asub
        JOIN students st ON st.id = asub.student_id
        JOIN users    u  ON u.id  = st.user_id
        WHERE asub.assignment_id = ?
        ORDER BY asub.submitted_at DESC
    ");
        $stmt->bind_param("i", $assignmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getEnrolledStudentsBySection($subject_id, $section_id)
    {
        $stmt = $this->db->prepare("
        SELECT u.name, sec.section_name, gl.name AS grade_level
        FROM student_enrollments se
        JOIN students st  ON st.id  = se.student_id
        JOIN users u      ON u.id   = st.user_id
        JOIN sections sec ON sec.id = se.section_id
        JOIN grade_level gl ON gl.id = sec.grade_level_id
        WHERE se.subject_id = ? AND se.section_id = ?
        ORDER BY u.name ASC
    ");
        $stmt->bind_param("ii", $subject_id, $section_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // CLASSES FEED MODULES
    // Adjusted to work directly with the modules table columns:
    // file_name, file_path, file_type, file_size (no module_materials table)
    // ============================================================
    public function countModules($subjectId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM modules WHERE subject_id = ?");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countInteractiveModules($subjectId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM interactive_modules WHERE subject_id = ?");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function countLessons($interactiveModuleId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM lessons WHERE interactive_module_id = ?");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function backfillJoinCodes()
    {
        $stmt = $this->db->prepare("SELECT id FROM teacher_assignments WHERE join_code IS NULL OR join_code = ''");
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        foreach ($rows as $row) {
            $join_code = $this->generateJoinCode(7);
            $upd = $this->db->prepare("UPDATE teacher_assignments SET join_code = ? WHERE id = ?");
            $upd->bind_param("si", $join_code, $row['id']);
            $upd->execute();
            $upd->close();
        }
    }

    public function getModules($subjectId, $teacherId = null, $sectionId = 0)
    {
        $sql = "SELECT id, title, description, posted_at,
                   file_name, file_path, file_type, file_size
            FROM modules WHERE subject_id = ?";
        $params = [$subjectId];
        $types = "i";

        if ($teacherId) {
            $sql .= " AND teacher_id = ?";
            $params[] = $teacherId;
            $types .= "i";
        }
        if ($sectionId > 0) {
            $sql .= " AND section_id = ?";   // ← ADD THIS
            $params[] = $sectionId;
            $types .= "i";
        }
        $sql .= " ORDER BY posted_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * getMaterialsByModule is kept for backward compatibility but now
     * simply returns the file columns from the modules row itself.
     */
    public function getMaterialsByModule($moduleId)
    {
        $stmt = $this->db->prepare("
            SELECT id, file_name, file_type, file_size, file_path
            FROM modules WHERE id = ?
        ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        // Return as an array of one item (or empty) to keep view code compatible
        if ($row && $row['file_name']) {
            return [$row];
        }
        return [];
    }

    public function getInteractiveModulesWithCount($subjectId)
    {
        $sql = "
        SELECT im.id, im.title, im.description, im.created_at,
               COUNT(l.id) AS lesson_count
        FROM interactive_modules im
        LEFT JOIN lessons l ON l.interactive_module_id = im.id
        WHERE im.subject_id = ?
        GROUP BY im.id ORDER BY im.created_at ASC
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSectionsByTeacherSubject($subject_id, $teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT ta.join_code, sec.section_name, gl.name AS grade_name
        FROM teacher_assignments ta
        JOIN sections sec ON sec.id = ta.section_id
        JOIN grade_level gl ON gl.id = ta.grade_level_id
        WHERE ta.subject_id = ? AND ta.teacher_id = ?
        ORDER BY gl.name, sec.section_name
    ");
        $stmt->bind_param("ii", $subject_id, $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonsByModule($interactiveModuleId)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, topic FROM lessons
        WHERE interactive_module_id = ? ORDER BY id ASC
    ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStudentCount($subjectId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM student_enrollments WHERE subject_id = ?");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getModuleByTitle($subjectId, $title)
    {
        $stmt = $this->db->prepare("SELECT id FROM modules WHERE subject_id = ? AND title = ? LIMIT 1");
        $stmt->bind_param("is", $subjectId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function insertModule(
        $subjectId,
        $title,
        $description,
        $teacherId,
        $fileName = null,
        $filePath = null,
        $fileType = null,
        $fileSize = null
    ) {
        $existingId = $this->getModuleByTitle($subjectId, $title);
        if ($existingId)
            return ['id' => $existingId, 'existed' => true];

        $fName = $fileName ?? null;
        $fPath = $filePath ?? null;
        $fType = $fileType ?? null;
        $fSize = (int) ($fileSize ?? 0);

        $stmt = $this->db->prepare("
    INSERT INTO modules (subject_id, teacher_id, title, description, posted_at,
                         file_name, file_path, file_type, file_size)
    VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?)
");
        $stmt->bind_param(
            "iisssssi",
            $subjectId,
            $teacherId,
            $title,
            $description,
            $fName,
            $fPath,
            $fType,
            $fSize
        );
        $stmt->execute();
        return ['id' => $this->db->insert_id, 'existed' => false];
    }

    public function getInteractiveModuleByTitle($subjectId, $title)
    {
        $stmt = $this->db->prepare("SELECT id FROM interactive_modules WHERE subject_id = ? AND title = ? LIMIT 1");
        $stmt->bind_param("is", $subjectId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    public function insertInteractiveModule($subjectId, $title, $description, $sortOrder, $teacherId = null)
    {
        $existingId = $this->getInteractiveModuleByTitle($subjectId, $title);
        if ($existingId)
            return ['id' => $existingId, 'existed' => true];
        $stmt = $this->db->prepare("
            INSERT INTO interactive_modules (subject_id, teacher_id, title, description)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("iiss", $subjectId, $teacherId, $title, $description);
        $stmt->execute();
        return ['id' => $this->db->insert_id, 'existed' => false];
    }

    public function getLessonByTitle($interactiveModuleId, $title)
    {
        $stmt = $this->db->prepare("SELECT id FROM lessons WHERE interactive_module_id = ? AND title = ? LIMIT 1");
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
        $stmt = $this->db->prepare("INSERT INTO lessons (interactive_module_id, title, topic, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $interactiveModuleId, $title, $topic, $content);
        $stmt->execute();
        return ['id' => $this->db->insert_id, 'existed' => false];
    }


    // In Teacher.php — add this method
    public function insertInteractiveContent($lessonId, $type, $data = [])
    {
        // Assign all values to variables first — bind_param() requires references
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
        INSERT INTO interactive_contents (
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

    // Total submitted assignments across all teacher's subjects
    public function getTotalSubmittedAssignments($teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(asub.id) AS total
        FROM assignment_submissions asub
        JOIN assignments a ON a.id = asub.assignment_id
        WHERE a.teacher_id = ?
    ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    // All unique enrolled students across teacher's classes
    public function getEnrolledStudentsByTeacher($teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT DISTINCT
            u.name,
            u.email,
            gl.name AS grade_level,
            sec.section_name
        FROM teacher_assignments ta
        JOIN student_enrollments se
            ON se.subject_id = ta.subject_id AND se.section_id = ta.section_id
        JOIN students st ON st.id = se.student_id
        JOIN users u ON u.id = st.user_id
        JOIN grade_level gl ON gl.id = ta.grade_level_id
        JOIN sections sec ON sec.id = ta.section_id
        WHERE ta.teacher_id = ?
        ORDER BY gl.name, sec.section_name, u.name
    ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // All submissions for this teacher's assignments
    public function getSubmittedAssignmentsByTeacher($teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT
            a.title AS assignment_title,
            s.subject_name,
            u.name AS student_name,
            gl.name AS grade_level,
            sec.section_name,
            asub.submitted_at,
            asub.status
        FROM assignment_submissions asub
        JOIN assignments a ON a.id = asub.assignment_id
        JOIN subjects s ON s.id = a.subject_id
        JOIN students st ON st.id = asub.student_id
        JOIN users u ON u.id = st.user_id
        JOIN sections sec ON sec.id = st.section_id
        JOIN grade_level gl ON gl.id = st.grade_level_id
        WHERE a.teacher_id = ?
        ORDER BY asub.submitted_at DESC
    ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAssignmentById($assignment_id)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, description, due_date, due_time, points, 
               file_name, file_path, file_type
        FROM assignments 
        WHERE id = ?
        LIMIT 1
    ");
        $stmt->bind_param("i", $assignment_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function saveGrade($submission_id, $points_earned, $feedback)
    {
        $stmt = $this->db->prepare("
        UPDATE assignment_submissions 
        SET points_earned = ?, feedback = ?, graded_at = NOW()
        WHERE id = ?
    ");
        $stmt->bind_param("isi", $points_earned, $feedback, $submission_id);
        $stmt->execute();
    }

    public function getAllGradeLevels()
    {
        $result = $this->db->query("SELECT id, name FROM grade_level ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllSubjectsWithGrade()
    {
        $result = $this->db->query("
        SELECT s.id, s.subject_name, s.subject_description,
               s.subject_code, s.subject_image, s.grade_level_id,
               gl.name AS grade_name
        FROM subjects s
        LEFT JOIN grade_level gl ON gl.id = s.grade_level_id
        ORDER BY gl.name ASC, s.subject_name ASC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSubjectsByGradeLevel($gradeLevelId)
    {
        $stmt = $this->db->prepare("
        SELECT s.id, s.subject_name, s.subject_description,
               s.subject_code, s.subject_image, s.grade_level_id,
               gl.name AS grade_name
        FROM subjects s
        LEFT JOIN grade_level gl ON gl.id = s.grade_level_id
        WHERE s.grade_level_id = ?
        ORDER BY s.subject_name ASC
    ");
        $stmt->bind_param("i", $gradeLevelId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSubjectWithGrade($id)
    {
        $stmt = $this->db->prepare("
        SELECT s.id, s.subject_name, s.subject_description,
               s.subject_code, s.subject_image, s.grade_level_id,
               gl.name AS grade_name
        FROM subjects s
        LEFT JOIN grade_level gl ON gl.id = s.grade_level_id
        WHERE s.id = ?
        LIMIT 1
    ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getModuleById($moduleId)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, description, created_at 
        FROM interactive_modules 
        WHERE id = ? 
        LIMIT 1
    ");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLessonById($lessonId)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, topic, content 
        FROM lessons 
        WHERE id = ? 
        LIMIT 1
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLessonImages($lessonId)
    {
        $stmt = $this->db->prepare("
        SELECT title, file_path, file_name, file_type 
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
        SELECT title, file_path, file_type 
        FROM interactive_contents 
        WHERE lesson_id = ? AND type = 'video'
        ORDER BY id ASC
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonFlashcards($lessonId)
    {
        $stmt = $this->db->prepare("
        SELECT card_front, card_back, card_type 
        FROM interactive_contents 
        WHERE lesson_id = ? AND type = 'flashcard'
        ORDER BY id ASC
    ");
        $stmt->bind_param("i", $lessonId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getLessonActivityData($lessonId, $studentId = 0)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, instructions, total_points,
               question, question_type,
               choice_a, choice_b, choice_c, choice_d,
               correct_ans, model_answer
        FROM interactive_contents
        WHERE lesson_id = ? AND type = 'activity'
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
                    'activity' => [
                        'title' => $row['title'],
                        'instructions' => $row['instructions'],
                        'total_points' => $row['total_points'],
                    ],
                    'questions' => []
                ];
            }
            $grouped[$key]['questions'][] = $row;
        }
        return $grouped;
    }

    public function getLessonQuizData($lessonId, $studentId = 0)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, instructions, passing_score,
               question, question_type,
               choice_a, choice_b, choice_c, choice_d,
               correct_ans
        FROM interactive_contents
        WHERE lesson_id = ? AND type = 'quiz'
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
                    'quiz' => [
                        'title' => $row['title'],
                        'instructions' => $row['instructions'],
                        'passing_score' => $row['passing_score'],
                    ],
                    'questions' => []
                ];
            }
            $grouped[$key]['questions'][] = $row;
        }
        return $grouped;
    }

    public function countDistinctStudentsByTeacher($teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT se.student_id) AS total
        FROM student_enrollments se
        WHERE se.subject_id IN (
            SELECT DISTINCT subject_id 
            FROM teacher_assignments 
            WHERE teacher_id = ?
        )
    ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getStudentById($student_id)
    {
        $stmt = $this->db->prepare("
        SELECT s.id AS student_id, s.student_LRN, u.id AS user_id,
               u.name, u.email, u.status,
               gl.id AS grade_level_id, gl.name AS grade_level,
               sec.id AS section_id, sec.section_name
        FROM students s
        JOIN users u ON s.user_id = u.id
        JOIN grade_level gl ON s.grade_level_id = gl.id
        JOIN sections sec ON s.section_id = sec.id
        WHERE s.id = ?
        LIMIT 1
    ");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStudent($user_id, $name, $email, $status, $grade_level_id, $section_id, $student_LRN, $student_id, $decline_reason = '', $approved_by = null)
    {
        // Update name and email in users table
        $stmt = $this->db->prepare("
        UPDATE users SET name = ?, email = ? WHERE id = ?
    ");
        $stmt->bind_param("ssi", $name, $email, $user_id);
        $stmt->execute();
        $stmt->close();

        // Use different queries depending on whether approved_by is provided
        if ($approved_by !== null) {
            $approvedBy = (int) $approved_by;
            $stmt2 = $this->db->prepare("
            UPDATE students 
            SET grade_level_id = ?, section_id = ?, student_LRN = ?, 
                status = ?, reason = ?, approved_by = ?, updated_at = NOW()
            WHERE id = ?
        ");
            $stmt2->bind_param(
                "iisssii",
                $grade_level_id,
                $section_id,
                $student_LRN,
                $status,
                $decline_reason,
                $approvedBy,
                $student_id
            );
        } else {
            $stmt2 = $this->db->prepare("
            UPDATE students 
            SET grade_level_id = ?, section_id = ?, student_LRN = ?, 
                status = ?, reason = ?, updated_at = NOW()
            WHERE id = ?
        ");
            $stmt2->bind_param(
                "iisssi",
                $grade_level_id,
                $section_id,
                $student_LRN,
                $status,
                $decline_reason,
                $student_id
            );
        }
        $stmt2->execute();
        $stmt2->close();
    }

    public function getAllSections()
    {
        $result = $this->db->query("
        SELECT sec.id, sec.section_name, gl.id AS grade_level_id, gl.name AS grade_name
        FROM sections sec
        JOIN grade_level gl ON gl.id = sec.grade_level_id
        ORDER BY gl.name ASC, sec.section_name ASC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
// ENROLLMENT INVITATIONS
// ============================================================

    public function getApprovedStudentsNotEnrolled(int $subjectId, int $sectionId): array
    {
        $stmt = $this->db->prepare("
        SELECT u.email, u.name
        FROM users u
        JOIN students s ON s.user_id = u.id
        WHERE u.role = 'student'
          AND s.status = 'Approved'
          AND s.id NOT IN (
              SELECT se.student_id
              FROM student_enrollments se
              WHERE se.subject_id = ? AND se.section_id = ?
          )
        ORDER BY u.name ASC
    ");
        $stmt->bind_param("ii", $subjectId, $sectionId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getApprovedStudentByEmail(string $email)
    {
        $stmt = $this->db->prepare("
        SELECT s.id AS student_id, u.id AS user_id, u.name, u.email, s.status,
               s.grade_level_id, s.section_id
        FROM students s
        JOIN users u ON u.id = s.user_id
        WHERE u.email = ? AND u.role = 'student' AND s.status = 'Approved'
        LIMIT 1
    ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function isAlreadyEnrolled(int $studentId, int $subjectId, int $sectionId): bool
    {
        $stmt = $this->db->prepare("
        SELECT id FROM student_enrollments
        WHERE student_id = ? AND subject_id = ? AND section_id = ?
        LIMIT 1
    ");
        $stmt->bind_param("iii", $studentId, $subjectId, $sectionId);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }

    public function hasPendingInvitation(string $email, int $subjectId, int $sectionId): bool
    {
        $stmt = $this->db->prepare("
        SELECT id FROM enrollment_invitations
        WHERE student_email = ? AND subject_id = ? AND section_id = ?
          AND status = 'pending' 
          AND expires_at > NOW()
        LIMIT 1
    ");
        $stmt->bind_param("sii", $email, $subjectId, $sectionId);
        $stmt->execute();
        return (bool) $stmt->get_result()->fetch_assoc();
    }

    public function createInvitation(
        int $teacherId,
        int $subjectId,
        int $gradeLevelId,
        int $sectionId,
        string $studentEmail,
        ?int $studentId
    ): string {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 days'));

        $stmt = $this->db->prepare("
        INSERT INTO enrollment_invitations
            (token, teacher_id, subject_id, grade_level_id, section_id,
             student_email, student_id, status, created_at, expires_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), ?)
    ");
        $stmt->bind_param(
            "siiissss",
            $token,
            $teacherId,
            $subjectId,
            $gradeLevelId,
            $sectionId,
            $studentEmail,
            $studentId,
            $expiresAt
        );
        $stmt->execute();
        return $token;
    }

    public function getInvitationByToken(string $token): ?array
    {
        $stmt = $this->db->prepare("
        SELECT ei.*,
               s.subject_name,
               gl.name AS grade_name,
               sec.section_name,
               t.user_id AS teacher_user_id,
               u.name AS teacher_name
        FROM enrollment_invitations ei
        JOIN subjects s     ON s.id  = ei.subject_id
        JOIN grade_level gl  ON gl.id = ei.grade_level_id
        JOIN sections sec    ON sec.id = ei.section_id
        JOIN teachers t      ON t.id  = ei.teacher_id
        JOIN users u         ON u.id  = t.user_id
        WHERE ei.token = ?
          AND ei.status = 'pending'
          AND (ei.expires_at IS NULL OR ei.expires_at > NOW())
        LIMIT 1
    ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function acceptInvitation(string $token): array
    {
        $inv = $this->getInvitationByToken($token);
        if (!$inv) {
            return ['success' => false, 'message' => 'Invitation is invalid or has expired.'];
        }

        // Resolve student_id from email if not stored
        $studentId = $inv['student_id'];
        if (!$studentId) {
            $stu = $this->getApprovedStudentByEmail($inv['student_email']);
            if (!$stu) {
                return ['success' => false, 'message' => 'Student account not found or not yet approved.'];
            }
            $studentId = $stu['student_id'];
        }

        if ($this->isAlreadyEnrolled($studentId, $inv['subject_id'], $inv['section_id'])) {
            // Mark token used anyway
            $this->markInvitationAccepted($token, $studentId);
            return ['success' => true, 'message' => 'You are already enrolled in this subject.', 'already' => true];
        }

        // Enroll the student
        $stmt = $this->db->prepare("
        INSERT INTO student_enrollments (student_id, subject_id, section_id, enrolled_at)
        VALUES (?, ?, ?, NOW())
    ");
        $stmt->bind_param("iii", $studentId, $inv['subject_id'], $inv['section_id']);
        $stmt->execute();

        $this->markInvitationAccepted($token, $studentId);

        return [
            'success' => true,
            'message' => 'You have been successfully enrolled!',
            'subject_name' => $inv['subject_name'],
            'section_name' => $inv['section_name'],
            'teacher_name' => $inv['teacher_name'],
        ];
    }

    private function markInvitationAccepted(string $token, int $studentId): void
    {
        $stmt = $this->db->prepare("
        UPDATE enrollment_invitations
        SET status = 'accepted', student_id = ?
        WHERE token = ?
    ");
        $stmt->bind_param("is", $studentId, $token);
        $stmt->execute();
    }

    public function getClassInfoForInviteModal(int $teacherId, int $subjectId, int $sectionId): ?array
    {
        $stmt = $this->db->prepare("
        SELECT s.subject_name, gl.name AS grade_name, sec.section_name,
               ta.grade_level_id
        FROM teacher_assignments ta
        JOIN subjects s     ON s.id  = ta.subject_id
        JOIN grade_level gl  ON gl.id = ta.grade_level_id
        JOIN sections sec    ON sec.id = ta.section_id
        WHERE ta.teacher_id = ? AND ta.subject_id = ? AND ta.section_id = ?
        LIMIT 1
    ");
        $stmt->bind_param("iii", $teacherId, $subjectId, $sectionId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function getAllApprovedStudents(): array
    {
        $stmt = $this->db->prepare("
        SELECT u.email, u.name
        FROM users u
        JOIN students s ON s.user_id = u.id
        WHERE u.role = 'student' AND s.status = 'Approved'
        ORDER BY u.name ASC
    ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Assignments with upcoming due dates across teacher's classes
    public function getUpcomingAssignments($teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT DISTINCT
            a.id,
            a.title,
            a.due_date,
            a.due_time,
            a.type,
            a.points,
            s.subject_name,
            sec.section_name,
            gl.name AS grade_level
        FROM assignments a
        JOIN subjects s ON s.id = a.subject_id
        -- Join teacher_assignments to get the correct section for this teacher
        JOIN teacher_assignments ta
            ON ta.subject_id  = a.subject_id
            AND ta.teacher_id = a.teacher_id
            AND (a.section_id = 0 OR a.section_id = ta.section_id)
        JOIN sections sec ON sec.id = ta.section_id
        JOIN grade_level gl ON gl.id = sec.grade_level_id
        WHERE a.teacher_id = ?
          AND a.due_date IS NOT NULL
          -- Not yet overdue: future date, OR same day but time hasn't passed yet
          AND (
              a.due_date > CURDATE()
              OR (
                  a.due_date = CURDATE()
                  AND COALESCE(a.due_time, '23:59:00') >= CURTIME()
              )
          )
          -- Within 5 days from now
          AND a.due_date <= DATE_ADD(CURDATE(), INTERVAL 5 DAY)
        ORDER BY a.due_date ASC, a.due_time ASC
        LIMIT 10
    ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // All announcements across teacher's subjects
    public function getAnnouncementsByTeacher($teacher_id)
    {
        $stmt = $this->db->prepare("
        SELECT n.title, n.message, n.created_at,
               s.subject_name,
               sec.section_name,
               u.name AS teacher_name
        FROM notifications n
        JOIN subjects s ON s.id = n.subject_id
        JOIN teacher_assignments ta 
            ON ta.subject_id = n.subject_id AND ta.teacher_id = ?
        JOIN sections sec ON sec.id = ta.section_id
        JOIN users u ON u.id = n.sender_id
        WHERE n.type = 'announcement'
        ORDER BY n.created_at DESC
        LIMIT 10
    ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getInvitationStatus(string $email, int $subjectId, int $sectionId): ?array
    {
        // Auto-expire pending invites past their expires_at
        $this->db->query(
            "UPDATE enrollment_invitations
         SET status = 'expired'
         WHERE status = 'pending'
           AND expires_at IS NOT NULL
           AND expires_at < NOW()"
        );

        $stmt = $this->db->prepare(
            "SELECT status, expires_at
         FROM enrollment_invitations
         WHERE student_email = ?
           AND subject_id    = ?
           AND section_id    = ?
         ORDER BY created_at DESC
         LIMIT 1"
        );
        // MySQLi style — bind_param instead of execute([...])
        $stmt->bind_param("sii", $email, $subjectId, $sectionId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result ?: null;
    }

    // ============================================================
// CF MODULE — maps to the existing `modules` table
// ============================================================
    public function insertCFModule($subjectId, $sectionId, $teacherId, $title, $description)
    {
        $stmt = $this->db->prepare("
        SELECT id FROM modules 
        WHERE subject_id = ? AND section_id = ? AND teacher_id = ? AND title = ? 
        LIMIT 1
    ");
        $stmt->bind_param("iiis", $subjectId, $sectionId, $teacherId, $title);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc())
            return null;

        $stmt = $this->db->prepare("
        INSERT INTO modules (subject_id, section_id, teacher_id, title, description, posted_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param("iiiss", $subjectId, $sectionId, $teacherId, $title, $description);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function insertModuleMaterial($moduleId, $originalName, $filePath, $fileType, $fileSize)
    {
        // Since modules table stores ONE file per row inline,
        // we update the module row with the first file,
        // or insert a duplicate row for additional files.

        // Check if this module already has a file
        $stmt = $this->db->prepare("SELECT file_name FROM modules WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (empty($row['file_name'])) {
            // No file yet — update the existing row
            $stmt = $this->db->prepare("
            UPDATE modules 
            SET file_name = ?, file_path = ?, file_type = ?, file_size = ?
            WHERE id = ?
        ");
            $stmt->bind_param("sssii", $originalName, $filePath, $fileType, $fileSize, $moduleId);
            $stmt->execute();
        } else {
            // Already has a file — get this module's info and insert a new row
            $stmt = $this->db->prepare("
            SELECT subject_id, teacher_id, title, description FROM modules WHERE id = ?
        ");
            $stmt->bind_param("i", $moduleId);
            $stmt->execute();
            $mod = $stmt->get_result()->fetch_assoc();

            $stmt = $this->db->prepare("
            INSERT INTO modules (subject_id, teacher_id, title, description, 
                                 file_name, file_path, file_type, file_size, posted_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
            $stmt->bind_param(
                "iissssi",
                $mod['subject_id'],
                $mod['teacher_id'],
                $mod['title'],
                $mod['description'],
                $originalName,
                $filePath,
                $fileType,
                $fileSize
            );
            $stmt->execute();
        }
        return $this->db->insert_id;
    }

    public function updateTeacherStatus(int $teacher_id, string $status): void
    {
        $stmt = $this->db->prepare("
        UPDATE teacher_assignments SET Status = ? WHERE teacher_id = ?
    ");
        $stmt->bind_param("si", $status, $teacher_id);
        $stmt->execute();
        $stmt->close();
    }

    public function assignPairs(int $teacher_id, array $pairs): void
    {
        // $pairs = [ section_id => [subject_id, subject_id, ...], ... ]
        foreach ($pairs as $section_id => $subject_ids) {
            $section_id = (int) $section_id;

            // Get grade_level_id for this section
            $stmt = $this->db->prepare("SELECT grade_level_id FROM sections WHERE id = ?");
            $stmt->bind_param("i", $section_id);
            $stmt->execute();
            $sec = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$sec)
                continue;
            $grade_level_id = (int) $sec['grade_level_id'];

            foreach ($subject_ids as $subject_id) {
                $subject_id = (int) $subject_id;

                // Check if already exists
                $exist = $this->db->prepare("
                SELECT id, join_code FROM teacher_assignments
                WHERE teacher_id = ? AND subject_id = ? AND section_id = ?
            ");
                $exist->bind_param("iii", $teacher_id, $subject_id, $section_id);
                $exist->execute();
                $existing = $exist->get_result()->fetch_assoc();
                $exist->close();

                if ($existing) {
                    // Fill missing join code if needed
                    if (empty($existing['join_code'])) {
                        $code = $this->generateJoinCode(7);
                        $upd = $this->db->prepare("UPDATE teacher_assignments SET join_code = ? WHERE id = ?");
                        $upd->bind_param("si", $code, $existing['id']);
                        $upd->execute();
                        $upd->close();
                    }
                } else {
                    $join_code = $this->generateJoinCode(7);
                    $insert = $this->db->prepare("
                    INSERT INTO teacher_assignments 
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

    public function getAllStudentsFiltered($limit, $offset, $search = '', $grade = '', $section = '', $status = '')
    {
        $where = ["u.role = 'student'"];
        $params = [];
        $types = '';

        if ($search) {
            $where[] = "(u.name LIKE ? OR u.email LIKE ? OR s.student_LRN LIKE ?)";
            $like = "%$search%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $types .= 'sss';  // ← was 'isss', remove the 'i'
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
        if ($status) {
            $where[] = "LOWER(s.status) = ?";
            $params[] = strtolower($status);
            $types .= 's';
        }

        $whereClause = implode(' AND ', $where);
        $sql = "
            SELECT s.id AS student_id, s.user_id, s.grade_level_id, s.section_id,
            s.student_LRN, s.status, s.reason, u.name, u.email,  
            u.id AS id,
            gl.name AS grade_level, sec.section_name
            FROM students s
            JOIN users u ON s.user_id = u.id
            JOIN grade_level gl ON s.grade_level_id = gl.id
            JOIN sections sec ON s.section_id = sec.id
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

    public function countAllStudentsFiltered($search = '', $grade = '', $section = '', $status = '')
    {
        $where = ["u.role = 'student'"];
        $params = [];
        $types = '';

        if ($search) {
            $where[] = "(u.name LIKE ? OR u.email LIKE ?)";
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
        if ($status) {
            $where[] = "LOWER(s.status) = ?";
            $params[] = strtolower($status);
            $types .= 's';
        }

        $whereClause = implode(' AND ', $where);
        $sql = "
        SELECT COUNT(*) AS total
        FROM students s
        JOIN users u ON s.user_id = u.id
        JOIN grade_level gl ON s.grade_level_id = gl.id
        JOIN sections sec ON s.section_id = sec.id
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

    public function countStudentsByStatus(string $status): int
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total
        FROM students s
        JOIN users u ON s.user_id = u.id
        WHERE u.role = 'student' AND LOWER(s.status) = LOWER(?)
    ");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getAllTeachersFilteredPaginated(
        string $search = '',
        string $grade = '',
        string $section = '',
        string $status = '',
        int $limit = 10,
        int $offset = 0
    ): array {
        // Reuse the same inner query as getAllTeachersFiltered
        $innerSQL = "
            SELECT
                t.id   AS teacher_id,
                u.name,
                u.email,
                COUNT(DISTINCT ta.id) AS class_count,
                MAX(ta.Status) AS status_raw,
                GROUP_CONCAT(
                    DISTINCT CONCAT(s.id, '~~', s.subject_name)
                    ORDER BY s.subject_name SEPARATOR '||'
                ) AS subjects_raw,
                GROUP_CONCAT(
                    DISTINCT CONCAT(gl.name, ' - ', sec.section_name)
                    ORDER BY gl.name, sec.section_name SEPARATOR '||'
                ) AS sections_raw,
                GROUP_CONCAT(
                    DISTINCT LOWER(gl.name)
                    ORDER BY gl.name SEPARATOR '|'
                ) AS grades_raw
            FROM teachers t
            JOIN  users u  ON t.user_id = u.id
            LEFT JOIN teacher_assignments ta ON ta.teacher_id = t.id
            LEFT JOIN subjects s             ON ta.subject_id   = s.id
            LEFT JOIN sections sec           ON ta.section_id   = sec.id
            LEFT JOIN grade_level gl         ON ta.grade_level_id = gl.id
            WHERE u.role = 'teacher'
            GROUP BY t.id, u.name, u.email
        ";

        $outerWhere = [];
        $params = [];
        $types = '';

        if ($search !== '') {
            $like = '%' . $search . '%';
            $outerWhere[] = "(name LIKE ? OR email LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $types .= 'ss';
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

        // Status filter is post-query (derived), so we fetch all and filter in PHP
        // But we still need LIMIT/OFFSET — apply after status filtering via PHP slice
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

            unset($row['subjects_raw'], $row['sections_raw'], $row['grades_raw']);

            $all[] = $row;
        }

        return array_slice($all, $offset, $limit);
    }

    public function countAllTeachersFiltered(
        string $search = '',
        string $grade = '',
        string $section = '',
        string $status = ''
    ): int {
        // Same logic as getAllTeachersFiltered but only counts
        $innerSQL = "
        SELECT
            t.id AS teacher_id,
            u.name,
            u.email,
            COUNT(DISTINCT ta.id) AS class_count,
            GROUP_CONCAT(
                DISTINCT LOWER(gl.name)
                ORDER BY gl.name SEPARATOR '|'
            ) AS grades_raw,
            GROUP_CONCAT(
                DISTINCT CONCAT(gl.name, ' - ', sec.section_name)
                ORDER BY gl.name, sec.section_name SEPARATOR '||'
            ) AS sections_raw
        FROM teachers t
        JOIN  users u  ON t.user_id = u.id
        LEFT JOIN teacher_assignments ta ON ta.teacher_id = t.id
        LEFT JOIN subjects s             ON ta.subject_id   = s.id
        LEFT JOIN sections sec           ON ta.section_id   = sec.id
        LEFT JOIN grade_level gl         ON ta.grade_level_id = gl.id
        WHERE u.role = 'teacher'
        GROUP BY t.id, u.name, u.email
    ";

        $outerWhere = [];
        $params = [];
        $types = '';

        if ($search !== '') {
            $like = '%' . $search . '%';
            $outerWhere[] = "(name LIKE ? OR email LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $types .= 'ss';
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

        // Count in PHP to apply status filter (derived field)
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

    public function bulkApproveStudents(array $studentIds): void
    {
        if (empty($studentIds))
            return;

        $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
        $types = str_repeat('i', count($studentIds));

        $stmt = $this->db->prepare("
        UPDATE students SET status = 'Approved' WHERE id IN ($placeholders)
    ");
        $stmt->bind_param($types, ...$studentIds);
        $stmt->execute();
        $stmt->close();
    }

    public function updateAssignmentDueDate($assignmentId, $dueDate, $dueTime)
    {
        $stmt = $this->db->prepare("
        UPDATE assignments SET due_date = ?, due_time = ? WHERE id = ?
    ");
        $stmt->bind_param("ssi", $dueDate, $dueTime, $assignmentId);
        return $stmt->execute();
    }

    public function getAllPendingStudentIds(): array
    {
        $stmt = $this->db->prepare("SELECT id FROM students WHERE status = 'Pending'");
        $stmt->execute();
        $result = $stmt->get_result();
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) $row['id'];
        }
        return $ids;
    }
}