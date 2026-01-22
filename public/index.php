<?php

require_once "../app/controllers/HomeController.php";

$url = $_GET['url'] ?? '';

$controller = new HomeController();

switch ($url) {
    case 'dashboard':
        $controller->index();
        break;

    case 'subjects':
        $controller->subjects();
        break;

    case 'subject_module':
        $controller->subject_module();
        break;

    case 'lessons':
        $controller->lessons();
        break;

    case 'Quiz':
        $controller->quiz();
        break;

    default:
        $controller->index();
        break;
}

