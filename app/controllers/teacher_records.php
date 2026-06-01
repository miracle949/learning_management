<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../vendor/autoload.php';

require_once "../app/models/Grade_level.php";
require_once "../app/models/subjects.php";
require_once "../app/models/Teacher.php";

class teacher_records
{
    public $subjectModel, $gradeLevel, $teacherModel;

    public function __construct()
    {
        $this->subjectModel = new subjects();
        $this->gradeLevel = new Grade_level();
        $this->teacherModel = new Teacher();
    }

    public function ClassView()
    {
        $teacher_id = $_SESSION['teacher_id'] ?? null;

        $user_id = $_SESSION['user_id'] ?? null;

        $result = $this->teacherModel->getTeacherIdByUserId($user_id);
        $teacher_id = $result['teacher_id'] ?? null;

        if (!$teacher_id) {
            die("Teacher record not found.");
        }

        $_SESSION['teacher_id'] = $teacher_id;

        $classes = $this->teacherModel->getTeacherClasses($teacher_id);
        $stats = $this->teacherModel->getTeacherStats($teacher_id);
        $totalStudents = array_sum(array_column($classes, 'student_count'));
        $teacherInfo = ['name' => $_SESSION['teacher_name'] ?? $_SESSION['name'] ?? 'Teacher'];

        require_once "../teacher_folder/classes.php"; // ← which classes.php loads here?
    }

