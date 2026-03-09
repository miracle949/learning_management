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

    public function teacherRecords()
    {
        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();

        $teachers = $this->teacherModel->getAllTeachers();

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

            // Basic validation
            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Name, email, and password are required.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            // Insert teacher into users table
            $teacher_id = $this->teacherModel->createTeacher($name, $email, $password);

            // Assign subjects and sections
            if (!empty($assigned_subjects) && !empty($assigned_sections)) {
                $this->teacherModel->assignSubjectsAndSections($teacher_id, $assigned_subjects, $assigned_sections);
            }

            $_SESSION['success'] = "Teacher created successfully.";
            header("Location: /learning_management/public/?url=teacher_records");
            exit;
            // header("Location: " . $_SERVER['HTTP_REFERER']);
            // exit;
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