<?php
$subjects = $subjects ?? [];
$subjectStats = $subjectStats ?? ['total' => 0, 'grade11' => 0, 'grade12' => 0];
$gradeLevels = $gradeLevels ?? [];
$search = $search ?? '';
$grade = $grade ?? '';
$strandFilter = $strandFilter ?? '';
$curriculumType = $curriculumType ?? 'new'; // 'legacy' | 'new'
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;
$totalSubjects = $totalSubjects ?? 0;
$limit = $limit ?? 10;
$offset = $offset ?? 0;

$curriculumDescriptions = [
    'legacy' => 'Showing subjects under the current strand-based system (STEM, ABM, HUMSS, TVL).',
    'new' => "Showing subjects under DepEd's Strengthened Curriculum for SY 2026–2027 — fixed core subjects plus elective clusters.",
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="stylesheet" href="../css_folder/Adminsubjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../admin_folder/sidebar.php") ?>

        <div class="rightbar">

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
                    <h2>Subjects</h2>
                    <p>Manage subjects offered per grade level, curriculum, and strand</p>
                </div>
            </div>

            <!-- Curriculum Tabs -->
            <?php
            $tabBaseParams = http_build_query([
                'url' => 'Adminsubjects',
                'search' => $search,
                'grade' => $grade,
                'strand' => $strandFilter,
            ]);
            ?>
            <div class="curriculum-tabs">
                <a href="?<?= $tabBaseParams ?>&curriculum=new"
                    class="tab-btn <?= $curriculumType === 'new' ? 'active' : '' ?>">
                    New Curriculum
                </a>
                <a href="?<?= $tabBaseParams ?>&curriculum=legacy"
                    class="tab-btn <?= $curriculumType === 'legacy' ? 'active' : '' ?>">
                    Legacy Curriculum
                </a>
            </div>
            <p class="curriculum-desc"><?= htmlspecialchars($curriculumDescriptions[$curriculumType]) ?></p>

            <div class="main-button-header">
                <button type="button" class="btn-add-subject" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="fa fa-plus"></i> Add Subject
                </button>
            </div>

            <div class="parent-card">
                <div class="card-box">
                    <div class="card-text">
                        <span>Total Subjects</span>
                        <p><?= (int) $subjectStats['total'] ?></p>
                        <div class="stat-data">Offered this school year</div>
                    </div>
                    <div class="card-icon"><i class="fa fa-book"></i></div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Grade 11 Subjects</span>
                        <p><?= (int) $subjectStats['grade11'] ?></p>
                        <div class="stat-data">Assigned to Grade 11</div>
                    </div>
                    <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Grade 12 Subjects</span>
                        <p><?= (int) $subjectStats['grade12'] ?></p>
                        <div class="stat-data">Assigned to Grade 12</div>
                    </div>
                    <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                </div>
            </div>

            <form id="subjectFilterForm" method="GET" action="">
                <input type="hidden" name="url" value="Adminsubjects">
                <input type="hidden" name="curriculum" value="<?= htmlspecialchars($curriculumType) ?>">
                <div class="filter-grid">
                    <div class="input-group">
                        <div class="input-group-text">
                            <i class="fa fa-search"></i>
                        </div>
                        <input type="text" name="search" id="subjectSearch" class="form-control"
                            placeholder="Search subject name..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <select name="grade" id="subjectGrade" class="form-select" style="max-width: 200px;">
                        <option value="">Grade Level</option>
                        <?php foreach ($gradeLevels as $gl): ?>
                            <option value="<?= htmlspecialchars($gl['name']) ?>"
                                <?= strtolower($grade) === strtolower($gl['name']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($gl['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <select name="strand" id="subjectStrand" class="form-select" style="max-width: 220px;">
                        <option value="">Group / Strand</option>
                        <?php foreach (($strandOptions ?? []) as $sOpt): ?>
                            <option value="<?= htmlspecialchars($sOpt) ?>" <?= strtolower($strandFilter) === strtolower($sOpt) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sOpt) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <div class="table-parent">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Subject Name</th>
                            <th>Grade Level</th>
                            <th>Group / Strand</th>
                            <th>Teachers Assigned</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subjectsTableBody">
                        <?php if (empty($subjects)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No subjects found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($subjects as $subject): ?>
                                <?php
                                $isG11 = stripos($subject['grade_level'], '11') !== false;
                                $teacherCount = (int) $subject['teacher_count'];
                                $strandLabel = $subject['strand_group'] ?? '';
                                $strandLower = strtolower($strandLabel);
                                $strandClass = 'default';
                                if (str_contains($strandLower, 'core')) {
                                    $strandClass = 'core';
                                } elseif (str_contains($strandLower, 'elective')) {
                                    $strandClass = 'elective';
                                } elseif (str_contains($strandLower, 'tvl')) {
                                    $strandClass = 'tvl';
                                } elseif (str_contains($strandLower, 'abm')) {
                                    $strandClass = 'abm';
                                } elseif (str_contains($strandLower, 'stem')) {
                                    $strandClass = 'stem';
                                } elseif (str_contains($strandLower, 'humss')) {
                                    $strandClass = 'humss';
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($subject['subject_image'])): ?>
                                            <img src="/learning_management/<?= htmlspecialchars($subject['subject_image']) ?>"
                                                alt="<?= htmlspecialchars($subject['subject_name']) ?>"
                                                style="width:44px; height:44px; object-fit:cover; border-radius:8px; border:1px solid #e4e7eb;">
                                        <?php else: ?>
                                            <div
                                                style="width:44px; height:44px; border-radius:8px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#9ca3af;">
                                                <i class="fa fa-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                    <td>
                                        <span class="tag-chip grade <?= $isG11 ? 'g11' : '' ?>">
                                            <?= htmlspecialchars($subject['grade_level']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($strandLabel !== ''): ?>
                                            <span class="tag-chip strand <?= $strandClass ?>">
                                                <?= htmlspecialchars($strandLabel) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic" style="font-size:13px;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="count-badge <?= $teacherCount === 0 ? 'zero' : '' ?>">
                                            <?= $teacherCount ?> teacher<?= $teacherCount === 1 ? '' : 's' ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-subject"
                                            data-bs-toggle="modal" data-bs-target="#editSubjectModal"
                                            data-id="<?= (int) $subject['id'] ?>"
                                            data-name="<?= htmlspecialchars($subject['subject_name'], ENT_QUOTES) ?>"
                                            data-grade-level-id="<?= (int) $subject['grade_level_id'] ?>"
                                            data-description="<?= htmlspecialchars($subject['subject_description'] ?? '', ENT_QUOTES) ?>"
                                            data-curriculum-type="<?= htmlspecialchars($subject['curriculum_type'] ?? 'legacy', ENT_QUOTES) ?>"
                                            data-strand-group="<?= htmlspecialchars($subject['strand_group'] ?? '', ENT_QUOTES) ?>"
                                            data-image="<?= htmlspecialchars($subject['subject_image'] ?? '', ENT_QUOTES) ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-subject"
                                            data-id="<?= (int) $subject['id'] ?>"
                                            data-name="<?= htmlspecialchars($subject['subject_name'], ENT_QUOTES) ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <?php
                $subjectFilterParams = http_build_query([
                    'url' => 'Adminsubjects',
                    'search' => $search,
                    'grade' => $grade,
                    'strand' => $strandFilter,
                    'curriculum' => $curriculumType,
                ]);
                ?>
                <div class="pagination-parent">
                    <small class="text-muted">Showing <?= min($offset + $limit, $totalSubjects) ?> of
                        <?= $totalSubjects ?> subjects</small>
                    <ul class="pagination">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $subjectFilterParams ?>&page=<?= $page - 1 ?>">
                                <i class="fa fa-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= $subjectFilterParams ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?<?= $subjectFilterParams ?>&page=<?= $page + 1 ?>">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Add Subject Modal -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/learning_management/public/?url=addSubject" enctype="multipart/form-data">
                    <input type="hidden" name="redirect_curriculum" value="<?= htmlspecialchars($curriculumType) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding: 16px 20px;">
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" name="subject_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Curriculum</label>
                            <select name="curriculum_type" class="form-select" disabled required>
                                <option value="new" <?= $curriculumType === 'new' ? 'selected' : '' ?>>New Curriculum</option>
                                <option value="legacy" <?= $curriculumType === 'legacy' ? 'selected' : '' ?>>Legacy
                                    Curriculum</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade_level_id" class="form-select" required>
                                <option value="">Select grade level</option>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= (int) $gl['id'] ?>"><?= htmlspecialchars($gl['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Group / Strand</label>
                            <input type="text" name="strand_group" class="form-control"
                                placeholder="e.g. Core (all strands), TVL — ICT Strand, ABM Strand">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Description</label>
                            <textarea name="subject_description" class="form-control" rows="3"
                                placeholder="Short description of this subject"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Image</label>
                            <input type="file" name="subject_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="padding: 14px 20px;border-top: 1px solid #e4e7eb; display: flex; gap: 16px; align-items: center;">
                        <button type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Subject Modal -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/learning_management/public/?url=updateSubject"
                    enctype="multipart/form-data">
                    <input type="hidden" name="subject_id" id="editSubjectId">
                    <input type="hidden" name="redirect_curriculum" value="<?= htmlspecialchars($curriculumType) ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding: 16px 20px;">
                        <div class="mb-3">
                            <label class="form-label">Subject Name</label>
                            <input type="text" name="subject_name" id="editSubjectName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Curriculum</label>
                            <select name="curriculum_type" id="editSubjectCurriculumType" class="form-select" disabled required>
                                <option value="new">New Curriculum</option>
                                <option value="legacy">Legacy Curriculum</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade Level</label>
                            <select name="grade_level_id" id="editSubjectGradeLevel" class="form-select" required>
                                <option value="">Select grade level</option>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= (int) $gl['id'] ?>"><?= htmlspecialchars($gl['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Group / Strand</label>
                            <input type="text" name="strand_group" id="editSubjectStrandGroup" class="form-control"
                                placeholder="e.g. Core (all strands), TVL — ICT Strand, ABM Strand">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Description</label>
                            <textarea name="subject_description" id="editSubjectDescription" class="form-control"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject Image</label>
                            <div id="editSubjectImageWrap" class="mb-3" style="display:none;">
                                <img id="editSubjectImagePreview" src="" alt="Current image"
                                    style="width: 100%; height: 230px; border-radius:8px; border:1px solid #e4e7eb;">
                            </div>
                            <input type="file" name="subject_image" class="form-control" accept="image/*">
                            <small class="text-muted" style="margin: 4px 0 0;">Leave empty to keep the current
                                image.</small>
                        </div>
                    </div>
                    <div class="modal-footer"
                        style="padding: 14px 20px;border-top: 1px solid #e4e7eb; display: flex; gap: 16px; align-items: center;">
                        <button type="button" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Subject Form (hidden, submitted via JS confirm) -->
    <form method="POST" action="/learning_management/public/?url=deleteSubject" id="deleteSubjectForm"
        style="display:none;">
        <input type="hidden" name="subject_id" id="deleteSubjectId">
    </form>

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

            requestAnimationFrame(() => toast.classList.add('show'));

            setTimeout(() => {
                toast.classList.remove('show');
                toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            }, 3500);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('subjectFilterForm');
            const searchInput = document.getElementById('subjectSearch');
            const gradeSelect = document.getElementById('subjectGrade');
            const strandSelect = document.getElementById('subjectStrand');

            let debounce;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(() => filterForm.submit(), 450);
            });

            gradeSelect.addEventListener('change', function () {
                filterForm.submit();
            });

            strandSelect.addEventListener('change', function () {
                filterForm.submit();
            });

            document.querySelectorAll('.btn-edit-subject').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.getElementById('editSubjectId').value = this.dataset.id;
                    document.getElementById('editSubjectName').value = this.dataset.name;
                    document.getElementById('editSubjectGradeLevel').value = this.dataset.gradeLevelId;
                    document.getElementById('editSubjectDescription').value = this.dataset.description || '';
                    document.getElementById('editSubjectCurriculumType').value = this.dataset.curriculumType || 'legacy';
                    document.getElementById('editSubjectStrandGroup').value = this.dataset.strandGroup || '';

                    const imgPath = this.dataset.image;
                    const imgWrap = document.getElementById('editSubjectImageWrap');
                    const imgPreview = document.getElementById('editSubjectImagePreview');
                    if (imgPath) {
                        imgPreview.src = '/learning_management/' + imgPath;
                        imgWrap.style.display = 'block';
                    } else {
                        imgWrap.style.display = 'none';
                        imgPreview.src = '';
                    }
                });
            });

            document.querySelectorAll('.btn-delete-subject').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const name = this.dataset.name;
                    if (confirm('Delete subject "' + name + '"? This cannot be undone.')) {
                        document.getElementById('deleteSubjectId').value = this.dataset.id;
                        document.getElementById('deleteSubjectForm').submit();
                    }
                });
            });
        });
    </script>
</body>

</html>