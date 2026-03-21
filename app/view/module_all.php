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
        .container-fluid .welcome-user .welcome-header {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .container-fluid .welcome-user {
            padding: 45px 35px 35px 45px;
            /* background-color: #F5F5F5; */
            /* background-color: #0d1117; */
            /* background-color: #5BCA3F; */
            background-color: var(--green);
            width: 100%;
            /* border: 1px solid rgba(0, 0, 0, 0.2); */
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

        /* .container-fluid .welcome-user .welcome-text{
    display: flex;
    justify-content: space-between;
    align-items: center;
} */

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

        /* .container-fluid .welcome-user .welcome-body .buttons-group a .fa{
    color: #364153;
} */

        .container-fluid .welcome-user .welcome-body .buttons-group a:hover {
            box-shadow: 0 8px 32px rgba(108, 63, 232, 0.13), 0 2px 8px rgba(0, 0, 0, 0.07);
            transform: translateY(-2px);
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group a:nth-child(1) {
            /* background-color: #3B5BDB; */
            /* background-color: #9333ea; */
            /* background: linear-gradient(to right, #4F5CFE, #7B2DFB); */
            border: 2px solid #ffffff;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 7px;
        }

        .container-fluid .welcome-user .welcome-body .buttons-group a:nth-child(2) {
            /* background: rgba(255, 255, 255, 0.08); */
            /* background-color: #ffffff; */
            border: 2px solid #ffffff;
            /* color: rgba(255, 255, 255, 0.8); */
            /* color: #7B2DFB; */
            color: #ffffff;
            /* border: 1px solid rgba(255, 255, 255, 0.12); */
            padding: 10px 20px;
            border-radius: 7px;
            text-decoration: none;
        }

        .container-fluid .welcome-user .welcome-text h2 {
            margin: 0;
            /* font-size: 25px; */
            font-size: 23px;
            font-weight: 500;
            text-transform: none;
            /* text-transform: uppercase; */
            font-family: "Titan", sans-serif;
            /* color: #ffffff; */
            /* font-weight: 600; */
            color: #ffffff;
        }

        .container-fluid .welcome-user .welcome-text h4 {
            font-size: 29px;
            margin: 0;
        }

        .container-fluid .welcome-user .welcome-text {
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
            /* margin-top: 1.5rem; */
        }

        .container-fluid .welcome-user .welcome-text span {
            /* color: #ffffff; */
            font-size: 15.5px;
            /* color: #6A7282; */
            color: #ffffff;
        }

        .rightbar .module-parent {
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            gap: 2rem;
            /* justify-content: space-between; */

        }

        .rightbar .module-parent .learning-catalog h4 {
            font-size: 19px;
            /* font-weight: 600; */
            margin: 0 0 10px;
            font-family: "Titan", sans-serif;
            /* color: var(--green-dark); */
        }

        .rightbar .module-parent .learning-catalog p {
            margin: 15px 0 0;
            font-size: 14.5px;
            font-weight: 600;
        }

        .rightbar .module-parent .learning-catalog .learning-module {
            margin-top: 0.7rem;
        }

        .rightbar .module-parent .learning-catalog .learning-module {
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
            /* width: 100%; */
            width: 675px;
            border-radius: 10px;
        }

        .rightbar .module-parent .learning-module-section .module-card .module-header {
            height: 130px;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(1) .module-header {
            /* width: 100%; */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/philosophy_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(2) .module-header {
            /* width: 100%; */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/ucsp_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(3) .module-header {
            /* width: 100%; */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/computer_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(4) .module-header {
            /* width: 100%; */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/pe_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(5) .module-header {
            /* width: 100%; */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/3i_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(6) .module-header {
            /* width: 100%; */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-image: url('../images/entrep_picture.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .rightbar .module-parent .learning-module-section .module-card:nth-child(7) .module-header {
            /* width: 100%; */
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
            /* border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px; */
        }

        .rightbar .module-parent .learning-module-section .module-card .module-body h4 {
            font-size: 18.5px;
            /* font-weight: 600; */
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

                <!-- <h4><?= htmlspecialchars($_SESSION["name"]) ?></h4> -->

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome,
                            <?= htmlspecialchars($_SESSION["name"]) ?>! 👋
                        </h2>
                        <div class="d-flex gap-2">
                            <span>Gets Ready to Learn!</span>
                            <!-- <span>
                                    <?= htmlspecialchars($_SESSION["grade_level"]) ?>
                                </span>
                                <span>—</span>
                                <span>
                                    <?= htmlspecialchars($_SESSION["section"]) ?>
                                </span> -->
                            <!-- <span>Here's what's happening with your learning today</span> -->
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="#">Browse Courses <i class="fa fa-arrow-right"></i></a>

                            <!-- <a href="#">View Progress</a> -->
                        </div>
                    </div>
                </div>

                <div class="module-parent">
                    <div class="learning-catalog">
                        <h4>Learning Catalog</h4>

                        <!-- Grade 12 Subjects -->
                        <p>Grade 12</p>
                        <div class="learning-module">
                            <?php foreach ($grade12Subjects as $subject): ?>
                                <ul>
                                    <li><?= htmlspecialchars($subject['subject_name']) ?></li>
                                </ul>
                            <?php endforeach; ?>
                        </div>

                        <!-- Grade 11 Subjects -->
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
                        <?php foreach ($subjects as $subject): ?>
                            <div class="module-card">
                                <div class="module-header">

                                </div>
                                <div class="module-body">
                                    <div class="module-text">
                                        <h4>
                                            <?= htmlspecialchars($subject['subject_name']) ?>
                                        </h4>
                                        <p>
                                            <?= !empty($subject['description'])
                                                ? htmlspecialchars($subject['description'])
                                                : 'Lorem ipsum dolor sit amet consectetur' ?>
                                        </p>
                                    </div>
                                    <div class="module-link">
                                        <a href="/learning_management/public/?url=modules&subject=<?= urlencode($subject['slug']) ?>">Start Now <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>


            <?php elseif (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 11'): ?>

            <?php else: ?>

                <div class="welcoming">
                    <h2>No grade level or section assigned to your account. Please contact your administrator.</h2>
                </div>

            <?php endif; ?>
        </div>
    </div>

</body>

</html>