    public function teacherDashboard()
    {
        $teacher_id = $_SESSION['teacher_id'] ?? null;

        if (!$teacher_id) {
            $user_id = $_SESSION['user_id'] ?? null;
            if (!$user_id) {
                header("Location: /learning_management/public/?url=login");
                exit;
            }
            $result = $this->teacherModel->getTeacherIdByUserId($user_id);
            $teacher_id = $result['teacher_id'] ?? null;
            if (!$teacher_id)
                die("Teacher record not found.");
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $classes = $this->teacherModel->getTeacherClasses($teacher_id);
        $stats = $this->teacherModel->getTeacherStats($teacher_id);
        $totalStudents = array_sum(array_column($classes, 'student_count'));
        $submittedCount = $this->teacherModel->getTotalSubmittedAssignments($teacher_id);
        $submittedAssignments = $this->teacherModel->getSubmittedAssignmentsByTeacher($teacher_id);
        $teacherInfo = ['name' => $_SESSION['teacher_name'] ?? $_SESSION['name'] ?? 'Teacher'];

        // ── NEW: replace enrolled students list + submitted assignments ──
        $upcomingAssignments = $this->teacherModel->getUpcomingAssignments($teacher_id);
        $teacherAnnouncements = $this->teacherModel->getAnnouncementsByTeacher($teacher_id);

        require_once "../app/view/teacher.php";
    }

    public function studentRecords()
    {
        require_once "../app/models/Teacher.php";
        $teacherModel = new Teacher();

        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $students = $teacherModel->getAllStudentsFiltered($limit, $offset, $search, $grade, $section, $status);
        $totalStudents = $teacherModel->countAllStudentsFiltered($search, $grade, $section, $status);
        $totalPages = ceil($totalStudents / $limit);
        $gradeLevels = $teacherModel->getAllGradeLevels();
        $allSections = $teacherModel->getAllSections();

        $totalPending = $teacherModel->countStudentsByStatus('Pending');
        $totalApproved = $teacherModel->countStudentsByStatus('Approved');
        $totalDeclined = $teacherModel->countStudentsByStatus('Rejected');

        extract(compact(
            'students',
            'totalStudents',
            'totalPages',
            'page',
            'limit',
            'offset',
            'gradeLevels',
            'allSections',
            'totalPending',
            'totalApproved',
            'totalDeclined'
        ));
        require "../admin_folder/student_users.php";
    }

    public function super_admin_studentRecords()
    {
        require_once "../app/models/Teacher.php";
        $teacherModel = new Teacher();

        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $students = $teacherModel->getAllStudentsFiltered($limit, $offset, $search, $grade, $section, $status);
        $totalStudents = $teacherModel->countAllStudentsFiltered($search, $grade, $section, $status);
        $totalPages = ceil($totalStudents / $limit);
        $gradeLevels = $teacherModel->getAllGradeLevels();
        $allSections = $teacherModel->getAllSections();

        $totalPending = $teacherModel->countStudentsByStatus('Pending');
        $totalApproved = $teacherModel->countStudentsByStatus('Approved');
        $totalDeclined = $teacherModel->countStudentsByStatus('Rejected');

        extract(compact(
            'students',
            'totalStudents',
            'totalPages',
            'page',
            'limit',
            'offset',
            'gradeLevels',
            'allSections',
            'totalPending',
            'totalApproved',
            'totalDeclined'
        ));
        require "../super_admin_folder/student_users.php";
    }

    public function super_admin_teacherRecords()
    {
        $this->teacherModel->backfillJoinCodes();

        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();

        // ── Read filter + pagination params ──
        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // ── Total count (for pagination) ──
        $totalTeachers = $this->teacherModel->countAllTeachersFiltered($search, $grade, $section, $status);
        $totalPages = (int) ceil($totalTeachers / $limit);

        // ── Paginated teacher list ──
        $teachers = $this->teacherModel->getAllTeachersFilteredPaginated(
            $search,
            $grade,
            $section,
            $status,
            $limit,
            $offset
        );

        // Attach enrolled students to each subject
        foreach ($teachers as &$teacher) {
            foreach ($teacher['subjects'] as &$subject) {
                $subject['students'] = !empty($subject['id'])
                    ? $this->teacherModel->getEnrolledStudentsBySubject(
                        (int) $subject['id'],
                        (int) $teacher['teacher_id']
                    )
                    : [];
            }
        }
        unset($teacher, $subject);

        extract(compact(
            'teachers',
            'totalTeachers',
            'totalPages',
            'page',
            'limit',
            'offset',
            'grade11Subjects',
            'grade12Subjects',
            'grade11Sections',
            'grade12Sections',
            'search',
            'grade',
            'section',
            'status'
        ));

        require_once "../super_admin_folder/teacher_users.php";
    }

    public function teacherRecords()
    {
        $this->teacherModel->backfillJoinCodes();

        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();

        // ── Read filter + pagination params ──
        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        // ── Total count (for pagination) ──
        $totalTeachers = $this->teacherModel->countAllTeachersFiltered($search, $grade, $section, $status);
        $totalPages = (int) ceil($totalTeachers / $limit);

        // ── Paginated teacher list ──
        $teachers = $this->teacherModel->getAllTeachersFilteredPaginated(
            $search,
            $grade,
            $section,
            $status,
            $limit,
            $offset
        );

        // Attach enrolled students to each subject
        foreach ($teachers as &$teacher) {
            foreach ($teacher['subjects'] as &$subject) {
                $subject['students'] = !empty($subject['id'])
                    ? $this->teacherModel->getEnrolledStudentsBySubject(
                        (int) $subject['id'],
                        (int) $teacher['teacher_id']
                    )
                    : [];
            }
        }
        unset($teacher, $subject);

        extract(compact(
            'teachers',
            'totalTeachers',
            'totalPages',
            'page',
            'limit',
            'offset',
            'grade11Subjects',
            'grade12Subjects',
            'grade11Sections',
            'grade12Sections',
            'search',
            'grade',
            'section',
            'status'
        ));

        require_once "../admin_folder/teacher_users.php";
    }

    public function createTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $pairs = $_POST['pairs'] ?? []; // [section_id => [subject_id, ...]]

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Name, email, and password are required.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $teacher_id = $this->teacherModel->createTeacher($name, $email, $password);

            if (!empty($pairs)) {
                $this->teacherModel->assignPairs($teacher_id, $pairs);
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Teacher created successfully.',
                'page' => 'teacher_users'
            ];
            header("Location: /learning_management/public/?url=teacher_users");
            exit;
        }
    }

    public function create_super_admin_Teacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $pairs = $_POST['pairs'] ?? []; // [section_id => [subject_id, ...]]

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Name, email, and password are required.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $teacher_id = $this->teacherModel->createTeacher($name, $email, $password);

            if (!empty($pairs)) {
                $this->teacherModel->assignPairs($teacher_id, $pairs);
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Teacher created successfully.',
                'page' => 'super_admin_teacher_users'  // ✅ this one is actually correct already
            ];
            header("Location: /learning_management/public/?url=super_admin_teacher_users");
            exit;
        }
    }

    public function addSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject_name = trim($_POST['subject_name'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);

            if (empty($subject_name) || $grade_level_id === 0) {
                $_SESSION['error'] = "Subject name and grade level are required.";
                header("Location: /learning_management/public/?url=teacher_users");
                exit;
            }

            $this->subjectModel->insertSubject($subject_name, $grade_level_id);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Subject added successfully.",
                'page' => 'teacher_users'
            ];
            header("Location: /learning_management/public/?url=teacher_users");
            exit;
        }
    }

    public function RecentStudents()
    {
        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();
        $teachers = $this->teacherModel->getAllTeachers();
        $recentStudents = $this->teacherModel->getRecentStudents(5);

        require_once "../app/view/admin.php";
    }

    public function save_grade()
    {
        $teacherModel = new Teacher();

        $submission_id = (int) ($_POST['submission_id'] ?? 0);
        $points_earned = (int) ($_POST['points_earned'] ?? 0);
        $feedback = trim($_POST['feedback'] ?? '');
        $assignment_id = (int) ($_POST['assignment_id'] ?? 0);
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $student_index = (int) ($_POST['student_index'] ?? 0);

        if ($submission_id) {
            $teacherModel->saveGrade($submission_id, $points_earned, $feedback);
        }

        header("Location: /learning_management/public/?url=works&assignment_id={$assignment_id}&subject_id={$subject_id}&student_index={$student_index}");
        exit;
    }

    public function updateTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /learning_management/public/?url=teacher_users");
            exit;
        }

        $redirectTo = $_SERVER['HTTP_REFERER']
            ?? "/learning_management/public/?url=teacher_users";

        $urlPage = 'teacher_users';
        if (str_contains($redirectTo, 'super_admin_teacher_users')) {
            $urlPage = 'super_admin_teacher_users';
        }

        $teacher_id = (int) ($_POST['teacher_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $pairs = $_POST['pairs'] ?? [];
        $teacher_status = trim($_POST['teacher_status'] ?? 'Active');

        if (!$teacher_id) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid teacher ID.', 'page' => $urlPage];
            header("Location: " . $redirectTo);
            exit;
        }

        if (empty($name) || empty($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Name and email are required.', 'page' => $urlPage];
            header("Location: " . $redirectTo);
            exit;
        }

        $this->teacherModel->updateTeacherInfo($teacher_id, $name, $email, $password);

        if ($teacher_status === 'Not Active') {
            // ✅ DELETE assignments so class_count becomes 0 → status shows "Not Active"
            $this->teacherModel->deleteTeacherAssignments($teacher_id);
            // Remove the updateTeacherStatus call — it does nothing useful
        } else {
            // Delete and reassign
            $this->teacherModel->deleteTeacherAssignments($teacher_id);
            if (!empty($pairs)) {
                $this->teacherModel->assignPairs($teacher_id, $pairs);
            }
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Teacher updated successfully.',
            'page' => $urlPage
        ];
        header("Location: " . $redirectTo);
        exit;
    }

    public function update_super_admin_Teacher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /learning_management/public/?url=teacher_users");
            exit;
        }

        $redirectTo = $_SERVER['HTTP_REFERER']
            ?? "/learning_management/public/?url=teacher_users";

        $urlPage = 'teacher_users';
        if (str_contains($redirectTo, 'super_admin_teacher_users')) {
            $urlPage = 'super_admin_teacher_users';
        }

        $teacher_id = (int) ($_POST['teacher_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $pairs = $_POST['pairs'] ?? [];
        $teacher_status = trim($_POST['teacher_status'] ?? 'Active');

        if (!$teacher_id) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid teacher ID.', 'page' => $urlPage];
            header("Location: " . $redirectTo);
            exit;
        }

        if (empty($name) || empty($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Name and email are required.', 'page' => $urlPage];
            header("Location: " . $redirectTo);
            exit;
        }

        $this->teacherModel->updateTeacherInfo($teacher_id, $name, $email, $password);

        // CURRENT (broken):
        // FIXED:
        if ($teacher_status === 'Not Active') {
            // Delete all assignments → class_count = 0 → status shows "Not Active"
            $this->teacherModel->deleteTeacherAssignments($teacher_id);
        } else {
            // Delete then reassign with new pairs
            $this->teacherModel->deleteTeacherAssignments($teacher_id);
            if (!empty($pairs)) {
                $this->teacherModel->assignPairs($teacher_id, $pairs);
            }
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Teacher updated successfully.',
            'page' => $urlPage
        ];
        header("Location: " . $redirectTo);
        exit;
    }

    public function bulk_approve_students()
    {
        header('Content-Type: application/json');
        $studentIds = $_POST['student_ids'] ?? [];

        if (empty($studentIds)) {
            echo json_encode(['success' => false, 'message' => 'No students selected.']);
            exit;
        }

        $studentIds = array_map('intval', $studentIds);
        $this->teacherModel->bulkApproveStudents($studentIds);

        echo json_encode(['success' => true]);
        exit;
    }

    public function updateStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = (int) ($_POST['student_id'] ?? 0);
            $user_id = (int) ($_POST['user_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $status = trim($_POST['status'] ?? '');
            $original_status = trim($_POST['original_status'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
            $section_id = (int) ($_POST['section_id'] ?? 0);
            $student_LRN = trim($_POST['student_LRN'] ?? '');
            $decline_reason = trim($_POST['decline_reason'] ?? '');

            $this->teacherModel->updateStudent(
                $user_id,
                $name,
                $email,
                $status,
                $grade_level_id,
                $section_id,
                $student_LRN,
                $student_id,
                $decline_reason,
                $_SESSION['user_id'] ?? null  // ← ADD THIS
            );

            if ($status === 'Approved' && $original_status !== 'Approved') {
                $message = "Student approved successfully.";
                $this->sendStatusEmail($email, $name, 'Approved');
            } elseif ($status === 'Rejected' && $original_status !== 'Rejected') {
                $message = "Student rejected successfully.";
                $this->sendStatusEmail($email, $name, 'Rejected', $decline_reason);
            } else {
                $message = "Student updated successfully.";
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $message,
                'page' => 'student_users'
            ];

            header("Location: /learning_management/public/?url=student_users");
            exit;
        }
    }

    public function update_super_admin_Student()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $student_id = (int) ($_POST['student_id'] ?? 0);
            $user_id = (int) ($_POST['user_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $status = trim($_POST['status'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
            $section_id = (int) ($_POST['section_id'] ?? 0);
            $student_LRN = trim($_POST['student_LRN'] ?? '');
            $decline_reason = trim($_POST['decline_reason'] ?? '');

            // Always use session user_id as approver
            $approver_id = (int) ($_SESSION['user_id'] ?? 0);

            // Use the model properly — no reflection hack
            require_once "../app/models/SuperAdmin.php";
            $superAdminModel = new SuperAdmin();

            $superAdminModel->updateStudentApproval(
                $student_id,
                $grade_level_id,
                $section_id,
                $student_LRN,
                $status,
                $decline_reason,
                $approver_id,   // ← always pass this
                $user_id,
                $name,
                $email
            );

            if ($status === 'Approved') {
                $message = "Student approved successfully.";
                $this->sendStatusEmail($email, $name, 'Approved');
            } elseif ($status === 'Rejected') {
                $message = "Student rejected successfully.";
                $this->sendStatusEmail($email, $name, 'Rejected', $decline_reason);
            } else {
                $message = "Student updated successfully.";
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => $message,
                'page' => 'super_admin_student_users'
            ];

            header("Location: /learning_management/public/?url=super_admin_student_users");
            exit;
        }
    }

    public function getAllPendingIds()
    {
        header('Content-Type: application/json');
        $ids = $this->teacherModel->getAllPendingStudentIds();
        echo json_encode(['ids' => $ids]);
        exit;
    }

    // ── Email helper ──────────────────────────────────────────────
    private function sendStatusEmail(string $to, string $studentName, string $status, string $reason = ''): void
    {

        $isApproved = $status === 'Approved';

        $headingText = $isApproved ? 'Account Approved' : 'Account Rejected';

        $emailSubject = $isApproved
            ? 'iLearn – Your account has been approved!'
            : 'iLearn – Your account was not approved';

        $accentColor = $isApproved ? '#00C950' : '#e53e3e';
        $iconEmoji = $isApproved ? '✅' : '❌';
        $headingText = $isApproved ? 'Account Approved' : 'Account rejected';
        $bodyText = $isApproved
            ? 'Great news! Your student account on <strong>iLearn</strong> has been <strong>approved</strong>. You can now log in and start accessing your classes and modules.'
            : 'We regret to inform you that your student account on <strong>iLearn</strong> has been <strong>rejected</strong>. You will need to create a new account to continue.';
        $ctaLabel = '';
        if ($isApproved) {
            $ctaLabel = 'Log In to iLearn';
        }
        $ctaUrl = $isApproved
            ? 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/learning_management/public/?url=login'
            : 'mailto:admin@ilearn.edu';

        // ── Reason block (only for declined + reason provided) ──
        $reasonBlock = '';
        if (!$isApproved && $reason !== '') {
            $reasonBlock = "
        <tr>
          <td style='padding: 0 36px 24px;'>
            <table cellpadding='0' cellspacing='0' width='100%'>
              <tr>
                <td style='background:#fff5f5;border:1px solid #fecdd3;border-radius:10px;padding:16px 20px;'>
                  <p style='margin:0 0 6px;font-size:11px;font-weight:700;color:#991b1b;
                             text-transform:uppercase;letter-spacing:.8px;'>
                    Reason for Rejection
                  </p>
                  <p style='margin:0;font-size:14px;color:#374151;line-height:1.6;'>
                    " . htmlspecialchars($reason) . "
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>";
        }

        $emailBody = "
    <html>
    <body style='margin:0;padding:0;background:#f0f2f5;font-family:Segoe UI,system-ui,sans-serif;'>
      <table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 20px;'>
        <tr><td align='center'>
          <table width='520' cellpadding='0' cellspacing='0'
                 style='background:#fff;border-radius:16px;overflow:hidden;
                        box-shadow:0 8px 40px rgba(0,0,0,.10);'>

            <!-- Header -->
            <tr>
              <td style='background:{$accentColor};padding:32px 36px;text-align:center;'>
                <div style='font-size:32px;'>{$iconEmoji}</div>
                <div style='font-size:22px;font-weight:800;color:#fff;margin-top:8px;'>
                  {$headingText}
                </div>
              </td>
            </tr>

            <!-- Body -->
            <tr>
              <td style='padding:32px 36px 20px;'>
                <p style='font-size:16px;color:#111827;font-weight:700;margin:0 0 6px;'>
                  Hi " . htmlspecialchars($studentName) . " 👋
                </p>
                <p style='font-size:14px;color:#6b7280;margin:0;line-height:1.6;'>
                  {$bodyText}
                </p>
              </td>
            </tr>

            <!-- Reason block (injected here if declined) -->
            {$reasonBlock}

            <!-- CTA -->
            <tr>
                <td align='center' style='padding:8px 0;'>
                    
                </td>
            </tr>
            <!-- Footer -->
            <tr>
              <td style='background:#f9fafb;border-top:1px solid #e4e7eb;
                          padding:16px 36px;text-align:center;'>
                <p style='font-size:12px;color:#9ca3af;margin:0;'>
                  &copy; " . date('Y') . " iLearn &mdash; All rights reserved.
                </p>
              </td>
            </tr>

          </table>
        </td></tr>
      </table>
    </body>
    </html>";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rogelioamoyan123@gmail.com';
            $mail->Password = 'cmdq rxjr sufp hnul';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('rogelioamoyan123@gmail.com', 'iLearn');
            $mail->addAddress($to, $studentName);
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body = $emailBody;
            $mail->AltBody = $isApproved
                ? "Your iLearn account has been approved. Log in at: {$ctaUrl}"
                : "Your iLearn account has been reject." . ($reason ? " Reason: {$reason}" : '') . " Register your account again.";

            $mail->send();

        } catch (Exception $e) {
            error_log("[iLearn] Status email failed for {$to}: " . $mail->ErrorInfo);
            error_log("[iLearn] Exception: " . $e->getMessage());
        }
    }

    public function update_due_date()
    {
        ob_start();

        header('Content-Type: application/json');

        $assignmentId = (int) ($_POST['assignment_id'] ?? 0);
        $dueDate = trim($_POST['due_date'] ?? '');
        $dueTime = trim($_POST['due_time'] ?? '23:59:00');

        if (!$assignmentId || !$dueDate) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            exit;
        }

        $result = $this->teacherModel->updateAssignmentDueDate($assignmentId, $dueDate, $dueTime);

        if ($result) {
            echo json_encode([
                'success' => true,
                'formatted' => date('M d, h:i A', strtotime($dueDate . ' ' . $dueTime))
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed.']);
        }
        exit;
    }

    // public function sendInvitation()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         header("Location: /learning_management/public/?url=classes");
    //         exit;
    //     }

    //     $teacherId = (int) ($_SESSION['teacher_id'] ?? 0);
    //     $subjectId = (int) ($_POST['subject_id'] ?? 0);
    //     $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);
    //     $sectionId = (int) ($_POST['section_id'] ?? 0);
    //     $email = trim($_POST['student_email'] ?? '');

    //     $redirectBack = "/learning_management/public/?url=class_view"
    //         . "&id={$subjectId}&grade_id={$gradeLevelId}&section_id={$sectionId}";

    //     if (!$teacherId || !$subjectId || !$gradeLevelId || !$sectionId || !$email) {
    //         $_SESSION['invite_error'] = "All fields are required.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 1. Check student exists and is Approved
    //     $student = $this->teacherModel->getApprovedStudentByEmail($email);
    //     if (!$student) {
    //         $_SESSION['invite_error'] = "No approved student found with that email.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 2. Check not already enrolled
    //     if ($this->teacherModel->isAlreadyEnrolled($student['student_id'], $subjectId, $sectionId)) {
    //         $_SESSION['invite_error'] = "This student is already enrolled in this class.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 3. Check no duplicate pending invite
    //     if ($this->teacherModel->hasPendingInvitation($email, $subjectId, $sectionId)) {
    //         $_SESSION['invite_error'] = "An invitation has already been sent to this student for this class.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 4. Create invitation token
    //     $token = $this->teacherModel->createInvitation(
    //         $teacherId,
    //         $subjectId,
    //         $gradeLevelId,
    //         $sectionId,
    //         $email,
    //         $student['student_id']
    //     );

    //     // 5. Get class info for email
    //     $classInfo = $this->teacherModel->getClassInfoForInviteModal($teacherId, $subjectId, $sectionId);
    //     $subjectName = $classInfo['subject_name'] ?? 'your class';
    //     $sectionName = $classInfo['section_name'] ?? '';
    //     $teacherName = $_SESSION['name'] ?? 'Your Teacher';

    //     $acceptUrl = "https://{$_SERVER['HTTP_HOST']}/learning_management/public/?url=accept_invite&token={$token}";

    //     // 6. Send email using PHP mail() — swap for PHPMailer/SMTP if available
    //     $to = $email;
    //     $subject = "Class Invitation: {$subjectName}";
    //     $message = "
    //     <html>
    //     <body style='font-family:Segoe UI,sans-serif;background:#f0f2f5;padding:30px;'>
    //       <div style='max-width:520px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);'>
    //         <div style='background:#00C950;padding:24px 28px;'>
    //           <h1 style='color:#fff;margin:0;font-size:20px;'>📚 iLearn Class Invitation</h1>
    //         </div>
    //         <div style='padding:28px;'>
    //           <p style='font-size:15px;color:#374151;'>Hi <strong>{$student['name']}</strong>,</p>
    //           <p style='font-size:14px;color:#6b7280;'>
    //             <strong>{$teacherName}</strong> has invited you to join:
    //           </p>
    //           <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px 20px;margin:20px 0;'>
    //             <p style='margin:0;font-size:16px;font-weight:700;color:#15803d;'>{$subjectName}</p>
    //             <p style='margin:4px 0 0;font-size:13px;color:#6b7280;'>Section: {$sectionName}</p>
    //           </div>
    //           <p style='font-size:13px;color:#6b7280;'>Click the button below to accept and enroll. This invitation expires in <strong>7 days</strong>.</p>
    //           <a href='{$acceptUrl}'
    //              style='display:inline-block;background:#00C950;color:#fff;text-decoration:none;
    //                     padding:13px 28px;border-radius:50px;font-weight:700;font-size:15px;margin:8px 0;'>
    //             ✅ Accept Invitation
    //           </a>
    //           <p style='font-size:12px;color:#9ca3af;margin-top:20px;'>
    //             If you did not expect this, you can safely ignore this email.
    //           </p>
    //         </div>
    //       </div>
    //     </body>
    //     </html>
    // ";

    //     $headers = "MIME-Version: 1.0\r\n";
    //     $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    //     $headers .= "From: iLearn <your-actual-jairabono@gmail.com>\r\n";  // ← THIS LINE

    //     $sent = mail($to, $subject, $message, $headers);

    //     if ($sent) {
    //         $_SESSION['invite_success'] = "Invitation sent to {$email}!";
    //     } else {
    //         // Still created the token, just mail failed — log it
    //         error_log("Mail failed to {$email} for token {$token}");
    //         $_SESSION['invite_success'] = "Invitation created. Email delivery may be delayed — token: {$token}";
    //     }

    //     header("Location: {$redirectBack}");
    //     exit;
    // }

    // public function acceptInvite()
    // {
    //     $token = trim($_GET['token'] ?? '');
    //     $result = ['success' => false, 'message' => 'Invalid or missing token.'];
    //     if ($token) {
    //         $result = $this->teacherModel->acceptInvitation($token);
    //     }
    //     require_once "../teacher_folder/accept_invite.php";
    // }
}