<?php

require_once "../app/controllers/HomeController.php";

$url = $_GET['url'] ?? '';

// $subject = $_GET['subject'] ?? null;

// $lessons = $_GET['lesson'] ?? null;

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

// $subjectMap = [
//     "philosophy" => "../philosophy_folder/philosophy.php",
// ];

// $subject_Lessons = [
//     "philosophy_lessons" => "../philosophy_folder/philosophy_lessons.php",
// ];  

// if ($subject && isset($subjectMap[$subject])) {
//     include($subjectMap[$subject]);
// } else {
//     echo "<h3>Select a subject</h3>";
// }

