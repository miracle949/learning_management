<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/teacher_lessons.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        /* ── Image upload box ── */
        .image-upload-area {
            width: 100%;
        }

        .image-upload-box {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: #fafafa;
            text-align: center;
        }

        .image-upload-box:hover {
            border-color: var(--green, #00C950);
            background: #f0fff4;
        }

        .image-upload-box i {
            font-size: 32px;
            color: #aaa;
        }

        .image-upload-box p {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #555;
        }

        .image-upload-box span {
            font-size: 12px;
            color: #aaa;
        }

        /* ── Image preview ── */
        .image-preview {
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
        }

        .preview-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .remove-preview-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background .18s;
        }

        .remove-preview-btn:hover {
            background: #fca5a5;
        }

        /* ── Classes Feed section ── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 2rem 0 1rem;
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
            border-color: var(--green, #00C950);
            color: var(--green, #00C950);
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <nav>
            <div class="nav-logo">
                <a
                    href="/learning_management/public/?url=teacher_class&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
            <div class="nav-menu">
                <button><i class="fa fa-bars"></i></button>
            </div>
        </nav>

        <main>
            <div class="card-parent-box">

                <form action="/learning_management/public/?url=save_lessons" method="POST"
                    enctype="multipart/form-data">

                    <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject_id ?? '') ?>">
                    <input type="hidden" name="grade_level_id" value="<?= htmlspecialchars($grade_level_id ?? '') ?>">

                    <!-- ══ SECTION 1 — CLASSES FEED MODULES ══ -->
                    <div class="card-header" style="margin-bottom:0;">
                        <h3>Classes Feed</h3>
                        <div class="buttons">
                            <button type="button" id="addCFModuleBtn">
                                <i class="fa fa-plus"></i> Add Module
                            </button>
                        </div>
                    </div>

                    <div id="cfModuleContainer" style="padding: 1rem 1.5rem;">
                        <div class="text-content" id="cfEmpty">
                            <i class="fa fa-inbox"></i>
                            <p>No classes feed modules yet — click "Add Module" to start.</p>
                        </div>
                    </div>

                    <!-- ══ SECTION 2 — INTERACTIVE MODULES ══ -->
                    <div class="section-divider" style="padding: 0 1.5rem;">
                        <h4><i class="fa fa-layer-group"
                                style="color:var(--green,#00C950);margin-right:6px;"></i>Interactive Modules</h4>
                    </div>

                    <div class="card-header" style="margin-bottom:0;">
                        <h3>Content</h3>
                        <div class="buttons">
                            <button type="button" id="addModuleBtn">
                                <i class="fa fa-file"></i> Modules
                            </button>
                        </div>
                    </div>

                    <div id="contentContainer">
                        <div class="text-content">
                            <i class="fa fa-circle-question"></i>
                            <p>No Content</p>
                        </div>
                    </div>

                    <div class="card-submit">
                        <button type="button">Cancel</button>
                        <button type="submit">Create Module</button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <!-- ══════════════════════════════════════════════════════
         DUPLICATE WARNING MODAL — Bootstrap only
         Shows when modules or lessons already exist in DB
         Reads from $_SESSION['save_skipped'] set by controller
         ══════════════════════════════════════════════════════ -->
    <?php if (!empty($_SESSION['save_skipped'])): ?>
        <?php
        $skipped = $_SESSION['save_skipped'];
        $cfMods = $skipped['cf_modules'] ?? [];   // modules table duplicates
        $imMods = $skipped['im_modules'] ?? [];   // interactive_modules table duplicates
        $lessons = $skipped['lessons'] ?? [];   // lessons table duplicates
        unset($_SESSION['save_skipped']);           // clear — show only once
        ?>

        <div class="modal fade" id="skippedModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-warning-subtle border-warning-subtle">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="fa fa-triangle-exclamation text-warning"></i>
                            Some items already existed
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            The items below were <strong>not re-inserted</strong> because they already
                            exist in the database. Any new content inside them was still saved normally.
                        </p>

                        <?php if (!empty($cfMods)): ?>
                            <p class="fw-semibold small mb-1">
                                <i class="fa fa-inbox text-warning me-1"></i>
                                Classes Feed Modules
                                <span class="badge bg-warning text-dark ms-1"><?= count($cfMods) ?> skipped</span>
                            </p>
                            <ul class="small text-secondary mb-3 ps-3">
                                <?php foreach ($cfMods as $name): ?>
                                    <li><?= htmlspecialchars($name) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($imMods)): ?>
                            <p class="fw-semibold small mb-1">
                                <i class="fa fa-layer-group text-warning me-1"></i>
                                Interactive Modules
                                <span class="badge bg-warning text-dark ms-1"><?= count($imMods) ?> skipped</span>
                            </p>
                            <ul class="small text-secondary mb-3 ps-3">
                                <?php foreach ($imMods as $name): ?>
                                    <li><?= htmlspecialchars($name) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($lessons)): ?>
                            <p class="fw-semibold small mb-1">
                                <i class="fa fa-file text-warning me-1"></i>
                                Lessons
                                <span class="badge bg-warning text-dark ms-1"><?= count($lessons) ?> skipped</span>
                            </p>
                            <ul class="small text-secondary mb-0 ps-3">
                                <?php foreach ($lessons as $name): ?>
                                    <li><?= htmlspecialchars($name) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            Got it
                        </button>
                    </div>

                </div>
            </div>
        </div>

    <?php endif; ?>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <?php if (isset($skipped)): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                new bootstrap.Modal(document.getElementById("skippedModal")).show();
            });
        </script>
    <?php endif; ?>

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
                        <div class="card-icon" style="width:36px;height:36px;border-radius:50%;background:var(--green-light,#e6f9ee);display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-inbox" style="color:var(--green,#00C950);font-size:15px;"></i>
                        </div>
                        <strong class="cf-module-num">Module ${idx + 1}</strong>
                    </div>
                    <button type="button" class="cf-remove-btn remove-item btn btn-sm">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <label>Module Title *</label>
                        <input type="text" name="cf_module_title[]"
                               class="form-control mt-2" placeholder="e.g. Module 1: Week 1-2">
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Description</label>
                        <textarea name="cf_module_description[]"
                                  class="form-control mt-2" rows="3"
                                  placeholder="Brief description of this module"></textarea>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Attach PDF / Materials</label>
                        <div class="cf-pdf-list"></div>
                        <button type="button" class="btn-cf-add-pdf mt-2">
                            <i class="fa fa-plus"></i> Add File
                        </button>
                        <input type="file"
                               name="cf_module_pdf[${idx}][]"
                               class="cf-file-input"
                               accept=".pdf,.ppt,.pptx,.doc,.docx"
                               multiple
                               style="display:none;">
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
                        item.innerHTML = `
                        <i class="fa fa-file-pdf"></i>
                        <span>${file.name}</span>
                        <button type="button" class="cf-pdf-remove"><i class="fa fa-times"></i></button>`;
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

    <!-- Interactive Modules JS -->
    <script src="../teacher_folder/lesson_folder/lesson.js"></script>

</body>

</html>