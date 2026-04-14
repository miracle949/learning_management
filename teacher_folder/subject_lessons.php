<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lessons (Teacher View)</title>
    <link rel="stylesheet" href="../css_folder/lessons.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('../Poppins/Poppins-Regular.ttf') format('truetype');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Titan';
            src: url('../Titan_One/TitanOne-Regular.ttf') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --green: #4CAF7D;
            --green-dark: #2D6A4F;
            --green-light: #E8F5EE;
            --green-mid: #c8e6d6;
            --orange: #FF8A65;
            --orange-light: #FFF3EF;
            --orange-dark: #bf5b3a;
            --bg: #F9FBF9;
            --white: #ffffff;
            --card-border: rgba(0, 0, 0, 0.07);
            --text: #2D6A4F;
            --text-dark: #1a3a2a;
            --text-muted: #7a9a8a;
            --sidebar-bg: #ffffff;
            --shadow: 0 2px 12px rgba(76, 175, 125, 0.08);
            --green-text: #4a6a58;
        }

        /* ── SIDEBAR / NAV (teacher layout) ── */
        .container-fluid {
            background-color: #ffffff;
            overflow: hidden;
        }

        .container-fluid nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem 1rem 1rem;
            background-color: #ffffff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border-left: 1px solid rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .container-fluid nav .nav-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .container-fluid nav h2 {
            font-size: 18px;
            margin: 0;
            text-transform: uppercase;
            font-family: "Titan", sans-serif;
        }

        .container-fluid nav h2 b {
            color: var(--green);
            font-family: "Titan", sans-serif;
        }

        .container-fluid nav form button {
            padding: 8px 30px;
            border-radius: 10px;
            border: none;
            background-color: var(--green);
            color: white;
            font-weight: 600;
            font-size: 14.5px;
        }

        .container-fluid .sidebar {
            width: 225px;
            height: 100%;
            background-color: #ffffff;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .container-fluid .sidebar .sidebar-menu ul {
            padding: 0.3px 1rem 0px 1rem;
        }

        .container-fluid .sidebar .sidebar-menu ul .sidebar-category h5 {
            margin: 10px 6px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            color: #808080;
        }

        .container-fluid .sidebar .sidebar-menu ul li {
            list-style: none;
            line-height: 50px;
            padding: 0 10px;
            border-radius: 10px;
            margin-top: 0.5rem;
        }

        .container-fluid .sidebar .sidebar-menu ul li:hover {
            background-color: #E7E8EB;
        }

        .container-fluid .sidebar .sidebar-menu ul li a {
            display: flex;
            justify-content: left;
            align-items: center;
            gap: 1rem;
            color: var(--green-text);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
        }

        .container-fluid .sidebar .sidebar-menu ul li a .fa {
            font-size: 18px;
        }

        .container-fluid .sidebar .sidebar-menu ul li.active {
            background-color: var(--green);
            color: #ffffff;
        }

        .container-fluid .sidebar .sidebar-menu ul li.active a {
            color: #ffffff;
        }

        .container-fluid .sidebar .sidebar-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: left;
            align-items: center;
            gap: 1rem;
            font-size: 15.5px;
            font-weight: 600;
            color: var(--green-text);
            padding: 26px;
        }

        .container-fluid .sidebar .sidebar-footer .fa {
            font-size: 18px;
        }

        .container-fluid .sidebar .sidebar-footer p {
            margin: 0;
        }

        .container-fluid .rightbar {
            width: calc(100% - 235px);
            height: 100vh;
            overflow-y: auto;
            margin-left: 235px;
            border-left: 1px solid rgba(0, 0, 0, 0.1);
            background-color: #F7F9F8;
        }

        .container-fluid .sidebar-logo {
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .container-fluid .sidebar-logo p {
            font-size: 22px;
            margin: 0;
            font-family: "Titan", sans-serif;
            color: var(--green);
        }

        .container-fluid .sidebar-logo p b {
            color: #212529;
            font-family: "Titan", sans-serif;
        }

        .container-fluid .sidebar-logo .logo-icon {
            padding: 11px;
            background-color: var(--green-light);
            border-radius: 10px;
        }

        .container-fluid .sidebar-logo .logo-icon .fa-solid {
            color: var(--green);
            font-size: 20px;
        }

        .container-fluid .sidebar .sidebar-menu {
            margin-top: 1rem;
        }

        /* ── LESSON CONTENT STYLES ── */
        .lesson-section {
            margin-bottom: 28px;
            font-size: 14.5px;
            line-height: 25px;
        }

        .lesson-section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--green);
            margin: 0 0 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--green-light, #f0fdf4);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* VIDEOS */
        .video-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 16px;
            background: #fff;
        }

        .video-card iframe {
            width: 100%;
            height: 280px;
            display: block;
            border: none;
        }

        .video-info {
            padding: 12px 16px;
        }

        .video-title {
            font-weight: 600;
            font-size: 14px;
            margin: 0;
            color: #1a1a2e;
        }

        /* IMAGES */
        .db-images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 14px;
        }

        .db-image-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            cursor: pointer;
            transition: box-shadow .2s;
        }

        .db-image-card:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, .09);
        }

        .db-image-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .db-image-caption {
            padding: 10px 12px;
            font-size: 13px;
            color: #555;
        }

        /* FLASHCARDS */
        .fc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .fc-item {
            height: 140px;
            perspective: 1000px;
            cursor: pointer;
        }

        .fc-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform .4s;
            border-radius: 10px;
        }

        .fc-item.flipped .fc-inner {
            transform: rotateY(180deg);
        }

        .fc-front,
        .fc-back {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            border-radius: 10px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .fc-front {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .fc-back {
            background: #faf5ff;
            border-color: #e9d5ff;
            transform: rotateY(180deg);
        }

        .fc-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            opacity: .5;
            margin-bottom: 5px;
            display: block;
        }

        .fc-front .fc-label {
            color: #065f46;
        }

        .fc-back .fc-label {
            color: #6b21a8;
        }

        .fc-text {
            font-size: 12px;
            font-weight: 600;
            line-height: 1.4;
            color: #065f46;
            display: block;
        }

        .fc-back .fc-text {
            color: #4c1d95;
        }

        .fc-hint {
            font-size: 10px;
            color: #aaa;
            margin-top: 6px;
            display: block;
        }

        /* ACTIVITY read-only */
        .activity-block {
            margin-bottom: 24px;
        }

        .activity-intro {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .activity-intro-icon {
            font-size: 28px;
            flex-shrink: 0;
        }

        .activity-intro-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 4px;
        }

        .activity-intro-desc {
            font-size: 13px;
            color: #555;
            margin: 0;
        }

        .activity-meta-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .meta-pill {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .pill-purple {
            background: #ede9ff;
            color: #5c44e8;
        }

        .pill-green {
            background: #ecfdf5;
            color: #065f46;
        }

        .pill-orange {
            background: #fff7ed;
            color: #c2410c;
        }

        .activity-question {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 12px;
            background: #fff;
        }

        .aq-num {
            font-size: 11px;
            font-weight: 700;
            color: var(--orange, #f59e0b);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0 0 5px;
        }

        .aq-text {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0 0 12px;
            line-height: 1.5;
        }

        /* QUIZ read-only */
        .q-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px 20px;
            margin-bottom: 14px;
            background: #fff;
        }

        .q-number {
            font-size: 11px;
            font-weight: 700;
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: .06em;
            margin: 0 0 6px;
        }

        .q-text {
            font-size: 15px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0 0 14px;
            line-height: 1.5;
        }

        .q-choices {
            display: grid;
            gap: 8px;
        }

        .q-choice {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1.5px solid #e5e7eb;
            font-size: 14px;
            color: #333;
        }

        .choice-letter {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
            color: #555;
        }

        .q-choice.correct-ans {
            border-color: #22c55e;
            background: #f0fdf4;
        }

        .q-choice.correct-ans .choice-letter {
            background: #22c55e;
            color: #fff;
        }

        /* QUIZ intro (teacher view) */
        .quiz-intro {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            background: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 16px;
            width: 100%;
        }

        .quiz-intro-icon {
            font-size: 28px;
            flex-shrink: 0;
        }

        .quiz-intro-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 4px;
        }

        .quiz-intro-desc {
            font-size: 13px;
            color: #555;
            margin: 0;
        }

        /* MODULE HEADER */
        .module-header-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
        }

        .module-header-card .module-header-banner {
            height: 10px;
            background: linear-gradient(90deg, var(--green) 0%, #34d399 100%);
        }

        .module-header-card .module-header-body {
            padding: 20px 24px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .module-header-card .module-header-left {
            flex: 1;
            min-width: 0;
        }

        .module-header-card .module-header-tag {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--green);
            margin: 0 0 6px;
        }

        .module-header-card .module-header-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 8px;
            line-height: 1.3;
        }

        .module-header-card .module-header-desc {
            font-size: 13.5px;
            color: #555;
            margin: 0;
            line-height: 1.6;
        }

        .module-header-card .module-header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 6px;
            flex-shrink: 0;
        }

        .module-header-card .module-stat {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #666;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 5px 14px;
        }

        .module-header-card .module-stat i {
            color: var(--green);
            font-size: 12px;
        }

        .module-stat-num {
            font-weight: 700;
            color: #1a1a2e;
        }

        /* TEACHER VIEW BADGE */
        .teacher-view-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #c2410c;
        }

        /* LIGHTBOX */
        .db-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .86);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .db-lightbox.open {
            display: flex;
        }

        .db-lightbox img {
            max-width: 92vw;
            max-height: 90vh;
            border-radius: 10px;
        }

        .db-lightbox-close {
            position: absolute;
            top: 20px;
            right: 26px;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            background: none;
            border: none;
        }

        /* SIDEBAR lesson list */
        .sidebar-menu li.active-lesson>a {
            color: var(--green) !important;
            font-weight: 600 !important;
        }

        .sidebar-menu li.active-lesson .fa {
            color: var(--green) !important;
        }

        .sidebar-menu li .fa-circle {
            color: var(--green) !important;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("sidebar.php") ?>

        <div class="rightbar">
            <?php include("nav.php") ?>

            <?php
            // ── Variables set by TeacherController::subject_lessons_teacher() ──
            // $subjectId, $moduleId, $module, $lessons, $lessonId, $lesson
            // $images, $videos, $flashcards, $activityData, $quizData, $totalLessons
            
            function youtubeEmbedT(string $url): string
            {
                $id = '';
                if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m))
                    $id = $m[1];
                elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m))
                    $id = $m[1];
                return $id ? "https://www.youtube.com/embed/{$id}" : $url;
            }

            $currentIndex = 1;
            $prevLessonId = null;
            $nextLessonId = null;
            foreach ($lessons as $i => $l) {
                if ($l['id'] == $lessonId) {
                    $currentIndex = $i + 1;
                    $prevLessonId = $lessons[$i - 1]['id'] ?? null;
                    $nextLessonId = $lessons[$i + 1]['id'] ?? null;
                    break;
                }
            }

            function tUrl(int $subjectId, int $moduleId, int $lessonId): string
            {
                return "/learning_management/public/?url=subject_lessons_teacher&subject_id={$subjectId}&id={$moduleId}&lesson={$lessonId}";
            }
            ?>

            <!-- ── NAVBAR (teacher — no progress bar needed, show teacher badge) ── -->
            <div class="navbar-lessons"
                style="display:flex;align-items:center;justify-content:space-between;padding:0 2rem;height:68px;background:#fff;border-bottom:1px solid rgba(0,0,0,0.08);box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                <div style="display:flex;align-items:center;gap:10px;">
                    <a href="/learning_management/public/?url=view_modules_teacher&subject_id=<?= (int) $subjectId ?>"
                        style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--green-text);text-decoration:none;padding:6px 12px;border:1px solid #e5e7eb;border-radius:20px;background:#fff;">
                        <i class="fa fa-arrow-left"></i> Back to Modules
                    </a>
                </div>
                <div class="teacher-view-badge">
                    <i class="fa fa-eye"></i> Teacher Preview — Read Only
                </div>
            </div>

            <!-- ── MODULE HEADER CARD ── -->
            <div class="module-header-card">
                <div class="module-header-banner"></div>
                <div class="module-header-body">
                    <div class="module-header-left">
                        <p class="module-header-tag">📚 <?= htmlspecialchars($module['subject_name'] ?? 'Module') ?></p>
                        <h2 class="module-header-title"><?= htmlspecialchars($module['title'] ?? '') ?></h2>
                        <?php if (!empty($module['description'])): ?>
                            <p class="module-header-desc"><?= nl2br(htmlspecialchars($module['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="module-header-right">
                        <div class="module-stat">
                            <i class="fa fa-book-open"></i>
                            <span><span class="module-stat-num"><?= $totalLessons ?></span> Lessons</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── BODY ── -->
            <div class="body-lessons">

                <!-- LESSON SIDEBAR -->
                <div class="sidebar-lessons">
                    <h4>Lessons</h4>
                    <div class="sidebar-menu">
                        <ul>
                            <?php foreach ($lessons as $i => $l):
                                $isActive = ($l['id'] == $lessonId);
                                $cls = $isActive ? 'active-lesson' : '';
                                $lessonNum = $i + 1;
                                $rawTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $l['title']);
                                ?>
                                <li class="<?= $cls ?>">
                                    <a href="<?= tUrl($subjectId, $moduleId, $l['id']) ?>">
                                        <i class="fa fa-circle lesson-icon-status"></i>
                                        <span>Lesson <?= $lessonNum ?>: <?= htmlspecialchars($rawTitle) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- VIEW LESSONS -->
                <div class="view-lessons">

                    <?php if (!$lesson): ?>
                        <div style="text-align:center;padding:60px 40px;color:#aaa;">
                            <i class="fa fa-book-open" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                            <p style="font-size:14px;margin:0;">Choose a lesson from the sidebar.</p>
                        </div>
                    <?php else: ?>

                            <?php $cleanTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $lesson['title']); ?>
                            <h3 id="lesson-title">Lesson <?= $currentIndex ?>: <?= htmlspecialchars($cleanTitle) ?></h3>

                            <div class="view-lessons-body" style="padding: 10px 1.9rem;">

                                <!-- 1. LESSON TEXT CONTENT -->
                                <?php if (!empty($lesson['content'])): ?>
                                            <div class="lesson-section">
                                                <?= nl2br(htmlspecialchars($lesson['content'])) ?>
                                            </div>
                                <?php endif; ?>

                                <!-- 2. VIDEOS -->
                                <?php if (!empty($videos)): ?>
                                            <div class="lesson-section">
                                                <p class="lesson-section-title"><i class="fa fa-video"></i> Videos</p>
                                                <?php foreach ($videos as $vid): ?>
                                                            <div class="video-card">
                                                                <iframe src="<?= htmlspecialchars(youtubeEmbedT($vid['file_path'])) ?>"
                                                                    allowfullscreen loading="lazy"
                                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                                                </iframe>
                                                                <?php if (!empty($vid['title'])): ?>
                                                                            <div class="video-info">
                                                                                <p class="video-title"><?= htmlspecialchars($vid['title']) ?></p>
                                                                            </div>
                                                                <?php endif; ?>
                                                            </div>
                                                <?php endforeach; ?>
                                            </div>
                                <?php endif; ?>

                                <!-- 3. IMAGES -->
                                <?php if (!empty($images)): ?>
                                            <div class="lesson-section">
                                                <p class="lesson-section-title"><i class="fa fa-image"></i> Images</p>
                                                <div class="db-images-grid">
                                                    <?php foreach ($images as $img): ?>
                                                                <div class="db-image-card"
                                                                    onclick="dbLightboxT('<?= htmlspecialchars($img['file_path']) ?>')">
                                                                    <img src="<?= htmlspecialchars($img['file_path']) ?>"
                                                                        alt="<?= htmlspecialchars($img['title'] ?? '') ?>" loading="lazy">
                                                                    <?php if (!empty($img['title'])): ?>
                                                                                <div class="db-image-caption"><?= htmlspecialchars($img['title']) ?></div>
                                                                    <?php endif; ?>
                                                                </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                <?php endif; ?>

                                <!-- 4. FLASHCARDS -->
                                <?php if (!empty($flashcards)): ?>
                                            <div class="lesson-section">
                                                <p class="lesson-section-title"><i class="fa fa-clone"></i> Flashcards</p>
                                                <div class="fc-grid">
                                                    <?php foreach ($flashcards as $fc): ?>
                                                                <div class="fc-item" onclick="this.classList.toggle('flipped')">
                                                                    <div class="fc-inner">
                                                                        <div class="fc-front">
                                                                            <span class="fc-label">Question</span>
                                                                            <span class="fc-text"><?= htmlspecialchars($fc['card_front']) ?></span>
                                                                            <span class="fc-hint">Tap to reveal</span>
                                                                        </div>
                                                                        <div class="fc-back">
                                                                            <span class="fc-label">Answer</span>
                                                                            <span class="fc-text"><?= htmlspecialchars($fc['card_back']) ?></span>
                                                                            <span class="fc-hint">Tap to go back</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                <?php endif; ?>

                                <!-- 5. ACTIVITIES (read-only — show questions + correct answers, no input) -->
                                <?php if (!empty($activityData)): ?>
                                            <div class="lesson-section">
                                                <p class="lesson-section-title"><i class="fa fa-pencil-alt"></i> Activities</p>
                                                <?php foreach ($activityData as $actId => $data):
                                                    $act = $data['activity'];
                                                    $questions = $data['questions'];
                                                    ?>
                                                            <div class="activity-block">
                                                                <div class="activity-intro">
                                                                    <div class="activity-intro-icon">✏️</div>
                                                                    <div>
                                                                        <p class="activity-intro-title"><?= htmlspecialchars($act['title']) ?></p>
                                                                        <?php if (!empty($act['instructions'])): ?>
                                                                                    <p class="activity-intro-desc"><?= htmlspecialchars($act['instructions']) ?></p>
                                                                        <?php endif; ?>
                                                                        <div class="activity-meta-pills">
                                                                            <span class="meta-pill pill-purple"><?= count($questions) ?> Questions</span>
                                                                            <span class="meta-pill pill-green">⭐ <?= (int) $act['total_points'] ?> pts</span>
                                                                            <span class="meta-pill pill-orange"><i class="fa fa-eye"></i> Preview</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <?php foreach ($questions as $qi => $q): ?>
                                                                            <div class="activity-question">
                                                                                <p class="aq-num">Question <?= $qi + 1 ?></p>
                                                                                <p class="aq-text"><?= htmlspecialchars($q['question']) ?></p>

                                                                                <?php if ($q['question_type'] === 'multiple_choice'): ?>
                                                                                            <?php
                                                                                            $ltrs = ['A', 'B', 'C', 'D'];
                                                                                            $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                                                                            $li = 0;
                                                                                            foreach ($ch as $key => $val):
                                                                                                if ($val === null)
                                                                                                    continue;
                                                                                                $isCorrect = strtolower($key) === strtolower($q['correct_ans'] ?? '');
                                                                                                ?>
                                                                                                        <div class="q-choice <?= $isCorrect ? 'correct-ans' : '' ?>"
                                                                                                            style="margin-bottom:8px;pointer-events:none;">
                                                                                                            <span class="choice-letter"
                                                                                                                style="<?= $isCorrect ? 'background:#22c55e;color:#fff;' : '' ?>">
                                                                                                                <?= $ltrs[$li++] ?>
                                                                                                            </span>
                                                                                                            <?= htmlspecialchars($val) ?>
                                                                                                            <?php if ($isCorrect): ?>
                                                                                                                        <i class="fa fa-check" style="margin-left:auto;color:#22c55e;"></i>
                                                                                                            <?php endif; ?>
                                                                                                        </div>
                                                                                            <?php endforeach; ?>
                                                                                <?php else: ?>
                                                                                            <!-- Essay — show model answer if available -->
                                                                                            <div style="background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;padding:12px 14px;font-size:14px;color:#555;font-style:italic;">
                                                                                                Essay question — student written response
                                                                                            </div>
                                                                                            <?php if (!empty($q['model_answer'])): ?>
                                                                                                        <div style="margin-top:8px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:10px 14px;font-size:13px;color:#065f46;">
                                                                                                            <strong>Model Answer:</strong>
                                                                                                            <?= nl2br(htmlspecialchars($q['model_answer'])) ?>
                                                                                                        </div>
                                                                                            <?php endif; ?>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                <?php endforeach; ?>
                                            </div>
                                <?php endif; ?>

                                <!-- 6. QUIZZES (read-only — show all questions + correct answers highlighted) -->
                                <?php if (!empty($quizData)): ?>
                                            <div class="lesson-section">
                                                <p class="lesson-section-title"><i class="fa fa-clipboard-list"></i> Quiz</p>
                                                <?php foreach ($quizData as $qzId => $data):
                                                    $qz = $data['quiz'];
                                                    $questions = $data['questions'];
                                                    ?>
                                                            <div class="quiz-intro">
                                                                <div class="quiz-intro-icon">📋</div>
                                                                <div>
                                                                    <p class="quiz-intro-title"><?= htmlspecialchars($qz['title']) ?></p>
                                                                    <?php if (!empty($qz['instructions'])): ?>
                                                                                <p class="quiz-intro-desc"><?= htmlspecialchars($qz['instructions']) ?></p>
                                                                    <?php endif; ?>
                                                                    <div class="activity-meta-pills">
                                                                        <span class="meta-pill pill-purple"><?= count($questions) ?> Questions</span>
                                                                        <span class="meta-pill pill-green">Passing: <?= (int) $qz['passing_score'] ?>%</span>
                                                                        <span class="meta-pill pill-orange"><i class="fa fa-eye"></i> Preview</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?php foreach ($questions as $qi => $q):
                                                                $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                                                $qLtrs = ['A', 'B', 'C', 'D'];
                                                                ?>
                                                                        <div class="q-card">
                                                                            <p class="q-number">Question <?= $qi + 1 ?></p>
                                                                            <p class="q-text"><?= htmlspecialchars($q['question']) ?></p>
                                                                            <div class="q-choices">
                                                                                <?php $li = 0;
                                                                                foreach ($ch as $key => $val):
                                                                                    if ($val === null)
                                                                                        continue;
                                                                                    $isCorrect = strtolower($key) === strtolower($q['correct_ans']);
                                                                                    ?>
                                                                                            <div class="q-choice <?= $isCorrect ? 'correct-ans' : '' ?>"
                                                                                                style="pointer-events:none;<?= $isCorrect ? 'border-color:#22c55e;background:#f0fdf4;' : '' ?>">
                                                                                                <span class="choice-letter"
                                                                                                    style="<?= $isCorrect ? 'background:#22c55e;color:#fff;' : '' ?>">
                                                                                                    <?= $qLtrs[$li++] ?>
                                                                                                </span>
                                                                                                <?= htmlspecialchars($val) ?>
                                                                                                <?php if ($isCorrect): ?>
                                                                                                            <i class="fa fa-check" style="margin-left:auto;color:#22c55e;"></i>
                                                                                                <?php endif; ?>
                                                                                            </div>
                                                                                <?php endforeach; ?>
                                                                            </div>
                                                                        </div>
                                                            <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </div>
                                <?php endif; ?>

                            </div><!-- /view-lessons-body -->

                            <!-- ── FOOTER NAV (prev / next only — no finish button) ── -->
                            <div class="view-lessons-footer" style="padding: 0 30.4px;">
                                <nav aria-label="Lesson navigation">
                                    <ul class="pagination m-0">
                                        <li class="page-item">
                                            <?php if ($prevLessonId): ?>
                                                        <a class="page-link" href="<?= tUrl($subjectId, $moduleId, $prevLessonId) ?>">
                                                            <i class="fa fa-chevron-left"></i>
                                                            <span>Previous Lesson</span>
                                                        </a>
                                            <?php else: ?>
                                                        <a class="page-link" style="visibility:hidden;pointer-events:none;">
                                                            <i class="fa fa-chevron-left"></i>
                                                            <span>Previous Lesson</span>
                                                        </a>
                                            <?php endif; ?>
                                        </li>
                                        <li class="page-item disabled">
                                            <span id="page-indicator"><?= $currentIndex ?> / <?= $totalLessons ?></span>
                                        </li>
                                        <li class="page-item">
                                            <?php if ($nextLessonId): ?>
                                                        <a class="page-link" href="<?= tUrl($subjectId, $moduleId, $nextLessonId) ?>">
                                                            <span>Next Lesson</span>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </a>
                                            <?php else: ?>
                                                        <!-- Last lesson — show disabled "End of Module", no finish action -->
                                                        <span class="page-link"
                                                            style="background:var(--green-light);color:var(--green-dark);border-color:var(--green-mid);font-weight:600;cursor:default;display:flex;align-items:center;gap:6px;padding:10px 30px;border-radius:10px;">
                                                            <i class="fa fa-flag-checkered"></i> End of Module
                                                        </span>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                <?php endif; ?>
            </div><!-- /view-lessons -->
        </div><!-- /body-lessons -->

        <!-- LIGHTBOX -->
        <div class="db-lightbox" id="dbLightboxT" onclick="dbLightboxClose()">
            <button class="db-lightbox-close" onclick="dbLightboxClose()"><i class="fa fa-times"></i></button>
            <img id="dbLightboxImgT" src="" alt="">
        </div>

    </div><!-- /rightbar -->
</div><!-- /container-fluid -->

<script>
function dbLightboxT(src) {
    document.getElementById('dbLightboxImgT').src = src;
    document.getElementById('dbLightboxT').classList.add('open');
}
function dbLightboxClose() {
    document.getElementById('dbLightboxT').classList.remove('open');
}
</script>

<script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>
</html>