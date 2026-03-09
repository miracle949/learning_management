<?php

require_once "../app/models/User.php";

class TeacherController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $user_id    = $_SESSION['user_id'];
        $teacher_id = $this->user->getTeacherId($user_id);

        $teacherInfo = $this->user->getTeacherInfo($user_id);
        $classes     = $this->user->getAssignedClasses($teacher_id);
        $stats       = $this->user->getTeacherStats($teacher_id);

        extract([
            'teacherInfo' => $teacherInfo,
            'classes'     => $classes,
            'stats'       => $stats,
        ]);

        require "../app/view/teacher.php";
    }

    public function viewClass()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        // Both subject_id and grade_level_id are needed to identify the specific class
        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);

        $classInfo = $this->user->getClassInfo($subject_id, $grade_level_id);

        extract([
            'subject_id'     => $subject_id,
            'grade_level_id' => $grade_level_id,
            'classInfo'      => $classInfo,
        ]);

        require "../teacher_folder/records.php";
    }

    public function lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);

        extract([
            'subject_id'     => $subject_id,
            'grade_level_id' => $grade_level_id,
        ]);

        require "../teacher_folder/lessons.php";
    }

    public function announce()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title   = htmlspecialchars($_POST['title']   ?? '');
            $content = htmlspecialchars($_POST['content'] ?? '');
            // TODO: save announcement to DB for $subject_id + $grade_level_id
        }

        header("Location: /learning_management/public/?url=teacher_class&id=$subject_id&grade_id=$grade_level_id");
        exit;
    }

    public function upload()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id     = (int)($_GET['id']       ?? 0);
        $grade_level_id = (int)($_GET['grade_id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // TODO: handle lesson/video/image/activity upload for $subject_id + $grade_level_id
        }

        header("Location: /learning_management/public/?url=lessons&id=$subject_id&grade_id=$grade_level_id");
        exit;
    }
}