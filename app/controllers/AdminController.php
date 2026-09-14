<?php

require_once "../core/Model.php";
require_once "../app/models/Admin.php";
require_once "../app/models/Grade_level.php";
require_once "../app/models/subjects.php";

class AdminController
{
    public $adminModel, $subjectModel, $gradeLevel;

    public function __construct()
    {
        $this->adminModel = new Admin();
        $this->subjectModel = new subjects();
        $this->gradeLevel = new Grade_level();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?url=login");
            exit;
        }

        $totalStudents = $this->adminModel->getTotalStudents();
        $totalTeachers = $this->adminModel->getTotalTeachers();
        $totalSubjects = $this->adminModel->getTotalSubjects();
        $totalSections = $this->adminModel->getTotalSections();

        $masterlistStatus = $this->adminModel->getMasterlistStatusCounts();
        $pendingMasterlistCount = $masterlistStatus['pending'];
        $enrolledMasterlistCount = $masterlistStatus['enrolled'];
        $pendingMasterlist = $this->adminModel->getRecentPendingMasterlist(5);

        $recentEnrollments = $this->adminModel->getRecentEnrollments(5);
        $announcements = $this->adminModel->getRecentAnnouncements(5);
        $teacherWorkload = $this->adminModel->getTeacherWorkload();
        $enrollmentByGrade = $this->adminModel->getEnrollmentByGrade();
        $activityLogs = $this->adminModel->getActivityLogs(15); // ← NEW

        extract([
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalSubjects' => $totalSubjects,
            'totalSections' => $totalSections,
            'pendingMasterlistCount' => $pendingMasterlistCount,
            'enrolledMasterlistCount' => $enrolledMasterlistCount,
            'pendingMasterlist' => $pendingMasterlist,
            'recentEnrollments' => $recentEnrollments,
            'announcements' => $announcements,
            'teacherWorkload' => $teacherWorkload,
            'enrollmentByGrade' => $enrollmentByGrade,
            'activityLogs' => $activityLogs, // ← NEW
        ]);

