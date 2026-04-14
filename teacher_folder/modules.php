<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Modules</title>
    <link rel="stylesheet" href="../css_folder/modules_teacher.css">
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
            --background-icon: #2d3748;
            --green-text: #4a6a58;
        }

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
            border-radius: 10px;
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

        .container-fluid main {
            background-color: #F7F9F8;
            padding: 1.8rem 1.4rem 1.8rem 1.4rem;
            margin-top: 68px;
        }

        /* ── BACK BREADCRUMB ── */
        .back-breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13.5px;
            color: var(--green-text);
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 1.2rem;
            padding: 6px 14px;
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            background: #fff;
            transition: border-color .18s, color .18s;
        }

        .back-breadcrumb:hover {
            border-color: var(--green);
            color: var(--green);
        }

        /* ── BANNER ── */
        .module-title {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #E2E8E5;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .module-title .module-picture {
            background-color: var(--green-dark);
            background-size: cover;
            background-position: center;
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .module-title .module-picture h2 {
            color: white;
            font-size: 28px;
            font-family: "Titan", sans-serif;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
            padding: 1rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .module-title .module-body {
            background-color: #ffffff;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .module-title .module-body h1 {
            font-size: 20px;
            text-transform: uppercase;
            font-family: "Titan", sans-serif;
            color: var(--green-dark);
            margin: 0 0 .4rem;
        }

        .module-title .module-body p {
            font-size: 14px;
            color: #555;
            line-height: 26px;
            margin: 0;
            max-width: 500px;
        }

        .module-browse-btn {
            text-decoration: none;
            background-color: var(--green);
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 28px;
            font-size: 13.5px;
            font-weight: 700;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .module-browse-btn:hover {
            opacity: .88;
            color: #fff;
        }

        /* ── MAIN LAYOUT ── */
        .modules-main {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        /* ── LEARNING CATALOG ── */
        .learning-catalog {
            width: 220px;
            min-width: 200px;
            flex-shrink: 0;
        }

        .learning-catalog h4 {
            font-size: 17px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 1rem;
        }

        .learning-catalog .catalog-section-title {
            font-size: 13px;
            font-weight: 700;
            color: #555;
            margin: 0 0 .5rem;
        }

        .catalog-list {
            list-style: none;
            padding: 0;
            margin: 0 0 1rem;
        }

        .catalog-list li {
            font-size: 13.5px;
            color: #333;
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: color .15s;
        }

        .catalog-list li:hover {
            color: var(--green);
        }

        .see-more-btn {
            background: none;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 12.5px;
            font-weight: 700;
            color: #555;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: border-color .18s, color .18s;
            margin-top: .3rem;
        }

        .see-more-btn:hover {
            border-color: var(--green);
            color: var(--green);
        }

        /* ── MODULE CARDS GRID ── */
        .learning-module {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
        }

        .module-feed-card {
            background: #ffffff;
            border: 1px solid #E2E8E5;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .2s, border-color .2s;
        }

        .module-feed-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .10);
            border-color: var(--green);
        }

        .module-feed-card .card-img {
            width: 100%;
            height: 130px;
            background-color: var(--green-dark);
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .module-feed-card .card-img i {
            font-size: 40px;
            color: rgba(255, 255, 255, 0.4);
        }

        .module-feed-card .card-body {
            padding: 1.2rem 1.4rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .module-feed-card .card-body .lesson-count {
            font-size: 12px;
            color: var(--green);
            font-weight: 600;
            margin-bottom: .4rem;
        }

        .module-feed-card .card-body h3 {
            font-size: 15px;
            color: var(--green-dark);
            font-family: "Titan", sans-serif;
            margin: 0 0 .5rem;
            line-height: 1.4;
        }

        .module-feed-card .card-body p {
            font-size: 13px;
            color: #555;
            margin: 0 0 1.2rem;
            line-height: 1.5;
            flex: 1;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn-view-lessons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            background-color: var(--green);
            color: #ffffff;
            padding: 9px 16px;
            flex: 1;
            border-radius: 28px;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity .18s;
            border: none;
            cursor: pointer;
        }

        .btn-view-lessons:hover {
            opacity: .88;
            color: #fff;
        }

        .modules-empty {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
            grid-column: span 2;
        }

        .modules-empty i {
            font-size: 48px;
            display: block;
            margin-bottom: 14px;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("sidebar.php") ?>

        <div class="rightbar">
            <?php include("nav.php") ?>
            <main>

                <?php if ($subjectInfo): ?>

                    <a href="/learning_management/public/?url=modules_teacher" class="back-breadcrumb">
                        <i class="fa fa-arrow-left"></i> Back to Modules
                    </a>

                    <!-- ── BANNER ── -->
                    <div class="module-title">
                        <div class="module-picture" <?php if (!empty($subjectInfo['subject_image'])): ?>
                                style="background-image: url('/learning_management/<?= htmlspecialchars($subjectInfo['subject_image']) ?>');"
                            <?php endif; ?>>
                            <h2><?= htmlspecialchars($subjectInfo['subject_name']) ?></h2>
                        </div>
                        <div class="module-body">
                            <div>
                                <h1><?= htmlspecialchars($subjectInfo['subject_name']) ?></h1>
                                <p><?= htmlspecialchars($subjectInfo['subject_description'] ?? 'No description available.') ?>
                                </p>
                            </div>
                            <a href="/learning_management/public/?url=create_module&subject_id=<?= (int) $subjectId ?>"
                                class="module-browse-btn">
                                <i class="fa fa-plus"></i> Create Module
                            </a>
                        </div>
                    </div>

                    <!-- ── MAIN LAYOUT ── -->
                    <div class="modules-main">

                        <!-- Learning Catalog -->
                        <div class="learning-catalog">
                            <h4>Learning Catalog</h4>
                            <p class="catalog-section-title">Modules</p>
                            <ul class="catalog-list">
                                <?php foreach ($modules as $i => $mod): ?>
                                    <li class="<?= $i >= 5 ? 'catalog-extra' : '' ?>"
                                        style="<?= $i >= 5 ? 'display:none;' : '' ?>">
                                        <?= htmlspecialchars($mod['title']) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php if (count($modules) > 5): ?>
                                <button class="see-more-btn" onclick="toggleCatalog(this)">
                                    See more <i class="fa fa-chevron-down"></i>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Module Cards Grid -->
                        <div class="learning-module">
                            <?php if (empty($modules)): ?>
                                <div class="modules-empty">
                                    <i class="fa fa-book-open"></i>
                                    <p>No modules yet for this subject.
                                        <a
                                            href="/learning_management/public/?url=create_module&subject_id=<?= (int) $subjectId ?>">
                                            Create one now.
                                        </a>
                                    </p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($modules as $mod):
                                    $count = (int) ($mod['lesson_count'] ?? 0);
                                    // First lesson ID for direct entry
                                    $firstLesson = $teacherModel->getLessonsByModule($mod['id']);
                                    $firstLessonId = !empty($firstLesson) ? (int) $firstLesson[0]['id'] : 0;
                                    $lessonsUrl = "/learning_management/public/?url=subject_lessons_teacher&subject_id={$subjectId}&id={$mod['id']}"
                                        . ($firstLessonId ? "&lesson={$firstLessonId}" : '');
                                    ?>
                                    <div class="module-feed-card">
                                        <div class="card-img">
                                            <i class="fa fa-book-open"></i>
                                        </div>
                                        <div class="card-body">
                                            <span class="lesson-count">
                                                <i class="fa fa-list-ul"></i>
                                                <?= $count ?> lesson<?= $count !== 1 ? 's' : '' ?>
                                            </span>
                                            <h3><?= htmlspecialchars($mod['title']) ?></h3>
                                            <p><?= htmlspecialchars(
                                                !empty($mod['description'])
                                                ? mb_strimwidth($mod['description'], 0, 100, '...')
                                                : 'No description available.'
                                            ) ?></p>
                                            <div class="card-actions">
                                                <a href="<?= htmlspecialchars($lessonsUrl) ?>" class="btn-view-lessons">
                                                    View Lessons <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                    </div><!-- /modules-main -->

                <?php else: ?>
                    <div style="padding:2rem;">
                        <h3>Subject not found.</h3>
                        <a href="/learning_management/public/?url=modules_teacher">← Back to Modules</a>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>

    <script>
        function toggleCatalog(btn) {
            var extras = document.querySelectorAll('.catalog-extra');
            var hidden = extras.length && extras[0].style.display === 'none';
            extras.forEach(function (el) { el.style.display = hidden ? 'block' : 'none'; });
            btn.innerHTML = hidden
                ? 'See less <i class="fa fa-chevron-up"></i>'
                : 'See more <i class="fa fa-chevron-down"></i>';
        }
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>