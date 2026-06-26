<?php

require_once "../core/Model.php";

class User extends Model
{

    public function isLRNTaken($student_lrn)
    {
        $stmt = $this->db->prepare("SELECT id FROM tbl_students WHERE student_LRN = ?");
        $stmt->bind_param("s", $student_lrn);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function isEmailTaken($email)
    {
        $stmt = $this->db->prepare("SELECT id FROM tbl_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function login($email)
    {
        $stmt = $this->db->prepare("
        SELECT u.*, s.status
        FROM tbl_users u
        LEFT JOIN tbl_students s ON s.user_id = u.id
        WHERE u.email = ?
        LIMIT 1
    ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function signup($student_no, $name, $email, $username, $password, $grade_level_id, $section_id)
    {
        $sql = "INSERT INTO tbl_users (name, email, username, password, role) 
            VALUES (?, ?, ?, ?, 'student')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $username, $password);
        $stmt->execute();
        $user_id = $this->db->insert_id;

        $sql2 = "INSERT INTO tbl_students (student_LRN, user_id, grade_level_id, section_id, status) 
         VALUES (?, ?, ?, ?, 'Pending')";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("siii", $student_no, $user_id, $grade_level_id, $section_id);
        $stmt2->execute();
    }

    public function getGrades()
    {
        $result = $this->db->query("SELECT * FROM tbl_grade_level");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getSections()
    {
        $result = $this->db->query("SELECT * FROM tbl_sections");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getName($id)
    {
        $stmt = $this->db->prepare("SELECT name FROM tbl_users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['name'] ?? null;
    }

    public function getStudentInfo($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT g.name AS tbl_grade_level, sec.section_name
            FROM tbl_students s
            JOIN tbl_grade_level g ON s.grade_level_id = g.id
            JOIN tbl_sections sec ON s.section_id = sec.id
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
            FROM tbl_teacher_assignments ta
            JOIN tbl_subjects s    ON ta.subject_id     = s.id
            JOIN tbl_grade_level g ON ta.grade_level_id = g.id
            JOIN tbl_sections sec  ON ta.section_id     = sec.id
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
            FROM tbl_teacher_assignments ta
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
                FROM tbl_subjects s
                JOIN tbl_teacher_assignments ta ON ta.subject_id     = s.id
                JOIN tbl_grade_level g          ON ta.grade_level_id = g.id
                JOIN tbl_sections sec           ON ta.section_id     = sec.id
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
                FROM tbl_subjects s
                LEFT JOIN tbl_teacher_assignments ta ON ta.subject_id     = s.id
                LEFT JOIN tbl_grade_level g          ON ta.grade_level_id = g.id
                LEFT JOIN tbl_sections sec           ON ta.section_id     = sec.id
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
            FROM tbl_users
            WHERE id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getTeacherId($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM tbl_teachers WHERE user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['id'] ?? null;
    }
}