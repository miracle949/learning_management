<?php
// Deterministic color pick for subject icons — purely cosmetic,
// same subject always gets the same color. Defined right here so this
// file has zero dependency on any other PHP file.
function sac_icon_color(string $seed): array
{
    $palette = [
        ['bg' => '#fee2e2', 'fg' => '#b91c1c'],
        ['bg' => '#dbeafe', 'fg' => '#1d4ed8'],
        ['bg' => '#dcfce7', 'fg' => '#15803d'],
        ['bg' => '#fef3c7', 'fg' => '#b45309'],
        ['bg' => '#ede9fe', 'fg' => '#6d28d9'],
        ['bg' => '#cffafe', 'fg' => '#0e7490'],
        ['bg' => '#fce7f3', 'fg' => '#be185d'],
    ];
    $index = crc32($seed) % count($palette);
    return $palette[$index];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Access Control</title>
    <link rel="stylesheet" href="../css_folder/subject_access.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../admin_folder/sidebar.php"); ?>

        <div class="rightbar">
            <div class="sac-header">
                <h2>Subject Access Control</h2>
                <p>Editing under
                    <strong><?= $curriculumType === 'new' ? 'New Curriculum (2026+)' : 'Legacy Curriculum (pre-2026)' ?></strong>.
                    Turn subjects on or off for an entire grade &amp; section — every student in it updates at once.</p>
            </div>

            <!-- TOOLBAR: search + grade + section, all optional narrowing
                 filters. With nothing selected, every section's panel shows. -->
            <form class="sac-toolbar" method="get" action="/learning_management/public/" id="sacFilterForm">
                <input type="hidden" name="url" value="subject_access">
                <input type="hidden" name="curriculum" value="<?= htmlspecialchars($curriculumType) ?>">

                <div class="input-group">
                    <div class="input-group-text">
                        <i class="fa fa-magnifying-glass"></i>
                    </div>
                    <input type="text" name="search" id="sacSearchInput" class="form-control form-control-sm"
                        placeholder="Search section..." value="<?= htmlspecialchars($search) ?>">
                </div>

                <select name="grade" id="sacGradeSelect" class="form-select form-select-sm sac-grade-select">
                    <option value="" <?= $gradeFilter === '' ? 'selected' : '' ?>>All grades</option>
                    <?php foreach ($gradeLevels as $gl): ?>
                        <option value="<?= htmlspecialchars($gl['name']) ?>"
                            <?= strtolower($gradeFilter) === strtolower($gl['name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($gl['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select name="section_id" id="sacSectionSelect" class="form-select form-select-sm sac-section-select">
                    <option value="">All sections</option>
                    <?php
                    $grouped = [];
                    foreach ($sections as $sec) {
                        $grouped[$sec['grade_level']][] = $sec;
                    }
                    ?>
                    <?php foreach ($grouped as $gradeName => $secList): ?>
                        <optgroup label="<?= htmlspecialchars($gradeName) ?>">
                            <?php foreach ($secList as $sec): ?>
                                <option value="<?= (int) $sec['id'] ?>"
                                    <?= (int) $sec['id'] === $selectedSectionId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sec['section_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>

                <span class="sac-result-count">
                    <?= count($sectionPanels) ?> section<?= count($sectionPanels) === 1 ? '' : 's' ?>
                </span>
            </form>

            <!-- PANELS: one full subject panel per matching section, all shown at once -->
            <?php if (empty($sectionPanels)): ?>
                <div class="sac-panel-placeholder">
                    <i class="fa fa-inbox"></i>
                    <p>No sections match your search or filters.</p>
                </div>
            <?php else: ?>
                <?php foreach ($sectionPanels as $panelData): ?>
                    <?php
                    $section = $panelData['section'];
                    $coreSubjects = $panelData['coreSubjects'];
                    $electiveGroups = $panelData['electiveGroups'];
                    $sectionId = (int) $section['id'];
                    ?>
                    <div class="sac-panel" data-section-panel="<?= $sectionId ?>">

                        <div class="sac-panel-header">
                            <div>
                                <h4><?= htmlspecialchars($section['section_name']) ?></h4>
                                <p class="text-muted mb-0">
                                    <?= htmlspecialchars($section['grade_level']) ?>
                                    <?php if (!empty($section['teacher_names'])): ?>
                                        · Adviser: <?= htmlspecialchars($section['teacher_names']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="sac-panel-stats">
                                <div><strong><?= (int) $section['student_count'] ?></strong><span>Students</span>
                                </div>
                                <div>
                                    <strong data-elective-count-for="<?= $sectionId ?>"><?= (int) $section['elective_enabled'] ?>/<?= (int) $section['elective_total'] ?></strong><span>Electives
                                        on</span></div>
                            </div>
                        </div>

                        <div class="sac-section-block">
                            <div class="sac-block-title">Core Subjects (all strands) · always on</div>
                            <div class="sac-subject-grid">
                                <?php foreach ($coreSubjects as $subj): ?>
                                    <?php $color = sac_icon_color('core-' . $subj['id']); ?>
                                    <div class="sac-subject-card sac-subject-fixed">
                                        <div class="parent-subject">
                                            <div class="sac-subject-icon" style="background:<?= $color['bg'] ?>;color:<?= $color['fg'] ?>;">
                                                <i class="fa fa-graduation-cap"></i>
                                            </div>
                                            <div class="sac-subject-main">
                                                <div class="sac-subject-name"><?= htmlspecialchars($subj['subject_name']) ?> <span
                                                        class="badge bg-warning-subtle text-warning-emphasis ms-1"><i
                                                            class="fa fa-lock"></i> Fixed</span></div>
                                                <div class="sac-subject-status">Included for all students</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (empty($coreSubjects)): ?>
                                    <div class="text-muted small">No core subjects defined for this grade/curriculum.</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (empty($electiveGroups)): ?>
                            <div class="sac-section-block">
                                <div class="text-muted small">No teachers are currently assigned to teach an elective in this section.</div>
                            </div>
                        <?php endif; ?>

                        <?php foreach ($electiveGroups as $group): ?>
                            <div class="sac-section-block">
                                <div class="sac-block-header">
                                    <div class="sac-block-title"><?= htmlspecialchars($group['strand_group']) ?></div>
                                    <div class="sac-bulk-actions">
                                        <button type="button" class="sac-bulk-btn enabled"
                                            data-section-id="<?= $sectionId ?>"
                                            data-grade-level-id="<?= (int) $section['grade_level_id'] ?>"
                                            data-strand-group="<?= htmlspecialchars($group['strand_group']) ?>"
                                            data-enabled="1">Enable all</button>
                                        <button type="button" class="sac-bulk-btn disabled"
                                            data-section-id="<?= $sectionId ?>"
                                            data-grade-level-id="<?= (int) $section['grade_level_id'] ?>"
                                            data-strand-group="<?= htmlspecialchars($group['strand_group']) ?>"
                                            data-enabled="0">Disable all</button>
                                    </div>
                                </div>
                                <div class="sac-subject-grid">
                                    <?php foreach ($group['subjects'] as $subj): ?>
                                        <?php $color = sac_icon_color((string) $subj['id']); ?>
                                        <div class="sac-subject-card <?= $subj['is_enabled'] ? 'sac-subject-on' : '' ?>">
                                            <div class="parent-subject">
                                                <div class="sac-subject-icon" style="background:<?= $color['bg'] ?>;color:<?= $color['fg'] ?>;">
                                                    <i class="fa fa-book"></i>
                                                </div>
                                                <div class="sac-subject-main">
                                                    <div class="sac-subject-name"><?= htmlspecialchars($subj['subject_name']) ?></div>
                                                    <div class="sac-subject-status">
                                                        <?= $subj['is_enabled'] ? 'Visible to this section' : 'Hidden from this section' ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input sac-subject-toggle" type="checkbox" role="switch"
                                                    data-section-id="<?= $sectionId ?>"
                                                    data-subject-id="<?= (int) $subj['id'] ?>"
                                                    data-grade-level-id="<?= (int) $section['grade_level_id'] ?>"
                                                    <?= $subj['is_enabled'] ? 'checked' : '' ?>>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        const CURRICULUM = "<?= $curriculumType ?>";

        // Search, grade, and section are all part of ONE form -> full page
        // reload with the right query params, so they combine correctly.
        let searchTimer;
        document.getElementById('sacSearchInput').addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                document.getElementById('sacFilterForm').submit();
            }, 500);
        });

        document.getElementById('sacGradeSelect').addEventListener('change', function () {
            document.getElementById('sacFilterForm').submit();
        });

        document.getElementById('sacSectionSelect').addEventListener('change', function () {
            document.getElementById('sacFilterForm').submit();
        });

        // Toggling one subject only needs to flip that one card's label —
        // no page reload, just a small JSON response.
        document.addEventListener('change', function (e) {
            if (!e.target.matches('.sac-subject-toggle')) return;

            const toggle = e.target;
            const sectionId = toggle.dataset.sectionId;
            const subjectId = toggle.dataset.subjectId;
            const gradeLevelId = toggle.dataset.gradeLevelId;
            const enabled = toggle.checked ? '1' : '0';

            const card = toggle.closest('.sac-subject-card');
            const label = card.querySelector('.sac-subject-status');
            label.textContent = "Saving...";

            const body = new URLSearchParams({
                section_id: sectionId, subject_id: subjectId,
                grade_level_id: gradeLevelId, curriculum: CURRICULUM, enabled
            });

            fetch("/learning_management/public/?url=toggle_subject_access", { method: 'POST', body })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        label.textContent = toggle.checked ? 'Visible to this section' : 'Hidden from this section';
                        card.classList.toggle('sac-subject-on', toggle.checked);
                        const counter = document.querySelector(`[data-elective-count-for="${sectionId}"]`);
                        if (counter) counter.textContent = data.counts.enabled + "/" + data.counts.total;
                    } else {
                        toggle.checked = !toggle.checked;
                        label.textContent = data.message || 'Failed to update.';
                    }
                });
        });

        // Bulk enable/disable changes several subjects in one section at
        // once, so the simplest correct thing is to just reload the page.
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.sac-bulk-btn');
            if (!btn) return;

            btn.disabled = true;
            btn.textContent = 'Saving...';

            const body = new URLSearchParams({
                section_id: btn.dataset.sectionId,
                strand_group: btn.dataset.strandGroup,
                grade_level_id: btn.dataset.gradeLevelId,
                curriculum: CURRICULUM,
                enabled: btn.dataset.enabled
            });

            fetch("/learning_management/public/?url=bulk_toggle_subject_access", { method: 'POST', body })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        btn.disabled = false;
                        btn.textContent = btn.dataset.enabled === '1' ? 'Enable all' : 'Disable all';
                    }
                });
        });
    </script>
</body>

</html>