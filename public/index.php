<?php

session_start();

require_once "../app/controllers/HomeController.php";
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/teacher_records.php";
require_once "../app/controllers/TeacherController.php";
require_once "../app/controllers/StudentsController.php";

$url = $_GET['url'] ?? '';

$controller = new HomeController();
$auth = new AuthController();
$teacher = new teacher_records();
$teacherDashboard = new TeacherController();
$student = new StudentsController();

switch ($url) {
    case 'landingpage':
        $controller->landingpage();
        break;

    case 'dashboard':
        $controller->dashboard();
        break;

    case 'classes':
        $student->classes();
        break;

    case 'subjects':
        $controller->subjects();
        break;

    // ── Classes feed ──────────────────────────────────────────
    case 'module_view':
        $student->module_view();
        break;

    case 'assignment_view':
        $student->assignment_view();
        break;

    case 'announcement_view':
        $student->announcement_view();
        break;

    case 'save_lessons':
        $teacherDashboard->save_lessons();
        break;

    // ── Interactive Modules ───────────────────────────────────
    case 'modules':
        $student->modules();
        break;

    case 'module_detail':
        $student->module_detail();
        break;

    case 'lesson_view':
        $student->lesson_view();
        break;

    case 'save_activity':
        $student->save_activity();
        break;

    case 'save_quiz':
        $student->save_quiz();
        break;

    // ── Subject Lessons (lesson viewer with tabs) ─────────────
    // FIX: was $controller->subject_lessons() — now $student
    case 'subject_lessons':
        $student->subject_lessons();
        break;

    // ── Save lesson-level activity & quiz ─────────────────────
    case 'save_lesson_activity':
        $student->save_lesson_activity();
        break;

    case 'save_lesson_quiz':
        $student->save_lesson_quiz();
        break;

    case 'mark_lesson_visited':
        $student->mark_lesson_visited();
        break;

    case 'save_lesson_answers':
        $student->save_lesson_answers();
        break;

    case 'mark_subject_started':
        $student->mark_subject_started();
        break;

    case 'mark_module_started':
        $student->mark_module_started();
        break;

    case 'mark_module_started':
        $student->mark_module_started();
        break;

    // ─────────────────────────────────────────────────────────
    case 'subject_quiz':
        $controller->subject_quiz();
        break;

    case 'module_all':
        $student->module_all();
        break;

    case 'addSubject':
        $teacher->addSubject();
        break;

    case 'admin':
        $teacher->recentStudents();
        break;

    case 'teacher':
        $teacher->teacherDashboard();
        break;

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