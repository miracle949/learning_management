<?php

require_once "../app/models/SuperAdmin.php";

class SuperAdminController
{
    private $superAdminModel;

    public function __construct()
    {
        $this->superAdminModel = new SuperAdmin();
    }

    public function super_index()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
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
        $approvedCount = $adminModel->countStudentsByStatus('Approved');
        $pendingCount = $adminModel->countStudentsByStatus('Pending');

        $activityLogs = $this->superAdminModel->getActivityLogs(15);

        // ── New data for the redesigned dashboard ──
        $totalAdmins = $this->superAdminModel->getTotalAdmins();
        $strandsOffered = $this->superAdminModel->getStrandsOffered();
        $enrollmentByStrand = $this->superAdminModel->getEnrollmentByStrand();
        $lastBackup = $this->superAdminModel->getLastBackup();
        $activeSessions = $this->superAdminModel->getActiveSessionsSummary();
        $systemAlerts = $this->superAdminModel->getSystemAlerts($totalPendingApprovals);

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
            'activityLogs' => $activityLogs,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'totalAdmins' => $totalAdmins,
            'strandsOffered' => $strandsOffered,
            'enrollmentByStrand' => $enrollmentByStrand,
            'lastBackup' => $lastBackup,
            'activeSessions' => $activeSessions,
            'systemAlerts' => $systemAlerts,
        ]);

        require "../app/view/super_admin.php";
    }

    public function activities()
    {
        $gradeLevels = $this->superAdminModel->getAllGradeLevels();
        $selectedGrade = isset($_GET['grade_id']) ? (int) $_GET['grade_id'] : 0;
        $subjects = $selectedGrade
            ? $this->superAdminModel->getSubjectsByGradeLevel($selectedGrade)
            : $this->superAdminModel->getAllSubjects();
        include "../super_admin_folder/activities.php";
    }

    public function create_activities()
    {
        $subjectId = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;
        $subject = $subjectId ? $this->superAdminModel->getSubjectById($subjectId) : null;
        include "../super_admin_folder/create_activities.php";
    }

    // ============================================================
    // SAVE INTERACTIVE MODULE
    // ------------------------------------------------------------
    // Every lesson now submits ONE ordered "blocks" array instead of
    // separate video/image/activity/quiz/flashcard arrays. Each block
    // carries its own type (text/image/video/quiz/activity/flashcard)
    // and its index in that array IS its sort_order — so whatever
    // order the admin arranged blocks in the builder UI is exactly
    // what gets saved and, later, exactly what gets rendered.
    // ============================================================
    public function save_interactive_module()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $subject_id = (int) ($_POST['subject_id'] ?? 0);

        if (!$subject_id) {
            header("Location: /learning_management/public/?url=activities");
            exit;
        }

        $skipped = ['im_modules' => [], 'lessons' => []];
        $baseUpload = dirname(__DIR__, 2) . '/uploads/';
        $imageDir = $baseUpload . 'lessons/images/';

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        $moduleTitles = $_POST['module_title'] ?? [];
        $moduleDescriptions = $_POST['module_description'] ?? [];
        $blocksData = $_POST['blocks'] ?? [];
        $blockImages = $_FILES['block_image'] ?? null;

        foreach ($moduleTitles as $modIdx => $modTitle) {
            if (empty(trim($modTitle)))
                continue;

            $existingIMCount = $this->superAdminModel->countInteractiveModules($subject_id);
            $imModuleNumber = $existingIMCount + $modIdx + 1;
            $numberedIMTitle = 'Module ' . $imModuleNumber . ': ' . trim($modTitle);

            $imResult = $this->superAdminModel->insertInteractiveModule(
                $subject_id,
                $numberedIMTitle,
                trim($moduleDescriptions[$modIdx] ?? '')
            );
            $interactiveModuleId = $imResult['id'] ?? null;
            if (!$interactiveModuleId)
                continue;
            if ($imResult['existed'])
                $skipped['im_modules'][] = $numberedIMTitle;

            $lessonTitles = $_POST['lesson_title'][$modIdx] ?? [];
            $lessonTopics = $_POST['lesson_topic'][$modIdx] ?? [];

            foreach ($lessonTitles as $lesIdx => $lesTitle) {
                if (empty(trim($lesTitle)))
                    continue;

                $existingLesCount = $this->superAdminModel->countLessons($interactiveModuleId);
                $lessonNumber = $existingLesCount + $lesIdx + 1;
                $numberedLesTitle = 'Lesson ' . $lessonNumber . ': ' . trim($lesTitle);

                $lesResult = $this->superAdminModel->insertLesson(
                    $interactiveModuleId,
                    $numberedLesTitle,
                    trim($lessonTopics[$lesIdx] ?? '')
                );
                $lessonId = $lesResult['id'] ?? null;
                if (!$lessonId)
                    continue;
                if ($lesResult['existed'])
                    $skipped['lessons'][] = $numberedLesTitle . ' (in ' . $numberedIMTitle . ')';

                $lessonBlocks = $blocksData[$modIdx][$lesIdx] ?? [];

                // Every block (text, image, video, quiz, activity, flashcard)
                // gets its own row in tbl_interactive_contents, tagged with
                // sort_order = its position in the builder — so the exact
                // order the admin arranged blocks in is preserved and can be
                // reconstructed later with ORDER BY sort_order.
                foreach ($lessonBlocks as $blockIdx => $block) {
                    $type = $block['type'] ?? '';
                    $sortOrder = (int) $blockIdx;

                    switch ($type) {

                        // ── TEXT ──
                        // 'heading' is optional — reuses the existing `title`
                        // column on tbl_interactive_contents (no schema change
                        // needed) as the section label, e.g. "The Software
                        // Layer", rendered above this block's body text.
                        case 'text':
                            $text = trim($block['text'] ?? '');
                            $heading = trim($block['heading'] ?? '');
                            $keyIdea = trim($block['key_idea'] ?? '');
                            if ($text === '' && $heading === '')
                                break;                       // only skip if BOTH are empty
                            $this->superAdminModel->insertInteractiveContent($lessonId, 'text', [
                                'title' => $heading !== '' ? $heading : null,
                                'body' => $text !== '' ? $text : null,
                                'key_idea' => $keyIdea !== '' ? $keyIdea : null,
                                'sort_order' => $sortOrder,
                            ]);
                            break;

                        // ── IMAGE ──
                        case 'image':
                            $groupTitle = trim($block['image_title'] ?? '');
                            $imgNames = $blockImages['name'][$modIdx][$lesIdx][$blockIdx] ?? [];
                            foreach ($imgNames as $imgIdx => $imgName) {
                                $file = [
                                    'name' => $blockImages['name'][$modIdx][$lesIdx][$blockIdx][$imgIdx] ?? null,
                                    'tmp_name' => $blockImages['tmp_name'][$modIdx][$lesIdx][$blockIdx][$imgIdx] ?? null,
                                    'error' => $blockImages['error'][$modIdx][$lesIdx][$blockIdx][$imgIdx] ?? UPLOAD_ERR_NO_FILE,
                                    'size' => $blockImages['size'][$modIdx][$lesIdx][$blockIdx][$imgIdx] ?? 0,
                                ];
                                if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || empty($file['name']))
                                    continue;
                                if (($file['size'] ?? 0) > 5 * 1024 * 1024)
                                    continue;
                                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    continue;
                                $uniqueName = uniqid('img_') . '.' . $ext;
                                if (move_uploaded_file($file['tmp_name'], $imageDir . $uniqueName)) {
                                    $this->superAdminModel->insertInteractiveContent($lessonId, 'image', [
                                        'title' => $groupTitle !== '' ? $groupTitle : null,
                                        'file_path' => '/learning_management/uploads/lessons/images/' . $uniqueName,
                                        'file_name' => $file['name'],
                                        'file_type' => $ext,
                                        'sort_order' => $sortOrder,
                                    ]);
                                }
                            }
                            break;

                        // ── VIDEO ──
                        case 'video':
                            $vTitle = trim($block['video_title'] ?? '');
                            $vUrl = trim($block['video_url'] ?? '');
                            if ($vTitle === '' || $vUrl === '')
                                break;
                            $this->superAdminModel->insertInteractiveContent($lessonId, 'video', [
                                'title' => $vTitle,
                                'file_path' => $vUrl,
                                'file_type' => 'url',
                                'sort_order' => $sortOrder,
                            ]);
                            break;

                        // ── QUIZ (one block = one quiz, N questions) ──
                        case 'quiz':
                            $qzTitle = trim($block['quiz_title'] ?? '');
                            if ($qzTitle === '')
                                break;
                            $questions = $block['questions'] ?? [];
                            foreach ($questions as $q) {
                                $qText = trim($q['text'] ?? '');
                                if ($qText === '')
                                    continue;
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'quiz', [
                                    'title' => $qzTitle,
                                    'instructions' => trim($block['quiz_instructions'] ?? ''),
                                    'passing_score' => (int) ($block['quiz_passing_score'] ?? 75),
                                    'question' => $qText,
                                    'question_type' => 'multiple_choice',
                                    'choice_a' => trim($q['choice_a'] ?? '') ?: null,
                                    'choice_b' => trim($q['choice_b'] ?? '') ?: null,
                                    'choice_c' => trim($q['choice_c'] ?? '') ?: null,
                                    'choice_d' => trim($q['choice_d'] ?? '') ?: null,
                                    'correct_ans' => strtolower($q['correct'] ?? 'a'),
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;

                        // ── ACTIVITY (one block = one activity, N questions) ──
                        case 'activity':
                            $actTitle = trim($block['activity_title'] ?? '');
                            if ($actTitle === '')
                                break;
                            $questions = $block['questions'] ?? [];
                            foreach ($questions as $q) {
                                $qText = trim($q['text'] ?? '');
                                if ($qText === '')
                                    continue;
                                $qType = $q['type'] ?? 'essay';
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'activity', [
                                    'title' => $actTitle,
                                    'instructions' => trim($block['activity_instructions'] ?? ''),
                                    'total_points' => (int) ($block['activity_points'] ?? 0),
                                    'question' => $qText,
                                    'question_type' => $qType,
                                    'model_answer' => $qType === 'essay' ? (trim($q['essay_answer'] ?? '') ?: null) : null,
                                    'choice_a' => $qType === 'multiple_choice' ? (trim($q['choice_a'] ?? '') ?: null) : null,
                                    'choice_b' => $qType === 'multiple_choice' ? (trim($q['choice_b'] ?? '') ?: null) : null,
                                    'choice_c' => $qType === 'multiple_choice' ? (trim($q['choice_c'] ?? '') ?: null) : null,
                                    'choice_d' => $qType === 'multiple_choice' ? (trim($q['choice_d'] ?? '') ?: null) : null,
                                    'correct_ans' => $qType === 'multiple_choice' ? (strtolower($q['correct'] ?? 'a') ?: null) : null,
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;

                        // ── FLASHCARD (one block = N cards) ──
                        case 'flashcard':
                            $cards = $block['cards'] ?? [];
                            foreach ($cards as $c) {
                                $front = trim($c['front'] ?? '');
                                $back = trim($c['back'] ?? '');
                                if ($front === '' || $back === '')
                                    continue;
                                $this->superAdminModel->insertInteractiveContent($lessonId, 'flashcard', [
                                    'card_type' => $c['card_type'] ?? 'term_definition',
                                    'card_front' => $front,
                                    'card_back' => $back,
                                    'sort_order' => $sortOrder,
                                ]);
                            }
                            break;
                    }
                }
            }
        }

        $hasSkipped = !empty($skipped['im_modules']) || !empty($skipped['lessons']);
        if ($hasSkipped)
            $_SESSION['save_skipped'] = $skipped;
        $_SESSION['save_success'] = true;

        header("Location: /learning_management/public/?url=activities");
        exit;
    }

    public function save_subject()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $subjectId = (int) ($_POST['subject_id'] ?? 0);
        $name = trim($_POST['subject_name'] ?? '');
        $code = trim($_POST['subject_code'] ?? '');
        $description = trim($_POST['subject_description'] ?? '');
        $gradeLevelId = (int) ($_POST['grade_level_id'] ?? 0);

        if (!$name || !$code || !$gradeLevelId) {
            header("Location: /learning_management/public/?url=activities");
            exit;
        }

        $imagePath = null;
        $file = $_FILES['subject_image'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK && $file['size'] > 0) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $uploadDir = dirname(__DIR__, 2) . '/uploads/subjects/';
                if (!is_dir($uploadDir))
                    mkdir($uploadDir, 0755, true);
                $uniqueName = uniqid('subj_') . '.' . $ext;
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $uniqueName)) {
                    $imagePath = 'uploads/subjects/' . $uniqueName;
                }
            }
        }

        if ($subjectId) {
            $this->superAdminModel->updateSubject($subjectId, $name, $code, $description, $gradeLevelId, $imagePath);
        } else {
            $this->superAdminModel->createSubject($name, $code, $description, $gradeLevelId, $imagePath);
        }

        $_SESSION['save_success'] = true;
        header("Location: /learning_management/public/?url=activities");
        exit;
    }

    // ============================================================
