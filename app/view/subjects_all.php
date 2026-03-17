<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="stylesheet" href="../css_folder/all_subjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">

            <?php foreach ($subjects as $subject): ?>
                <div class="card-box-parent">
                    <div class="card-box">

                        <div class="card-box-picture"></div>

                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p><?= htmlspecialchars($subject['subject_name']) ?></p>
                                <span>
                                    <?= !empty($subject['description'])
                                        ? htmlspecialchars($subject['description'])
                                        : 'Lorem ipsum dolor sit amet consectetur, adipisicing elit.' ?>
                                </span>
                            </div>

                            <div class="card-body-enrolled">
                                <?php if (in_array($subject['id'], $enrolledSubjectIds)): ?>
                                    <a
                                        href="/learning_management/public/?url=subjects&subject=<?= urlencode($subject['slug']) ?>">
                                        <span>Go to Subject</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>

                                <?php else: ?>

                
                                    <a
                                        href="/learning_management/public/?url=subjects_all&enroll=1&subject_id=<?= $subject['id'] ?>&subject_slug=<?= urlencode($subject['slug']) ?>">
                                        <span>Enroll Now</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>

                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>