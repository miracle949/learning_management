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
            SELECT COUNT(*) FROM announcements a
            WHERE a.subject_id = ta.subject_id AND a.teacher_id = ta.teacher_id
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
        $sql = "SELECT COUNT(DISTINCT ta.subject_id) AS total_classes, 0 AS total_modules
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
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'teacher', '1')");
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

    public function getAllTeachers()
    {
        $sql = "
        SELECT t.id AS teacher_id, u.name, u.email,
            GROUP_CONCAT(DISTINCT CONCAT(s.id, '~~', s.subject_name, '~~', COALESCE(ta.join_code, ''))
                ORDER BY s.subject_name SEPARATOR '||') AS subjects,
            COUNT(DISTINCT ta.subject_id) AS class_count,
            GROUP_CONCAT(DISTINCT CONCAT(gl.name, ' - ', sec.section_name)
                ORDER BY gl.name SEPARATOR '||') AS sections
        FROM teachers t
        JOIN users u ON t.user_id = u.id
        LEFT JOIN teacher_assignments ta ON ta.teacher_id = t.id
        LEFT JOIN subjects s ON ta.subject_id = s.id
        LEFT JOIN sections sec ON ta.section_id = sec.id
        LEFT JOIN grade_level gl ON ta.grade_level_id = gl.id
        WHERE u.role = 'teacher' AND u.status = '1'
        GROUP BY t.id, u.name, u.email ORDER BY u.name ASC
        ";

        $result = $this->db->query($sql);
        $teachers = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['subjects']) {
                $pairs = explode('||', $row['subjects']);
                $row['subjects'] = array_map(function ($pair) {
                    $parts = explode('~~', $pair, 3);
                    return [
                        'id' => $parts[0] ?? '',
                        'name' => $parts[1] ?? '',
                        'join_code' => $parts[2] ?? ''
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
    // ANNOUNCEMENTS
    // ============================================================
    public function getAnnouncements($subjectId, $teacherId)
    {
        $stmt = $this->db->prepare("SELECT id, title, body, posted_at FROM announcements WHERE subject_id = ? AND teacher_id = ? ORDER BY posted_at DESC");
        $stmt->bind_param("ii", $subjectId, $teacherId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertAnnouncement($subjectId, $teacherId, $title, $body)
    {
        $stmt = $this->db->prepare("
            INSERT INTO announcements (subject_id, teacher_id, title, body, posted_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("iiss", $subjectId, $teacherId, $title, $body);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // ============================================================
    // ASSIGNMENTS
    // ============================================================
    public function getAssignments($subjectId, $teacherId)
    {
        $stmt = $this->db->prepare("
        SELECT id, title, description, due_date, points, created_at,
               file_name, file_path, file_type
        FROM assignments 
        WHERE subject_id = ? AND teacher_id = ? 
        ORDER BY created_at DESC
    ");
        $stmt->bind_param("ii", $subjectId, $teacherId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertAssignment(
        $subjectId,
        $teacherId,
        $type,
        $title,
        $description,
        $task,
        $instructions,
        $dueDate,
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
        $fName = $fileName ?? null;
        $fPath = $filePath ?? null;
        $fType = $fileType ?? null;

        $stmt = $this->db->prepare("
        INSERT INTO assignments 
            (subject_id, teacher_id, type, title, description, task, instructions, 
             due_date, points, file_name, file_path, file_type, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
        $stmt->bind_param(
            "iissssssisss",
            $subjectId,
            $teacherId,
            $type,
            $title,
            $desc,
            $task,
            $instr,
            $due,
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

    public function getModules($subjectId, $teacherId = null)
    {
        $sql = "
        SELECT id, title, description, posted_at,
               file_name, file_path, file_type, file_size
        FROM modules
        WHERE subject_id = ?
    ";
        $params = [$subjectId];
        $types = "i";
        if ($teacherId) {
            $sql .= " AND teacher_id = ?";  // ← not posted_by
            $params[] = $teacherId;
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

    public function getInteractiveModulesWithCount($subjectId, $teacherId = null)
    {
        $sql = "
            SELECT im.id, im.title, im.description, im.created_at,
                   COUNT(l.id) AS lesson_count
            FROM interactive_modules im
            LEFT JOIN lessons l ON l.interactive_module_id = im.id
            WHERE im.subject_id = ?
        ";
        $params = [$subjectId];
        $types = "i";
        if ($teacherId) {
            $sql .= " AND im.teacher_id = ?";
            $params[] = $teacherId;
            $types .= "i";
        }
        $sql .= " GROUP BY im.id ORDER BY im.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
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
}