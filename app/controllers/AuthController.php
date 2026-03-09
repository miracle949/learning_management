<?php

require_once "../app/models/User.php";

class AuthController
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? null;
            $password = $_POST["password"] ?? null;

            $userModel = new User();
            $user = $userModel->login($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role']    = $user['role'];
                $_SESSION['email']   = $user['email'];
                $_SESSION['name']    = $userModel->getName($user['id']);

                // Fetch grade and section for students
                if ($user['role'] === 'student') {
                    $studentInfo = $userModel->getStudentInfo($user['id']);
                    $_SESSION['grade_level'] = $studentInfo['grade_level'] ?? null;
                    $_SESSION['section']     = $studentInfo['section_name'] ?? null;
                }

                if ($_SESSION['role'] === 'admin') {
                    header("Location: /learning_management/public/?url=admin");
                } elseif ($_SESSION['role'] === 'teacher') {
                    header("Location: /learning_management/public/?url=teacher");
                } elseif ($_SESSION['role'] === 'student') {
                    header("Location: /learning_management/public/?url=dashboard");
                } else {
                    echo "<h1>Unknown role</h1>";
                }

                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }

        require "../app/view/login.php";
    }

    public function signup()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $student_id = $_POST["student_id"] ?? null;
            $firstname = $_POST["firstname"] ?? null;
            $middle = $_POST["middle"] ?? null;
            $lastname = $_POST["lastname"] ?? null;
            $email = $_POST["email"] ?? null;
            $username = $_POST["username"] ?? null;
            $password = $_POST["password"] ?? null;
            $confirm_password = $_POST["confirm_password"] ?? null;
            $grade_level_id = $_POST["grade_level_id"] ?? null;
            $section_id = $_POST["section_id"] ?? null;

            // Basic validation
            if (!$student_id || !$firstname || !$lastname || !$email || !$username || !$password || !$grade_level_id || !$section_id) {
                die("Please fill in all required fields.");
            }

            if ($password !== $confirm_password) {
                die("Passwords do not match.");
            }

            // Combine name into one
            $name = trim($firstname . ' ' . ($middle ? $middle . ' ' : '') . $lastname);

            $password_HASH = password_hash($password, PASSWORD_DEFAULT);

            $userModel = new User();
            $userModel->signup(
                $student_id,
                $name,
                $email,
                $username,
                $password_HASH,
                $grade_level_id,
                $section_id
            );

            header("Location: /learning_management/public/?url=login");
            exit;
        }

        $studentModel = new User();
        $grades = $studentModel->getGrades();
        $sections = $studentModel->getSections();

        require "../app/view/signup.php";
    }

    public function logout()
    {
        session_destroy();
        header("Location: /learning_management/public/?url=landingpage");
        exit;
    }
}