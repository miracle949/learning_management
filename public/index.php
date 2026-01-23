<?php

require_once "../app/controllers/HomeController.php";

$url = $_GET['url'] ?? '';

$controller = new HomeController();

switch ($url) {
    case 'dashboard':
        $controller->index();
        break;

    case 'all_subjects':
        $controller->all_subjects();
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

    default:
        $controller->index();
        break;
}

