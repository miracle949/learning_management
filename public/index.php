<?php

session_start();

require_once "../app/controllers/HomeController.php";
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/teacher_records.php";
require_once "../app/controllers/TeacherController.php";

$url = $_GET['url'] ?? '';

$controller = new HomeController();
$auth = new AuthController();
$teacher = new teacher_records();
$teacherDashboard = new TeacherController(); 

switch ($url) {
    case 'landingpage':
        $controller->landingpage();
        break;

    case 'dashboard':
        $controller->dashboard();
        break;

    case 'subjects_all':
        $controller->subjects_all();
        break;

    case 'subjects':
        $controller->subjects();
        break;

    case 'addSubject':
        $teacher->addSubject();
        break;

    case 'subject_lessons':
        $controller->subject_lessons();
        break;

    case 'subject_quiz':
        $controller->subject_quiz();
        break;

    case 'admin':
        $teacher->recentStudents();
        break;
        
    case 'teacher':
        $teacherDashboard->index();
        break;

    // ✅ This was the missing route — "View class" now loads records.php
    case 'teacher_class':
        $teacherDashboard->viewClass();
        break;

    case 'student_records':
        $controller->student_records();
        break;

    case 'teacher_records':
        $teacher->teacherRecords();
        break;
    
    case 'createTeacher':
        $teacher->createTeacher();
        break;

    case 'records':
        $controller->records();
        break;

    case 'lessons':
        $teacherDashboard->lessons();
        break;

    case 'announce':
        $teacherDashboard->announce();
        break;

    case 'upload':
        $teacherDashboard->upload();
        break;

    case 'login':
        $auth->login();
        break;

    case 'signup':
        $auth->signup();
        break;
    
    case 'logout':
        $auth->logout();
        break;

    default:
        $controller->landingpage();
        break;
}