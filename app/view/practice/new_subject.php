<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject</title>
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">

            <?php
            require_once "../app/models/Students.php";
            $studentModel = new Students();

            $subject = $_GET['subject'] ?? null;

            if ($subject):
                // Pull subject info dynamically from DB — no hardcoded map needed
                $subjectInfo = $studentModel->getSubjectBySlug($subject);

                if ($subjectInfo):
                    $feedItems = $studentModel->getSubjectFeed($subject, $_SESSION['student_id'] ?? 0);
                    ?>

                    <style>
                        .container-fluid .rightbar .module-title {
                            width: 100%;
                            color: white;
                            border-radius: 10px;
                            border: 1px solid #E2E8E5;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
                        }

                        .container-fluid .rightbar .module-title .module-picture {
                            background-color: var(--green-dark);
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                            width: 100%;
                            height: 180px;
                            /* border-top-left-radius: 10px;
                                                            border-top-right-radius: 10px; */
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .container-fluid .rightbar .module-title .module-picture img {
                            width: 100%;
                            height: 180px;
                            /* border-top-left-radius: 10px;
                                                            border-top-right-radius: 10px; */
                        }

                        .container-fluid .rightbar .module-title .module-picture h2 {
                            color: white;
                            font-size: 28px;
                            font-family: "Titan", sans-serif;
                            text-transform: uppercase;
                            letter-spacing: 2px;
                            text-align: center;
                            padding: 1rem;
                            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                        }

                        .container-fluid .rightbar .module-title .module-body {
                            background-color: #ffffff;
                            border-bottom-left-radius: 10px;
                            border-bottom-right-radius: 10px;
                            width: 100%;
                            background: transparent;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-body-child {
                            position: relative;
                            /* z-index: 2; */
                            width: 100%;
                            display: flex;
                            gap: 48px;
                            align-items: center;
                            justify-content: space-between;
                            padding: 1.8rem 2rem;
                            background-color: #ffffff;
                            border-bottom: none;
                            outline: 0;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-body-child .module-description p {
                            color: #212529;
                            margin: 15px 0 0;
                            font-size: 15px;
                            width: 100%;
                            max-width: 620px;
                            line-height: 25px;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links {
                            /* width: 55%; */
                            width: 100%;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links span {
                            color: var(--green);
                            font-weight: 600;
                            font-size: 13.5px;
                            text-transform: uppercase;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons {
                            display: flex;
                            align-items: center;
                            gap: 1.5rem;
                            /* margin-top: 1.5rem; */
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(1) {
                            color: white;
                            background-color: var(--green);
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(2) {
                            border: 2px solid var(--green);
                            color: var(--green);
                            padding: 10px 40px;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a {
                            text-decoration: none;
                            padding: 10px 27px;
                            border-radius: 28px;
                            text-transform: uppercase;
                            font-weight: 600;
                            font-size: 13px;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links h1 {
                            font-size: 22px;
                            text-transform: uppercase;
                            font-family: "Titan", sans-serif;
                            color: var(--green-dark);
                            margin-top: 0.7rem;
                        }

                        /* 
                                                                                                                                                        .container-fluid .rightbar .module-title .module-body .module-text {
                                                                                                                                                            width: 45%;
                                                                                                                                                        } */

                        .container-fluid .rightbar .module-title .module-body .module-text p {
                            line-height: 26px;
                            margin: 0;
                            font-size: 14px;
                            color: #1A1A1A;
                        }

                        .module-parent-progress {
                            display: flex;
                            flex-direction: column;
                            gap: 1.5rem;
                        }

                        .module-parent-progress a {
                            text-decoration: none;
                            color: inherit;
                            display: block;
                        }

                        .rightbar {
                            padding: 0;
                        }

                        .subject-tabs {
                            padding: 16px 20px 0;
                        }

                        .header {
                            border: none;
                        }

                        /* ── Stream filter chips ── */
                        .stream-filter-bar {
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            padding: 14px 20px 0;
                            flex-wrap: wrap;
                        }

                        .stream-filter-label {
                            font-size: 0.82rem;
                            font-weight: 600;
                            color: #6b7280;
                            margin-right: 2px;
                        }

                        .stream-chip {
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            padding: 6px 14px;
                            border-radius: 20px;
                            font-size: 0.78rem;
                            font-weight: 600;
                            border: 1.5px solid #e4e7eb;
                            background: #fff;
                            color: #6b7280;
                            cursor: pointer;
                            transition: all 0.15s;
                            user-select: none;
                        }

                        .stream-chip:hover {
                            border-color: var(--green);
                            color: var(--green);
                            background: #e8f5ee;
                        }

                        .stream-chip.active {
                            background: var(--green);
                            border-color: var(--green);
                            color: #fff;
                        }

                        .stream-chip .chip-count {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            min-width: 20px;
                            height: 18px;
                            padding: 0 6px;
                            border-radius: 20px;
                            font-size: 0.7rem;
                            font-weight: 700;
                            background: rgba(0, 0, 0, 0.12);
                            color: inherit;
                        }

                        .stream-chip.active .chip-count {
                            background: rgba(255, 255, 255, 0.25);
                        }

                        /* feed item type label colors */
                        .feed-type-label {
                            font-size: 0.72rem;
                            font-weight: 700;
                            text-transform: uppercase;
                            letter-spacing: 0.06em;
                            margin-bottom: 2px;
                            display: block;
                        }

                        .feed-type-label.announcement {
                            color: #d97706;
                        }

                        .feed-type-label.material {
                            color: var(--green);
                        }

                        /* announcement icon bg */
                        .module-icon.announcement-icon {
                            background: #fef3c7 !important;
                            color: #d97706 !important;
                        }

                        .module-icon.announcement-icon i {
                            color: #d97706 !important;
                        }
                    </style>

                    <!-- ── HERO BANNER — fully dynamic from DB ── -->
                    <div class="header">
                        <div class="module-title">
                            <div class="module-picture">
                                <img src="/learning_management/<?= htmlspecialchars($subjectInfo['subject_image']) ?>" alt="">
                            </div>
                            <div class="module-body">
                                <div class="module-body-child">
                                    <div class="module-links">
                                        <div class="module-description">
                                            <h1><?= htmlspecialchars($subjectInfo['subject_name']) ?></h1>
                                            <p><?= htmlspecialchars($subjectInfo['subject_description']) ?></p>
                                        </div>
                                        <div class="module-buttons">
                                            <a href="/learning_management/public/?url=classes">Browse Classes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── TABS NAV ── -->
                    <div class="subject-tabs">
                        <button class="subject-tab-btn active" data-tab="stream">Stream</button>
                        <button class="subject-tab-btn" data-tab="classwork">Classwork</button>
                    </div>

                    <!-- ── FEED ── -->
                    <div class="parent">
                        <div class="module-parent-progress">

                            <?php
                            $urlMap = [
                                'module' => 'module_view',
                                'assignment' => 'assignment_view',
                                'announcement' => 'announcement_view',
                            ];
                            $labelMap = [
                                'module' => 'New Material',
                                'assignment' => 'New Assignment',
                                'announcement' => 'Announcement',
                            ];

                            // Split feed into stream (modules + announcements) and classwork (assignments)
                            $streamItems = [];
                            $classworkItems = [];
                            foreach ($feedItems as $item) {
                                if ($item['type'] === 'assignment') {
                                    $classworkItems[] = $item;
                                } else {
                                    $streamItems[] = $item;
                                }
                            }
                            ?>

                            <!-- STREAM TAB -->
                            <div class="subject-tab-pane active" id="subject-tab-stream">

                                <?php
                                $totalMaterials = count(array_filter($streamItems, fn($i) => $i['type'] === 'module'));
                                $totalAnnouncements = count(array_filter($streamItems, fn($i) => $i['type'] === 'announcement'));
                                ?>

                                <!-- Filter chips -->
                                <div class="stream-filter-bar">
                                    <span class="stream-filter-label">Show:</span>
                                    <button class="stream-chip active" data-filter="all" onclick="filterStream('all', this)">
                                        <i class="fa fa-bars" style="font-size:11px;"></i>
                                        All
                                    </button>
                                    <button class="stream-chip" data-filter="module" onclick="filterStream('module', this)">
                                        <i class="fa fa-book" style="font-size:11px;"></i>
                                        Materials
                                        <span class="chip-count"><?= $totalMaterials ?></span>
                                    </button>
                                    <button class="stream-chip" data-filter="announcement"
                                        onclick="filterStream('announcement', this)">
                                        <i class="fa fa-bell" style="font-size:11px;"></i>
                                        Announcements
                                        <span class="chip-count"><?= $totalAnnouncements ?></span>
                                    </button>
                                </div>

                                <?php if (!empty($streamItems)): ?>
                                    <?php foreach ($streamItems as $item): ?>
                                        <?php
                                        $pageUrl = "/learning_management/public/?url={$urlMap[$item['type']]}&subject=" . urlencode($subject) . "&id={$item['id']}";
                                        $label = $labelMap[$item['type']] ?? $item['type'];
                                        $date = date('M j', strtotime($item['date']));
                                        $subtext = mb_strimwidth(strip_tags($item['subtext']), 0, 120, '...');
                                        $isAnnouncement = $item['type'] === 'announcement';
                                        ?>
                                        <a href="<?= $pageUrl ?>" data-feed-type="<?= $item['type'] ?>">
                                            <div class="module-progress">
                                                <div class="module-parent">
                                                    <div class="module-icon <?= $isAnnouncement ? 'announcement-icon' : '' ?>">
                                                        <i class="fa <?= $isAnnouncement ? 'fa-bell' : 'fa-layer-group' ?>"></i>
                                                    </div>
                                                    <div class="module-content">
                                                        <span
                                                            class="feed-type-label <?= $isAnnouncement ? 'announcement' : 'material' ?>">
                                                            <?= htmlspecialchars($label) ?>
                                                        </span>
                                                        <h3><?= htmlspecialchars($item['heading']) ?></h3>
                                                        <p><?= htmlspecialchars($subtext) ?></p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="module-date"
                                                    style="display:flex; justify-content:space-between; align-items:center;">
                                                    <p style="margin:0;">Date Received: <?= $date ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                    <p id="stream-empty-msg"
                                        style="display:none; color:#aaa; padding:2rem; font-size:14px; text-align:center;">
                                        No items to show.
                                    </p>
                                <?php else: ?>
                                    <p style="color:#aaa; padding:2rem; font-size:14px; text-align:center;">No materials or
                                        announcements posted yet.</p>
                                <?php endif; ?>

                            </div>

                            <!-- CLASSWORK TAB -->
                            <div class="subject-tab-pane" id="subject-tab-classwork">
                                <?php if (!empty($classworkItems)): ?>
                                    <?php foreach ($classworkItems as $item): ?>
                                        <?php
                                        $pageUrl = "/learning_management/public/?url={$urlMap[$item['type']]}&subject=" . urlencode($subject) . "&id={$item['id']}";
                                        $date = date('M j', strtotime($item['date']));
                                        $subtext = mb_strimwidth(strip_tags($item['subtext']), 0, 120, '...');
                                        ?>
                                        <a href="<?= $pageUrl ?>">
                                            <div class="module-progress">
                                                <div class="module-parent">
                                                    <div class="module-icon"><i class="fa fa-layer-group"></i></div>
                                                    <div class="module-content">
                                                        <span>New Assignment</span>
                                                        <h3><?= htmlspecialchars($item['heading']) ?></h3>
                                                        <p><?= htmlspecialchars($subtext) ?></p>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="module-date"
                                                    style="display:flex; justify-content:space-between; align-items:center;">
                                                    <p style="margin:0;">Date Received: <?= $date ?></p>
                                                    <?php if (isset($item['points_earned']) && $item['points_earned'] !== null): ?>
                                                        <?php
                                                        $percent = $item['total_points'] > 0 ? ($item['points_earned'] / $item['total_points']) * 100 : 0;
                                                        $scoreColor = $percent >= 75 ? '#4CAF7D' : '#C82525';
                                                        ?>
                                                        <p style="margin:0; font-weight:700;">
                                                            <span style="color:<?= $scoreColor ?>;"><?= (int) $item['points_earned'] ?></span>
                                                            <span style="color:#aaa;"> / <?= (int) $item['total_points'] ?></span>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color:#aaa; padding:2rem; font-size:14px; text-align:center;">No assignments yet.</p>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                    <?php
                else:
                    // Subject slug not found in DB
                    ?>
                    <div style="padding:2rem;">
                        <h3>Subject not found.</h3>
                        <p>The subject "<strong><?= htmlspecialchars($subject) ?></strong>" does not exist.</p>
                        <a href="/learning_management/public/?url=classes">← Back to Courses</a>
                    </div>
                    <?php
                endif;
            else:
                ?>
                <div style="padding:2rem;">
                    <h3>No subject selected.</h3>
                    <a href="/learning_management/public/?url=classes">← Browse Courses</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // TABS
            const tabBtns = document.querySelectorAll('.subject-tab-btn');
            const tabPanes = document.querySelectorAll('.subject-tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('subject-tab-' + this.dataset.tab).classList.add('active');
                });
            });
        });

        // STREAM FILTER
        function filterStream(type, btn) {
            document.querySelectorAll('.stream-chip').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');

            const items = document.querySelectorAll('#subject-tab-stream a[data-feed-type]');
            let visible = 0;

            items.forEach(item => {
                const show = type === 'all' || item.dataset.feedType === type;
                item.style.display = show ? 'block' : 'none';
                if (show) visible++;
            });

            const empty = document.getElementById('stream-empty-msg');
            if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
        }
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

</body>

</html>