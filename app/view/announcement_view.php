<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement View</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        .anv-page {
            width: 100%;
        }

        .anv-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--green);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .anv-back-link:hover {
            text-decoration: underline;
        }

        .anv-main-card {
            background: #fff;
            border: 1px solid #E2E8E5;
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            margin-bottom: 1.4rem;
        }

        .anv-card-top {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            margin-bottom: 1.4rem;
        }

        .anv-card-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background: #f0ebff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            font-size: 22px;
        }

        .anv-card-label {
            font-size: 12px;
            color: #aaa;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            display: block;
            margin-bottom: 4px;
        }

        .anv-card-top h2 {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 4px;
        }

        .anv-date {
            font-size: 12px;
            color: #aaa;
            display: block;
        }

        .anv-poster-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 1rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 1.2rem;
        }

        .anv-avatar {
            width: 44px;
            height: 44px;
            min-width: 44px;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
        }

        .anv-poster-name {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            display: block;
        }

        .anv-poster-meta {
            font-size: 12px;
            color: #aaa;
            display: block;
        }

        .anv-body-text {
            font-size: 14.5px;
            color: #333;
            line-height: 1.8;
            margin: 0;
        }

        .anv-message-card {
            background: #fff;
            border: 1px solid #E2E8E5;
            border-radius: 12px;
            padding: 1.5rem 1.8rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            margin-bottom: 1.4rem;
        }

        .anv-message-card h5 {
            font-size: 14px;
            font-weight: 800;
            color: #333;
            margin-bottom: 1rem;
        }

        .anv-message-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 28px;
            padding: 8px 8px 8px 16px;
            background: #fafafa;
        }

        .anv-message-box input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            color: #333;
            font-family: inherit;
        }

        .anv-message-box button {
            background: var(--green);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            flex-shrink: 0;
            font-size: 14px;
        }

        .anv-message-actions {
            display: flex;
            gap: 1.2rem;
            margin-top: .8rem;
            padding-left: 6px;
        }

        .anv-message-actions button {
            background: none;
            border: none;
            color: #aaa;
            font-size: 20px;
            cursor: pointer;
            transition: color .18s;
            padding: 0;
        }

        .anv-message-actions button:hover {
            color: var(--green);
        }

        .anv-not-found {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }

        .anv-not-found i {
            font-size: 48px;
            display: block;
            margin-bottom: 14px;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="anv-page">

                <?php
                // $announcement — from StudentsController::announcement_view()
                // $subjectSlug  — from StudentsController::announcement_view()
                
                // Get initials from teacher name for avatar
                $initials = '';
                if ($announcement && !empty($announcement['teacher_name'])) {
                    $parts = explode(' ', $announcement['teacher_name']);
                    $initials = strtoupper(
                        substr($parts[0], 0, 1) .
                        (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
                    );
                }
                ?>

                <?php if (!$announcement): ?>
                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="anv-back-link">
                        <i class="fa fa-arrow-left"></i> Back to Subject
                    </a>
                    <div class="anv-not-found">
                        <i class="fa fa-folder-open"></i>
                        <p>Announcement not found.</p>
                    </div>

                <?php else: ?>

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($announcement['slug']) ?>"
                        class="anv-back-link">
                        <i class="fa fa-arrow-left"></i> Back to <?= htmlspecialchars($announcement['subject_name']) ?>
                    </a>

                    <div class="anv-main-card">
                        <div class="anv-card-top">
                            <div class="anv-card-icon">
                                <i class="fa fa-bullhorn"></i>
                            </div>
                            <div>
                                <span class="anv-card-label">Announcement</span>
                                <h2><?= htmlspecialchars($announcement['title']) ?></h2>
                                <span class="anv-date">
                                    Date Received: <?= date('M j', strtotime($announcement['posted_at'])) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Teacher info -->
                        <div class="anv-poster-row">
                            <div class="anv-avatar"><?= $initials ?></div>
                            <div>
                                <span class="anv-poster-name"><?= htmlspecialchars($announcement['teacher_name']) ?></span>
                                <span class="anv-poster-meta">
                                    Date Received: <?= date('M j', strtotime($announcement['posted_at'])) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Full announcement body -->
                        <p class="anv-body-text">
                            <?= nl2br(htmlspecialchars($announcement['body'])) ?>
                        </p>
                    </div>

                    <!-- Reply box -->
                    <div class="anv-message-card">
                        <h5>Reply</h5>
                        <div class="anv-message-box">
                            <input type="text" id="annMsgInput" placeholder="Message...">
                            <button title="Send"><i class="fa fa-paper-plane"></i></button>
                        </div>
                        <div class="anv-message-actions">
                            <button title="Attach file"><i class="fa fa-paperclip"></i></button>
                            <button title="Image"><i class="fa fa-image"></i></button>
                            <button title="Video"><i class="fa fa-film"></i></button>
                            <button title="Emoji"><i class="fa fa-smile"></i></button>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>