// TEACHERS (Super Admin side)
// ============================================================
    public function super_admin_teacherRecords()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        require_once "../app/models/subjects.php";
        require_once "../app/models/Grade_level.php";
        $subjectModel = new subjects();
        $gradeLevelModel = new Grade_level();

        $grade11Subjects = $subjectModel->getGrade11Subjects();
        $grade12Subjects = $subjectModel->getGrade12Subjects();
        $grade11Sections = $gradeLevelModel->getGrade11Sections();
        $grade12Sections = $gradeLevelModel->getGrade12Sections();

        $teacherStats = $this->superAdminModel->getTeacherStatusCounts();

        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalTeachers = $this->superAdminModel->countAllTeachersFiltered($search, $grade, $section, $status);
        $totalPages = (int) ceil($totalTeachers / $limit);

        $teachers = $this->superAdminModel->getAllTeachersFilteredPaginated(
            $search,
            $grade,
            $section,
            $status,
            $limit,
            $offset
        );

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
            'teacherStats'
        ));

        require "../super_admin_folder/teacher_users.php";
    }

    // ============================================================
// STUDENTS (Super Admin side) — enrolled students only
// ============================================================
    public function super_admin_studentRecords()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $grade = trim($_GET['grade'] ?? '');
        $section = trim($_GET['section'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $gradeLevels = $this->superAdminModel->getAllGradeLevels();
        $allSections = $this->superAdminModel->getAllSections();

        $totalStudents = $this->superAdminModel->countAllStudentsFiltered($search, $grade, $section);
        $totalPages = max(1, (int) ceil($totalStudents / $limit));

        $students = $this->superAdminModel->getAllStudentsFilteredPaginated(
            $limit,
            $offset,
            $search,
            $grade,
            $section
        );

        // ADD THESE TWO — used by the stat cards
        $totalGrade12 = $this->superAdminModel->countAllStudentsFiltered('', 'Grade 12', '');
        $totalGrade11 = $this->superAdminModel->countAllStudentsFiltered('', 'Grade 11', '');

        extract(compact(
            'students',
            'totalStudents',
            'totalPages',
            'page',
            'limit',
            'offset',
            'gradeLevels',
            'allSections',
            'search',
            'grade',
            'section',
            'totalGrade12',   // ADD
            'totalGrade11'    // ADD
        ));

        require "../super_admin_folder/student_users.php";
    }

    public function update_super_admin_Student()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $student_id = (int) ($_POST['student_id'] ?? 0);
            $user_id = (int) ($_POST['user_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $grade_level_id = (int) ($_POST['grade_level_id'] ?? 0);
            $section_id = (int) ($_POST['section_id'] ?? 0);
            $student_LRN = trim($_POST['student_LRN'] ?? '');

            $this->superAdminModel->updateStudentInfo(
                $user_id,
                $name,
                $grade_level_id,
                $section_id,
                $student_LRN,
                $student_id
            );

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Student updated successfully.',
                'page' => 'super_admin_student_users'
            ];

            header("Location: /learning_management/public/?url=super_admin_student_users");
            exit;
        }
    }

    // ============================================================
