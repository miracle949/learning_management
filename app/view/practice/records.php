<!-- INVITE STUDENT MODAL -->
<!-- <div id="inviteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
     z-index:9998;align-items:center;justify-content:center;pointer-events:none;">
            <div style="background:#fff;border-radius:16px;width:90%;max-width:480px;overflow:hidden;
                box-shadow:0 20px 60px rgba(0,0,0,.2);">

                <div
                    style="background:#00C950;padding:18px 22px;display:flex;justify-content:space-between;align-items:center;">
                    <h5 style="color:#fff;margin:0;font-size:16px;font-weight:700;">
                        <i class="fa fa-envelope me-2"></i>Invite Student by Email
                    </h5>
                    <button onclick="closeInviteModal()"
                        style="background:none;border:none;color:#fff;font-size:20px;cursor:pointer;line-height:1;">✕</button>
                </div>

                <form method="POST" action="/learning_management/public/?url=send_invitation">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                    <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">

                    <div style="padding:20px 24px 16px;">


                        <?php if (!empty($_SESSION['invite_error'])): ?>
                            <div style="background:#fee2e2;color:#dc2626;padding:10px 14px;border-radius:8px;
                                font-size:13px;margin-bottom:14px;">
                                <i class="fa fa-circle-exclamation me-1"></i>
                                <?= htmlspecialchars($_SESSION['invite_error']) ?>
                            </div>
                            <?php unset($_SESSION['invite_error']); ?>
                        <?php endif; ?>


                        <label style="font-size:13px;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                            Student's Gmail / Email
                        </label>
                        <input type="email" name="student_email" id="studentEmailInput" required
                            placeholder="e.g. student@gmail.com" style="width:100%;padding:11px 14px;border:1.5px solid #d1d5db;border-radius:10px;
                              font-size:14px;outline:none;box-sizing:border-box;transition:border-color .2s;"
                            onfocus="this.style.borderColor='#00C950'" onblur="this.style.borderColor='#d1d5db'">


                        <?php
                        $approvedStudents = $teacherModel ? $teacherModel->getAllApprovedStudents() : [];
                        ?>
                        <?php if (!empty($approvedStudents)): ?>
                            <div style="margin-top:14px;">
                                <p style="font-size:12px;font-weight:600;color:#6b7280;margin-bottom:8px;">
                                    <i class="fa fa-users me-1"></i> Approved Students — click to fill email:
                                </p>
                                <div style="display:flex;flex-direction:column;gap:6px;max-height:180px;
                                    overflow-y:auto;padding-right:4px;">
                                    <?php foreach ($approvedStudents as $s): ?>
                                        <div onclick="fillEmail('<?= htmlspecialchars($s['email'], ENT_QUOTES) ?>', this)"
                                            style="display:flex;align-items:center;gap:10px;padding:9px 12px;
                                            background:#f9fafb;border:1.5px solid #e4e7eb;border-radius:10px;
                                            cursor:pointer;transition:all .15s;"
                                            onmouseover="this.style.borderColor='#00C950';this.style.background='#f0fdf4'"
                                            onmouseout="if(!this.classList.contains('selected')){this.style.borderColor='#e4e7eb';this.style.background='#f9fafb'}">

                                            <div style="width:34px;height:34px;border-radius:50%;background:#00C950;
                                                color:#fff;display:flex;align-items:center;justify-content:center;
                                                font-size:13px;font-weight:800;flex-shrink:0;">
                                                <?= strtoupper(substr($s['name'], 0, 1)) ?>
                                            </div>

                                            <div style="flex:1;min-width:0;">
                                                <div style="font-size:13px;font-weight:700;color:#111827;
                                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    <?= htmlspecialchars($s['name']) ?>
                                                </div>
                                                <div style="font-size:12px;color:#6b7280;
                                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    <?= htmlspecialchars($s['email']) ?>
                                                </div>
                                            </div>

                                            <i class="fa fa-circle-check" style="color:#00C950;display:none;"
                                                id="check-<?= md5($s['email']) ?>"></i>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p style="font-size:12px;color:#9ca3af;margin-top:12px;background:#f9fafb;
                              padding:10px 14px;border-radius:8px;border:1px dashed #e4e7eb;">
                                <i class="fa fa-info-circle me-1"></i>
                                No approved students found. Students must be approved by admin first.
                            </p>
                        <?php endif; ?>

                        <p style="font-size:12px;color:#9ca3af;margin-top:10px;">
                            Only approved students can receive invitations. They'll get an email with an enrollment
                            link.
                        </p>
                    </div>

                    <div style="padding:0 24px 20px;display:flex;gap:10px;justify-content:flex-end;">
                        <button type="button" onclick="closeInviteModal()" style="padding:10px 20px;border:1px solid #d1d5db;background:#fff;
                               border-radius:50px;font-size:13px;font-weight:600;cursor:pointer;">
                            Cancel
                        </button>
                        <button type="submit" style="padding:10px 22px;background:#00C950;color:#fff;border:none;
                               border-radius:50px;font-size:13px;font-weight:700;cursor:pointer;
                               display:flex;align-items:center;gap:6px;">
                            <i class="fa fa-paper-plane"></i> Send Invitation
                        </button>
                    </div>
                </form>
            </div>
        </div> -->

