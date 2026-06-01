<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/student_records.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c8e6c9;
        }

        .status-pending {
            background: #fff3e0;
            color: #e65100;
            border: 1px solid #ffe0b2;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
    </style>
</head>


<body>


    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">

            <?php include("../super_admin_folder/nav.php") ?>
            <!-- MAIN -->
            <main class="main">



                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Students</h2>
                        <p>Manage students accounts and approval</p>
                    </div>
                    <div class="main-button-header">
                    </div>
                </div>

                <div class="main-body">

                    <div class="card-parent-box">
                        <div class="card-box">
                            <div class="card-text">
                                <span>Pendings</span>
                                <p><?= $totalPending ?></p>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-clock"></i>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-text">
                                <span>Approved</span>
                                <p><?= $totalApproved ?></p>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>

                        <div class="card-box">
                            <div class="card-text">
                                <span>Rejected</span>
                                <p><?= $totalDeclined ?></p>
                            </div>
                            <div class="card-icon">
                                <i class="fa fa-times-circle"></i>
                            </div>
                        </div>
                    </div>

                    <form method="GET" action="" id="filter-form">
                        <input type="hidden" name="url" value="super_admin_student_users">
                        <input type="hidden" name="page" id="page-input" value="1">

                        <div class="search-parent">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="search" name="search" id="search-input" class="form-control"
                                    placeholder="Search name, email, or LRN..."
                                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>

                            <select name="grade" id="filter-grade" class="form-select">
                                <option value="">Grade Level</option>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= strtolower(htmlspecialchars($gl['name'])) ?>"
                                        <?= (strtolower($_GET['grade'] ?? '') === strtolower($gl['name'])) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($gl['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="section" id="filter-section" class="form-select">
                                <option value="">All Sections</option>
                                <?php foreach ($allSections as $sec): ?>
                                    <option value="<?= strtolower(htmlspecialchars($sec['section_name'])) ?>"
                                        data-grade="<?= strtolower(htmlspecialchars($sec['grade_name'])) ?>"
                                        <?= (strtolower($_GET['section'] ?? '') === strtolower($sec['section_name'])) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sec['section_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="status" id="filter-status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" <?= (($_GET['status'] ?? '') === 'pending') ? 'selected' : '' ?>>
                                    Pending</option>
                                <option value="approved" <?= (($_GET['status'] ?? '') === 'approved') ? 'selected' : '' ?>>
                                    Approved</option>
                                <option value="rejected" <?= (($_GET['status'] ?? '') === 'rejected') ? 'selected' : '' ?>>
                                    Rejected</option>
                            </select>

                            <button type="button" id="bulkApproveBtn" onclick="bulkApprove()" disabled style="background:#c0c0c0; color:#fff; border:none; border-radius:8px; 
                                    padding:9px 5px; font-size:13.5px; font-weight:700; cursor:not-allowed;
                                    white-space:nowrap; transition:background .2s;">
                                <i class="fa fa-check"></i> Approve Selected
                            </button>
                        </div>
                    </form>

                    <!-- TABLE -->
                    <div class="table-parent">
                        <table class="table">
                            <thead>
                                <tr style="background-color: #ddd;">
                                    <?php
                                    // Count pending students on the CURRENT page only
                                    $pendingOnPage = count(array_filter($students, fn($s) => strtolower($s['status']) === 'pending'));
                                    ?>
                                    <th id="bulk-th" style="width:40px;">
                                        <?php if ($pendingOnPage > 0): ?>
                                            <input type="checkbox" id="selectAllPending" title="Select all pending">
                                        <?php else: ?>
                                            <input type="checkbox" id="selectAllPending" title="Select all pending"
                                                style="display:none;">
                                        <?php endif; ?>
                                    </th>
                                    <th>Student LRN</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">No students found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr class="students-data" data-lrn="<?= htmlspecialchars(strtolower($student['id'])) ?>"
                                            data-lrn="<?= htmlspecialchars(strtolower($student['student_LRN'])) ?>"
                                            data-name="<?= htmlspecialchars(strtolower($student['name'])) ?>"
                                            data-email="<?= htmlspecialchars(strtolower($student['email'])) ?>"
                                            data-grade="<?= htmlspecialchars(strtolower($student['grade_level'])) ?>"
                                            data-section="<?= htmlspecialchars(strtolower($student['section_name'])) ?>"
                                            data-status="<?= htmlspecialchars(strtolower($student['status'])) ?>">
                                            <td>
                                                <?php if (strtolower($student['status']) === 'pending'): ?>
                                                    <input type="checkbox" class="pending-cb" value="<?= $student['student_id'] ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($student['student_LRN']) ?></td>
                                            <td><?= htmlspecialchars($student['name']) ?></td>
                                            <td><?= htmlspecialchars($student['email']) ?></td>
                                            <td><?= htmlspecialchars($student['grade_level']) ?></td>
                                            <td><?= htmlspecialchars($student['section_name']) ?></td>
                                            <td>
                                                <span
                                                    class="status-badge status-<?= strtolower(htmlspecialchars($student['status'])) ?>">
                                                    <?= htmlspecialchars($student['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-student"
                                                    data-student-id="<?= $student['student_id'] ?>"
                                                    data-user-id="<?= $student['user_id'] ?>"
                                                    data-name="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>"
                                                    data-email="<?= htmlspecialchars($student['email'], ENT_QUOTES) ?>"
                                                    data-status="<?= htmlspecialchars($student['status'], ENT_QUOTES) ?>"
                                                    data-grade-level-id="<?= $student['grade_level_id'] ?>"
                                                    data-section-id="<?= $student['section_id'] ?>"
                                                    data-lrn="<?= htmlspecialchars($student['student_LRN'], ENT_QUOTES) ?>"
                                                    data-reason="<?= htmlspecialchars($student['reason'] ?? '', ENT_QUOTES) ?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-parent">
                            <small class="text-muted">
                                Showing <?= $offset + 1 ?> - <?= min($offset + $limit, $totalStudents) ?> of
                                <?= $totalStudents ?> students
                            </small>
                            <ul class="pagination">

                                <?php
                                $filterParams = http_build_query([
                                    'url' => 'super_admin_student_users',
                                    'search' => $_GET['search'] ?? '',
                                    'grade' => $_GET['grade'] ?? '',
                                    'section' => $_GET['section'] ?? '',
                                    'status' => $_GET['status'] ?? '',
                                ]);
                                ?>

                                <!-- Prev -->
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page - 1 ?>">
                                        <i class="fa fa-chevron-left"></i>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= $filterParams ?>&page=<?= $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next -->
                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page + 1 ?>">
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

            </main>
        </div>
    </div>

    <!-- EDIT STUDENT MODAL -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user-edit me-2"></i>Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/learning_management/public/?url=update_super_admin_Student">
                    <div class="modal-body">

                        <input type="hidden" name="student_id" id="edit_student_id">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <input type="hidden" name="original_status" id="original_status">

                        <input type="hidden" name="grade_level_id" id="hidden_grade_level_id">
                        <input type="hidden" name="section_id" id="hidden_section_id">

                        <input type="hidden" name="status" id="hidden_status">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">LRN</label>
                            <input type="text" name="student_LRN" id="edit_lrn" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select id="edit_grade_level" class="form-select" required>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= $gl['id'] ?>">
                                        <?= htmlspecialchars($gl['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Section</label>
                            <select id="edit_section" class="form-select" required>
                                <?php foreach ($allSections as $sec): ?>
                                    <option value="<?= $sec['id'] ?>" data-grade="<?= $sec['grade_level_id'] ?>">
                                        <?= htmlspecialchars($sec['grade_name'] . ' - ' . $sec['section_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Status — shown always but Approve/Reject buttons only if Pending -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="edit_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>

                        <!-- Reason (shown only when Rejected) -->
                        <div class="mb-3" id="reason-display-box" style="display:none;">
                            <label class="form-label fw-semibold" style="color:#991b1b;">
                                <!-- <i class="fa fa-exclamation-circle me-1" style="color:#e53e3e;"></i> -->
                                Reason for Rejection
                            </label>
                            <div id="edit_reason_display" style="background:#fff5f5; border:1px solid #fecdd3; border-radius:8px;
               padding:12px 16px; font-size:13.5px; color:#374151; line-height:1.6;">
                            </div>
                        </div>

                        <!-- Quick Approve / Reject buttons (visible only when Pending) -->
                        <div id="approval-actions" class="d-none">
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-success w-50" id="btn-approve">
                                    <i class="fa fa-check me-1"></i> Approve
                                </button>
                                <button type="button" class="btn btn-danger w-50" id="btn-reject">
                                    <i class="fa fa-times me-1"></i> Reject
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer" id="footer-pending" style="display:none;">
                        <!-- empty — no buttons when pending, actions are above -->
                    </div>

                    <div class="modal-footer" id="footer-edit">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn"
                            style="background-color: var(--green); color: #ffffff; font-weight: 600;">
                            <i class="fa fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- DECLINE CONFIRMATION MODAL -->
    <div class="modal fade" id="declineConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:#fff5f5; border-bottom: 1px solid #fecdd3;">
                    <h5 class="modal-title" style="color:#991b1b;">
                        <i class="fa fa-exclamation-triangle me-2" style="color:#e53e3e;"></i>
                        Reject Student Account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-3 mb-3 p-3"
                        style="background:#fff5f5; border-radius:10px; border:1px solid #fecdd3;">
                        <div style="width:42px;height:42px;border-radius:50%;background:#fee2e2;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa fa-user" style="color:#e53e3e;font-size:18px;"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold" id="decline-student-name" style="color:#1f2937;"></p>
                            <p class="mb-0" style="font-size:12px;color:#6b7280;" id="decline-student-email"></p>
                        </div>
                    </div>

                    <p style="font-size:13.5px;color:#374151;margin-bottom:12px;">
                        Please provide a reason for rejected this student's account.
                        This will be included in the notification email sent to the student.
                    </p>

                    <label class="form-label fw-semibold" style="font-size:13px;color:#374151;">
                        Reason for Rejected <span style="color:#e53e3e;">*</span>
                    </label>
                    <textarea id="decline-reason-input" class="form-control" rows="4"
                        placeholder="e.g. Incomplete registration information, Invalid LRN, Duplicate account..."
                        style="font-size:13px; resize:vertical; border-color:#fca5a5;" maxlength="500"></textarea>
                    <div class="d-flex justify-content-between mt-1">
                        <small id="decline-reason-error" class="text-danger" style="display:none;">
                            <i class="fa fa-exclamation-circle me-1"></i>Please enter a reason before rejection.
                        </small>
                        <small class="text-muted ms-auto" id="reason-char-count">0 / 500</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        <i class="fa fa-arrow-left me-1"></i> Go Back
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" id="btn-confirm-decline">
                        <i class="fa fa-times me-1"></i> Confirm Rejection
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pendingCbs = () => document.querySelectorAll('.pending-cb');
        const selectAll = document.getElementById('selectAllPending');
        const bulkApproveBtn = document.getElementById('bulkApproveBtn');
        let allPendingIds = []; // ✅ declare it here

        if (selectAll) {
            selectAll.addEventListener('change', async function () {
                if (this.checked) {
                    // Fetch ALL pending IDs across all pages
                    try {
                        const res = await fetch('/learning_management/public/?url=get_all_pending_ids');
                        const data = await res.json();
                        allPendingIds = data.ids.map(String);
                    } catch (e) {
                        allPendingIds = [];
                    }
                    pendingCbs().forEach(cb => cb.checked = true);
                } else {
                    allPendingIds = [];
                    pendingCbs().forEach(cb => cb.checked = false);
                }
                updateBulkBtn();
            });
        }

        function updateBulkBtn() {
            const visibleChecked = [...pendingCbs()].filter(cb => cb.checked).map(cb => cb.value);

            // Remove unchecked visible ones from allPendingIds
            pendingCbs().forEach(cb => {
                if (!cb.checked) {
                    allPendingIds = allPendingIds.filter(id => id !== cb.value);
                }
            });

            const finalIds = [...new Set([...allPendingIds, ...visibleChecked])];

            if (finalIds.length > 0) {
                bulkApproveBtn.disabled = false;
                bulkApproveBtn.style.background = 'var(--green)';
                bulkApproveBtn.style.cursor = 'pointer';
                bulkApproveBtn.innerHTML = '<i class="fa fa-check"></i> Approve Selected (' + finalIds.length + ')';
            } else {
                bulkApproveBtn.disabled = true;
                bulkApproveBtn.style.background = '#c0c0c0';
                bulkApproveBtn.style.cursor = 'not-allowed';
                bulkApproveBtn.innerHTML = '<i class="fa fa-check"></i> Approve Selected';
            }

            if (selectAll) {
                selectAll.checked = finalIds.length > 0 && [...pendingCbs()].every(cb => cb.checked);
                selectAll.indeterminate = visibleChecked.length > 0 && !selectAll.checked;
            }
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('pending-cb')) {
                updateBulkBtn();
            }
        });

        function clearSelection() {
            allPendingIds = [];
            pendingCbs().forEach(cb => cb.checked = false);
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            updateBulkBtn();
        }

        function bulkApprove() {
            const visibleChecked = [...pendingCbs()].filter(cb => cb.checked).map(cb => cb.value);
            const ids = [...new Set([...allPendingIds, ...visibleChecked])];

            if (ids.length === 0) return;

            bulkApproveBtn.disabled = true;
            bulkApproveBtn.style.background = '#c0c0c0';
            bulkApproveBtn.style.cursor = 'not-allowed';

            const fd = new FormData();
            ids.forEach(id => fd.append('student_ids[]', id));

            fetch('/learning_management/public/?url=bulk_approve_students', {
                method: 'POST',
                body: fd
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        allPendingIds = [];
                        showToast('success', ids.length + ' student(s) approved successfully!');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast('error', data.message || 'Bulk approve failed.');
                        updateBulkBtn();
                    }
                })
                .catch(() => {
                    showToast('error', 'Network error. Please try again.');
                    updateBulkBtn();
                });
        }
    </script>

    <script>
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
    </script>

    <!-- TOAST CONTAINER -->
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filter-form');
            const filterGrade = document.getElementById('filter-grade');
            const filterSection = document.getElementById('filter-section');

            // On page load, filter section options to match current grade selection
            filterSectionOptions(filterGrade.value);

            filterGrade.addEventListener('change', function () {
                filterSectionOptions(this.value);
                // Reset section to "All Sections" when grade changes
                filterSection.value = '';
                document.getElementById('page-input').value = 1;
                form.submit();
            });

            filterSection.addEventListener('change', function () {
                document.getElementById('page-input').value = 1;
                form.submit();
            });

            document.getElementById('filter-status').addEventListener('change', function () {
                document.getElementById('page-input').value = 1;
                form.submit();
            });

            document.getElementById('search-input').addEventListener('input', debounce(() => {
                document.getElementById('page-input').value = 1;
                form.submit();
            }, 400));

            function filterSectionOptions(selectedGrade) {
                const options = filterSection.querySelectorAll('option');
                options.forEach(opt => {
                    if (!opt.value) return; // keep "All Sections"
                    if (!selectedGrade || opt.dataset.grade === selectedGrade) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                    }
                });
            }

            function debounce(fn, delay) {
                let timer;
                return function (...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
            const allSections = <?= json_encode($allSections) ?>;

            document.querySelectorAll('.btn-edit-student').forEach(btn => {
                btn.addEventListener('click', function () {
                    const studentId = this.dataset.studentId;
                    const userId = this.dataset.userId;
                    const name = this.dataset.name;
                    const email = this.dataset.email;
                    const status = this.dataset.status;
                    const gradeLevelId = this.dataset.gradeLevelId;
                    const sectionId = this.dataset.sectionId;
                    const lrn = this.dataset.lrn;
                    const reason = this.dataset.reason ?? '';

                    document.getElementById('edit_student_id').value = studentId;
                    document.getElementById('edit_user_id').value = userId;
                    document.getElementById('original_status').value = status;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_lrn').value = lrn;
                    document.getElementById('edit_grade_level').value = gradeLevelId;

                    document.getElementById('hidden_grade_level_id').value = gradeLevelId;
                    document.getElementById('hidden_section_id').value = sectionId;

                    filterSections(gradeLevelId, sectionId);

                    document.getElementById('edit_status').value = status;
                    toggleApprovalButtons(status, reason);

                    editModal.show();
                });
            });

            document.getElementById('edit_grade_level').addEventListener('change', function () {
                filterSections(this.value, null);
            });

            function filterSections(gradeLevelId, selectedSectionId) {
                const select = document.getElementById('edit_section');
                select.innerHTML = '';
                allSections
                    .filter(sec => sec.grade_level_id == gradeLevelId)
                    .forEach(sec => {
                        const opt = document.createElement('option');
                        opt.value = sec.id;
                        opt.textContent = sec.section_name;
                        if (sec.id == selectedSectionId) opt.selected = true;
                        select.appendChild(opt);
                    });
            }

            function toggleApprovalButtons(status, reason = '') {
                const approvalBox = document.getElementById('approval-actions');
                const footerEdit = document.getElementById('footer-edit');
                const reasonBox = document.getElementById('reason-display-box');
                const reasonDisplay = document.getElementById('edit_reason_display');

                const isPending = status === 'Pending';
                const isApproved = status === 'Approved';
                const isRejected = status === 'Rejected';
                const isLocked = isApproved || isRejected;

                document.getElementById('hidden_status').value = status;

                approvalBox.classList.toggle('d-none', !isPending);

                if (isPending || isRejected) {
                    footerEdit.style.display = 'none';
                } else {
                    footerEdit.style.display = 'flex';
                }

                // Show reason box only when Rejected and reason exists
                if (isRejected && reason) {
                    reasonBox.style.display = 'block';
                    reasonDisplay.textContent = reason;
                } else {
                    reasonBox.style.display = 'none';
                    reasonDisplay.textContent = '';
                }

                const fields = ['edit_name', 'edit_email', 'edit_lrn'];
                fields.forEach(id => {
                    const el = document.getElementById(id);
                    el.readOnly = isPending || isRejected;
                    el.style.backgroundColor = (isPending || isRejected) ? '#f0f0f0' : '';
                    el.style.cursor = (isPending || isRejected) ? 'not-allowed' : '';
                });

                ['edit_grade_level', 'edit_section'].forEach(id => {
                    const el = document.getElementById(id);
                    el.disabled = isPending || isRejected;
                    el.style.backgroundColor = (isPending || isRejected) ? '#f0f0f0' : '';
                    el.style.cursor = (isPending || isRejected) ? 'not-allowed' : '';
                });

                const statusEl = document.getElementById('edit_status');
                statusEl.disabled = isPending || isLocked;
                statusEl.style.backgroundColor = (isPending || isLocked) ? '#f0f0f0' : '';
                statusEl.style.cursor = (isPending || isLocked) ? 'not-allowed' : '';
            }

            // Approve — auto submit
            document.getElementById('btn-approve').addEventListener('click', function () {
                document.getElementById('edit_status').disabled = false;
                document.getElementById('edit_grade_level').disabled = false;
                document.getElementById('edit_section').disabled = false;
                document.getElementById('edit_status').value = 'Approved';
                document.getElementById('hidden_status').value = 'Approved';
                this.closest('form').submit();
            });

            // Reject button — show confirmation modal first
            document.getElementById('btn-reject').addEventListener('click', function () {
                const name = document.getElementById('edit_name').value;
                const email = document.getElementById('edit_email').value;

                document.getElementById('decline-student-name').textContent = name;
                document.getElementById('decline-student-email').textContent = email;
                document.getElementById('decline-reason-input').value = '';
                document.getElementById('reason-char-count').textContent = '0 / 500';
                document.getElementById('decline-reason-error').style.display = 'none';

                bootstrap.Modal.getInstance(document.getElementById('editStudentModal')).hide();
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('declineConfirmModal')).show();
                }, 300);
            });

            // Character counter
            document.getElementById('decline-reason-input').addEventListener('input', function () {
                const len = this.value.length;
                document.getElementById('reason-char-count').textContent = len + ' / 500';
                if (len > 0) {
                    document.getElementById('decline-reason-error').style.display = 'none';
                }
            });

            // Confirm rejection
            document.getElementById('btn-confirm-decline').addEventListener('click', function () {
                const reason = document.getElementById('decline-reason-input').value.trim();
                if (!reason) {
                    document.getElementById('decline-reason-error').style.display = 'inline';
                    document.getElementById('decline-reason-input').focus();
                    return;
                }

                let reasonField = document.getElementById('hidden_decline_reason');
                if (!reasonField) {
                    reasonField = document.createElement('input');
                    reasonField.type = 'hidden';
                    reasonField.name = 'decline_reason';
                    reasonField.id = 'hidden_decline_reason';
                    document.querySelector('#editStudentModal form').appendChild(reasonField);
                }
                reasonField.value = reason;

                document.getElementById('edit_status').disabled = false;
                document.getElementById('edit_grade_level').disabled = false;
                document.getElementById('edit_section').disabled = false;
                document.getElementById('edit_status').value = 'Rejected';
                document.getElementById('hidden_status').value = 'Rejected';

                bootstrap.Modal.getInstance(document.getElementById('declineConfirmModal')).hide();
                document.querySelector('#editStudentModal form').submit();
            });

            // Re-check when status manually changed
            document.getElementById('edit_status').addEventListener('change', function () {
                document.getElementById('hidden_status').value = this.value;
                toggleApprovalButtons(this.value);
            });

        });
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>