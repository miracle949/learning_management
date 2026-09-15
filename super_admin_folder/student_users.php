<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="../css_folder/student_records.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
        /* -- Toast Notification -- */
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

        .pagination-parent small {
            font-size: 13px;
        }

        /* Subtle loading state while a filter/search is in flight */
        .table-parent.is-loading {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity .15s ease;
        }

        /* -- Stat cards -- */
        .ml-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 1.5rem 0;
            /* margin-bottom: 1.5rem; */
        }

        @media (max-width: 768px) {
            .ml-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .ml-stat-card {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .ml-stat-icon {
            width: 32px;
            height: 32px;
            background: rgba(0, 119, 204, 0.08);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ml-stat-icon .fa {
            font-size: 15px;
            color: var(--neon-cyan);
        }

        .ml-stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 10px 0 0;
        }

        .ml-stat-label {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-dim);
        }

        .ml-stat-desc {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-dim);
            margin: 4px 0 0;
        }

        .search-parent-enrolled {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background-color: #ffffff;
            border-radius: 10px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            margin: 1.5rem 0;
        }

        @media (max-width: 900px) {
            .search-parent-enrolled {
                flex-wrap: wrap;
            }
        }
    </style>
</head>


<body>


    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <!-- MAIN -->
            <main class="main">

                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Students</h2>
                        <p>Manage student accounts</p>
                    </div>
                    <div class="main-button-header">
                    </div>
                </div>

                <div class="main-body">

                    <!-- STUDENT STATS -->
                    <div class="ml-stats-grid">
                        <div class="ml-stat-card">
                            <div>
                                <div class="ml-stat-label">Total Enrolled Students</div>
                                <div class="ml-stat-value"><?= $totalStudents ?? 0 ?></div>
                                <div class="ml-stat-desc">Currently active student accounts</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-user-graduate"></i></div>
                        </div>
                        <div class="ml-stat-card">
                            <div>
                                <div class="ml-stat-label">Grade 12 Students</div>
                                <div class="ml-stat-value"><?= $totalGrade12 ?? 0 ?></div>
                                <div class="ml-stat-desc">Currently enrolled in Grade 12</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-user-check"></i></div>
                        </div>
                        <div class="ml-stat-card">
                            <div>
                                <div class="ml-stat-label">Grade 11 Students</div>
                                <div class="ml-stat-value"><?= $totalGrade11 ?? 0 ?></div>
                                <div class="ml-stat-desc">Currently enrolled in Grade 11</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-hourglass-half"></i></div>
                        </div>
                    </div>

                    <!-- FILTER FORM -->
                    <form method="GET" action="" id="filter-form">
                        <input type="hidden" name="url" value="super_admin_student_users">
                        <input type="hidden" name="page" id="page-input" value="1">

                        <div class="search-parent-enrolled">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="search" name="search" id="search-input" class="form-control"
                                    placeholder="Search name or LRN..."
                                    value="<?= htmlspecialchars($search ?? '') ?>">
                            </div>

                            <select name="grade" id="filter-grade" class="form-select">
                                <option value="">Grade Level</option>
                                <?php foreach ($gradeLevels as $gl): ?>
                                        <option value="<?= strtolower(htmlspecialchars($gl['name'])) ?>"
                                            <?= (strtolower($grade ?? '') === strtolower($gl['name'])) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($gl['name']) ?>
                                        </option>
                                <?php endforeach; ?>
                            </select>

                            <select name="section" id="filter-section" class="form-select">
                                <option value="">All Sections</option>
                                <?php foreach ($allSections as $sec): ?>
                                        <option value="<?= strtolower(htmlspecialchars($sec['section_name'])) ?>"
                                            data-grade="<?= strtolower(htmlspecialchars($sec['grade_name'])) ?>"
                                            <?= (strtolower($section ?? '') === strtolower($sec['section_name'])) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sec['section_name']) ?>
                                        </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>

                    <!-- TABLE -->
                    <div class="table-parent">
                        <table class="table">
                            <thead>
                                <tr style="background-color: #ddd;">
                                    <th>Student LRN</th>
                                    <th>Name</th>
                                    <th>Grade Level</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No students found.</td>
                                        </tr>
                                <?php else: ?>
                                        <?php foreach ($students as $student): ?>
                                                <tr class="students-data"
                                                    data-lrn="<?= htmlspecialchars(strtolower($student['student_LRN'])) ?>"
                                                    data-name="<?= htmlspecialchars(strtolower($student['name'])) ?>"
                                                    data-grade="<?= htmlspecialchars(strtolower($student['grade_level'])) ?>"
                                                    data-section="<?= htmlspecialchars(strtolower($student['section_name'])) ?>">
                                                    <td><?= htmlspecialchars($student['student_LRN']) ?></td>
                                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                                    <td><?= htmlspecialchars($student['grade_level']) ?></td>
                                                    <td><?= htmlspecialchars($student['section_name']) ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-secondary btn-edit-student"
                                                            data-student-id="<?= $student['student_id'] ?>"
                                                            data-user-id="<?= $student['user_id'] ?>"
                                                            data-name="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>"
                                                            data-grade-level-id="<?= $student['grade_level_id'] ?>"
                                                            data-section-id="<?= $student['section_id'] ?>"
                                                            data-lrn="<?= htmlspecialchars($student['student_LRN'], ENT_QUOTES) ?>">
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
                    <?php if (($totalPages ?? 0) > 1): ?>
                            <div class="pagination-parent">
                                <small class="text-muted">
                                    Showing <?= min($offset + $limit, $totalStudents) ?> of
                                    <?= $totalStudents ?> students
                                </small>
                                <ul class="pagination">
                                    <?php
                                    $filterParams = http_build_query([
                                        'url' => 'super_admin_student_users',
                                        'search' => $search ?? '',
                                        'grade' => $grade ?? '',
                                        'section' => $section ?? '',
                                    ]);
                                    ?>

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

                                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page + 1 ?>">
                                            <i class="fa fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                    <?php endif; ?>

                </div><!-- /main-body -->

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
                    <div class="modal-body" style="padding: 16px 20px;">

                        <input type="hidden" name="student_id" id="edit_student_id">
                        <input type="hidden" name="user_id" id="edit_user_id">
                        <input type="hidden" name="grade_level_id" id="hidden_grade_level_id">
                        <input type="hidden" name="section_id" id="hidden_section_id">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
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

                    </div>

                    <div class="modal-footer" style="display: flex; padding: 16px 22px; gap: 10px;">
                        <button type="button" class="btn btn-secondary"
                            style="background: none; border: 1px solid #e4e7eb; border-radius: 50px; padding: 9px 20px; font-size: 13px; font-weight: 700; color: #6b7280;"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn"
                            style="background-color: var(--neon-cyan); color: #ffffff; font-weight: 600; border-radius: 28px;">
                            <i class="fa fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

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

            filterSectionOptions(filterGrade.value);

            filterGrade.addEventListener('change', function () {
                filterSectionOptions(this.value);
                filterSection.value = '';
                document.getElementById('page-input').value = 1;
                form.submit();
            });

            filterSection.addEventListener('change', function () {
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
                    if (!opt.value) return;
                    opt.style.display = (!selectedGrade || opt.dataset.grade === selectedGrade) ? '' : 'none';
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

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.btn-edit-student');
                if (!btn) return;

                const studentId = btn.dataset.studentId;
                const userId = btn.dataset.userId;
                const name = btn.dataset.name;
                const gradeLevelId = btn.dataset.gradeLevelId;
                const sectionId = btn.dataset.sectionId;
                const lrn = btn.dataset.lrn;

                document.getElementById('edit_student_id').value = studentId;
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_lrn').value = lrn;
                document.getElementById('edit_grade_level').value = gradeLevelId;

                document.getElementById('hidden_grade_level_id').value = gradeLevelId;
                document.getElementById('hidden_section_id').value = sectionId;

                filterSections(gradeLevelId, sectionId);

                editModal.show();
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
        });
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>