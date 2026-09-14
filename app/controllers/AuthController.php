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

    // ============================================================
    // LOGIN — single form, single route. A hidden "login_type" field
    // ("student" or "staff") tells us which credential path to use:
    //   - student -> LRN + password, must resolve to role = student
    //   - staff   -> username/email + password, must resolve to
    //                role = teacher/admin/superadmin
    // ============================================================
    public function login()
    {
        // If already logged in, redirect to their dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['role']);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $login_type = ($_POST['login_type'] ?? 'student') === 'staff' ? 'staff' : 'student';
            $password = $_POST["password"] ?? null;

            $userModel = new User();
            $user = null;

            if ($login_type === 'staff') {
                $identifier = trim($_POST["identifier"] ?? '');

                if ($identifier === '' || !$password) {
                    $error = "Please enter your username/email and password.";
                } else {
                    // loginByIdentifier() should look up tbl_users by username OR email
                    // and return id, email, password, role, status (same shape as loginByLRN()).
                    $user = $userModel->loginByIdentifier($identifier);

                    if (!$user) {
                        $error = "No account found with that username.";
                    } elseif ($user['role'] === 'student') {
                        // Students must use the LRN-based form instead.
                        $error = "Students should sign in from the student login form.";
                        $user = null;
                    }
                }
            } else {
                $lrn = trim($_POST["lrn"] ?? '');

                if ($lrn === '' || !$password) {
                    $error = "Please enter your LRN and password.";
                } else {
                    // loginByLRN() should join tbl_students -> tbl_users on user_id
                    // and return the user's id, email, password, role, status.
                    $user = $userModel->loginByLRN($lrn);

                    if (!$user) {
                        $error = "No account found with that LRN.";
                    }
                }
            }

            // Shared checks once we have a candidate $user, regardless of login_type.
            if ($user) {
                if (!password_verify($password, $user['password'])) {
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
                        $_SESSION['student_lrn'] = $lrn ?? null;
                    }

                    $this->redirectToDashboard($user['role']);
                }
            }
        }

        require "../app/view/login.php";
    }

    public function dashboard()
    {
        $this->checkAuth(['student']);
        $studentsController = new StudentsController();
        $studentsController->dashboardView();
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

    // ============================================================
    // SIGNUP — LRN must exist in tbl_master_lrn before an account
    // can be created. Auto-approves on match; otherwise blocks signup.
    // ============================================================
    public function signup()
    {
        // Prevent logged-in users from accessing signup
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['role']);
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $student_id = trim($_POST["student_id"] ?? '');
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

            // ── Verify LRN against the admin-imported masterlist FIRST ──
            // matchAgainstMasterlist() should query tbl_master_lrn by student_LRN.
            $masterRecord = $userModel->matchAgainstMasterlist($student_id);
            if (!$masterRecord) {
                $errors['student_id'] = "This LRN was not found in our official student list. Please contact your school registrar.";
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

            // signup() should return the new tbl_students.id (insert_id) so we
            // can link any enrollments the teacher already bulk-enrolled by LRN.
            $newStudentId = $userModel->signup(
                $student_id,
                $name,
                $email,
                $username,
                $password_HASH,
                $grade_level_id,
                $section_id
            );

            // Attach this brand-new account to any tbl_student_enrollments rows
            // that were created earlier (teacher bulk enrollment) with this LRN
            // but no student_id yet, since the account didn't exist at the time.
            if ($newStudentId) {
                $userModel->linkPendingEnrollments($student_id, $newStudentId);
            }

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