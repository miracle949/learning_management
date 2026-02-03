<?php

session_start();

require_once "../app/controllers/HomeController.php";
require_once "../app/controllers/AuthController.php";

$url = $_GET['url'] ?? '';

$controller = new HomeController();
$auth = new AuthController();

switch ($url) {
    case 'dashboard':
        $controller->index();
        break;

    case 'subjects_all':
        $controller->subjects_all();
        break;

    case 'subjects':
        $controller->subjects();
        break;

    case 'subject_lessons':
        $controller->subject_lessons();
        break;

    case 'subject_quiz':
        $controller->subject_quiz();
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
        $controller->login();
        break;
}


