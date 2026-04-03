<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classes</title>
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

            <?php
            /** @var array $subjects */
            /** @var int[] $enrolledSubjectIds */
            /** @var array $enrolledSubjects */

            // Filter to only enrolled subjects
            $enrolledSubjects = array_filter($subjects, function ($subject) use ($enrolledSubjectIds) {
                return in_array((int) $subject['id'], $enrolledSubjectIds, true); // strict int comparison
            });
            ?>

            <div style="display: none;">
                <?php
                echo "<pre>";
                echo "Subjects: ";
                print_r($subjects);
                echo "Enrolled IDs: ";
                print_r($enrolledSubjectIds);
                echo "Filtered: ";
                print_r($enrolledSubjects);
                echo "</pre>";
                ?>
            </div>


            <?php if (empty($enrolledSubjects)): ?>
                <!-- Empty state: no enrolled classes yet -->
                <div class="classes-empty-state">
                    <div class="classes-empty-icon">
                        <i class="fa fa-book-open"></i>
                    </div>
                    <h4 class="classes-empty-title">You're not enrolled in any classes yet</h4>
                    <p class="classes-empty-hint">
                        Click the <strong><i class="fa fa-plus"></i></strong> button in the top navigation bar and enter
                        your class code to join a class.
                    </p>
                    <button class="classes-join-cta" data-bs-toggle="modal" data-bs-target="#joinClassModal">
                        <i class="fa fa-plus me-2"></i>Join a Class
                    </button>
                </div>

            <?php else: ?>
                <?php foreach ($enrolledSubjects as $subject): ?>
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
                                    <a
                                        href="/learning_management/public/?url=subjects&subject=<?= urlencode($subject['subject_code']) ?>">
                                        <span>Go to Subject</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <style>
        /* ── Empty state ── */
        .classes-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            padding: 40px 20px;
        }

        .classes-empty-icon {
            width: 96px;
            height: 96px;
            background: #e8f5f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .classes-empty-icon .fa {
            font-size: 2.4rem;
            color: #1e7e5e;
        }

        .classes-empty-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 10px;
        }

        .classes-empty-hint {
            font-size: .9rem;
            color: #666;
            max-width: 380px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .classes-join-cta {
            background: #1e7e5e;
            color: #fff;
            border: none;
            padding: 11px 28px;
            border-radius: 8px;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .classes-join-cta:hover {
            background: #166347;
        }
    </style>

</body>

</html>