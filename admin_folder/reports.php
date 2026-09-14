<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../css_folder/reports.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>


    <div class="container-fluid p-0">
        <?php include("../admin_folder/sidebar.php") ?>

        <div class="rightbar">

            <?php
            // Helper: is a given report card visible under the current selection?
            $isRTVisible = function (string $key) use ($showAll, $reportTypes) {
                return $showAll || in_array($key, $reportTypes, true);
            };

            // $reportTypeProvided (from controller) tells us whether the admin has
            // EVER submitted a report_type in the URL. On a true first visit this is
            // false, so the checkboxes render unchecked and the button shows
            // "Choose type" instead of defaulting to "All Reports".
            $rtAllChecked = $reportTypeProvided && $showAll;
            ?>

            <!-- ===== Page Header ===== -->
            <div class="reports-header">
                <div>
                    <h2>Reports</h2>
                    <p class="text-muted mb-0">All student, teacher, section, and subject records in one place</p>
                </div>
                <!-- <div class="reports-header-actions">
                    <button class="btn btn-outline-secondary" id="btnPrint">
                        <i class="fa fa-print me-1"></i> Print
                    </button>
                </div> -->
            </div>

            <!-- ===== Daily / Monthly Tabs ===== -->
            <ul class="nav report-tabs">
                <li class="nav-item">
                    <a class="nav-link <?= $period === 'daily' ? 'active' : '' ?>" href="?url=Reports&period=daily">
                        <i class="fa fa-calendar-day me-1"></i> Daily Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $period === 'monthly' ? 'active' : '' ?>" href="?url=Reports&period=monthly">
                        <i class="fa fa-calendar-alt me-1"></i> Monthly Report
                    </a>
                </li>
            </ul>

            <!-- ===== Filter Card ===== -->
            <form class="filter-card" method="GET" action="" id="reportsFilterForm">
                <input type="hidden" name="url" value="Reports">
                <input type="hidden" name="period" value="<?= htmlspecialchars($period) ?>">

                <?php if ($period === 'monthly'): ?>
                    <div class="filter-field">
                        <label>From</label>
                        <input type="date" name="date_from" class="form-control" value="<?= htmlspecialchars($dateFrom) ?>">
                    </div>
                    <div class="filter-field">
                        <label>To</label>
                        <input type="date" name="date_to" class="form-control" value="<?= htmlspecialchars($dateTo) ?>">
                    </div>
                <?php else: ?>
                    <div class="filter-field">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
                    </div>
                <?php endif; ?>

                <!-- ===== Report type: multi-select via checkboxes ===== -->
                <div class="filter-field">
                    <label><i class="fa fa-list me-1"></i> Report type</label>
                    <div class="dropdown">
                        <button type="button" class="form-select text-start" id="reportTypeBtn"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <span id="reportTypeLabel">Choose type</span>
                            <!-- <i class="fa fa-chevron-down float-end mt-1"></i> -->
                        </button>
                        <ul class="dropdown-menu p-2" style="min-width: 220px;" aria-labelledby="reportTypeBtn">
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input rt-checkbox" type="checkbox" name="report_type[]"
                                        value="all" id="rt_all" <?= $rtAllChecked ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="rt_all"><strong>All Reports</strong></label>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <?php
                            $rtOptions = [
                                'students' => 'Students',
                                'masterlist' => 'Masterlist',
                                'teachers' => 'Teachers',
                                'sections' => 'Sections',
                                'subjects' => 'Subjects',
                            ];
                            foreach ($rtOptions as $rtKey => $rtLabel):
                                $rtChecked = $reportTypeProvided && !$showAll && in_array($rtKey, $reportTypes, true);
                                ?>
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input rt-checkbox" type="checkbox" name="report_type[]"
                                            value="<?= $rtKey ?>" id="rt_<?= $rtKey ?>" <?= $rtChecked ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="rt_<?= $rtKey ?>"><?= $rtLabel ?></label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="button" class="btn btn-primary apply-btn" id="btnGenerate" disabled
                        style="font-size: 14px; font-weight: 600;">
                        <i class="fa fa-file me-1"></i> Generate Reports
                    </button>
                    <button type="button" class="btn btn-outline-primary apply-btn" id="btnExportAll" disabled
                        style="font-size: 14px; font-weight: 600;">
                        <i class="fa fa-file-csv me-1"></i> Export CSV
                    </button>
                </div>

                <!-- <div id="filterRequiredError" class="text-danger small w-100 mt-2 d-none"></div> -->
            </form>

            <p class="filter-note">
                <i class="fa fa-info-circle me-1"></i>
                <?php if ($period === 'monthly'): ?>
                    Sections tagged <strong>"By period"</strong> are filtered between the selected From/To dates —
                    sections tagged <strong>"Current structure"</strong> always show current data.
                <?php else: ?>
                    Date filters only affect sections tagged <strong>"By period"</strong> — sections tagged
                    <strong>"Current structure"</strong> always show current data.
                <?php endif; ?>
            </p>

            <!-- ===== Selected report-type summary (updates live via JS) ===== -->
            <div id="selectedTypesDisplay" class="selected-types-display text-muted small mb-3"></div>

            <!-- ===== Quick nav pills ===== -->
            <div class="quick-nav">
                <a href="#students-report"
                    class="quick-nav-pill <?= $isRTVisible('students') ? '' : 'd-none' ?>">Students</a>
                <a href="#masterlist-report"
                    class="quick-nav-pill <?= $isRTVisible('masterlist') ? '' : 'd-none' ?>">Masterlist</a>
                <a href="#teachers-report"
                    class="quick-nav-pill <?= $isRTVisible('teachers') ? '' : 'd-none' ?>">Teachers</a>
                <a href="#sections-report"
                    class="quick-nav-pill <?= $isRTVisible('sections') ? '' : 'd-none' ?>">Sections</a>
                <a href="#subjects-report"
                    class="quick-nav-pill <?= $isRTVisible('subjects') ? '' : 'd-none' ?>">Subjects</a>
            </div>

            <!-- ===================================================== -->
            <!-- STUDENTS -->
            <!-- ===================================================== -->
            <div class="report-card <?= $isRTVisible('students') ? '' : 'd-none' ?>" id="students-report">
                <div class="report-card-header">
                    <div class="report-card-left">
                        <div class="report-card-icon icon-blue"><i class="fa fa-user-graduate"></i></div>
                        <div class="report-card-title">
                            <h5>Students <span class="badge-tag">By period</span></h5>
                            <p>Enrolled accounts &middot; SY <?= htmlspecialchars($currentSchoolYear) ?></p>
                        </div>
                    </div>
                    <span class="report-count"><?= (int) $totalStudents ?> active accounts</span>
                </div>

                <div class="report-subheader">
                    <span><i class="fa fa-user-check me-1"></i> Enrolled Students</span>
                    <span class="report-count"><?= (int) $totalStudents ?> active accounts</span>
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-target="studentsEnrolledTable"
                        data-name="enrolled_students">
                        <i class="fa fa-file-export me-1"></i> Export CSV
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table report-table" id="studentsEnrolledTable">
                        <thead>
                            <tr>
                                <th>Student LRN</th>
                                <th>Name</th>
                                <th>Grade Level</th>
                                <th>Section</th>
                                <th>School Year</th>
                                <th>Date Enrolled</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">No enrolled students found.</td>
                                </tr> <!-- colspan 5→6 -->
                            <?php else: ?>
                                <?php foreach ($students as $s): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($s['student_LRN']) ?></td>
                                        <td><?= htmlspecialchars($s['name']) ?></td>
                                        <td><?= htmlspecialchars($s['grade_level']) ?></td>
                                        <td><?= htmlspecialchars($s['section_name']) ?></td>
                                        <td><?= htmlspecialchars($currentSchoolYear) ?></td>
                                        <td><?= !empty($s['date_enrolled']) ? htmlspecialchars(date('M j, Y', strtotime($s['date_enrolled']))) : '-' ?>
                                        </td> <!-- ADD -->
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- MASTERLIST -->
            <!-- ===================================================== -->
            <div class="report-card <?= $isRTVisible('masterlist') ? '' : 'd-none' ?>" id="masterlist-report">
                <div class="report-card-header">
                    <div class="report-card-title">
                        <h5>Masterlist <span class="badge-tag">By period</span></h5>
                        <p>Imported masterlist &middot; SY <?= htmlspecialchars($currentSchoolYear) ?></p>
                    </div>
                    <span class="report-count"><?= (int) $totalMasterlist ?> imported records</span>
                </div>
                <div class="report-subheader mt-4">
                    <span><i class="fa fa-file-alt me-1"></i> Masterlist</span>
                    <span class="report-count">
                        <?= (int) $totalMasterlist ?> records &middot;
                        <?= (int) $mlTotalEnrolled ?> enrolled &middot;
                        <?= (int) $mlTotalPending ?> pending
                    </span>
                    <button class="btn btn-sm btn-outline-secondary export-btn" data-target="masterlistTable"
                        data-name="masterlist">
                        <i class="fa fa-file-export me-1"></i> Export CSV
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table report-table" id="masterlistTable">
                        <thead>
                            <tr>
                                <th>LRN</th>
                                <th>Name</th>
                                <th>Grade Level</th>
                                <th>Section</th>
                                <th>Strand</th>
                                <th>School Year</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($masterlist)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">No masterlist records found.</td>
                                </tr> <!-- colspan 7→8 -->
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
                                        <td><?= !empty($row['imported_at']) ? htmlspecialchars(date('M j, Y', strtotime($row['imported_at']))) : '-' ?>
                                        </td> <!-- ADD -->
                                        <td>
                                            <?= $isMatched
                                                ? '<span class="status-chip enrolled">Enrolled</span>'
                                                : '<span class="status-chip pending">Pending</span>' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- TEACHERS -->
            <!-- ===================================================== -->
            <div class="report-card <?= $isRTVisible('teachers') ? '' : 'd-none' ?>" id="teachers-report">
                <div class="report-card-header">
                    <div class="report-card-icon icon-green"><i class="fa fa-chalkboard-teacher"></i></div>
                    <div class="report-card-left">
                        <div class="report-card-title">
                            <h5>Teachers <span class="badge-tag">Current structure</span></h5>
                            <p>Grade level, sections, and status</p>
                        </div>
                    </div>
                    <span class="report-count"><?= (int) $totalTeachers ?> registered</span>
                    <!-- <button class="btn btn-sm btn-outline-secondary export-btn ms-auto" data-target="teachersTable"
                        data-name="teachers">
                        <i class="fa fa-file-export me-1"></i> Export CSV
                    </button> -->
                </div>
                <div class="table-responsive">
                    <table class="table report-table" id="teachersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Grade Level</th>
                                <th>Sections</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($teachers)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No teachers found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($teachers as $t): ?>
                                    <?php
                                    $gradeLevels = !empty($t['grade_levels']) ? implode(', ', (array) $t['grade_levels']) : '-';
                                    $sectionNames = !empty($t['sections']) ? implode(', ', (array) $t['sections']) : '-';
                                    $status = $t['status'] ?? ($t['teacher_status'] ?? 'Active');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($t['name']) ?></td>
                                        <td><?= htmlspecialchars($t['username']) ?></td>
                                        <td><?= htmlspecialchars($gradeLevels) ?></td>
                                        <td><?= htmlspecialchars($sectionNames) ?></td>
                                        <td>
                                            <span
                                                class="status-chip <?= strtolower($status) === 'active' ? 'enrolled' : 'pending' ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- SECTIONS -->
            <!-- ===================================================== -->
            <div class="report-card <?= $isRTVisible('sections') ? '' : 'd-none' ?>" id="sections-report">
                <div class="report-card-header">
                    <div class="report-card-icon icon-purple"><i class="fa fa-layer-group"></i></div>
                    <div class="report-card-left">
                        <div class="report-card-title">
                            <h5>Sections <span class="badge-tag">Current structure</span></h5>
                            <p>Assigned teachers and student counts</p>
                        </div>
                    </div>
                    <span class="report-count"><?= (int) $totalSections ?> sections</span>
                    <!-- <button class="btn btn-sm btn-outline-secondary export-btn ms-auto" data-target="sectionsTable"
                        data-name="sections">
                        <i class="fa fa-file-export me-1"></i> Export CSV
                    </button> -->
                </div>
                <div class="table-responsive">
                    <table class="table report-table" id="sectionsTable">
                        <thead>
                            <tr>
                                <th>Section Name</th>
                                <th>Grade Level</th>
                                <th>Teachers Assigned</th>
                                <th>Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($sections)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No sections found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sections as $sec): ?>
                                    <?php
                                    $teacherNames = !empty($sec['teacher_names']) ? $sec['teacher_names'] : null;
                                    $studentCount = (int) ($sec['student_count'] ?? 0);
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sec['section_name']) ?></td>
                                        <td><?= htmlspecialchars($sec['grade_level']) ?></td>
                                        <td>
                                            <?= $teacherNames
                                                ? htmlspecialchars($teacherNames)
                                                : '<span class="text-muted fst-italic">No teacher assigned yet</span>' ?>
                                        </td>
                                        <td>
                                            <span class="status-chip <?= $studentCount > 0 ? 'enrolled' : 'pending' ?>">
                                                <?= $studentCount ?> students
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===================================================== -->
            <!-- SUBJECTS -->
            <!-- ===================================================== -->
            <div class="report-card <?= $isRTVisible('subjects') ? '' : 'd-none' ?>" id="subjects-report">
                <div class="report-card-header">
                    <div class="report-card-icon icon-orange"><i class="fa fa-book"></i></div>
                    <div class="report-card-left">
                        <div class="report-card-title">
                            <h5>Subjects <span class="badge-tag">Current structure</span></h5>
                            <p>Offered per grade level</p>
                        </div>
                    </div>
                    <span class="report-count"><?= (int) $totalSubjects ?> subjects</span>
                    <!-- <button class="btn btn-sm btn-outline-secondary export-btn ms-auto" data-target="subjectsTable"
                        data-name="subjects">
                        <i class="fa fa-file-export me-1"></i> Export CSV
                    </button> -->
                </div>
                <div class="table-responsive">
                    <table class="table report-table" id="subjectsTable">
                        <thead>
                            <tr>
                                <th>Subject Name</th>
                                <th>Grade Level</th>
                                <th>Teachers Assigned</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($subjects)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No subjects found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($subjects as $subj): ?>
                                    <?php $teacherCount = (int) ($subj['teacher_count'] ?? 0); ?>
                                    <tr>
                                        <td><?= htmlspecialchars($subj['subject_name']) ?></td>
                                        <td><?= htmlspecialchars($subj['grade_level']) ?></td>
                                        <td>
                                            <span class="status-chip <?= $teacherCount > 0 ? 'enrolled' : 'pending' ?>">
                                                <?= $teacherCount ?> teacher<?= $teacherCount === 1 ? '' : 's' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ================================================================
            // Core element references — declared FIRST so nothing below can
            // reference them before initialization (this was the bug: a call
            // to refreshActionButtons() ran before these were declared).
            // ================================================================
            const form = document.getElementById('reportsFilterForm');
            const btnGenerate = document.getElementById('btnGenerate');
            const btnExportAll = document.getElementById('btnExportAll');
            const filterRequiredError = document.getElementById('filterRequiredError');

            const schoolName = <?= json_encode($schoolName ?? 'SHS Strand') ?>;
            const generatedByName = <?= json_encode($_SESSION['name'] ?? ($_SESSION['username'] ?? 'Administrator')) ?>;

            // ----- Print (page as-is) -----
            document.getElementById('btnPrint')?.addEventListener('click', function () {
                window.print();
            });

            // ----- CSV helpers -----
            function tableToCSV(tableId) {
                const table = document.getElementById(tableId);
                if (!table) return '';

                const rows = Array.from(table.querySelectorAll('tr'));
                const csv = rows.map(row => {
                    const cells = Array.from(row.querySelectorAll('th, td'));
                    return cells.map(cell => {
                        let text = cell.innerText.replace(/\s+/g, ' ').trim();
                        text = text.replace(/"/g, '""');
                        return `"${text}"`;
                    }).join(',');
                }).join('\n');

                return csv;
            }

            function downloadCSV(csv, filename) {
                const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', filename + '.csv');
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }

            // ----- Per-table Export CSV buttons (unchanged behavior) -----
            document.querySelectorAll('.export-btn, .export-all-link').forEach(btn => {
                btn.addEventListener('click', function () {
                    const targetId = this.dataset.target;
                    const name = this.dataset.name || 'report';
                    const csv = tableToCSV(targetId);
                    if (csv) downloadCSV(csv, name);
                });
            });

            // ================================================================
            // Report type multi-select (checkbox dropdown)
            // ================================================================
            const rtCheckboxes = Array.from(document.querySelectorAll('.rt-checkbox'));
            const rtAllCheckbox = document.getElementById('rt_all');
            const rtLabelEl = document.getElementById('reportTypeLabel');
            const selectedTypesDisplay = document.getElementById('selectedTypesDisplay');
            const rtLabels = {
                all: 'All Reports', students: 'Students', masterlist: 'Masterlist',
                teachers: 'Teachers', sections: 'Sections', subjects: 'Subjects'
            };

            function getCheckedSpecificTypes() {
                return rtCheckboxes.filter(cb => cb.value !== 'all' && cb.checked);
            }

            // Dropdown button label: "Choose type" until something is picked.
            function updateReportTypeLabel() {
                if (rtAllCheckbox.checked) {
                    rtLabelEl.textContent = 'All Reports';
                    return;
                }
                const checkedSpecific = getCheckedSpecificTypes();
                rtLabelEl.textContent = checkedSpecific.length > 0
                    ? checkedSpecific.map(cb => rtLabels[cb.value]).join(', ')
                    : 'Choose type';
            }

            // Summary line shown under the "By period / Current structure" note.
            function updateSelectedTypesDisplay() {
                if (!selectedTypesDisplay) return;

                if (rtAllCheckbox.checked) {
                    selectedTypesDisplay.innerHTML =
                        '<i class="fa fa-check-circle me-1 text-primary"></i>Showing: <strong>All Reports</strong>';
                    return;
                }

                const checkedSpecific = getCheckedSpecificTypes();
                if (checkedSpecific.length === 0) {
                    selectedTypesDisplay.innerHTML = '';
                    return;
                }

                const names = checkedSpecific.map(cb => rtLabels[cb.value]).join(', ');
                selectedTypesDisplay.innerHTML =
                    '<i class="fa fa-check-circle me-1 text-primary"></i>Showing: <strong>' + names + '</strong>';
            }

            rtCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (this.value === 'all') {
                        if (this.checked) {
                            rtCheckboxes.forEach(other => { if (other !== this) other.checked = false; });
                        }
                        // Unchecking "All" is allowed here — the admin then has to
                        // pick specific types, enforced by refreshActionButtons().
                    } else if (this.checked) {
                        rtAllCheckbox.checked = false;
                    }
                    updateReportTypeLabel();
                    updateSelectedTypesDisplay();
                    refreshActionButtons();
                });
            });

            updateReportTypeLabel();
            updateSelectedTypesDisplay();

            // ================================================================
            // Require BOTH a date (or date range) AND at least one report type
            // before Generate / Export is allowed.
            // ================================================================
            const periodInput = document.querySelector('input[name="period"]');
            const dateInput = document.querySelector('input[name="date"]');
            const dateFromInput = document.querySelector('input[name="date_from"]');
            const dateToInput = document.querySelector('input[name="date_to"]');

            function hasRequiredDate() {
                if (periodInput?.value === 'monthly') {
                    return !!(dateFromInput?.value && dateToInput?.value);
                }
                return !!dateInput?.value;
            }

            function hasRequiredType() {
                return rtCheckboxes.some(cb => cb.checked);
            }

            function getMissingRequirementsMessage() {
                const missing = [];
                if (!hasRequiredDate()) {
                    missing.push(periodInput?.value === 'monthly' ? 'a From and To date' : 'a date');
                }
                if (!hasRequiredType()) {
                    missing.push('a report type');
                }
                if (missing.length === 0) return '';
                return 'Please select ' + missing.join(' and ') + ' before generating or exporting a report.';
            }

            function refreshActionButtons() {
                const message = getMissingRequirementsMessage();
                const ok = message === '';
                btnGenerate.disabled = !ok;
                btnExportAll.disabled = !ok;
                if (filterRequiredError) {
                    filterRequiredError.textContent = message;
                    filterRequiredError.classList.toggle('d-none', ok);
                }
            }

            [dateInput, dateFromInput, dateToInput].forEach(el => {
                el?.addEventListener('input', refreshActionButtons);
            });
            refreshActionButtons();

            // ----- Shared: apply AJAX response data onto the on-page tables -----
            const cardConfig = {
                students: { cardId: 'students-report', tableId: 'studentsEnrolledTable' },
                masterlist: { cardId: 'masterlist-report', tableId: 'masterlistTable' },
                teachers: { cardId: 'teachers-report', tableId: 'teachersTable' },
                sections: { cardId: 'sections-report', tableId: 'sectionsTable' },
                subjects: { cardId: 'subjects-report', tableId: 'subjectsTable' },
            };

            function applyReportData(data) {
                Object.keys(cardConfig).forEach(key => {
                    const cfg = cardConfig[key];
                    const card = document.getElementById(cfg.cardId);
                    const pill = document.querySelector('.quick-nav-pill[href="#' + cfg.cardId + '"]');
                    const section = data.sections ? data.sections[key] : null;
                    const isVisible = !!(section && section.visible);

                    if (card) card.classList.toggle('d-none', !isVisible);
                    if (pill) pill.classList.toggle('d-none', !isVisible);

                    if (!card || !section) return;

                    const tbody = document.querySelector('#' + cfg.tableId + ' tbody');
                    if (tbody) tbody.innerHTML = section.rows_html;

                    const countEls = card.querySelectorAll('.report-count');

                    if (key === 'students') {
                        countEls.forEach(el => el.textContent = section.total + ' active accounts');
                        const syEl = card.querySelector('.report-card-title p');
                        if (syEl) syEl.textContent = 'Enrolled accounts · SY ' + section.school_year;
                    } else if (key === 'masterlist') {
                        const headerCount = card.querySelector('.report-card-header .report-count');
                        if (headerCount) headerCount.textContent = section.total + ' imported records';
                        const subCount = card.querySelector('.report-subheader .report-count');
                        if (subCount) subCount.textContent = section.total + ' records · ' + section.enrolled + ' enrolled · ' + section.pending + ' pending';
                        const syEl = card.querySelector('.report-card-title p');
                        if (syEl) syEl.textContent = 'Imported masterlist · SY ' + section.school_year;
                    } else if (key === 'teachers') {
                        countEls.forEach(el => el.textContent = section.total + ' registered');
                    } else if (key === 'sections') {
                        countEls.forEach(el => el.textContent = section.total + ' sections');
                    } else if (key === 'subjects') {
                        countEls.forEach(el => el.textContent = section.total + ' subjects');
                    }
                });
            }

            function formatDateLabel(value) {
                if (!value) return '—';
                const d = new Date(value + 'T00:00:00');
                if (isNaN(d)) return value;
                return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            }

            // Takes an ARRAY of selected report_type[] values.
            function getReportTypeLabel(types) {
                if (!types || types.length === 0 || types.includes('all')) return 'All Reports';
                return types.map(t => rtLabels[t] || t).join(', ');
            }

            function getPeriodLabel(params) {
                const period = params.get('period') || 'daily';
                if (period === 'monthly') {
                    const from = formatDateLabel(params.get('date_from'));
                    const to = formatDateLabel(params.get('date_to'));
                    return 'Monthly Report · ' + from + ' to ' + to;
                }
                const date = params.get('date') ? formatDateLabel(params.get('date')) : 'All Dates';
                return 'Daily Report · ' + date;
            }

            function buildPrintableReportHTML(params) {
                const reportTypeLabel = getReportTypeLabel(params.getAll('report_type[]'));
                const periodLabel = getPeriodLabel(params);

                const generatedOn = new Date().toLocaleString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit'
                });

                let sectionsHtml = '';
                document.querySelectorAll('.report-card:not(.d-none)').forEach(card => {
                    const table = card.querySelector('table.report-table');
                    if (!table) return;

                    const titleEl = card.querySelector('.report-card-title h5');
                    const title = titleEl ? titleEl.childNodes[0].textContent.trim() : 'Report';
                    const badgeEl = card.querySelector('.report-card-title .badge-tag');
                    const badge = badgeEl ? badgeEl.textContent.trim() : '';
                    const countEl = card.querySelector('.report-card-header .report-count');
                    const count = countEl ? countEl.textContent.trim() : '';

                    sectionsHtml += `
                        <section class="print-section">
                            <div class="print-section-header">
                                <h2>${title}${badge ? ' <span class="print-badge">' + badge + '</span>' : ''}</h2>
                                <span class="print-count">${count}</span>
                            </div>
                            ${table.outerHTML}
                        </section>
                    `;
                });

                return `
                <!DOCTYPE html>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>${schoolName} - ${reportTypeLabel}</title>
                <style>
                    * { box-sizing: border-box; }
                    body { font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a1a; margin: 0; padding: 40px; }
                    .print-header { text-align: center; border-bottom: 3px solid #1a1a1a; padding-bottom: 16px; margin-bottom: 24px; }
                    .print-header h1 { margin: 0 0 4px; font-size: 24px; letter-spacing: 0.5px; }
                    .print-header p { margin: 2px 0; font-size: 13px; color: #444; }
                    .print-meta { display: flex; justify-content: space-between; font-size: 12px; color: #555; margin-bottom: 28px; }
                    .print-section { margin-bottom: 32px; page-break-inside: auto; }
                    table.report-table { width: 100%; border-collapse: collapse; font-size: 12px; page-break-inside: auto; }
                    table.report-table tr { page-break-inside: avoid; }
                    table.report-table thead { display: table-header-group; background: #f0f0f0; }
                    #masterlistTable thead { display: table-row-group; }  
                    .print-section-header { display: flex; justify-content: space-between; align-items: baseline; border-bottom: 1px solid #ccc; padding-bottom: 6px; margin-bottom: 10px; }
                    .print-section-header h2 { font-size: 16px; margin: 0; }
                    .print-badge { font-size: 10px; font-weight: 600; color: #555; border: 1px solid #999; border-radius: 10px; padding: 1px 8px; margin-left: 6px; }
                    .print-count { font-size: 12px; color: #666; }
                    table.report-table { width: 100%; border-collapse: collapse; font-size: 12px; }
                    table.report-table th, table.report-table td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
                    .status-chip { padding: 2px 8px; border-radius: 10px; font-size: 11px; }
                    .status-chip.enrolled { background: #e6f7ec; color: #1a7a3c; }
                    .status-chip.pending { background: #fdf2e0; color: #a35c00; }

                    .print-section { margin-bottom: 20px; page-break-inside: auto; }
                    .print-footer { margin-top: 12px; font-size: 11px; color: #888; text-align: center; border-top: 1px solid #ddd; padding-top: 8px; page-break-before: avoid; break-before: avoid; }
                    @media print {
                        body { padding: 20px; }
                        .print-section { page-break-inside: avoid; }
                    }
                </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>${schoolName}</h1>
                        <p>Official Report</p>
                    </div>
                    <div class="print-meta">
                        <div>
                            <div><strong>Report Type:</strong> ${reportTypeLabel}</div>
                            <div><strong>Coverage:</strong> ${periodLabel}</div>
                        </div>
                        <div>
                            <div><strong>Generated on:</strong> ${generatedOn}</div>
                            <div><strong>Generated by:</strong> ${generatedByName}</div>
                        </div>
                    </div>
                    ${sectionsHtml}
                    <div class="print-footer">This is a system-generated report from ${schoolName}.</div>
                </body>
                </html>`;
            }

            function printReportInPage(params) {
                const html = buildPrintableReportHTML(params);

                let iframe = document.getElementById('reportPrintFrame');
                if (!iframe) {
                    iframe = document.createElement('iframe');
                    iframe.id = 'reportPrintFrame';
                    iframe.style.position = 'fixed';
                    iframe.style.right = '0';
                    iframe.style.bottom = '0';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = '0';
                    document.body.appendChild(iframe);
                }

                const frameWin = iframe.contentWindow;
                let printed = false;
                function triggerPrint() {
                    if (printed) return;
                    printed = true;
                    frameWin.focus();
                    frameWin.print();
                }

                frameWin.document.open();
                frameWin.document.write(html);
                frameWin.document.close();
                setTimeout(triggerPrint, 300);
            }

            document.getElementById('btnPreviewClose')?.addEventListener('click', function () {
                document.getElementById('reportPreviewOverlay').classList.add('d-none');
                document.getElementById('reportPreviewFrame').src = 'about:blank';
            });

            document.getElementById('btnPreviewPrint')?.addEventListener('click', function () {
                const frame = document.getElementById('reportPreviewFrame');
                // Printing the PDF viewer's own content — no injected date/URL header,
                // since this isn't an HTML page being printed.
                frame.contentWindow?.print();
            });

            async function fetchReportData(params) {
                const res = await fetch('?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.message || 'Failed to generate report');
                return data;
            }

            // ===== Generate Reports: refresh tables + open printable "paper" view. NO file download. =====
            async function generateReport() {
                const missingMsg = getMissingRequirementsMessage();
                if (missingMsg) {
                    refreshActionButtons();
                    alert(missingMsg);
                    return;
                }

                const params = new URLSearchParams(new FormData(form));
                params.set('ajax', '1');

                const originalLabel = btnGenerate.innerHTML;
                btnGenerate.disabled = true;
                btnGenerate.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Generating...';

                try {
                    const data = await fetchReportData(params);
                    applyReportData(data);

                    params.delete('ajax');
                    printReportInPage(params);   // was: await generateReportPDF(params)

                    window.history.pushState({}, '', window.location.pathname + '?' + params.toString());

                } catch (err) {
                    console.error(err);
                    alert(err.message || 'Could not generate the report. Please try again.');
                } finally {
                    btnGenerate.innerHTML = originalLabel;
                    refreshActionButtons();
                }
            }

            // ===== Export CSV (all visible sections): refresh tables + download ONE combined CSV file. =====
            async function exportAllCSV() {
                const missingMsg = getMissingRequirementsMessage();
                if (missingMsg) {
                    refreshActionButtons();
                    alert(missingMsg);
                    return;
                }

                const params = new URLSearchParams(new FormData(form));
                params.set('ajax', '1');

                const originalLabel = btnExportAll.innerHTML;
                btnExportAll.disabled = true;
                btnExportAll.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Exporting...';

                try {
                    const data = await fetchReportData(params);
                    applyReportData(data);

                    let combined = '';
                    document.querySelectorAll('.report-card:not(.d-none)').forEach(card => {
                        const table = card.querySelector('table.report-table');
                        if (!table) return;
                        const titleEl = card.querySelector('.report-card-title h5');
                        const title = titleEl ? titleEl.innerText.replace(/\s+/g, ' ').trim() : 'Report';
                        const csv = tableToCSV(table.id);
                        if (csv) combined += title + '\n' + csv + '\n\n';
                    });

                    if (combined) {
                        const period = params.get('period') || 'daily';
                        const typesLabel = params.getAll('report_type[]').includes('all')
                            ? 'all'
                            : (params.getAll('report_type[]').join('_') || 'all');
                        const stamp = period === 'monthly'
                            ? (params.get('date_from') || 'start') + '_to_' + (params.get('date_to') || 'end')
                            : (params.get('date') || 'all_dates');
                        downloadCSV(combined, 'report_' + typesLabel + '_' + period + '_' + stamp);
                    }

                    params.delete('ajax');
                    window.history.pushState({}, '', window.location.pathname + '?' + params.toString());

                } catch (err) {
                    console.error(err);
                    alert(err.message || 'Could not export the report. Please try again.');
                } finally {
                    btnExportAll.innerHTML = originalLabel;
                    refreshActionButtons();
                }
            }

            btnGenerate?.addEventListener('click', generateReport);
            btnExportAll?.addEventListener('click', exportAllCSV);

            form?.addEventListener('submit', function (e) {
                e.preventDefault();
                generateReport();
            });

        });
    </script>
</body>

</html>