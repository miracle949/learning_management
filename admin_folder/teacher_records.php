<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Records</title>
    <link rel="stylesheet" href="../css_folder/teacher_records.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">

        <!-- ══ CREATE TEACHER MODAL ══════════════════════════════════════ -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Create New Teacher</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="?url=createTeacher" method="post">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Enter email" required>
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter password" required>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>

                                <!-- Subjects -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Assigned Subjects</label>
                                    <div class="parent-subjects mt-2">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon"><span>G12</span></div>
                                                <p>Grade 12</p>
                                            </div>
                                            <div class="selected">
                                                <span class="selected-count" id="g12-sub-count">0 selected</span>
                                            </div>
                                        </div>
                                        <div class="body-subjects">
                                            <div class="card-parent" id="g12-subjects-list">
                                                <?php foreach ($grade12Subjects as $subject): ?>
                                                    <label class="subject-checkbox-label">
                                                        <input class="subject-checkbox" type="checkbox"
                                                            name="assigned_subjects[]" value="<?= $subject['id'] ?>"
                                                            data-name="<?= htmlspecialchars($subject['subject_name']) ?>"
                                                            data-grade="12">
                                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon"><span>G11</span></div>
                                                <p>Grade 11</p>
                                            </div>
                                            <div class="selected">
                                                <span class="selected-count" id="g11-sub-count">0 selected</span>
                                            </div>
                                        </div>
                                        <div class="body-subjects">
                                            <div class="card-parent" id="g11-subjects-list">
                                                <?php foreach ($grade11Subjects as $subject): ?>
                                                    <label class="subject-checkbox-label">
                                                        <input class="subject-checkbox" type="checkbox"
                                                            name="assigned_subjects[]" value="<?= $subject['id'] ?>"
                                                            data-name="<?= htmlspecialchars($subject['subject_name']) ?>"
                                                            data-grade="11">
                                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="add-subject-row mt-3" style="display: none;">
                                        <i class="fa fa-plus-circle"></i>
                                        <input type="text" name="subject_name" form="addSubjectForm"
                                            id="add-sub-name-input" placeholder="Subject name e.g. Filipino">
                                        <select name="grade_level_id" form="addSubjectForm" id="add-sub-grade-select">
                                            <option value="">Grade level</option>
                                            <option value="2">Grade 12</option>
                                            <option value="1">Grade 11</option>
                                        </select>
                                        <button type="submit" form="addSubjectForm">
                                            <i class="fa fa-save"></i> Add Subject
                                        </button>
                                    </div>
                                    <?php if (!empty($_SESSION['success'])): ?>
                                        <div class="add-msg success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                                        <?php unset($_SESSION['success']); ?>
                                    <?php endif; ?>
                                    <?php if (!empty($_SESSION['error'])): ?>
                                        <div class="add-msg error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                                        <?php unset($_SESSION['error']); ?>
                                    <?php endif; ?>
                                    <div class="preview-wrap" id="sub-preview-wrap">
                                        <p class="preview-label">Selected Subjects:</p>
                                        <div class="preview-tags" id="sub-preview-tags"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr>
                                </div>

                                <!-- Sections -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Assigned Sections</label>
                                    <div class="parent-subjects mt-2">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon"><span>G12</span></div>
                                                <p><?= htmlspecialchars($grade12Sections[0]['grade_name'] ?? 'Grade 12') ?>
                                                </p>
                                            </div>
                                            <div class="selected">
                                                <span class="selected-count" id="g12-sec-count">0 selected</span>
                                            </div>
                                        </div>
                                        <div class="body-subjects">
                                            <div class="card-parent" id="g12-sections-list">
                                                <?php foreach ($grade12Sections as $section): ?>
                                                    <label class="section-checkbox-label">
                                                        <input class="section-checkbox" type="checkbox"
                                                            name="assigned_sections[]" value="<?= $section['id'] ?>"
                                                            data-name="<?= htmlspecialchars($section['section_name']) ?>"
                                                            data-grade="12">
                                                        <?= htmlspecialchars($section['section_name']) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon"><span>G11</span></div>
                                                <p><?= htmlspecialchars($grade11Sections[0]['grade_name'] ?? 'Grade 11') ?>
                                                </p>
                                            </div>
                                            <div class="selected">
                                                <span class="selected-count" id="g11-sec-count">0 selected</span>
                                            </div>
                                        </div>
                                        <div class="body-subjects">
                                            <div class="card-parent" id="g11-sections-list">
                                                <?php foreach ($grade11Sections as $section): ?>
                                                    <label class="section-checkbox-label">
                                                        <input class="section-checkbox" type="checkbox"
                                                            name="assigned_sections[]" value="<?= $section['id'] ?>"
                                                            data-name="<?= htmlspecialchars($section['section_name']) ?>"
                                                            data-grade="11">
                                                        <?= htmlspecialchars($section['section_name']) ?>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-wrap" id="sec-preview-wrap">
                                        <p class="preview-label">Selected Sections:</p>
                                        <div class="preview-tags" id="sec-preview-tags"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit">Create teacher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- NAV -->
        <!-- <nav>
            <a href="/learning_management/public/?url=admin"><i class="fa fa-arrow-left"></i></a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa fa-plus-circle"></i> Add Teachers
            </button>
        </nav> -->

        <?php include("../admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <?php include("../admin_folder/nav.php") ?>

            <!-- MAIN -->
            <main class="main">
                <div class="page-wrap">
                    <div class="main-tabs-wrap">

                        <!-- ── Main tab buttons ── -->
                        <div class="main-tabs-header">
                            <div class="nav-button">
                                <h4>
                                    <i class="fa fa-chalkboard-teacher me-2"></i>
                                    Teacher Records
                                </h4>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa fa-plus-circle me-1"></i> Add Teacher
                                </button>
                            </div>
                            <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-list-btn" data-bs-toggle="tab"
                                        data-bs-target="#tab-list" type="button" role="tab">
                                        <i class="fa fa-list"></i> All Teachers
                                        <span class="badge ms-1"
                                            style="background:rgba(255,255,255,.3);font-size:11px;">
                                            <?= count($teachers) ?>
                                        </span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-detail-btn" data-bs-toggle="tab"
                                        data-bs-target="#tab-detail" type="button" role="tab">
                                        <i class="fa fa-search"></i> View by Teacher
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content" id="mainTabsContent">

                            <!-- ════════════════════════════
                            TAB 1 — All Teachers List
                        ════════════════════════════ -->
                            <div class="tab-pane fade show active" id="tab-list" role="tabpanel">

                                <div class="search-parent">
                                    <div class="filter">
                                        <i class="fa fa-filter"></i>
                                        <span id="showingCount">Showing <?= count($teachers) ?> teachers</span>
                                    </div>
                                    <div class="search-full-parent">
                                        <div class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                <input type="search" id="searchInput" class="form-control"
                                                    placeholder="Search by name or email...">
                                            </div>
                                        </div>
                                        <div class="search-level">
                                            <select class="form-select" id="filterLevel">
                                                <option value="">Grade Level</option>
                                                <option value="Grade 12">Grade 12</option>
                                                <option value="Grade 11">Grade 11</option>
                                            </select>
                                        </div>
                                        <div class="search-section">
                                            <select class="form-select" id="filterSection">
                                                <option value="">All Sections</option>
                                                <?php
                                                $allSections = array_merge($grade12Sections, $grade11Sections);
                                                foreach ($allSections as $sec):
                                                    ?>
                                                    <option value="<?= htmlspecialchars($sec['section_name']) ?>">
                                                        <?= htmlspecialchars($sec['section_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="table-parent">
                                    <h3>All Teachers</h3>
                                    <hr>
                                    <div class="teachers-parents-data" id="teacherListContainer">
                                        <?php if (empty($teachers)): ?>
                                            <p style="color:#999;padding:20px;">No teachers found.</p>
                                        <?php else: ?>
                                            <?php foreach ($teachers as $teacher): ?>
                                                <div class="teachers-data"
                                                    data-name="<?= strtolower(htmlspecialchars($teacher['name'])) ?>"
                                                    data-email="<?= strtolower(htmlspecialchars($teacher['email'])) ?>"
                                                    data-sections="<?= strtolower(implode(',', $teacher['sections'])) ?>">
                                                    <div class="teachers-text">
                                                        <div class="teachers-name-parent">
                                                            <div class="teachers-initial">
                                                                <span><?= strtoupper(substr($teacher['name'] ?? '', 0, 1)) ?></span>
                                                            </div>
                                                            <div class="teachers-name">
                                                                <p><?= htmlspecialchars($teacher['name']) ?></p>
                                                                <p><?= htmlspecialchars($teacher['email']) ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="teachers-button">
                                                            <div class="class-count">
                                                                <p><?= $teacher['class_count'] ?> classes</p>
                                                            </div>
                                                            <button data-id="<?= $teacher['teacher_id'] ?>">
                                                                <i class="fa fa-edit"></i>
                                                                <span>Edit Details</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="teachers-body">
                                                        <div class="assign-subject">
                                                            <span class="title">Assigned Subjects:</span>
                                                            <div class="subject">
                                                                <div class="parent-sub">
                                                                    <?php foreach ($teacher['subjects'] as $subject): ?>
                                                                        <span><?= htmlspecialchars($subject['name']) ?></span>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="assign-section">
                                                            <span class="title">Assigned Sections:</span>
                                                            <div class="sections">
                                                                <?php if (!empty($teacher['sections'])): ?>
                                                                    <?php foreach ($teacher['sections'] as $section): ?>
                                                                        <span><?= htmlspecialchars($section) ?></span>
                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <span style="color:#999;">No sections assigned</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- ════════════════════════════
                            TAB 2 — View by Teacher
                        ════════════════════════════ -->
                            <div class="tab-pane fade" id="tab-detail" role="tabpanel">
                                <div class="tab2-body">

                                    <!-- Dropdown -->
                                    <div class="teacher-select-wrap">
                                        <label><i class="fa fa-user-tie me-1"></i> Select Teacher:</label>
                                        <select id="teacherDropdown">
                                            <option value="">— Choose a teacher —</option>
                                            <?php foreach ($teachers as $teacher): ?>
                                                <option value="<?= $teacher['teacher_id'] ?>">
                                                    <?= htmlspecialchars($teacher['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Placeholder when nothing selected -->
                                    <div class="placeholder-msg" id="tab2Placeholder">
                                        <i class="fa fa-user-circle fa-3x mb-3 d-block" style="opacity:.2;"></i>
                                        Select a teacher from the dropdown above to view their details.
                                    </div>

                                    <!-- Teacher detail panel (hidden until selected) -->
                                    <div id="teacherDetail">
                                        <?php foreach ($teachers as $teacher): ?>
                                            <div class="teacher-panel" data-id="<?= $teacher['teacher_id'] ?>"
                                                style="display:none;">

                                                <!-- Header card -->
                                                <div class="detail-header">
                                                    <div class="detail-avatar-info">
                                                        <div class="detail-avatar">
                                                            <?= strtoupper(substr($teacher['name'], 0, 1)) ?>
                                                        </div>
                                                        <div>
                                                            <p class="detail-name"><?= htmlspecialchars($teacher['name']) ?>
                                                            </p>
                                                            <p class="detail-email">
                                                                <?= htmlspecialchars($teacher['email']) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <span class="detail-classes">
                                                        <?= $teacher['class_count'] ?> classes
                                                    </span>
                                                </div>

                                                <!-- Subjects accordion -->
                                                <?php if (empty($teacher['subjects'])): ?>
                                                    <div class="no-subjects-msg">
                                                        <i class="fa fa-book-open fa-2x mb-2 d-block" style="opacity:.3;"></i>
                                                        No subjects assigned to this teacher.
                                                    </div>
                                                <?php else: ?>
                                                    <p class="subjects-label">
                                                        <i class="fa fa-book me-1"></i> Assigned Subjects
                                                    </p>
                                                    <div class="accordion subject-accordion"
                                                        id="acc-t<?= $teacher['teacher_id'] ?>">
                                                        <?php foreach ($teacher['subjects'] as $si => $subject): ?>
                                                            <?php
                                                            $sections = $this->teacherModel->getSectionsByTeacherSubject(
                                                                (int) $subject['id'],
                                                                (int) $teacher['teacher_id']
                                                            );
                                                            $students = $subject['students'] ?? [];
                                                            ?>
                                                            <?php foreach ($sections as $secIdx => $sec): ?>
                                                                <?php
                                                                $secLabel = $sec['grade_name'] . ' - ' . $sec['section_name'];
                                                                $secStudents = [];
                                                                foreach ($students as $sg) {
                                                                    if ($sg['section_label'] === $secLabel) {
                                                                        $secStudents = $sg['students'];
                                                                        break;
                                                                    }
                                                                }
                                                                $bodyId = 'sb-' . $teacher['teacher_id'] . '-' . $si . '-' . $secIdx;
                                                                ?>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header">
                                                                        <button class="accordion-button collapsed" type="button"
                                                                            data-bs-toggle="collapse" data-bs-target="#<?= $bodyId ?>"
                                                                            aria-expanded="false">
                                                                            <i class="fa fa-book-open me-2"></i>
                                                                            <?= htmlspecialchars($subject['name']) ?>
                                                                            <span
                                                                                style="font-size:12px;color:#6b7280;margin-left:8px;font-weight:500;">
                                                                                <?= htmlspecialchars($secLabel) ?>
                                                                            </span>
                                                                            <?php if (!empty($sec['join_code'])): ?>
                                                                                <span class="jc-badge ms-2">
                                                                                    <i class="fa fa-key"></i>
                                                                                    <?= htmlspecialchars($sec['join_code']) ?>
                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="<?= $bodyId ?>" class="accordion-collapse collapse">
                                                                        <div class="accordion-body">
                                                                            <div class="students-meta">
                                                                                <span><i class="fa fa-users me-1"></i> Enrolled
                                                                                    Students</span>
                                                                                <span class="student-count-badge">
                                                                                    <?= count($secStudents) ?>
                                                                                    student<?= count($secStudents) !== 1 ? 's' : '' ?>
                                                                                </span>
                                                                            </div>

                                                                            <?php if (empty($secStudents)): ?>
                                                                                <div class="no-students">
                                                                                    <i class="fa fa-user-slash fa-lg mb-1 d-block"></i>
                                                                                    No students enrolled yet.
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <table class="students-table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th>Student Name</th>
                                                                                            <th>Email</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php foreach ($secStudents as $n => $stu): ?>
                                                                                            <tr>
                                                                                                <td style="color:#aaa;width:40px;"><?= $n + 1 ?>
                                                                                                </td>
                                                                                                <td>
                                                                                                    <span class="student-row-avatar">
                                                                                                        <?= strtoupper(substr($stu['name'], 0, 1)) ?>
                                                                                                    </span>
                                                                                                    <?= htmlspecialchars($stu['name']) ?>
                                                                                                </td>
                                                                                                <td style="color:#777;">
                                                                                                    <?= htmlspecialchars($stu['email']) ?></td>
                                                                                            </tr>
                                                                                        <?php endforeach; ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div><!-- /teacherDetail -->

                                </div><!-- /tab2-body -->
                            </div>

                        </div><!-- /tab-content -->
                    </div><!-- /main-tabs-wrap -->
                </div><!-- /page-wrap -->
            </main>
        </div>
    </div>

    <!-- Standalone addSubject form -->
    <form id="addSubjectForm" action="?url=addSubject" method="post" style="display:none;"></form>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Tab 2: Teacher dropdown ────────────────────────────────────
            const dropdown = document.getElementById('teacherDropdown');
            const placeholder = document.getElementById('tab2Placeholder');
            const detailWrap = document.getElementById('teacherDetail');
            const panels = detailWrap.querySelectorAll('.teacher-panel');

            dropdown.addEventListener('change', function () {
                const val = this.value;
                panels.forEach(p => p.style.display = 'none');
                if (!val) {
                    placeholder.style.display = 'block';
                    detailWrap.style.display = 'none';
                    return;
                }
                placeholder.style.display = 'none';
                detailWrap.style.display = 'block';
                const target = detailWrap.querySelector('.teacher-panel[data-id="' + val + '"]');
                if (target) target.style.display = 'block';
            });

            // ── Tab 1: Live search + filter ───────────────────────────────
            const searchInput = document.getElementById('searchInput');
            const filterLevel = document.getElementById('filterLevel');
            const filterSection = document.getElementById('filterSection');
            const cards = document.querySelectorAll('.teachers-data');
            const showingCount = document.getElementById('showingCount');

            function applyFilters() {
                const q = searchInput.value.toLowerCase();
                const lvl = filterLevel.value.toLowerCase();
                const sec = filterSection.value.toLowerCase();
                let visible = 0;
                cards.forEach(card => {
                    const name = card.dataset.name || '';
                    const email = card.dataset.email || '';
                    const sections = card.dataset.sections || '';
                    const matchQ = !q || name.includes(q) || email.includes(q);
                    const matchLvl = !lvl || sections.includes(lvl);
                    const matchSec = !sec || sections.includes(sec);
                    const show = matchQ && matchLvl && matchSec;
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                showingCount.textContent = 'Showing ' + visible + ' teacher' + (visible !== 1 ? 's' : '');
            }

            searchInput.addEventListener('input', applyFilters);
            filterLevel.addEventListener('change', applyFilters);
            filterSection.addEventListener('change', applyFilters);

            // ── Modal checkbox helpers ─────────────────────────────────────
            function updateSubjectCounts() {
                document.getElementById('g12-sub-count').textContent =
                    document.querySelectorAll('#g12-subjects-list .subject-checkbox:checked').length + ' selected';
                document.getElementById('g11-sub-count').textContent =
                    document.querySelectorAll('#g11-subjects-list .subject-checkbox:checked').length + ' selected';
            }
            function updateSectionCounts() {
                document.getElementById('g12-sec-count').textContent =
                    document.querySelectorAll('#g12-sections-list .section-checkbox:checked').length + ' selected';
                document.getElementById('g11-sec-count').textContent =
                    document.querySelectorAll('#g11-sections-list .section-checkbox:checked').length + ' selected';
            }
            function renderPreview(selector, wrapId, tagsId, tagClass) {
                const checked = document.querySelectorAll(selector + ':checked');
                const wrap = document.getElementById(wrapId);
                const tags = document.getElementById(tagsId);
                tags.innerHTML = '';
                wrap.style.display = checked.length ? 'block' : 'none';
                checked.forEach(cb => {
                    const tag = document.createElement('span');
                    tag.className = 'preview-tag ' + tagClass;
                    tag.innerHTML = cb.dataset.name + ' <i class="fa fa-times" data-val="' + cb.value + '"></i>';
                    tag.querySelector('i').addEventListener('click', function () {
                        const input = document.querySelector(selector + '[value="' + this.dataset.val + '"]');
                        if (input) { input.checked = false; input.dispatchEvent(new Event('change')); }
                    });
                    tags.appendChild(tag);
                });
            }
            document.querySelectorAll('.subject-checkbox').forEach(cb => {
                cb.addEventListener('change', () => {
                    updateSubjectCounts();
                    renderPreview('.subject-checkbox', 'sub-preview-wrap', 'sub-preview-tags', 'subject-tag');
                });
            });
            document.querySelectorAll('.section-checkbox').forEach(cb => {
                cb.addEventListener('change', () => {
                    updateSectionCounts();
                    renderPreview('.section-checkbox', 'sec-preview-wrap', 'sec-preview-tags', 'section-tag');
                });
            });
            document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
                document.querySelectorAll('.subject-checkbox,.section-checkbox').forEach(cb => cb.checked = false);
                ['sub-preview-wrap', 'sec-preview-wrap'].forEach(id => document.getElementById(id).style.display = 'none');
                ['sub-preview-tags', 'sec-preview-tags'].forEach(id => document.getElementById(id).innerHTML = '');
                document.getElementById('add-sub-name-input').value = '';
                document.getElementById('add-sub-grade-select').value = '';
                updateSubjectCounts();
                updateSectionCounts();
            });
        });
    </script>

    <script>
        const listBtn = document.querySelector('#tab-list-btn');
        const tabBtn = document.querySelector('#tab-detail-btn');
        const main = document.querySelector('.main');

        // Tab 2 → full height
        tabBtn.addEventListener('shown.bs.tab', function () {
            main.style.height = '100vh';
        });

        // Tab 1 → normal height
        listBtn.addEventListener('shown.bs.tab', function () {
            main.style.height = 'auto';
        });
    </script>
</body>

</html>