<div class="col-lg-6 mt-3">
    <label class="cw-label">Priority</label>
    <div class="cw-select-wrap">
        <select name="announcement_priority[]" class="cw-select mt-2">
            <option value="normal">🔵 Normal</option>
            <option value="important">🟠 Important</option>
            <option value="urgent">🔴 Urgent</option>
        </select>
        <i class="fa fa-chevron-down cw-select-icon"></i>
    </div>
</div>
<div class="col-lg-6 mt-3">
    <label class="cw-label">Post Date</label>
    <input type="date" name="announcement_date[]" class="cw-input cw-date mt-2">
</div>

<div class="col-lg-12 mt-4">
    <label class="cw-label">Attach File <span style="color:#9ca3af;font-size:12px;">(optional)</span></label>
    <div class="ann-pdf-list"></div>
    <button type="button" class="btn-cf-add-pdf ann-add-file-btn mt-2">
        <i class="fa fa-plus"></i> Attach File
    </button>
    <input type="file" name="announcement_file[${idx}][]" class="ann-file-input"
        accept=".pdf,.ppt,.pptx,.doc,.docx,image/*" multiple style="display:none;">
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // ===============================
        // INVITE MODAL
        // ===============================
        window.openInviteModal = function () {
            const m = document.getElementById('inviteModal');
            m.style.display = 'flex';
            m.style.pointerEvents = 'auto';  // ← add this
            document.body.style.overflow = 'hidden';
        };

        window.closeInviteModal = function () {
            const m = document.getElementById('inviteModal');
            m.style.display = 'none';
            m.style.pointerEvents = 'none';
            document.body.style.overflow = '';
            const inp = document.getElementById('studentEmailInput');
            if (inp) inp.value = '';
            document.querySelectorAll('.selected-row').forEach(r => {
                r.classList.remove('selected-row');
                r.style.borderColor = '#e4e7eb';
                r.style.background = '#f9fafb';
            });
            document.querySelectorAll('[id^="check-"]').forEach(i => i.style.display = 'none');
        };

        window.fillEmail = function (email, row) {
            document.getElementById('studentEmailInput').value = email;
            document.querySelectorAll('[id^="check-"]').forEach(i => i.style.display = 'none');
            document.querySelectorAll('.invite-student-row').forEach(r => {
                r.classList.remove('selected-row');
                r.style.borderColor = '#e4e7eb';
                r.style.background = '#f9fafb';
            });
            row.style.borderColor = '#00C950';
            row.style.background = '#f0fdf4';
            row.classList.add('selected-row');
            const checkIcon = row.querySelector('.fa-circle-check');
            if (checkIcon) checkIcon.style.display = 'block';
        };

        const inviteModal = document.getElementById('inviteModal');
        if (inviteModal) {
            inviteModal.addEventListener('click', function (e) {
                if (e.target === this) closeInviteModal();
            });
        }

    });
</script>