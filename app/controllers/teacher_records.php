<?php

require_once "../app/models/Grade_level.php";
require_once "../app/models/subjects.php";
require_once "../app/models/Teacher.php";

class teacher_records
{
    public $subjectModel, $gradeLevel, $teacherModel;

    public function __construct()
    {
        $this->subjectModel = new subjects();
        $this->gradeLevel = new Grade_level();
        $this->teacherModel = new Teacher();
    }

    public function teacherDashboard()
    {
        // Check what session key your teacher login uses
        // Try teacher_id first, fallback to user_id
        $teacher_id = $_SESSION['teacher_id'] ?? null;

        if (!$teacher_id) {
            // Find teacher_id from users table using user_id session
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                header("Location: /learning_management/public/?url=login");
                exit;
            }

            $result = $this->teacherModel->getTeacherIdByUserId($user_id);
            $teacher_id = $result['teacher_id'] ?? null;

            if (!$teacher_id) {
                die("Teacher record not found.");
            }

            $_SESSION['teacher_id'] = $teacher_id;
        }

        $classes = $this->teacherModel->getTeacherClasses($teacher_id);
        $stats = $this->teacherModel->getTeacherStats($teacher_id);
        $totalStudents = array_sum(array_column($classes, 'student_count'));
        $teacherInfo = ['name' => $_SESSION['teacher_name'] ?? $_SESSION['name'] ?? 'Teacher'];

        require_once "../app/view/teacher.php";
    }

    public function teacherRecords()
    {
        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();
        $teachers = $this->teacherModel->getAllTeachers();

        // Legacy classCounts kept for backward compatibility
        // $classCountsRaw = $this->teacherModel->getStudentCountPerClass($_SESSION['teacher_id']);
        // $classCounts = [];
        // foreach ($classCountsRaw as $row) {
        //     $key = $row['subject_id'] . '_' . $row['section_id'];
        //     $classCounts[$key] = $row['total_students'];
        // }

        require_once "../admin_folder/teacher_records.php";
    }

    public function createTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $assigned_subjects = $_POST['assigned_subjects'] ?? [];
            $assigned_sections = $_POST['assigned_sections'] ?? [];

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Name, email, and password are required.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $teacher_id = $this->teacherModel->createTeacher($name, $email, $password);

            if (!empty($assigned_subjects) && !empty($assigned_sections)) {
                $this->teacherModel->assignSubjectsAndSections($teacher_id, $assigned_subjects, $assigned_sections);
            }

            $_SESSION['success'] = "Teacher created successfully.";
            header("Location: /learning_management/public/?url=teacher_records");
            exit;
        }
    }

    public function addSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject_name = trim($_POST['subject_name'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);

            if (empty($subject_name) || $grade_level_id === 0) {
                $_SESSION['error'] = "Subject name and grade level are required.";
                header("Location: /learning_management/public/?url=teacher_records");
                exit;
            }

            $this->subjectModel->insertSubject($subject_name, $grade_level_id);

            $_SESSION['success'] = "Subject \"$subject_name\" added successfully.";
            header("Location: /learning_management/public/?url=teacher_records");
            exit;
        }
    }

    public function RecentStudents()
    {
        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();
        $teachers = $this->teacherModel->getAllTeachers();
        $recentStudents = $this->teacherModel->getRecentStudents(5);

        require_once "../app/view/admin.php";
    }
}