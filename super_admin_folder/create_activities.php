<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/create_activities.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <?php include("../super_admin_folder/nav.php") ?>

            <main>
                <?php if ($subject): ?>
                    <div class="header">
                        <div class="card">
                            <?php if (!empty($subject['subject_image'])): ?>
                                <div class="card-header" style="padding:0;overflow:hidden;">
                                    <img src="/learning_management/<?= htmlspecialchars($subject['subject_image']) ?>"
                                        alt="subject image"
                                        style="width:100%;height:100%;object-fit:cover;border-radius:8px 8px 0 0;">
                                </div>
                            <?php else: ?>
                                <div class="card-header"></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="card-text">
                                    <h4><?= htmlspecialchars($subject['subject_name']) ?></h4>
                                    <?php if (!empty($subject['grade_name'])): ?>
                                        <small
                                            style="color:#16a34a; font-weight:600; font-size:14.5px; display:block;">
                                            <i class="fa fa-layer-group"></i>
                                            <?= htmlspecialchars($subject['grade_name']) ?>
                                        </small>
                                    <?php endif; ?>
                                    <span
                                        style="display:block;"><?= htmlspecialchars(!empty($subject['subject_description']) ? $subject['subject_description'] : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus, inventore.') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="padding:2rem;">
                        <p style="color:#9ca3af;font-size:13px;">Subject not found.</p>
                        <a href="/learning_management/public/?url=activities">← Back to subjects</a>
                    </div>
                <?php endif; ?>

                <div class="body">
                    <form action="/learning_management/public/?url=save_interactive_module" method="POST"
                        enctype="multipart/form-data">

                        <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subject['id'] ?? '') ?>">

                        <div class="card-parent-box">
                            <div class="card-header" style="margin-bottom:0;">
                                <h3>Create Activities</h3>
                                <div class="buttons">
                                    <button type="button" id="addModuleBtn">
                                        <i class="fa fa-plus"></i> Add Module
                                    </button>
                                </div>
                            </div>

                            <div id="contentContainer" style="padding:1.5rem 0 0 0;">
                                <div class="text-content" id="contentEmpty" style="display:flex;">
                                    <i class="fa fa-inbox"></i>
                                    <p>No modules yet — click "Add Module" to start.</p>
                                </div>
                            </div>

                            <div class="card-submit">
                                <a href="/learning_management/public/?url=activities">
                                    <button type="button">Cancel</button>
                                </a>
                                <button type="submit">Save Activities</button>
                            </div>
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script src="../super_admin_folder/activities.js"></script>

</body>

</html>