// ADMINS (Super Admin side)
// ============================================================
    public function super_admin_adminRecords()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalAdmins = $this->superAdminModel->countAllAdminsFiltered($search);
        $totalPages = max(1, (int) ceil($totalAdmins / $limit));

        $admins = $this->superAdminModel->getAllAdminsFilteredPaginated($limit, $offset, $search);

        $activeAdminsNow = $this->superAdminModel->countActiveAdminsNow();
        $addedThisMonth = $this->superAdminModel->countAdminsAddedThisMonth();

        extract(compact(
            'admins',
            'totalAdmins',
            'totalPages',
            'page',
            'limit',
            'offset',
            'search',
            'activeAdminsNow',
            'addedThisMonth'
        ));

        require "../super_admin_folder/admin_users.php";
    }

    public function create_super_admin_Admin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($name === '' || $username === '' || $password === '') {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'All fields are required.',
                    'page' => 'super_admin_admin_users'
                ];
                header("Location: /learning_management/public/?url=super_admin_admin_users");
                exit;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Passwords do not match.',
                    'page' => 'super_admin_admin_users'
                ];
                header("Location: /learning_management/public/?url=super_admin_admin_users");
                exit;
            }

            $result = $this->superAdminModel->createAdmin($name, $username, $password);

            $_SESSION['flash'] = $result['success']
                ? ['type' => 'success', 'message' => 'Admin account created successfully.', 'page' => 'super_admin_admin_users']
                : ['type' => 'error', 'message' => $result['error'] ?? 'Failed to create admin.', 'page' => 'super_admin_admin_users'];
        }

        header("Location: /learning_management/public/?url=super_admin_admin_users");
        exit;
    }

    public function update_super_admin_Admin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['admin_id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $this->superAdminModel->updateAdminInfo($id, $name, $username, $password !== '' ? $password : null);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Admin updated successfully.',
                'page' => 'super_admin_admin_users'
            ];
        }

        header("Location: /learning_management/public/?url=super_admin_admin_users");
        exit;
    }

    public function delete_super_admin_Admin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['admin_id'] ?? 0);

            // Guard rail: don't let a super admin delete their own logged-in account here.
            if ($id !== (int) ($_SESSION['user_id'] ?? 0)) {
                $this->superAdminModel->deleteAdmin($id);
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Admin account deleted.',
                    'page' => 'super_admin_admin_users'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'You cannot delete your own account.',
                    'page' => 'super_admin_admin_users'
                ];
            }
        }

        header("Location: /learning_management/public/?url=super_admin_admin_users");
        exit;
    }

    // ============================================================
