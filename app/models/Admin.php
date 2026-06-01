<?php
require_once "../core/Model.php";

class Admin extends Model
{
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

    public function getTotalStudents()
    {
        $result = $this->db->query("
        SELECT COUNT(*) AS total 
        FROM students s 
        JOIN users u ON s.user_id = u.id 
        WHERE u.role = 'student'
    ");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalTeachers()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM teachers t JOIN users u ON t.user_id = u.id WHERE u.role = 'teacher'");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalPendingApprovals()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM students WHERE status = 'Pending'");
        return (int) $result->fetch_assoc()['total'];
    }

    // public function getPendingStudents()
    // {
    //     $result = $this->db->query("
    //     SELECT u.name, s.status 
    //     FROM students s
    //     JOIN users u ON s.user_id = u.id
    //     WHERE s.status = 'Pending'
    // ");
    //     return $result->fetch_all(MYSQLI_ASSOC);
    // }

    public function getTotalSubjects()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM subjects");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getTotalSections()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM sections");
        return (int) $result->fetch_assoc()['total'];
    }

    public function getPendingStudents()
    {
        $result = $this->db->query("
        SELECT u.name, sec.section_name, u.email, s.status,
               gl.name AS grade_level
        FROM users u
        JOIN students s ON s.user_id = u.id
        LEFT JOIN grade_level gl ON gl.id = s.grade_level_id
        LEFT JOIN sections sec ON sec.id = s.section_id
        WHERE u.role = 'student' AND s.status = 'Pending'
        ORDER BY u.id DESC
    ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    // public function getRecentEnrollments($limit = 5)
    // {
    //     $stmt = $this->db->prepare("
    //     SELECT u.name, sec.section_name,
    //            gl.name AS grade_level,
    //            MAX(se.enrolled_at) AS enrolled_at
    //     FROM student_enrollments se
    //     JOIN students st ON st.id = se.student_id
    //     JOIN users u ON u.id = st.user_id
    //     JOIN sections sec ON sec.id = se.section_id
    //     JOIN grade_level gl ON gl.id = sec.grade_level_id
    //     GROUP BY st.id, u.name, sec.section_name, gl.name
    //     ORDER BY enrolled_at DESC
    //     LIMIT ?
    // ");
    //     $stmt->bind_param("i", $limit);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    // }

    public function getRecentEnrollments($limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT u.name, s.subject_name, sec.section_name,
               gl.name AS grade_level, se.enrolled_at
        FROM student_enrollments se
        JOIN students st ON st.id = se.student_id
        JOIN users u ON u.id = st.user_id
        JOIN subjects s ON s.id = se.subject_id
        JOIN sections sec ON sec.id = se.section_id
        JOIN grade_level gl ON gl.id = sec.grade_level_id
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
            FROM notifications n
            JOIN subjects s ON s.id = n.subject_id
            JOIN users u ON u.id = n.sender_id
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
        FROM teachers t
        JOIN users u ON t.user_id = u.id
        INNER JOIN teacher_assignments ta ON ta.teacher_id = t.id
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
            FROM student_enrollments se
            JOIN sections sec ON sec.id = se.section_id
            JOIN grade_level gl ON gl.id = sec.grade_level_id
            GROUP BY gl.id, gl.name
            ORDER BY gl.name ASC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}