<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Works</title>
    <link rel="stylesheet" href="../css_folder/student_works.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <?php
    // Get parameters passed from records.php
    $assignment_id = isset($_GET['assignment_id']) ? (int) $_GET['assignment_id'] : 0;
    $subject_id = isset($_GET['subject_id']) ? (int) $_GET['subject_id'] : 0;

    // Reuse your existing TeacherController/model
    require_once "../app/models/Teacher.php";
    $teacherModel = new Teacher();

    // Fetch assignment info and its submissions
    $assignmentInfo = $assignment_id ? $teacherModel->getAssignmentById($assignment_id) : null;
    $submissions = $assignment_id ? $teacherModel->getSubmissions($assignment_id) : [];
    ?>

    <div class="container-fluid p-0">
        <?php include("sidebar.php"); ?>

        <div class="rightbar">
            <?php include("nav.php"); ?>

            <main>
                <div class="sidebar-works">
                    <div class="student-sub-header">
                        <i class="fa fa-users"></i>
                        <p>All Students</p>
                    </div>

                    <h5>Submitted Works</h5>

                    <div class="sidebar-list">
                        <?php foreach ($submissions as $loop_index => $sub):
                            $initials = strtoupper(substr($sub['student_name'] ?? 'S', 0, 1));
                            ?>
                            <div class="student"
                                onclick="window.location.href='/learning_management/public/?url=works&assignment_id=<?= $assignment_id ?>&subject_id=<?= $subject_id ?>&student_index=<?= $loop_index ?>'">
                                <div class="name">
                                    <div class="icon"><span><?= $initials ?></span></div>
                                    <p><?= htmlspecialchars($sub['student_name'] ?? '—') ?></p>
                                </div>
                                <div class="grade">
                                    <p>
                                        <?= $sub['points_earned'] !== null && $sub['points_earned'] !== ''
                                            ? (int) $sub['points_earned']
                                            : '__' ?>
                                        /<?= (int) ($assignmentInfo['points'] ?? 100) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($submissions)): ?>
                            <p style="font-size:13px;color:#9ca3af;padding:1rem;">No submissions yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="rightbar-works">
                    <div class="assignment-name">
                        <h5><?= htmlspecialchars($assignmentInfo['title'] ?? 'Assignment') ?></h5>
                    </div>
                    <div class="rightbar-submit">
                        <p id="due-display">
                            <?php if (!empty($assignmentInfo['due_date'])): ?>
                                Submission closed
                                <?= date('M d, h:i A', strtotime($assignmentInfo['due_date'] . ' ' . ($assignmentInfo['due_time'] ?? '00:00:00'))) ?>
                            <?php else: ?>
                                No due date set
                            <?php endif; ?>
                        </p>
                        <i class="fa fa-pen" id="edit-due-btn" title="Edit due date" style="cursor:pointer;"></i>
                    </div>

                    <!-- Edit Due Date Modal -->
                    <div id="due-modal"
                        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:9999;align-items:center;justify-content:center;">
                        <div
                            style="background:#fff;border-radius:12px;padding:28px 32px;width:340px;box-shadow:0 8px 32px rgba(0,0,0,.15);">
                            <h5 style="margin:0 0 18px;font-size:16px;font-weight:700;color:#111827;">Edit Due Date &
                                Time</h5>
                            <div style="margin-bottom:14px;">
                                <label
                                    style="font-size:13px;color:#6b7280;font-weight:500;display:block;margin-bottom:4px;">Due
                                    Date</label>
                                <input type="date" id="input-due-date"
                                    value="<?= htmlspecialchars($assignmentInfo['due_date'] ?? '') ?>"
                                    style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;outline:none;">
                            </div>
                            <div style="margin-bottom:20px;">
                                <label
                                    style="font-size:13px;color:#6b7280;font-weight:500;display:block;margin-bottom:4px;">Due
                                    Time</label>
                                <input type="time" id="input-due-time"
                                    value="<?= htmlspecialchars($assignmentInfo['due_time'] ?? '23:59') ?>"
                                    style="width:100%;padding:8px 12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;outline:none;">
                            </div>
                            <div style="display:flex;gap:10px;justify-content:flex-end;">
                                <button id="cancel-due-btn"
                                    style="padding:8px 18px;border:1px solid #d1d5db;background:#fff;border-radius:8px;font-size:14px;cursor:pointer;color:#374151;">
                                    Cancel
                                </button>
                                <button id="save-due-btn"
                                    style="padding:8px 18px;background:#00C950;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
                                    Save
                                </button>
                            </div>
                            <p id="due-save-error" style="color:#C82525;font-size:13px;margin:10px 0 0;display:none;">
                            </p>
                        </div>
                    </div>

                    <div class="parent-submit-file">
                        <?php foreach ($submissions as $loop_index => $sub):
                            $initials = strtoupper(substr($sub['student_name'] ?? 'S', 0, 1));
                            $filePath = $sub['file_path'] ?? '';
                            $fileName = basename($filePath);
                            $cleanName = preg_replace('/^[a-f0-9]+_/', '', $fileName);
                            if ($filePath && !str_starts_with($filePath, '/') && !str_starts_with($filePath, 'http')) {
                                $filePath = '/learning_management/' . $filePath;
                            }
                            $worksUrl = "/learning_management/public/?url=works&assignment_id={$assignment_id}&subject_id={$subject_id}&student_index={$loop_index}";
                            ?>
                            <div class="student-submit" style="cursor:pointer"
                                onclick="window.location.href='<?= $worksUrl ?>'">
                                <div class="student-header">
                                    <div class="icon"><span><?= $initials ?></span></div>
                                    <p><?= htmlspecialchars($sub['student_name'] ?? '—') ?></p>
                                </div>
                                <div class="student-body">
                                    <div class="file-box">
                                        <i class="fa fa-file-pdf" style="font-size:2rem;color:#e53e3e;"></i>
                                    </div>
                                    <p style="font-weight: 500;"><?= htmlspecialchars($cleanName) ?></p>
                                    <p style="color: var(--green); font-weight: 600; margin: 5px 0 0;">Submitted</p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($submissions)): ?>
                            <div style="grid-column:1/-1;text-align:center;padding:1rem;color:#9ca3af; display: flex; align-items: center; gap: 1rem;">
                                <i class="fa fa-inbox"
                                    style="font-size:2rem;opacity:.3;display:block;"></i>
                                <p class="m-0">No submissions yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>

    </div>

    <!-- Save Toast -->
    <div id="saveToast" style="
        display:none;
        position:fixed;
        bottom:28px;
        right:28px;
        background:#111827;
        color:#fff;
        padding:13px 22px;
        border-radius:50px;
        font-size:13px;
        font-weight:600;
        z-index:10001;
        box-shadow:0 4px 20px rgba(0,0,0,.25);
        align-items:center;
        gap:10px;
    ">
        <span style="color:#00C950;font-size:16px;">✓</span>
        <span id="saveToastMsg">Due date updated!</span>
    </div>

    <script>
        function showSaveToast(msg) {
            const toast = document.getElementById('saveToast');
            const msgEl = document.getElementById('saveToastMsg');
            msgEl.textContent = msg;
            toast.style.display = 'flex';
            toast.style.opacity = '1';
            toast.style.transition = '';
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity .4s ease';
                setTimeout(() => {
                    toast.style.display = 'none';
                    toast.style.opacity = '1';
                    toast.style.transition = '';
                }, 400);
            }, 3000);
        }

        const modal = document.getElementById('due-modal');
        const editBtn = document.getElementById('edit-due-btn');
        const cancelBtn = document.getElementById('cancel-due-btn');
        const saveBtn = document.getElementById('save-due-btn');
        const dueDateInput = document.getElementById('input-due-date');
        const dueTimeInput = document.getElementById('input-due-time');
        const dueDisplay = document.getElementById('due-display');
        const errorMsg = document.getElementById('due-save-error');
        const assignmentId = <?= (int) $assignment_id ?>;

        editBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
            errorMsg.style.display = 'none';
        });

        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });

        saveBtn.addEventListener('click', async () => {
            const dueDate = dueDateInput.value;
            const dueTime = dueTimeInput.value || '23:59';

            if (!dueDate) {
                errorMsg.textContent = 'Please select a due date.';
                errorMsg.style.display = 'block';
                return;
            }

            saveBtn.textContent = 'Saving...';
            saveBtn.disabled = true;
            errorMsg.style.display = 'none';

            try {
                const formData = new FormData();
                formData.append('assignment_id', assignmentId);
                formData.append('due_date', dueDate);
                formData.append('due_time', dueTime + ':00');

                const res = await fetch('/learning_management/public/?url=update_due_date', {
                    method: 'POST',
                    body: formData
                });

                if (!res.ok) {
                    throw new Error('Server returned ' + res.status);
                }

                const text = await res.text();

                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Non-JSON response:', text);
                    errorMsg.textContent = 'Server error. Check console for details.';
                    errorMsg.style.display = 'block';
                    saveBtn.textContent = 'Save';
                    saveBtn.disabled = false;
                    return;
                }

                if (data.success) {
                    // Format client-side so display updates immediately and correctly
                    const [year, month, day] = dueDateInput.value.split('-');
                    const [hour, minute] = dueTimeInput.value.split(':');
                    const dateObj = new Date(year, month - 1, day, hour, minute);
                    const formatted = dateObj.toLocaleString('en-US', {
                        month: 'short', day: '2-digit',
                        hour: '2-digit', minute: '2-digit', hour12: true
                    });
                    dueDisplay.textContent = 'Submission closed ' + formatted;
                    modal.style.display = 'none';
                    showSaveToast('Due date updated successfully!');
                } else {
                    errorMsg.textContent = data.message || 'Something went wrong.';
                    errorMsg.style.display = 'block';
                }
            } catch (err) {
                console.error('Fetch error:', err);
                errorMsg.textContent = 'Network error: ' + err.message;
                errorMsg.style.display = 'block';
            }

            saveBtn.textContent = 'Save';
            saveBtn.disabled = false;
        });
    </script>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

</body>

</html>