<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes</title>
    <link rel="stylesheet" href="../css_folder/classes_teacher.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        /* ── CLASSES PAGE — Figma match ── */
        .tc-page-title {
            font-size: 22px;
            font-weight: 800;
            color: #1a1a2e;
            margin: 0 0 4px;
            font-family: 'Poppins', sans-serif;
        }

        .tc-page-sub {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 1.2rem;
        }

        .tc-filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 1.8rem;
        }

        .tc-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 20px;
            border: 1.5px solid var(--green, #4caf82);
            background: var(--green, #4caf82);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }

        .tc-filter-btn.outline {
            background: #fff;
            color: #374151;
            border-color: #e5e7eb;
        }

        /* ── CARD ── */
        .tc-grid {
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        .tc-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            transition: box-shadow .2s, border-color .2s;
            animation: tcFadeUp .35s ease both;
        }

        .tc-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.09);
            border-color: var(--green, #4caf82);
        }

        @keyframes tcFadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tc-card:nth-child(1) {
            animation-delay: 0s
        }

        .tc-card:nth-child(2) {
            animation-delay: .07s
        }

        .tc-card:nth-child(3) {
            animation-delay: .14s
        }

        .tc-card:nth-child(4) {
            animation-delay: .21s
        }

        .tc-card:nth-child(5) {
            animation-delay: .28s
        }

        /* Banner */
        .tc-banner {
            width: 100%;
            height: 120px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #e8f5ee;
        }

        /* subject-specific banners */
        .b-phil {
            background-image: url('../images/philosophy_picture.jpg');
        }

        .b-ucsp {
            background-image: url('../images/ucsp_picture.jpg');
        }

        .b-css {
            background-image: url('../images/computer_picture.jpg');
        }

        .b-pe {
            background-image: url('../images/pe_picture.jpg');
        }

        .b-3i {
            background-image: url('../images/3i_picture.jpg');
        }

        .b-entr {
            background-image: url('../images/entrep_picture.jpg');
        }

        .b-work {
            background-image: url('../images/work_picture.jpg');
        }

        .b-def {
            background-image: url('../images/philosophy_picture.jpg');
        }

        /* Card body */
        .tc-body {
            padding: 1.1rem 1.5rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .tc-info {
            flex: 1;
            min-width: 200px;
        }

        .tc-info h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--green-dark, #065f46);
            margin: 0 0 4px;
            font-family: 'Poppins', sans-serif;
        }

        .tc-schedule {
            font-size: 12.5px;
            color: #6b7280;
            margin: 0 0 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tc-schedule i {
            font-size: 11px;
            color: var(--green, #4caf82);
        }

        .tc-students {
            font-size: 13px;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .tc-students i {
            color: #9ca3af;
        }

        .tc-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .tc-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f3f4f6;
            color: #374151;
            border-radius: 20px;
            padding: 5px 13px;
            font-size: 12px;
            font-weight: 500;
        }

        .tc-pill i {
            font-size: 10px;
            color: #9ca3af;
        }

        .tc-pill.tc-pill-section {
            background: #ecfdf5;
            color: #065f46;
        }

        .tc-pill.tc-pill-section i {
            color: #6ee7b7;
        }

        /* Manage button */
        .tc-manage {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 26px;
            background: var(--green, #4caf82);
            color: #fff;
            border-radius: 28px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity .18s, transform .15s;
            font-family: 'Poppins', sans-serif;
            flex-shrink: 0;
        }

        .tc-manage:hover {
            opacity: .88;
            color: #fff;
            transform: translateY(-1px);
        }

        /* Empty */
        .tc-empty {
            text-align: center;
            padding: 70px 20px;
            color: #9ca3af;
        }

        .tc-empty i {
            font-size: 48px;
            display: block;
            margin-bottom: 14px;
        }

        .tc-empty p {
            font-size: 14px;
            margin: 0;
        }

        .container-fluid .rightbar main {
            margin-top: 68px;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("sidebar.php") ?>

        <div class="rightbar">
            <!-- <nav>
                <div class="nav-logo">
                    <h2>Teacher <b>Dashboard</b></h2>
                </div>
                <form action="?url=logout" method="post">
                    <button><i class="fa fa-sign-out"></i> Logout</button>
                </form>
            </nav> -->

            <?php include("nav.php") ?>

            <main>
                <?php
                // Banner helper
                function tcGetBanner(string $name): string
                {
                    $n = strtolower($name);
                    if (str_contains($n, 'phil'))
                        return 'b-phil';
                    if (str_contains($n, 'ucsp') || str_contains($n, 'cultur'))
                        return 'b-ucsp';
                    if (str_contains($n, 'comput') || str_contains($n, 'css'))
                        return 'b-css';
                    if (str_contains($n, 'physical edu') || $n === 'pe' || str_contains($n, 'p.e'))
                        return 'b-pe';
                    if (str_contains($n, 'inquir') || str_contains($n, '3i'))
                        return 'b-3i';
                    if (str_contains($n, 'entrep'))
                        return 'b-entr';
                    if (str_contains($n, 'work') || str_contains($n, 'immersion'))
                        return 'b-work';
                    return 'b-def';
                }
                $schedulePool = [];
                ?>

                <!-- Page header -->
                <p class="tc-page-title">Classes</p>
                <p class="tc-page-sub">Manage your class and students here</p>

                <!-- Filter dropdowns -->
                <div class="tc-filters">
                    <!-- Classes dropdown -->
                    <div class="tc-dropdown" id="classDropdown">
                        <button class="tc-filter-btn" onclick="toggleDropdown('classDropdown')">
                            <span id="classLabel">Classes</span>
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <div class="tc-dropdown-menu" id="classMenu">
                            <div class="tc-dropdown-item active"
                                onclick="filterBy('class', 'all', 'Classes', 'classDropdown')">
                                All Classes
                            </div>
                            <?php
                            // Build unique subject list for dropdown
                            $uniqueSubjects = [];
                            foreach ($classes as $c) {
                                $id = $c['subject_id'];
                                if (!isset($uniqueSubjects[$id])) {
                                    $uniqueSubjects[$id] = $c['subject_name'];
                                }
                            }
                            foreach ($uniqueSubjects as $sid => $sname): ?>
                                <div class="tc-dropdown-item"
                                    onclick="filterBy('class', '<?= $sid ?>', '<?= htmlspecialchars(addslashes($sname)) ?>', 'classDropdown')">
                                    <?= htmlspecialchars($sname) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Sections dropdown -->
                    <div class="tc-dropdown" id="sectionDropdown">
                        <button class="tc-filter-btn outline" onclick="toggleDropdown('sectionDropdown')">
                            <span id="sectionLabel">Sections</span>
                            <i class="fa fa-chevron-down"></i>
                        </button>
                        <div class="tc-dropdown-menu" id="sectionMenu">
                            <div class="tc-dropdown-item active"
                                onclick="filterBy('section', 'all', 'Sections', 'sectionDropdown')">
                                All Sections
                            </div>
                            <?php
                            // Build unique sections list
                            $uniqueSections = [];
                            foreach ($classes as $c) {
                                $sec = trim($c['section'] ?? '');
                                if ($sec && !in_array($sec, $uniqueSections)) {
                                    $uniqueSections[] = $sec;
                                }
                            }
                            sort($uniqueSections);
                            foreach ($uniqueSections as $sec): ?>
                                <div class="tc-dropdown-item"
                                    onclick="filterBy('section', '<?= htmlspecialchars(addslashes($sec)) ?>', '<?= htmlspecialchars(addslashes($sec)) ?>', 'sectionDropdown')">
                                    <?= htmlspecialchars($sec) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Cards -->
                <div class="tc-grid">
                    <?php if (empty($classes)): ?>
                        <div class="tc-empty">
                            <i class="fa fa-chalkboard-teacher"></i>
                            <p>No classes assigned yet. Please contact your administrator.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($classes as $index => $class):
                            $banner = tcGetBanner($class['subject_name']);
                            $students = (int) ($class['student_count'] ?? 0);
                            $materials = (int) ($class['material_count'] ?? 0);
                            $announcements = (int) ($class['announcement_count'] ?? 0);
                            $section = trim($class['section'] ?? '');
                            ?>
                            <div class="tc-card" data-subject-id="<?= (int) $class['subject_id'] ?>"
                                data-section="<?= htmlspecialchars($section) ?>">
                                <!-- Banner image -->
                                <div class="tc-banner <?= $banner ?>"></div>

                                <!-- Body -->
                                <div class="tc-body">
                                    <div class="tc-info">
                                        <!-- Subject name -->
                                        <h3><?= htmlspecialchars($class['subject_name']) ?></h3>

                                        <!-- Single section as schedule line -->
                                        <p class="tc-schedule">
                                            <i class="fa fa-layer-group"></i>
                                            <?= htmlspecialchars($section) ?>
                                        </p>

                                        <!-- Student count -->
                                        <div class="tc-students">
                                            <i class="fa fa-users"></i>
                                            <?= $students ?> Students
                                        </div>

                                        <!-- Pills: Announcements + Materials only -->
                                        <div class="tc-pills">
                                            <span class="tc-pill">
                                                <i class="fa fa-bullhorn"></i>
                                                <?= $announcements ?> Announcement<?= $announcements !== 1 ? 's' : '' ?>
                                            </span>
                                            <span class="tc-pill">
                                                <i class="fa fa-folder"></i>
                                                <?= $materials ?> Material<?= $materials !== 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Manage button -->
                                    <a href="?url=teacher_class&id=<?= (int) $class['subject_id'] ?>&grade_id=<?= (int) $class['grade_level_id'] ?>&section_id=<?= (int) $class['section_id'] ?>"
                                        class="tc-manage <?= ($current_url ?? '') === 'classes_teacher' ? 'active' : '' ?>">
                                        Manage <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div><!-- /tc-grid -->
                <div id="tcNoResults"
                    style="display:none;text-align:center;padding:50px 20px;color:#9ca3af;font-size:14px;">
                    <i class="fa fa-search" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                    No classes match your filter. Try a different selection.
                </div>

            </main>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <style>
        /* ── DROPDOWN ── */
        .tc-dropdown {
            position: relative;
            display: inline-block;
        }

        .tc-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 200px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
            z-index: 999;
            overflow: hidden;
            animation: ddFade .15s ease;
        }

        @keyframes ddFade {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tc-dropdown-menu.open {
            display: block;
        }

        .tc-dropdown-item {
            padding: 10px 16px;
            font-size: 13px;
            color: #374151;
            cursor: pointer;
            transition: background .15s;
        }

        .tc-dropdown-item:hover {
            background: #f3f4f6;
        }

        .tc-dropdown-item.active {
            background: #ecfdf5;
            color: var(--green, #4caf82);
            font-weight: 600;
        }

        /* Hidden card */
        .tc-card.tc-hidden {
            display: none;
        }

        /* No results message */
        .tc-no-results {
            display: none;
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
            font-size: 14px;
        }
    </style>
    <script>
        // Active filters
        var activeClass = 'all';
        var activeSection = 'all';

        function toggleDropdown(id) {
            var menu = document.getElementById(id).querySelector('.tc-dropdown-menu');
            // Close all other menus first
            document.querySelectorAll('.tc-dropdown-menu').forEach(function (m) {
                if (m !== menu) m.classList.remove('open');
            });
            menu.classList.toggle('open');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.tc-dropdown')) {
                document.querySelectorAll('.tc-dropdown-menu').forEach(function (m) {
                    m.classList.remove('open');
                });
            }
        });

        function filterBy(type, value, label, dropdownId) {
            // Update label
            if (type === 'class') {
                activeClass = value;
                document.getElementById('classLabel').textContent = label;
                // Update active state in dropdown
                document.querySelectorAll('#classMenu .tc-dropdown-item').forEach(function (item) {
                    item.classList.remove('active');
                    if (item.textContent.trim() === label || (value === 'all' && item.textContent.trim() === 'All Classes')) {
                        item.classList.add('active');
                    }
                });
                // Toggle button style
                var btn = document.querySelector('#classDropdown .tc-filter-btn');
                if (value === 'all') {
                    btn.classList.remove('outline');
                } else {
                    btn.classList.remove('outline');
                }
            } else {
                activeSection = value;
                document.getElementById('sectionLabel').textContent = label;
                document.querySelectorAll('#sectionMenu .tc-dropdown-item').forEach(function (item) {
                    item.classList.remove('active');
                    if (item.textContent.trim() === label || (value === 'all' && item.textContent.trim() === 'All Sections')) {
                        item.classList.add('active');
                    }
                });
                // Toggle section button style
                var sBtn = document.querySelector('#sectionDropdown .tc-filter-btn');
                if (value === 'all') {
                    sBtn.classList.add('outline');
                } else {
                    sBtn.classList.remove('outline');
                }
            }

            // Close dropdown
            document.getElementById(dropdownId).querySelector('.tc-dropdown-menu').classList.remove('open');

            // Apply filter to cards
            applyFilters();
        }

        function applyFilters() {
            var cards = document.querySelectorAll('.tc-card');
            var visible = 0;

            cards.forEach(function (card) {
                var matchClass = (activeClass === 'all') || (card.dataset.subjectId === activeClass);
                var matchSection = (activeSection === 'all') || (card.dataset.section === activeSection);

                if (matchClass && matchSection) {
                    card.classList.remove('tc-hidden');
                    visible++;
                } else {
                    card.classList.add('tc-hidden');
                }
            });

            // Show/hide no results message
            var noRes = document.getElementById('tcNoResults');
            if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
        }
    </script>
</body>

</html>