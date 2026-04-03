<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Records</title>
    <link rel="stylesheet" href="../css_folder/teacher_records.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <!-- <style>
        body {
            background: #f0f2ff;
        }

        /* ── NAV ── */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 28px;
            background: #fff;
            border-bottom: 1px solid #e5e7ef;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        nav a {
            color: #333;
            font-size: 18px;
            text-decoration: none;
        }

        nav>button {
            background: #5b21f5;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        nav>button:hover {
            background: #4a18d4;
        }

        /* ── PAGE WRAP ── */
        /* .page-wrap {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        } */

        /* ── MAIN TABS (Tab 1 / Tab 2) ── */
        .main-tabs-wrap {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(91, 33, 245, .07);
            overflow: hidden;
        }

        .main-tabs-header {
            padding: 18px 24px 0;
            border-bottom: 2px solid #ebebf5;
        }

        .main-tabs-header h4 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 14px;
        }

        .nav-tabs {
            border-bottom: none;
            gap: 6px;
        }

        .nav-tabs .nav-link {
            border: 1.5px solid #e0e0f0;
            border-radius: 10px 10px 0 0 !important;
            color: #555;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 20px;
            background: #f7f8ff;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: all .2s;
        }

        .nav-tabs .nav-link:hover {
            background: #ede9ff;
            color: #5b21f5;
        }

        .nav-tabs .nav-link.active {
            background: #5b21f5;
            color: #fff !important;
            border-color: #5b21f5;
        }

        /* ══════════════════════════
           TAB 1 — All Teachers List
        ══════════════════════════ */
        .tab-content {
            padding: 0;
        }

        /* search bar */
        .search-parent {
            padding: 20px 24px 14px;
            border-bottom: 1px solid #f0f0f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            align-items: center;
            /* flex-wrap: wrap; */
            gap: 12px;
        }

        .search-full-parent {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            /* flex-wrap: wrap; */
            /* flex: 1; */
        }

        /* .search-form {
            flex: 1;
            min-width: 200px;
        } */

        .search-level,
        .search-section {
            min-width: 160px;
        }

        .filter {
            font-size: 13px;
            color: #888;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* teacher cards */
        .table-parent {
            padding: 20px 24px;
        }

        .table-parent h3 {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .teachers-data {
            border: 1.5px solid #ebebf5;
            border-radius: 12px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .teachers-text {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f8;
            flex-wrap: wrap;
            gap: 10px;
        }

        .teachers-name-parent {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .teachers-initial {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7c3aed, #5b21f5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            font-weight: 700;
        }

        .teachers-name p {
            margin: 0;
        }

        .teachers-name p:first-child {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .teachers-name p:last-child {
            font-size: 13px;
            color: #888;
        }

        .teachers-button {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .class-count {
            background: #f0ebff;
            color: #5b21f5;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 13px;
            font-weight: 600;
        }

        .teachers-button button {
            background: #5b21f5;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .teachers-button button:hover {
            background: #4a18d4;
        }

        .teachers-body {
            display: flex;
            gap: 30px;
            padding: 16px 20px;
            flex-wrap: wrap;
        }

        .assign-subject,
        .assign-section {
            flex: 1;
            min-width: 200px;
        }

        .assign-subject .title,
        .assign-section .title {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #aaa;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        

        /* .sections {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        } */

        /* .sections span {
            background: #f0ebff;
            color: #5b21f5;
            border-radius: 8px;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: 600;
        } */

        .tab-pane{
            padding: 2rem;
        }

        /* ══════════════════════════
           TAB 2 — Teacher Detail
        ══════════════════════════ */
        .tab2-body {
            padding: 24px;
        }

        .teacher-select-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .teacher-select-wrap label {
            font-size: 13px;
            font-weight: 700;
            color: #555;
            white-space: nowrap;
        }

        #teacherDropdown {
            border: 1.5px solid #d0d0e8;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 14px;
            color: #333;
            background: #f7f8ff;
            min-width: 260px;
            cursor: pointer;
            outline: none;
        }

        #teacherDropdown:focus {
            border-color: #5b21f5;
        }

        /* teacher detail panel */
        #teacherDetail {
            display: none;
        }

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #5b21f5, #7c3aed);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 22px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .detail-avatar-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .detail-avatar {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
        }

        .detail-name {
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .detail-email {
            color: rgba(255, 255, 255, .75);
            font-size: 13px;
            margin: 0;
        }

        .detail-classes {
            background: rgba(255, 255, 255, .2);
            color: #fff;
            border-radius: 20px;
            padding: 5px 16px;
            font-size: 13px;
            font-weight: 600;
        }

        /* subject accordion in tab 2 */
        .subjects-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #aaa;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .subject-accordion .accordion-item {
            border: 1.5px solid #e8eaf0;
            border-radius: 12px !important;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .subject-accordion .accordion-button {
            background: #f7f8ff;
            color: #1a1a2e;
            font-weight: 600;
            font-size: 14px;
            border-radius: 12px !important;
            gap: 10px;
        }

        .subject-accordion .accordion-button:not(.collapsed) {
            background: #ede9ff;
            color: #5b21f5;
            box-shadow: none;
        }

        .jc-badge {
            margin-left: auto;
            margin-right: 12px;
            background: #5b21f5;
            color: #fff;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            font-family: monospace;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        .jc-badge i {
            font-size: 10px;
            opacity: .8;
        }

        .accordion-body {
            background: #fff;
            padding: 16px;
        }

        .students-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .students-meta span {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #aaa;
            text-transform: uppercase;
        }

        .student-count-badge {
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .students-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
        }

        .students-table thead th {
            background: #f7f8ff;
            color: #888;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 8px 14px;
            border-bottom: 1.5px solid #eee;
        }

        .students-table tbody tr:hover {
            background: #fafbff;
        }

        .students-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f5;
            color: #333;
            vertical-align: middle;
        }

        .student-row-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            margin-right: 8px;
        }

        .section-pill {
            background: #f0ebff;
            color: #5b21f5;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .no-students {
            text-align: center;
            color: #bbb;
            padding: 20px;
            font-size: 13px;
        }

        .no-subjects-msg {
            text-align: center;
            color: #bbb;
            padding: 40px;
            font-size: 14px;
        }

        .placeholder-msg {
            text-align: center;
            color: #bbb;
            padding: 60px 20px;
            font-size: 14px;
        }
    </style> -->
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
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter password">
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
                                    <div class="add-subject-row mt-3">
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
            <div class="page-wrap">
                <div class="main-tabs-wrap">

                    <!-- ── Main tab buttons ── -->
                    <div class="main-tabs-header">
                        <h4>
                            <i class="fa fa-chalkboard-teacher me-2" style="color:#5b21f5;"></i>
                            Teacher Records
                        </h4>
                        <ul class="nav nav-tabs" id="mainTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="tab-list-btn" data-bs-toggle="tab"
                                    data-bs-target="#tab-list" type="button" role="tab">
                                    <i class="fa fa-list"></i> All Teachers
                                    <span class="badge ms-1" style="background:rgba(255,255,255,.3);font-size:11px;">
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
                                                        <p class="detail-name"><?= htmlspecialchars($teacher['name']) ?></p>
                                                        <p class="detail-email"><?= htmlspecialchars($teacher['email']) ?>
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
                                                        $bodyId = 'sb-' . $teacher['teacher_id'] . '-' . $si;
                                                        $students = $subject['students'] ?? [];
                                                        ?>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse" data-bs-target="#<?= $bodyId ?>"
                                                                    aria-expanded="false">
                                                                    <i class="fa fa-book-open me-2"
                                                                        style="color:#5b21f5;font-size:13px;"></i>
                                                                    <?= htmlspecialchars($subject['name']) ?>

                                                                    <?php
                                                                    // Fetch sections with join codes for this subject+teacher
                                                                    $subjectSections = $this->teacherModel->getSectionsByTeacherSubject(
                                                                        (int) $subject['id'],
                                                                        (int) $teacher['teacher_id']
                                                                    );
                                                                    ?>
                                                                    <?php foreach ($subjectSections as $ss): ?>
                                                                        <?php if (!empty($ss['join_code'])): ?>
                                                                            <span class="jc-badge ms-2">
                                                                                <i class="fa fa-key"></i>
                                                                                <?= htmlspecialchars($ss['join_code']) ?>
                                                                            </span>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </button>
                                                            </h2>
                                                            <div id="<?= $bodyId ?>" class="accordion-collapse collapse"
                                                                data-bs-parent="#acc-t<?= $teacher['teacher_id'] ?>">
                                                                <div class="accordion-body">
                                                                    <?php
                                                                    // Fetch sections for this teacher+subject even if no students
                                                                    $sections = $this->teacherModel->getSectionsByTeacherSubject(
                                                                        (int) $subject['id'],
                                                                        (int) $teacher['teacher_id']
                                                                    );
                                                                    $totalStudents = 0;
                                                                    foreach ($students as $sec) {
                                                                        $totalStudents += count($sec['students']);
                                                                    }
                                                                    ?>
                                                                    <div class="students-meta">
                                                                        <span><i class="fa fa-users me-1"></i> Enrolled
                                                                            Students</span>
                                                                        <span class="student-count-badge">
                                                                            <?= $totalStudents ?>
                                                                            student<?= $totalStudents !== 1 ? 's' : '' ?>
                                                                        </span>
                                                                    </div>

                                                                    <?php foreach ($sections as $sec): ?>
                                                                        <?php
                                                                        // Find matching student group for this section
                                                                        $secLabel = $sec['grade_name'] . ' - ' . $sec['section_name'];
                                                                        $secStudents = [];
                                                                        foreach ($students as $sg) {
                                                                            if ($sg['section_label'] === $secLabel) {
                                                                                $secStudents = $sg['students'];
                                                                                break;
                                                                            }
                                                                        }
                                                                        ?>
                                                                        <div class="section-group-header">
                                                                            <i class="fa fa-layer-group"></i>
                                                                            <?= htmlspecialchars($secLabel) ?>
                                                                            <span class="sec-student-count ms-auto">
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
                                                                                            <td style="color:#aaa; width:40px;"><?= $n + 1 ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <span class="student-row-avatar">
                                                                                                    <?= strtoupper(substr($stu['name'], 0, 1)) ?>
                                                                                                </span>
                                                                                                <?= htmlspecialchars($stu['name']) ?>
                                                                                            </td>
                                                                                            <td style="color:#777;">
                                                                                                <?= htmlspecialchars($stu['email']) ?>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
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
</body>

</html>