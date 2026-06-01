<?php

require_once "../core/Model.php";
require_once "../app/models/Admin.php";

class AdminController
{

    public function index() // or whatever your admin dashboard method is called
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?url=login");
            exit;
        }

        require_once "../app/models/Admin.php";
        $adminModel = new Admin();

        $totalStudents = $adminModel->getTotalStudents();
        $totalTeachers = $adminModel->getTotalTeachers();
        $totalSubjects = $adminModel->getTotalSubjects();
        $totalSections = $adminModel->getTotalSections();
        $totalPendingApprovals = $adminModel->getTotalPendingApprovals();
        $pendingStudents = $adminModel->getPendingStudents();
        $recentEnrollments = $adminModel->getRecentEnrollments(5);
        $announcements = $adminModel->getRecentAnnouncements(5);
        $teacherWorkload = $adminModel->getTeacherWorkload();
        $enrollmentByGrade = $adminModel->getEnrollmentByGrade();
        $approvedCount = $adminModel->countStudentsByStatus('Approved'); // ← ADD THIS
        $pendingCount = $adminModel->countStudentsByStatus('Pending');

        extract([
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalSubjects' => $totalSubjects,
            'totalSections' => $totalSections,
            'totalPendingApprovals' => $totalPendingApprovals,
            'pendingStudents' => $pendingStudents,
            'recentEnrollments' => $recentEnrollments,
            'announcements' => $announcements,
            'teacherWorkload' => $teacherWorkload,
            'enrollmentByGrade' => $enrollmentByGrade,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
        ]);

        require "../app/view/admin.php"; // or your admin view path
    }

}
