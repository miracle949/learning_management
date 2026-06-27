<?php

// require_once __DIR__ . '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once __DIR__ . '/../../vendor/autoload.php';

require_once "../app/models/User.php";
require_once "../app/models/Teacher.php";


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

        $user_id = $_SESSION['user_id'];
        $teacher_id = $this->user->getTeacherId($user_id);
        $teacherInfo = $this->user->getTeacherInfo($user_id);

        $teacherModel = new Teacher();

        // Store teacher_id in session for reuse
        $_SESSION['teacher_id'] = $teacher_id;

        $classes = $teacherModel->getTeacherClassesPerSection($teacher_id);
        $stats = $this->user->getTeacherStats($teacher_id);
        $totalStudents = $teacherModel->getEnrolledStudentsByTeacher($teacher_id);
        $submittedCount = $teacherModel->getTotalSubmittedAssignments($teacher_id);
        $upcomingAssignments = $teacherModel->getUpcomingAssignments($teacher_id);
        $teacherAnnouncements = $teacherModel->getAnnouncementsByTeacher($teacher_id);

        extract([
            'teacherInfo' => $teacherInfo,
            'classes' => $classes,
            'stats' => $stats,
            'totalStudents' => $totalStudents,
            'submittedCount' => $submittedCount,
            'upcomingAssignments' => $upcomingAssignments,
            'teacherAnnouncements' => $teacherAnnouncements,
        ]);

        require "../app/view/teacher.php";
    }

    // ============================================================
    // VIEW CLASS
    // ============================================================
    public function viewClass()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);

        $teacherModel = new Teacher();

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $classInfo = $teacherModel->getClassInfo($subject_id, $grade_level_id, $section_id);

        $cfModules = $teacherModel->getModules($subject_id, $teacher_id, $section_id);
        foreach ($cfModules as &$mod) {
            $mod['materials'] = $teacherModel->getMaterialsByModule($mod['id']);
        }
        unset($mod);

        $imModules = $teacherModel->getInteractiveModulesWithCount($subject_id, $teacher_id);
        foreach ($imModules as &$im) {
            $im['lessons'] = $teacherModel->getLessonsByModule($im['id']);
        }
        unset($im);

        $studentCount = $teacherModel->getStudentCountBySection($subject_id, $section_id);

        $totalLessons = 0;
        foreach ($imModules as $im) {
            $totalLessons += (int) $im['lesson_count'];
        }

        $enrolledStudents = $teacherModel->getEnrolledStudentsBySection($subject_id, $section_id);

        $approvedStudents = $teacherModel->getApprovedStudentsNotEnrolled($subject_id, $section_id);

        extract([
            'subject_id' => $subject_id,
            'grade_level_id' => $grade_level_id,
            'section_id' => $section_id,
            'classInfo' => $classInfo,
            'cfModules' => $cfModules,
            'imModules' => $imModules,
            'studentCount' => $studentCount,
            'totalLessons' => $totalLessons,
            'teacherModel' => $teacherModel,
            'approvedStudents' => $approvedStudents,
            'enrolledStudents' => $enrolledStudents,
        ]);

        require "../teacher_folder/records.php";
    }

    public function lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);

        $teacherModel = new Teacher();
        $classInfo = $teacherModel->getClassInfo($subject_id, $grade_level_id, $section_id);

        extract([
            'subject_id' => $subject_id,
            'grade_level_id' => $grade_level_id,
            'section_id' => $section_id,
            'classInfo' => $classInfo,
        ]);

        require "../teacher_folder/lessons.php";
    }

    public function announce()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);
        header("Location: /learning_management/public/?url=teacher_class&id=$subject_id&grade_id=$grade_level_id&section_id=$section_id");
        exit;
    }

    public function save_announcement()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $titles = $_POST['announcement_title'] ?? [];
        $messages = $_POST['announcement_message'] ?? [];

        foreach ($titles as $i => $title) {
            $title = trim($title);
            $message = trim($messages[$i] ?? '');
            if (!$title || !$message || !$subject_id || !$teacher_id)
                continue;
            $teacherModel->insertAnnouncement($subject_id, $section_id, $teacher_id, $title, $message);
            //                                              ↑ ADD THIS
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=teacher_class&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}&saved=stream");
        exit;
    }

    public function save_assignment()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $titles = $_POST['assignment_title'] ?? [];
        $descriptions = $_POST['assignment_description'] ?? [];
        $tasks = $_POST['assignment_task'] ?? [];
        $instructions = $_POST['assignment_instructions'] ?? [];
        $types = $_POST['assignment_type'] ?? [];
        $due_dates = $_POST['assignment_due_date'] ?? [];
        $due_times = $_POST['assignment_due_time'] ?? [];   // ← ADD THIS
        $points_arr = $_POST['assignment_points'] ?? [];
        $files = $_FILES['assignment_file'] ?? [];

        foreach ($titles as $i => $title) {
            if (empty(trim($title)))
                continue;

            $fileName = $filePath = $fileType = null;
            if (!empty($files['tmp_name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
                $originalName = basename($files['name'][$i]);
                $fileType = $files['type'][$i];
                $uploadDir = __DIR__ . '/../../uploads/assignments/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);
                $uniqueName = uniqid() . '_' . $originalName;
                $destPath = $uploadDir . $uniqueName;
                if (move_uploaded_file($files['tmp_name'][$i], $destPath)) {
                    $fileName = $originalName;
                    $filePath = 'uploads/assignments/' . $uniqueName;
                }
            }

            $due_time = !empty($due_times[$i]) ? $due_times[$i] . ':00' : '23:59:00';

            $teacherModel->insertAssignment(
                $subject_id,
                $section_id,          // ← ADD THIS (already in $_POST)
                $teacher_id,
                trim($types[$i] ?? 'seatwork'),
                trim($title),
                trim($descriptions[$i] ?? ''),
                trim($tasks[$i] ?? ''),
                trim($instructions[$i] ?? ''),
                trim($due_dates[$i] ?? '') ?: null,
                $due_time,
                (int) ($points_arr[$i] ?? 100),
                $fileName,
                $filePath,
                $fileType
            );
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=teacher_class&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}&saved=classwork");
        exit;
    }

    public function upload()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }
        $subject_id = (int) ($_GET['id'] ?? 0);
        $grade_level_id = (int) ($_GET['grade_id'] ?? 0);
        $section_id = (int) ($_GET['section_id'] ?? 0);
        header("Location: /learning_management/public/?url=lessons&id=$subject_id&grade_id=$grade_level_id&section_id=$section_id");
        exit;
    }

    // ============================================================
    // SEND INVITATION — teacher invites a student by email
    // ============================================================
    // public function send_invitation()
    // {
    //     if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    //         header("Location: ?url=login");
    //         exit;
    //     }

    //     $teacherModel = new Teacher();
    //     $teacherId = (int) ($_SESSION['teacher_id'] ?? 0);
    //     $subjectId = (int) ($_POST['subject_id'] ?? 0);
    //     $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);
    //     $sectionId = (int) ($_POST['section_id'] ?? 0);
    //     $email = trim($_POST['student_email'] ?? '');

    //     $redirectBack = "/learning_management/public/?url=teacher_class"
    //         . "&id={$subjectId}&grade_id={$gradeLevelId}&section_id={$sectionId}#people";

    //     // Resolve teacher_id if not yet in session
    //     if (!$teacherId) {
    //         $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
    //         $teacherId = (int) ($result['teacher_id'] ?? 0);
    //         $_SESSION['teacher_id'] = $teacherId;
    //     }

    //     // Basic validation
    //     if (!$teacherId || !$subjectId || !$gradeLevelId || !$sectionId || !$email) {
    //         $_SESSION['invite_error'] = "All fields are required.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         $_SESSION['invite_error'] = "Please enter a valid email address.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 1. Check student exists and is Approved
    //     $student = $teacherModel->getApprovedStudentByEmail($email);
    //     if (!$student) {
    //         $_SESSION['invite_error'] = "No approved student found with that email address.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 2. Check not already enrolled
    //     if ($teacherModel->isAlreadyEnrolled($student['student_id'], $subjectId, $sectionId)) {
    //         $_SESSION['invite_error'] = "This student is already enrolled in this class.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 3. Check for duplicate pending invitation
    //     if ($teacherModel->hasPendingInvitation($email, $subjectId, $sectionId)) {
    //         $_SESSION['invite_error'] = "An invitation has already been sent to this student for this class.";
    //         header("Location: {$redirectBack}");
    //         exit;
    //     }

    //     // 4. Create invitation token (7-day expiry)
    //     $token = $teacherModel->createInvitation(
    //         $teacherId,
    //         $subjectId,
    //         $gradeLevelId,
    //         $sectionId,
    //         $email,
    //         $student['student_id']
    //     );

    //     // 5. Gather class info for the email
    //     $classInfo = $teacherModel->getClassInfoForInviteModal($teacherId, $subjectId, $sectionId);
    //     $subjectName = $classInfo['subject_name'] ?? 'your class';
    //     $sectionName = $classInfo['section_name'] ?? '';
    //     $teacherName = $_SESSION['name'] ?? 'Your Teacher';

    //     $acceptUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    //         . "://{$_SERVER['HTTP_HOST']}/learning_management/public/?url=accept_invite&token={$token}";

    //     // 6. Build the HTML email
    //     $emailSubject = "Class Invitation: {$subjectName} — iLearn";
    //     $emailBody = "
    //     <html>
    //     <body style='margin:0;padding:0;background:#f0f2f5;font-family:Segoe UI,system-ui,sans-serif;'>
    //       <table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 20px;'>
    //         <tr><td align='center'>
    //           <table width='520' cellpadding='0' cellspacing='0'
    //                  style='background:#fff;border-radius:16px;overflow:hidden;
    //                         box-shadow:0 8px 40px rgba(0,0,0,.10);'>

    //             <!-- Header -->
    //             <tr>
    //               <td style='background:linear-gradient(135deg,#00C950 0%,#009e3e 100%);
    //                           padding:32px 36px;text-align:left;'>
    //                 <div style='font-size:24px;font-weight:800;color:#fff;letter-spacing:-.3px;'>
    //                   📚 iLearn
    //                 </div>
    //                 <div style='font-size:14px;color:rgba(255,255,255,.8);margin-top:4px;'>
    //                   Learning Management System
    //                 </div>
    //               </td>
    //             </tr>

    //             <!-- Body -->
    //             <tr>
    //               <td style='padding:32px 36px;'>
    //                 <p style='font-size:16px;color:#111827;font-weight:700;margin:0 0 6px;'>
    //                   Hi " . htmlspecialchars($student['name']) . " 👋
    //                 </p>
    //                 <p style='font-size:14px;color:#6b7280;margin:0 0 24px;line-height:1.6;'>
    //                   <strong style='color:#374151;'>" . htmlspecialchars($teacherName) . "</strong>
    //                   has invited you to join a class on iLearn.
    //                 </p>

    //                 <!-- Class card -->
    //                 <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;
    //                             padding:20px 24px;margin-bottom:28px;'>
    //                   <div style='font-size:18px;font-weight:800;color:#15803d;margin-bottom:6px;'>
    //                     " . htmlspecialchars($subjectName) . "
    //                   </div>
    //                   <div style='font-size:13px;color:#6b7280;'>
    //                     Section: <strong style='color:#374151;'>" . htmlspecialchars($sectionName) . "</strong>
    //                   </div>
    //                   <div style='font-size:13px;color:#6b7280;margin-top:4px;'>
    //                     Teacher: <strong style='color:#374151;'>" . htmlspecialchars($teacherName) . "</strong>
    //                   </div>
    //                 </div>

    //                 <p style='font-size:13px;color:#6b7280;margin:0 0 20px;line-height:1.6;'>
    //                   Click the button below to accept this invitation and gain access to the class.
    //                   This invitation will expire in <strong style='color:#374151;'>7 days</strong>.
    //                 </p>

    //                 <!-- CTA Button -->
    //                 <table cellpadding='0' cellspacing='0'>
    //                   <tr>
    //                     <td style='border-radius:50px;background:#00C950;
    //                                box-shadow:0 4px 20px rgba(0,201,80,.35);'>
    //                       <a href='{$acceptUrl}'
    //                          style='display:inline-block;padding:14px 36px;
    //                                 font-size:15px;font-weight:700;color:#fff;
    //                                 text-decoration:none;border-radius:50px;
    //                                 letter-spacing:.2px;'>
    //                         ✅ Accept Invitation
    //                       </a>
    //                     </td>
    //                   </tr>
    //                 </table>

    //                 <p style='font-size:12px;color:#9ca3af;margin-top:28px;line-height:1.6;'>
    //                   If you weren't expecting this invitation, you can safely ignore this email.
    //                   <br>The invitation link will expire on
    //                   <strong>" . date('F d, Y', strtotime('+7 days')) . "</strong>.
    //                 </p>
    //               </td>
    //             </tr>

    //             <!-- Footer -->
    //             <tr>
    //               <td style='background:#f9fafb;border-top:1px solid #e4e7eb;
    //                           padding:16px 36px;text-align:center;'>
    //                 <p style='font-size:12px;color:#9ca3af;margin:0;'>
    //                   iLearn Learning Management System
    //                 </p>
    //               </td>
    //             </tr>

    //           </table>
    //         </td></tr>
    //       </table>
    //     </body>
    //     </html>";

    //     $mail = new PHPMailer(true);
    //     $sent = false;

    //     try {
    //         // Server settings
    //         $mail->isSMTP();
    //         $mail->Host = 'smtp.gmail.com';
    //         $mail->SMTPAuth = true;
    //         $mail->Username = 'rogelioamoyan123@gmail.com';
    //         $mail->Password = 'yinf tvlf cnxs nfld';
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //         $mail->Port = 587;

    //         // Sender & recipient
    //         $mail->setFrom('rogelioamoyan123@gmail.com', 'iLearnSystem');  // ← fixed
    //         $mail->addAddress($email, $student['name']);

    //         // Email content
    //         $mail->isHTML(true);
    //         $mail = new PHPMailer(true);
    //         $mail->SMTPDebug = 2;
    //         $mail->Debugoutput = function ($str, $level) {
    //             error_log("SMTP DEBUG: " . $str);
    //         };
    //         $sent = false;
    //         $mail->Subject = $emailSubject;
    //         $mail->Body = $emailBody;
    //         $mail->AltBody = "You have been invited to join {$subjectName}. Visit: {$acceptUrl}";

    //         $mail->send();
    //         $sent = true;

    //     } catch (Exception $e) {
    //         // error_log("[ILearn] PHPMailer Error for {$email}: " . $mail->ErrorInfo);
    //         error_log("[iLearn] PHPMailer Error: " . $mail->ErrorInfo);
    //         error_log("[iLearn] Exception: " . $e->getMessage());
    //     }

    //     if ($sent) {
    //         $_SESSION['invite_success'] = "Invitation sent to {$email}!";
    //     } else {
    //         $_SESSION['invite_error'] = "Failed to send email to {$email}. Check server logs.";
    //     }

    //     header("Location: {$redirectBack}");
    //     exit;
    // }
    

    public function send_invitation()
    {
        // Always respond with JSON
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $teacherModel = new Teacher();
        $teacherId = (int) ($_SESSION['teacher_id'] ?? 0);
        $subjectId = (int) ($_POST['subject_id'] ?? 0);
        $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);
        $sectionId = (int) ($_POST['section_id'] ?? 0);
        $email = trim($_POST['student_email'] ?? '');

        if (!$teacherId) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacherId = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacherId;
        }

        if (!$teacherId || !$subjectId || !$gradeLevelId || !$sectionId || !$email) {
            echo json_encode(['success' => false, 'message' => 'All fields are required.']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
            exit;
        }

        $student = $teacherModel->getApprovedStudentByEmail($email);
        if (!$student) {
            echo json_encode(['success' => false, 'message' => 'No approved student found.']);
            exit;
        }

        if ($teacherModel->isAlreadyEnrolled($student['student_id'], $subjectId, $sectionId)) {
            echo json_encode(['success' => false, 'message' => 'Student already enrolled.']);
            exit;
        }

        if ($teacherModel->hasPendingInvitation($email, $subjectId, $sectionId)) {
            echo json_encode(['success' => false, 'message' => 'Invitation already sent.']);
            exit;
        }

        $token = $teacherModel->createInvitation(
            $teacherId,
            $subjectId,
            $gradeLevelId,
            $sectionId,
            $email,
            $student['student_id']
        );

        $classInfo = $teacherModel->getClassInfoForInviteModal($teacherId, $subjectId, $sectionId);
        $subjectName = $classInfo['subject_name'] ?? 'your class';
        $sectionName = $classInfo['section_name'] ?? '';
        $teacherName = $_SESSION['name'] ?? 'Your Teacher';

        $acceptUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
            . "://{$_SERVER['HTTP_HOST']}/learning_management/public/?url=accept_invite&token={$token}";

        $emailSubject = "Class Invitation: {$subjectName} — iLearn";
        $emailBody = "
    <html>
    <body style='margin:0;padding:0;background:#f0f2f5;font-family:Segoe UI,system-ui,sans-serif;'>
      <table width='100%' cellpadding='0' cellspacing='0' style='padding:40px 20px;'>
        <tr><td align='center'>
          <table width='520' cellpadding='0' cellspacing='0'
                 style='background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.10);'>
            <tr>
              <td style='background:linear-gradient(135deg,#00C950 0%,#009e3e 100%);padding:32px 36px;'>
                <div style='font-size:24px;font-weight:800;color:#fff;'>📚 iLearn</div>
                <div style='font-size:14px;color:rgba(255,255,255,.8);margin-top:4px;'>Learning Management System</div>
              </td>
            </tr>
            <tr>
              <td style='padding:32px 36px;'>
                <p style='font-size:16px;color:#111827;font-weight:700;margin:0 0 6px;'>
                  Hi " . htmlspecialchars($student['name']) . " 👋
                </p>
                <p style='font-size:14px;color:#6b7280;margin:0 0 24px;'>
                  <strong>" . htmlspecialchars($teacherName) . "</strong> has invited you to join a class on iLearn.
                </p>
                <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px 24px;margin-bottom:28px;'>
                  <div style='font-size:18px;font-weight:800;color:#15803d;'>" . htmlspecialchars($subjectName) . "</div>
                  <div style='font-size:13px;color:#6b7280;margin-top:6px;'>Section: <strong>" . htmlspecialchars($sectionName) . "</strong></div>
                  <div style='font-size:13px;color:#6b7280;margin-top:4px;'>Teacher: <strong>" . htmlspecialchars($teacherName) . "</strong></div>
                </div>
                <table cellpadding='0' cellspacing='0'>
                  <tr>
                    <td style='border-radius:50px;background:#00C950;'>
                      <a href='{$acceptUrl}' style='display:inline-block;padding:14px 36px;font-size:15px;font-weight:700;color:#fff;text-decoration:none;border-radius:50px;'>
                        ✅ Accept Invitation
                      </a>
                    </td>
                  </tr>
                </table>
                <p style='font-size:12px;color:#9ca3af;margin-top:28px;'>
                  This invitation expires on <strong>" . date('F d, Y', strtotime('+7 days')) . "</strong>.
                </p>
              </td>
            </tr>
            <tr>
              <td style='background:#f9fafb;border-top:1px solid #e4e7eb;padding:16px 36px;text-align:center;'>
                <p style='font-size:12px;color:#9ca3af;margin:0;'>iLearn Learning Management System</p>
              </td>
            </tr>
          </table>
        </td></tr>
      </table>
    </body>
    </html>";

        $mail = new PHPMailer(true);
        $sent = false;

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rogelioamoyan123@gmail.com';
            $mail->Password = 'cmdq rxjr sufp hnul';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('rogelioamoyan123@gmail.com', 'learningManagement');
            $mail->addAddress($email, $student['name']);
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body = $emailBody;
            $mail->AltBody = "You have been invited to join {$subjectName}. Visit: {$acceptUrl}";

            $mail->send();
            $sent = true;

        } catch (Exception $e) {
            error_log("[iLearn] PHPMailer Error: " . $mail->ErrorInfo);
            error_log("[iLearn] Exception: " . $e->getMessage());
        }

        echo json_encode(['success' => $sent, 'message' => $sent ? 'Sent' : $mail->ErrorInfo]);
        exit;
    }


    // ============================================================
    // ACCEPT INVITE — student clicks link in email
    // ============================================================
    public function accept_invite()
    {
        $teacherModel = new Teacher();
        $token = trim($_GET['token'] ?? '');
        $result = ['success' => false, 'message' => 'Invalid or missing token.'];

        if ($token) {
            $result = $teacherModel->acceptInvitation($token);
        }

        require "../teacher_folder/accept_invite.php";
    }

    // ============================================================
    // MODULE TEACHER
    // ============================================================
    public function module_teacher()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $gradeLevels = $teacherModel->getAllGradeLevels();
        $selectedGrade = isset($_GET['grade_id']) ? (int) $_GET['grade_id'] : 0;
        $subjects = $selectedGrade
            ? $teacherModel->getSubjectsByGradeLevel($selectedGrade)
            : $teacherModel->getAllSubjectsWithGrade();

        extract([
            'gradeLevels' => $gradeLevels,
            'selectedGrade' => $selectedGrade,
            'subjects' => $subjects,
        ]);

        require "../teacher_folder/modules_teacher.php";
    }

    public function create_module()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
        $subject = $subjectId ? $teacherModel->getSubjectWithGrade($subjectId) : null;

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        extract(['subject' => $subject, 'teacher_id' => $teacher_id]);
        require "../teacher_folder/create_module.php";
    }

    public function save_module()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        if (!$subject_id || !$teacher_id) {
            header("Location: /learning_management/public/?url=modules_teacher");
            exit;
        }

        $skipped = ['im_modules' => [], 'lessons' => []];
        $baseUpload = dirname(__DIR__, 2) . '/uploads/';
        $imageDir = $baseUpload . 'lessons/images/';
        $videoDir = $baseUpload . 'lessons/videos/';

        foreach ([$imageDir, $videoDir] as $dir) {
            if (!is_dir($dir))
                mkdir($dir, 0755, true);
        }

        $moduleTitles = $_POST['module_title'] ?? [];
        $moduleContents = $_POST['module_content'] ?? [];

        foreach ($moduleTitles as $modIdx => $modTitle) {
            if (empty(trim($modTitle)))
                continue;

            $existingIMCount = $teacherModel->countInteractiveModules($subject_id);
            $imModuleNumber = $existingIMCount + $modIdx + 1;
            $numberedIMTitle = 'Module ' . $imModuleNumber . ': ' . trim($modTitle);

            $imResult = $teacherModel->insertInteractiveModule(
                $subject_id,
                $numberedIMTitle,
                trim($moduleContents[$modIdx] ?? ''),
                $imModuleNumber,
                $teacher_id
            );
            $interactiveModuleId = $imResult['id'] ?? null;
            if (!$interactiveModuleId)
                continue;
            if ($imResult['existed'])
                $skipped['im_modules'][] = $numberedIMTitle;

            $lessonTitles = $_POST['lesson_title'][$modIdx] ?? [];
            $lessonTopics = $_POST['lesson_topic'][$modIdx] ?? [];
            $lessonContents = $_POST['lesson_content'][$modIdx] ?? [];

            foreach ($lessonTitles as $lesIdx => $lesTitle) {
                if (empty(trim($lesTitle)))
                    continue;

                $existingLesCount = $teacherModel->countLessons($interactiveModuleId);
                $lessonNumber = $existingLesCount + $lesIdx + 1;
                $numberedLesTitle = 'Lesson ' . $lessonNumber . ': ' . trim($lesTitle);

                $lesResult = $teacherModel->insertLesson(
                    $interactiveModuleId,
                    $numberedLesTitle,
                    trim($lessonTopics[$lesIdx] ?? ''),
                    trim($lessonContents[$lesIdx] ?? '')
                );
                $lessonId = $lesResult['id'] ?? null;
                if (!$lessonId)
                    continue;
                if ($lesResult['existed'])
                    $skipped['lessons'][] = $numberedLesTitle . ' (in ' . $numberedIMTitle . ')';

                // ── VIDEOS ──
                $videoTitles = $_POST['video_title'][$modIdx][$lesIdx] ?? [];
                $videoUrls = $_POST['video_url'][$modIdx][$lesIdx] ?? [];
                foreach ($videoTitles as $vIdx => $vTitle) {
                    if (empty(trim($vTitle)) || empty(trim($videoUrls[$vIdx] ?? '')))
                        continue;
                    $teacherModel->insertInteractiveContent($lessonId, 'video', [
                        'title' => trim($vTitle),
                        'file_path' => trim($videoUrls[$vIdx]),
                        'file_type' => 'url',
                    ]);
                }

                // ── IMAGES ──
                $imageFiles = $_FILES['image_file'] ?? [];
                $imageTitles = $_POST['image_title'][$modIdx][$lesIdx] ?? [];
                $fileNames = $imageFiles['name'][$modIdx][$lesIdx] ?? [];
                $fileTmps = $imageFiles['tmp_name'][$modIdx][$lesIdx] ?? [];
                $fileErrors = $imageFiles['error'][$modIdx][$lesIdx] ?? [];
                $fileSizes = $imageFiles['size'][$modIdx][$lesIdx] ?? [];

                foreach ($fileNames as $iIdx => $imgFileName) {
                    if (($fileErrors[$iIdx] ?? 1) !== UPLOAD_ERR_OK)
                        continue;
                    if (empty($imgFileName) || ($fileSizes[$iIdx] ?? 0) > 5 * 1024 * 1024)
                        continue;
                    $ext = strtolower(pathinfo($imgFileName, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        continue;
                    $uniqueName = uniqid('img_') . '.' . $ext;
                    if (move_uploaded_file($fileTmps[$iIdx], $imageDir . $uniqueName)) {
                        $teacherModel->insertInteractiveContent($lessonId, 'image', [
                            'title' => trim($imageTitles[$iIdx] ?? ''),
                            'file_path' => '/learning_management/uploads/lessons/images/' . $uniqueName,
                            'file_name' => $imgFileName,
                            'file_type' => $ext,
                        ]);
                    }
                }

                // ── ACTIVITIES ──
                $actTitles = $_POST['activity_title'][$modIdx][$lesIdx] ?? [];
                $actInstructions = $_POST['activity_instructions'][$modIdx][$lesIdx] ?? [];
                $actPoints = $_POST['activity_points'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($actTitles) as $aIdx) {
                    $aTitle = $actTitles[$aIdx];
                    if (empty(trim($aTitle)))
                        continue;
                    $qTypes = $_POST['activity_question_type'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qTexts = $_POST['activity_question_text'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qAnswers = $_POST['activity_essay_answer'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceA = $_POST['activity_choice_a'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceB = $_POST['activity_choice_b'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceC = $_POST['activity_choice_c'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qChoiceD = $_POST['activity_choice_d'][$modIdx][$lesIdx][$aIdx] ?? [];
                    $qCorrect = $_POST['activity_correct_answer'][$modIdx][$lesIdx][$aIdx] ?? [];

                    foreach ($qTexts as $qIdx => $qText) {
                        if (empty(trim($qText)))
                            continue;
                        $qType = $qTypes[$qIdx] ?? 'essay';
                        $teacherModel->insertInteractiveContent($lessonId, 'activity', [
                            'title' => trim($aTitle),
                            'instructions' => trim($actInstructions[$aIdx] ?? ''),
                            'total_points' => (int) ($actPoints[$aIdx] ?? 0),
                            'question' => trim($qText),
                            'question_type' => $qType,
                            'model_answer' => $qType === 'essay' ? (trim($qAnswers[$qIdx] ?? '') ?: null) : null,
                            'choice_a' => $qType === 'multiple_choice' ? (trim($qChoiceA[$qIdx] ?? '') ?: null) : null,
                            'choice_b' => $qType === 'multiple_choice' ? (trim($qChoiceB[$qIdx] ?? '') ?: null) : null,
                            'choice_c' => $qType === 'multiple_choice' ? (trim($qChoiceC[$qIdx] ?? '') ?: null) : null,
                            'choice_d' => $qType === 'multiple_choice' ? (trim($qChoiceD[$qIdx] ?? '') ?: null) : null,
                            'correct_ans' => $qType === 'multiple_choice' ? (strtolower($qCorrect[$qIdx] ?? 'a') ?: null) : null,
                        ]);
                    }
                }

                // ── QUIZZES ──
                $quizTitles = $_POST['quiz_title'][$modIdx][$lesIdx] ?? [];
                $quizInstruct = $_POST['quiz_instructions'][$modIdx][$lesIdx] ?? [];
                $quizPassing = $_POST['quiz_passing_score'][$modIdx][$lesIdx] ?? [];

                foreach (array_keys($quizTitles) as $qzIdx) {
                    $qzTitle = $quizTitles[$qzIdx];
                    if (empty(trim($qzTitle)))
                        continue;
                    $qqTexts = $_POST['question_text'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceA = $_POST['choice_a'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceB = $_POST['choice_b'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceC = $_POST['choice_c'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqChoiceD = $_POST['choice_d'][$modIdx][$lesIdx][$qzIdx] ?? [];
                    $qqCorrect = $_POST['correct_answer'][$modIdx][$lesIdx][$qzIdx] ?? [];

                    foreach ($qqTexts as $qqIdx => $qqText) {
                        if (empty(trim($qqText)))
                            continue;
                        $teacherModel->insertInteractiveContent($lessonId, 'quiz', [
                            'title' => trim($qzTitle),
                            'instructions' => trim($quizInstruct[$qzIdx] ?? ''),
                            'passing_score' => (int) ($quizPassing[$qzIdx] ?? 75),
                            'question' => trim($qqText),
                            'question_type' => 'multiple_choice',
                            'choice_a' => trim($qqChoiceA[$qqIdx] ?? '') ?: null,
                            'choice_b' => trim($qqChoiceB[$qqIdx] ?? '') ?: null,
                            'choice_c' => trim($qqChoiceC[$qqIdx] ?? '') ?: null,
                            'choice_d' => trim($qqChoiceD[$qqIdx] ?? '') ?: null,
                            'correct_ans' => strtolower($qqCorrect[$qqIdx] ?? 'a'),
                        ]);
                    }
                }

                // ── FLASHCARDS ──
                $fcFronts = $_POST['flashcard_front'][$modIdx][$lesIdx] ?? [];
                $fcBacks = $_POST['flashcard_back'][$modIdx][$lesIdx] ?? [];
                $fcTypes = $_POST['flashcard_type'][$modIdx][$lesIdx] ?? [];

                foreach ($fcFronts as $fcIdx => $fcFront) {
                    if (empty(trim($fcFront)) || empty(trim($fcBacks[$fcIdx] ?? '')))
                        continue;
                    $teacherModel->insertInteractiveContent($lessonId, 'flashcard', [
                        'card_type' => $fcTypes[$fcIdx] ?? 'term_definition',
                        'card_front' => trim($fcFront),
                        'card_back' => trim($fcBacks[$fcIdx]),
                    ]);
                }
            }
        }

        $hasSkipped = !empty($skipped['im_modules']) || !empty($skipped['lessons']);
        if ($hasSkipped)
            $_SESSION['save_skipped'] = $skipped;
        $_SESSION['save_success'] = true;

        header("Location: /learning_management/public/?url=modules_teacher");
        exit;
    }

    public function view_modules_teacher()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $subjectInfo = $subjectId ? $teacherModel->getSubjectWithGrade($subjectId) : null;
        $modules = $subjectId ? $teacherModel->getInteractiveModulesWithCount($subjectId) : [];

        extract([
            'subjectId' => $subjectId,
            'subjectInfo' => $subjectInfo,
            'teacherModel' => $teacherModel,
            'modules' => $modules
        ]);
        require "../teacher_folder/modules.php";
    }

    public function subject_lessons_teacher()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
        $moduleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $lessonId = isset($_GET['lesson']) ? (int) $_GET['lesson'] : 0;

        $module = $teacherModel->getModuleById($moduleId);
        $lessons = $teacherModel->getLessonsByModule($moduleId);

        if (!$lessonId && !empty($lessons)) {
            $lessonId = (int) $lessons[0]['id'];
        }

        $lesson = $lessonId ? $teacherModel->getLessonById($lessonId) : null;
        $images = $lessonId ? $teacherModel->getLessonImages($lessonId) : [];
        $videos = $lessonId ? $teacherModel->getLessonVideos($lessonId) : [];
        $flashcards = $lessonId ? $teacherModel->getLessonFlashcards($lessonId) : [];
        $activityData = $lessonId ? $teacherModel->getLessonActivityData($lessonId, 0) : [];
        $quizData = $lessonId ? $teacherModel->getLessonQuizData($lessonId, 0) : [];
        $totalLessons = count($lessons);

        $subjectInfo = $teacherModel->getSubjectWithGrade($subjectId);
        if ($module && $subjectInfo) {
            $module['subject_name'] = $subjectInfo['subject_name'];
        }

        extract([
            'subjectId' => $subjectId,
            'moduleId' => $moduleId,
            'lessonId' => $lessonId,
            'module' => $module,
            'lessons' => $lessons,
            'lesson' => $lesson,
            'images' => $images,
            'videos' => $videos,
            'flashcards' => $flashcards,
            'activityData' => $activityData,
            'quizData' => $quizData,
            'totalLessons' => $totalLessons,
        ]);

        require "../teacher_folder/subject_lessons.php";
    }

    // ============================================================
    // SAVE LESSONS — handles both classes_feed and interactive_module
    // Posted from lessons.php forms
    // ============================================================
    public function save_lessons()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subject_id = (int) ($_POST['subject_id'] ?? 0);
        $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
        $section_id = (int) ($_POST['section_id'] ?? 0);
        $save_type = trim($_POST['save_type'] ?? '');

        $teacher_id = $_SESSION['teacher_id'] ?? 0;
        if (!$teacher_id) {
            $result = $teacherModel->getTeacherIdByUserId($_SESSION['user_id'] ?? 0);
            $teacher_id = (int) ($result['teacher_id'] ?? 0);
            $_SESSION['teacher_id'] = $teacher_id;
        }

        $redirectBack = "/learning_management/public/?url=teacher_class"
            . "&id={$subject_id}&grade_id={$grade_level_id}&section_id={$section_id}";

        // ── CLASSES FEED (plain modules with optional PDF/file attachments) ──
        if ($save_type === 'classes_feed') {

            $cfTitles = $_POST['cf_module_title'] ?? [];
            $cfDescriptions = $_POST['cf_module_description'] ?? [];
            $cfFiles = $_FILES['cf_module_pdf'] ?? [];

            $uploadDir = dirname(__DIR__, 2) . '/uploads/modules/';
            if (!is_dir($uploadDir))
                mkdir($uploadDir, 0755, true);

            foreach ($cfTitles as $idx => $title) {
                $title = trim($title);
                if (!$title)
                    continue;

                $moduleId = $teacherModel->insertCFModule(
                    $subject_id,
                    $section_id,    // ← ADD THIS
                    $teacher_id,
                    $title,
                    trim($cfDescriptions[$idx] ?? '')
                );

                if (!$moduleId)
                    continue;

                // Handle multiple file attachments per module
                $fileNames = $cfFiles['name'][$idx] ?? [];
                $fileTmps = $cfFiles['tmp_name'][$idx] ?? [];
                $fileErrors = $cfFiles['error'][$idx] ?? [];
                $fileSizes = $cfFiles['size'][$idx] ?? [];

                foreach ($fileNames as $fIdx => $originalName) {
                    if (($fileErrors[$fIdx] ?? 1) !== UPLOAD_ERR_OK)
                        continue;
                    if (empty($originalName))
                        continue;
                    if (($fileSizes[$fIdx] ?? 0) > 50 * 1024 * 1024)
                        continue; // 50 MB max

                    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    $uniqueName = uniqid() . '_' . $originalName;
                    $destPath = $uploadDir . $uniqueName;

                    if (move_uploaded_file($fileTmps[$fIdx], $destPath)) {
                        $teacherModel->insertModuleMaterial(
                            $moduleId,
                            $originalName,
                            'uploads/modules/' . $uniqueName,
                            $ext,
                            $fileSizes[$fIdx] ?? 0
                        );
                    }
                }
            }

            $_SESSION['save_success'] = true;
            header("Location: {$redirectBack}&saved=stream");
            exit;
        }

        // ── INTERACTIVE MODULE (modules → lessons → videos/images/activities/quizzes/flashcards) ──
        if ($save_type === 'interactive_module') {

            $skipped = ['im_modules' => [], 'lessons' => []];
            $baseUpload = dirname(__DIR__, 2) . '/uploads/';
            $imageDir = $baseUpload . 'lessons/images/';
            $videoDir = $baseUpload . 'lessons/videos/';

            foreach ([$imageDir, $videoDir] as $dir) {
                if (!is_dir($dir))
                    mkdir($dir, 0755, true);
            }

            $moduleTitles = $_POST['module_title'] ?? [];
            $moduleContents = $_POST['module_content'] ?? [];

            foreach ($moduleTitles as $modIdx => $modTitle) {
                $modTitle = trim($modTitle);
                if (!$modTitle)
                    continue;

                $existingCount = $teacherModel->countInteractiveModules($subject_id);
                $moduleNumber = $existingCount + $modIdx + 1;
                $numberedTitle = 'Module ' . $moduleNumber . ': ' . $modTitle;

                $imResult = $teacherModel->insertInteractiveModule(
                    $subject_id,
                    $numberedTitle,
                    trim($moduleContents[$modIdx] ?? ''),
                    $moduleNumber,
                    $teacher_id
                );
                $interactiveModuleId = $imResult['id'] ?? null;
                if (!$interactiveModuleId)
                    continue;
                if ($imResult['existed'])
                    $skipped['im_modules'][] = $numberedTitle;

                $lessonTitles = $_POST['lesson_title'][$modIdx] ?? [];
                $lessonTopics = $_POST['lesson_topic'][$modIdx] ?? [];
                $lessonContents = $_POST['lesson_content'][$modIdx] ?? [];

                foreach ($lessonTitles as $lesIdx => $lesTitle) {
                    $lesTitle = trim($lesTitle);
                    if (!$lesTitle)
                        continue;

                    $existingLes = $teacherModel->countLessons($interactiveModuleId);
                    $lessonNumber = $existingLes + $lesIdx + 1;
                    $numberedLes = 'Lesson ' . $lessonNumber . ': ' . $lesTitle;

                    $lesResult = $teacherModel->insertLesson(
                        $interactiveModuleId,
                        $numberedLes,
                        trim($lessonTopics[$lesIdx] ?? ''),
                        trim($lessonContents[$lesIdx] ?? '')
                    );
                    $lessonId = $lesResult['id'] ?? null;
                    if (!$lessonId)
                        continue;
                    if ($lesResult['existed'])
                        $skipped['lessons'][] = $numberedLes . ' (in ' . $numberedTitle . ')';

                    // ── VIDEOS ──
                    $videoTitles = $_POST['video_title'][$modIdx][$lesIdx] ?? [];
                    $videoUrls = $_POST['video_url'][$modIdx][$lesIdx] ?? [];
                    foreach ($videoTitles as $vIdx => $vTitle) {
                        $vTitle = trim($vTitle);
                        $vUrl = trim($videoUrls[$vIdx] ?? '');
                        if (!$vTitle || !$vUrl)
                            continue;
                        $teacherModel->insertInteractiveContent($lessonId, 'video', [
                            'title' => $vTitle,
                            'file_path' => $vUrl,
                            'file_type' => 'url',
                        ]);
                    }

                    // ── IMAGES ──
                    $imageFiles = $_FILES['image_file'] ?? [];
                    $imageTitles = $_POST['image_title'][$modIdx][$lesIdx] ?? [];
                    $imgNames = $imageFiles['name'][$modIdx][$lesIdx] ?? [];
                    $imgTmps = $imageFiles['tmp_name'][$modIdx][$lesIdx] ?? [];
                    $imgErrors = $imageFiles['error'][$modIdx][$lesIdx] ?? [];
                    $imgSizes = $imageFiles['size'][$modIdx][$lesIdx] ?? [];

                    foreach ($imgNames as $iIdx => $imgFileName) {
                        if (($imgErrors[$iIdx] ?? 1) !== UPLOAD_ERR_OK)
                            continue;
                        if (empty($imgFileName))
                            continue;
                        if (($imgSizes[$iIdx] ?? 0) > 5 * 1024 * 1024)
                            continue;
                        $ext = strtolower(pathinfo($imgFileName, PATHINFO_EXTENSION));
                        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            continue;
                        $uniqueName = uniqid('img_') . '.' . $ext;
                        if (move_uploaded_file($imgTmps[$iIdx], $imageDir . $uniqueName)) {
                            $teacherModel->insertInteractiveContent($lessonId, 'image', [
                                'title' => trim($imageTitles[$iIdx] ?? ''),
                                'file_path' => '/learning_management/uploads/lessons/images/' . $uniqueName,
                                'file_name' => $imgFileName,
                                'file_type' => $ext,
                            ]);
                        }
                    }

                    // ── ACTIVITIES ──
                    $actTitles = $_POST['activity_title'][$modIdx][$lesIdx] ?? [];
                    $actInstructions = $_POST['activity_instructions'][$modIdx][$lesIdx] ?? [];
                    $actPoints = $_POST['activity_points'][$modIdx][$lesIdx] ?? [];

                    foreach (array_keys($actTitles) as $aIdx) {
                        $aTitle = trim($actTitles[$aIdx]);
                        if (!$aTitle)
                            continue;

                        $qTypes = $_POST['activity_question_type'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qTexts = $_POST['activity_question_text'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qAnswers = $_POST['activity_essay_answer'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qChoiceA = $_POST['activity_choice_a'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qChoiceB = $_POST['activity_choice_b'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qChoiceC = $_POST['activity_choice_c'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qChoiceD = $_POST['activity_choice_d'][$modIdx][$lesIdx][$aIdx] ?? [];
                        $qCorrect = $_POST['activity_correct_answer'][$modIdx][$lesIdx][$aIdx] ?? [];

                        foreach ($qTexts as $qIdx => $qText) {
                            $qText = trim($qText);
                            if (!$qText)
                                continue;
                            $qType = $qTypes[$qIdx] ?? 'essay';
                            $teacherModel->insertInteractiveContent($lessonId, 'activity', [
                                'title' => $aTitle,
                                'instructions' => trim($actInstructions[$aIdx] ?? ''),
                                'total_points' => (int) ($actPoints[$aIdx] ?? 0),
                                'question' => $qText,
                                'question_type' => $qType,
                                'model_answer' => $qType === 'essay'
                                    ? (trim($qAnswers[$qIdx] ?? '') ?: null) : null,
                                'choice_a' => $qType === 'multiple_choice'
                                    ? (trim($qChoiceA[$qIdx] ?? '') ?: null) : null,
                                'choice_b' => $qType === 'multiple_choice'
                                    ? (trim($qChoiceB[$qIdx] ?? '') ?: null) : null,
                                'choice_c' => $qType === 'multiple_choice'
                                    ? (trim($qChoiceC[$qIdx] ?? '') ?: null) : null,
                                'choice_d' => $qType === 'multiple_choice'
                                    ? (trim($qChoiceD[$qIdx] ?? '') ?: null) : null,
                                'correct_ans' => $qType === 'multiple_choice'
                                    ? (strtolower($qCorrect[$qIdx] ?? 'a') ?: null) : null,
                            ]);
                        }
                    }

                    // ── QUIZZES ──
                    $quizTitles = $_POST['quiz_title'][$modIdx][$lesIdx] ?? [];
                    $quizInstruct = $_POST['quiz_instructions'][$modIdx][$lesIdx] ?? [];
                    $quizPassing = $_POST['quiz_passing_score'][$modIdx][$lesIdx] ?? [];

                    foreach (array_keys($quizTitles) as $qzIdx) {
                        $qzTitle = trim($quizTitles[$qzIdx]);
                        if (!$qzTitle)
                            continue;

                        $qqTexts = $_POST['question_text'][$modIdx][$lesIdx][$qzIdx] ?? [];
                        $qqChoiceA = $_POST['choice_a'][$modIdx][$lesIdx][$qzIdx] ?? [];
                        $qqChoiceB = $_POST['choice_b'][$modIdx][$lesIdx][$qzIdx] ?? [];
                        $qqChoiceC = $_POST['choice_c'][$modIdx][$lesIdx][$qzIdx] ?? [];
                        $qqChoiceD = $_POST['choice_d'][$modIdx][$lesIdx][$qzIdx] ?? [];
                        $qqCorrect = $_POST['correct_answer'][$modIdx][$lesIdx][$qzIdx] ?? [];

                        foreach ($qqTexts as $qqIdx => $qqText) {
                            $qqText = trim($qqText);
                            if (!$qqText)
                                continue;
                            $teacherModel->insertInteractiveContent($lessonId, 'quiz', [
                                'title' => $qzTitle,
                                'instructions' => trim($quizInstruct[$qzIdx] ?? ''),
                                'passing_score' => (int) ($quizPassing[$qzIdx] ?? 75),
                                'question' => $qqText,
                                'question_type' => 'multiple_choice',
                                'choice_a' => trim($qqChoiceA[$qqIdx] ?? '') ?: null,
                                'choice_b' => trim($qqChoiceB[$qqIdx] ?? '') ?: null,
                                'choice_c' => trim($qqChoiceC[$qqIdx] ?? '') ?: null,
                                'choice_d' => trim($qqChoiceD[$qqIdx] ?? '') ?: null,
                                'correct_ans' => strtolower($qqCorrect[$qqIdx] ?? 'a'),
                            ]);
                        }
                    }

                    // ── FLASHCARDS ──
                    $fcFronts = $_POST['flashcard_front'][$modIdx][$lesIdx] ?? [];
                    $fcBacks = $_POST['flashcard_back'][$modIdx][$lesIdx] ?? [];
                    $fcTypes = $_POST['flashcard_type'][$modIdx][$lesIdx] ?? [];

                    foreach ($fcFronts as $fcIdx => $fcFront) {
                        $fcFront = trim($fcFront);
                        $fcBack = trim($fcBacks[$fcIdx] ?? '');
                        if (!$fcFront || !$fcBack)
                            continue;
                        $teacherModel->insertInteractiveContent($lessonId, 'flashcard', [
                            'card_type' => $fcTypes[$fcIdx] ?? 'term_definition',
                            'card_front' => $fcFront,
                            'card_back' => $fcBack,
                        ]);
                    }
                }
            }

            $hasSkipped = !empty($skipped['im_modules']) || !empty($skipped['lessons']);
            if ($hasSkipped)
                $_SESSION['save_skipped'] = $skipped;
            $_SESSION['save_success'] = true;

            header("Location: {$redirectBack}");
            exit;
        }

        // Fallback — unknown save_type
        header("Location: {$redirectBack}&saved=stream");
        exit;
    }

    public function view_modules_admin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;

        // Super admin views ALL modules regardless of teacher
        $subjectInfo = $subjectId ? $teacherModel->getSubjectWithGrade($subjectId) : null;
        $modules = $subjectId ? $teacherModel->getInteractiveModulesWithCount($subjectId) : [];

        extract([
            'subjectId' => $subjectId,
            'subjectInfo' => $subjectInfo,
            'modules' => $modules,
            'teacherModel' => $teacherModel,   // needed inside module.php for getLessonsByModule()
        ]);

        require "../super_admin_folder/module.php";
    }

    // ── subject_lessons_admin ───────────────────────────────────
// Mirrors subject_lessons_teacher.
// Route: ?url=subject_lessons_admin&subject_id=X&id=MODULE_ID&lesson=LESSON_ID
// View:  ../super_admin_folder/lessons.php
// ────────────────────────────────────────────────────────────
    public function subject_lessons_admin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $teacherModel = new Teacher();
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
        $moduleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $lessonId = isset($_GET['lesson']) ? (int) $_GET['lesson'] : 0;

        $module = $teacherModel->getModuleById($moduleId);
        $lessons = $teacherModel->getLessonsByModule($moduleId);

        // Default to first lesson if none specified
        if (!$lessonId && !empty($lessons)) {
            $lessonId = (int) $lessons[0]['id'];
        }

        $lesson = $lessonId ? $teacherModel->getLessonById($lessonId) : null;
        $images = $lessonId ? $teacherModel->getLessonImages($lessonId) : [];
        $videos = $lessonId ? $teacherModel->getLessonVideos($lessonId) : [];
        $flashcards = $lessonId ? $teacherModel->getLessonFlashcards($lessonId) : [];
        $activityData = $lessonId ? $teacherModel->getLessonActivityData($lessonId, 0) : [];
        $quizData = $lessonId ? $teacherModel->getLessonQuizData($lessonId, 0) : [];
        $totalLessons = count($lessons);

        // Attach subject name to module array
        $subjectInfo = $teacherModel->getSubjectWithGrade($subjectId);
        if ($module && $subjectInfo) {
            $module['subject_name'] = $subjectInfo['subject_name'];
        }

        extract([
            'subjectId' => $subjectId,
            'moduleId' => $moduleId,
            'lessonId' => $lessonId,
            'module' => $module,
            'lessons' => $lessons,
            'lesson' => $lesson,
            'images' => $images,
            'videos' => $videos,
            'flashcards' => $flashcards,
            'activityData' => $activityData,
            'quizData' => $quizData,
            'totalLessons' => $totalLessons,
        ]);

        require "../super_admin_folder/subject_lessons.php";
    }
}