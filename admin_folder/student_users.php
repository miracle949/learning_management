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

        /* -- Page tabs -- */
        .page-tabs {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        .pagination-parent small{
            font-size: 13px;
        }

        .page-tab-btn {
            border: none;
            background: none;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-tab-btn.active {
            color: var(--neon-cyan, #0891b2);
            border-bottom-color: var(--neon-cyan, #0891b2);
        }

        .page-tab-badge {
            background: #eef2f7;
            color: #6b7280;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            padding: 2px 9px;
        }

        .page-tab-btn.active .page-tab-badge {
            background: rgba(8, 145, 178, 0.12);
            color: var(--neon-cyan, #0891b2);
        }

        .page-tab-pane {
            display: none;
        }

        .page-tab-pane.active {
            display: block;
        }

        .status-chip {
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .status-chip.enrolled {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-chip.pending {
            background: #fff3e0;
            color: #b45309;
            border: 1px solid #ffe0b2;
        }

        /* Subtle loading state while an AJAX refresh is in flight */
        .table-parent.is-loading {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity .15s ease;
        }

        /* -- Masterlist summary cards -- */
        .ml-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .ml-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .ml-stat-card {
            display: flex;
            justify-content: space-between;
            /* align-items: center; */
            gap: 14px;
            background: #fff;
            /* border: 1px solid #e4e7eb; */
            border-radius: 14px;
            padding: 18px 20px;
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04); */
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .ml-stat-icon {
            width: 32px;
            height: 32px;
            /* width: 48px; */
            /* height: 48px; */
            background: rgba(0, 119, 204, 0.08);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ml-stat-icon .fa{
            /* font-size: 19.5px; */
            /* color: var(--green); */
            font-size: 15px;
            color: var(--neon-cyan);
        }

        /* .ml-stat-card.total .ml-stat-icon {
            background: rgba(8, 145, 178, 0.12);
            color: var(--neon-cyan, #0891b2);
        }

        .ml-stat-card.enrolled .ml-stat-icon {
            background: #d4edda;
            color: #155724;
        }

        .ml-stat-card.pending .ml-stat-icon {
            background: #fff3e0;
            color: #b45309;
        } */

        .ml-stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 10px 0 0;
            /* line-height: 1.1; */
        }

        .ml-stat-label {
            font-size: 12.5px;
            font-weight: 500;
            /* color: #6b7280; */
            color: var(--text-dim);
            /* margin-top: 2px; */
        }

        .ml-stat-desc{
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-dim);
            margin: 4px 0 0;
        }
    </style>
</head>


<body>


    <div class="container-fluid p-0">

        <?php include("../admin_folder/sidebar.php") ?>

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

                    <!-- PAGE TABS -->
                    <div class="page-tabs">
                        <button type="button" class="page-tab-btn <?= ($activeTab ?? 'enrolled') === 'enrolled' ? 'active' : '' ?>" data-tab="enrolled">
                            <i class="fa fa-user-check"></i> Enrolled Students
                            <!-- <span class="page-tab-badge" id="enrolled-badge"><?= $totalStudents ?? 0 ?></span> -->
                        </button>
                        <button type="button" class="page-tab-btn <?= ($activeTab ?? 'enrolled') === 'masterlist' ? 'active' : '' ?>" data-tab="masterlist">
                            <i class="fa fa-file-alt"></i> Masterlist
                            <!-- <span class="page-tab-badge" id="masterlist-badge"><?= $totalMasterlist ?? 0 ?></span> -->
                        </button>
                    </div>

                    <!-- ======================================================
                         TAB 1 - ENROLLED STUDENTS
                    ====================================================== -->
                    <div class="page-tab-pane <?= ($activeTab ?? 'enrolled') === 'enrolled' ? 'active' : '' ?>" id="tab-enrolled">

                    <!-- ENROLLED STUDENTS SUMMARY CARDS -->
                    <div class="ml-stats-grid" id="es-stats-grid">
                        <div class="ml-stat-card total">
                            <div>
                                <div class="ml-stat-label">Total Enrolled Students</div>
                                <div class="ml-stat-value" id="es-stat-total"><?= $totalStudents ?? 0 ?></div>
                                <div class="ml-stat-desc">Currently active student accounts</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-user-graduate"></i></div>
                        </div>
                        <div class="ml-stat-card enrolled">
                            <div>
                                <div class="ml-stat-label">Grade 12 Students</div>
                                <div class="ml-stat-value" id="es-stat-approved"><?= $totalGrade12 ?? 0 ?></div>
                                <div class="ml-stat-desc">Currently enrolled in Grade 12</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-user-check"></i></div>
                        </div>
                        <div class="ml-stat-card pending">
                            <div>
                                <div class="ml-stat-label">Grade 11 Students</div>
                                <div class="ml-stat-value" id="es-stat-pending"><?= $totalGrade11 ?? 0 ?></div>
                                <div class="ml-stat-desc">Currently enrolled in Grade 11</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-hourglass-half"></i></div>
                        </div>
                    </div>

                        <form method="GET" action="" id="filter-form" onsubmit="return false;">
                            <input type="hidden" name="url" value="student_users">
                            <input type="hidden" name="active_tab" value="enrolled">

                            <div class="search-parent-enrolled">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                    <input type="search" name="search" id="search-input" class="form-control"
                                        placeholder="Search name or LRN..."
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
                                <tbody id="enrolled-tbody">
                                    <?php if (empty($students)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">No students found.</td>
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
                        <div id="enrolled-pagination-container">
                            <?php if (($totalPages ?? 0) > 1): ?>
                                <div class="pagination-parent">
                                    <small class="text-muted">
                                        Showing <?= min($offset + $limit, $totalStudents) ?> of
                                        <?= $totalStudents ?> students
                                    </small>
                                    <ul class="pagination">

                                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link ajax-page-link" href="javascript:void(0)" data-page="<?= $page - 1 ?>">
                                                <i class="fa fa-chevron-left"></i>
                                            </a>
                                        </li>

                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                                <a class="page-link ajax-page-link" href="javascript:void(0)" data-page="<?= $i ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                            <a class="page-link ajax-page-link" href="javascript:void(0)" data-page="<?= $page + 1 ?>">
                                                <i class="fa fa-chevron-right"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div><!-- /tab-enrolled -->

                    <!-- ======================================================
                         TAB 2 - MASTERLIST
                    ====================================================== -->
                    <div class="page-tab-pane <?= ($activeTab ?? 'enrolled') === 'masterlist' ? 'active' : '' ?>" id="tab-masterlist">

                        <div class="main-button-header"
                            style="display: flex; justify-content: end; margin-bottom: 1.5rem;">
                            <button type="button" class="btn" data-bs-toggle="modal"
                                data-bs-target="#importMasterlistModal"
                                style="background-color: var(--neon-cyan); font-size: 14.5px; color:#fff; font-weight:600; border-radius:20px; padding:10px 24px;">
                                <i class="fa fa-file-upload me-1"></i> Import Masterlist
                            </button>
                        </div>

                        <!-- MASTERLIST SUMMARY CARDS -->
                        <div class="ml-stats-grid" id="ml-stats-grid">
                            <div class="ml-stat-card total">
                                
                                <div>
                                    <div class="ml-stat-label">Total Students Records</div>
                                    <div class="ml-stat-value" id="ml-stat-total"><?= $totalMasterlist ?? 0 ?></div>
                                    <div class="ml-stat-desc">From the imported masterlist</div>
                                </div>
                                <div class="ml-stat-icon"><i class="fa fa-file-alt"></i></div>
                            </div>
                            <div class="ml-stat-card enrolled">
                                
                                <div>
                                    <div class="ml-stat-label">Enrolled Students</div>
                                    <div class="ml-stat-value" id="ml-stat-enrolled"><?= $mlTotalEnrolled ?? 0 ?></div>
                                    <div class="ml-stat-desc">Matched to an enrolled account</div>
                                </div>
                                <div class="ml-stat-icon"><i class="fa fa-user-check"></i></div>
                            </div>
                            <div class="ml-stat-card pending">
                                
                                <div>
                                    <div class="ml-stat-label">Pending Students</div>
                                    <div class="ml-stat-value" id="ml-stat-pending"><?= $mlTotalPending ?? 0 ?></div>
                                    <div class="ml-stat-desc">Not yet matched or enrolled</div>
                                </div>
                                <div class="ml-stat-icon"><i class="fa fa-hourglass-half"></i></div>
                            </div>
                        </div>

                        <form method="GET" action="" id="ml-filter-form" onsubmit="return false;">
                            <input type="hidden" name="url" value="student_users">
                            <input type="hidden" name="active_tab" value="masterlist">

                            <div class="search-parent">
                                <div class="search-parent1">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                        <input type="search" name="ml_search" id="ml-search-input" class="form-control"
                                            placeholder="Search name or LRN..."
                                            value="<?= htmlspecialchars($mlSearch ?? '') ?>">
                                    </div>

                                    <select name="ml_grade" id="ml-filter-grade" class="form-select">
                                        <option value="">Grade Level</option>
                                        <?php foreach ($gradeLevels as $gl): ?>
                                            <option value="<?= strtolower(htmlspecialchars($gl['name'])) ?>"
                                                <?= (strtolower($mlGrade ?? '') === strtolower($gl['name'])) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($gl['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <select name="ml_section" id="ml-filter-section" class="form-select">
                                        <option value="">All Sections</option>
                                        <?php foreach ($allSections as $sec): ?>
                                            <option value="<?= strtolower(htmlspecialchars($sec['section_name'])) ?>"
                                                data-grade="<?= strtolower(htmlspecialchars($sec['grade_name'])) ?>"
                                                <?= (strtolower($mlSection ?? '') === strtolower($sec['section_name'])) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($sec['section_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <select name="ml_strand" id="ml-filter-strand" class="form-select">
                                        <option value="">All Strands</option>
                                        <?php foreach ($mlStrands ?? [] as $strandOpt): ?>
                                            <option value="<?= htmlspecialchars($strandOpt) ?>"
                                                <?= (($mlStrand ?? '') === $strandOpt) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($strandOpt) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="search-parent2">
                                    <select name="ml_school_year" id="ml-filter-school-year" class="form-select">
                                        <option value="">All School Years</option>
                                        <?php foreach ($mlSchoolYears ?? [] as $syOpt): ?>
                                            <option value="<?= htmlspecialchars($syOpt) ?>"
                                                <?= (($mlSchoolYear ?? '') === $syOpt) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($syOpt) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <select name="ml_status" id="ml-filter-status" class="form-select">
                                        <option value="">All Statuses</option>
                                        <option value="enrolled" <?= (strtolower($mlStatus ?? '') === 'enrolled') ? 'selected' : '' ?>>Enrolled</option>
                                        <option value="pending" <?= (strtolower($mlStatus ?? '') === 'pending') ? 'selected' : '' ?>>Pending</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-parent">
                            <table class="table">
                                <thead>
                                    <tr style="background-color: #ddd;">
                                        <th>LRN</th>
                                        <th>Name</th>
                                        <th>Grade Level</th>
                                        <th>Section</th>
                                        <th>Strand</th>
                                        <th>School Year</th>
                                        <!-- <th>Date</th> -->
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="masterlist-tbody">
                                    <?php if (empty($masterlist)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">No masterlist records found.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($masterlist as $row): ?>
                                            <?php
                                            $fullName = trim($row['last_name'] . ', ' . $row['first_name'] . ' ' . ($row['middle_name'] ?? ''));
                                            $isMatched = !empty($row['is_matched']) && (int) $row['is_matched'] === 1;
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['student_LRN']) ?></td>
                                                <td><?= htmlspecialchars($fullName) ?></td>
                                                <td><?= htmlspecialchars($row['grade_level'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['section_name'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['strand'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['school_year'] ?? '-') ?></td>
                                                <td>
                                                    <?php if ($isMatched): ?>
                                                        <span class="status-chip enrolled">Enrolled</span>
                                                    <?php else: ?>
                                                        <span class="status-chip pending">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- PAGINATION -->
                        <div id="masterlist-pagination-container">
                            <?php if (($totalMlPages ?? 0) > 1): ?>
                                <div class="pagination-parent">
                                    <small class="text-muted">
                                        Showing <?= min($mlOffset + $mlLimit, $totalMasterlist) ?> of
                                        <?= $totalMasterlist ?> masterlist records
                                    </small>
                                    <ul class="pagination">

                                        <li class="page-item <?= $mlPage <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link ajax-ml-page-link" href="javascript:void(0)" data-page="<?= $mlPage - 1 ?>">
                                                <i class="fa fa-chevron-left"></i>
                                            </a>
                                        </li>

                                        <?php for ($i = 1; $i <= $totalMlPages; $i++): ?>
                                            <li class="page-item <?= $i === $mlPage ? 'active' : '' ?>">
                                                <a class="page-link ajax-ml-page-link" href="javascript:void(0)" data-page="<?= $i ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?= $mlPage >= $totalMlPages ? 'disabled' : '' ?>">
                                            <a class="page-link ajax-ml-page-link" href="javascript:void(0)" data-page="<?= $mlPage + 1 ?>">
                                                <i class="fa fa-chevron-right"></i>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div><!-- /tab-masterlist -->

                </div><!-- /main-body -->

            </main>
        </div>
    </div>

    <!-- IMPORT MASTERLIST MODAL -->
    <div class="modal fade" id="importMasterlistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Import Student Masterlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/learning_management/public/?url=import_masterlist"
                    enctype="multipart/form-data" id="importMasterlistForm">
                    <div class="modal-body" style="padding: 16px 20px;">

                        <label style="display:block;font-size:14.5px;font-weight:700;color:#374151;margin-bottom:6px;">
                            Import CSV Files
                        </label>
                        <div>
                            <input type="file" name="masterlist_csv" id="masterlist_csv"
                                style="width: 100%; padding: 9px 12px; font-size: 14.5px; border: 1px dashed #d1d5db;border-radius: 8px;"
                                accept=".csv" required>
                        </div>
                        <p style="font-size:13.5px;color:#9ca3af; margin: 0; line-height: 1.5; font-weight: 500; margin: 6px 0 0">
                            Upload a CSV with an <strong>LRN</strong> column if you need to enroll students not in
                            the masterlist preview.
                        </p>

                        <div id="import-summary" style="display:none;" class="p-3 mb-2"
                            style="background:#f0f7f2; border-radius:10px; font-size:14.5px;"></div>

                    </div>
                    <div class="modal-footer"
                        style="padding: 14px 20px; border-top: 1px solid #e4e7eb; display: flex; justify-content: space-between; align-items: center;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="background: none; border: 1px solid #e4e7eb; border-radius: 50px; padding: 8px 18px; font-size: 14.5px; font-weight: 600; color: #6b7280; cursor: pointer;">Cancel</button>
                        <button type="submit" class="btn" id="btn-import-submit"
                            style="background-color: var(--neon-cyan); color:#fff; font-weight:600; border-radius: 20px;">
                            <i class="fa fa-upload me-1"></i> Upload & Import
                        </button>
                    </div>
                </form>

            </div>
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

                <form method="POST" action="/learning_management/public/?url=update_student">
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
                            style="border-radius: 28px;     background: none;border: 1px solid #e4e7eb; border-radius: 50px; padding: 9px 20px; font-size: 13px; font-weight: 700; color: #6b7280;"
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

            /* --- PAGE TABS --- */
            const tabBtns = document.querySelectorAll('.page-tab-btn');
            const tabPanes = document.querySelectorAll('.page-tab-pane');

            function activateTab(tabName) {
                tabBtns.forEach(b => b.classList.toggle('active', b.dataset.tab === tabName));
                tabPanes.forEach(p => p.classList.toggle('active', p.id === 'tab-' + tabName));
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tab = btn.dataset.tab;
                    activateTab(tab);

                    const url = new URL(window.location.href);
                    url.searchParams.set('active_tab', tab);
                    window.history.replaceState({}, '', url);
                });
            });

            /* --- AJAX helpers --- */
            function ajaxGet(params) {
                const usp = new URLSearchParams(params);
                return fetch('?' + usp.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(r => r.json());
            }

            function debounce(fn, delay) {
                let timer;
                return function (...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            function setLoading(tableParentEl, loading) {
                if (!tableParentEl) return;
                tableParentEl.classList.toggle('is-loading', loading);
            }

            /* --- ENROLLED STUDENTS - AJAX search/filter/pagination --- */
            const searchInput = document.getElementById('search-input');
            const filterGrade = document.getElementById('filter-grade');
            const filterSection = document.getElementById('filter-section');
            const enrolledTbody = document.getElementById('enrolled-tbody');
            const enrolledPaginationContainer = document.getElementById('enrolled-pagination-container');
            const enrolledBadge = document.getElementById('enrolled-badge');
            const enrolledTableParent = enrolledTbody ? enrolledTbody.closest('.table-parent') : null;

            filterSectionOptions(filterGrade.value);

            function loadEnrolled(page) {
                page = page || 1;
                setLoading(enrolledTableParent, true);
                ajaxGet({
                    url: 'student_users_search',
                    tab: 'enrolled',
                    search: searchInput.value,
                    grade: filterGrade.value,
                    section: filterSection.value,
                    page: page
                }).then(data => {
                    if (!data || !data.success) {
                        showToast('error', 'Could not load students.');
                        return;
                    }
                    enrolledTbody.innerHTML = data.rows_html;
                    enrolledPaginationContainer.innerHTML = data.pagination_html;
                    if (enrolledBadge) enrolledBadge.textContent = data.total;
                }).catch(() => {
                    showToast('error', 'Network error while loading students.');
                }).finally(() => setLoading(enrolledTableParent, false));
            }

            filterGrade.addEventListener('change', function () {
                filterSectionOptions(this.value);
                filterSection.value = '';
                loadEnrolled(1);
            });

            filterSection.addEventListener('change', function () {
                loadEnrolled(1);
            });

            searchInput.addEventListener('input', debounce(() => loadEnrolled(1), 400));

            enrolledPaginationContainer.addEventListener('click', function (e) {
                const link = e.target.closest('.ajax-page-link');
                if (!link) return;
                const page = parseInt(link.dataset.page, 10);
                if (!page || page < 1) return;
                loadEnrolled(page);
            });

            function filterSectionOptions(selectedGrade) {
                const options = filterSection.querySelectorAll('option');
                options.forEach(opt => {
                    if (!opt.value) return;
                    opt.style.display = (!selectedGrade || opt.dataset.grade === selectedGrade) ? '' : 'none';
                });
            }

            /* --- MASTERLIST - AJAX search/filter/pagination --- */
            const mlSearchInput = document.getElementById('ml-search-input');
            const mlFilterGrade = document.getElementById('ml-filter-grade');
            const mlFilterSection = document.getElementById('ml-filter-section');
            const mlFilterStrand = document.getElementById('ml-filter-strand');
            const mlFilterSchoolYear = document.getElementById('ml-filter-school-year');
            const mlFilterStatus = document.getElementById('ml-filter-status');
            const masterlistTbody = document.getElementById('masterlist-tbody');
            const masterlistPaginationContainer = document.getElementById('masterlist-pagination-container');
            const masterlistBadge = document.getElementById('masterlist-badge');
            const masterlistTableParent = masterlistTbody ? masterlistTbody.closest('.table-parent') : null;
            const mlStatTotal = document.getElementById('ml-stat-total');
            const mlStatEnrolled = document.getElementById('ml-stat-enrolled');
            const mlStatPending = document.getElementById('ml-stat-pending');

            filterMlSectionOptions(mlFilterGrade.value);

            function loadMasterlist(page) {
                page = page || 1;
                setLoading(masterlistTableParent, true);
                ajaxGet({
                    url: 'student_users_search',
                    tab: 'masterlist',
                    ml_search: mlSearchInput.value,
                    ml_grade: mlFilterGrade.value,
                    ml_section: mlFilterSection.value,
                    ml_strand: mlFilterStrand.value,
                    ml_school_year: mlFilterSchoolYear.value,
                    ml_status: mlFilterStatus.value,
                    ml_page: page
                }).then(data => {
                    if (!data || !data.success) {
                        showToast('error', 'Could not load masterlist.');
                        return;
                    }
                    masterlistTbody.innerHTML = data.rows_html;
                    masterlistPaginationContainer.innerHTML = data.pagination_html;
                    if (masterlistBadge) masterlistBadge.textContent = data.total;
                    if (data.stats) {
                        if (mlStatTotal) mlStatTotal.textContent = data.stats.total;
                        if (mlStatEnrolled) mlStatEnrolled.textContent = data.stats.enrolled;
                        if (mlStatPending) mlStatPending.textContent = data.stats.pending;
                    }
                }).catch(() => {
                    showToast('error', 'Network error while loading masterlist.');
                }).finally(() => setLoading(masterlistTableParent, false));
            }

            mlFilterGrade.addEventListener('change', function () {
                filterMlSectionOptions(this.value);
                mlFilterSection.value = '';
                loadMasterlist(1);
            });

            mlFilterSection.addEventListener('change', function () { loadMasterlist(1); });
            mlFilterStrand.addEventListener('change', function () { loadMasterlist(1); });
            mlFilterSchoolYear.addEventListener('change', function () { loadMasterlist(1); });
            mlFilterStatus.addEventListener('change', function () { loadMasterlist(1); });
            mlSearchInput.addEventListener('input', debounce(() => loadMasterlist(1), 400));

            masterlistPaginationContainer.addEventListener('click', function (e) {
                const link = e.target.closest('.ajax-ml-page-link');
                if (!link) return;
                const page = parseInt(link.dataset.page, 10);
                if (!page || page < 1) return;
                loadMasterlist(page);
            });

            function filterMlSectionOptions(selectedGrade) {
                const options = mlFilterSection.querySelectorAll('option');
                options.forEach(opt => {
                    if (!opt.value) return;
                    opt.style.display = (!selectedGrade || opt.dataset.grade === selectedGrade) ? '' : 'none';
                });
            }

            window.reloadStudentTables = function () {
                loadEnrolled(1);
                loadMasterlist(1);
            };
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

    <script>
        document.getElementById('importMasterlistForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = document.getElementById('btn-import-submit');
            const fd = new FormData(this);

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Importing...';

            fetch('/learning_management/public/?url=import_masterlist', {
                method: 'POST',
                body: fd
            })
                .then(r => r.json())
                .then(data => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-upload me-1"></i> Upload & Import';

                    if (data.success) {
                        showToast('success', `Imported: ${data.inserted} new, ${data.updated} updated, ${data.skipped} skipped.`);
                        if (window.reloadStudentTables) window.reloadStudentTables();
                        const modalEl = document.getElementById('importMasterlistModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    } else {
                        showToast('error', data.message || 'Import failed.');
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-upload me-1"></i> Upload & Import';
                    showToast('error', 'Network error during import.');
                });
        });
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>