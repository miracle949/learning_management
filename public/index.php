<?php

session_start();

require_once "../app/controllers/HomeController.php";
require_once "../app/controllers/AuthController.php";
require_once "../app/controllers/teacher_records.php";
require_once "../app/controllers/TeacherController.php";
require_once "../app/controllers/StudentsController.php";
require_once "../app/controllers/SuperAdminController.php";
require_once "../app/controllers/AdminController.php";

$url = $_GET['url'] ?? '';

// ── Auth Guard ────────────────────────────────────────────────
function requireAuth(...$allowed_roles)
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: /learning_management/public/?url=login");
        exit;
    }

    if (!empty($allowed_roles) && !in_array($_SESSION['role'], $allowed_roles)) {
        $role = $_SESSION['role'];
        $map = [
            'superadmin' => 'super_admin',
            'admin' => 'admin',
            'teacher' => 'teacher',
            'student' => 'dashboard',
            'teacher_users' => 'teacher_users',
            'student_users' => 'student_users',

        ];
        $redirect = $map[$role] ?? 'login';
        header("Location: /learning_management/public/?url=$redirect");
        exit;
    }
}
// ─────────────────────────────────────────────────────────────

$controller = new HomeController();
$auth = new AuthController();
$teacher = new teacher_records();
$teacherDashboard = new TeacherController();
$student = new StudentsController();
$super_controller = new SuperAdminController();
$admin = new AdminController();

