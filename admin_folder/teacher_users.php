<?php
$grade11Subjects = $grade11Subjects ?? [];
$grade12Subjects = $grade12Subjects ?? [];
$grade11Sections = $grade11Sections ?? [];
$grade12Sections = $grade12Sections ?? [];
$teachers = $teachers ?? [];

$search = $_GET['search'] ?? '';
$grade = $_GET['grade'] ?? '';
$section = $_GET['section'] ?? '';
$status = $_GET['status'] ?? '';

// Pagination variables (passed from controller, with safe defaults)
$page = (int) ($page ?? 1);
$totalPages = (int) ($totalPages ?? 1);
$totalTeachers = (int) ($totalTeachers ?? count($teachers));
$limit = 10;
$offset = (int) ($offset ?? 0);

$teacherStats = $teacherStats ?? ['total' => 0, 'active' => 0, 'inactive' => 0];

$allSectionsFilter = array_merge($grade12Sections, $grade11Sections);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers</title>
    <link rel="stylesheet" href="../css_folder/teacher_users.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        /* ── chips ── */
        .tag-chip {
            display: inline-block;
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            margin: 2px;
        }

        .tag-chip.section {
            background: #e3f2fd;
            color: #1565c0;
            border-color: #bbdefb;
            font-size: 12.5px;
            font-weight: 600;
        }

        .tag-chip.grade {
            background: #fff3e0;
            color: #e65100;
            border-color: #ffe0b2;
            font-size: 12.5px;
            font-weight: 600;
        }

        .tags-cell {
            max-width: 150px;
        }

        .tags-overflow-wrap {
            display: flex;
            flex-wrap: nowrap;
            overflow: hidden;
            align-items: center;
            gap: 2px;
        }

        .more-badge {
            flex-shrink: 0;
            background: #f5f5f5;
            color: #555;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 2px 8px;
            font-size: 11px;
            cursor: pointer;
            white-space: nowrap;
        }

        .more-badge:hover {
            background: #e0e0e0;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12.5px;
            font-weight: 600;
        }

        /* ── filter grid — always 4 columns ── */
        .filter-grid {
            /* display: grid !important;
            grid-template-columns: 2fr 1fr 1fr 1fr; */
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background-color: #ffffff;
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04); */
            border-radius: 10px;
            /* border: 1px solid #e2e8f0; */
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            /* margin-top: 16px; */
            margin: 1.5rem 0 0;
        }

        @media (max-width: 900px) {
            .filter-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 520px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }

        /* #exampleModal .modal-content{
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        } */

        /* ── Edit modal (now mirrors create modal) ── */
        /* #editTeacherModal .modal-dialog {
            max-width: 680px;
        } */

        #editTeacherModal .modal-content {
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        #editTeacherModal .modal-body {
            /* padding: 16px 24px 24px; */
            padding: 16px 20px;
            max-height: 65vh;
            overflow-y: auto;
        }

        /* teacher info header inside modal */
        .teacher-info-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            background: #f9fafb;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .teacher-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            /* background-color: var(--green, #00C950); */
            background-color: var(--neon-cyan);
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .teacher-info-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .teacher-info-header p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        /* section label above cards */
        .edit-section-label {
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 8px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* modal footer */
        #editTeacherModal .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 16px;
            /* justify-content: space-between; */
            align-items: center;
        }

        #editTeacherModal .btn-save {
            padding: 8px 22px;
            background-color: var(--neon-cyan);
            color: #ffffff;
            border-radius: 28px;
            border: none;
            outline: none;
            font-weight: 600;
            margin: 0;
        }

        /* #editTeacherModal .btn-save:hover {
            background: #00a040;
        } */

        /* ── Pagination ── */
        .pagination-parent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* padding: 14px 4px 4px; */
            margin: 16px 0 0;
            flex-wrap: wrap;
            gap: 10px;
        }

        .pagination-parent small {
            font-size: 14px;
        }

        .pagination-parent .pagination {
            margin: 0;
            gap: 6px;
        }

        .pagination .page-link {
            border-radius: 6px !important;
            /* color: #2e7d52; */
            border-color: #dee2e6;
            /* padding: 6px 12px; */
            padding: 0;
            width: 34px;
            height: 34px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination .page-item.disabled {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination .page-item.active .page-link {
            /* background-color: #2e7d52; */
            background-color: var(--neon-cyan);
            /* border-color: #2e7d52; */
            border-color: var(--neon-cyan);
            color: #fff;
            /* color: #fff; */
            font-weight: 600;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: #c0c0c0;
            border-color: #e5e7eb;
        }

        /* .pagination .page-link:hover:not(.active) {
            background-color: #f0fdf4;
            border-color: #2e7d52;
            color: #2e7d52;
        } */

        /* ── Section-pair cards (shared between create & edit modals) ── */
        .section-pair-card {
            background: #f9fafb;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 12px;
            overflow: hidden;
        }

        .section-pair-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: #f1f5f9;
            border-bottom: 1px solid #e9ecef;
        }

        .section-pair-header-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-pair-body {
            padding: 12px 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .pair-pill-label {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            border: 1.5px solid #d1d5db;
            background: #fff;
            font-size: 12px;
            color: #374151;
            cursor: pointer;
            transition: all .15s ease;
            user-select: none;
        }

        .pair-pill-label:hover {
            border-color: #00C950;
            background: #f0fdf4;
        }

        .pair-pill-label input[type=checkbox] {
            display: none;
        }

        .pair-pill-label.checked {
            background: #e8f5e9;
            border-color: #00C950;
            color: #15803d;
            font-weight: 600;
        }

        .pair-pill-label.checked::before {
            content: '✓';
            font-size: 11px;
            color: #00C950;
        }

        .pair-count-badge {
            font-size: 11px;
            color: #6b7280;
            font-weight: 500;
        }

        /* ── Toast Notification ── */
        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-notif {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-radius: 12px;
            padding: 14px 18px;
            min-width: 280px;
            max-width: 380px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #00C950;
            pointer-events: all;
            animation: toastIn .35s cubic-bezier(.34, 1.56, .64, 1) forwards;
            position: relative;
            overflow: hidden;
        }

        .toast-notif.toast-error {
            border-left-color: #e53e3e;
        }

        .toast-notif.toast-hiding {
            animation: toastOut .3s ease forwards;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            color: #00C950;
        }

        .toast-notif.toast-error .toast-icon {
            background: #fff5f5;
            color: #e53e3e;
        }

        .toast-body-text {
            flex: 1;
        }

        .toast-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 2px;
        }

        .toast-msg {
            font-size: 12.5px;
            color: #6b7280;
            margin: 0;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
            line-height: 1;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #374151;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #00C950;
            border-radius: 0 0 0 12px;
            animation: toastProgress 3.5s linear forwards;
            width: 100%;
        }

        .toast-notif.toast-error .toast-progress {
            background: #e53e3e;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(60px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateX(60px) scale(0.9);
            }
        }

        @keyframes toastProgress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c8e6c9;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12.5px;
            font-weight: 600;
        }

        /* Not Active → red/danger */
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12.5px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../admin_folder/sidebar.php") ?>

        <div class="rightbar">

            <!-- MAIN -->
            <main class="main">

                <!-- ══ TOAST CONTAINER ═══════════════════════════════════════ -->
                <div id="toast-container"></div>

                <?php
                $flash = $_SESSION['flash'] ?? null;
                $currentPage = $_GET['url'] ?? '';
                if ($flash && $flash['page'] === $currentPage):
                    unset($_SESSION['flash']);
                    ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            showToast('<?= $flash['type'] ?>', '<?= addslashes(htmlspecialchars($flash['message'])) ?>');
                        });
                    </script>
                <?php elseif ($flash && $flash['page'] !== $currentPage): ?>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <!-- ══ CREATE TEACHER MODAL ══════════════════════════════════ -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title" id="exampleModalLabel">Create New Teacher</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="?url=createTeacher" method="post">
                                <div class="modal-body" style="padding: 16px 20px;">
                                    <div class="row g-3">

                                        <!-- Basic Info -->
                                        <div class="col-lg-12">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Enter full name" required>
                                        </div>
                                        <div class="col-lg-12">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Enter username" required>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted" style="font-size:12px;">
                                                New teacher accounts are created with the default password
                                                <strong>12345678</strong>. The teacher can change it after logging in.
                                            </small>
                                        </div>

                                        <div class="col-12">
                                            <hr>
                                        </div>

                                        <!-- ── Assign Subjects per Section (pairs) ── -->
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Assign Subjects per Section</label>
                                            <p class="text-muted small mb-3">
                                                For each section, check which subjects to assign to this teacher.
                                            </p>

                                            <?php if (!empty($grade12Sections)): ?>
                                                <p class="mb-2"
                                                    style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">
                                                    <span
                                                        style="background:#fff3e0;color:#e65100;border:1px solid #ffe0b2;border-radius:6px;padding:2px 8px;margin-right:6px;">G12</span>
                                                    <?= htmlspecialchars($grade12Sections[0]['grade_name'] ?? 'Grade 12') ?>
                                                </p>
                                                <?php foreach ($grade12Sections as $sec): ?>
                                                    <div class="section-pair-card">
                                                        <div class="section-pair-header">
                                                            <div class="section-pair-header-left">
                                                                <i class="fa fa-users"
                                                                    style="color:#1976d2;font-size:13px;"></i>
                                                                <span style="font-size:13px;font-weight:600;color:#374151;">
                                                                    <?= htmlspecialchars($sec['section_name']) ?>
                                                                </span>
                                                            </div>
                                                            <span class="pair-count-badge sec-count-<?= $sec['id'] ?>">0
                                                                selected</span>
                                                        </div>
                                                        <div class="section-pair-body">
                                                            <?php if (empty($grade12Subjects)): ?>
                                                                <span class="text-muted small">No subjects available.</span>
                                                            <?php else: ?>
                                                                <?php foreach ($grade12Subjects as $subject): ?>
                                                                    <label class="pair-pill-label">
                                                                        <input type="checkbox" name="pairs[<?= $sec['id'] ?>][]"
                                                                            value="<?= $subject['id'] ?>" class="sec-subject-cb"
                                                                            data-section-count-id="<?= $sec['id'] ?>">
                                                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                                                    </label>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php if (!empty($grade11Sections)): ?>
                                                <p class="mb-2 mt-3"
                                                    style="font-size:12px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">
                                                    <span
                                                        style="background:#fff3e0;color:#e65100;border:1px solid #ffe0b2;border-radius:6px;padding:2px 8px;margin-right:6px;">G11</span>
                                                    <?= htmlspecialchars($grade11Sections[0]['grade_name'] ?? 'Grade 11') ?>
                                                </p>
                                                <?php foreach ($grade11Sections as $sec): ?>
                                                    <div class="section-pair-card">
                                                        <div class="section-pair-header">
                                                            <div class="section-pair-header-left">
                                                                <i class="fa fa-users"
                                                                    style="color:#1976d2;font-size:13px;"></i>
                                                                <span style="font-size:13px;font-weight:600;color:#374151;">
                                                                    <?= htmlspecialchars($sec['section_name']) ?>
                                                                </span>
                                                            </div>
                                                            <span class="pair-count-badge sec-count-<?= $sec['id'] ?>">0
                                                                selected</span>
                                                        </div>
                                                        <div class="section-pair-body">
                                                            <?php if (empty($grade11Subjects)): ?>
                                                                <span class="text-muted small">No subjects available.</span>
                                                            <?php else: ?>
                                                                <?php foreach ($grade11Subjects as $subject): ?>
                                                                    <label class="pair-pill-label">
                                                                        <input type="checkbox" name="pairs[<?= $sec['id'] ?>][]"
                                                                            value="<?= $subject['id'] ?>" class="sec-subject-cb"
                                                                            data-section-count-id="<?= $sec['id'] ?>">
                                                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                                                    </label>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <?php if (empty($grade12Sections) && empty($grade11Sections)): ?>
                                                <p class="text-muted small">No sections available.</p>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer"
                                    style="padding: 14px 20px; padding: 14px 20px; border-top: 1px solid #e4e7eb; display: flex; gap: 16px; align-items: center;">
                                    <button type="button" class="btn btn-secondary"
                                        style="background: none; border: 1px solid #e4e7eb;border-radius: 50px; padding: 8px 18px;  font-size: 14.5px; font-weight: 600; color: #6b7280; cursor: pointer;"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" style="font-size: 14.5px;">Create teacher</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ══ EDIT / DETAIL TEACHER MODAL ═══════════════════════════ -->
                <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Edit Teacher Assignment</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form id="editTeacherForm" action="?url=updateTeacher" method="post">
                                <input type="hidden" name="teacher_id" id="edit-teacher-id">

                                <div class="modal-body">

                                    <!-- Teacher info header -->
                                    <div class="teacher-info-header">
                                        <div class="teacher-avatar" id="edit-avatar">T</div>
                                        <div style="display:flex;flex-direction:column;gap:4px;">
                                            <h5 id="edit-teacher-name-display">—</h5>
                                        </div>
                                    </div>

                                    <!-- ── Editable Basic Info ── -->
                                    <div class="row g-3 mb-3">
                                        <div class="col-lg-12">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" id="edit-name" class="form-control"
                                                placeholder="Enter name" required>
                                        </div>

                                        <div class="col-lg-12">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" id="edit-username" class="form-control"
                                                placeholder="Enter username" required>
                                        </div>

                                        <div class="col-lg-12 mt-3">
                                            <label class="form-label">Status</label>
                                            <select name="teacher_status" id="edit-teacher-status" class="form-select">
                                                <option value="Active">Active</option>
                                                <option value="Not Active">Not Active</option>
                                            </select>
                                            <small class="text-muted" style="font-size:12px;">
                                                Setting to "Not Active" will remove all subject assignments from this
                                                teacher.
                                            </small>
                                        </div>
                                    </div>

                                    <hr class="mb-3">

                                    <!-- ── Assign Subjects per Section (same pair-card format as create) ── -->
                                    <label class="form-label fw-semibold">Assign Subjects per Section</label>
                                    <p class="text-muted small mb-3">
                                        For each section, check which subjects to assign to this teacher.
                                    </p>

                                    <!-- Grade 12 section cards -->
                                    <?php if (!empty($grade12Sections)): ?>
                                        <p class="edit-section-label">
                                            <span
                                                style="background:#fff3e0;color:#e65100;border:1px solid #ffe0b2;border-radius:6px;padding:2px 8px;margin-right:6px;">G12</span>
                                            <?= htmlspecialchars($grade12Sections[0]['grade_name'] ?? 'Grade 12') ?>
                                        </p>
                                        <?php foreach ($grade12Sections as $sec): ?>
                                            <div class="section-pair-card">
                                                <div class="section-pair-header">
                                                    <div class="section-pair-header-left">
                                                        <i class="fa fa-users" style="color:#1976d2;font-size:13px;"></i>
                                                        <span style="font-size:13px;font-weight:600;color:#374151;">
                                                            <?= htmlspecialchars($sec['section_name']) ?>
                                                        </span>
                                                    </div>
                                                    <span class="pair-count-badge edit-sec-count-<?= $sec['id'] ?>">0
                                                        selected</span>
                                                </div>
                                                <div class="section-pair-body">
                                                    <?php if (empty($grade12Subjects)): ?>
                                                        <span class="text-muted small">No subjects available.</span>
                                                    <?php else: ?>
                                                        <?php foreach ($grade12Subjects as $subject): ?>
                                                            <label class="pair-pill-label">
                                                                <input type="checkbox" name="pairs[<?= $sec['id'] ?>][]"
                                                                    value="<?= $subject['id'] ?>" class="edit-pair-cb"
                                                                    data-section-id="<?= $sec['id'] ?>"
                                                                    data-subject-id="<?= $subject['id'] ?>">
                                                                <?= htmlspecialchars($subject['subject_name']) ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Grade 11 section cards -->
                                    <?php if (!empty($grade11Sections)): ?>
                                        <p class="edit-section-label mt-3">
                                            <span
                                                style="background:#fff3e0;color:#e65100;border:1px solid #ffe0b2;border-radius:6px;padding:2px 8px;margin-right:6px;">G11</span>
                                            <?= htmlspecialchars($grade11Sections[0]['grade_name'] ?? 'Grade 11') ?>
                                        </p>
                                        <?php foreach ($grade11Sections as $sec): ?>
                                            <div class="section-pair-card">
                                                <div class="section-pair-header">
                                                    <div class="section-pair-header-left">
                                                        <i class="fa fa-users" style="color:#1976d2;font-size:13px;"></i>
                                                        <span style="font-size:13px;font-weight:600;color:#374151;">
                                                            <?= htmlspecialchars($sec['section_name']) ?>
                                                        </span>
                                                    </div>
                                                    <span class="pair-count-badge edit-sec-count-<?= $sec['id'] ?>">0
                                                        selected</span>
                                                </div>
                                                <div class="section-pair-body">
                                                    <?php if (empty($grade11Subjects)): ?>
                                                        <span class="text-muted small">No subjects available.</span>
                                                    <?php else: ?>
                                                        <?php foreach ($grade11Subjects as $subject): ?>
                                                            <label class="pair-pill-label">
                                                                <input type="checkbox" name="pairs[<?= $sec['id'] ?>][]"
                                                                    value="<?= $subject['id'] ?>" class="edit-pair-cb"
                                                                    data-section-id="<?= $sec['id'] ?>"
                                                                    data-subject-id="<?= $subject['id'] ?>">
                                                                <?= htmlspecialchars($subject['subject_name']) ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <?php if (empty($grade12Sections) && empty($grade11Sections)): ?>
                                        <p class="text-muted small">No sections available.</p>
                                    <?php endif; ?>

                                </div><!-- /modal-body -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" style="font-size:14.5px;"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn-save" style="font-size:14.5px;">
                                        <i class="fa fa-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ══ PAGE HEADER ════════════════════════════════════════════ -->
                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Teachers</h2>
                        <p>Manage teacher accounts and assignments</p>
                    </div>
                </div>

                <div class="main-button-header">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa fa-plus me-1"></i> Add Teacher
                    </button>
                </div>

                <!-- ══ STATS CARDS ════════════════════════════════════════════ -->
                <div class="stats-cards-grid">
                    <div class="stat-card">
                        
                        <div class="stat-card-value">
                            <span class="stat-card-title">Total Teachers</span>
                            <h3><?= $teacherStats['total'] ?></h3>
                            <div class="stat-card-desc">All registered teacher accounts</div>
                        </div>
                        <div class="stat-card-top">
                            
                            <span class="stat-icon-box stat-icon-blue"><i class="fa fa-chalkboard-teacher"></i></span>
                        </div>
                        
                    </div>

                    <div class="stat-card">
                        
                        <div class="stat-card-value">
                            <span class="stat-card-title">Active Teachers</span>
                            <h3><?= $teacherStats['active'] ?></h3>
                            <div class="stat-card-desc">Currently has class assignments</div>
                        </div>
                        <div class="stat-card-top">
                            
                            <span class="stat-icon-box stat-icon-green"><i class="fa fa-user-check"></i></span>
                        </div>
                        
                    </div>

                    <div class="stat-card">
                        
                        <div class="stat-card-value">
                            <span class="stat-card-title">Not Active Teachers</span>
                            <h3><?= $teacherStats['inactive'] ?></h3>
                            <div class="stat-card-desc">No current class assignments</div>
                        </div>
                        <div class="stat-card-top">
                            
                            <span class="stat-icon-box stat-icon-red"><i class="fa fa-user-xmark"></i></span>
                        </div>
                        
                    </div>
                </div>

                <!-- ══ MAIN BODY ══════════════════════════════════════════════ -->
                <div class="main-body">

                    <!-- FILTER FORM -->
                    <form method="GET" action="" id="teacher-filter-form">
                        <input type="hidden" name="url" value="teacher_users">
                        <input type="hidden" name="page" id="page-input" value="1">
                        <div class="filter-grid">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="search" name="search" id="teacher-search" class="form-control"
                                    placeholder="Search name..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <select name="grade" id="filter-grade" class="form-select">
                                <option value="">Grade Level</option>
                                <option value="Grade 12" <?= $grade === 'Grade 12' ? 'selected' : '' ?>>Grade 12</option>
                                <option value="Grade 11" <?= $grade === 'Grade 11' ? 'selected' : '' ?>>Grade 11</option>
                            </select>
                            <select name="section" id="filter-section" class="form-select">
                                <option value="">All Sections</option>
                                <?php foreach ($allSectionsFilter as $sec):
                                    $secVal = $sec['section_name'];
                                    $gradeVal = strtolower($sec['grade_name'] ?? '');
                                    $sel = (strtolower($section) === strtolower($secVal)) ? 'selected' : '';
                                    ?>
                                    <option value="<?= htmlspecialchars($secVal) ?>"
                                        data-grade="<?= htmlspecialchars($gradeVal) ?>" <?= $sel ?>>
                                        <?= htmlspecialchars($secVal) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="status" id="filter-status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Active" <?= $status === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="Not Active" <?= $status === 'Not Active' ? 'selected' : '' ?>>Not Active
                                </option>
                            </select>
                        </div>
                    </form>

                    <!-- TABLE -->
                    <div class="table-parent">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Grade Level</th>
                                    <th>Sections</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($teachers)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No teachers found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($teachers as $teacher):
                                        $sectionsList = $teacher['sections'] ?? [];

                                        $gradeSet = [];
                                        foreach ($sectionsList as $secStr) {
                                            $parts = explode(' - ', $secStr, 2);
                                            if (!empty($parts[0]))
                                                $gradeSet[$parts[0]] = true;
                                        }
                                        $gradeLabels = array_keys($gradeSet);

                                        $teacherStatus = $teacher['status_label'] ?? (((int) $teacher['class_count'] > 0) ? 'Active' : 'Not Active');
                                        $isActive = ($teacherStatus === 'Active');

                                        $sectionNamesFlat = implode('|', array_map('strtolower', $sectionsList));
                                        $gradeNamesFlat = implode('|', array_map('strtolower', $gradeLabels));

                                        $assignedSubjectIds = array_column($teacher['subjects'] ?? [], 'id');

                                        $assignedSectionNames = [];
                                        foreach ($sectionsList as $s) {
                                            $parts = explode(' - ', $s, 2);
                                            if (!empty($parts[1]))
                                                $assignedSectionNames[] = strtolower(trim($parts[1]));
                                        }
                                        $assignedSectionIds = [];
                                        foreach ($allSectionsFilter as $asec) {
                                            if (in_array(strtolower($asec['section_name']), $assignedSectionNames)) {
                                                $assignedSectionIds[] = $asec['id'];
                                            }
                                        }

                                        // Build pairs: section_id => [subject_ids]
                                        // Use controller-provided pairs if available,
                                        // otherwise fall back: every assigned section gets all assigned subjects
                                        $teacherPairs = $teacher['pairs'] ?? [];
                                        if (empty($teacherPairs) && !empty($assignedSectionIds) && !empty($assignedSubjectIds)) {
                                            foreach ($assignedSectionIds as $sid) {
                                                $teacherPairs[(string) $sid] = array_map('intval', $assignedSubjectIds);
                                            }
                                        }
                                        ?>
                                        <tr class="teachers-data"
                                            data-name="<?= htmlspecialchars(strtolower($teacher['name'])) ?>"
                                            data-sections="<?= htmlspecialchars($sectionNamesFlat) ?>"
                                            data-grades="<?= htmlspecialchars($gradeNamesFlat) ?>"
                                            data-status="<?= htmlspecialchars(strtolower($teacherStatus)) ?>">

                                            <td><?= htmlspecialchars($teacher['name']) ?></td>
                                            <td><?= htmlspecialchars($teacher['username'] ?? '—') ?></td>

                                            <!-- GRADE LEVEL -->
                                            <td class="tags-cell">
                                                <?php if (empty($gradeLabels)): ?>
                                                    <span class="text-muted small">—</span>
                                                <?php else: ?>
                                                    <div class="tags-overflow-wrap">
                                                        <?php foreach ($gradeLabels as $gl): ?>
                                                            <span class="tag-chip grade"><?= htmlspecialchars($gl) ?></span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <!-- SECTIONS -->
                                            <td class="tags-cell">
                                                <?php if (empty($sectionsList)): ?>
                                                    <span class="text-muted small">—</span>
                                                <?php else:
                                                    $shownSec = array_slice($sectionsList, 0, 2);
                                                    $hiddenSec = count($sectionsList) - 2;
                                                    ?>
                                                    <div class="tags-overflow-wrap">
                                                        <?php foreach ($shownSec as $secItem):
                                                            $secParts = explode(' - ', $secItem, 2);
                                                            $secLabel = $secParts[1] ?? $secItem;
                                                            ?>
                                                            <span class="tag-chip section"><?= htmlspecialchars($secLabel) ?></span>
                                                        <?php endforeach; ?>
                                                        <?php if ($hiddenSec > 0): ?>
                                                            <span class="more-badge" data-teacher-id="<?= $teacher['teacher_id'] ?>">
                                                                +<?= $hiddenSec ?> more
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>

                                            <!-- STATUS -->
                                            <td>
                                                <span class="<?= $isActive ? 'status-active' : 'status-inactive' ?>">
                                                    <?= $teacherStatus ?>
                                                </span>
                                            </td>

                                            <!-- ACTIONS -->
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-teacher"
                                                    data-teacher-id="<?= $teacher['teacher_id'] ?>"
                                                    data-teacher-name="<?= htmlspecialchars($teacher['name']) ?>"
                                                    data-teacher-username="<?= htmlspecialchars($teacher['username'] ?? '') ?>"
                                                    data-teacher-status="<?= htmlspecialchars($teacherStatus) ?>"
                                                    data-subject-ids='<?= json_encode(array_map('intval', $assignedSubjectIds)) ?>'
                                                    data-section-ids='<?= json_encode(array_map('intval', $assignedSectionIds)) ?>'
                                                    data-pairs='<?= json_encode($teacherPairs) ?>'
                                                    data-sections-full='<?= json_encode($sectionsList) ?>'>
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div><!-- /main-body -->

                <!-- PAGINATION -->
                <div class="pagination-parent">
                    <small class="text-muted">
                        <?php if ($totalTeachers > 0): ?>
                            Showing
                            <?= min($offset + $limit, $totalTeachers) ?> of
                            <?= $totalTeachers ?> teachers
                        <?php else: ?>
                            No teachers found
                        <?php endif; ?>
                    </small>
                    <ul class="pagination">
                        <?php
                        $filterParams = http_build_query([
                            'url' => 'teacher_users',
                            'search' => $search,
                            'grade' => $grade,
                            'section' => $section,
                            'status' => $status,
                        ]);
                        ?>
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page - 1 ?>">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php
                        $displayPages = max(1, $totalPages);
                        for ($i = 1; $i <= $displayPages; $i++):
                            ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= $filterParams ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $displayPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page + 1 ?>">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            </main>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ═══════════════════════════════════════════════════════════
           TOAST SYSTEM
           ═══════════════════════════════════════════════════════════ */
        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            const isError = type === 'error';

            const toast = document.createElement('div');
            toast.className = 'toast-notif' + (isError ? ' toast-error' : '');
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fa ${isError ? 'fa-times-circle' : 'fa-check-circle'}"></i>
                </div>
                <div class="toast-body-text">
                    <p class="toast-title">${isError ? 'Error' : 'Success'}</p>
                    <p class="toast-msg">${escHtml(message)}</p>
                </div>
                <button class="toast-close" onclick="dismissToast(this.parentElement)">
                    <i class="fa fa-times"></i>
                </button>
            `;
            container.appendChild(toast);

            // Auto-dismiss after 3.5s
            setTimeout(() => dismissToast(toast), 3500);
        }

        function dismissToast(toast) {
            if (!toast || toast.classList.contains('toast-hiding')) return;
            toast.classList.add('toast-hiding');
            setTimeout(() => toast.remove(), 300);
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }

        /* ═══════════════════════════════════════════════════════════
           MAIN LOGIC
           ═══════════════════════════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', function () {

            /* ─── 1. FILTER LOGIC ─────────────────────────────────── */
            const form = document.getElementById('teacher-filter-form');
            const filterGrade = document.getElementById('filter-grade');
            const filterSection = document.getElementById('filter-section');
            const filterStatus = document.getElementById('filter-status');
            const searchInput = document.getElementById('teacher-search');

            syncSectionDropdown(filterGrade.value);

            filterGrade.addEventListener('change', function () {
                syncSectionDropdown(this.value);
                filterSection.value = '';
                form.submit();
            });
            filterSection.addEventListener('change', () => form.submit());
            filterStatus.addEventListener('change', () => form.submit());
            searchInput.addEventListener('input', debounce(() => form.submit(), 450));

            function syncSectionDropdown(selectedGrade) {
                const sel = selectedGrade.toLowerCase();
                filterSection.querySelectorAll('option').forEach(opt => {
                    if (!opt.value) return;
                    const optGrade = (opt.dataset.grade || '').toLowerCase();
                    opt.style.display = (!sel || optGrade === sel) ? '' : 'none';
                });
            }

            function applyLocalFilter() {
                const q = searchInput.value.trim().toLowerCase();
                const grade = filterGrade.value.trim().toLowerCase();
                const sec = filterSection.value.trim().toLowerCase();
                const status = filterStatus.value.trim().toLowerCase();

                document.querySelectorAll('tr.teachers-data').forEach(row => {
                    const name = (row.dataset.name || '').toLowerCase();
                    const grades = (row.dataset.grades || '').toLowerCase();
                    const sections = (row.dataset.sections || '').toLowerCase();
                    const rowStat = (row.dataset.status || '').toLowerCase();

                    const matchQ = !q || name.includes(q);
                    const matchGrade = !grade || grades.split('|').some(g => g.trim() === grade);
                    const matchSec = !sec || sections.split('|').some(s => {
                        const parts = s.split(' - ');
                        return (parts[1] || '').trim() === sec;
                    });
                    const matchStatus = !status || rowStat === status;

                    row.style.display = (matchQ && matchGrade && matchSec && matchStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', applyLocalFilter);
            filterGrade.addEventListener('change', applyLocalFilter);
            filterSection.addEventListener('change', applyLocalFilter);
            filterStatus.addEventListener('change', applyLocalFilter);

            /* ─── 2. CREATE MODAL — pair pill toggles + count ───────── */
            document.querySelectorAll('.sec-subject-cb').forEach(cb => {
                cb.addEventListener('change', function () {
                    this.closest('.pair-pill-label').classList.toggle('checked', this.checked);
                    const sectionId = this.dataset.sectionCountId;
                    const count = document.querySelectorAll(
                        `input.sec-subject-cb[data-section-count-id="${sectionId}"]:checked`
                    ).length;
                    const badge = document.querySelector(`.sec-count-${sectionId}`);
                    if (badge) badge.textContent = count + ' selected';
                });
            });

            // Reset create modal when closed
            document.getElementById('exampleModal').addEventListener('hidden.bs.modal', function () {
                this.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    cb.checked = false;
                    cb.closest('.pair-pill-label')?.classList.remove('checked');
                });
                document.querySelectorAll('#exampleModal .pair-count-badge').forEach(badge => {
                    badge.textContent = '0 selected';
                });
            });

            /* ─── 3. EDIT MODAL — pair pill toggles + count ────────── */
            const editModal = new bootstrap.Modal(document.getElementById('editTeacherModal'));

            // Live toggle for edit checkboxes
            document.querySelectorAll('.edit-pair-cb').forEach(cb => {
                cb.addEventListener('change', function () {
                    this.closest('.pair-pill-label').classList.toggle('checked', this.checked);
                    const sectionId = this.dataset.sectionId;
                    updateEditSectionCount(sectionId);
                });
            });

            function updateEditSectionCount(sectionId) {
                const count = document.querySelectorAll(
                    `.edit-pair-cb[data-section-id="${sectionId}"]:checked`
                ).length;
                const badge = document.querySelector(`.edit-sec-count-${sectionId}`);
                if (badge) badge.textContent = count + ' selected';
            }

            // Reset all edit checkboxes
            function resetEditModal() {
                document.querySelectorAll('.edit-pair-cb').forEach(cb => {
                    cb.checked = false;
                    cb.closest('.pair-pill-label')?.classList.remove('checked');
                });
                document.querySelectorAll('#editTeacherModal .pair-count-badge').forEach(badge => {
                    badge.textContent = '0 selected';
                });
            }

            document.getElementById('editTeacherModal').addEventListener('hidden.bs.modal', resetEditModal);

            /* Open edit modal — populate from teacher data */
            document.addEventListener('click', function (e) {

                // Clicking "+N more" badge opens the edit modal
                if (e.target.closest('.more-badge')) {
                    const badge = e.target.closest('.more-badge');
                    const teacherId = badge.dataset.teacherId;
                    const editBtn = document.querySelector(`.btn-edit-teacher[data-teacher-id="${teacherId}"]`);
                    if (editBtn) editBtn.click();
                    return;
                }

                const btn = e.target.closest('.btn-edit-teacher');
                if (!btn) return;

                const teacherName = btn.dataset.teacherName;
                // pairs: { "section_id": [subject_id, ...], ... }
                const pairs = JSON.parse(btn.dataset.pairs || '{}');

                // Set header info
                document.getElementById('edit-teacher-id').value = btn.dataset.teacherId;
                document.getElementById('edit-teacher-name-display').textContent = teacherName;
                document.getElementById('edit-avatar').textContent = teacherName.trim().charAt(0).toUpperCase();

                // Populate editable fields
                document.getElementById('edit-name').value = teacherName;
                document.getElementById('edit-username').value = btn.dataset.teacherUsername || '';

                const teacherStatus = btn.dataset.teacherStatus || 'Active';
                document.getElementById('edit-teacher-status').value = teacherStatus;

                // Reset first
                resetEditModal();

                // Pre-check boxes based on pairs data
                document.querySelectorAll('.edit-pair-cb').forEach(cb => {
                    const secId = String(cb.dataset.sectionId);
                    const subjId = parseInt(cb.dataset.subjectId);
                    // pairs keys may be strings or numbers — normalise
                    const secPairs = (pairs[secId] || pairs[parseInt(secId)] || []).map(Number);
                    const checked = secPairs.includes(subjId);
                    cb.checked = checked;
                    cb.closest('.pair-pill-label').classList.toggle('checked', checked);
                });

                // Update all count badges
                const allSectionIds = [...new Set(
                    [...document.querySelectorAll('.edit-pair-cb')].map(cb => cb.dataset.sectionId)
                )];
                allSectionIds.forEach(sid => updateEditSectionCount(sid));

                editModal.show();
            });

            /* ─── 4. FORM SUBMIT TOASTS ─────────────────────────────── */
            // Create form
            document.querySelector('#exampleModal form').addEventListener('submit', function () {
                // Toast fires after page reload via PHP session — handled by PHP block at top
                // But if you want an optimistic toast before submit, uncomment:
                // showToast('success', 'Creating teacher...');
            });

            // Edit form
            document.getElementById('editTeacherForm').addEventListener('submit', function () {
                // Toast fires after page reload via PHP session — handled by PHP block at top
            });

            // Update avatar letter live when name changes
            document.getElementById('edit-name').addEventListener('input', function () {
                const first = this.value.trim().charAt(0).toUpperCase();
                document.getElementById('edit-avatar').textContent = first || 'T';
                document.getElementById('edit-teacher-name-display').textContent = this.value.trim() || '—';
            });

            /* ─── 5. UTILITIES ──────────────────────────────────────── */
            function debounce(fn, delay) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), delay);
                };
            }
        });
    </script>
</body>

</html>