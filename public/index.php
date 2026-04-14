<?php

session_start();

require_once "../app/controllers/HomeController.php";
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/teacher_records.php";
require_once "../app/controllers/TeacherController.php";
require_once "../app/controllers/StudentsController.php";
require_once "../app/controllers/SuperAdminController.php";

$url = $_GET['url'] ?? '';

$controller = new HomeController();
$auth = new AuthController();
$teacher = new teacher_records();
$teacherDashboard = new TeacherController();
$student = new StudentsController();
$super_controller = new SuperAdminController();

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
    case 'join_class':
        $student->join_class();
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

    // case 'modules_teacher':
    //     $controller->module_teacher();
    //     break;

    case 'static_lesson':
        $subject = $_GET['subject'] ?? '';
        $staticId = $_GET['id'] ?? '';
        include '../app/view/subject_lessons.php';
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

    // ── Subject Lessons ───────────────────────────────────────
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
        break; // ← single, no duplicate

    case 'submit_assignment':
        $student->submit_assignment();
        break;

    case 'unsubmit_assignment':
        $student->unsubmit_assignment();
        break;

    case 'assignments':
        $student->assignments_view();
        break;

    case 'progress':
        $controller->progress();
        break;

    case 'subject_quiz':
        $controller->subject_quiz();
        break;

    case 'module_all':
        $student->module_all();
        break;

    // ── Admin / Teacher ───────────────────────────────────────
    case 'addSubject':
        $teacher->addSubject();
        break;

    case 'admin':
        $teacher->RecentStudents();
        break;

    case 'teacher':
        $teacher->teacherDashboard();
        break;

    case 'classes_teacher':
        $teacher->ClassView();
        break; // ← single, no duplicate

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

    case 'student_works':
        $controller->student_works();
        break;

    case 'works':
        $controller->works();
        break;

    case 'save_grade':
        $teacher->save_grade();
        break;

    case 'lessons':
        $teacherDashboard->lessons();
        break;

    case 'announce':
        $teacherDashboard->announce();
        break;

    case 'save_announcement':
        $teacherDashboard->save_announcement();
        break;

    case 'save_assignment':
        $teacherDashboard->save_assignment();
        break;

    case 'upload':
        $teacherDashboard->upload();
        break;

    case 'modules_teacher':
        $teacherDashboard->module_teacher();
        break;

    case 'view_modules_teacher':
        $teacherDashboard->view_modules_teacher();
        break;

    case 'subject_lessons_teacher':
        $teacherDashboard->subject_lessons_teacher();
        break;

    case 'create_module':
        $teacherDashboard->create_module();
        break;

    case 'save_module':
        $teacherDashboard->save_module();
        break;

    // super admin

    case 'super_admin':
        $controller->super_admin();
        break;

    case 'activities':
        $super_controller->activities();
        break;

    case 'create_activities':
        $super_controller->create_activities();
        break;

    case 'save_interactive_module':        // ← ADD THIS
        $super_controller->save_interactive_module();
        break;

    case 'save_subject':
        $super_controller->save_subject();
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