        require "../app/view/admin.php";
    }

    // ============================================================
    // TEACHERS (Admin side)
    // ============================================================
    public function teacherRecords()
    {
        $this->adminModel->backfillJoinCodes();

        $grade11Subjects = $this->subjectModel->getGrade11Subjects();
        $grade12Subjects = $this->subjectModel->getGrade12Subjects();
        $grade11Sections = $this->gradeLevel->getGrade11Sections();
        $grade12Sections = $this->gradeLevel->getGrade12Sections();

        $teacherStats = $this->adminModel->getTeacherStatusCounts();

        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalTeachers = $this->adminModel->countAllTeachersFiltered($search, $grade, $section, $status);
        $totalPages = (int) ceil($totalTeachers / $limit);

        $teachers = $this->adminModel->getAllTeachersFilteredPaginated(
            $search,
            $grade,
            $section,
            $status,
            $limit,
            $offset
        );

        foreach ($teachers as &$teacher) {
            foreach ($teacher['subjects'] as &$subject) {
                $subject['students'] = !empty($subject['id'])
                    ? $this->adminModel->getEnrolledStudentsBySubject(
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
            'status',
            'teacherStats' // NEW
        ));

        require_once "../admin_folder/teacher_users.php";
    }

    public function createTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = '12345678'; // default password shown in the create-teacher modal
            $pairs = $_POST['pairs'] ?? [];

            if (empty($name) || empty($username)) {
                $_SESSION['error'] = "Name and username are required.";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $teacher_id = $this->adminModel->createTeacher($name, $username, $password);

            if (!empty($pairs)) {
                $this->adminModel->assignPairs($teacher_id, $pairs);
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

    public function updateTeacher()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /learning_management/public/?url=teacher_users");
            exit;
        }

        $redirectTo = $_SERVER['HTTP_REFERER'] ?? "/learning_management/public/?url=teacher_users";
        $urlPage = 'teacher_users';

        $teacher_id = (int) ($_POST['teacher_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $pairs = $_POST['pairs'] ?? [];
        $teacher_status = trim($_POST['teacher_status'] ?? 'Active');

        if (!$teacher_id) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid teacher ID.', 'page' => $urlPage];
            header("Location: " . $redirectTo);
            exit;
        }

        if (empty($name) || empty($username)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Name and username are required.', 'page' => $urlPage];
            header("Location: " . $redirectTo);
            exit;
        }

        $this->adminModel->updateTeacherInfo($teacher_id, $name, $username);

        $this->adminModel->deleteTeacherAssignments($teacher_id);
        if ($teacher_status !== 'Not Active' && !empty($pairs)) {
            $this->adminModel->assignPairs($teacher_id, $pairs);
        }

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Teacher updated successfully.',
            'page' => $urlPage
        ];
        header("Location: " . $redirectTo);
        exit;
    }

    private function uploadSubjectImage(): ?string
    {
        $file = $_FILES['subject_image'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] <= 0) {
            return null;
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return null;
        }
        $uploadDir = dirname(__DIR__, 2) . '/uploads/subjects/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uniqueName = uniqid('subj_') . '.' . $ext;
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $uniqueName)) {
            return 'uploads/subjects/' . $uniqueName;
        }
        return null;
    }

    public function addSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject_name = trim($_POST['subject_name'] ?? '');
            $subject_description = trim($_POST['subject_description'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
            $curriculum_type = ($_POST['curriculum_type'] ?? 'new') === 'legacy' ? 'legacy' : 'new';
            $strand_group = trim($_POST['strand_group'] ?? '');
            $redirectCurriculum = ($_POST['redirect_curriculum'] ?? $curriculum_type) === 'legacy' ? 'legacy' : 'new';

            $redirectTo = "/learning_management/public/?url=Adminsubjects&curriculum=" . $redirectCurriculum;

            if (empty($subject_name) || $grade_level_id === 0) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Subject name and grade level are required.',
                    'page' => 'Adminsubjects'
                ];
                header("Location: " . $redirectTo);
                exit;
            }

            $imagePath = $this->uploadSubjectImage();

            $this->subjectModel->insertSubject($subject_name, $grade_level_id, $subject_description, $imagePath, $curriculum_type, $strand_group);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Subject added successfully.",
                'page' => 'Adminsubjects'
            ];
            header("Location: " . $redirectTo);
            exit;
        }
    }

    // ============================================================
    // REPORTS (Admin side)
    // ============================================================
    public function reportsPage()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?url=login");
            exit;
        }

        $isAjax = ($_GET['ajax'] ?? '') === '1';

        // ---- Report type(s): now multi-select ----
        // Accepts report_type[] as an array from the checkbox dropdown, but also
        // tolerates a legacy single report_type string for old bookmarked links.
        $rawReportTypes = $_GET['report_type'] ?? ['all'];
        if (!is_array($rawReportTypes)) {
            $rawReportTypes = [$rawReportTypes];
        }
        $validTypes = ['students', 'masterlist', 'teachers', 'sections', 'subjects'];
        $reportTypes = array_values(array_intersect($rawReportTypes, array_merge(['all'], $validTypes)));
        if (empty($reportTypes)) {
            $reportTypes = ['all'];
        }
        $showAll = in_array('all', $reportTypes, true);

        // ADD THIS LINE:
        $reportTypeProvided = isset($_GET['report_type']);

        $period = trim($_GET['period'] ?? 'daily'); // daily | monthly

        // ---- Date handling ----
        // Fields start blank; a date (daily) or a full From/To range (monthly)
        // is now REQUIRED before Generate/Export will actually run.
        if ($period === 'monthly') {
            $dateFrom = trim($_GET['date_from'] ?? '');
            $dateTo = trim($_GET['date_to'] ?? '');
            if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
                [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
            }
            $date = '';
            $dateProvided = $dateFrom !== '' && $dateTo !== '';
        } else {
            $date = trim($_GET['date'] ?? '');
            $dateFrom = $date;
            $dateTo = $date;
            $dateProvided = $date !== '';
        }

        // AJAX (Generate Reports / Export CSV) must always include the required date.
        // This backstops the frontend's disabled-button check.
        if ($isAjax && !$dateProvided) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $period === 'monthly'
                    ? 'Please select both a From and To date before generating the report.'
                    : 'Please select a date before generating the report.',
            ]);
            exit;
        }

        // ---- Students: Enrolled (by period — SY / date context) ----
        $students = $this->adminModel->getAllStudentsFiltered(1000, 0, '', '', '', '', $dateFrom, $dateTo);
        $totalStudents = $this->adminModel->countAllStudentsFiltered('', '', '', '', $dateFrom, $dateTo);

        // ---- Students: Masterlist (by period) ----
        $masterlist = $this->adminModel->getMasterlistFiltered(1000, 0, '', '', '', '', '', '', $dateFrom, $dateTo);
        $totalMasterlist = $this->adminModel->countMasterlistFiltered('', '', '', '', '', '', $dateFrom, $dateTo);

        $mlTotalEnrolled = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'enrolled');
        $mlTotalPending = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'pending');
        $mlSchoolYears = $this->adminModel->getDistinctMasterlistSchoolYears();
        $currentSchoolYear = $mlSchoolYears[0] ?? '—';

        // ---- Teachers (current structure) ----
        $teachers = $this->adminModel->getAllTeachersFilteredPaginated('', '', '', '', 1000, 0);
        $totalTeachers = $this->adminModel->countAllTeachersFiltered('', '', '', '');
        $teacherStats = $this->adminModel->getTeacherStatusCounts();

        // ---- Sections (current structure) ----
        $sections = $this->adminModel->getAllSectionsWithDetails('', '', 1000, 0);
        $totalSections = $this->adminModel->countAllSectionsFiltered('', '');
        $sectionStats = $this->adminModel->getSectionStats();

        // ---- Subjects (current structure) ----
        $subjects = $this->subjectModel->getAllSubjectsFiltered('', '', 1000, 0);
        $totalSubjects = $this->subjectModel->countAllSubjectsFiltered('', '');
        $subjectStats = $this->subjectModel->getSubjectStats();

        $visible = function (string $key) use ($showAll, $reportTypes) {
            return $showAll || in_array($key, $reportTypes, true);
        };

        // ---- Students ----
        if ($visible('students')) {
            $students = $this->adminModel->getAllStudentsFiltered(1000, 0, '', '', '', '', $dateFrom, $dateTo);
            $totalStudents = $this->adminModel->countAllStudentsFiltered('', '', '', '', $dateFrom, $dateTo);
        } else {
            $students = [];
            $totalStudents = 0;
        }

        // ---- Masterlist ----
        if ($visible('masterlist')) {
            $masterlist = $this->adminModel->getMasterlistFiltered(1000, 0, '', '', '', '', '', '', $dateFrom, $dateTo);
            $totalMasterlist = $this->adminModel->countMasterlistFiltered('', '', '', '', '', '', $dateFrom, $dateTo);
            $mlTotalEnrolled = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'enrolled', $dateFrom, $dateTo);
            $mlTotalPending = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'pending', $dateFrom, $dateTo);
        } else {
            $masterlist = [];
            $totalMasterlist = $mlTotalEnrolled = $mlTotalPending = 0;
        }

        // ---- Teachers ----
        if ($visible('teachers')) {
            $teachers = $this->adminModel->getAllTeachersFilteredPaginated('', '', '', '', 1000, 0);
            $totalTeachers = $this->adminModel->countAllTeachersFiltered('', '', '', '');
        } else {
            $teachers = [];
            $totalTeachers = 0;
        }

        // ---- Sections ----
        if ($visible('sections')) {
            $sections = $this->adminModel->getAllSectionsWithDetails('', '', 1000, 0);
            $totalSections = $this->adminModel->countAllSectionsFiltered('', '');
        } else {
            $sections = [];
            $totalSections = 0;
        }

        // ---- Subjects ----
        if ($visible('subjects')) {
            $subjects = $this->subjectModel->getAllSubjectsFiltered('', '', 1000, 0);
            $totalSubjects = $this->subjectModel->countAllSubjectsFiltered('', '');
        } else {
            $subjects = [];
            $totalSubjects = 0;
        }

        if ($isAjax) {
            header('Content-Type: application/json');

            echo json_encode([
                'success' => true,
                'reportTypes' => $reportTypes,
                'period' => $period,
                'sections' => [
                    'students' => [
                        'visible' => $visible('students'),
                        'total' => (int) $totalStudents,
                        'school_year' => $currentSchoolYear,
                        'rows_html' => $this->renderReportStudentsRowsHtml($students, $currentSchoolYear),
                    ],
                    'masterlist' => [
                        'visible' => $visible('masterlist'),
                        'total' => (int) $totalMasterlist,
                        'enrolled' => (int) $mlTotalEnrolled,
                        'pending' => (int) $mlTotalPending,
                        'school_year' => $currentSchoolYear,
                        'rows_html' => $this->renderMasterlistRowsHtml($masterlist),
                    ],
                    'teachers' => [
                        'visible' => $visible('teachers'),
                        'total' => (int) $totalTeachers,
                        'rows_html' => $this->renderReportTeachersRowsHtml($teachers),
                    ],
                    'sections' => [
                        'visible' => $visible('sections'),
                        'total' => (int) $totalSections,
                        'rows_html' => $this->renderReportSectionsRowsHtml($sections),
                    ],
                    'subjects' => [
                        'visible' => $visible('subjects'),
                        'total' => (int) $totalSubjects,
                        'rows_html' => $this->renderReportSubjectsRowsHtml($subjects),
                    ],
                ],
            ]);
            exit;
        }

        extract(compact(
            'date',
            'dateFrom',
            'dateTo',
            'reportTypes',
            'showAll',
            'reportTypeProvided',
            'period',
            'students',
            'totalStudents',
            'masterlist',
            'totalMasterlist',
            'mlTotalEnrolled',
            'mlTotalPending',
            'currentSchoolYear',
            'teachers',
            'totalTeachers',
            'teacherStats',
            'sections',
            'totalSections',
            'sectionStats',
            'subjects',
            'totalSubjects',
            'subjectStats'
        ));

        require "../admin_folder/reports.php";
    }

    private function renderReportStudentsRowsHtml(array $students, string $currentSchoolYear): string
    {
        if (empty($students)) {
            return '<tr><td colspan="6" class="text-center text-muted py-3">No enrolled students found.</td></tr>';
        }

        $html = '';
        foreach ($students as $s) {
            $dateEnrolled = !empty($s['date_enrolled']) ? date('M j, Y', strtotime($s['date_enrolled'])) : '-';
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($s['student_LRN']) . '</td>';
            $html .= '<td>' . htmlspecialchars($s['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($s['grade_level']) . '</td>';
            $html .= '<td>' . htmlspecialchars($s['section_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($currentSchoolYear) . '</td>';
            $html .= '<td>' . htmlspecialchars($dateEnrolled) . '</td>';
            $html .= '</tr>';
        }
        return $html;
    }

    private function renderReportTeachersRowsHtml(array $teachers): string
    {
        if (empty($teachers)) {
            return '<tr><td colspan="5" class="text-center text-muted py-3">No teachers found.</td></tr>';
        }

        $html = '';
        foreach ($teachers as $t) {
            $gradeLevels = !empty($t['grade_levels']) ? implode(', ', (array) $t['grade_levels']) : '-';
            $sectionNames = !empty($t['sections']) ? implode(', ', (array) $t['sections']) : '-';
            $status = $t['status'] ?? ($t['teacher_status'] ?? 'Active');
            $statusClass = strtolower($status) === 'active' ? 'enrolled' : 'pending';

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($t['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($t['username']) . '</td>';
            $html .= '<td>' . htmlspecialchars($gradeLevels) . '</td>';
            $html .= '<td>' . htmlspecialchars($sectionNames) . '</td>';
            $html .= '<td><span class="status-chip ' . $statusClass . '">' . htmlspecialchars($status) . '</span></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    private function renderReportSectionsRowsHtml(array $sections): string
    {
        if (empty($sections)) {
            return '<tr><td colspan="4" class="text-center text-muted py-3">No sections found.</td></tr>';
        }

        $html = '';
        foreach ($sections as $sec) {
            $teacherNames = !empty($sec['teacher_names']) ? $sec['teacher_names'] : null;
            $studentCount = (int) ($sec['student_count'] ?? 0);
            $statusClass = $studentCount > 0 ? 'enrolled' : 'pending';

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($sec['section_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($sec['grade_level']) . '</td>';
            $html .= '<td>' . ($teacherNames
                ? htmlspecialchars($teacherNames)
                : '<span class="text-muted fst-italic">No teacher assigned yet</span>') . '</td>';
            $html .= '<td><span class="status-chip ' . $statusClass . '">' . $studentCount . ' students</span></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    private function renderReportSubjectsRowsHtml(array $subjects): string
    {
        if (empty($subjects)) {
            return '<tr><td colspan="3" class="text-center text-muted py-3">No subjects found.</td></tr>';
        }

        $html = '';
        foreach ($subjects as $subj) {
            $teacherCount = (int) ($subj['teacher_count'] ?? 0);
            $statusClass = $teacherCount > 0 ? 'enrolled' : 'pending';
            $label = $teacherCount . ' teacher' . ($teacherCount === 1 ? '' : 's');

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($subj['subject_name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($subj['grade_level']) . '</td>';
            $html .= '<td><span class="status-chip ' . $statusClass . '">' . $label . '</span></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    // ============================================================
    // STUDENTS (Admin side)
    // ============================================================
    public function studentRecords()
    {
        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $students = $this->adminModel->getAllStudentsFiltered($limit, $offset, $search, $grade, $section, $status);
        $totalStudents = $this->adminModel->countAllStudentsFiltered($search, $grade, $section, $status);
        $totalPages = ceil($totalStudents / $limit);
        $gradeLevels = $this->adminModel->getAllGradeLevels();
        $allSections = $this->adminModel->getAllSections();

        $totalPending = $this->adminModel->countStudentsByStatus('Pending');

        $totalGrade12 = $this->adminModel->countAllStudentsFiltered('', 'grade 12', '', '');
        $totalGrade11 = $this->adminModel->countAllStudentsFiltered('', 'grade 11', '', '');

        $mlSearch = trim($_GET['ml_search'] ?? '');
        $mlGrade = trim($_GET['ml_grade'] ?? '');
        $mlSection = trim($_GET['ml_section'] ?? '');
        $mlStrand = trim($_GET['ml_strand'] ?? '');
        $mlSchoolYear = trim($_GET['ml_school_year'] ?? '');
        $mlStatus = trim($_GET['ml_status'] ?? '');
        $mlPage = max(1, (int) ($_GET['ml_page'] ?? 1));
        $mlLimit = 20;
        $mlOffset = ($mlPage - 1) * $mlLimit;

        $masterlist = $this->adminModel->getMasterlistFiltered($mlLimit, $mlOffset, $mlSearch, $mlGrade, $mlSection, $mlStrand, $mlSchoolYear, $mlStatus);
        $totalMasterlist = $this->adminModel->countMasterlistFiltered($mlSearch, $mlGrade, $mlSection, $mlStrand, $mlSchoolYear, $mlStatus);
        $totalMlPages = ceil($totalMasterlist / $mlLimit);

        $mlStrands = $this->adminModel->getDistinctMasterlistStrands();
        $mlSchoolYears = $this->adminModel->getDistinctMasterlistSchoolYears();

        $mlTotalEnrolled = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'enrolled');
        $mlTotalPending = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'pending');

        $activeTab = ($_GET['active_tab'] ?? '') === 'masterlist' ? 'masterlist' : 'enrolled';

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
            'masterlist',
            'totalMasterlist',
            'totalMlPages',
            'mlPage',
            'mlLimit',
            'mlOffset',
            'mlSearch',
            'mlGrade',
            'mlSection',
            'mlStrand',
            'mlSchoolYear',
            'mlStatus',
            'mlStrands',
            'mlSchoolYears',
            'mlTotalEnrolled',
            'mlTotalPending',
            'activeTab',
            'totalGrade12',
            'totalGrade11'
        ));
        require "../admin_folder/student_users.php";
    }

    public function studentRecordsAjax()
    {
        header('Content-Type: application/json');

        $tab = trim($_GET['tab'] ?? 'enrolled');

        if ($tab === 'masterlist') {
            $mlSearch = trim($_GET['ml_search'] ?? '');
            $mlGrade = trim($_GET['ml_grade'] ?? '');
            $mlSection = trim($_GET['ml_section'] ?? '');
            $mlStrand = trim($_GET['ml_strand'] ?? '');
            $mlSchoolYear = trim($_GET['ml_school_year'] ?? '');
            $mlStatus = trim($_GET['ml_status'] ?? '');
            $mlPage = max(1, (int) ($_GET['ml_page'] ?? 1));
            $mlLimit = 20;
            $mlOffset = ($mlPage - 1) * $mlLimit;

            $masterlist = $this->adminModel->getMasterlistFiltered(
                $mlLimit,
                $mlOffset,
                $mlSearch,
                $mlGrade,
                $mlSection,
                $mlStrand,
                $mlSchoolYear,
                $mlStatus
            );
            $totalMasterlist = $this->adminModel->countMasterlistFiltered(
                $mlSearch,
                $mlGrade,
                $mlSection,
                $mlStrand,
                $mlSchoolYear,
                $mlStatus
            );
            $totalMlPages = (int) ceil($totalMasterlist / $mlLimit);

            $mlTotalEnrolled = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'enrolled');
            $mlTotalPending = $this->adminModel->countMasterlistFiltered('', '', '', '', '', 'pending');
            $mlTotalAll = $this->adminModel->countMasterlistFiltered('', '', '', '', '', '');

            echo json_encode([
                'success' => true,
                'rows_html' => $this->renderMasterlistRowsHtml($masterlist),
                'pagination_html' => $this->renderMasterlistPaginationHtml($mlPage, $mlLimit, $mlOffset, $totalMasterlist, $totalMlPages),
                'total' => (int) $totalMasterlist,
                'stats' => [
                    'total' => (int) $mlTotalAll,
                    'enrolled' => (int) $mlTotalEnrolled,
                    'pending' => (int) $mlTotalPending,
                ],
            ]);
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $students = $this->adminModel->getAllStudentsFiltered($limit, $offset, $search, $grade, $section, $status);
        $totalStudents = $this->adminModel->countAllStudentsFiltered($search, $grade, $section, $status);
        $totalPages = (int) ceil($totalStudents / $limit);

        echo json_encode([
            'success' => true,
            'rows_html' => $this->renderEnrolledRowsHtml($students),
            'pagination_html' => $this->renderEnrolledPaginationHtml($page, $limit, $offset, $totalStudents, $totalPages),
            'total' => (int) $totalStudents,
        ]);
        exit;
    }

    private function renderEnrolledRowsHtml(array $students): string
    {
        if (empty($students)) {
            return '<tr><td colspan="6" class="text-center text-muted py-3">No students found.</td></tr>';
        }

        $html = '';
        foreach ($students as $student) {
            $html .= '<tr class="students-data"'
                . ' data-lrn="' . htmlspecialchars(strtolower($student['student_LRN'])) . '"'
                . ' data-name="' . htmlspecialchars(strtolower($student['name'])) . '"'
                . ' data-grade="' . htmlspecialchars(strtolower($student['grade_level'])) . '"'
                . ' data-section="' . htmlspecialchars(strtolower($student['section_name'])) . '">';
            $html .= '<td>' . htmlspecialchars($student['student_LRN']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['grade_level']) . '</td>';
            $html .= '<td>' . htmlspecialchars($student['section_name']) . '</td>';
            $html .= '<td><button class="btn btn-sm btn-outline-secondary btn-edit-student"'
                . ' data-student-id="' . (int) $student['student_id'] . '"'
                . ' data-user-id="' . (int) $student['user_id'] . '"'
                . ' data-name="' . htmlspecialchars($student['name'], ENT_QUOTES) . '"'
                . ' data-grade-level-id="' . (int) $student['grade_level_id'] . '"'
                . ' data-section-id="' . (int) $student['section_id'] . '"'
                . ' data-lrn="' . htmlspecialchars($student['student_LRN'], ENT_QUOTES) . '">'
                . '<i class="fa fa-edit"></i></button></td>';
            $html .= '</tr>';
        }
        return $html;
    }

    private function renderEnrolledPaginationHtml(int $page, int $limit, int $offset, int $totalStudents, int $totalPages): string
    {
        if ($totalPages <= 1) {
            return '';
        }

        $showing = min($offset + $limit, $totalStudents);
        $html = '<div class="pagination-parent">';
        $html .= '<small class="text-muted">Showing ' . $showing . ' of ' . $totalStudents . ' students</small>';
        $html .= '<ul class="pagination">';

        $prevDisabled = $page <= 1 ? ' disabled' : '';
        $html .= '<li class="page-item' . $prevDisabled . '"><a class="page-link ajax-page-link" href="javascript:void(0)" data-page="' . ($page - 1) . '"><i class="fa fa-chevron-left"></i></a></li>';

        for ($i = 1; $i <= $totalPages; $i++) {
            $active = $i === $page ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link ajax-page-link" href="javascript:void(0)" data-page="' . $i . '">' . $i . '</a></li>';
        }

        $nextDisabled = $page >= $totalPages ? ' disabled' : '';
        $html .= '<li class="page-item' . $nextDisabled . '"><a class="page-link ajax-page-link" href="javascript:void(0)" data-page="' . ($page + 1) . '"><i class="fa fa-chevron-right"></i></a></li>';

        $html .= '</ul></div>';
        return $html;
    }

    private function renderMasterlistRowsHtml(array $masterlist): string
    {
        if (empty($masterlist)) {
            return '<tr><td colspan="8" class="text-center text-muted py-3">No masterlist records found.</td></tr>';
        }

        $html = '';
        foreach ($masterlist as $row) {
            $fullName = trim($row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? ''));
            $isMatched = !empty($row['is_matched']) && (int) $row['is_matched'] === 1;
            $dateImported = !empty($row['imported_at']) ? date('M j, Y', strtotime($row['imported_at'])) : '-';

            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['student_LRN']) . '</td>';
            $html .= '<td>' . htmlspecialchars($fullName) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['grade_level'] ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($row['section_name'] ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($row['strand'] ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($row['school_year'] ?? '-') . '</td>';
            $html .= '<td>' . htmlspecialchars($dateImported) . '</td>';
            $html .= '<td>' . ($isMatched
                ? '<span class="status-chip enrolled">Enrolled</span>'
                : '<span class="status-chip pending">Pending</span>') . '</td>';
            $html .= '</tr>';
        }
        return $html;
    }

    private function renderMasterlistPaginationHtml(int $mlPage, int $mlLimit, int $mlOffset, int $totalMasterlist, int $totalMlPages): string
    {
        if ($totalMlPages <= 1) {
            return '';
        }

        $showing = min($mlOffset + $mlLimit, $totalMasterlist);
        $html = '<div class="pagination-parent">';
        $html .= '<small class="text-muted">Showing ' . $showing . ' of ' . $totalMasterlist . ' masterlist records</small>';
        $html .= '<ul class="pagination">';

        $prevDisabled = $mlPage <= 1 ? ' disabled' : '';
        $html .= '<li class="page-item' . $prevDisabled . '"><a class="page-link ajax-ml-page-link" href="javascript:void(0)" data-page="' . ($mlPage - 1) . '"><i class="fa fa-chevron-left"></i></a></li>';

        for ($i = 1; $i <= $totalMlPages; $i++) {
            $active = $i === $mlPage ? ' active' : '';
            $html .= '<li class="page-item' . $active . '"><a class="page-link ajax-ml-page-link" href="javascript:void(0)" data-page="' . $i . '">' . $i . '</a></li>';
        }

        $nextDisabled = $mlPage >= $totalMlPages ? ' disabled' : '';
        $html .= '<li class="page-item' . $nextDisabled . '"><a class="page-link ajax-ml-page-link" href="javascript:void(0)" data-page="' . ($mlPage + 1) . '"><i class="fa fa-chevron-right"></i></a></li>';

        $html .= '</ul></div>';
        return $html;
    }

    public function updateStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = (int) ($_POST['student_id'] ?? 0);
            $user_id = (int) ($_POST['user_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
            $section_id = (int) ($_POST['section_id'] ?? 0);
            $student_LRN = trim($_POST['student_LRN'] ?? '');

            $this->adminModel->updateStudent(
                $user_id,
                $name,
                $grade_level_id,
                $section_id,
                $student_LRN,
                $student_id
            );

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => "Student updated successfully.",
                'page' => 'student_users'
            ];

            header("Location: /learning_management/public/?url=student_users");
            exit;
        }
    }

    public function importMasterlist()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        header('Content-Type: application/json');

        if (!isset($_FILES['masterlist_csv']) || $_FILES['masterlist_csv']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'No valid file uploaded.']);
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['masterlist_csv']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            echo json_encode(['success' => false, 'message' => 'File must be a .csv']);
            exit;
        }

        $schoolYear = trim($_POST['school_year'] ?? '');

        $result = $this->adminModel->importMasterlistFromCSV($_FILES['masterlist_csv']['tmp_name'], $schoolYear);

        if (!empty($result['success']) && !empty($result['grade_section_counts'])) {
            require_once "../app/models/Teacher.php";
            $teacherModel = new Teacher();

            foreach ($result['grade_section_counts'] as $pair) {
                $teacherModel->notifyTeachersOfMasterlistImport(
                    $pair['grade_level_id'],
                    $pair['section_id'],
                    $pair['count']
                );
            }
        }

        echo json_encode($result);
        exit;
    }

    private function countGradeSectionPairsInCsv(string $csvPath): array
    {
        $counts = [];

        if (($handle = fopen($csvPath, 'r')) === false) {
            return $counts;
        }

        $rowIndex = 0;
        $gradeCol = null;
        $sectionCol = null;

        while (($row = fgetcsv($handle)) !== false) {
            $rowIndex++;
            if (empty($row))
                continue;

            if ($rowIndex === 1) {
                $headers = array_map('strtolower', array_map('trim', $row));
                $gradeCol = array_search('grade_level_id', $headers);
                $sectionCol = array_search('section_id', $headers);
                continue;
            }

            if ($gradeCol === false || $sectionCol === false)
                continue;

            $gradeLevelId = (int) trim($row[$gradeCol] ?? 0);
            $sectionId = (int) trim($row[$sectionCol] ?? 0);
            if (!$gradeLevelId || !$sectionId)
                continue;

            $key = "{$gradeLevelId}_{$sectionId}";
            if (!isset($counts[$key])) {
                $counts[$key] = [
                    'grade_level_id' => $gradeLevelId,
                    'section_id' => $sectionId,
                    'count' => 0,
                ];
            }
            $counts[$key]['count']++;
        }

        fclose($handle);
        return array_values($counts);
    }

    public function SectionPage()
    {
        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $sectionStats = $this->adminModel->getSectionStats();
        $totalSections = $this->adminModel->countAllSectionsFiltered($search, $grade);
        $totalPages = (int) ceil($totalSections / $limit);
        $sections = $this->adminModel->getAllSectionsWithDetails($search, $grade, $limit, $offset);
        $gradeLevels = $this->adminModel->getAllGradeLevels();

        extract(compact(
            'sectionStats',
            'sections',
            'gradeLevels',
            'search',
            'grade',
            'page',
            'limit',
            'offset',
            'totalSections',
            'totalPages'
        ));

        require "../admin_folder/sections.php";
    }

    public function createSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['section_name'] ?? '');
            $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);

            if (empty($name) || !$gradeLevelId) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Section name and grade level are required.', 'page' => 'Adminsections'];
                header("Location: /learning_management/public/?url=Adminsections");
                exit;
            }

            $this->adminModel->createSection($name, $gradeLevelId);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Section created successfully.', 'page' => 'Adminsections'];
            header("Location: /learning_management/public/?url=Adminsections");
            exit;
        }
    }

    public function updateSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['section_id'] ?? 0);
            $name = trim($_POST['section_name'] ?? '');
            $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);

            if (!$id || empty($name) || !$gradeLevelId) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid section data.', 'page' => 'Adminsections'];
                header("Location: /learning_management/public/?url=Adminsections");
                exit;
            }

            $this->adminModel->updateSection($id, $name, $gradeLevelId);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Section updated successfully.', 'page' => 'Adminsections'];
            header("Location: /learning_management/public/?url=Adminsections");
            exit;
        }
    }

    public function deleteSection()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['section_id'] ?? 0);
            $deleted = $this->adminModel->deleteSection($id);

            $_SESSION['flash'] = $deleted
                ? ['type' => 'success', 'message' => 'Section deleted successfully.', 'page' => 'Adminsections']
                : ['type' => 'error', 'message' => 'Cannot delete a section that still has enrolled students.', 'page' => 'Adminsections'];

            header("Location: /learning_management/public/?url=Adminsections");
            exit;
        }
    }

    public function SubjectPage()
    {
        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $strandFilter = trim($_GET['strand'] ?? '');
        $curriculumType = ($_GET['curriculum'] ?? 'new') === 'legacy' ? 'legacy' : 'new';
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $subjectStats = $this->subjectModel->getSubjectStats($curriculumType);
        $totalSubjects = $this->subjectModel->countAllSubjectsFiltered($search, $grade, $strandFilter, $curriculumType);
        $totalPages = (int) ceil($totalSubjects / $limit);
        $subjects = $this->subjectModel->getAllSubjectsFiltered($search, $grade, $limit, $offset, $strandFilter, $curriculumType);
        $strandOptions = $this->subjectModel->getDistinctStrandGroups($curriculumType);
        $gradeLevels = $this->adminModel->getAllGradeLevels();

        extract(compact(
            'subjectStats',
            'subjects',
            'gradeLevels',
            'search',
            'grade',
            'strandFilter',
            'strandOptions',
            'curriculumType',
            'page',
            'limit',
            'offset',
            'totalSubjects',
            'totalPages'
        ));

        require "../admin_folder/subjects.php";
    }

    public function updateSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['subject_id'] ?? 0);
            $name = trim($_POST['subject_name'] ?? '');
            $subject_description = trim($_POST['subject_description'] ?? '');
            $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);
            $curriculum_type = ($_POST['curriculum_type'] ?? 'new') === 'legacy' ? 'legacy' : 'new';
            $strand_group = trim($_POST['strand_group'] ?? '');
            $redirectCurriculum = ($_POST['redirect_curriculum'] ?? $curriculum_type) === 'legacy' ? 'legacy' : 'new';

            $redirectTo = "/learning_management/public/?url=Adminsubjects&curriculum=" . $redirectCurriculum;

            if (!$id || empty($name) || !$gradeLevelId) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid subject data.', 'page' => 'Adminsubjects'];
                header("Location: " . $redirectTo);
                exit;
            }

            $imagePath = $this->uploadSubjectImage();

            $this->subjectModel->updateSubject($id, $name, $gradeLevelId, $subject_description, $imagePath, $curriculum_type, $strand_group);

            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Subject updated successfully.', 'page' => 'Adminsubjects'];
            header("Location: " . $redirectTo);
            exit;
        }
    }

    public function deleteSubject()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['subject_id'] ?? 0);
            $deleted = $this->subjectModel->deleteSubject($id);

            $_SESSION['flash'] = $deleted
                ? ['type' => 'success', 'message' => 'Subject deleted successfully.', 'page' => 'Adminsubjects']
                : ['type' => 'error', 'message' => 'Cannot delete a subject that has teachers assigned.', 'page' => 'Adminsubjects'];

            header("Location: /learning_management/public/?url=Adminsubjects");
            exit;
        }
    }


    public function subjectAccessPage()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: ?url=login");
            exit;
        }

        $curriculumType = ($_GET['curriculum'] ?? 'legacy') === 'new' ? 'new' : 'legacy';
        $gradeFilter = trim($_GET['grade'] ?? '');
        $search = trim($_GET['search'] ?? '');
        $selectedSectionId = (int) ($_GET['section_id'] ?? 0);

        // $sections powers both the section dropdown options AND the default
        // panel list — filtered only by search + grade so far.
        $sections = $this->adminModel->getAllSectionsWithDetails($search, $gradeFilter, 1000, 0);

        foreach ($sections as &$sec) {
            $counts = $this->subjectModel->getElectiveCountsForSection(
                (int) $sec['grade_level_id'],
                $curriculumType,
                (int) $sec['id']
            );
            $sec['elective_enabled'] = $counts['enabled'];
            $sec['elective_total'] = $counts['total'];
        }
        unset($sec);

        // If a specific section was picked from the dropdown, narrow the
        // panel list down to just that one. Otherwise every matching
        // section (from search/grade) gets its own panel — nothing is
        // gated behind having to pick something first.
        $panelSections = $sections;
        if ($selectedSectionId > 0) {
            $panelSections = array_values(array_filter(
                $sections,
                fn($sec) => (int) $sec['id'] === $selectedSectionId
            ));
        }

        $sectionPanels = [];
        foreach ($panelSections as $sec) {
            $coreSubjects = $this->subjectModel->getCoreSubjects((int) $sec['grade_level_id'], $curriculumType);
            $electiveGroups = $this->subjectModel->getElectiveSubjectsGrouped(
                (int) $sec['grade_level_id'],
                $curriculumType,
                (int) $sec['id']
            );

            // Only show sections that actually have a teacher assigned to
            // teach at least one elective. Core subjects (like Capstone)
            // apply to every section automatically regardless of teacher
            // assignments, so their presence alone shouldn't be a reason
            // to display a section that has nothing else going on.
            if (empty($electiveGroups)) {
                continue;
            }

            $sectionPanels[] = [
                'section' => $sec,
                'coreSubjects' => $coreSubjects,
                'electiveGroups' => $electiveGroups,
            ];
        }

        $gradeLevels = $this->adminModel->getAllGradeLevels();

        extract(compact(
            'sections',
            'sectionPanels',
            'selectedSectionId',
            'gradeLevels',
            'curriculumType',
            'gradeFilter',
            'search'
        ));

        require "../admin_folder/subject_access.php";
    }

    public function subjectAccessSectionAjax()
    {
        header('Content-Type: application/json');

        $sectionId = (int) ($_GET['section_id'] ?? 0);
        $curriculumType = ($_GET['curriculum'] ?? 'legacy') === 'new' ? 'new' : 'legacy';

        $sections = $this->adminModel->getAllSectionsWithDetails('', '', 1000, 0);
        $selectedSection = null;
        foreach ($sections as $sec) {
            if ((int) $sec['id'] === $sectionId) {
                $selectedSection = $sec;
                break;
            }
        }
        if (!$selectedSection) {
            echo json_encode(['success' => false, 'message' => 'Section not found.']);
            exit;
        }

        $counts = $this->subjectModel->getElectiveCountsForSection((int) $selectedSection['grade_level_id'], $curriculumType, $sectionId);
        $selectedSection['elective_enabled'] = $counts['enabled'];
        $selectedSection['elective_total'] = $counts['total'];

        $coreSubjects = $this->subjectModel->getCoreSubjects((int) $selectedSection['grade_level_id'], $curriculumType);
        $electiveGroups = $this->subjectModel->getElectiveSubjectsGrouped(
            (int) $selectedSection['grade_level_id'],
            $curriculumType,
            $sectionId
        );

        $selectedSectionId = $sectionId;

        ob_start();
        require "../admin_folder/subject_access_panel.php";
        $html = ob_get_clean();

        echo json_encode(['success' => true, 'html' => $html]);
        exit;
    }

    public function toggleSubjectAccessAjax()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $sectionId = (int) ($_POST['section_id'] ?? 0);
        $subjectId = (int) ($_POST['subject_id'] ?? 0);
        $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);
        $curriculumType = ($_POST['curriculum'] ?? 'legacy') === 'new' ? 'new' : 'legacy';
        $enabled = ($_POST['enabled'] ?? '0') === '1';

        if (!$sectionId || !$subjectId) {
            echo json_encode(['success' => false, 'message' => 'Missing section or subject.']);
            exit;
        }

        $this->subjectModel->setSubjectAccess($sectionId, $subjectId, $enabled);
        $counts = $this->subjectModel->getElectiveCountsForSection($gradeLevelId, $curriculumType, $sectionId);

        echo json_encode(['success' => true, 'counts' => $counts]);
        exit;
    }

    public function bulkToggleSubjectAccessAjax()
    {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $sectionId = (int) ($_POST['section_id'] ?? 0);
        $strandGroup = trim($_POST['strand_group'] ?? '');
        $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);
        $curriculumType = ($_POST['curriculum'] ?? 'legacy') === 'new' ? 'new' : 'legacy';
        $enabled = ($_POST['enabled'] ?? '0') === '1';

        if (!$sectionId || $strandGroup === '') {
            echo json_encode(['success' => false, 'message' => 'Missing section or strand.']);
            exit;
        }

        $this->subjectModel->bulkSetStrandAccess($sectionId, $strandGroup, $curriculumType, $enabled);
        $counts = $this->subjectModel->getElectiveCountsForSection($gradeLevelId, $curriculumType, $sectionId);

        echo json_encode(['success' => true, 'counts' => $counts]);
        exit;
    }
}