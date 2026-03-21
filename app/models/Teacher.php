<?php
require_once "../core/Model.php";

class Teacher extends Model
{

    // ============================================================
    // EXISTING METHODS — exactly as you wrote them, do not remove
    // ============================================================

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
                s.subject_name,
                gl.name AS grade_name,
                GROUP_CONCAT(DISTINCT sec.section_name ORDER BY sec.section_name SEPARATOR ', ') AS sections,
                COUNT(DISTINCT se.student_id) AS student_count,
                0 AS module_count
            FROM teacher_assignments ta
            JOIN subjects s ON ta.subject_id = s.id
            JOIN grade_level gl ON ta.grade_level_id = gl.id
            JOIN sections sec ON ta.section_id = sec.id
            LEFT JOIN student_enrollments se 
                ON se.subject_id = ta.subject_id 
                AND se.section_id = ta.section_id
            WHERE ta.teacher_id = ?
            GROUP BY ta.subject_id, ta.grade_level_id, s.subject_name, gl.name
            ORDER BY gl.name, s.subject_name
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

    public function assignSubjectsAndSections($teacher_id, $assigned_subjects, $assigned_sections)
    {
        foreach ($assigned_subjects as $subject_id) {
            $stmt = $this->db->prepare("SELECT grade_level_id FROM subjects WHERE id = ?");
            $stmt->bind_param("i", $subject_id);
            $stmt->execute();
            $subject = $stmt->get_result()->fetch_assoc();
            $grade_level_id = $subject['grade_level_id'] ?? null;

            foreach ($assigned_sections as $section_id) {
                $check = $this->db->prepare("SELECT id FROM sections WHERE id = ? AND grade_level_id = ?");
                $check->bind_param("ii", $section_id, $grade_level_id);
                $check->execute();
                if ($check->get_result()->fetch_assoc()) {
                    $insert = $this->db->prepare("INSERT INTO teacher_assignments (teacher_id, subject_id, grade_level_id, section_id) VALUES (?, ?, ?, ?)");
                    $insert->bind_param("iiii", $teacher_id, $subject_id, $grade_level_id, $section_id);
                    $insert->execute();
                }
            }
        }
    }

