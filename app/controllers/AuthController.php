<?php

require_once "../app/models/User.php";

class AuthController
{
    /**
     * Call this at the top of any protected page.
     * Redirects to login if not logged in, or to the correct dashboard if the role doesn't match.
     */
    private function checkAuth($allowed_roles = [])
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /learning_management/public/?url=login");
            exit;
        }

        if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
            // Redirect them back to their own dashboard
            $this->redirectToDashboard($_SESSION['role']);
        }
    }

    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'superadmin':
                header("Location: /learning_management/public/?url=super_admin");
                break;
            case 'admin':
                header("Location: /learning_management/public/?url=admin");
                break;
            case 'teacher':
                header("Location: /learning_management/public/?url=teacher");
                break;
            case 'student':
                header("Location: /learning_management/public/?url=dashboard");
                break;
            default:
                header("Location: /learning_management/public/?url=login");
                break;
        }
        exit;
    }

    public function login()
    {
        // If already logged in, redirect to their dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['role']);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST["email"] ?? null;
            $password = $_POST["password"] ?? null;

            $userModel = new User();
            $user = $userModel->login($email);

            if (!$user) {
                $error = "No account found with that email address.";
            } elseif ($user['status'] === 'Pending') {
                $error = "Your account is pending approval.";
            } elseif ($user['status'] === 'Declined') {
                $error = "Your account has been declined.";
            } elseif (!password_verify($password, $user['password'])) {
                $error = "Incorrect password. Please try again.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $userModel->getName($user['id']);

                if ($user['role'] === 'student') {
                    $studentInfo = $userModel->getStudentInfo($user['id']);
                    $_SESSION['grade_level'] = $studentInfo['grade_level'] ?? null;
                    $_SESSION['section'] = $studentInfo['section_name'] ?? null;
                }

                $this->redirectToDashboard($user['role']);
            }
        }

        require "../app/view/login.php";
    }

    public function dashboard()
    {
        $this->checkAuth(['student']);
        require "../app/view/dashboard.php";
    }

    public function admin()
    {
        $this->checkAuth(['admin']);
        require "../app/view/admin.php";
    }

    public function super_admin()
    {
        $this->checkAuth(['superadmin']);
        require "../app/view/super_admin.php";
    }

    public function teacher()
    {
        $this->checkAuth(['teacher']);
        require "../app/view/teacher.php";
    }

    public function signup()
    {
        // Prevent logged-in users from accessing signup
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['role']);
        }

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

            $userModel = new User();
            $errors = [];

            if (!$student_id || !$firstname || !$lastname || !$email || !$username || !$password || !$grade_level_id || !$section_id) {
                die("Please fill in all required fields.");
            }

            if ($userModel->isLRNTaken($student_id)) {
                $errors['student_id'] = "This LRN is already registered.";
            }

            if ($userModel->isEmailTaken($email)) {
                $errors['email'] = "This email is already in use.";
            }

            if ($password !== $confirm_password) {
                $errors['confirm_password'] = "Passwords do not match.";
            }

            $sections = $userModel->getSections();
            $sectionBelongsToGrade = false;
            foreach ($sections as $section) {
                if ($section['id'] == $section_id && $section['grade_level_id'] == $grade_level_id) {
                    $sectionBelongsToGrade = true;
                    break;
                }
            }

            if (!$sectionBelongsToGrade) {
                $errors['section_id'] = "The chosen section does not belong to the selected grade level.";
            }

            if (!empty($errors)) {
                $grades = $userModel->getGrades();
                $sections = $userModel->getSections();
                require "../app/view/signup.php";
                exit;
            }

            $name = trim($firstname . ' ' . ($middle ? $middle . '. ' : '') . $lastname);
            $password_HASH = password_hash($password, PASSWORD_DEFAULT);

            $userModel->signup($student_id, $name, $email, $username, $password_HASH, $grade_level_id, $section_id);

            $_SESSION['signup_success'] = true;
            header("Location: /learning_management/public/?url=signup");
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
        header("Location: /learning_management/public/?url=login");
        exit;
    }
}