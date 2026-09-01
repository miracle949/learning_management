<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Modules</title>
    <link rel="stylesheet" href="../css_folder/modules_teacher.css">
    <link rel="stylesheet" href="../css_folder/modules_teacher_interactive.css">
    <link rel="stylesheet" href="../css_folder/components.css">

    <!-- <link rel="stylesheet" href="../css_folder/create_module.css"> -->
    <!-- <link rel="stylesheet" href="../css_folder/create_activities_blocks.css"> -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>

    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("sidebar.php") ?>

        <div class="rightbar">
            <main>

                <?php if ($subjectInfo): ?>

                    <div class="nav-banner">
                        <a href="/learning_management/public/?url=modules_teacher" class="back-breadcrumb">
                            <i class="fa fa-arrow-left"></i> Back to Modules
                        </a>
                    </div>

                    <!-- ── BANNER ── -->
                    <div class="module-title">
                        <!-- <div class="module-picture" <?php if (!empty($subjectInfo['subject_image'])): ?>
                                style="background-image: url('/learning_management/<?= htmlspecialchars($subjectInfo['subject_image']) ?>');"
                            <?php endif; ?>>
                            <h2><?= htmlspecialchars($subjectInfo['subject_name']) ?></h2>
                        </div> -->
                        <div class="module-body">
                            <div>
                                <h1><?= htmlspecialchars($subjectInfo['subject_name']) ?></h1>
                                <p><?= htmlspecialchars($subjectInfo['subject_description'] ?? 'No description available.') ?>
                                </p>
                            </div>
                            <!-- <a href="/learning_management/public/?url=create_module&subject_id=<?= (int) $subjectId ?>"
                                class="module-browse-btn">
                                <i class="fa fa-plus"></i> Create Module
                            </a> -->
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

                        <!-- Module List -->
                        <div class="learning-module-wrap" style="flex:1;">

                            <!-- ── TOOLBAR: heading + Grid/List toggle ── -->
                            <div class="modules-toolbar">

                                <?php if (!empty($modules)): ?>
                                    <div class="view-toggle" id="viewToggle">
                                        <button type="button" class="view-btn" data-view="grid" onclick="setModuleView('grid')">
                                            <i class="fa fa-th-large"></i> Grid View
                                        </button>
                                        <button type="button" class="view-btn active" data-view="list"
                                            onclick="setModuleView('list')">
                                            <i class="fa fa-list"></i> List View
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <div class="toolbar-heading">
                                    <!-- <div class="icon-badge"><i class="fa fa-bookmark"></i></div>
                                    <div>
                                        <h4>Your Modules</h4>
                                        <span>Manage and organize your learning modules</span>
                                    </div> -->
                                    <button type="button" class="btn-open-create-module" data-bs-toggle="modal"
                                        data-bs-target="#createModuleModal">
                                        <i class="fa fa-plus"></i>
                                        <span>Create module</span>
                                    </button>
                                </div>
                            </div>

                            <div class="learning-module" id="learningModule">
                                <?php if (empty($modules)): ?>
                                    <div class="modules-empty">
                                        <i class="fa fa-book-open"></i>
                                        <p>No modules yet for this subject.</p>
                                    </div>
                                <?php else: ?>
                                    <?php
                                    $iconColors = [
                                        ['bg' => '#E4FCFF', 'fg' => '#00A8CC'], // cyan
                                        ['bg' => '#E9F7FF', 'fg' => '#0EA5E9'], // sky blue
                                        ['bg' => '#EAFBFA', 'fg' => '#0D9488'], // teal
                                        ['bg' => '#EEF9FF', 'fg' => '#3B82F6'], // blue
                                    ];
                                    ?>
                                    <?php foreach ($modules as $i => $mod):
                                        $lessonCount = (int) ($mod['lesson_count'] ?? 0);
                                        $videoCount = (int) ($mod['video_count'] ?? 0);
                                        $imageCount = (int) ($mod['image_count'] ?? 0);
                                        $activityCount = (int) ($mod['activity_count'] ?? 0);
                                        $quizCount = (int) ($mod['quiz_count'] ?? 0);
                                        $arrangeStepsCount = (int) ($mod['arrange_steps_count'] ?? 0);
                                        $color = $iconColors[$i % count($iconColors)];

                                        $firstLesson = $teacherModel->getLessonsByModule($mod['id']);
                                        $firstLessonId = !empty($firstLesson) ? (int) $firstLesson[0]['id'] : 0;
                                        $lessonsUrl = "/learning_management/public/?url=subject_lessons_teacher&subject_id={$subjectId}&id={$mod['id']}"
                                            . ($firstLessonId ? "&lesson={$firstLessonId}" : '');
                                        ?>
                                        <div class="module-feed-card">
                                            <div class="modal-header">
                                                <div class="card-icon-box" style="background:<?= $color['bg'] ?>;">
                                                    <i class="fa fa-book-open" style="color:<?= $color['fg'] ?>;"></i>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h3><?= htmlspecialchars($mod['title']) ?></h3>
                                                <p><?= htmlspecialchars(
                                                    !empty($mod['description'])
                                                    ? mb_strimwidth($mod['description'], 0, 100, '...')
                                                    : 'No description available.'
                                                ) ?></p>
                                                <div class="card-stats">
                                                    <span class="stat-pill"><i class="fa fa-book-open"></i> <?= $lessonCount ?>
                                                        Lesson<?= $lessonCount !== 1 ? 's' : '' ?></span>
                                                    <span class="stat-pill"><i class="fa fa-circle-play"></i> <?= $videoCount ?>
                                                        Video<?= $videoCount !== 1 ? 's' : '' ?></span>
                                                    <span class="stat-pill"><i class="fa fa-image"></i> <?= $imageCount ?>
                                                        Image<?= $imageCount !== 1 ? 's' : '' ?></span>
                                                    <span class="stat-pill"><i class="fa fa-pen"></i> <?= $activityCount ?>
                                                        Activit<?= $activityCount !== 1 ? 'ies' : 'y' ?></span>
                                                    <span class="stat-pill"><i class="fa fa-circle-question"></i> <?= $quizCount ?>
                                                        Quiz<?= $quizCount !== 1 ? 'zes' : '' ?></span>
                                                    <span class="stat-pill"><i class="fa fa-random"></i> <?= $arrangeStepsCount ?>
                                                        Arrange<?= $arrangeStepsCount !== 1 ? ' Games' : ' Game' ?></span>
                                                </div>
                                            </div>
                                            <div class="card-actions">
                                                <a href="<?= htmlspecialchars($lessonsUrl) ?>" class="btn-view-lessons">
                                                    View Lessons <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- ── PAGINATION (client-side, 10 per page) ── -->
                            <div class="modules-pagination" id="modulesPagination">
                                <span class="pagination-info" id="paginationInfo"></span>
                                <div class="pagination-controls" id="paginationControls"></div>
                            </div>

                        </div><!-- /learning-module-wrap -->

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

        // ── GRID / LIST VIEW TOGGLE ──
        function setModuleView(view) {
            var wrap = document.getElementById('learningModule');
            var buttons = document.querySelectorAll('#viewToggle .view-btn');
            if (!wrap) return;

            if (view === 'grid') {
                wrap.classList.add('grid-view');
            } else {
                wrap.classList.remove('grid-view');
            }

            buttons.forEach(function (b) {
                b.classList.toggle('active', b.dataset.view === view);
            });

            try {
                localStorage.setItem('modulesViewPreference', view);
            } catch (e) { /* ignore storage errors */ }
        }

        // ── PAGINATION (client-side, 10 modules per page) ──
        var MODULES_PER_PAGE = 10;
        var currentModulePage = 1;

        function paginateModules() {
            var container = document.getElementById('learningModule');
            if (!container) return;

            var cards = Array.prototype.slice.call(container.querySelectorAll('.module-feed-card'));
            var totalItems = cards.length;
            var totalPages = Math.max(1, Math.ceil(totalItems / MODULES_PER_PAGE));

            if (currentModulePage > totalPages) currentModulePage = totalPages;
            if (currentModulePage < 1) currentModulePage = 1;

            var start = (currentModulePage - 1) * MODULES_PER_PAGE;
            var end = start + MODULES_PER_PAGE;

            cards.forEach(function (card, i) {
                card.style.display = (i >= start && i < end) ? '' : 'none';
            });

            renderModulePagination(totalItems, totalPages, start, end);
        }

        function renderModulePagination(totalItems, totalPages, start, end) {
            var wrap = document.getElementById('modulesPagination');
            var infoEl = document.getElementById('paginationInfo');
            var controlsEl = document.getElementById('paginationControls');
            if (!wrap || !infoEl || !controlsEl) return;

            if (totalItems <= MODULES_PER_PAGE) {
                wrap.style.display = 'none';
                return;
            }
            wrap.style.display = 'flex';

            var shownStart = totalItems === 0 ? 0 : start + 1;
            var shownEnd = Math.min(end, totalItems);
            infoEl.textContent = 'Showing ' + shownEnd + ' of ' + totalItems + ' modules';

            var html = '';
            html += '<button type="button" class="page-nav-btn" onclick="goToModulePage(' + (currentModulePage - 1) + ')"' +
                (currentModulePage === 1 ? ' disabled' : '') + '><i class="fa fa-chevron-left"></i></button>';

            for (var p = 1; p <= totalPages; p++) {
                html += '<button type="button" class="page-num-btn' + (p === currentModulePage ? ' active' : '') +
                    '" onclick="goToModulePage(' + p + ')">' + p + '</button>';
            }

            html += '<button type="button" class="page-nav-btn" onclick="goToModulePage(' + (currentModulePage + 1) + ')"' +
                (currentModulePage === totalPages ? ' disabled' : '') + '><i class="fa fa-chevron-right"></i></button>';

            controlsEl.innerHTML = html;
        }

        function goToModulePage(page) {
            currentModulePage = page;
            paginateModules();

            var wrapEl = document.querySelector('.learning-module-wrap');
            if (wrapEl) wrapEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var saved = 'list';
            try {
                saved = localStorage.getItem('modulesViewPreference') || 'list';
            } catch (e) { /* ignore storage errors */ }
            setModuleView(saved);

            paginateModules();
        });
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <!-- ── CREATE MODULE MODAL ── -->
    <div class="modal fade" id="createModuleModal" tabindex="-1" aria-labelledby="createModuleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:16px; border:none;">

                <div class="modal-header" style="border-bottom:1px solid var(--border);">
                    <h5 class="modal-title" id="createModuleModalLabel">
                        Create Module —
                        <?= htmlspecialchars($subjectInfo['subject_name'] ?? '') ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="createModuleForm" action="/learning_management/public/?url=save_module" method="POST"
                        enctype="multipart/form-data">

                        <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subjectId ?? '') ?>">

                        <div class="card-parent-box">
                            <div class="card-header" style="margin-bottom:0;">
                                <h3>Create Module</h3>
                                <div class="buttons">
                                    <button type="button" id="addModuleBtn">
                                        <i class="fa fa-plus"></i> Add Module
                                    </button>
                                </div>
                            </div>

                            <div id="contentContainer" style="padding:1.5rem 0 0 0;">
                                <div class="text-content" id="contentEmpty" style="display:flex;">
                                    <i class="fa fa-inbox"></i>
                                    <p>No modules yet — click "Add Module" to start.</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer" style="border-top:1px solid var(--border); padding: 16px 22px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="createModuleForm" class="btn" id="saveModuleBtn" disabled
                        style="background-color: var(--neon-cyan); color:#fff;">
                        Save Module
                    </button>
                </div>

            </div>
        </div>
    </div>

    <?php if (isset($_GET['saved']) && $_GET['saved'] === '1'): ?>
        <div class="toast-success" id="moduleSavedToast">
            <div class="toast-icon"><i class="fa fa-check"></i></div>
            <div class="toast-text">
                <p class="toast-title">Module created successfully</p>
                <p class="toast-desc">Your module has been added to this subject.</p>
            </div>
            <button type="button" class="toast-close" onclick="dismissToast()"><i class="fa fa-times"></i></button>
        </div>
    <?php endif; ?>

    <script src="../teacher_folder/create_module_folder/create_module.js"></script>
</body>

</html>