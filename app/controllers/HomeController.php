<?php

require_once "../app/models/User.php";

class HomeController{

    public $user;

    public function index(){
        $this->user = new User();
        $name = $this->user->getName();

        require_once "../app/view/dashboard.php";
    }

    public function all_subjects(){
        require_once "../app/view/all_subjects.php";
    }

    public function homepage(){
        require_once "../app/view/homepage.php";
    }

    public function subjects(){
        require_once "../app/view/subjects.php";
    }

    public function subject_lessons(){
        require_once "../app/view/subject_lessons.php";
    }

    public function subject_quiz(){
        require_once "../app/view/subject_quiz.php";
    }
}
