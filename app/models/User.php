<?php

require_once "../core/Model.php";

class User extends Model {
    public function getName(){
        return "Rogelio Amoyan";
    }

    public function login($email){
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? AND status = 'Active'"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function signup($student_id, $firstname, $middle, $lastname, $email, $username, $password, $grade_level, $section){
        $role = "Student";
        $status = "Active";

        $stmt = $this->db->prepare(
            "INSERT INTO users (student_id, firstname, middle, lastname, email, username, password, grade_level, section, role, status) VALUES (?,?,?,?,?,?,?,?,?,?,?)"
        );

        $stmt->bind_param("sssssssssss", $student_id, $firstname, $middle, $lastname, $email, $username, $password, $grade_level, $section, $role, $status);

        $stmt->execute();
    }
}