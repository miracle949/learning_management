<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/activities.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <?php include("../super_admin_folder/nav.php") ?>

            <main>
                <div class="header">
                    <div>
                        <h4>Interactive Modules</h4>
                        <p>Upload, manage and distribute learning content</p>
                    </div>
                    <div>
                        <button data-bs-toggle="modal" data-bs-target="#subjectModal" id="createModuleBtn">
                            <i class="fa fa-pencil"></i> Create Modules
                        </button>
                    </div>
                </div>

                <div class="grade-filter" style="margin: 2rem 0rem;">
                    <form method="GET" action="/learning_management/public/">
                        <input type="hidden" name="url" value="activities">
                        <select name="grade_id" class="form-select" style="max-width:220px; display:inline-block;"
                            onchange="this.form.submit()">
                            <option value="">-- All Grade Levels --</option>
                            <?php foreach ($gradeLevels as $gl): ?>
                                <option value="<?= $gl['id'] ?>" <?= (isset($selectedGrade) && $selectedGrade == $gl['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($gl['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <div class="card-parent-box">
                    <?php if (empty($subjects)): ?>
                        <p style="color:#9ca3af;font-size:13px;">No subjects found.</p>
                    <?php else: ?>
                        <?php foreach ($subjects as $subject): ?>
                            <div class="card">
                                <div class="card-header">
                                    <?php if (!empty($subject['subject_image'])): ?>
                                        <img src="/learning_management/<?= htmlspecialchars($subject['subject_image']) ?>"
                                            alt="subject image"
                                            style="width:100%;height:100%;object-fit:cover;border-radius:8px 8px 0 0;">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="card-text">
                                        <h4><?= htmlspecialchars($subject['subject_name']) ?></h4>
                                        <?php if (!empty($subject['grade_name'])): ?>
                                            <small
                                                style="color:#16a34a; font-weight:600; font-size:14.5px; display:block; margin-bottom:4px;">
                                                <i class="fa fa-layer-group"></i>
                                                <?= htmlspecialchars($subject['grade_name']) ?>
                                            </small>
                                        <?php endif; ?>
                                        <span
                                            style="display:block;"><?= htmlspecialchars(!empty($subject['subject_description']) ? $subject['subject_description'] : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus, inventore.') ?></span>
                                    </div>
                                    <div class="card-link" style="display:flex; gap:8px; align-items:center;">
                                        <!-- Edit button -->
                                        <button class="btn-edit-subject"
                                            style="background:none;border:none;cursor:pointer;color:#16a34a;font-size:20px;padding:4px 8px;"
                                            onclick="openEditModal(
                                                <?= $subject['id'] ?>,
                                                '<?= addslashes($subject['subject_name']) ?>',
                                                '<?= addslashes($subject['subject_description'] ?? '') ?>',
                                                '<?= addslashes($subject['subject_code'] ?? '') ?>',
                                                <?= $subject['grade_level_id'] ?? 0 ?>
                                            )" title="Edit subject">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <a
                                            href="/learning_management/public/?url=create_activities&subject_id=<?= (int) $subject['id'] ?>">
                                            <span>View module</span>
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
         CREATE / EDIT SUBJECT MODAL
    ══════════════════════════════════════════════ -->
    <div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:12px;">
                <form action="/learning_management/public/?url=save_subject" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="subject_id" id="modal_subject_id" value="">

                    <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                        <h5 class="modal-title" id="subjectModalLabel" style="font-weight: 600; font-size: 16px;">Create Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" style="display:flex;flex-direction:column;gap:1rem;">

                        <!-- Subject Name -->
                        <div>
                            <label style="font-size:14.5px;font-weight:600;margin-bottom:4px;display:block;">Subject Name
                                <span>*</span></label>
                            <input type="text" name="subject_name" id="modal_subject_name" style="font-size: 14.5px;" class="form-control"
                                placeholder="e.g. Computer System Servicing" required>
                        </div>

                        <!-- Subject Code -->
                        <div>
                            <label style="font-size:14.5px;font-weight:600;margin-bottom:4px;display:block;">Subject Code
                                <span>*</span></label>
                            <input type="text" name="subject_code" id="modal_subject_code" style="font-size: 14.5px;" class="form-control"
                                placeholder="e.g. css_11" required>
                        </div>

                        <!-- Grade Level -->
                        <div>
                            <label style="font-size:14.5px;font-weight:600;margin-bottom:4px;display:block;">Grade Level
                                <span>*</span></label>
                            <select name="grade_level_id" id="modal_grade_level_id" class="form-select" style="font-size: 14.5px;" required>
                                <option value="">-- Select Grade Level --</option>
                                <?php foreach ($gradeLevels as $gl): ?>
                                    <option value="<?= $gl['id'] ?>">
                                        <?= htmlspecialchars($gl['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Description -->
                        <div>
                            <label
                                style="font-size:14.5px;font-weight:600;margin-bottom:4px;display:block;">Description</label>
                            <textarea name="subject_description" id="modal_subject_description" class="form-control"
                                rows="3" placeholder="Short description about this subject..." style="font-size: 14.5px;"></textarea>
                        </div>

                        <!-- Subject Image -->
                        <div>
                            <label style="font-size:14.5px;font-weight:600;margin-bottom:4px;display:block;">Subject
                                Image</label>
                            <input type="file" name="subject_image" id="modal_subject_image" class="form-control"
                                accept="image/*" onchange="previewImage(this)" style="font-size: 14.5px;" required>
                            <div id="imagePreviewWrapper" style="margin-top:8px; display:none;">
                                <img id="imagePreview" src="#" alt="Preview"
                                    style="max-height:120px;border-radius:8px; width: 100%; object-fit:cover;">
                            </div>
                            <div id="currentImageWrapper" style="margin-top:8px;display:none;">
                                <p style="font-size:12px;color:#6b7280;margin-bottom:4px;">Current image:</p>
                                <img id="currentImage" src="#" alt="Current"
                                    style="max-height:120px; width: 100%;  border-radius:8px;object-fit:cover;">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="font-size:13px;">Cancel</button>
                        <button type="submit" class="btn"
                            style="background: var(--green);color:#fff;font-size:14.5px;font-weight:600;"
                            id="modalSubmitBtn">Save Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        // Reset modal to CREATE mode
        document.getElementById('createModuleBtn').addEventListener('click', function () {
            document.getElementById('subjectModalLabel').textContent = 'Create Subject';
            document.getElementById('modalSubmitBtn').textContent = 'Save Subject';
            document.getElementById('modal_subject_id').value = '';
            document.getElementById('modal_subject_name').value = '';
            document.getElementById('modal_subject_code').value = '';
            document.getElementById('modal_subject_description').value = '';
            document.getElementById('modal_grade_level_id').value = '';
            document.getElementById('imagePreviewWrapper').style.display = 'none';
            document.getElementById('currentImageWrapper').style.display = 'none';
        });

        // Open modal in EDIT mode
        function openEditModal(id, name, description, code, gradeLevelId) {
            document.getElementById('subjectModalLabel').textContent = 'Edit Subject';
            document.getElementById('modalSubmitBtn').textContent = 'Update Subject';
            document.getElementById('modal_subject_id').value = id;
            document.getElementById('modal_subject_name').value = name;
            document.getElementById('modal_subject_code').value = code;
            document.getElementById('modal_subject_description').value = description;

            const gradeSelect = document.getElementById('modal_grade_level_id');
            gradeSelect.value = gradeLevelId;

            document.getElementById('imagePreviewWrapper').style.display = 'none';

            const modal = new bootstrap.Modal(document.getElementById('subjectModal'));
            modal.show();
        }

        // Preview uploaded image
        function previewImage(input) {
            const wrapper = document.getElementById('imagePreviewWrapper');
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    wrapper.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>

</html>