// SCHOOL PROFILE
// ============================================================
    public function schoolProfile()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $profile = $this->superAdminModel->getSchoolProfile();
        $strandSettings = $this->superAdminModel->getAllStrandSettings();
        $landingVideos = $this->superAdminModel->getAllLandingVideos(); // NEW

        extract(compact('profile', 'strandSettings', 'landingVideos'));

        require "../super_admin_folder/school_profile.php";
    }

    public function saveSchoolProfile()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'school_name' => trim($_POST['school_name'] ?? ''),
                'deped_school_id' => trim($_POST['deped_school_id'] ?? ''),
                'region_division' => trim($_POST['region_division'] ?? ''),
                'principal_name' => trim($_POST['principal_name'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'contact_number' => trim($_POST['contact_number'] ?? ''),
                'current_school_year' => trim($_POST['current_school_year'] ?? ''),
                'grade_levels_offered' => trim($_POST['grade_levels_offered'] ?? ''),
            ];

            if ($data['school_name'] === '') {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'School name is required.',
                    'page' => 'school_profile'
                ];
                header("Location: /learning_management/public/?url=school_profile");
                exit;
            }

            $this->superAdminModel->updateSchoolProfile($data);

            $offeredStrandIds = $_POST['strands_offered'] ?? []; // array of checked strand ids
            $this->superAdminModel->setStrandOfferedStates($offeredStrandIds);

            // NEW — persist per-strand description/image edits
            $strandDescriptions = $_POST['strand_description'] ?? [];
            $strandImages = $_POST['strand_image'] ?? [];
            $this->superAdminModel->updateStrandContent($strandDescriptions, $strandImages);

            // NEW — persist "Strands in Action" video edits
            $videoTitles = $_POST['video_title'] ?? [];
            $videoCategories = $_POST['video_category'] ?? [];
            $videoDurations = $_POST['video_duration'] ?? [];
            $videoIds = $_POST['video_youtube_id'] ?? [];
            $videoActive = $_POST['video_active'] ?? []; // only checked ones are present

            foreach ($videoTitles as $id => $title) {
                $id = (int) $id;
                if ($id <= 0)
                    continue;

                $this->superAdminModel->updateLandingVideo(
                    $id,
                    trim($title),
                    trim($videoCategories[$id] ?? ''),
                    trim($videoDurations[$id] ?? ''),
                    trim($videoIds[$id] ?? ''),
                    isset($videoActive[$id])
                );
            }

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'School profile updated successfully.',
                'page' => 'school_profile'
            ];
        }

        header("Location: /learning_management/public/?url=school_profile");
        exit;
    }

    // ============================================================
