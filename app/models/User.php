<?php

require_once "../core/Model.php";

class User extends Model
{
    public function login($email)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? AND status = '1'"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function signup($student_no, $name, $email, $username, $password, $grade_level_id, $section_id)
    {
        $sql = "INSERT INTO users (name, email, username, password, role, status) 
            VALUES (?, ?, ?, ?, 'student', '1')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $username, $password);
        $stmt->execute();
        $user_id = $this->db->insert_id;

        $sql2 = "INSERT INTO students (student_no, user_id, grade_level_id, section_id) 
             VALUES (?, ?, ?, ?)";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("siii", $student_no, $user_id, $grade_level_id, $section_id);
        $stmt2->execute();
    }

    public function getGrades()
    {
        $result = $this->db->query("SELECT * FROM grade_level");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSections()
    {
        $result = $this->db->query("SELECT * FROM sections");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getName($id)
    {
        $stmt = $this->db->prepare("SELECT name FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['name'] ?? null;
    }

    public function getStudentInfo($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT g.name AS grade_level, sec.section_name
            FROM students s
            JOIN grade_level g ON s.grade_level_id = g.id
            JOIN sections sec ON s.section_id = sec.id
            WHERE s.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // -------------------------------------------------------
    // Teacher methods
    // -------------------------------------------------------

    /**
     * Returns one row per subject + grade level combination.
     * e.g. "Computer System Servicing - Grade 11" and
     *      "Computer System Servicing - Grade 12" as separate class cards.
     * Sections are grouped within each subject+grade.
     */
    public function getAssignedClasses($teacher_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.id          AS subject_id,
                s.subject_name,
                g.id          AS grade_level_id,
                g.name        AS grade_name,
                GROUP_CONCAT(DISTINCT sec.section_name ORDER BY sec.id SEPARATOR ', ') AS sections
            FROM teacher_assignments ta
            JOIN subjects s    ON ta.subject_id     = s.id
            JOIN grade_level g ON ta.grade_level_id = g.id
            JOIN sections sec  ON ta.section_id     = sec.id
            WHERE ta.teacher_id = ?
            GROUP BY s.id, g.id
            ORDER BY s.subject_name, g.id
        ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Counts distinct subject+grade combos as total classes.
     */
    public function getTeacherStats($teacher_id)
    {
        $stmt = $this->db->prepare("
            SELECT
                COUNT(DISTINCT CONCAT(ta.subject_id, '-', ta.grade_level_id)) AS total_classes
            FROM teacher_assignments ta
            WHERE ta.teacher_id = ?
        ");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Fetches subject name + grade + all sections for a subject+grade combo.
     * Pass grade_level_id for a specific grade, or omit for all grades.
     */
    public function getClassInfo($subject_id, $grade_level_id = null)
    {
        if ($grade_level_id) {
            $stmt = $this->db->prepare("
                SELECT 
                    s.subject_name,
                    g.name AS grade,
                    GROUP_CONCAT(DISTINCT sec.section_name ORDER BY sec.id SEPARATOR ', ') AS section
                FROM subjects s
                JOIN teacher_assignments ta ON ta.subject_id     = s.id
                JOIN grade_level g          ON ta.grade_level_id = g.id
                JOIN sections sec           ON ta.section_id     = sec.id
                WHERE s.id = ? AND g.id = ?
                GROUP BY s.id, g.id
            ");
            $stmt->bind_param("ii", $subject_id, $grade_level_id);
        } else {
            $stmt = $this->db->prepare("
                SELECT 
                    s.subject_name,
                    GROUP_CONCAT(DISTINCT g.name ORDER BY g.id SEPARATOR ', ')             AS grade,
                    GROUP_CONCAT(DISTINCT sec.section_name ORDER BY sec.id SEPARATOR ', ') AS section
                FROM subjects s
                LEFT JOIN teacher_assignments ta ON ta.subject_id     = s.id
                LEFT JOIN grade_level g          ON ta.grade_level_id = g.id
                LEFT JOIN sections sec           ON ta.section_id     = sec.id
                WHERE s.id = ?
                GROUP BY s.id
            ");
            $stmt->bind_param("i", $subject_id);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTeacherInfo($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, username
            FROM users
            WHERE id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTeacherId($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM teachers WHERE user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['id'] ?? null;
    }
}