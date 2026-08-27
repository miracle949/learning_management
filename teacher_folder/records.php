<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($classInfo['subject_name'] ?? 'Class') ?></title>
    <link rel="stylesheet" href="../css_folder/records.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* 
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #111827;
        } */

        /* parent icons */

        /* .parent-icons{
            padding: 1.2rem 1.5rem;
            background-color: #ffffff;
            display: flex;
            justify-content: end;
            gap: 1rem;
            border: 1px solid black;
        }

        .parent-icons .icons{
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .parent-icons .fa{
            font-size: 19px;
            color: var(--green);
        } */

        /* ── HERO BANNER ── */
        .hero-banner {
            width: 100%;
            height: 180px;
            /* margin-top: 66px; */
            background-size: cover;
            background-position: center;
            position: relative;
            /* margin-top: 1rem; */
            position: relative;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            z-index: 1;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: rgba(0, 0, 0, .30);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        /* ── HERO INFO ── */
        .hero-info {
            background: #fff;
            padding: 16px 20px 0;
            /* border-bottom: 1px solid #e4e7eb; */
            border: 1px solid #e4e7eb;
            position: relative;
            z-index: 2;
        }

        .hero-info h2 {
            font-size: 18px;
            font-weight: 800;
            /* color: #00a84a; */
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .hero-meta {
            font-size: 14.5px;
            /* color: #6b7280; */
            color: var(--text-dim);
            margin-bottom: 14px;
        }

        /* ── TABS ── */
        .tabs {
            display: flex;
            gap: 0;
            padding: 0;
            background: #fff;
            margin-top: .5rem;
            /* border-bottom: 2px solid #e4e7eb; */
        }

        .tab-btn {
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: color .15s, border-color .15s;
        }

        .tab-btn.active {
            /* color: #009e3e; */
            color: var(--neon-cyan);
            /* border-bottom-color: #00C950; */
            border-bottom-color: var(--neon-cyan);
        }

        .tab-btn:hover:not(.active) {
            color: #111;
        }

        /* ── MAIN CONTENT ── */
        main {
            /* max-width: 680px; */
            /* margin: 0 auto; */
            padding: 1.5rem 2rem 2rem;
        }


        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* ══════════════════════════════
           STREAM CARDS
        ══════════════════════════════ */

        #tab-stream.active {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .stream-card {
            background: #fff;
            /* border: 1px solid #e4e7eb; */
            border: 1px solid var(--border);
            border-radius: 12px;
            height: 100%;
            /* margin-bottom: 14px; */
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, .05); */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: box-shadow .15s;
            margin-top: 0.5rem;
        }

        .stream-card:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, .09);
        }

        .stream-card-inner {
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        /* Green stacked-layers icon */
        .stream-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #e8f5ee;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stack-lines {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .stack-lines span {
            display: block;
            border-radius: 2px;
            background: #009e3e;
            width: 20px;
            height: 4px;
        }

        .stack-lines span:nth-child(1) {
            opacity: .4;
        }

        .stack-lines span:nth-child(2) {
            opacity: .7;
        }

        .stack-lines span:nth-child(3) {
            opacity: 1;
        }

        /* Announcement avatar */
        .anm-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #00C950;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
        }

        .stream-body {
            flex: 1;
            min-width: 0;
        }

        .stream-label {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .stream-line {
            font-size: 13px;
            color: #111827;
            margin-bottom: 2px;
        }

        .stream-line strong {
            font-weight: 500;
            font-size: 13px;
            color: var(--text-dim);
        }

        .stream-anm-text {
            font-size: 13px;
            color: #374151;
            line-height: 1.55;
            margin-top: 4px;
        }

        .stream-menu {
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 18px;
            padding: 2px 6px;
            border-radius: 6px;
            flex-shrink: 0;
            letter-spacing: .05em;
        }

        .stream-menu:hover {
            background: #f3f4f6;
        }

        /* Files inside stream card */
        .stream-files {
            padding: 0 16px 10px 70px;
        }

        .stream-file {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafb;
            border: 1px solid #e4e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .stream-file:last-child {
            margin-bottom: 0;
        }

        .stream-file i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .sf-name {
            flex: 1;
            font-weight: 600;
            color: #111827;
        }

        .sf-meta {
            color: #9ca3af;
            font-size: 11px;
            white-space: nowrap;
        }

        .sf-dl {
            color: #9ca3af;
            text-decoration: none;
            transition: color .12s;
        }

        .sf-dl:hover {
            color: #009e3e;
        }

        .stream-footer {
            padding: 9px 16px;
            border-top: 1px solid #f0f2f5;
            font-size: 12px;
            color: #9ca3af;
        }

        /* ══════════════════════════════
           CLASSWORK — student submissions
        ══════════════════════════════ */
        .cw-section-title {
            font-size: 11px;
            font-weight: 800;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cw-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e4e7eb;
        }

        /* Assignment card in classwork */
        .assign-card {
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            overflow: hidden;
            transition: box-shadow .15s;
            cursor: pointer;
            margin-top: 1.5rem;
        }

        .assign-card:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, .09);
        }

        .assign-inner {
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .assign-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #e8f5ee;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .assign-body {
            flex: 1;
            min-width: 0;
        }

        .assign-label {
            font-size: 12px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .assign-line {
            font-size: 13px;
            color: #111827;
            margin-bottom: 2px;
        }

        .assign-line strong {
            font-weight: 700;
        }

        .assign-footer {
            padding: 9px 16px;
            border-top: 1px solid #f0f2f5;
            font-size: 12px;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .submit-points {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .assign-pts {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
        }

        /* Submission list inside assignment (accordion) */
        .assign-submissions {
            border-top: 1px solid #e4e7eb;
            display: none;
            padding: 12px 16px;
            background: #fafafa;
        }

        .assign-submissions.open {
            display: block;
        }

        .sub-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 8px;
            margin-bottom: 7px;
            font-size: 12px;
        }

        .sub-row:last-child {
            margin-bottom: 0;
        }

        .sub-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dbeafe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: #1d4ed8;
            flex-shrink: 0;
        }

        .sub-name {
            flex: 1;
            font-weight: 600;
            color: #111827;
        }

        .sub-date {
            color: #9ca3af;
            font-size: 11px;
        }

        .sub-status {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 20px;
        }

        .sub-status.submitted {
            background: #dcfce7;
            color: #16a34a;
        }

        .sub-status.late {
            background: #fef9c3;
            color: #ca8a04;
        }

        .sub-status.missing {
            background: #fee2e2;
            color: #dc2626;
        }

        .sub-file-link {
            color: #9ca3af;
            text-decoration: none;
            transition: color .12s;
        }

        .sub-file-link:hover {
            color: #009e3e;
        }

        /* Submission count badge */
        .sub-count-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
            /* background: #dcfce7; */
            color: #16a34a;
            /* border: 1px solid #bbf7d0; */
            margin-left: 8px;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 38px;
            opacity: .18;
            display: block;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 13px;
            line-height: 1.6;
        }

        /* ── CREATE DROPDOWN ── */
        .create-wrap {
            /* position: fixed;
            bottom: 5%;
            right: 2%; */
            display: flex;
            justify-content: end;
            /* z-index: 999; */
        }

        .create-menu {
            display: none;
            position: absolute;
            /* bottom: calc(100% + 10px); */
            bottom: 90%;
            right: 70%;
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            min-width: 180px;
            overflow: hidden;
        }

        .create-menu.open {
            display: block;
        }

        .create-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            text-decoration: none;
            transition: background .12s;
        }

        .create-menu a:hover {
            background: #f3f4f6;
        }

        .create-menu a i {
            width: 18px;
            text-align: center;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            /* background: #00C950; */
            background-color: var(--neon-cyan);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-size: 14.5px;
            font-weight: 700;
            /* margin-right: 40%; */
            cursor: pointer;
            /* box-shadow: 0 4px 20px rgba(0, 201, 80, .4); */
            text-decoration: none;
            transition: background .15s, transform .15s;
        }

        .btn-create:hover {
            background: #009e3e;
            transform: translateY(-2px);
            color: #fff;
        }

        .pdf-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .75);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .pdf-modal-overlay.open {
            display: flex;
        }

        .pdf-modal {
            background: #1e1e1e;
            border-radius: 12px;
            width: 90vw;
            max-width: 960px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .pdf-modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: #2b2b2b;
            border-bottom: 1px solid #3a3a3a;
            flex-shrink: 0;
        }

        .pdf-icon {
            width: 32px;
            height: 32px;
            background: #e53e3e;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
        }

        .pdf-modal-title {
            flex: 1;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pdf-modal-actions {
            display: flex;
            gap: 8px;
        }

        .pdf-modal-actions a,
        .pdf-modal-actions button {
            background: #3a3a3a;
            border: none;
            border-radius: 8px;
            color: #d1d5db;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pdf-modal-actions a:hover,
        .pdf-modal-actions button:hover {
            background: #4a4a4a;
            color: #fff;
        }

        .btn-close-pdf {
            font-size: 16px;
            padding: 7px 12px !important;
        }

        .pdf-modal-body {
            flex: 1;
            overflow: hidden;
        }

        .pdf-modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Hero overlay */
        .hero-banner {
            display: flex;
            justify-content: start;
            align-items: center;
            /* align-items: flex-end; */
            padding: 24px 28px;
            height: 220px;
        }

        .hero-overlay-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }

        .class-badge {
            display: inline-block;
            background: #1447e6;
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .03em;
            margin-bottom: 8px;
        }

        .hero-title {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .hero-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, .85);
            margin-bottom: 6px;
        }

        .hero-tagline {
            font-size: 13px;
            color: rgba(255, 255, 255, .7);
            max-width: 520px;
        }

        /* Layout with sidebar */
        .content-layout {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
            /* border: 1px solid var(--border); */
            background-color: #ffffff;
            border: 1px solid #e4e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .content-layout main {
            flex: 1;
            min-width: 0;
            padding: 1.5rem 0 2rem;
        }

        .sidebar-col {
            width: 300px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            margin-top: 1.5rem;
        }

        .info-card {
            background: #fff;
            /* border: 1px solid #e4e7eb; */
            border-radius: 12px;
            padding: 18px;
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, .05); */
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .info-card-header {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .info-label {
            color: #9ca3af;
        }

        .info-value {
            font-weight: 600;
            color: #111827;
        }

        .donut-wrap {
            display: flex;
            justify-content: center;
            margin: 10px 0 18px;
        }

        .donut {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .donut-hole {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #1447e6;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #374151;
            margin-bottom: 8px;
        }

        .legend-row b {
            margin-left: auto;
        }

        .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-blue {
            background: #1447e6;
        }

        .dot-green {
            background: #00c950;
        }

        .dot-orange {
            background: #f59e0b;
        }

        .total-students {
            border-top: 1px solid #f0f2f5;
            margin-top: 10px;
            padding-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }

        .total-students b {
            color: #111827;
        }

        /* Icon variants + assignment due chip */
        .icon-blue {
            background: #dbeafe;
        }

        .icon-blue i {
            color: #1447e6;
        }

        .icon-orange {
            background: #ffedd4;
        }

        .icon-orange i {
            color: #f54900;
        }

        .due-chip {
            background: #fef3c7;
            color: #b45309;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("sidebar.php"); ?>

        <div class="rightbar">

            <?php
            /* ── Helpers ── */
            function getInitials(string $name): string
            {
                $parts = explode(' ', trim($name));
                $i = strtoupper(substr($parts[0], 0, 1));
                if (count($parts) > 1)
                    $i .= strtoupper(substr(end($parts), 0, 1));
                return $i;
            }
            $teacherName = $_SESSION['name'] ?? 'Teacher';
            $initials = getInitials($teacherName);

            /* ── Dynamic banner by subject name ── */
            function getBannerBg(string $subject): string
            {
                $n = strtolower($subject);
                if (str_contains($n, 'phil'))
                    return "url('../images/philosophy_picture.jpg')";
                if (str_contains($n, 'ucsp') || str_contains($n, 'cultur'))
                    return "url('../images/ucsp_picture.jpg')";
                if (str_contains($n, 'comput') || str_contains($n, 'css'))
                    return "url('../images/computer_picture.jpg')";
                if (str_contains($n, 'physical') || $n === 'pe')
                    return "url('../images/pe_picture.jpg')";
                if (str_contains($n, 'inquir') || str_contains($n, '3i'))
                    return "url('../images/3i_picture.jpg')";
                if (str_contains($n, 'entrep'))
                    return "url('../images/entrep_picture.jpg')";
                if (str_contains($n, 'work') || str_contains($n, 'immersion'))
                    return "url('../images/work_picture.jpg')";
                if (str_contains($n, 'media') || str_contains($n, 'information'))
                    return "url('../images/media_picture.jpg')";
                return "url('../images/philosophy_picture.jpg')";
            }
            $bannerBg = getBannerBg($classInfo['subject_name'] ?? '');

            /* ── Fetch announcements, assignments, and submissions ── */
            $tid = $_SESSION['teacher_id'] ?? 0;
            $announcements = ($subject_id && $teacherModel) ? $teacherModel->getAnnouncements($subject_id, $tid, $section_id) : [];

            $assignments = ($subject_id && $teacherModel) ? $teacherModel->getAssignments($subject_id, $tid, $section_id) : [];

            $progressStats = ($subject_id && $teacherModel)
                ? $teacherModel->getClassProgressStats($subject_id, $tid, $section_id ?? 0)
                : ['submitted' => 0, 'graded' => 0, 'pending' => 0, 'percentage' => 0, 'total_students' => 0];

            // For each assignment, load student submissions
            foreach ($assignments as &$asgn) {
                $asgn['submissions'] = ($teacherModel) ? $teacherModel->getSubmissions($asgn['id']) : [];
            }
            unset($asgn);
            ?>

            <!-- <div class="parent-icons">
                <div class="icons">
                    <i class="fa fa-users"></i>

                    <i class="fa fa-question-circle"></i>

                    <i class="fa fa-bars"></i>
                </div>
            </div> -->

            <!-- INVITE STUDENT MODAL -->
            <div id="inviteOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);
     z-index:9999;align-items:center;justify-content:center;" onclick="closeInviteModal()">
                <div style="background:var(--bs-body-bg,#fff);border-radius:14px;width:90%;max-width:480px;
              overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.15);" onclick="event.stopPropagation()">

                    <!-- Header -->
                    <div
                        style="padding:18px 20px 14px;border-bottom:1px solid #e4e7eb;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <h5 style="font-size:16px;font-weight:700;color:#111827;margin:0;">Invite Students</h5>
                            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">
                                <?= htmlspecialchars($classInfo['subject_name'] ?? '') ?> &middot;
                                <?= htmlspecialchars($classInfo['section'] ?? '') ?>
                            </p>
                        </div>
                        <button onclick="closeInviteModal()" style="background:none;border:none;font-size:18px;
              cursor:pointer;color:#9ca3af;padding:4px 8px;border-radius:8px;line-height:1;">✕</button>
                    </div>

                    <!-- Body -->
                    <div style="padding:16px 20px;">
                        <div style="position:relative;margin-bottom:10px;">
                            <span
                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px;">&#9906;</span>
                            <input type="text" id="inviteSearch" placeholder="Search name or email…"
                                oninput="filterInviteStudents()" style="width:100%;padding:8px 12px 8px 30px;font-size:13px;border:1px solid #e4e7eb;
                      border-radius:50px;outline:none;background:#f9fafb;color:#111827;">
                        </div>

                        <!-- Select all -->
                        <label
                            style="display:flex;align-items:center;gap:8px;padding:6px 10px;margin-bottom:6px;
                    background:#f3f4f6;border-radius:8px;font-size:12px;color:#6b7280;cursor:pointer;user-select:none;">
                            <input type="checkbox" id="inviteSelectAll" onchange="toggleInviteAll()"
                                style="accent-color:#00C950;width:14px;height:14px;">
                            Select all
                        </label>

                        <!-- Student list -->
                        <div id="inviteStudentList"
                            style="max-height:210px;overflow-y:auto;border:1px solid #e4e7eb;border-radius:10px;">
                            <?php foreach ($approvedStudents ?? [] as $stu):
                                $initials = strtoupper(substr($stu['name'], 0, 1));
                                $parts = explode(' ', trim($stu['name']));
                                if (count($parts) > 1)
                                    $initials .= strtoupper(substr(end($parts), 0, 1));

                                // Get invitation status from enrollment_invitations
                                $inviteStatus = 'none';
                                if ($teacherModel) {
                                    $inv = $teacherModel->getInvitationStatus(
                                        $stu['email'],
                                        (int) $subject_id,
                                        (int) ($section_id ?? 0)
                                    );
                                    if ($inv)
                                        $inviteStatus = $inv['status']; // 'pending' | 'accepted' | 'expired'
                                }

                                $isPending = ($inviteStatus === 'pending');
                                $isAccepted = ($inviteStatus === 'accepted');
                                $isDisabled = $isPending || $isAccepted;
                                ?>
                                <label class="invite-stu-row" style="display:flex;align-items:center;gap:15px;padding:15px 12px;
               border-bottom:1px solid #f0f2f5;
               cursor:<?= $isDisabled ? 'not-allowed' : 'pointer' ?>;
               opacity:<?= $isDisabled ? '0.55' : '1' ?>;
               transition:background .1s;" onmouseover="<?= !$isDisabled ? "this.style.background='#f9fafb'" : '' ?>"
                                    onmouseout="<?= !$isDisabled ? "this.style.background=''" : '' ?>">

                                    <input type="checkbox" class="invite-stu-check"
                                        value="<?= htmlspecialchars($stu['email']) ?>"
                                        data-name="<?= htmlspecialchars(strtolower($stu['name'])) ?>"
                                        data-email="<?= htmlspecialchars(strtolower($stu['email'])) ?>" <?= $isDisabled ? 'disabled' : '' ?> onchange="updateInviteCount()"
                                        style="accent-color:#00C950;width:14px;height:14px;flex-shrink:0;">

                                    <div style="width:30px;height:30px;border-radius:50%;background:#e8f5ee;flex-shrink:0;
                    display:flex;align-items:center;justify-content:center;
                    font-size:11px;font-weight:700;color:#009e3e;">
                                        <?= $initials ?>
                                    </div>

                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:13px;font-weight:600;color:#111827;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars($stu['name']) ?>
                                        </div>
                                        <div style="font-size:11px;color:#9ca3af;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars($stu['email']) ?>
                                        </div>
                                    </div>

                                    <!-- Invitation status badge -->
                                    <?php if ($isPending): ?>
                                        <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;
                         background:#fef9c3;color:#ca8a04;white-space:nowrap;flex-shrink:0;
                         border:1px solid #fde68a;">
                                            ⏳ Pending
                                        </span>
                                    <?php elseif ($isAccepted): ?>
                                        <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;
                         background:#dcfce7;color:#16a34a;white-space:nowrap;flex-shrink:0;
                         border:1px solid #bbf7d0;">
                                            ✓ Enrolled
                                        </span>
                                    <?php endif; ?>

                                </label>
                            <?php endforeach; ?>
                            <?php if (empty($approvedStudents)): ?>
                                <p style="text-align:center;padding:20px;font-size:13px;color:#9ca3af;">
                                    No approved students found.
                                </p>
                            <?php endif; ?>
                        </div>

                        <p id="inviteSelectedCount"
                            style="font-size:12px;color:#009e3e;font-weight:600;margin-top:8px;min-height:16px;"></p>
                    </div>

                    <!-- Footer -->
                    <div
                        style="padding:14px 20px;border-top:1px solid #e4e7eb;display:flex;justify-content:space-between;align-items:center;">
                        <button onclick="closeInviteModal()" style="background:none;border:1px solid #e4e7eb;border-radius:50px;
                     padding:8px 18px;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">
                            Cancel
                        </button>
                        <button id="inviteSendBtn" onclick="submitInvitations()" disabled style="background:#00C950;border:none;border-radius:50px;padding:8px 22px;
                     font-size:13px;font-weight:700;color:#fff;cursor:pointer;
                     display:flex;align-items:center;gap:6px;opacity:0.5;">
                            <i class="fa fa-paper-plane"></i> Send Invitations
                        </button>
                    </div>
                </div>
            </div>

            <!-- TOAST -->
            <div id="inviteToast" style="display:none;position:fixed;bottom:24px;right:24px;
     background:#111827;color:#fff;padding:12px 20px;border-radius:50px;
     font-size:13px;font-weight:600;z-index:10000;box-shadow:0 4px 20px rgba(0,0,0,.2);">
            </div>

            <!-- TOAST NOTIFICATION -->
            <div class="invite-toast" id="inviteToast"></div>

            <!-- HERO BANNER -->
            <div class="hero-banner" style="background-image:<?= $bannerBg ?>;">
                <div class="hero-overlay-content">
                    <?php if (!empty($classInfo['subject_code'])): ?>
                        <span class="class-badge"><?= htmlspecialchars($classInfo['subject_code']) ?></span>
                    <?php endif; ?>
                    <h2 class="hero-title"><?= htmlspecialchars($classInfo['subject_name'] ?? '') ?></h2>
                    <p class="hero-subtitle">
                        <?= htmlspecialchars($classInfo['subject_code'] ?? '') ?>
                        <?php if (!empty($classInfo['grade'])): ?> &middot;
                            <?= htmlspecialchars($classInfo['grade']) ?><?php endif; ?>
                        <?php if (!empty($classInfo['section'])): ?> &middot;
                            <?= htmlspecialchars($classInfo['section']) ?><?php endif; ?>
                    </p>
                    <p class="hero-tagline">Manage your class content, engage with students, and track their learning
                        progress.</p>
                </div>
            </div>

            <!-- HERO INFO (tabs only now) -->
            <div class="hero-info">
                <div class="tabs">
                    <button class="tab-btn active" data-tab="stream">Stream</button>
                    <button class="tab-btn" data-tab="classwork">Classwork</button>
                    <button class="tab-btn" data-tab="people">People</button>
                </div>
            </div>

            <div class="content-layout">
                <main>

                    <!-- ════════════ STREAM TAB ════════════ -->
                    <div class="tab-pane active" id="tab-stream">

                        <?php
                        // Merge modules + announcements into chronological feed (newest first)
                        $feed = [];
                        foreach ($cfModules ?? [] as $m) {
                            $feed[] = ['type' => 'module', 'data' => $m, 'time' => strtotime($m['posted_at'])];
                        }
                        foreach ($assignments as $asg) {
                            $feed[] = ['type' => 'assignment', 'data' => $asg, 'time' => strtotime($asg['created_at'])];
                        }
                        foreach ($announcements as $a) {
                            $feed[] = ['type' => 'announcement', 'data' => $a, 'time' => strtotime($a['posted_at'])];
                        }
                        usort($feed, fn($a, $b) => $b['time'] - $a['time']);
                        usort($feed, fn($a, $b) => $b['time'] - $a['time']);
                        ?>

                        <div class="create-wrap">
                            <div class="create-menu" id="createMenu">
                                <!-- <a
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>">
                                <i class="fa fa-layer-group"></i> Module
                            </a>
                            <a
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>">
                                <i class="fa fa-bullhorn"></i> Announcement
                            </a> -->
                                <!-- <a
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>">
                                <i class="fa fa-clipboard-list"></i> Assignment
                            </a> -->
                            </div>
                            <!-- <button class="btn-create" onclick="toggleCreateMenu()" id="createBtn">
                            <i class="fa fa-plus"></i> Create
                        </button> -->
                            <!-- Stream tab Create button -->
                            <a class="btn-create"
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>&tab=stream">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>

                        <?php if (empty($feed)): ?>
                            <div class="empty-state">
                                <i class="fa fa-layer-group"></i>
                                <p>No materials posted yet.<br>Click <strong>+ Create</strong> to start.</p>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($feed as $item):
                            $d = $item['data'];
                            ?>

                            <?php if ($item['type'] === 'announcement'): ?>
                                <!-- ANNOUNCEMENT CARD -->
                                <div class="stream-card">
                                    <div class="stream-card-inner">
                                        <div class="anm-avatar"><?= $initials ?></div>
                                        <div class="stream-body">
                                            <div class="stream-label">Announcement</div>
                                            <div class="stream-line">
                                                <strong><?= htmlspecialchars($d['title'] ?? '') ?></strong>
                                            </div>
                                        </div>
                                        <button class="stream-menu">⋮</button>
                                    </div>
                                    <div class="stream-footer">
                                        Date: <?= date('M d', strtotime($d['posted_at'])) ?>
                                    </div>
                                </div>

                            <?php elseif ($item['type'] === 'assignment'): ?>
                                <!-- ASSIGNMENT CARD (orange) -->
                                <div class="stream-card">
                                    <div class="stream-card-inner">
                                        <div class="stream-icon icon-orange"><i class="fa fa-clipboard-list"></i></div>
                                        <div class="stream-body">
                                            <div class="stream-label" style="color:#f54900;">New Assignment</div>
                                            <div class="stream-line"><strong><?= htmlspecialchars($d['title']) ?></strong></div>
                                            <?php if (!empty($d['description'])): ?>
                                                <div class="stream-anm-text"><?= htmlspecialchars($d['description']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="stream-menu">⋮</button>
                                    </div>
                                    <div class="stream-footer"
                                        style="display:flex;justify-content:space-between;align-items:center;">
                                        <span>Date: <?= date('M d', $item['time']) ?></span>
                                        <?php if (!empty($d['due_date'])): ?>
                                            <span class="due-chip">Due: <?= date('M d, Y', strtotime($d['due_date'])) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- MODULE CARD -->
                                <div class="stream-card">
                                    <div class="stream-card-inner">
                                        <div class="stream-icon">
                                            <div class="stack-lines"><span></span><span></span><span></span></div>
                                        </div>
                                        <div class="stream-body">
                                            <div class="stream-label">New Material</div>
                                            <div class="stream-line">
                                                <strong>Title :</strong> <?= htmlspecialchars($d['title']) ?>
                                            </div>
                                            <?php if (!empty($d['description'])): ?>
                                                <div class="stream-line">
                                                    <strong>Description:</strong> <?= htmlspecialchars($d['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="stream-menu">⋮</button>
                                    </div>

                                    <?php if (!empty($d['materials'])): ?>
                                        <div class="stream-files">
                                            <?php foreach ($d['materials'] as $mat):
                                                if (empty($mat['file_name']) || empty($mat['file_path']))
                                                    continue;
                                                $ext = strtolower(pathinfo($mat['file_name'], PATHINFO_EXTENSION));

                                                // Pick icon + color based on extension
                                                if (in_array($ext, ['ppt', 'pptx'])) {
                                                    $ico = 'fa-file-powerpoint';
                                                    $icoColor = '#d04423';
                                                } elseif (in_array($ext, ['doc', 'docx'])) {
                                                    $ico = 'fa-file-word';
                                                    $icoColor = '#1e5ebb';
                                                } else {
                                                    $ico = 'fa-file-pdf';
                                                    $icoColor = '#e53e3e';
                                                }

                                                $sizeKb = $mat['file_size'] > 0 ? round($mat['file_size'] / 1024, 1) . ' KB' : '';
                                                ?>
                                                <div class="stream-file"
                                                    onclick="openFileViewer('<?= htmlspecialchars($mat['file_path']) ?>', '<?= htmlspecialchars($mat['file_name']) ?>', '<?= $ext ?>')"
                                                    style="cursor:pointer;">
                                                    <i class="fa <?= $ico ?>" style="color:<?= $icoColor ?>"></i>
                                                    <span class="sf-name"><?= htmlspecialchars($mat['file_name']) ?></span>
                                                    <?php if ($sizeKb): ?>
                                                        <span class="sf-meta"><?= strtoupper($ext) ?> · <?= $sizeKb ?></span>
                                                    <?php endif; ?>
                                                    <a class="sf-dl" href="<?= htmlspecialchars($mat['file_path']) ?>"
                                                        download="<?= htmlspecialchars($mat['file_name']) ?>" target="_blank"
                                                        onclick="event.stopPropagation()" title="Download">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="stream-footer">
                                        Date: <?= date('M d', $item['time']) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>

                        <!-- CREATE DROPDOWN -->


                    </div><!-- /stream -->


                    <!-- ════════════ CLASSWORK TAB ════════════ -->
                    <!-- Shows ASSIGNMENTS and STUDENT SUBMISSIONS — not uploads -->
                    <div class="tab-pane" id="tab-classwork">

                        <!-- CREATE DROPDOWN -->
                        <div class="create-wrap">
                            <div class="create-menu" id="createMenu2">
                                <!-- <a
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>">
                                <i class="fa fa-layer-group"></i> Module
                            </a>
                            <a
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>">
                                <i class="fa fa-bullhorn"></i> Announcement
                            </a> -->
                                <!-- <a
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>">
                                <i class="fa fa-clipboard-list"></i> Assignment
                            </a> -->
                            </div>
                            <!-- <button class="btn-create" onclick="toggleCreateMenu2()" id="createBtn2">
                            <i class="fa fa-plus"></i> Create
                        </button> -->
                            <!-- <a class="btn-create" href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>" id="createBtn">
                            <i class="fa fa-plus"></i> Create
                        </a> -->

                            <!-- Classwork tab Create button -->
                            <a class="btn-create"
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>&tab=classwork">
                                <i class="fa fa-plus"></i> Create
                            </a>
                        </div>

                        <?php if (empty($assignments)): ?>
                            <div class="empty-state">
                                <i class="fa fa-clipboard-list"></i>
                                <p>No assignments yet.<br>Click <strong>+ Create</strong> to add one.</p>
                            </div>
                        <?php else: ?>

                            <!-- <p class="cw-section-title"><i class="fa fa-clipboard-list me-1"></i> Assignments</p> -->

                            <?php foreach ($assignments as $asgn):
                                $subCount = count($asgn['submissions'] ?? []);
                                ?>
                                <div class="assign-card"
                                    onclick="window.location.href='/learning_management/public/?url=student_works&assignment_id=<?= $asgn['id'] ?>&subject_id=<?= $subject_id ?>'">
                                    <!-- Assignment header — click to toggle submissions -->
                                    <div class="assign-inner">
                                        <div class="assign-icon">
                                            <div class="stack-lines"><span></span><span></span><span></span></div>
                                        </div>
                                        <div class="assign-body">
                                            <div class="assign-label">
                                                New Assignment
                                                <!-- Add this line below -->
                                                <span style="font-size:11px;font-weight:500;color:#6b7280;margin-left:8px;">
                                                    <?= htmlspecialchars($classInfo['subject_name'] ?? '') ?>
                                                </span>
                                            </div>
                                            <div class="assign-line">
                                                <strong>Title:</strong> <?= htmlspecialchars($asgn['title']) ?>
                                            </div>
                                            <?php if (!empty($asgn['description'])): ?>
                                                <div class="assign-line">
                                                    <strong>Description:</strong> <?= htmlspecialchars($asgn['description']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <button class="stream-menu" onclick="event.stopPropagation()">⋮</button>
                                    </div>
                                    <?php if (!empty($asgn['file_name']) && !empty($asgn['file_path'])): ?>
                                        <?php
                                        $ext = strtolower(pathinfo($asgn['file_name'], PATHINFO_EXTENSION));
                                        if (in_array($ext, ['ppt', 'pptx'])) {
                                            $ico = 'fa-file-powerpoint';
                                            $icoColor = '#d04423';
                                        } elseif (in_array($ext, ['doc', 'docx'])) {
                                            $ico = 'fa-file-word';
                                            $icoColor = '#1e5ebb';
                                        } else {
                                            $ico = 'fa-file-pdf';
                                            $icoColor = '#e53e3e';
                                        }
                                        $sizeKb = !empty($asgn['file_size']) && $asgn['file_size'] > 0
                                            ? round($asgn['file_size'] / 1024, 1) . ' KB' : '';
                                        ?>
                                        <div class="stream-files">
                                            <div class="stream-file"
                                                onclick="event.stopPropagation(); openFileViewer('<?= htmlspecialchars($asgn['file_path']) ?>', '<?= htmlspecialchars($asgn['file_name']) ?>', '<?= $ext ?>')"
                                                style="cursor:pointer;">
                                                <i class="fa <?= $ico ?>" style="color:<?= $icoColor ?>"></i>
                                                <span class="sf-name">
                                                    <?= htmlspecialchars($asgn['file_name']) ?>
                                                </span>
                                                <?php if ($sizeKb): ?>
                                                    <span class="sf-meta">
                                                        <?= strtoupper($ext) ?> ·
                                                        <?= $sizeKb ?>
                                                    </span>
                                                <?php endif; ?>
                                                <a class="sf-dl" href="<?= htmlspecialchars($asgn['file_path']) ?>"
                                                    download="<?= htmlspecialchars($asgn['file_name']) ?>"
                                                    onclick="event.stopPropagation()" title="Download">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="assign-footer">
                                        <span>Due Date:
                                            <?= !empty($asgn['due_date']) ? date('M d', strtotime($asgn['due_date'])) : '—' ?>
                                            <?= !empty($asgn['due_time']) ? ' at ' . date('h:i A', strtotime($asgn['due_time'])) : '' ?>
                                        </span>

                                        <div class="submit-points">
                                            <?php if ($subCount > 0): ?>
                                                <span class="sub-count-badge"><?= $subCount ?> submitted</span>
                                            <?php endif; ?>

                                            <span class="assign-pts"><?= (int) ($asgn['points'] ?? 100) ?> pts</span>
                                        </div>
                                    </div>

                                    <!-- Student submissions (accordion) -->
                                    <div class="assign-submissions">
                                        <?php if (empty($asgn['submissions'])): ?>
                                            <p style="font-size:12px;color:#9ca3af;text-align:center;padding:.75rem 0;">
                                                <i class="fa fa-inbox me-1"></i> No submissions yet.
                                            </p>
                                        <?php else: ?>
                                            <?php foreach ($asgn['submissions'] as $sub):
                                                $stuInitials = getInitials($sub['student_name'] ?? 'S');
                                                $status = $sub['status'] ?? 'submitted';
                                                $statusLabel = ucfirst($status);

                                                $subExt = '';
                                                $subFilePath = '';
                                                $subFileName = '';
                                                $subOriginalName = '';
                                                if (!empty($sub['file_path'])) {
                                                    $subFilePath = $sub['file_path'];
                                                    if (!str_starts_with($subFilePath, '/') && !str_starts_with($subFilePath, 'http')) {
                                                        $subFilePath = '/learning_management/' . $subFilePath;
                                                    }
                                                    $subFileName = basename($sub['file_path']);
                                                    $subExt = strtolower(pathinfo($subFileName, PATHINFO_EXTENSION));

                                                    // Strip the unique prefix (e.g. "69cf73c9475c0_") to get the original name
                                                    $subOriginalName = preg_replace('/^[a-f0-9]+_/', '', $subFileName);
                                                }

                                                // Pick icon based on extension
                                                if (in_array($subExt, ['ppt', 'pptx'])) {
                                                    $subIco = 'fa-file-powerpoint';
                                                    $subIcoColor = '#d04423';
                                                } elseif (in_array($subExt, ['doc', 'docx'])) {
                                                    $subIco = 'fa-file-word';
                                                    $subIcoColor = '#1e5ebb';
                                                } elseif ($subExt === 'pdf') {
                                                    $subIco = 'fa-file-pdf';
                                                    $subIcoColor = '#e53e3e';
                                                } else {
                                                    $subIco = 'fa-file-arrow-down';
                                                    $subIcoColor = '#6b7280';
                                                }

                                                // Format submitted time — show time if today, otherwise date + time
                                                $submittedAt = '';
                                                $submittedTime = '';
                                                if (!empty($sub['submitted_at'])) {
                                                    $ts = strtotime($sub['submitted_at']);
                                                    $submittedAt = date('M d', $ts);
                                                    $submittedTime = date('h:i A', $ts); // e.g. 02:35 PM
                                                }
                                                ?>
                                                <div class="sub-row">
                                                    <div class="sub-avatar"><?= $stuInitials ?></div>

                                                    <!-- Student name -->
                                                    <span class="sub-name"><?= htmlspecialchars($sub['student_name'] ?? '—') ?></span>

                                                    <!-- File chip — shows cleaned original filename, clickable to open viewer -->
                                                    <?php if (!empty($sub['file_path'])): ?>
                                                        <a class="sub-file-chip" href="javascript:void(0)"
                                                            onclick="openFileViewer('<?= htmlspecialchars($subFilePath) ?>', '<?= htmlspecialchars($subFileName) ?>', '<?= $subExt ?>')"
                                                            title="View <?= htmlspecialchars($subOriginalName) ?>">
                                                            <i class="fa <?= $subIco ?>" style="color:<?= $subIcoColor ?>;"></i>
                                                            <span><?= htmlspecialchars($subOriginalName) ?></span>
                                                        </a>
                                                    <?php else: ?>
                                                        <span style="flex:1;"></span>
                                                    <?php endif; ?>

                                                    <!-- Submitted date + time instead of download icon -->
                                                    <?php if ($submittedAt): ?>
                                                        <span class="sub-date">
                                                            <?= $submittedAt ?> · <span
                                                                style="color:#374151;font-weight:600;"><?= $submittedTime ?></span>
                                                        </span>
                                                    <?php endif; ?>

                                                    <span class="sub-status <?= $status ?>"><?= $statusLabel ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>



                        <?php endif; ?>

                    </div><!-- /classwork -->

                    <div class="tab-pane" id="tab-people">
                        <div class="people-header">
                            <div>
                                <h4 style="font-size:16px;font-weight:800;color:#111827;margin:0;">
                                    <?= count($enrolledStudents ?? []) ?> students
                                </h4>
                                <!-- <p style="font-size:13px;color:#6b7280;margin:0;"><?= count($enrolledStudents ?? []) ?>
                                Students</p> -->
                            </div>
                            <button onclick="openInviteModal()" style="background:#00C950;color:#fff;border:none;border-radius:50px;
   padding:10px 20px;font-size:13px;font-weight:700;cursor:pointer;
   display:flex;align-items:center;gap:8px;">
                                <i class="fa fa-envelope"></i> Invite Student
                            </button>
                        </div>
                        <div class="list-people">
                            <?php if (empty($enrolledStudents)): ?>
                                <div class="empty-state">
                                    <i class="fa fa-users"></i>
                                    <p>No students enrolled yet.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($enrolledStudents as $stu):
                                    $stuInitial = strtoupper(substr($stu['name'], 0, 1));
                                    $sectionLabel = $stu['section_name'];
                                    ?>
                                    <div class="student">
                                        <div class="student-header">
                                            <div class="icon">
                                                <span><?= $stuInitial ?></span>
                                            </div>
                                            <p><?= htmlspecialchars($stu['name']) ?></p>
                                        </div>
                                        <div class="student-section">
                                            <p><?= htmlspecialchars($sectionLabel) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </main>
                <aside class="sidebar-col">
                    <div class="info-card">
                        <div class="info-card-header"><i class="fa fa-circle-info"></i> About this class</div>
                        <div class="info-row">
                            <span class="info-label">Subject Code</span>
                            <span class="info-value">
                                <?= htmlspecialchars($classInfo['subject_code'] ?? '—') ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Teacher</span>
                            <span class="info-value">
                                <?= htmlspecialchars($teacherName) ?>
                            </span>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-card-header"><i class="fa fa-chart-line"></i> Class Progress Overview</div>
                        <div class="donut-wrap">
                            <div class="donut"
                                style="background: conic-gradient(#1447e6 <?= $progressStats['percentage'] * 3.6 ?>deg, #e9ecf1 0deg);">
                                <div class="donut-hole">
                                    <?= $progressStats['percentage'] ?>%
                                </div>
                            </div>
                        </div>
                        <div class="legend-row"><span class="dot dot-blue"></span> Submitted <b>
                                <?= $progressStats['submitted'] ?>
                            </b></div>
                        <div class="legend-row"><span class="dot dot-green"></span> Graded <b>
                                <?= $progressStats['graded'] ?>
                            </b></div>
                        <div class="legend-row"><span class="dot dot-orange"></span> Pending <b>
                                <?= $progressStats['pending'] ?>
                            </b></div>
                        <div class="total-students">Total Students: <b>
                                <?= $progressStats['total_students'] ?>
                            </b></div>
                    </div>
                </aside>
            </div>

            <!-- ANNOUNCEMENT MODAL -->
            <div class="modal fade" id="announceModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="fa fa-bullhorn text-warning me-2"></i>New Announcement
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="/learning_management/public/?url=save_announcement">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Title</label>
                                    <input type="text" name="title" class="form-control"
                                        placeholder="Announcement title…" required>
                                </div>
                                <div>
                                    <label class="form-label fw-semibold">Message</label>
                                    <textarea name="body" class="form-control" rows="5"
                                        placeholder="Write your message…" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fa fa-bullhorn me-1"></i> Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ASSIGNMENT MODAL -->
            <div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="fa fa-clipboard-list text-primary me-2"></i>New Assignment
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="/learning_management/public/?url=save_assignment">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Assignment title…"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="4"
                                        placeholder="Instructions…"></textarea>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Due Date</label>
                                        <input type="date" name="due_date" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Points</label>
                                        <input type="number" name="points" class="form-control" value="100" min="1">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-paper-plane me-1"></i> Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div><!-- /rightbar -->

        <!-- <?php if (!empty($_SESSION['save_success'])): ?>
            <?php unset($_SESSION['save_success']); ?>
            <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body text-center py-4">
                            <div
                                style="width:56px;height:56px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                                <i class="fa fa-circle-check text-success" style="font-size:28px;"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Saved!</h6>
                            <p class="text-muted small mb-0">Your content has been posted.</p>
                        </div>
                        <div class="modal-footer justify-content-center border-0 pt-0">
                            <button type="button" class="btn btn-success btn-sm px-4" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>document.addEventListener("DOMContentLoaded", () => new bootstrap.Modal(document.getElementById("successModal")).show());</script>
        <?php endif; ?> -->

        <!-- PDF VIEWER MODAL -->
        <div class="pdf-modal-overlay" id="pdfModalOverlay" onclick="closePdfViewer(event)">
            <div class="pdf-modal" onclick="event.stopPropagation()">
                <div class="pdf-modal-header">
                    <div class="pdf-icon"><i class="fa fa-file-pdf"></i></div>
                    <span class="pdf-modal-title" id="pdfModalTitle">Document</span>
                    <div class="pdf-modal-actions">
                        <a id="pdfDownloadBtn" href="#" target="_blank">
                            <i class="fa fa-download"></i> Download
                        </a>
                        <!-- ✕ button now calls closePdfViewerBtn() -->
                        <button class="btn-close-pdf" onclick="closePdfViewerBtn()">✕</button>
                    </div>
                </div>
                <div class="pdf-modal-body">
                    <iframe id="pdfModalFrame" src="" allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <!-- SAVE SUCCESS TOAST -->
        <div id="saveToast" style="
    display:none;
    position:fixed;
    bottom:28px;
    right:28px;
    background:#111827;
    color:#fff;
    padding:13px 22px;
    border-radius:50px;
    font-size:13px;
    font-weight:600;
    z-index:10001;
    box-shadow:0 4px 20px rgba(0,0,0,.25);
    align-items:center;
    gap:10px;
">
            <span style="color:#00C950;font-size:16px;">✓</span>
            <span id="saveToastMsg">Saved successfully!</span>
        </div>


    </div>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===============================
        // INVITE MODAL
        // ===============================
        window.openInviteModal = function () {
            document.getElementById('inviteOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        window.closeInviteModal = function () {
            document.getElementById('inviteOverlay').style.display = 'none';
            document.body.style.overflow = '';
        };

        window.filterInviteStudents = function () {
            const q = document.getElementById('inviteSearch').value.toLowerCase();
            document.querySelectorAll('.invite-stu-row').forEach(row => {
                const name = row.querySelector('.invite-stu-check').dataset.name;
                const email = row.querySelector('.invite-stu-check').dataset.email;
                row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
            });
            updateInviteCount();
        };

        window.toggleInviteAll = function () {
            const checked = document.getElementById('inviteSelectAll').checked;
            document.querySelectorAll('.invite-stu-check').forEach(c => {
                // Skip disabled checkboxes (pending/accepted students)
                if (!c.disabled && c.closest('.invite-stu-row').style.display !== 'none') {
                    c.checked = checked;
                }
            });
            updateInviteCount();
        };

        window.updateInviteCount = function () {
            const total = document.querySelectorAll('.invite-stu-check:checked').length;
            const countEl = document.getElementById('inviteSelectedCount');
            const btn = document.getElementById('inviteSendBtn');
            countEl.textContent = total ? `${total} student${total > 1 ? 's' : ''} selected` : '';
            btn.disabled = total === 0;
            btn.style.opacity = total === 0 ? '0.5' : '1';
        };

        // ── SINGLE definition — sends one by one with X-Requested-With header ──
        window.submitInvitations = function () {
            const emails = [...document.querySelectorAll('.invite-stu-check:checked')].map(c => c.value);
            if (!emails.length) return;

            const btn = document.getElementById('inviteSendBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending…';

            const promises = emails.map(email => {
                const form = new FormData();
                form.append('student_email', email);
                form.append('subject_id', '<?= $subject_id ?>');
                form.append('grade_level_id', '<?= $grade_level_id ?>');
                form.append('section_id', '<?= $section_id ?? 0 ?>');

                return fetch('/learning_management/public/?url=send_invitation', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: form
                })
                    .then(res => res.json())
                    .catch(() => ({ success: false }));
            });

            Promise.all(promises).then(results => {
                const successCount = results.filter(r => r && r.success).length;
                const toast = document.getElementById('inviteToast');

                closeInviteModal();

                toast.style.background = successCount > 0 ? '#111827' : '#dc2626';
                toast.textContent = successCount > 0
                    ? `✓ ${successCount} invitation${successCount > 1 ? 's' : ''} sent!`
                    : `✗ Failed to send. Please try again.`;
                toast.style.display = 'block';

                // Save tab preference BEFORE reload
                sessionStorage.setItem('activeTab', 'people');

                setTimeout(() => {
                    toast.style.display = 'none';
                    window.location.reload();
                }, 1500);
            });
        };

        // ===============================
        // CREATE MENU
        // ===============================
        window.toggleCreateMenu = function () {
            document.getElementById('createMenu')?.classList.toggle('open');
        };

        window.toggleCreateMenu2 = function () {
            document.getElementById('createMenu2')?.classList.toggle('open');
        };

        // ===============================
        // DOM READY
        // ===============================
        document.addEventListener("DOMContentLoaded", function () {

            // TABS
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            function activateTab(tabName) {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                const btn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
                const pane = document.getElementById('tab-' + tabName);
                if (btn) btn.classList.add('active');
                if (pane) pane.classList.add('active');
            }

            // Restore tab after reload (set by submitInvitations)
            const savedTab = sessionStorage.getItem('activeTab');
            if (savedTab) {
                activateTab(savedTab);
                sessionStorage.removeItem('activeTab');
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    activateTab(btn.dataset.tab);
                });
            });

            // ── AUTO-TAB + TOAST on redirect back ──────────────────────
            (function () {
                const params = new URLSearchParams(window.location.search);
                const saved = params.get('saved'); // 'stream' | 'classwork' | null
                if (!saved) return;

                // Activate the right tab
                activateTab(saved === 'classwork' ? 'classwork' : 'stream');

                // Show toast
                const toast = document.getElementById('saveToast');
                const msg = document.getElementById('saveToastMsg');
                if (!toast) return;

                msg.textContent = saved === 'classwork'
                    ? 'Assignment created successfully!'
                    : 'Content created successfully!';

                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity .4s ease';
                    setTimeout(() => {
                        toast.style.display = 'none';
                        toast.style.opacity = '1';
                        toast.style.transition = '';
                    }, 400);
                }, 3000);

                // Clean URL (remove ?saved= without reload)
                const cleanUrl = window.location.href.replace(/&saved=[^&]*/, '');
                window.history.replaceState({}, '', cleanUrl);
            })();

            // CLOSE CREATE MENU ON OUTSIDE CLICK
            window.addEventListener('click', function (e) {
                const btn1 = document.getElementById('createBtn');
                const btn2 = document.getElementById('createBtn2');
                if (btn1 && !btn1.contains(e.target)) {
                    document.getElementById('createMenu')?.classList.remove('open');
                }
                if (btn2 && !btn2.contains(e.target)) {
                    document.getElementById('createMenu2')?.classList.remove('open');
                }
            });

            // PDF VIEWER
            window.openFileViewer = function (filePath, fileName, ext) {
                const viewable = ['pdf'];
                if (!filePath.startsWith('/') && !filePath.startsWith('http')) {
                    filePath = '/learning_management/' + filePath;
                }
                if (viewable.includes(ext.toLowerCase())) {
                    document.getElementById('pdfModalTitle').textContent = fileName;
                    document.getElementById('pdfModalFrame').src = filePath;
                    document.getElementById('pdfDownloadBtn').href = filePath;
                    document.getElementById('pdfModalOverlay').classList.add('open');
                    document.body.style.overflow = 'hidden';
                } else {
                    window.open(filePath, '_blank');
                }
            };

            window.closePdfViewer = function (e) {
                if (e && e.target !== document.getElementById('pdfModalOverlay')) return;
                closePdfViewerBtn();
            };

            window.closePdfViewerBtn = function () {
                document.getElementById('pdfModalOverlay').classList.remove('open');
                document.getElementById('pdfModalFrame').src = '';
                document.body.style.overflow = '';
            };

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closePdfViewerBtn();
            });
        });
    </script>

</body>

</html>