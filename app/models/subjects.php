<?php

require_once "../core/Model.php";

class subjects extends Model
{

    public function getSectionForSubject($student_id, $subject_id)
    {
        $sql = "
        SELECT ta.section_id 
        FROM teacher_assignments ta
        JOIN students s ON s.section_id = ta.section_id
        WHERE s.id = ? 
        AND ta.subject_id = ?
        AND ta.grade_level_id = (
            SELECT grade_level_id FROM subjects WHERE id = ?
        )
        LIMIT 1
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $student_id, $subject_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['section_id'] : null;
    }

    public function getGrade11Subjects()
    {
        return $this->getSubjectsByGradeLevel(1);
    }

    public function getGrade12Subjects()
    {
        return $this->getSubjectsByGradeLevel(2);
    }

    public function getStudentByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE user_id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getSubjectsByGradeLevel($grade_level_id)
    {
        $sql = "SELECT * FROM subjects WHERE grade_level_id = ? ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $grade_level_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function isEnrolled($student_id, $subject_id)
    {
        $sql = "SELECT id FROM student_enrollments WHERE student_id = ? AND subject_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $student_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function enrollStudent($student_id, $subject_id, $section_id)
    {
        $sql = "INSERT INTO student_enrollments (student_id, subject_id, section_id, enrolled_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $student_id, $subject_id, $section_id);
        return $stmt->execute();
    }

    public function getEnrolledSubjectIds($student_id)
    {
        $sql = "SELECT subject_id FROM student_enrollments WHERE student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map('intval', array_column($rows, 'subject_id')); // ← ensure integers
    }

    public function insertSubject($subject_name, $grade_level_id)
    {
        $stmt = $this->db->prepare("INSERT INTO subjects (subject_name, grade_level_id) VALUES (?, ?)");
        $stmt->bind_param("si", $subject_name, $grade_level_id);
        $stmt->execute();
        return $this->db->insert_id;
    }

    /**
     * Look up a subject by its join code.
     * Returns the subject row (with id, subject_name, subject_code) or null.
     */
    public function getSubjectByCode($code)
    {
        $sql = "
        SELECT s.id, s.subject_name, s.subject_code, 
               ta.section_id, ta.grade_level_id, ta.join_code
        FROM teacher_assignments ta
        JOIN subjects s ON s.id = ta.subject_id
        WHERE ta.join_code = ?
        LIMIT 1
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }
}