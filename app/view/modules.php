<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/modules.css">
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

            $subject = $_GET['subject'] ?? $subject ?? null;

            if ($subject):
                $subjectInfo = $studentModel->getSubjectBySlug($subject);
                $modules = $studentModel->getInteractiveModules($subject);
                $startedModuleIds = $studentModel->getStartedModuleIds($_SESSION['student_id'] ?? 0);

                // Build lesson counts per module
                $lessonCounts = [];
                foreach ($modules as $mod) {
                    $lessonCounts[$mod['id']] = $studentModel->countIMlessons($mod['id']);
                }

                if ($subjectInfo):
                    ?>

                    <!-- ── TOP NAV ── -->
                    <div class="module-nav">
                        <div class="module-text">
                            <h2>Modules</h2>
                            <p>Lessons, quizzes, activities, videos &amp; images across your subject</p>
                        </div>
                        <div class="module-acc">
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
                    </div>

                    <!-- ── STAT CARDS ── -->
                    <div class="card-box-parent">
                        <div class="card-box">
                            <div class="card-icon card-icon--modules">
                                <i class="fa fa-layer-group"></i>
                            </div>
                            <div class="card-text">
                                <p>9</p>
                                <div class="data-head">Total Modules</div>
                                <span class="card-badge card-badge--green"><i class="fa fa-arrow-up"></i> Across 5
                                    classes</span>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-icon card-icon--quiz">
                                <i class="fa fa-pen-to-square"></i>
                            </div>
                            <div class="card-text">
                                <p>14</p>
                                <div class="data-head">Quizzes &amp; Activities</div>
                                <span class="card-badge card-badge--blue">11 Completed</span>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-icon card-icon--progress">
                                <i class="fa fa-spinner"></i>
                            </div>
                            <div class="card-text">
                                <p>3</p>
                                <div class="data-head">Modules in progress</div>
                                <span class="card-badge card-badge--orange">where you left off</span>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-icon card-icon--percent">
                                <i class="fa fa-chart-line"></i>
                            </div>
                            <div class="card-text">
                                <p>81%</p>
                                <div class="data-head">Module progress</div>
                                <span class="card-badge card-badge--green"><i class="fa fa-arrow-up"></i> +6% this week</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── FILTER TABS + SEARCH ── -->
                    <div class="module-filter-bar">
                        <div class="module-tabs">
                            <button class="tab-btn active" data-filter="all">All Modules</button>
                            <button class="tab-btn" data-filter="in-progress">In Progress</button>
                            <button class="tab-btn" data-filter="not-started">Not Started</button>
                            <button class="tab-btn" data-filter="completed">Completed</button>
                        </div>
                        <div class="module-search">
                            <i class="fa fa-search"></i>
                            <input type="text" id="moduleSearchInput" placeholder="Search modules...">
                        </div>
                    </div>

                    <!-- ── MODULE CARDS GRID ── -->
                    <div class="modules-main">
                        <div class="learning-module" id="moduleGrid">
                            <?php if (empty($modules)): ?>
                                <div class="modules-empty">
                                    <i class="fa fa-book-open"></i>
                                    <p>No interactive modules available yet.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($modules as $mod):
                                    $detailUrl = "/learning_management/public/?url=subject_lessons&subject=" . urlencode($subject) . "&id={$mod['id']}";
                                    $count = $lessonCounts[$mod['id']] ?? 0;
                                    $isStarted = in_array($mod['id'], $startedModuleIds);
                                    $btnText = $isStarted ? 'Continue Learning' : 'Start now';
                                    $btnClass = $isStarted ? 'start-now-btn btn-continue' : 'start-now-btn';
                                    // data-status for JS filtering: 'started' or 'not-started'
                                    $statusAttr = $isStarted ? 'in-progress' : 'not-started';
                                    ?>
                                    <div class="module-feed-card" data-status="<?= $statusAttr ?>"
                                        data-title="<?= htmlspecialchars(strtolower($mod['title'])) ?>">
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
                                            <button class="<?= $btnClass ?>" data-module-id="<?= (int) $mod['id'] ?>"
                                                data-href="<?= htmlspecialchars($detailUrl) ?>" onclick="handleModuleStart(this)">
                                                <?= $btnText ?> <i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Empty state for filtering -->
                        <div class="modules-empty filter-empty" id="filterEmpty" style="display:none;">
                            <i class="fa fa-filter"></i>
                            <p>No modules match this filter.</p>
                        </div>
                    </div>

                    <?php
                else:
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
        // ── TAB FILTERING ──────────────────────────────────────────
        var currentFilter = 'all';
        var currentSearch = '';

        document.querySelectorAll('.tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                currentFilter = btn.dataset.filter;
                applyFilters();
            });
        });

        document.getElementById('moduleSearchInput').addEventListener('input', function () {
            currentSearch = this.value.trim().toLowerCase();
            applyFilters();
        });

        function applyFilters() {
            var cards = document.querySelectorAll('.module-feed-card');
            var visibleCount = 0;

            cards.forEach(function (card) {
                var status = card.dataset.status;   // 'in-progress' | 'not-started' | 'completed'
                var title = card.dataset.title;     // lowercase title

                var matchesFilter = currentFilter === 'all' || status === currentFilter;
                var matchesSearch = currentSearch === '' || title.indexOf(currentSearch) !== -1;

                if (matchesFilter && matchesSearch) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('filterEmpty').style.display = visibleCount === 0 ? 'flex' : 'none';
            document.getElementById('moduleGrid').style.display = visibleCount === 0 ? 'none' : '';
        }

        // ── MODULE START ───────────────────────────────────────────
        function handleModuleStart(btn) {
            var moduleId = btn.dataset.moduleId;
            var href = btn.dataset.href;
            var isStarted = btn.classList.contains('btn-continue');

            if (isStarted) {
                window.location.href = href;
                return;
            }

            var fd = new FormData();
            fd.append('module_id', moduleId);
            fetch('/learning_management/public/?url=mark_module_started', {
                method: 'POST',
                body: fd
            })
                .then(function (res) { return res.json(); })
                .then(function () { window.location.href = href; })
                .catch(function () { window.location.href = href; });
        }
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>