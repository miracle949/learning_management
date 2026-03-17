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
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTeacherStats($teacher_id)
    {
        $sql = "
            SELECT
                COUNT(DISTINCT ta.subject_id) AS total_classes,
                0 AS total_modules
            FROM teacher_assignments ta
            WHERE ta.teacher_id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getStudentCountPerClass($teacher_id)
    {
        $sql = "
            SELECT 
                ta.subject_id,
                ta.section_id,
                COUNT(se.student_id) AS total_students
            FROM teacher_assignments ta
            LEFT JOIN student_enrollments se 
                ON se.subject_id = ta.subject_id 
                AND se.section_id = ta.section_id
            WHERE ta.teacher_id = ?
            GROUP BY ta.subject_id, ta.section_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecentStudents($limit = 5)
    {
        $sql = "
            SELECT 
                u.name,
                u.email,
                gl.name AS grade_level_name,
                sec.section_name
            FROM students s
            JOIN users u ON s.user_id = u.id
            JOIN grade_level gl ON s.grade_level_id = gl.id
            JOIN sections sec ON s.section_id = sec.id
            WHERE u.role = 'student' AND u.status = '1'
            ORDER BY s.id DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        return $students;
    }

    public function createTeacher($name, $email, $password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'teacher', '1')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed);
        $stmt->execute();
        $user_id = $this->db->insert_id;

        $sql2 = "INSERT INTO teachers (user_id) VALUES (?)";
        $stmt2 = $this->db->prepare($sql2);
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
                $sectionCheck = $this->db->prepare("SELECT id FROM sections WHERE id = ? AND grade_level_id = ?");
                $sectionCheck->bind_param("ii", $section_id, $grade_level_id);
                $sectionCheck->execute();
                $match = $sectionCheck->get_result()->fetch_assoc();

                if ($match) {
                    $insert = $this->db->prepare(
                        "INSERT INTO teacher_assignments (teacher_id, subject_id, grade_level_id, section_id) 
                         VALUES (?, ?, ?, ?)"
                    );
                    $insert->bind_param("iiii", $teacher_id, $subject_id, $grade_level_id, $section_id);
                    $insert->execute();
                }
            }
        }
    }

    public function getAllTeachers()
    {
        $sql = "
            SELECT 
                t.id AS teacher_id,
                u.name,
                u.email,
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
            GROUP BY t.id, u.name, u.email
            ORDER BY u.name ASC
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
}