    public function getAllTeachers()
    {
        $sql = "
            SELECT t.id AS teacher_id, u.name, u.email,
                GROUP_CONCAT(DISTINCT s.subject_name ORDER BY s.subject_name SEPARATOR '||') AS subjects,
                COUNT(DISTINCT s.subject_name) AS class_count,
                GROUP_CONCAT(DISTINCT CONCAT(gl.name, ' - ', sec.section_name) ORDER BY gl.name SEPARATOR '||') AS sections
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
            $row['subjects'] = $row['subjects'] ? explode('||', $row['subjects']) : [];
            $row['sections'] = $row['sections'] ? explode('||', $row['sections']) : [];
            $teachers[] = $row;
        }
        return $teachers;
    }


    // ============================================================
    // CLASSES FEED — modules + module_materials
    // ============================================================

    // Returns existing module id if same title already exists for this subject
    public function getModuleByTitle($subjectId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM modules WHERE subject_id = ? AND title = ? LIMIT 1
        ");
        $stmt->bind_param("is", $subjectId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    // Insert new OR return existing id if same title already exists
    public function insertModule($subjectId, $title, $description, $postedBy, $sortOrder)
    {
        $existingId = $this->getModuleByTitle($subjectId, $title);
        if ($existingId) {
            return $existingId;  // ← do not duplicate
        }

        $stmt = $this->db->prepare("
            INSERT INTO modules (subject_id, title, description, posted_by, posted_at, sort_order)
            VALUES (?, ?, ?, ?, NOW(), ?)
        ");
        $stmt->bind_param("issii", $subjectId, $title, $description, $postedBy, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function insertModuleMaterial($moduleId, $fileName, $filePath, $fileType, $fileSize, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO module_materials (module_id, file_name, file_path, file_type, file_size, sort_order)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isssii", $moduleId, $fileName, $filePath, $fileType, $fileSize, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }


    // ============================================================
    // INTERACTIVE MODULES
    // ============================================================

    // Check if module already exists for this subject — returns existing id if found
    public function getInteractiveModuleByTitle($subjectId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM interactive_modules
            WHERE subject_id = ? AND title = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $subjectId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    // Insert new OR return existing id if same title already exists for this subject
    public function insertInteractiveModule($subjectId, $title, $description, $sortOrder)
    {
        // Check if module with same title already exists for this subject
        $existingId = $this->getInteractiveModuleByTitle($subjectId, $title);
        if ($existingId) {
            return $existingId;  // ← return existing id, do not duplicate
        }

        $stmt = $this->db->prepare("
            INSERT INTO interactive_modules (subject_id, title, description, sort_order)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("issi", $subjectId, $title, $description, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // Same for lessons — check if lesson already exists inside a module
    public function getLessonByTitle($interactiveModuleId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM lessons
            WHERE interactive_module_id = ? AND title = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $interactiveModuleId, $title);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row ? $row['id'] : null;
    }

    // Insert new lesson OR return existing id if same title already exists in module
    public function insertLesson($interactiveModuleId, $title, $topic, $content, $sortOrder)
    {
        // Check if lesson with same title already exists in this module
        $existingId = $this->getLessonByTitle($interactiveModuleId, $title);
        if ($existingId) {
            return $existingId;  // ← return existing id, do not duplicate
        }

        $stmt = $this->db->prepare("
            INSERT INTO lessons (interactive_module_id, title, topic, content, sort_order)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isssi", $interactiveModuleId, $title, $topic, $content, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function insertLessonVideo($lessonId, $title, $videoUrl, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO lesson_videos (lesson_id, title, video_url, sort_order)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("issi", $lessonId, $title, $videoUrl, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function insertLessonImage($lessonId, $imagePath, $caption, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO lesson_images (lesson_id, image_path, caption, sort_order)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("issi", $lessonId, $imagePath, $caption, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // stores BOTH lesson_id (which lesson) + interactive_module_id (which module)
    public function insertActivity($lessonId, $interactiveModuleId, $title, $instructions, $totalPoints, $timeLimit, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO activities (lesson_id, interactive_module_id, title, instructions, total_points, time_limit, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissiii", $lessonId, $interactiveModuleId, $title, $instructions, $totalPoints, $timeLimit, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // ── FIXED: null handling for choices ─────────────────────
    public function insertActivityQuestion(
        $activityId,
        $questionType,
        $question,
        $modelAnswer,
        $choiceA,
        $choiceB,
        $choiceC,
        $choiceD,
        $correctAns,
        $points,
        $sortOrder
    ) {
        $stmt = $this->db->prepare("
            INSERT INTO activity_questions
                (activity_id, question_type, question, model_answer,
                 choice_a, choice_b, choice_c, choice_d, correct_ans,
                 points, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssssssii",
            $activityId,
            $questionType,
            $question,
            $modelAnswer,
            $choiceA,
            $choiceB,
            $choiceC,
            $choiceD,
            $correctAns,
            $points,
            $sortOrder
        );
        $stmt->execute();
        return $this->db->insert_id;
    }

    // stores BOTH lesson_id (which lesson) + interactive_module_id (which module)
    public function insertQuiz($lessonId, $interactiveModuleId, $title, $instructions, $timeLimit, $passingScore, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO quizzes (lesson_id, interactive_module_id, title, instructions, time_limit, passing_score, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissiii", $lessonId, $interactiveModuleId, $title, $instructions, $timeLimit, $passingScore, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function insertQuizQuestion($quizId, $question, $choiceA, $choiceB, $choiceC, $choiceD, $correctAns, $points, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO quiz_questions
                (quiz_id, question, choice_a, choice_b, choice_c, choice_d, correct_ans, points, sort_order)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssssii",
            $quizId,
            $question,
            $choiceA,
            $choiceB,
            $choiceC,
            $choiceD,
            $correctAns,
            $points,
            $sortOrder
        );
        $stmt->execute();
        return $this->db->insert_id;
    }

    // stores BOTH lesson_id (which lesson) + interactive_module_id (which module)
    public function insertFlashcard($lessonId, $interactiveModuleId, $cardType, $front, $back, $sortOrder)
    {
        $stmt = $this->db->prepare("
            INSERT INTO im_flashcards (lesson_id, interactive_module_id, card_type, front, back, sort_order)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iisssi", $lessonId, $interactiveModuleId, $cardType, $front, $back, $sortOrder);
        $stmt->execute();
        return $this->db->insert_id;
    }
}