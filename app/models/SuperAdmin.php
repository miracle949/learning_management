<?php

require_once "../core/Model.php";

class SuperAdmin extends Model
{
    // ============================================================
    // SUBJECTS
    // ============================================================
    public function getAllSubjects()
    {
        $result = $this->db->query("
        SELECT s.id, s.subject_name, s.subject_description, s.subject_code,
               s.subject_image, s.grade_level_id,
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
        SELECT s.id, s.subject_name, s.subject_description, s.subject_code,
               s.subject_image, s.grade_level_id,
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

    public function getSubjectById($id)
    {
        $stmt = $this->db->prepare("
        SELECT s.id, s.subject_name, s.subject_description, s.subject_code,
               s.subject_image, s.grade_level_id,
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

    // ============================================================
    // GRADE LEVELS
    // ============================================================
    public function getAllGradeLevels()
    {
        $result = $this->db->query("SELECT id, name FROM grade_level ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ============================================================
    // INTERACTIVE MODULES (moved from Teacher.php)
    // ============================================================
    public function countInteractiveModules($subjectId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total FROM interactive_modules WHERE subject_id = ?
        ");
        $stmt->bind_param("i", $subjectId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getInteractiveModuleByTitle($subjectId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM interactive_modules
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

        $teacherId = null; // super admin has no teacher record — must be NULL

        $stmt = $this->db->prepare("
        INSERT INTO interactive_modules (subject_id, teacher_id, title, description, created_at)
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
            SELECT COUNT(*) AS total FROM lessons WHERE interactive_module_id = ?
        ");
        $stmt->bind_param("i", $interactiveModuleId);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getLessonByTitle($interactiveModuleId, $title)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM lessons
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
            INSERT INTO lessons (interactive_module_id, title, topic, content)
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

    public function createSubject($name, $code, $description, $gradeLevelId, $imagePath = null)
    {
        $stmt = $this->db->prepare("
        INSERT INTO subjects (subject_name, subject_code, subject_description, grade_level_id, subject_image)
        VALUES (?, ?, ?, ?, ?)
    ");
        $stmt->bind_param("sssis", $name, $code, $description, $gradeLevelId, $imagePath);
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function updateSubject($id, $name, $code, $description, $gradeLevelId, $imagePath = null)
    {
        if ($imagePath) {
            // Update with new image
            $stmt = $this->db->prepare("
            UPDATE subjects
            SET subject_name = ?, subject_code = ?, subject_description = ?,
                grade_level_id = ?, subject_image = ?
            WHERE id = ?
        ");
            $stmt->bind_param("sssisi", $name, $code, $description, $gradeLevelId, $imagePath, $id);
        } else {
            // Keep existing image
            $stmt = $this->db->prepare("
            UPDATE subjects
            SET subject_name = ?, subject_code = ?, subject_description = ?,
                grade_level_id = ?
            WHERE id = ?
        ");
            $stmt->bind_param("sssii", $name, $code, $description, $gradeLevelId, $id);
        }
        $stmt->execute();
    }

}