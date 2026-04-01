<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($classInfo['subject_name'] ?? 'Content') ?></title>
    <link rel="stylesheet" href="../css_folder/teacher_lessons.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ── HERO BANNER ── */
        .hero-banner {
            width: 100%;
            height: 180px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .30);
        }

        /* ── HERO INFO ── */
        .hero-info {
            background: #fff;
            padding: 16px 20px 0;
            border-bottom: 1px solid #e4e7eb;
        }

        .hero-info h2 {
            font-size: 18px;
            font-weight: 800;
            color: #00a84a;
            margin-bottom: 4px;
        }

        .hero-meta {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 14px;
        }

        /* ── TABS ── */
        .tabs {
            display: flex;
            gap: 0;
            padding: 0;
            background: #fff;
            margin-top: .5rem;
            border-bottom: 2px solid #e4e7eb;
        }

        .tab-btn {
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: color .15s, border-color .15s;
        }

        .tab-btn.active {
            color: #009e3e;
            border-bottom-color: #00C950;
        }

        .tab-btn:hover:not(.active) {
            color: #111;
        }

        /* ── MAIN ── */
        main {
            padding: 2rem 2rem;
        }

        .tab-pane {
            display: none;
            animation: fadeIn .18s ease;
        }

        .tab-pane.active {
            display: block;
            animation: fadeIn .18s ease;
        }

        /* ── Module type selector bar ── */
        .module-type-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.75rem;
        }

        .module-type-bar label {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            white-space: nowrap;
        }

        .module-type-select {
            appearance: none;
            -webkit-appearance: none;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 14px center;
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            padding: 10px 40px 10px 14px;
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            cursor: pointer;
            min-width: 230px;
            transition: border-color .15s, box-shadow .15s;
        }

        .module-type-select:focus {
            outline: none;
            border-color: #00C950;
            box-shadow: 0 0 0 3px rgba(0, 201, 80, .15);
        }

        /* ── Stream panels ── */
        .stream-panel {
            display: none;
            animation: fadeIn .18s ease;
        }

        .stream-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Section divider ── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1rem 0 1rem;
        }

        .section-divider h4 {
            white-space: nowrap;
            font-size: 15px;
            font-weight: 700;
            color: #374151;
            margin: 0;
        }

        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* ── CF Module card ── */
        .cf-module-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.25rem;
            background: #fff;
            margin-bottom: 1rem;
        }

        .cf-pdf-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 14px;
            margin-top: 8px;
        }

        .cf-pdf-item i {
            color: #ef4444;
            font-size: 18px;
        }

        .cf-pdf-item span {
            flex: 1;
            font-size: 13px;
            color: #374151;
            word-break: break-all;
        }

        .cf-pdf-remove {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 14px;
            padding: 2px 6px;
            border-radius: 4px;
            transition: color .15s;
        }

        .cf-pdf-remove:hover {
            color: #ef4444;
        }

        .cf-pdf-list {
            margin-top: 8px;
        }

        .btn-cf-add-pdf {
            background: none;
            border: 1px dashed #d1d5db;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            transition: border-color .15s, color .15s;
        }

        .btn-cf-add-pdf:hover {
            border-color: #00C950;
            color: #00C950;
        }

        main h3 {
            font-size: 20px;
            font-weight: 600;
        }

        /* ── Classwork form ── */
        .cw-field {
            margin-bottom: 1.25rem;
            
        }

        .cw-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
        }

        .cw-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #111827;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .cw-input:focus {
            border-color: #00C950;
            box-shadow: 0 0 0 3px rgba(0, 201, 80, .12);
        }

        .cw-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .cw-row {
            display: flex;
            gap: 16px;
            margin-bottom: 1.25rem;
        }

        .cw-select-wrap {
            position: relative;
        }

        .cw-select {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 36px 10px 14px;
            font-size: 14px;
            font-weight: 600;
            color: #111827;
            background: #fff;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            outline: none;
            transition: border-color .15s;
        }

        .cw-select:focus {
            border-color: #00C950;
            box-shadow: 0 0 0 3px rgba(0, 201, 80, .12);
        }

        .cw-select-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: #6b7280;
            pointer-events: none;
        }

        .cw-date {
            cursor: pointer;
        }

        .cw-upload-box {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            background: #fafafa;
            transition: border-color .2s, background .2s;
        }

        .cw-upload-box:hover {
            border-color: #00C950;
            background: #f0fff4;
        }

        .cw-upload-box i {
            font-size: 26px;
            color: var(--green);
            display: block;
            margin-bottom: 6px;
        }

        .cw-upload-box p {
            font-size: 14px;
            font-weight: 700;
            color: #374151;
            margin: 0 0 4px;
        }

        .cw-upload-box span {
            font-size: 12px;
            color: #9ca3af;
        }

        .cw-choose-btn {
            display: inline-block;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: background .15s;
        }

        .cw-choose-btn:hover {
            background: #e5e7eb;
        }

        /* ── Announcement card accent ── */
        .ann-card-accent {
            /* border-left: 4px solid #f59e0b; */
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            /* background: #fffbeb; */
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("sidebar.php"); ?>
        <div class="rightbar">
            <?php include("nav.php"); ?>

            <?php
            function getBannerBg(string $subject): string
            {
                $n = strtolower($subject);
                if (str_contains($n, 'phil'))
                    return "url('../images/philosophy_picture.jpg')";
                if (str_contains($n, 'ucsp') || str_contains($n, 'cultur'))
                    return "url('../images/ucsp_picture.jpg')";
                if (str_contains($n, 'comput') || str_contains($n, 'css'))
                    return "url('../images/computer_picture.jpg')";
                if (str_contains($n, 'physical') || $n === 'pe')
                    return "url('../images/pe_picture.jpg')";
                if (str_contains($n, 'inquir') || str_contains($n, '3i'))
                    return "url('../images/3i_picture.jpg')";
                if (str_contains($n, 'entrep'))
                    return "url('../images/entrep_picture.jpg')";
                if (str_contains($n, 'work') || str_contains($n, 'immersion'))
                    return "url('../images/work_picture.jpg')";
                if (str_contains($n, 'media') || str_contains($n, 'information'))
                    return "url('../images/media_picture.jpg')";
                return "url('../images/philosophy_picture.jpg')";
            }
            $bannerBg = getBannerBg($classInfo['subject_name'] ?? '');
            ?>

            <!-- HERO BANNER -->
            <div class="hero-banner" style="background-image:<?= $bannerBg ?>;"></div>

            <!-- HERO INFO -->
            <div class="hero-info">
                <h2><?= htmlspecialchars($classInfo['subject_name'] ?? '') ?></h2>
                <p class="hero-meta"><?= htmlspecialchars($classInfo['section'] ?? '') ?></p>

                <!-- TABS -->
                <div class="tabs">
                    <button class="tab-btn active" data-tab="stream">Stream</button>
                    <button class="tab-btn" data-tab="classwork">Classwork</button>
                </div>
            </div>

            <main>

                <!-- ════════════ STREAM TAB ════════════ -->
                <div class="tab-pane active" id="tab-stream">

                    <!-- Dropdown -->
                    <div class="module-type-bar">
                        <!-- <label><i class="fa fa-layer-group me-1" style="color:#00C950;"></i> Module Type :</label> -->
                        <select class="module-type-select" id="moduleTypeSelect"
                            onchange="switchModulePanel(this.value)">
                            <option value="classes-feed" selected> Modules</option>
                            <option value="interactive">Interactive Module</option>
                            <option value="announcement"> Announcements</option>
                        </select>
                    </div>

                    <!-- ── Panel: Classes Feed ── -->
                    <div class="stream-panel active" id="panel-classes-feed">
                        <form action="/learning_management/public/?url=save_lessons" method="POST"
                            enctype="multipart/form-data">

                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id ?? '') ?>">
                            <input type="hidden" name="grade_level_id"
                                value="<?= htmlspecialchars($grade_level_id ?? '') ?>">
                            <input type="hidden" name="section_id" value="<?= htmlspecialchars($section_id ?? 0) ?>">
                            <input type="hidden" name="save_type" value="classes_feed">

                            <div class="card-parent-box">
                                <div class="card-header" style="margin-bottom:0;">
                                    <h3>Modules</h3>
                                    <div class="buttons">
                                        <button type="button" id="addCFModuleBtn">
                                            <i class="fa fa-plus"></i> Add Module
                                        </button>
                                    </div>
                                </div>

                                <div id="cfModuleContainer" style="padding:1.5rem 0 0 0;">
                                    <div class="text-content" id="cfEmpty" style="display:flex;">
                                        <i class="fa fa-inbox"></i>
                                        <p>No modules yet — click "Add Module" to start.</p>
                                    </div>
                                </div>

                                <div class="card-submit">
                                    <button type="button" onclick="history.back()">Cancel</button>
                                    <button type="submit">Save to Stream</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /panel-classes-feed -->

                    <!-- ── Panel: Interactive Module ── -->
                    <div class="stream-panel" id="panel-interactive">
                        <form action="/learning_management/public/?url=save_lessons" method="POST"
                            enctype="multipart/form-data">

                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id ?? '') ?>">
                            <input type="hidden" name="grade_level_id"
                                value="<?= htmlspecialchars($grade_level_id ?? '') ?>">
                            <input type="hidden" name="section_id" value="<?= htmlspecialchars($section_id ?? 0) ?>">
                            <input type="hidden" name="save_type" value="interactive_module">

                            <div class="card-parent-box">
                                <div class="card-header" style="margin-bottom:0;">
                                    <h3>Content</h3>
                                    <div class="buttons">
                                        <button type="button" id="addModuleBtn">
                                            <i class="fa fa-file"></i>
                                            Add Modules
                                        </button>
                                    </div>
                                </div>

                                <div id="contentContainer" style="padding:1.5rem 0 0 0;">
                                    <div class="text-content">
                                        <i class="fa fa-circle-question"></i>
                                        <p>No Content</p>
                                    </div>
                                </div>

                                <div class="card-submit">
                                    <button type="button" onclick="history.back()">Cancel</button>
                                    <button type="submit">Create Module</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /panel-interactive -->

                    <!-- ── Panel: Announcements ── -->
                    <div class="stream-panel" id="panel-announcement">
                        <form action="/learning_management/public/?url=save_announcement" method="POST"
                            enctype="multipart/form-data">

                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id ?? '') ?>">
                            <input type="hidden" name="grade_level_id"
                                value="<?= htmlspecialchars($grade_level_id ?? '') ?>">
                            <input type="hidden" name="section_id" value="<?= htmlspecialchars($section_id ?? 0) ?>">
                            <input type="hidden" name="save_type" value="announcement">

                            <div class="card-parent-box">
                                <div class="card-header" style="margin-bottom:0;">
                                    <h3>Announcements</h3>
                                    <div class="buttons">
                                        <button type="button" id="addAnnouncementBtn">
                                            <i class="fa fa-plus"></i> Add Announcement
                                        </button>
                                    </div>
                                </div>

                                <div id="announcementContainer" style="padding:1.5rem 0 0 0;">
                                    <div class="text-content" id="announcementEmpty" style="display:flex;">
                                        <i class="fa fa-bullhorn"></i>
                                        <p>No announcements yet — click "Add Announcement" to start.</p>
                                    </div>
                                </div>

                                <div class="card-submit">
                                    <button type="button" onclick="history.back()">Cancel</button>
                                    <button type="submit">Post Announcements</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /panel-announcement -->

                </div><!-- /tab-stream -->


                <!-- ════════════ CLASSWORK TAB ════════════ -->
                <div class="tab-pane" id="tab-classwork">
                    <div class="card-parent-box">

                        <form action="/learning_management/public/?url=save_assignment" method="POST"
                            enctype="multipart/form-data">

                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id ?? '') ?>">
                            <input type="hidden" name="grade_level_id"
                                value="<?= htmlspecialchars($grade_level_id ?? '') ?>">
                            <input type="hidden" name="section_id" value="<?= htmlspecialchars($section_id ?? 0) ?>">

                            <div class="card-header" style="margin-bottom:0;">
                                <h3>Classwork</h3>
                                <div class="buttons">
                                    <button type="button" id="addAssignmentBtn">
                                        <i class="fa fa-plus"></i> Add Assignment
                                    </button>
                                </div>
                            </div>

                            <div id="cwAssignmentContainer" style="padding:1.5rem 0 0 0;">
                                <div class="text-content" id="cwAssignmentEmpty" style="display:flex;">
                                    <i class="fa fa-clipboard-check"></i>
                                    <p>No assignments yet — click "Add Assignment" to start.</p>
                                </div>
                            </div>

                            <div class="card-submit">
                                <button type="button" onclick="history.back()">Cancel</button>
                                <button type="submit">Create</button>
                            </div>

                        </form>
                    </div>
                </div>

            </main>
        </div><!-- /rightbar -->
    </div>

    <!-- DUPLICATE WARNING MODAL -->
    <?php if (!empty($_SESSION['save_skipped'])): ?>
        <?php
        $skipped = $_SESSION['save_skipped'];
        $cfMods = $skipped['cf_modules'] ?? [];
        $imMods = $skipped['im_modules'] ?? [];
        $lessons = $skipped['lessons'] ?? [];
        unset($_SESSION['save_skipped']);
        ?>
        <div class="modal fade" id="skippedModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning-subtle border-warning-subtle">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="fa fa-triangle-exclamation text-warning"></i> Some items already existed
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-3">These items were <strong>not re-inserted</strong> because they
                            already exist.</p>
                        <?php if (!empty($cfMods)): ?>
                            <p class="fw-semibold small mb-1"><i class="fa fa-inbox text-warning me-1"></i> Modules <span
                                    class="badge bg-warning text-dark ms-1"><?= count($cfMods) ?> skipped</span></p>
                            <ul class="small text-secondary mb-3 ps-3"><?php foreach ($cfMods as $n): ?>
                                    <li><?= htmlspecialchars($n) ?></li><?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if (!empty($imMods)): ?>
                            <p class="fw-semibold small mb-1"><i class="fa fa-layer-group text-warning me-1"></i> Interactive
                                Modules <span class="badge bg-warning text-dark ms-1"><?= count($imMods) ?> skipped</span></p>
                            <ul class="small text-secondary mb-3 ps-3"><?php foreach ($imMods as $n): ?>
                                    <li><?= htmlspecialchars($n) ?></li><?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <?php if (!empty($lessons)): ?>
                            <p class="fw-semibold small mb-1"><i class="fa fa-file text-warning me-1"></i> Lessons <span
                                    class="badge bg-warning text-dark ms-1"><?= count($lessons) ?> skipped</span></p>
                            <ul class="small text-secondary mb-0 ps-3"><?php foreach ($lessons as $n): ?>
                                    <li><?= htmlspecialchars($n) ?></li><?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Got it</button>
                    </div>
                </div>
            </div>
        </div>
        <script>document.addEventListener("DOMContentLoaded", () => new bootstrap.Modal(document.getElementById("skippedModal")).show());</script>
    <?php endif; ?>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ── Tab switching ── */
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
            });
        });

        /* ── Stream panel switcher ── */
        function switchModulePanel(val) {
            document.querySelectorAll('.stream-panel').forEach(p => p.classList.remove('active'));
            if (val === 'classes-feed') document.getElementById('panel-classes-feed').classList.add('active');
            if (val === 'interactive') document.getElementById('panel-interactive').classList.add('active');
            if (val === 'announcement') document.getElementById('panel-announcement').classList.add('active');
        }
    </script>

    <!-- Classes Feed Module JS -->
    <script>
        (function () {
            const container = document.getElementById("cfModuleContainer");
            const addBtn = document.getElementById("addCFModuleBtn");
            const emptyState = document.getElementById("cfEmpty");

            function reNumberCF() {
                container.querySelectorAll(".cf-module-card").forEach((card, i) => {
                    card.querySelector(".cf-module-num").textContent = "Module " + (i + 1);
                });
                emptyState.style.display =
                    container.querySelectorAll(".cf-module-card").length === 0 ? "flex" : "none";
            }

            addBtn.addEventListener("click", () => {
                emptyState.style.display = "none";
                const idx = container.querySelectorAll(".cf-module-card").length;
                const card = document.createElement("div");
                card.className = "cf-module-card";
                card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:#e6f9ee;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-inbox" style="color:#00C950;font-size:15px;"></i>
                        </div>
                        <strong class="cf-module-num">Module ${idx + 1}</strong>
                    </div>
                    <button type="button" class="cf-remove-btn btn btn-sm"><i class="fa fa-times"></i></button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <label>Module Title *</label>
                        <input type="text" name="cf_module_title[]" class="form-control mt-2" placeholder="e.g. Module 1: Week 1-2">
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Description</label>
                        <textarea name="cf_module_description[]" class="form-control mt-2" rows="3" placeholder="Brief description of this module"></textarea>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Attach PDF / Materials</label>
                        <div class="cf-pdf-list"></div>
                        <button type="button" class="btn-cf-add-pdf mt-2"><i class="fa fa-plus"></i> Add File</button>
                        <input type="file" name="cf_module_pdf[${idx}][]" class="cf-file-input" accept=".pdf,.ppt,.pptx,.doc,.docx" multiple style="display:none;">
                    </div>
                </div>`;

                container.appendChild(card);

                const addFileBtn = card.querySelector(".btn-cf-add-pdf");
                const fileInput = card.querySelector(".cf-file-input");
                const pdfList = card.querySelector(".cf-pdf-list");

                addFileBtn.addEventListener("click", () => fileInput.click());
                fileInput.addEventListener("change", () => {
                    Array.from(fileInput.files).forEach(file => {
                        const item = document.createElement("div");
                        item.className = "cf-pdf-item";
                        item.innerHTML = `<i class="fa fa-file-pdf"></i><span>${file.name}</span><button type="button" class="cf-pdf-remove"><i class="fa fa-times"></i></button>`;
                        item.querySelector(".cf-pdf-remove").addEventListener("click", () => item.remove());
                        pdfList.appendChild(item);
                    });
                });

                card.querySelector(".cf-remove-btn").addEventListener("click", () => {
                    card.remove();
                    reNumberCF();
                });
            });
        })();
    </script>

    <!-- Interactive Modules + Assignment + Announcement JS -->
    <script src="../teacher_folder/lesson_folder/lesson.js"></script>

</body>

</html>