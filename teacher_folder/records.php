<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link rel="stylesheet" href="../css_folder/records.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <nav>
            <div class="nav-logo">
                <a href="/learning_management/public/?url=teacher"><i class="fa fa-arrow-left"></i></a>
            </div>
            <div class="nav-menu">
                <button><i class="fa fa-bars"></i></button>
            </div>
        </nav>

        <main>

            <h3><?= htmlspecialchars($classInfo['subject_name'] ?? 'Unknown Subject') ?></h3>
            <div class="main-text">
                <p>Computer System Servicing</p>
                <span>•</span>
                <span><?= htmlspecialchars($classInfo['grade'] ?? '') ?></span>
                <span>-</span>
                <span><?= htmlspecialchars($classInfo['section'] ?? '') ?></span>
            </div>

            <!-- ── Stats cards ── -->
            <div class="card-box-parent">
                <div class="card-box">
                    <div class="card-text">
                        <span>Students</span>
                        <p><?= $studentCount ?? 0 ?></p>
                    </div>
                    <div class="card-icon"><i class="fa fa-users"></i></div>
                </div>
                <div class="card-box">
                    <div class="card-text">
                        <span>Modules</span>
                        <p><?= count($cfModules ?? []) + count($imModules ?? []) ?></p>
                    </div>
                    <div class="card-icon"><i class="fa fa-graduation-cap"></i></div>
                </div>
                <div class="card-box">
                    <div class="card-text">
                        <span>Lessons</span>
                        <p><?= $totalLessons ?? 0 ?></p>
                    </div>
                    <div class="card-icon"><i class="fa fa-file"></i></div>
                </div>
            </div>

            <!-- ── Upload / Announce buttons ── -->
            <div class="progress-announce">
                <a href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>">
                    <div class="student-progress">
                        <div class="student-icon"><i class="fa fa-arrow-up-from-bracket"></i></div>
                        <div class="student-text">
                            <p>Upload</p>
                            <span>Add lessons, videos, images</span>
                        </div>
                    </div>
                </a>
                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <div class="announcement">
                        <div class="announce-icon"><i class="fa fa-bullhorn"></i></div>
                        <div class="announce-text">
                            <p>Announcement</p>
                            <span>Post updates for students</span>
                        </div>
                    </div>
                </button>
            </div>

            <!-- Modal announcement -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">New Announcement</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="" method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <label>Title</label>
                                        <input type="text" name="" class="form-control mt-1" placeholder="Enter announcement title...">
                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <label>Content</label>
                                        <textarea name="" class="form-control mt-1" rows="6" placeholder="Enter content..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Post Announcement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ══════════════════════════════════════════
                 ONE accordion for ALL Classes Feed Modules
                 Click header → reveals all modules inside
                 ══════════════════════════════════════════ -->
            <div class="mt-4 px-2">
                <div class="accordion" id="mainAccordion">

                    <!-- ── CLASSES FEED MODULES — single accordion item ── -->
                    <div class="accordion-item border rounded-3 mb-3" style="overflow:hidden;">
                        <h2 class="accordion-header" id="cfHeading">
                            <button class="accordion-button collapsed py-3" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#cfCollapse"
                                    aria-expanded="false"
                                    aria-controls="cfCollapse">
                                <div class="d-flex align-items-center gap-3 w-100 me-3">
                                    <div style="width:36px;height:36px;border-radius:50%;background:#e6f9ee;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fa fa-inbox text-success" style="font-size:15px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-bold" style="font-size:15px;">Classes Modules</p>
                                        <small class="text-muted fw-normal">PDF, PPT and document materials</small>
                                    </div>
                                    <span class="badge bg-success me-2"><?= count($cfModules ?? []) ?> module<?= count($cfModules ?? []) != 1 ? 's' : '' ?></span>
                                </div>
                            </button>
                        </h2>
                        <div id="cfCollapse" class="accordion-collapse collapse"
                             aria-labelledby="cfHeading"
                             data-bs-parent="#mainAccordion">
                            <div class="accordion-body p-0">
                                <?php if (empty($cfModules)): ?>
                                <div class="text-center py-4 text-muted small">
                                    <i class="fa fa-inbox mb-2 d-block" style="font-size:24px;opacity:.4;"></i>
                                    No classes feed modules yet.
                                </div>
                                <?php else: ?>
                                <div class="d-flex flex-column">
                                    <?php foreach ($cfModules as $cfIdx => $mod): ?>
                                    <div class="border-top px-3 py-3">
                                        <!-- Module header row -->
                                        <div class="d-flex align-items-start gap-3">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#e6f9ee;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                                                <span style="font-size:12px;font-weight:700;color:#16a34a;"><?= $cfIdx + 1 ?></span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <p class="mb-0 fw-semibold" style="font-size:14px;"><?= htmlspecialchars($mod['title']) ?></p>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fa fa-file-pdf text-danger me-1"></i>
                                                            <?= $mod['material_count'] ?> file<?= $mod['material_count'] != 1 ? 's' : '' ?>
                                                        </span>
                                                        <small class="text-muted"><?= date('M d, Y', strtotime($mod['posted_at'])) ?></small>
                                                    </div>
                                                </div>
                                                <?php if (!empty($mod['description'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars($mod['description']) ?></small>
                                                <?php endif; ?>

                                                <!-- Files list -->
                                                <?php if (!empty($mod['materials'])): ?>
                                                <div class="d-flex flex-column gap-1 mt-2">
                                                    <?php foreach ($mod['materials'] as $mat): ?>
                                                    <?php
                                                        $ext = strtolower($mat['file_type'] ?? '');
                                                        $iconClass = in_array($ext, ['ppt','pptx']) ? 'fa-file-powerpoint text-warning' :
                                                                     (in_array($ext, ['doc','docx']) ? 'fa-file-word text-primary' : 'fa-file-pdf text-danger');
                                                    ?>
                                                    <div class="d-flex align-items-center gap-2 p-2 rounded-2 bg-light border">
                                                        <i class="fa <?= $iconClass ?>" style="font-size:16px;"></i>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-0 small fw-semibold"><?= htmlspecialchars($mat['file_name']) ?></p>
                                                            <small class="text-muted"><?= strtoupper($mat['file_type']) ?> &bull; <?= round($mat['file_size'] / 1024, 1) ?> KB</small>
                                                        </div>
                                                        <a href="<?= htmlspecialchars($mat['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ── INTERACTIVE MODULES — single accordion item ── -->
                    <div class="accordion-item border rounded-3 mb-3" style="overflow:hidden;">
                        <h2 class="accordion-header" id="imHeading">
                            <button class="accordion-button collapsed py-3" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#imCollapse"
                                    aria-expanded="false"
                                    aria-controls="imCollapse">
                                <div class="d-flex align-items-center gap-3 w-100 me-3">
                                    <div style="width:36px;height:36px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fa fa-layer-group" style="color:#3b82f6;font-size:15px;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0 fw-bold" style="font-size:15px;">Interactive Modules</p>
                                        <small class="text-muted fw-normal">Lessons, videos, activities and quizzes</small>
                                    </div>
                                    <span class="badge bg-primary me-2"><?= count($imModules ?? []) ?> module<?= count($imModules ?? []) != 1 ? 's' : '' ?></span>
                                </div>
                            </button>
                        </h2>
                        <div id="imCollapse" class="accordion-collapse collapse"
                             aria-labelledby="imHeading"
                             data-bs-parent="#mainAccordion">
                            <div class="accordion-body p-0">
                                <?php if (empty($imModules)): ?>
                                <div class="text-center py-4 text-muted small">
                                    <i class="fa fa-layer-group mb-2 d-block" style="font-size:24px;opacity:.4;"></i>
                                    No interactive modules yet.
                                </div>
                                <?php else: ?>
                                <div class="d-flex flex-column">
                                    <?php foreach ($imModules as $imIdx => $im): ?>
                                    <div class="border-top px-3 py-3">
                                        <!-- Module header row -->
                                        <div class="d-flex align-items-start gap-3">
                                            <div style="width:32px;height:32px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                                                <span style="font-size:12px;font-weight:700;color:#3b82f6;"><?= $imIdx + 1 ?></span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <p class="mb-0 fw-semibold" style="font-size:14px;"><?= htmlspecialchars($im['title']) ?></p>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fa fa-file me-1" style="color:#3b82f6;"></i>
                                                            <?= $im['lesson_count'] ?> lesson<?= $im['lesson_count'] != 1 ? 's' : '' ?>
                                                        </span>
                                                        <small class="text-muted"><?= date('M d, Y', strtotime($im['created_at'])) ?></small>
                                                    </div>
                                                </div>
                                                <?php if (!empty($im['description'])): ?>
                                                <small class="text-muted"><?= htmlspecialchars($im['description']) ?></small>
                                                <?php endif; ?>

                                                <!-- Lessons list -->
                                                <?php if (!empty($im['lessons'])): ?>
                                                <div class="d-flex flex-column gap-1 mt-2">
                                                    <?php foreach ($im['lessons'] as $lesIdx => $les): ?>
                                                    <div class="d-flex align-items-center gap-2 p-2 rounded-2 bg-light border">
                                                        <div style="width:22px;height:22px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                            <span style="font-size:10px;font-weight:700;color:#3b82f6;"><?= $lesIdx + 1 ?></span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-0 small fw-semibold"><?= htmlspecialchars($les['title']) ?></p>
                                                            <?php if (!empty($les['topic'])): ?>
                                                            <small class="text-muted"><?= htmlspecialchars($les['topic']) ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                        <i class="fa fa-file" style="color:#3b82f6;font-size:13px;"></i>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div><!-- end #mainAccordion -->
            </div>

            <!-- ── Students table ── -->
            <div class="parent-table mt-4">
                <div class="search-full-parent">
                    <div class="search-form">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="search" class="form-control" placeholder="Search by name or email...">
                        </div>
                    </div>
                    <div class="search-level">
                        <select class="form-select">
                            <option value="">Grade Level</option>
                            <option value="Grade 12">Grade 12</option>
                            <option value="Grade 11">Grade 11</option>
                        </select>
                    </div>
                    <div class="search-section">
                        <select class="form-select">
                            <option value="">All Sections</option>
                            <option value="CSS 12-1">CSS 12-1</option>
                            <option value="CSS 12-2">CSS 12-2</option>
                            <option value="CSS 11-1">CSS 11-1</option>
                            <option value="CSS 11-2">CSS 11-2</option>
                        </select>
                    </div>
                </div>

                <div class="filter">
                    <i class="fa fa-filter"></i>
                    <span>Showing 4 of 4 students</span>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Grade / Section</th>
                            <th>Enrolled Classes</th>
                            <th>Overall Progress</th>
                            <th>Avg. Quiz Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 8; $i++): ?>
                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td><span>Grade 12</span> <span>CSS-12-1</span></td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar"><div class="progress"></div></div>
                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td><div class="actions"><i class="fa fa-eye"></i><span>View Details</span></div></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <!-- ── Save Success Modal ── -->
    <?php if (!empty($_SESSION['save_success'])): ?>
    <?php unset($_SESSION['save_success']); ?>
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success-subtle border-success-subtle">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="fa fa-circle-check text-success"></i>
                        Saved Successfully!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div style="width:64px;height:64px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fa fa-circle-check text-success" style="font-size:32px;"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Modules saved successfully!</h6>
                    <p class="text-muted small mb-0">Your lessons and modules have been saved.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-sm px-4" data-bs-dismiss="modal">
                        <i class="fa fa-check me-1"></i> Done
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new bootstrap.Modal(document.getElementById("successModal")).show();
    });
    </script>
    <?php endif; ?>

    <!-- ── Duplicate Warning Modal ── -->
    <?php if (!empty($_SESSION['save_skipped'])): ?>
    <?php
        $skipped = $_SESSION['save_skipped'];
        $cfMods  = $skipped['cf_modules'] ?? [];
        $imMods  = $skipped['im_modules'] ?? [];
        $lessons = $skipped['lessons']    ?? [];
        unset($_SESSION['save_skipped']);
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
                        exist. Any new content inside them was still saved normally.
                    </p>
                    <?php if (!empty($cfMods)): ?>
                    <p class="fw-semibold small mb-1">
                        <i class="fa fa-inbox text-warning me-1"></i> Classes Modules
                        <span class="badge bg-warning text-dark ms-1"><?= count($cfMods) ?> skipped</span>
                    </p>
                    <ul class="small text-secondary mb-3 ps-3">
                        <?php foreach ($cfMods as $name): ?><li><?= htmlspecialchars($name) ?></li><?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if (!empty($imMods)): ?>
                    <p class="fw-semibold small mb-1">
                        <i class="fa fa-layer-group text-warning me-1"></i> Interactive Modules
                        <span class="badge bg-warning text-dark ms-1"><?= count($imMods) ?> skipped</span>
                    </p>
                    <ul class="small text-secondary mb-3 ps-3">
                        <?php foreach ($imMods as $name): ?><li><?= htmlspecialchars($name) ?></li><?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if (!empty($lessons)): ?>
                    <p class="fw-semibold small mb-1">
                        <i class="fa fa-file text-warning me-1"></i> Lessons
                        <span class="badge bg-warning text-dark ms-1"><?= count($lessons) ?> skipped</span>
                    </p>
                    <ul class="small text-secondary mb-0 ps-3">
                        <?php foreach ($lessons as $name): ?><li><?= htmlspecialchars($name) ?></li><?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Got it</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new bootstrap.Modal(document.getElementById("skippedModal")).show();
    });
    </script>
    <?php endif; ?>

</body>
</html>