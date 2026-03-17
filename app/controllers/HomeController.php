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

    public function subjects_all()
    {
        require_once "../app/view/subjects_all.php";
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
}
