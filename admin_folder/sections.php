<?php
$sections = $sections ?? [];
$sectionStats = $sectionStats ?? ['total' => 0, 'grade11' => 0, 'grade12' => 0];
$gradeLevels = $gradeLevels ?? [];
$search = $search ?? '';
$grade = $grade ?? '';
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$totalSections = $totalSections ?? 0;
$limit = $limit ?? 10;
$offset = $offset ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sections</title>
    <link rel="stylesheet" href="../css_folder/sections.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <main class="main">

                <div id="toast-container"></div>

                <?php
                $flash = $_SESSION['flash'] ?? null;
                $currentPage = $_GET['url'] ?? '';
                if ($flash && $flash['page'] === $currentPage):
                    unset($_SESSION['flash']);
                    ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            showToast('<?= addslashes(htmlspecialchars($flash['message'])) ?>', '<?= addslashes($flash['type'] ?? 'success') ?>');
                        });
                    </script>
                <?php elseif ($flash && $flash['page'] !== $currentPage): ?>
                    <?php unset($_SESSION['flash']); ?>
                <?php endif; ?>

                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Sections</h2>
                        <p>Manage class sections across grade levels</p>
                    </div>
                </div>

                <div class="main-button-header">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                        <i class="fa fa-plus me-1"></i> Add Section
                    </button>
                </div>

                <div class="parent-card">
                    <div class="card-box">
                        <div class="card-text">
                            <span>Total Sections</span>
                            <p><?= (int) $sectionStats['total'] ?></p>
                            <div class="stat-data">Grade 11 & 12 combined</div>
                        </div>
                        <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                    </div>
                    <div class="card-box">
                        <div class="card-text">
                            <span>Grade 11 Sections</span>
                            <p><?= (int) $sectionStats['grade11'] ?></p>
                            <div class="stat-data">Active this school year</div>
                        </div>
                        <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                    </div>
                    <div class="card-box">
                        <div class="card-text">
                            <span>Grade 12 Sections</span>
                            <p><?= (int) $sectionStats['grade12'] ?></p>
                            <div class="stat-data">Active this school year</div>
                        </div>
                        <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                    </div>
                </div>

                <form method="GET" action="" id="section-filter-form">
                    <input type="hidden" name="url" value="Adminsections">
                    <div class="filter-grid">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="search" name="search" id="sectionSearch" class="form-control"
                                placeholder="Search section name..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <select name="grade" id="sectionGrade" style="max-width: 200px;" class="form-select" onchange="this.form.submit()">
                            <option value="">Grade Level</option>
                            <?php foreach ($gradeLevels as $gl): ?>
                                <option value="<?= htmlspecialchars($gl['name']) ?>"
                                    <?= strtolower($grade) === strtolower($gl['name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($gl['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>

                <div class="table-parent">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Section Name</th>
                                <th>Grade Level</th>
                                <th>Teachers Assigned</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sectionsTableBody">
                            <?php if (empty($sections)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No sections found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sections as $sec):
                                    $isG11 = stripos($sec['grade_level'], '11') !== false;
                                    $studentCount = (int) $sec['student_count'];
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sec['section_name']) ?></td>
                                        <td><span
                                                class="tag-chip grade <?= $isG11 ? 'g11' : '' ?>"><?= htmlspecialchars($sec['grade_level']) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($sec['teacher_names'])): ?>
                                                <span class="tag-chip section"><?= htmlspecialchars($sec['teacher_names']) ?></span>
                                            <?php else: ?>
                                                <span class="no-adviser">No teacher assigned yet</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="count-badge <?= $studentCount === 0 ? 'zero' : '' ?>">
                                                <?= $studentCount ?> student<?= $studentCount !== 1 ? 's' : '' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-section"
                                                data-section-id="<?= $sec['id'] ?>"
                                                data-section-name="<?= htmlspecialchars($sec['section_name']) ?>"
                                                data-grade-level="<?= htmlspecialchars($sec['grade_level']) ?>">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form action="?url=deleteSection" method="post" style="display:inline;"
                                                onsubmit="return confirm('Delete this section?');">
                                                <input type="hidden" name="section_id" value="<?= $sec['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <?php
                    $sectionFilterParams = http_build_query([
                        'url' => 'Adminsections',
                        'search' => $search,
                        'grade' => $grade,
                    ]);
                    ?>
                    <div class="pagination-parent">
                        <small class="text-muted">Showing <?= min($offset + $limit, $totalSections) ?> of
                            <?= $totalSections ?> sections</small>
                        <ul class="pagination">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= $sectionFilterParams ?>&page=<?= $page - 1 ?>">
                                    <i class="fa fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= $sectionFilterParams ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= $sectionFilterParams ?>&page=<?= $page + 1 ?>">
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>

    <!-- Add Section Modal -->
    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="?url=createSection" method="post">
                    <div class="modal-body" style="padding: 16px 20px;">
                        <div class="mb-3">
                            <label class="form-label">Section Name</label>
                            <input type="text" name="section_name" class="form-control" placeholder="e.g. CSS 12-4"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade_level_id" class="form-select" required>
                                <option value="">Select grade level</option>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= $gl['id'] ?>"><?= htmlspecialchars($gl['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="padding: 14px 20px; border-top: 1px solid #e4e7eb; display: flex; gap: 16px; align-items: center;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="margin: 0;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="margin: 0;">Save Section</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Section Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Section</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSectionForm" action="?url=updateSection" method="post">
                    <input type="hidden" name="section_id" id="edit-section-id">
                    <div class="modal-body" style="padding: 16px 20px;">
                        <div class="mb-3">
                            <label class="form-label">Section Name</label>
                            <input type="text" name="section_name" id="edit-section-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade_level_id" id="edit-section-grade" class="form-select" required>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= $gl['id'] ?>"><?= htmlspecialchars($gl['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="padding: 14px 20px; border-top: 1px solid #e4e7eb; display: flex; gap: 16px; align-items: center;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-msg toast-${type}`;
            toast.innerHTML = `
        <i class="fa ${type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'}"></i>
        <span>${message}</span>
    `;

            container.appendChild(toast);

            // trigger enter animation
            requestAnimationFrame(() => toast.classList.add('show'));

            setTimeout(() => {
                toast.classList.remove('show');
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            }, 3500);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = new bootstrap.Modal(document.getElementById('editSectionModal'));
            const searchInput = document.getElementById('sectionSearch');
            const filterForm = document.getElementById('section-filter-form');

            document.querySelectorAll('.btn-edit-section').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('edit-section-id').value = this.dataset.sectionId;
                    document.getElementById('edit-section-name').value = this.dataset.sectionName;

                    const gs = document.getElementById('edit-section-grade');
                    [...gs.options].forEach(opt => {
                        opt.selected = opt.textContent.trim() === this.dataset.gradeLevel.trim();
                    });

                    editModal.show();
                });
            });

            let debounce;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(() => filterForm.submit(), 450);
            });
        });
    </script>
</body>

</html>