// ROLES & PERMISSIONS
// ============================================================
    public function rolesPermissions()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $roleDefinitions = SuperAdmin::ROLE_PERMISSIONS;
        $rolePermissions = [];
        foreach (array_keys($roleDefinitions) as $role) {
            $rolePermissions[$role] = $this->superAdminModel->getRolePermissions($role);
        }

        extract(compact('roleDefinitions', 'rolePermissions'));

        require "../super_admin_folder/roles_permissions.php";
    }

    public function toggleRolePermission()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }

        $role = trim($_POST['role'] ?? '');
        $permissionKey = trim($_POST['permission_key'] ?? '');
        $allowed = filter_var($_POST['allowed'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $ok = $this->superAdminModel->setRolePermission($role, $permissionKey, $allowed);

        if ($ok) {
            echo json_encode(['success' => true, 'message' => 'Permission updated.']);
        } else {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Invalid role or permission key.']);
        }
        exit;
    }

    public function saveAdminPermissions()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int) ($_POST['user_id'] ?? 0);
            $allowedPages = $_POST['allowed_pages'] ?? [];

            if ($userId > 0) {
                $this->superAdminModel->saveAdminPermissions($userId, $allowedPages);
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Permissions updated successfully.',
                    'page' => 'roles_permissions'
                ];
            }
        }

        header("Location: /learning_management/public/?url=roles_permissions");
        exit;
    }

    // ============================================================
