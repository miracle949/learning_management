<?php

require_once "../app/models/User.php";

class HomeController{

    public $user;

    public function index(){
        $this->user = new User();
        $name = $this->user->getName();

        require_once "../app/view/dashboard.php";
    }

    public function subjects(){
        require_once "../app/view/subjects.php";
    }

    public function homepage(){
        require_once "../app/view/homepage.php";
    }

    public function subject_module(){
        require_once "../app/view/subject_module.php";
    }

    public function lessons(){
        require_once "../app/view/lessons.php";
    }

    public function quiz(){
        require_once "../app/view/Quiz.php";
    }
}
