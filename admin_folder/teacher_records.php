<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Records</title>
    <link rel="stylesheet" href="../css_folder/teacher_records.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
        
    </style>
</head>

<body>

    <div class="container-fluid p-0">

        <!-- ══ CREATE TEACHER MODAL ══════════════════════════════════════ -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Teacher</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="?url=createTeacher" method="post">
                        <div class="modal-body">
                            <div class="row g-3">

                                <!-- Name, Email, Password -->
                                <div class="col-lg-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter name">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Enter email">
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password">
                                </div>

                                <div class="col-12"><hr></div>

                                <!-- ── Assigned Subjects ── -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Assigned Subjects</label>

                                    <!-- Grade 12 -->
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
                                                                name="assigned_subjects[]"
                                                                value="<?= $subject['id'] ?>"
                                                                data-name="<?= htmlspecialchars($subject['subject_name']) ?>"
                                                                data-grade="12">
                                                            <?= htmlspecialchars($subject['subject_name']) ?>
                                                        </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grade 11 -->
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
                                                                name="assigned_subjects[]"
                                                                value="<?= $subject['id'] ?>"
                                                                data-name="<?= htmlspecialchars($subject['subject_name']) ?>"
                                                                data-grade="11">
                                                            <?= htmlspecialchars($subject['subject_name']) ?>
                                                        </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ── Add new subject row — uses form="addSubjectForm" ── -->
                                    <div class="add-subject-row mt-3">
                                        <i class="fa fa-plus-circle"></i>
                                        <input type="text"
                                               name="subject_name"
                                               form="addSubjectForm"
                                               id="add-sub-name-input"
                                               placeholder="Subject name e.g. Filipino">
                                        <select name="grade_level_id"
                                                form="addSubjectForm"
                                                id="add-sub-grade-select">
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

                                    <!-- Selected subjects preview -->
                                    <div class="preview-wrap" id="sub-preview-wrap">
                                        <p class="preview-label">Selected Subjects:</p>
                                        <div class="preview-tags" id="sub-preview-tags"></div>
                                    </div>
                                </div>

                                <div class="col-12"><hr></div>

                                <!-- ── Assigned Sections ── -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Assigned Sections</label>

                                    <!-- Grade 12 -->
                                    <div class="parent-subjects mt-2">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon"><span>G12</span></div>
                                                <p><?= htmlspecialchars($grade12Sections[0]['grade_name'] ?? 'Grade 12') ?></p>
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
                                                                name="assigned_sections[]"
                                                                value="<?= $section['id'] ?>"
                                                                data-name="<?= htmlspecialchars($section['section_name']) ?>"
                                                                data-grade="12">
                                                            <?= htmlspecialchars($section['section_name']) ?>
                                                        </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grade 11 -->
                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon"><span>G11</span></div>
                                                <p><?= htmlspecialchars($grade11Sections[0]['grade_name'] ?? 'Grade 11') ?></p>
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
                                                                name="assigned_sections[]"
                                                                value="<?= $section['id'] ?>"
                                                                data-name="<?= htmlspecialchars($section['section_name']) ?>"
                                                                data-grade="11">
                                                            <?= htmlspecialchars($section['section_name']) ?>
                                                        </label>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selected sections preview -->
                                    <div class="preview-wrap" id="sec-preview-wrap">
                                        <p class="preview-label">Selected Sections:</p>
                                        <div class="preview-tags" id="sec-preview-tags"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create teacher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- NAV -->
        <nav>
            <a href="/learning_management/public/?url=admin"><i class="fa fa-arrow-left"></i></a>
            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa fa-plus-circle"></i> Add Teachers
            </button>
        </nav>

        <!-- MAIN -->
        <main>
            <div class="search-parent">
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
                    <span>Showing <?= count($teachers ?? []) ?> teachers</span>
                </div>
            </div>

            <div class="table-parent">
                <h3>All Teachers</h3>
                <hr>
                <div class="teachers-parents-data">
                    <?php if (empty($teachers)): ?>
                            <p style="color:#999;padding:20px;">No teachers found.</p>
                    <?php else: ?>
                            <?php foreach ($teachers as $teacher): ?>
                                    <div class="teachers-data">
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
                                                                <span><?= htmlspecialchars($subject) ?></span>
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
        </main>
    </div>

    <!-- ── Standalone addSubject form — outside all other forms ── -->
    <form id="addSubjectForm" action="?url=addSubject" method="post" style="display:none;"></form>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── 1. Selected count helpers ─────────────────────────────────
        function updateSubjectCounts() {
            const g12 = document.querySelectorAll('#g12-subjects-list .subject-checkbox:checked').length;
            const g11 = document.querySelectorAll('#g11-subjects-list .subject-checkbox:checked').length;
            document.getElementById('g12-sub-count').textContent = g12 + ' selected';
            document.getElementById('g11-sub-count').textContent = g11 + ' selected';
        }

        function updateSectionCounts() {
            const g12 = document.querySelectorAll('#g12-sections-list .section-checkbox:checked').length;
            const g11 = document.querySelectorAll('#g11-sections-list .section-checkbox:checked').length;
            document.getElementById('g12-sec-count').textContent = g12 + ' selected';
            document.getElementById('g11-sec-count').textContent = g11 + ' selected';
        }

        // ── 2. Render preview tags ────────────────────────────────────
        function renderPreview(selector, wrapId, tagsId, tagClass) {
            const checked = document.querySelectorAll(selector + ':checked');
            const wrap    = document.getElementById(wrapId);
            const tags    = document.getElementById(tagsId);
            tags.innerHTML = '';
            wrap.style.display = checked.length ? 'block' : 'none';
            checked.forEach(function (cb) {
                const tag = document.createElement('span');
                tag.className = 'preview-tag ' + tagClass;
                tag.innerHTML = escHtml(cb.dataset.name) +
                    ' <i class="fa fa-times" data-id="' + cb.id + '"></i>';
                tag.querySelector('i').addEventListener('click', function () {
                    const target = document.getElementById(this.dataset.id);
                    if (target) { target.checked = false; target.dispatchEvent(new Event('change')); }
                });
                tags.appendChild(tag);
            });
        }

        function escHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        // ── 3. Bind checkboxes ────────────────────────────────────────
        function bindSubjectCheckbox(cb) {
            cb.addEventListener('change', function () {
                updateSubjectCounts();
                renderPreview('.subject-checkbox', 'sub-preview-wrap', 'sub-preview-tags', 'subject-tag');
            });
        }

        function bindSectionCheckbox(cb) {
            cb.addEventListener('change', function () {
                updateSectionCounts();
                renderPreview('.section-checkbox', 'sec-preview-wrap', 'sec-preview-tags', 'section-tag');
            });
        }

        document.querySelectorAll('.subject-checkbox').forEach(bindSubjectCheckbox);
        document.querySelectorAll('.section-checkbox').forEach(bindSectionCheckbox);

        // ── 4. Reset on modal close ───────────────────────────────────
        document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
            document.querySelectorAll('.subject-checkbox, .section-checkbox').forEach(cb => cb.checked = false);
            ['sub-preview-wrap','sec-preview-wrap'].forEach(id => document.getElementById(id).style.display = 'none');
            ['sub-preview-tags','sec-preview-tags'].forEach(id => document.getElementById(id).innerHTML = '');
            document.getElementById('add-sub-name-input').value  = '';
            document.getElementById('add-sub-grade-select').value = '';
            updateSubjectCounts();
            updateSectionCounts();
        });

    });
    </script>

</body>
</html>