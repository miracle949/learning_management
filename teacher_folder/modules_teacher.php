
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modules</title>
    <link rel="stylesheet" href="../css_folder/modules_teacher.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("sidebar.php") ?>

        <div class="rightbar">
            <?php include("nav.php") ?>
            <main>
                <div class="header">
                    <div>
                        <h4>Interactive Modules</h4>
                        <p>Upload, manage and distribute learning content</p>
                    </div>
                </div>

                <!-- Grade Level Filter -->
                <div class="grade-filter" style="margin: 2rem 0;">
                    <form method="GET" action="/learning_management/public/">
                        <input type="hidden" name="url" value="modules_teacher">
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

                <!-- Subject Cards -->
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
                                                style="color:#16a34a;font-weight:600;font-size:14.5px;display:block;margin-bottom:4px;">
                                                <i class="fa fa-layer-group"></i>
                                                <?= htmlspecialchars($subject['grade_name']) ?>
                                            </small>
                                        <?php endif; ?>
                                        <span style="display:block;">
                                            <?= htmlspecialchars(!empty($subject['subject_description'])
                                                ? $subject['subject_description']
                                                : 'Lorem ipsum dolor sit amet consectetur adipisicing elit.') ?>
                                        </span>
                                    </div>
                                    <div class="card-link">
                                        <a class="<?= ($current_url ?? '') === 'modules_teacher' ? 'active' : '' ?>"
                                            href="/learning_management/public/?url=create_module&subject_id=<?= (int) $subject['id'] ?>">
                                            <span>Create module</span>
                                            <i class="fa fa-arrow-right"></i>
                                        </a>
                                        <a class="<?= ($current_url ?? '') === 'modules_teacher' ? 'active' : '' ?>" href="/learning_management/public/?url=view_modules_teacher&subject_id=<?= (int) $subject['id'] ?>">
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
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>