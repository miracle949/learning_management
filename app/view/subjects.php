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
                    $teacher = $studentModel->getTeacherBySubjectId((int) $subjectInfo['id']);
                    $teacherName = $teacher['name'] ?? 'Unknown Instructor';

                    $nameParts = explode(' ', $teacherName);
                    $initials = '';
                    foreach ($nameParts as $part) {
                        if (!empty($part))
                            $initials .= strtoupper($part[0]);
                    }
                    $initials = substr($initials, 0, 2);
                    ?>

                    <style>

                    </style>

                    <!-- ── HERO BANNER — fully dynamic from DB ── -->

                    <!-- <p>My Class <i class="fa fa-chevron-right"></i> <b>Computer System Servicing</b></p> -->

                    <div class="navbar-bread">
                        <div class="bread-crambs">
                            Dashboard <i class="fa fa-chevron-right"></i> <b>My Subject</b>
                        </div>

                        <div class="notification">
                            <button>
                                <i class="fa fa-bell"></i>
                            </button>
                        </div>
                    </div>
                    <!-- <div class="subject-nav">
                        <div class="subject-text">
                            <h2>My Subject</h2>
                            <p>Announcements, Materials, Assignments</p>
                        </div>

                        <div class="subject-acc">
                            <button>
                                <div class="notification-icon">
                                    <i class="fa fa-message"></i>
                                </div>
                            </button>
                            <button>
                                <div class="notification-icon">
                                    <i class="fa fa-bell"></i>
                                </div>
                            </button>

                            <button>
                                <?php
                                $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                                echo $initial;
                                ?>
                            </button>
                        </div>
                    </div> -->
                    <div class="header">
                        <div class="module-title">
                            <!-- <div class="module-picture">
                                <img src="/learning_management/<?= htmlspecialchars($subjectInfo['subject_image']) ?>" alt="">
                            </div> -->
                            <div class="module-body">
                                <div class="module-body-child">
                                    <div class="module-links">
                                        <div class="module-description">
                                            <div class="module-nav"></div>
                                            <h1><?= htmlspecialchars($subjectInfo['subject_name']) ?></h1>
                                            <p><?= htmlspecialchars($subjectInfo['subject_description']) ?></p>

                                            <div class="icon-parent">
                                                <div class="icon">
                                                    <?= htmlspecialchars($initials) ?>
                                                </div>
                                                <div class="text">
                                                    <p>
                                                        <?= htmlspecialchars($teacherName) ?>
                                                    </p>
                                                    <span>CSS Teacher</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="module-buttons">
                                            <!-- <div class="module-icon">
                                                <i class="fa fa-book-open"></i>
                                            </div>
                                            <div class="module-text">
                                                <p>Hardware · Software · TroubleShooting</p>
                                            </div> -->
                                            <div class="speech-bubble">
                                                <strong>BonBon</strong>
                                                <p id="bonbonMessage"></p>
                                            </div>
                                            <img src="../images/robot-ai7.png" alt="">
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
                                    <!-- <span class="stream-filter-label">Show:</span> -->
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
                                                            <span
                                                                style="color:<?= $scoreColor ?>;"><?= (int) $item['points_earned'] ?></span>
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
        (function typewriter() {
            const el = document.getElementById('bonbonMessage');
            if (!el) return;

            const message = "You're viewing <?= htmlspecialchars(addslashes($subjectInfo['subject_name'])) ?>, and access all your assignments, materials and announcements.";
            const speed = 20; // ms per character — lower = faster
            let i = 0;

            // cursor span that blinks while typing
            const cursor = document.createElement('span');
            cursor.className = 'typing-cursor';
            el.appendChild(cursor);

            function type() {
                if (i < message.length) {
                    cursor.insertAdjacentText('beforebegin', message.charAt(i));
                    i++;
                    setTimeout(type, speed);
                } else {
                    // remove cursor once finished (optional)
                    setTimeout(() => cursor.remove(), 1200);
                }
            }

            type();
        })();
    </script>

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