// ADD THIS BLOCK TO SuperAdminController.php
// ============================================================

    public function backupRestore()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $backups = $this->superAdminModel->getAllBackups(50);
        $stats = $this->superAdminModel->getBackupStats();
        $lastBackup = $this->superAdminModel->getLastBackup();

        extract(compact('backups', 'stats', 'lastBackup'));

        require "../super_admin_folder/backup_restore.php";
    }

    public function createBackup()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $backupDir = dirname(__DIR__, 2) . '/uploads/backups/';
        $result = $this->superAdminModel->createFullBackup($backupDir);

        if ($result['success']) {
            $this->superAdminModel->recordBackup(
                $result['filename'],
                $result['file_path'],
                'manual',
                'success',
                $result['size_mb'],
                (int) $_SESSION['user_id']
            );
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Backup created successfully (' . $result['size_mb'] . ' MB).',
                'page' => 'backup_restore'
            ];
        } else {
            $this->superAdminModel->recordBackup(
                'failed_backup_' . date('Ymd_His') . '.sql',
                '',
                'manual',
                'failed',
                0,
                (int) $_SESSION['user_id']
            );
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Backup failed: ' . ($result['error'] ?? 'Unknown error.'),
                'page' => 'backup_restore'
            ];
        }

        header("Location: /learning_management/public/?url=backup_restore");
        exit;
    }

    public function downloadBackup()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $backup = $this->superAdminModel->getBackupById($id);

        if (!$backup || !file_exists($backup['file_path'])) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Backup file not found.',
                'page' => 'backup_restore'
            ];
            header("Location: /learning_management/public/?url=backup_restore");
            exit;
        }

        header('Content-Type: application/sql');
        header('Content-Disposition: attachment; filename="' . basename($backup['filename']) . '"');
        header('Content-Length: ' . filesize($backup['file_path']));
        readfile($backup['file_path']);
        exit;
    }

    public function deleteBackup()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id'] ?? 0);
            $backup = $this->superAdminModel->deleteBackupRecord($id);

            if ($backup) {
                if (!empty($backup['file_path']) && file_exists($backup['file_path'])) {
                    unlink($backup['file_path']);
                }
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Backup deleted.',
                    'page' => 'backup_restore'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Backup not found.',
                    'page' => 'backup_restore'
                ];
            }
        }

        header("Location: /learning_management/public/?url=backup_restore");
        exit;
    }

    public function restoreBackup()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /learning_management/public/?url=backup_restore");
            exit;
        }

        $sqlContent = null;

        // Option A: restore from an existing backup in history
        $existingId = (int) ($_POST['backup_id'] ?? 0);
        if ($existingId > 0) {
            $backup = $this->superAdminModel->getBackupById($existingId);
            if ($backup && file_exists($backup['file_path'])) {
                $sqlContent = file_get_contents($backup['file_path']);
            }
        }

        // Option B: restore from a freshly uploaded .sql file
        if ($sqlContent === null && !empty($_FILES['restore_file']['tmp_name']) && $_FILES['restore_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['restore_file']['name'], PATHINFO_EXTENSION));
            if ($ext === 'sql') {
                $sqlContent = file_get_contents($_FILES['restore_file']['tmp_name']);
            }
        }

        if ($sqlContent === null) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'No valid .sql file or backup selected to restore.',
                'page' => 'backup_restore'
            ];
            header("Location: /learning_management/public/?url=backup_restore");
            exit;
        }

        $result = $this->superAdminModel->restoreFromSql($sqlContent);

        if ($result['success']) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Database restored successfully (' . $result['executed'] . ' statements executed).',
                'page' => 'backup_restore'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Restore completed with errors: ' . implode('; ', array_slice($result['errors'], 0, 3)),
                'page' => 'backup_restore'
            ];
        }

        header("Location: /learning_management/public/?url=backup_restore");
        exit;
    }

    // ============================================================
// ADD THIS METHOD TO SuperAdminController.php
// ============================================================

    public function auditLogs()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'superadmin') {
            header("Location: ?url=login");
            exit;
        }

        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $result = $this->superAdminModel->getFilteredActivityLogs($search, $role, $status, $limit, $offset);
        $logs = $result['logs'];
        $totalLogs = $result['total'];
        $totalPages = max(1, (int) ceil($totalLogs / $limit));

        $stats = $this->superAdminModel->getActivityLogStats();

        extract(compact('logs', 'totalLogs', 'totalPages', 'page', 'limit', 'search', 'role', 'status', 'stats'));

        require "../super_admin_folder/audit_logs.php";
    }
}