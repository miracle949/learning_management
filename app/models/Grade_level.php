<?php

require_once "../core/Model.php";

class Grade_level extends Model {

    public function getSectionsByGradeLevel($grade_level_id) {
        $sql = "SELECT s.*, g.name as grade_name 
                FROM sections s 
                JOIN grade_level g ON s.grade_level_id = g.id 
                WHERE s.grade_level_id = ?
                ORDER BY s.id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $grade_level_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getGrade11Sections() {
        return $this->getSectionsByGradeLevel(1);
    }

    public function getGrade12Sections() {
        return $this->getSectionsByGradeLevel(2);
    }
}