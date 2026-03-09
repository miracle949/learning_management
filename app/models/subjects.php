<?php

require_once "../core/Model.php";

class subjects extends Model {

    public function getSubjectsByGradeLevel($grade_level_id) {
        $sql = "SELECT * FROM subjects WHERE grade_level_id = ? ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $grade_level_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getGrade11Subjects() {
        return $this->getSubjectsByGradeLevel(1);
    }

    public function getGrade12Subjects() {
        return $this->getSubjectsByGradeLevel(2);
    }

    public function getAllSubjectsGroupedByGrade() {
        $sql = "SELECT s.*, g.name as grade_name 
                FROM subjects s 
                JOIN grade_level g ON s.grade_level_id = g.id 
                ORDER BY s.grade_level_id ASC, s.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $results = $result->fetch_all(MYSQLI_ASSOC);

        $grouped = [];
        foreach ($results as $row) {
            $grouped[$row['grade_name']][] = $row;
        }
        return $grouped;
    }
}