switch ($url) {
    case 'landingpage':
        $controller->landingpage();
        break;

    // ── Student routes ────────────────────────────────────────
    case 'dashboard':
        requireAuth('student');
        $student->dashboardView();
        break;
    case 'classes':
        requireAuth('student');
        $student->classes();
        break;
    case 'join_class':
        requireAuth('student');
        $student->join_class();
        break;
    case 'subjects':
        requireAuth('student');
        $controller->subjects();
        break;
    case 'module_view':
        requireAuth('student');
        $student->module_view();
        break;
    case 'assignment_view':
        requireAuth('student');
        $student->assignment_view();
        break;
    case 'announcement_view':
        requireAuth('student');
        $student->announcement_view();
        break;
    case 'modules':
        requireAuth('student');
        $student->modules();
        break;
    case 'module_detail':
        requireAuth('student');
        $student->module_detail();
        break;
    case 'lesson_view':
        requireAuth('student');
        $student->lesson_view();
        break;
    case 'save_activity':
        requireAuth('student');
        $student->save_activity();
        break;
    case 'save_quiz':
        requireAuth('student');
        $student->save_quiz();
        break;
    case 'subject_lessons':
        requireAuth('student');
        $student->subject_lessons();
        break;
    case 'save_lesson_activity':
        requireAuth('student');
        $student->save_lesson_activity();
        break;
    case 'save_lesson_quiz':
        requireAuth('student');
        $student->save_lesson_quiz();
        break;
    case 'mark_lesson_visited':
        requireAuth('student');
        $student->mark_lesson_visited();
        break;
    case 'save_lesson_answers':
        requireAuth('student');
        $student->save_lesson_answers();
        break;
    case 'mark_subject_started':
        requireAuth('student');
        $student->mark_subject_started();
        break;
    case 'mark_module_started':
        requireAuth('student');
        $student->mark_module_started();
        break;
    case 'submit_assignment':
        requireAuth('student');
        $student->submit_assignment();
        break;
    case 'unsubmit_assignment':
        requireAuth('student');
        $student->unsubmit_assignment();
        break;
    case 'assignments':
        requireAuth('student');
        $student->assignments_view();
        break;
    case 'progress':
        requireAuth('student');
        $controller->progress();
        break;
    case 'subject_quiz':
        requireAuth('student');
        $controller->subject_quiz();
        break;
    case 'module_all':
        requireAuth('student');
        $student->module_all();
        break;
    case 'static_lesson':
        requireAuth('student');
        $subject = $_GET['subject'] ?? '';
        $staticId = $_GET['id'] ?? '';
        include '../app/view/subject_lessons.php';
        break;

    case 'subject_activity':
        requireAuth('student');
        $student->subject_activity();
        break;
    case 'submit_dragdrop':
        requireAuth('student');
        $student->submit_dragdrop();
        break;

    case 'submit_arrange_steps':
        requireAuth('student');
        $student->submit_arrange_steps();
        break;

    case 'submit_quiz':
        requireAuth('student');
        $student->submit_quiz();
        break;

    case 'submit_connect_pairs':
        requireAuth('student');
        $student->submit_connect_pairs();
        break;

    // ── Teacher routes ────────────────────────────────────────
    case 'teacher':
        requireAuth('teacher');
        $teacher->teacherDashboard();
        break;
    case 'classes_teacher':
        requireAuth('teacher');
        $teacher->ClassView();
        break;
    case 'teacher_class':
        requireAuth('teacher');
        $teacherDashboard->viewClass();
        break;
    case 'lessons':
        requireAuth('teacher');
        $teacherDashboard->lessons();
        break;
    case 'announce':
        requireAuth('teacher');
        $teacherDashboard->announce();
        break;
    case 'save_announcement':
        requireAuth('teacher');
        $teacherDashboard->save_announcement();
        break;
    case 'save_assignment':
        requireAuth('teacher');
        $teacherDashboard->save_assignment();
        break;
    case 'upload':
        requireAuth('teacher');
        $teacherDashboard->upload();
        break;
    case 'modules_teacher':
        requireAuth('teacher');
        $teacherDashboard->module_teacher();
        break;
    case 'view_modules_teacher':
        requireAuth('teacher');
        $teacherDashboard->view_modules_teacher();
        break;
    case 'subject_lessons_teacher':
        requireAuth('teacher');
        $teacherDashboard->subject_lessons_teacher();
        break;
    case 'create_module':
        requireAuth('teacher');
        $teacherDashboard->create_module();
        break;
    case 'save_module':
        requireAuth('teacher');
        $teacherDashboard->save_module();
        break;
    case 'save_lessons':
        requireAuth('teacher');
        $teacherDashboard->save_lessons();
        break;
    case 'save_grade':
        requireAuth('teacher', 'admin');
        $teacher->save_grade();
        break;
    case 'student_works':
        requireAuth('teacher', 'admin');
        $controller->student_works();
        break;

    case 'get_assignment_due':
        requireAuth('student');
        $student->get_assignment_due();
        break;

    case 'update_due_date':
        requireAuth('teacher', 'admin');
        $teacher->update_due_date();
        break;

    case 'works':
        requireAuth('teacher', 'admin');
        $controller->works();
        break;

    case 'get_masterlist_preview':
        $teacherDashboard->get_masterlist_preview();
        break;

    // Registers the download_masterlist_csv() method that already exists
    // in TeacherController — without this case it silently fell through
    // to the default landingpage() route.
    case 'download_masterlist_csv':
        requireAuth('teacher');
        $teacherDashboard->download_masterlist_csv();
        break;

    case 'bulk_enroll_from_masterlist':
        $teacherDashboard->bulk_enroll_from_masterlist();
        break;

    // ── Admin routes ──────────────────────────────────────────
    case 'admin':
        requireAuth('admin');
        $admin->index();
        break;

    case 'addSubject':
        requireAuth('admin');
        $admin->addSubject();
        break;
    case 'updateSubject':
        requireAuth('admin');
        $admin->updateSubject();
        break;

    case 'deleteSubject':
        requireAuth('admin');
        $admin->deleteSubject();
        break;

    case 'student_records':
        requireAuth('admin');
        $controller->student_records();
        break;
    case 'records':
        requireAuth('admin');
        $controller->records();
        break;


    case 'send_invitation':
        $teacherDashboard->send_invitation();
        break;

    case 'accept_invite':
        $teacherDashboard->accept_invite();
        break;

    // wherever your other routes are defined
    case 'import_masterlist':
        requireAuth('admin');   // ← add this line
        $admin->importMasterlist();
        break;

    case 'bulk_enroll_students':
        requireAuth('teacher');
        $teacherDashboard->bulk_enroll_students();
        break;

    // ── Super Admin routes ────────────────────────────────────
    case 'super_admin':
        requireAuth('superadmin');
        $super_controller->super_index();
        break;
    case 'activities':
        requireAuth('superadmin');
        $super_controller->activities();
        break;
    case 'view_modules_admin':
        requireAuth('superadmin');
        $teacherDashboard->view_modules_admin();
        break;
    case 'subject_lessons_admin':
        requireAuth('superadmin');
        $teacherDashboard->subject_lessons_admin();
        break;
    case 'create_activities':
        requireAuth('superadmin');
        $super_controller->create_activities();
        break;
    case 'save_interactive_module':
        requireAuth('superadmin');
        $super_controller->save_interactive_module();
        break;
    case 'save_subject':
        requireAuth('superadmin');
        $super_controller->save_subject();
        break;

    case 'createTeacher':
        requireAuth('admin');
        $admin->createTeacher();
        break;

    case 'updateTeacher':
        requireAuth('admin');
        $admin->updateTeacher();
        break;

    case 'create_super_admin_Teacher':
        requireAuth('superadmin');
        $teacher->create_super_admin_Teacher();
        break;

    case 'update_super_admin_Teacher':
        requireAuth('superadmin');
        $teacher->update_super_admin_Teacher();
        break;

    case 'get_all_pending_ids':
        $teacher->getAllPendingIds();
        break;

    case 'bulk_approve_students':
        $teacher->bulk_approve_students();
        break;

    case 'update_student':
        $admin->updateStudent();
        break;

    case 'update_super_admin_Student':
        requireAuth('superadmin');
        $super_controller->update_super_admin_Student();
        break;

    case 'teacher_users':
        requireAuth('admin');
        $admin->teacherRecords();
        break;

    case 'student_users':
        requireAuth('admin');
        $admin->studentRecords();
        break;

    case 'Adminsubjects':
        requireAuth('admin');
        $admin->SubjectPage();
        break;

    case 'Adminsections':
        requireAuth('admin');
        $admin->SectionPage();
        break;

    case 'createSection':
        requireAuth('admin');
        $admin->createSection();
        break;

    case 'updateSection':
        requireAuth('admin');
        $admin->updateSection();
        break;

    case 'deleteSection':
        requireAuth('admin');
        $admin->deleteSection();
        break;

    case 'Reports':
        requireAuth('admin');
        $admin->reportsPage();
        break;

    case 'subject_access':
        requireAuth('admin');
        $admin->subjectAccessPage();
        break;

    case 'subject_access_section_ajax':
        requireAuth('admin');
        $admin->subjectAccessSectionAjax();
        break;

    case 'toggle_subject_access':
        requireAuth('admin');
        $admin->toggleSubjectAccessAjax();
        break;

    case 'bulk_toggle_subject_access':
        requireAuth('admin');
        $admin->bulkToggleSubjectAccessAjax();
        break;

    case 'super_admin_admin_users':
        requireAuth('superadmin');
        $super_controller->super_admin_adminRecords();
        break;

    case 'create_super_admin_Admin':
        requireAuth('superadmin');
        $super_controller->create_super_admin_Admin();
        break;

    case 'update_super_admin_Admin':
        requireAuth('superadmin');
        $super_controller->update_super_admin_Admin();
        break;

    case 'delete_super_admin_Admin':
        requireAuth('superadmin');
        $super_controller->delete_super_admin_Admin();
        break;

    case 'school_profile':
        requireAuth('superadmin');
        $super_controller->schoolProfile();
        break;

    case 'save_school_profile':
        requireAuth('superadmin');
        $super_controller->saveSchoolProfile();
        break;

    case 'roles_permissions':
        requireAuth('superadmin');
        $super_controller->rolesPermissions();
        break;

    case 'save_admin_permissions':
        requireAuth('superadmin');
        $super_controller->saveAdminPermissions();
        break;

    case 'toggle_role_permission':
        requireAuth('superadmin');
        $super_controller->toggleRolePermission();
        break;

    case 'backup_restore':
        requireAuth('superadmin');
        $super_controller->backupRestore();
        break;

    case 'create_backup':
        requireAuth('superadmin');
        $super_controller->createBackup();
        break;

    case 'download_backup':
        requireAuth('superadmin');
        $super_controller->downloadBackup();
        break;

    case 'delete_backup':
        requireAuth('superadmin');
        $super_controller->deleteBackup();
        break;

    case 'restore_backup':
        requireAuth('superadmin');
        $super_controller->restoreBackup();
        break;

    case 'audit_logs':
        requireAuth('superadmin');
        $super_controller->auditLogs();
        break;

        // ── Admin Page-Permission Gate ──────────────────────────────
// Only restricts the 'admin' role. superadmin/teacher/student
// are unaffected — requireAuth() already scopes those.
        function requireAdminPageAccess(string $pageKey)
        {
            if (($_SESSION['role'] ?? '') === 'admin') {
                require_once "../app/models/SuperAdmin.php";
                $model = new SuperAdmin();
                if (!$model->adminCanAccessPage((int) $_SESSION['user_id'], $pageKey)) {
                    $_SESSION['flash'] = [
                        'type' => 'error',
                        'message' => "You don't have access to that page.",
                        'page' => 'admin'
                    ];
                    header("Location: /learning_management/public/?url=admin");
                    exit;
                }
            }
        }

    case 'teacher_users':
        requireAuth('admin');
        requireAdminPageAccess('teacher_users');
        $admin->teacherRecords();
        break;

    case 'student_users':
        requireAuth('admin');
        requireAdminPageAccess('student_users');
        $admin->studentRecords();
        break;

    case 'Adminsubjects':
        requireAuth('admin');
        requireAdminPageAccess('Adminsubjects');
        $admin->SubjectPage();
        break;

    case 'Adminsections':
        requireAuth('admin');
        requireAdminPageAccess('Adminsections');
        $admin->SectionPage();
        break;

    case 'Reports':
        requireAuth('admin');
        requireAdminPageAccess('Reports');
        $admin->reportsPage();
        break;

    case 'subject_access':
        requireAuth('admin');
        requireAdminPageAccess('subject_access');
        $admin->subjectAccessPage();
        break;

    case 'roles_permissions':
        requireAuth('superadmin');
        $super_controller->rolesPermissions();
        break;

    case 'save_admin_permissions':
        requireAuth('superadmin');
        $super_controller->saveAdminPermissions();
        break;
    // ─────────────────────────────────────────────────────────────

    // ── NEW — AJAX endpoint for student_users.php search/filter/pagination
    //          (Enrolled Students + Masterlist tables), no full page reload.
    case 'student_users_search':
        requireAuth('admin');
        $admin->studentRecordsAjax();
        break;

    case 'super_admin_teacher_users':
        requireAuth('superadmin');
        $super_controller->super_admin_teacherRecords();
        break;

    case 'super_admin_student_users':
        requireAuth('superadmin');
        $super_controller->super_admin_studentRecords();
        break;

    // ── Auth routes (public) ──────────────────────────────────
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