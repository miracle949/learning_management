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
            $subject = $_GET['subject'] ?? $subject ?? null;
            $moduleProgress = $moduleProgress ?? [];

            if ($subject):
                if ($subjectInfo):
                    ?>

                    <!-- ── TOP NAV ── -->
                    <div class="bread-crambs">
                        Dashboard <i class="fa fa-chevron-right"></i> <b>Modules</b>
                    </div>

                    <div class="progress-banner">
                        <div class="progress-text">
                            <!-- <div class="progress-nav">
                                <span class="pulse-dot"></span>Pick up right where you left off.
                            </div> -->
                            <h2>You're doing great — keep the momentum going.</h2>
                            <p>Keep learning to unlock more lessons and quizzes. You've completed
                                <b><?= $overallProgress['completed'] ?> of <?= $overallProgress['total'] ?> modules</b> stay
                                consistent and you'll finish the rest in no time.
                            </p>

                            <div class="continue-learning">
                                <a href="#">Continue Learning <i class="fa fa-arrow-right"></i></a>
                            </div>

                        </div>

                        <?php
                        $ringRadius = 52;
                        $circumference = 2 * M_PI * $ringRadius;
                        $ringOffset = $circumference - ($circumference * $overallProgress['percentage'] / 100);
                        ?>
                        <div class="progress-side">
                            <div class="speech-bubble">
                                <strong>BonBon</strong>

                                <p id="bonbonMessage"></p>

                            </div>
                            <img src="../images/robot-ai8.png" alt="">
                            <div class="progress-parent">
                                <div class="progress-ring-wrap">
                                    <svg class="progress-ring" viewBox="0 0 120 120">
                                        <defs>
                                            <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" stop-color="#EAF0F8" />
                                                <stop offset="100%" stop-color="#FFFFFF" />
                                            </linearGradient>
                                        </defs>
                                        <circle class="ring-bg" cx="60" cy="60" r="<?= $ringRadius ?>" />
                                        <circle class="ring-fill" cx="60" cy="60" r="<?= $ringRadius ?>"
                                            style="stroke-dasharray: <?= $circumference ?>; stroke-dashoffset: <?= $ringOffset ?>;" />
                                    </svg>
                                    <div class="ring-label">
                                        <div class="ring-pct">
                                            <?= $overallProgress['percentage'] ?>%
                                        </div>
                                        <div class="ring-sub">Overall Progress</div>
                                    </div>
                                </div>
                            </div>
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
                                <div class="data-head">Quizzes</div>
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
                                <p>4</p>
                                <div class="data-head">Activities</div>
                                <span class="card-badge card-badge--green"><i class="fa fa-arrow-up"></i> +6% this week</span>
                            </div>
                        </div>
                    </div>

                    <!-- ── FILTER TABS + SEARCH ── -->
                    <div class="module-filter-bar">
                        <div class="module-tabs">
                            <button class="tab-btn active" data-filter="all">
                                <i class="fa fa-book-open"></i>
                                All Modules</button>
                            <button class="tab-btn" data-filter="in-progress">
                                <i class="fa fa-hourglass-half"></i>
                                In Progress</button>
                            <button class="tab-btn" data-filter="not-started">
                                <i class="fa fa-pause"></i>
                                Not Started</button>
                            <button class="tab-btn" data-filter="completed">
                                <i class="fa fa-circle-check"></i>
                                Completed</button>
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
                                    $imgCount = $imageCounts[$mod['id']] ?? 0;
                                    $vidCount = $videoCounts[$mod['id']] ?? 0;
                                    $actCount = $activityCounts[$mod['id']] ?? 0;
                                    $qzCount = $quizCounts[$mod['id']] ?? 0;

                                    // ✅ Progress-aware status: completed > in-progress > not-started
                                    $prog = $moduleProgress[$mod['id']] ?? null;
                                    $isFinished = $prog && (int) $prog['is_finished'] === 1;
                                    
                                    $isStarted = in_array($mod['id'], $startedModuleIds);
                                    $pct = $prog ? (float) $prog['completion_percentage'] : 0;
                                    $pct = max(0, min(100, $pct)); // clamp for safety
                    
                                    if ($isFinished) {
                                        $statusAttr = 'completed';
                                        $btnText = 'Review Module';
                                        $btnClass = 'start-now-btn btn-completed';
                                    } elseif ($isStarted) {
                                        $statusAttr = 'in-progress';
                                        $btnText = 'Continue Learning';
                                        $btnClass = 'start-now-btn btn-continue';
                                    } else {
                                        $statusAttr = 'not-started';
                                        $btnText = 'Start now';
                                        $btnClass = 'start-now-btn';
                                    }
                                    ?>
                                    <div class="module-feed-card" data-status="<?= $statusAttr ?>"
                                        data-title="<?= htmlspecialchars(strtolower($mod['title'])) ?>">

                                        <div class="card-img" style="position:relative;">
                                            <div class="card-text">
                                                <div class="module-banner-tag">CSS · Hardware</div>
                                            </div>
                                            <div class="card-icon">
                                                <i class="fa fa-desktop"></i>
                                            </div>
                                            <?php if ($isFinished): ?>
                                                <span class="card-badge card-badge--green"
                                                    style="position:absolute; top:10px; right:10px; z-index:2;">
                                                    <i class="fa fa-circle-check"></i> Completed
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="card-body">
                                            <h3><?= htmlspecialchars($mod['title']) ?></h3>
                                            <p><?= htmlspecialchars(
                                                !empty($mod['description'])
                                                ? mb_strimwidth($mod['description'], 0, 100, '...')
                                                : 'No description available.'
                                            ) ?></p>

                                            <!-- ── CONTENT TYPE BADGES ── -->
                                            <div class="module-content-badges">
                                                <?php if ($count > 0): ?>
                                                    <span class="content-badge badge--lesson">
                                                        <i class="fa fa-book-open"></i>
                                                        <?= $count ?> Lesson<?= $count !== 1 ? 's' : '' ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($vidCount > 0): ?>
                                                    <span class="content-badge badge--video">
                                                        <i class="fa fa-video"></i>
                                                        <?= $vidCount ?> Video<?= $vidCount !== 1 ? 's' : '' ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($imgCount > 0): ?>
                                                    <span class="content-badge badge--image">
                                                        <i class="fa fa-image"></i>
                                                        <?= $imgCount ?> Image<?= $imgCount !== 1 ? 's' : '' ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($actCount > 0): ?>
                                                    <span class="content-badge badge--activity">
                                                        <i class="fa fa-pen-to-square"></i>
                                                        <?= $actCount ?> Activit<?= $actCount !== 1 ? 'ies' : 'y' ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($qzCount > 0): ?>
                                                    <span class="content-badge badge--quiz">
                                                        <i class="fa fa-circle-question"></i>
                                                        <?= $qzCount ?> Quiz<?= $qzCount !== 1 ? 'zes' : '' ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- ── PER-MODULE PROGRESS BAR ── -->
                                            <!-- ── PER-MODULE PROGRESS BAR (segmented/dash style) ── -->
                                            <?php if ($prog):
                                                $totalSegments = 5;
                                                $filledSegments = (int) round(($pct / 100) * $totalSegments);
                                                $segmentClass = $isFinished ? ' completed' : '';
                                                ?>
                                                <div style="margin:4px 0 14px;">
                                                    <div
                                                        style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:6px;">
                                                        <span
                                                            style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.04em; color:#4A6B8A;">
                                                            Progress
                                                        </span>
                                                        <span
                                                            style="font-size:12.5px; font-weight:700; color:<?= $isFinished ? 'var(--neon-cyan)' : '#ff7a00' ?>;">
                                                            <?= (int) round($pct) ?>%
                                                        </span>
                                                    </div>
                                                    <div class="segmented-progress">
                                                        <?php for ($i = 1; $i <= $totalSegments; $i++): ?>
                                                            <div class="segment<?= $i <= $filledSegments ? ' filled' . $segmentClass : '' ?>">
                                                            </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

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
        (function typewriter() {
            const el = document.getElementById('bonbonMessage');
            if (!el) return;

            const message = "This is your overall progress you're doing better than you think! Keep completing modules and you'll reach 100%.";
            const speed = 28; // ms per character — lower = faster
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
                var status = card.dataset.status;
                var title = card.dataset.title;

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

        // ✅ This is the function called by onclick="handleModuleStart(this)"
        function handleModuleStart(btn) {
            var moduleId = btn.dataset.moduleId;
            var href = btn.dataset.href;
            // Completed modules behave like "continue" — just navigate,
            // no need to re-mark as started.
            var isStarted = btn.classList.contains('btn-continue') || btn.classList.contains('btn-completed');

            if (isStarted) {
                window.location.href = href;
                return;
            }

            // Disable button while request is in-flight
            btn.disabled = true;
            btn.innerHTML = 'Starting... <i class="fa fa-spinner fa-spin"></i>';

            var fd = new FormData();
            fd.append('module_id', moduleId);
            fetch('/learning_management/public/?url=mark_module_started', {
                method: 'POST',
                body: fd
            })
                .then(function (res) { return res.json(); })
                .then(function () {
                    // Navigate AFTER server confirms the write
                    window.location.href = href;
                })
                .catch(function () {
                    window.location.href = href;
                });
        }
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>