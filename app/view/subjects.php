<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject</title>
    <link rel="stylesheet" href="../css_folder/subjects.css">
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

            <?php
            require_once "../app/models/Students.php";
            $studentModel = new Students();

            $subject = $_GET['subject'] ?? null;

            if ($subject):
                // Pull subject info dynamically from DB — no hardcoded map needed
                $subjectInfo = $studentModel->getSubjectBySlug($subject);

                if ($subjectInfo):
                    $feedItems = $studentModel->getSubjectFeed($subject);
                    ?>

                    <style>
                        .container-fluid .rightbar .module-title {
                            width: 100%;
                            color: white;
                            border-radius: 10px;
                            border: 1px solid #E2E8E5;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
                        }

                        .container-fluid .rightbar .module-title .module-picture {
                            background-color: var(--green-dark);
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                            width: 100%;
                            height: 180px;
                            border-top-left-radius: 10px;
                            border-top-right-radius: 10px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }

                        .container-fluid .rightbar .module-title .module-picture h2 {
                            color: white;
                            font-size: 28px;
                            font-family: "Titan", sans-serif;
                            text-transform: uppercase;
                            letter-spacing: 2px;
                            text-align: center;
                            padding: 1rem;
                            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                        }

                        .container-fluid .rightbar .module-title .module-body {
                            background-color: #ffffff;
                            border-bottom-left-radius: 10px;
                            border-bottom-right-radius: 10px;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-body-child {
                            position: relative;
                            z-index: 2;
                            display: flex;
                            gap: 48px;
                            align-items: center;
                            padding: 1.8rem 2rem;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links {
                            width: 55%;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links span {
                            color: var(--green);
                            font-weight: 600;
                            font-size: 13.5px;
                            text-transform: uppercase;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons {
                            display: flex;
                            align-items: center;
                            gap: 1.5rem;
                            margin-top: 1.5rem;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(1) {
                            color: white;
                            background-color: var(--green);
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(2) {
                            border: 2px solid var(--green);
                            color: var(--green);
                            padding: 10px 40px;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a {
                            text-decoration: none;
                            padding: 10px 27px;
                            border-radius: 28px;
                            text-transform: uppercase;
                            font-weight: 600;
                            font-size: 13px;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-links h1 {
                            font-size: 22px;
                            text-transform: uppercase;
                            font-family: "Titan", sans-serif;
                            color: var(--green-dark);
                            margin-top: 0.7rem;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-text {
                            width: 45%;
                        }

                        .container-fluid .rightbar .module-title .module-body .module-text p {
                            line-height: 26px;
                            margin: 0;
                            font-size: 14px;
                            color: #1A1A1A;
                        }

                        .module-parent-progress a {
                            text-decoration: none;
                            color: inherit;
                            display: block;
                        }
                    </style>

                    <!-- ── HERO BANNER — fully dynamic from DB ── -->
                    <div class="module-title">
                        <div class="module-picture">
                            <h2><?= htmlspecialchars($subjectInfo['subject_name']) ?></h2>
                        </div>
                        <div class="module-body">
                            <div class="module-body-child">
                                <div class="module-links">
                                    <h1><?= htmlspecialchars($subjectInfo['subject_name']) ?></h1>
                                    <div class="module-buttons">
                                        <a href="/learning_management/public/?url=classes">Browse Courses</a>
                                        <a
                                            href="/learning_management/public/?url=subject_lessons&subject=<?= urlencode($subject) ?>">
                                            View Lessons
                                        </a>
                                    </div>
                                </div>
                                <div class="module-text">
                                    <p><?= htmlspecialchars($subjectInfo['subject_description'] ?? 'No description available.') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── FEED — dynamic from DB ── -->
                    <div class="parent">
                        <div class="module-parent-progress">

                            <?php
                            $urlMap = [
                                'module' => 'module_view',
                                'assignment' => 'assignment_view',
                                'announcement' => 'announcement_view',
                            ];
                            $labelMap = [
                                'module' => 'New Material',
                                'assignment' => 'New Assignment',
                                'announcement' => 'Announcement',
                            ];

                            if (!empty($feedItems)):
                                foreach ($feedItems as $item):
                                    $pageUrl = "/learning_management/public/?url={$urlMap[$item['type']]}&subject=" . urlencode($subject) . "&id={$item['id']}";
                                    $label = $labelMap[$item['type']] ?? $item['type'];
                                    $date = date('M j', strtotime($item['date']));
                                    $subtext = mb_strimwidth(strip_tags($item['subtext']), 0, 120, '...');
                                    ?>
                                    <a href="<?= $pageUrl ?>">
                                        <div class="module-progress">
                                            <div class="module-parent">
                                                <div class="module-icon">
                                                    <i class="fa fa-layer-group"></i>
                                                </div>
                                                <div class="module-content">
                                                    <span><?= htmlspecialchars($label) ?></span>
                                                    <h3><?= htmlspecialchars($item['heading']) ?></h3>
                                                    <p><?= htmlspecialchars($subtext) ?></p>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="module-date">
                                                <p>Date Received: <?= $date ?></p>
                                            </div>
                                        </div>
                                    </a>
                                    <?php
                                endforeach;
                            else:
                                ?>
                                <p style="color:#aaa; padding:2rem; font-size:14px; text-align:center;">
                                    No materials posted yet.
                                </p>
                            <?php endif; ?>

                        </div>
                    </div>

                    <?php
                else:
                    // Subject slug not found in DB
                    ?>
                    <div style="padding:2rem;">
                        <h3>Subject not found.</h3>
                        <p>The subject "<strong><?= htmlspecialchars($subject) ?></strong>" does not exist.</p>
                        <a href="/learning_management/public/?url=classes">← Back to Courses</a>
                    </div>
                    <?php
                endif;
            else:
                ?>
                <div style="padding:2rem;">
                    <h3>No subject selected.</h3>
                    <a href="/learning_management/public/?url=classes">← Browse Courses</a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

</body>

</html>