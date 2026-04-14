<?php

require_once "../app/models/User.php";

class HomeController
{

    // public function index(){
    //     if(!isset($_SESSION['user_id'])){

    //         header("Location: /learning_management/public/?url=login");
    //         exit;

    //     }else{

    //         require_once "../app/view/dashboard.php";

    //     }

    // }

    public function classes()
    {
        require_once "../app/view/classes.php";
    }

    public function dashboard()
    {
        require_once "../app/view/dashboard.php";
    }

    public function subjects()
    {
        // require_once "../app/view/subjects.php";
        $subject = $_GET['subject'] ?? null;

        require "../app/view/subjects.php";
    }


    public function subject_lessons()
    {
        require_once "../app/view/subject_lessons.php";
    }

    public function module_teacher(){
        require_once "../teacher_folder/modules_teacher.php";
    }

    public function subject_quiz()
    {
        require_once "../app/view/subject_quiz.php";
    }

    public function login()
    {
        require_once "../app/view/login.php";
    }

    public function signup()
    {
        require_once "../app/view/signup.php";
    }

    public function landingpage()
    {
        require_once "../app/view/landingpage.php";
    }

    public function admin()
    {
        require_once "../app/view/admin.php";
    }

    public function teacher()
    {
        require_once "../app/view/teacher.php";
    }

    public function super_admin(){
        require_once "../app/view/super_admin.php";
    }

    public function activities(){
        require_once "../super_admin_folder/activities.php";
    }

    public function create_activities(){
        require_once "../super_admin_folder/create_activities.php";
    }

    // public function student_works(){
    //     require_once "../teacher_folder/student_works.php";
    // }

    public function student_works()
    {
        require_once "../app/models/Teacher.php";

        $teacherModel = new Teacher();

        $assignment_id = isset($_GET['assignment_id']) ? (int) $_GET['assignment_id'] : 0;
        $subject_id = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;

        $assignmentInfo = $assignment_id ? $teacherModel->getAssignmentById($assignment_id) : null;
        $submissions = $assignment_id ? $teacherModel->getSubmissions($assignment_id) : [];

        require_once "../teacher_folder/student_works.php";
    }

    public function works()
    {
        require_once "../app/models/Teacher.php";

        $teacherModel = new Teacher();

        $assignment_id = isset($_GET['assignment_id']) ? (int) $_GET['assignment_id'] : 0;
        $subject_id = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
        $student_index = isset($_GET['student_index']) ? (int) $_GET['student_index'] : 0;

        $assignmentInfo = $assignment_id ? $teacherModel->getAssignmentById($assignment_id) : null;
        $submissions = $assignment_id ? $teacherModel->getSubmissions($assignment_id) : [];

        // Current student submission based on index
        $totalStudents = count($submissions);
        $student_index = max(0, min($student_index, $totalStudents - 1));
        $currentSub = $submissions[$student_index] ?? null;

        require_once "../teacher_folder/works.php";
    }

    public function student_records()
    {
        require_once "../admin_folder/student_records.php";
    }

    public function teacher_records()
    {
        require_once "../admin_folder/teacher_records.php";
    }

    public function records()
    {
        require_once "../teacher_folder/records.php";
    }

    public function lessons()
    {
        require_once "../teacher_folder/lessons.php";
    }

    public function module_all()
    {
        require_once "../app/view/module_all.php";
    }

    public function assignments()
    {
        require_once "../app/view/assignments.php";
    }

    public function progress()
    {
        require_once "../app/view/progress.php";
    }

    public function classes_teacher()
    {
        require_once "../teacher_folder/classes.php";
    }
}
