<?php
// ============================================================
// assignments.php — grouped-by-subject assignment tracker
// Fed by $subjectGroups / $totalAssignments / $urgentCount /
// $pendingCount / $missingCount / $completedCount / $gradedCount /
// $avgScore / $typeCounts, all built in
// StudentsController::assignments_view()
//
// - No progress percentage on subject headers
// - Whole card is a link to assignment_view (no CTA button)
// - Client-side pagination, 10 cards per page, works together
//   with the existing search + filter dropdown
// ============================================================

$assignmentTypes = ['Seatwork', 'Homework', 'Project', 'Quiz', 'Exam', 'Performance Task'];

function aq_type_slug($type)
{
    return strtolower(str_replace(' ', '-', trim((string) $type)));
}

function aq_status_icon($status)
{
    $map = [
        'pending' => 'fa-clock',
        'missing' => 'fa-triangle-exclamation',
        'completed' => 'fa-circle-check',
        'graded' => 'fa-file-circle-check',
    ];
    return $map[$status] ?? 'fa-clipboard-list';
}

// Safe fallbacks in case the controller didn't set something
$subjectGroups = $subjectGroups ?? [];
$totalAssignments = $totalAssignments ?? 0;
$urgentCount = $urgentCount ?? 0;
$pendingCount = $pendingCount ?? 0;
$missingCount = $missingCount ?? 0;
$completedCount = $completedCount ?? 0;
$gradedCount = $gradedCount ?? 0;
$avgScore = $avgScore ?? null;
$typeCounts = $typeCounts ?? [];
$submittedOnlyCount = $submittedOnlyCount ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <link rel="stylesheet" href="../css_folder/assignments.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap"
        rel="stylesheet">

    <style>
        
    </style>
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="aq-wrap">
                <!-- ── TOP NAV ── -->
                <div class="bread-crambs">
                    Dashboard <i class="fa fa-chevron-right"></i> <b>Assignments</b>
                </div>

                <div class="aq-header">

                    <div class="aq-header-left">
                        <h1>Assignment Tasks and Learning Activities</h1>
                        <div class="sub">
                            View and manage all your assignments across your subjects. Keep track of upcoming, pending, overdue, completed, and graded assignments so you can stay organized and submit your work on time.
                        </div>
                    </div>
                    <div class="aq-header-right">
                        <!-- <h2>hello</h2> -->
                         <div class="speech-bubble">
                            <strong>BonBon</strong>

                            <p id="bonbonMessage"></p>

                        </div>
                         <img src="../images/assignment-robot.png" alt="">
                    </div>
                </div>

                <section class="aq-stats">
                    <button class="aq-chip" data-filter="all" type="button">
                        
                        <div>
                            <div class="aq-chip-label">Total assignments</div>
                            <div class="aq-chip-num">
                                <?= (int) $totalAssignments ?>
                            </div>
                            <div class="aq-chip-sub">Across <?= count($subjectGroups) ?> subject<?= count($subjectGroups) === 1 ? '' : 's' ?></div>
                        </div>
                        <div class="aq-chip-icon">
                            <i class="fa fa-list-check"></i>
                        </div>
                        
                    </button>
                    <button class="aq-chip" data-filter="urgent" type="button">
                        <div>
                            <div class="aq-chip-label">Overdue</div>
                            <div class="aq-chip-num"><?= (int) $urgentCount ?></div>
                            
                            <div class="aq-chip-sub warn"><?= $urgentCount > 0 ? 'Needs attention' : 'Nothing overdue' ?></div>
                        </div>
                        <div class="aq-chip-icon">
                            <i class="fa fa-triangle-exclamation"></i>
                        </div>
                        
                    </button>
                    <button class="aq-chip" data-filter="pending" type="button">
                        <div>
                            <div class="aq-chip-label">Pending</div>
                            <div class="aq-chip-num"><?= (int) $pendingCount ?></div>
                            
                            <div class="aq-chip-sub">Not yet submitted</div>
                        </div>
                        <div class="aq-chip-icon">
                            <i class="fa fa-clock"></i>
                        </div>
                    </button>
                    <button class="aq-chip" data-filter="completed" type="button">
                        <div>
                            <div class="aq-chip-label">Completed</div>
                            <div class="aq-chip-num"><?= (int) $submittedOnlyCount ?></div>
                            <div class="aq-chip-sub">Awaiting grade</div>
                        </div>
                        <div class="aq-chip-icon">
                            <i class="fa fa-circle-check"></i>
                        </div>
                    </button>

                    <button class="aq-chip" data-filter="graded" type="button">
                        <div>
                            <div class="aq-chip-label">Graded</div>
                            <div class="aq-chip-num"><?= (int) $gradedCount ?></div>
                            <div class="aq-chip-sub up"><?= $avgScore !== null ? 'Avg score ' . $avgScore . '%' : 'No grades yet' ?></div>
                        </div>
                        <div class="aq-chip-icon">
                            <i class="fa fa-file-circle-check"></i>
                        </div>
                    </button>
                </section>

                <div class="aq-filter-row">
                    <!-- <div class="aq-search-row">
                        
                    </div> -->
                    <div class="aq-search-box">
                        <i class="fa fa-search"></i>
                        <input id="aqSearch" type="text" placeholder="Search assignments…" autocomplete="off">
                    </div>
                    <div class="aq-filter-dropdown" id="aqFilterDropdown">
                        <button class="aq-filter-dropdown-btn" id="aqFilterBtn" type="button" aria-haspopup="listbox" aria-expanded="false">
                            <span id="aqFilterLabel">All assignments</span>
                            <i class="fa fa-chevron-down aq-chev"></i>
                        </button>
                        <div class="aq-filter-dropdown-menu" id="aqFilterMenu" role="listbox">
                            <div class="aq-filter-option active" data-filter="all" role="option">All assignments</div>
                            <div class="aq-filter-divider"></div>
                            <div class="aq-filter-option" data-filter="urgent" role="option">Overdue</div>
                            <div class="aq-filter-option" data-filter="pending" role="option">Pending</div>
                            <div class="aq-filter-option" data-filter="missing" role="option">Missing</div>
                            <div class="aq-filter-option" data-filter="completed" role="option">Completed</div>
                            <div class="aq-filter-option" data-filter="graded" role="option">Graded</div>
                            <?php
                            $hasTypeFilters = false;
                            foreach ($assignmentTypes as $type) {
                                if (!empty($typeCounts[aq_type_slug($type)])) {
                                    $hasTypeFilters = true;
                                    break;
                                }
                            }
                            ?>
                            <?php if ($hasTypeFilters): ?>
                                        <div class="aq-filter-divider"></div>
                                        <?php foreach ($assignmentTypes as $type):
                                            $slug = aq_type_slug($type);
                                            if (empty($typeCounts[$slug]))
                                                continue;
                                            ?>
                                                    <div class="aq-filter-option" data-filter="<?= $slug ?>" role="option">
                                                        <?= htmlspecialchars($type) ?> <span class="aq-count"><?= $typeCounts[$slug] ?></span>
                                                    </div>
                                        <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="aq-active-filter-tag" id="aqActiveTag">
                        <span id="aqActiveTagLabel"></span>
                        <button type="button" id="aqClearFilter" aria-label="Clear filter"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <?php if (empty($subjectGroups)): ?>
                            <div class="aq-empty is-visible">
                                <i class="fa fa-inbox"></i>
                                <strong>No assignments yet</strong>
                                <span>Join a class to see assignments here.</span>
                            </div>
                <?php else: ?>
                            <?php foreach ($subjectGroups as $group): ?>
                                        <section class="aq-subject" data-subject>
                                            <div class="aq-subject-head">
                                                <div class="aq-subject-left">
                                                    <div class="aq-subject-icon"><i class="fa fa-book"></i></div>
                                                    <div>
                                                        <div class="aq-subject-title"><?= htmlspecialchars($group['subject_name']) ?></div>
                                                        <div class="aq-subject-meta">
                                                            <?= $group['total'] ?> assignment<?= $group['total'] === 1 ? '' : 's' ?>
                                                            · <?= $group['done'] ?> done
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="aq-grid" data-quadrant>
                                                <?php foreach ($group['items'] as $item):
                                                    $status = $item['status'];
                                                    $typeSlug = aq_type_slug($item['type'] ?? '');
                                                    $titleLower = htmlspecialchars(strtolower($item['task']));

                                                    $badgeClass = 'normal';
                                                    $badgeText = 'Upcoming';
                                                    $metaLine = '';
                                                    $scoreHtml = '';
                                                    $accent = 'var(--aq-cyan)';

                                                    if ($status === 'pending') {
                                                        $dl = $item['days_left'] ?? null;
                                                        if ($item['urgency'] === 'urgent') {
                                                            $badgeClass = 'urgent';
                                                            $badgeText = ($dl !== null && $dl <= 0) ? 'Due Today' : 'Urgent';
                                                            $accent = 'var(--aq-danger)';
                                                        } elseif ($item['urgency'] === 'soon') {
                                                            $badgeClass = 'soon';
                                                            $badgeText = 'Soon';
                                                            $accent = 'var(--aq-gold)';
                                                        }
                                                        $metaLine = !empty($item['due_date'])
                                                            ? 'Due ' . date('F j, Y', strtotime($item['due_date'])) . (!empty($item['due_time']) ? ' · ' . date('g:i A', strtotime($item['due_time'])) : '')
                                                            : 'No due date';
                                                    } elseif ($status === 'missing') {
                                                            $badgeClass = 'urgent';
                                                            $badgeText = 'Overdue';
                                                            $accent = 'var(--aq-danger)';
                                                            $metaLine = !empty($item['due_date'])
                                                                ? 'Was due ' . date('F j, Y', strtotime($item['due_date'])) . (!empty($item['overdue_label']) ? ' · ' . $item['overdue_label'] : '')
                                                                : '';
                                                        
                                                    } elseif ($status === 'completed') {
                                                        $badgeClass = 'done';
                                                        $badgeText = 'Submitted';
                                                        $accent = 'var(--aq-green)';
                                                        $metaLine = !empty($item['submitted_at']) ? 'Submitted ' . date('F j, Y', strtotime($item['submitted_at'])) : '';
                                                    } elseif ($status === 'graded') {
                                                        $badgeClass = 'done';
                                                        $badgeText = 'Graded';
                                                        $accent = 'var(--aq-green)';
                                                        $metaLine = !empty($item['submitted_at']) ? 'Submitted ' . date('F j, Y', strtotime($item['submitted_at'])) : '';
                                                        $totalPts = (int) ($item['total_points'] ?? 0);
                                                        $earned = (int) ($item['points_earned'] ?? 0);
                                                        $pct = $totalPts > 0 ? round(($earned / $totalPts) * 100) : 0;
                                                        $scoreColor = $pct >= 75 ? 'var(--aq-green)' : 'var(--aq-danger)';
                                                        $scoreHtml = '<div class="aq-score" style="color:' . $scoreColor . '">' . $earned
                                                            . '<span style="color:var(--aq-text-faint); font-weight:600;"> / ' . $totalPts . '</span></div>';
                                                    }

                                                    $isUrgentFlag = ($status === 'missing' || ($status === 'pending' && $item['urgency'] === 'urgent')) ? 1 : 0;
                                                    ?>
                                                            <a class="aq-card"
                                                               style="--accent: <?= $accent ?>"
                                                               data-status="<?= $status ?>"
                                                               data-type="<?= $typeSlug ?>"
                                                               data-urgent="<?= $isUrgentFlag ?>"
                                                               data-title="<?= $titleLower ?>"
                                                               href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= (int) $item['id'] ?>">
                                                                <div class="aq-parent-card">
                                                                        <div class="aq-icon"><i class="fa <?= aq_status_icon($status) ?>"></i></div>
                                                                    <div class="aq-body">
                                                                        <div class="aq-title-row">
                                                                            <div class="aq-text">
                                                                                    <div class="aq-title-type">
                                                                                        <div class="aq-title"><?= htmlspecialchars($item['task']) ?></div>
                                                                                        <?php if (!empty($item['type'])): ?>
                                                                                            <span class="aq-type"><?= htmlspecialchars($item['type']) ?></span>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                <?php if (!empty($item['description'])): ?>
                                                                                    <div class="aq-description">
                                                                                        <?= htmlspecialchars($item['description']) ?>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <div class="aq-right">
                                                                        <span class="aq-badge <?= $badgeClass ?>"><?= $badgeText ?></span>
                                                                        <?= $scoreHtml ?>
                                                                    </div>
                                                                </div>

                                                                <hr>

                                                                <div class="aq-footer-card">
                                                                    <div class="aq-meta">
                                                                        <?php if ($metaLine): ?><span><i class="fa fa-calendar"></i><?= htmlspecialchars($metaLine) ?></span><?php endif; ?>
                                                                            
                                                                        (<?php if (!empty($item['total_points'])): ?><span><?= (int) $item['total_points'] ?> pts</span><?php endif; ?>)
                                                                    </div>
                                                                </div>
                                                            </a>
                                                <?php endforeach; ?>
                                            </div>
                                        </section>
                            <?php endforeach; ?>

                            <div class="aq-empty" id="aqEmpty">
                                <i class="fa fa-inbox"></i>
                                <strong>No assignments match</strong>
                                <span>Try a different filter or clear your search.</span>
                            </div>

                            <div class="aq-pagination" id="aqPagination">
                                <button type="button" class="aq-page-btn" id="aqPrevPage" aria-label="Previous page">
                                    <i class="fa fa-chevron-left"></i>
                                </button>
                                <span class="aq-page-info" id="aqPageInfo">Page 1 of 1</span>
                                <button type="button" class="aq-page-btn" id="aqNextPage" aria-label="Next page">
                                    <i class="fa fa-chevron-right"></i>
                                </button>
                            </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script>
        <?php
        // ── BonBon contextual message for the Assignments page ──
        if ($urgentCount > 0) {
            $bonbonMsg = $urgentCount === 1
                ? "Heads up — you have 1 overdue assignment. Let's knock that out first!"
                : "Heads up — you have {$urgentCount} overdue assignments. Let's tackle those first!";
        } elseif ($pendingCount > 0) {
            $bonbonMsg = $pendingCount === 1
                ? "You have 1 assignment waiting to be submitted. You've got this!"
                : "You have {$pendingCount} assignments waiting to be submitted. You've got this!";
        } elseif ($submittedOnlyCount > 0) {
            $bonbonMsg = $submittedOnlyCount === 1
                ? "Nice work! 1 assignment is submitted and waiting to be graded."
                : "Nice work! {$submittedOnlyCount} assignments are submitted and waiting to be graded.";
        } elseif ($gradedCount > 0) {
            $bonbonMsg = $avgScore !== null
                ? "You're all caught up! Your average score so far is {$avgScore}%. Keep it up!"
                : "You're all caught up on your assignments. Great job!";
        } else {
            $bonbonMsg = "No assignments yet — once your teachers post some, they'll show up here.";
        }
        ?>
    </script>

    <script>
        (function typewriter() {
            const el = document.getElementById('bonbonMessage');
            if (!el) return;

            const message = <?= json_encode($bonbonMsg) ?>;
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
                    setTimeout(() => cursor.remove(), 1200);
                }
            }

            type();
        })();
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('aqSearch');
            if (!searchInput) return; // no assignments at all — nothing to wire up

            var dropdown = document.getElementById('aqFilterDropdown');
            var dropdownBtn = document.getElementById('aqFilterBtn');
            var dropdownLabel = document.getElementById('aqFilterLabel');
            var options = document.querySelectorAll('.aq-filter-option');
            var chips = document.querySelectorAll('.aq-chip[data-filter]');
            var activeTag = document.getElementById('aqActiveTag');
            var activeTagLabel = document.getElementById('aqActiveTagLabel');
            var clearBtn = document.getElementById('aqClearFilter');
            var subjects = document.querySelectorAll('.aq-subject');
            var globalEmpty = document.getElementById('aqEmpty');

            var pagination = document.getElementById('aqPagination');
            var pageInfo = document.getElementById('aqPageInfo');
            var prevBtn = document.getElementById('aqPrevPage');
            var nextBtn = document.getElementById('aqNextPage');

            var activeFilter = 'all';
            var currentPage = 1;
            var perPage = 10;

            function matchesFilter(card, filter) {
                if (filter === 'all') return true;
                if (filter === 'urgent') return card.dataset.urgent === '1';
                if (filter === 'pending' || filter === 'missing' || filter === 'completed' || filter === 'graded') {
                    return card.dataset.status === filter;
                }
                return card.dataset.type === filter;
            }

            function applyFilters() {
                var query = (searchInput.value || '').trim().toLowerCase();
                var matches = [];

                // Pass 1: filter/search — decide which cards are eligible at all
                subjects.forEach(function (subject) {
                    var cards = subject.querySelectorAll('.aq-card');
                    cards.forEach(function (card) {
                        var filterOk = matchesFilter(card, activeFilter);
                        var searchOk = !query || (card.dataset.title || '').indexOf(query) !== -1;
                        var show = filterOk && searchOk;
                        card.classList.toggle('aq-hide-filter', !show);
                        if (show) matches.push(card);
                    });
                });

                // Pass 2: pagination over whatever matched
                var totalPages = Math.max(1, Math.ceil(matches.length / perPage));
                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;

                var start = (currentPage - 1) * perPage;
                var end = start + perPage;

                matches.forEach(function (card, i) {
                    card.classList.toggle('aq-hide-page', i < start || i >= end);
                });

                // Subject section is visible only if it has a card showing on this page
                subjects.forEach(function (subject) {
                    var visibleInSubject = subject.querySelectorAll('.aq-card:not(.aq-hide-filter):not(.aq-hide-page)').length;
                    subject.classList.toggle('is-hidden', visibleInSubject === 0);
                });

                if (globalEmpty) globalEmpty.classList.toggle('is-visible', matches.length === 0);

                if (pagination) {
                    pagination.classList.toggle('is-hidden', totalPages <= 1);
                    pageInfo.textContent = 'Page ' + currentPage + ' of ' + totalPages;
                    prevBtn.disabled = currentPage <= 1;
                    nextBtn.disabled = currentPage >= totalPages;
                }
            }

            function setFilter(filter, label) {
                activeFilter = filter;
                currentPage = 1;

                options.forEach(function (opt) {
                    opt.classList.toggle('active', opt.dataset.filter === filter);
                });

                if (filter === 'all') {
                    dropdownLabel.textContent = 'All assignments';
                    activeTag.classList.remove('is-visible');
                } else {
                    dropdownLabel.textContent = label;
                    activeTagLabel.textContent = label;
                    activeTag.classList.add('is-visible');
                }

                applyFilters();
                closeDropdown();
            }

            function openDropdown() { dropdown.classList.add('is-open'); dropdownBtn.setAttribute('aria-expanded', 'true'); }
            function closeDropdown() { dropdown.classList.remove('is-open'); dropdownBtn.setAttribute('aria-expanded', 'false'); }

            dropdownBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.contains('is-open') ? closeDropdown() : openDropdown();
            });
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target)) closeDropdown();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDropdown();
            });

            options.forEach(function (opt) {
                opt.addEventListener('click', function () {
                    var label = opt.childNodes[0].textContent.trim();
                    setFilter(opt.dataset.filter, label);
                });
            });

            chips.forEach(function (chip) {
                chip.addEventListener('click', function () {
                    var label = chip.querySelector('.aq-chip-label').textContent.trim();
                    setFilter(chip.dataset.filter, label);
                });
            });

            clearBtn.addEventListener('click', function () { setFilter('all', 'All assignments'); });

            searchInput.addEventListener('input', function () {
                currentPage = 1;
                applyFilters();
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    if (currentPage > 1) { currentPage--; applyFilters(); }
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    currentPage++;
                    applyFilters();
                });
            }

            applyFilters();
        });
    </script>
</body>

</html>