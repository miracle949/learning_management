<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/subjects.css">
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

                    <style>
                        /* ── BANNER ── */
                        .container-fluid .rightbar .module-title {
                            width: 100%;
                            border-radius: 10px;
                            border: 1px solid #E2E8E5;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
                            overflow: hidden;
                            margin-bottom: 1.5rem;
                        }

                        .container-fluid .rightbar .module-title .module-picture {
                            background-color: var(--green-dark);
                            background-size: cover;
                            background-position: center;
                            width: 100%;
                            height: 180px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
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
                            padding: 1.5rem 2rem;
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            gap: 2rem;
                        }

                        .container-fluid .rightbar .module-title .module-body h1 {
                            font-size: 20px;
                            text-transform: uppercase;
                            font-family: "Titan", sans-serif;
                            color: var(--green-dark);
                            margin: 0 0 .4rem;
                        }

                        .container-fluid .rightbar .module-title .module-body p {
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
                            margin-top: 2rem;
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

                        .start-now-btn {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            gap: 7px;
                            background-color: var(--green);
                            color: #ffffff;
                            padding: 9px 20px;
                            width: 100%;
                            border-radius: 28px;
                            font-size: 14px;
                            font-weight: 600;
                            text-decoration: none;
                            transition: opacity .18s;
                            border: none;
                            cursor: pointer;
                        }

                        .start-now-btn:hover {
                            opacity: .88;
                            color: #fff;
                        }

                        .start-now-btn.btn-continue {
                            background-color: var(--green-dark, #065f46);
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

                    <!-- ── BANNER — fully dynamic from DB ── -->
                    <div class="module-title">
                        <div class="module-picture">
                            <h2><?= htmlspecialchars($subjectInfo['subject_name']) ?></h2>
                        </div>
                        <div class="module-body">
                            <div>
                                <h1><?= htmlspecialchars($subjectInfo['subject_name']) ?></h1>
                                <p><?= htmlspecialchars($subjectInfo['subject_description'] ?? 'No description available.') ?>
                                </p>
                            </div>
                            <a href="/learning_management/public/?url=module_all" class="module-browse-btn">
                                Browse <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- ── MAIN LAYOUT ── -->
                    <div class="modules-main">

                        <!-- Learning Catalog (left sidebar) -->
                        <div class="learning-catalog">
                            <h4>Learning Catalog</h4>
                            <p class="catalog-section-title">Modules</p>
                            <ul class="catalog-list">
                                <?php foreach ($modules as $i => $mod): ?>
                                    <li class="<?= $i >= 5 ? 'catalog-extra' : '' ?>" style="<?= $i >= 5 ? 'display:none;' : '' ?>">
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
                                    <p>No interactive modules available yet.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($modules as $mod):
                                    $detailUrl = "/learning_management/public/?url=subject_lessons&subject=" . urlencode($subject) . "&id={$mod['id']}";
                                    $count = $lessonCounts[$mod['id']] ?? 0;
                                    $isStarted = in_array($mod['id'], $startedModuleIds);
                                    $btnText = $isStarted ? 'Continue Learning' : 'Start now';
                                    $btnClass = $isStarted ? 'start-now-btn btn-continue' : 'start-now-btn';
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
                                            <button class="<?= $btnClass ?>" data-module-id="<?= (int) $mod['id'] ?>"
                                                data-href="<?= htmlspecialchars($detailUrl) ?>" onclick="handleModuleStart(this)">
                                                <?= $btnText ?> <i class="fa fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
        function toggleCatalog(btn) {
            var extras = document.querySelectorAll('.catalog-extra');
            var hidden = extras.length && extras[0].style.display === 'none';
            extras.forEach(function (el) { el.style.display = hidden ? 'block' : 'none'; });
            btn.innerHTML = hidden
                ? 'See less <i class="fa fa-chevron-up"></i>'
                : 'See more <i class="fa fa-chevron-down"></i>';
        }

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
                .then(function () {
                    var modal = new bootstrap.Modal(document.getElementById('startSuccessModal'));
                    modal.show();
                    setTimeout(function () {
                        modal.hide();
                        window.location.href = href;
                    }, 1800);
                })
                .catch(function () {
                    window.location.href = href;
                });
        }
    </script>

    <!-- ── SUCCESS MODAL ── -->
    <div class="modal fade" id="startSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <div class="modal-body text-center" style="padding:2rem 1.5rem;">
                    <div
                        style="width:60px;height:60px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fa fa-check" style="color:var(--green);font-size:26px;"></i>
                    </div>
                    <h5 style="font-family:'Titan',sans-serif;color:var(--green-dark);margin:0 0 .4rem;font-size:17px;">
                        Start learning successfully!
                    </h5>
                    <p style="font-size:13.5px;color:#555;margin:0;">
                        Starting your learning journey now...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>