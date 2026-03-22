<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/all_subjects.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        /* (all existing styles kept exactly as-is) */
        .container-fluid .welcome-user .welcome-header {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .container-fluid .welcome-user {
            padding: 45px 35px 35px 45px;
            background-color: var(--green);
            width: 100%;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.7rem;
            position: relative;
            overflow: hidden;
            animation: slideIn 0.4s ease both;
        }

        .container-fluid .welcome-user .welcome-icon .fa {
            font-size: 90px;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group a {
            font-size: 14.5px;
            color: white;
            z-index: 1;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group a:hover {
            box-shadow: 0 8px 32px rgba(108, 63, 232, 0.13), 0 2px 8px rgba(0, 0, 0, 0.07);
            transform: translateY(-2px);
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group a:nth-child(1) {
            border: 2px solid #ffffff;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 7px;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group a:nth-child(2) {
            border: 2px solid #ffffff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 7px;
            text-decoration: none;
        }

        .container-fluid .welcome-user .welcome-text h2 {
            margin: 0;
            font-size: 23px;
            font-weight: 500;
            font-family: "Titan", sans-serif;
            color: #ffffff;
            text-transform: none;
        }

        .container-fluid .welcome-user .welcome-text {
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }

        .container-fluid .welcome-user .welcome-text span {
            font-size: 15.5px;
            color: #ffffff;
        }

        .rightbar .module-parent {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            gap: 2rem;
        }

        .rightbar .module-parent .learning-catalog h4 {
            font-size: 19px;
            margin: 0 0 10px;
            font-family: "Titan", sans-serif;
        }

        .rightbar .module-parent .learning-catalog p {
            margin: 15px 0 0;
            font-size: 14.5px;
            font-weight: 600;
        }

        .rightbar .module-parent .learning-catalog .learning-module {
            margin-top: 0.7rem;
            width: 240px;
        }

        .rightbar .module-parent .learning-catalog .learning-module ul li {
            font-size: 14.5px;
        }

        .rightbar .module-parent .learning-module-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            animation: slideIn 0.4s ease both;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .rightbar .module-parent .learning-module-section .module-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            width: 675px;
            border-radius: 10px;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-header {
            height: 130px;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(1) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/philosophy_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(2) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/ucsp_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(3) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/computer_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(4) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/pe_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(5) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/3i_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(6) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/entrep_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(7) .module-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/work_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-body {
            padding: 2.1rem 1.7rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-body h4 {
            font-size: 18.5px;
            line-height: 28px;
            width: 400px;
            color: var(--green-dark);
            font-family: "Titan", sans-serif;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-body p {
            margin: 0;
            width: 340px;
            font-size: 14.5px;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-body .module-link a {
            padding: 10px 30px;
            text-decoration: none;
            color: #ffffff;
            background-color: var(--green);
            border-radius: 28px;
            font-size: 14.5px;
            font-weight: 600;
            transition: background .2s;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-body .module-link a.btn-continue {
            background-color: var(--green-dark, #065f46);
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0 m-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <?php if (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 12'): ?>

                <div class="welcome-user">
                    <div class="welcome-text">
                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span>Gets Ready to Learn!</span>
                        </div>
                    </div>
                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="/learning_management/public/?url=classes">Browse Courses <i class="fa fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="module-parent">
                    <div class="learning-catalog">
                        <h4>Learning Catalog</h4>
                        <p>Grade 12</p>
                        <div class="learning-module">
                            <?php foreach ($grade12Subjects as $subject): ?>
                                <ul>
                                    <li><?= htmlspecialchars($subject['subject_name']) ?></li>
                                </ul>
                            <?php endforeach; ?>
                        </div>
                        <p>Grade 11</p>
                        <div class="learning-module">
                            <?php foreach ($grade11Subjects as $subject): ?>
                                <ul>
                                    <li><?= htmlspecialchars($subject['subject_name']) ?></li>
                                </ul>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="learning-module-section">
                        <?php foreach ($subjects as $subject):
                            $slug = $subject['slug'];
                            $isStarted = in_array($slug, $startedSlugs ?? []);
                            $btnText = $isStarted ? 'Continue Learning' : 'Start Now';
                            $btnClass = $isStarted ? 'btn-continue' : 'btn-start';
                            ?>
                            <div class="module-card">
                                <div class="module-header"></div>
                                <div class="module-body">
                                    <div class="module-text">
                                        <h4><?= htmlspecialchars($subject['subject_name']) ?></h4>
                                        <p><?= !empty($subject['description']) ? htmlspecialchars($subject['description']) : 'Lorem ipsum dolor sit amet consectetur' ?>
                                        </p>
                                    </div>
                                    <div class="module-link">
                                        <a href="/learning_management/public/?url=modules&subject=<?= urlencode($slug) ?>"
                                            class="<?= $btnClass ?>" data-slug="<?= htmlspecialchars($slug) ?>"
                                            onclick="handleStart(event, this)">
                                            <?= $btnText ?> <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php elseif (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 11'): ?>
            <?php else: ?>
                <div class="welcoming">
                    <h2>No grade level or section assigned. Please contact your administrator.</h2>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleStart(e, link) {
            var slug = link.dataset.slug;
            var isStarted = link.classList.contains('btn-continue');
            var href = link.getAttribute('href');

            if (!isStarted) {
                e.preventDefault();

                var fd = new FormData();
                fd.append('slug', slug);
                fetch('/learning_management/public/?url=mark_subject_started', {
                    method: 'POST',
                    body: fd
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        // Show success modal then navigate
                        var modal = new bootstrap.Modal(document.getElementById('startSuccessModal'));
                        modal.show();
                        setTimeout(function () {
                            modal.hide();
                            window.location.href = href;
                        }, 1800);
                    })
                    .catch(function (err) {
                        console.error('AJAX error:', err);
                        window.location.href = href;
                    });
            }
        }
    </script>

    <!-- ── SUCCESS MODAL ── -->
    <div class="modal fade" id="startSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <div class="modal-body text-center" style="padding:2rem 1.5rem;">
                    <div
                        style="width:60px;height:60px;background:#ecfdf5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fa fa-check" style="color:var(--green);font-size:26px;"></i>
                    </div>
                    <h5 style="font-family:'Titan',sans-serif;color:var(--green-dark);margin:0 0 .4rem;font-size:17px;">
                        Start learning successfully!
                    </h5>
                    <p style="font-size:13.5px;color:#555;margin:0;">
                        Starting your learning journey now...
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>