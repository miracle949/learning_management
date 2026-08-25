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
            /* margin-bottom: 1.2rem; */
            margin: 0 0 21px;
        }

        .mv-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-dim);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .anv-back-link:hover {
            text-decoration: underline;
        }

        .reminder {
            font-size: 15px;
            font-family: "Orbitron", sans-serif;
            color: #1a1a1a;
            margin: 20px 0 8px;
        }

        /* .anv-main-card {
            background: #fff;
            border: 1px solid #E2E8E5;
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
            margin-bottom: 1.4rem;
        }
            

        .anv-card-top {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            margin-bottom: 1.4rem;
            padding: 1.8rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        } */

        .anv-main-sub-card {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
            margin: 15px 0 0;
            position: relative;
        }

        .anv-main-sub-card img {
            width: 180px;
            height: 200px;
            position: absolute;
            top: -5rem;
            right: 20px;
        }

        .speech-bubble-announce {
            position: absolute;
            left: -510px;
            /* left: -315px; */
            /* right: -185px; */
            /* right: -510px; */
            /* top: 2px; */
            top: -100px;
            /* top: -50px; */
            /* width: 148px; */
            /* width: 175px; */
            width: 280px;
            /* background: #fff; */
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
            background: #fff;
            /* box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18); */
            /* display: none; */
            /* animation: bubblePop 4s ease-in-out infinite; */
        }

        .speech-bubble-announce strong {
            display: block;
            color: var(--neon-cyan);
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 14.5px;
        }

        .speech-bubble-announce p {
            margin: 0;
            font-size: 13.5px;
            color: var(--text-dim);
            line-height: 22px;
        }

        .speech-bubble-announce p .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 14px;
            background: var(--neon-cyan);
            margin-left: 2px;
            vertical-align: middle;
            animation: cursorBlink 0.8s step-end infinite;
        }

        @keyframes cursorBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .speech-bubble-announce::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 50px;
            /* top: 60px; */
            width: 12px;
            height: 12px;
            background: #ffffff;
            /* border: 1px solid var(--border); */
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04); */
            transform: rotate(45deg);
            /* z-index: -1; */
        }

        @keyframes bubblePop {

            0%,
            8% {
                opacity: 0;
                transform: translateY(6px) scale(.92);
            }

            16%,
            84% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            92%,
            100% {
                opacity: 0;
                transform: translateY(6px) scale(.92);
            }
        }

        .anv-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* height: 400px; */
            gap: 1.2rem;
            position: relative;
            /* overflow: hidden; */
            border: 1px solid var(--border);
            background-color: var(--neon-cyan);
            padding: 2rem;
            /* background-color: var(--neon-cyan); */
            border-radius: 20px;
            /* margin-bottom: 1.4rem; */
            /* padding: 1.8rem; */
            /* border-bottom: 1px solid rgba(0, 0, 0, 0.1); */
        }

        .anv-card-top::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(255, 255, 255, 0.05) 0 2px, transparent 2px 26px);
            pointer-events: none;
        }

        .anv-card-top .anv-card-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .anv-card-top .anv-card-right {
            position: relative;
        }

        /* .anv-card-top .anv-card-right img{
            position: absolute;
        } */

        .anv-card-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background: var(--green);
            display: flex;
            align-items: center;
            justify-content: center;
            /* color: #7c3aed; */
            color: #ffffff;
            font-size: 22px;
        }

        .anv-card-label {
            font-size: 12px;
            /* color: #aaa; */
            /* color: #212529; */
            /* font-weight: 700; */
            /* text-transform: uppercase; */
            /* letter-spacing: .5px;
            display: block;
            margin-bottom: 4px; */
            font-weight: 500;
            display: block;
            /* margin-bottom: 6px; */
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            /* color: #cc7700; */
            color: #ffffff;
        }

        .anv-card-top h2 {
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            /* color: #1a1a1a; */
            font-family: "Orbitron", sans-serif;
            margin: 0 0 6px;
        }

        .anv-date {
            font-size: 13.5px;
            /* color: #1a1a1a; */
            color: #ffffff;
            /* margin-top: 6px; */
            /* margin-top: 5px; */
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
            /* width: 44px;
            height: 44px;
            min-width: 44px; */
            /* border-radius: 50%; */
            width: 54px;
            height: 54px;
            border-radius: 10px;
            /* background: var(--green);
            color: #fff; */
            /* background: #fef3c7 !important; */
            background-color: #ffffff;
            /* color: #cc7700 !important; */
            color: var(--neon-cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
        }

        /* .anv-poster-name {
            font-size: 24px;
            font-weight: 800;
            font-family: "Orbitron", sans-serif;
            color: #ffffff;
        } */

        .anv-poster-meta {
            font-size: 12px;
            color: #aaa;
            display: block;
        }

        .anv-header-text {
            font-size: 14.5px;
            font-weight: 500;
            /* padding: 1.8rem 1.8rem 0; */
            margin: 0 0 8px;
            color: var(--text-dim);
        }

        .anv-body-text {
            font-size: 14.5px;
            /* color: #1a1a1a; */
            color: var(--text-dim);
            line-height: 1.8;
            margin: 0;
            /* padding: 1.4rem 1.8rem 1.8rem; */
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

        .av-message-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 10px 16px;
            background: #F0F0F0;
            transition: background .2s, border-color .2s;
        }

        .av-msg-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .av-msg-text-col {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .av-msg-status-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .rightbar {
            padding: 1.8rem 1.4rem 1.8rem 1.4rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
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

                    <div class="navbar-bread">
                        <div class="bread-crambs">
                            Dashboard <i class="fa fa-chevron-right"></i>
                            My Subject
                            <i class="fa fa-chevron-right"></i>
                            Stream
                            <i class="fa fa-chevron-right"></i>
                            <b>Announcement</b>
                        </div>

                        <div class="notification">
                            <button>
                                <i class="fa fa-bell"></i>
                            </button>
                        </div>
                    </div>

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="mv-back-link">
                        <i class="fa fa-chevron-left"></i>
                    </a>

                    <div class="anv-main-card">
                        <div class="anv-main-sub-card">
                            <div class="anv-card-top">
                                <div class="anv-card-left">
                                    <div class="anv-card-icon">
                                        <!-- <i class="fa fa-bullhorn"></i> -->

                                        <div class="anv-avatar">
                                            <?= $initials ?>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="anv-card-label ">Announcement</span>
                                        <!-- <h2><?= htmlspecialchars($announcement['title']) ?></h2> -->
                                        <h2 class="anv-poster-name">
                                            <?= htmlspecialchars($announcement['teacher_name']) ?>
                                        </h2>
                                        <span class="anv-date">
                                            Date Received:
                                            <?= date('M j', strtotime($announcement['posted_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="anv-card-right">
                                    <div class="speech-bubble-announce">
                                        <strong>BonBon</strong>
                                        <p id="bonbonMessage"></p>
                                    </div>
                                    <img src="../images/announce-bonbon.png" alt="">
                                </div>
                            </div>


                            <!-- <div class="mv-border"></div> -->
                            <hr>

                            <!-- Full announcement body -->
                            <div class="anv-body">
                                <p class="anv-body-text">
                                    <?= nl2br(htmlspecialchars($announcement['body'])) ?>
                                </p>
                                <div class="reminder">
                                    Reminder
                                </div>
                                <h2 class="anv-header-text">
                                    <?= htmlspecialchars($announcement['title']) ?>
                                </h2>
                            </div>
                        </div>

                        <!-- Message text box -->
                        <div style="background:#fff; border:1px solid rgba(0,0,0,0.1); border-radius:10px; 
                padding:10px 16px; display:none; align-items:center; gap:10px; margin: 6rem 0px 16px;">
                            <input type="text" id="annMsgInput" placeholder="Message..."
                                style="flex:1; border:none; outline:none; font-size:14px; color:#333; background:transparent;">
                            <button onclick="sendAnnMessage()" title="Send" style="background:#00C950;border:none;border-radius:50%;width:36px;height:36px;
                   display:flex;align-items:center;justify-content:center;color:#fff;cursor:pointer;font-size:14px;">
                                <i class="fa fa-paper-plane" id="annSendIcon"></i>
                            </button>
                        </div>

                        <!-- Action icons -->
                        <div style="display:none;justify-content:space-between;align-items:center;
                padding:10px 50px;border-radius:12px;border:1px solid rgba(0,0,0,0.1);background:#F0F0F0;">
                            <button title="Attach file" onclick="document.getElementById('attachFileInput').click()"
                                style="background:none;border:none;color: var(--green);font-size:23px;cursor:pointer;padding:0;">
                                <i class="fa fa-paperclip"></i>
                            </button>
                            <button title="Image" onclick="document.getElementById('attachImageInput').click()"
                                style="background:none;border:none;color: var(--green);font-size:23px;cursor:pointer;padding:0;">
                                <i class="fa fa-image"></i>
                            </button>
                            <button title="Video" onclick="document.getElementById('attachVideoInput').click()"
                                style="background:none;border:none;color: var(--green);font-size:23px;cursor:pointer;padding:0;">
                                <i class="fa fa-film"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Reply box -->
                <!-- <div class="anv-message-card">
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
                    </div> -->

            <?php endif; ?>

        </div>
    </div>
    </div>
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        (function typewriter() {
            const el = document.getElementById('bonbonMessage');
            if (!el) return;

            const message = "Heads up! <?= htmlspecialchars(addslashes($announcement['title'])) ?> — check the reminder below for details.";
            const speed = 20; // ms per character — lower = faster
            let i = 0;

            // cursor span that blinks while typing
            const cursor = document.createElement('span');
            cursor.className = 'typing-cursor';
            el.appendChild(cursor);

            function type() {
                if (i < message.length) {
                    cursor.insertAdjacentText('beforebegin', message.charAt(i));
                    i++;
                    setTimeout(type, speed);
                } else {
                    // remove cursor once finished (optional)
                    setTimeout(() => cursor.remove(), 1200);
                }
            }

            type();
        })();
    </script>

    <script>
        let annAttachedFile = null;

        document.getElementById('attachFileInput').addEventListener('change', function () {
            if (this.files[0]) setAnnFile(this.files[0], 'file');
        });
        document.getElementById('attachImageInput').addEventListener('change', function () {
            if (this.files[0]) setAnnFile(this.files[0], 'image');
        });
        document.getElementById('attachVideoInput').addEventListener('change', function () {
            if (this.files[0]) setAnnFile(this.files[0], 'video');
        });

        function setAnnFile(file, type) {
            annAttachedFile = file;
            const msgBox = document.getElementById('msgBox');
            const msgIcon = document.getElementById('msgIcon');
            const msgTextCol = document.getElementById('msgTextCol');
            const msgTitle = document.getElementById('msgTitle');
            const msgPlaceholder = document.getElementById('msgPlaceholder');
            const msgActionBtn = document.getElementById('msgActionBtn');

            msgBox.style.background = '#f0fdf4';
            msgBox.style.borderColor = '#bbf7d0';
            msgPlaceholder.style.display = 'none';
            msgIcon.style.display = 'block';
            msgTextCol.style.display = 'flex';
            msgTitle.textContent = file.name;
            msgActionBtn.style.background = '#00C950';

            // Show preview
            const strip = document.getElementById('attachPreview');
            const img = document.getElementById('previewImage');
            const vid = document.getElementById('previewVideo');
            img.style.display = 'none';
            vid.style.display = 'none';

            if (type === 'image') {
                const reader = new FileReader();
                reader.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
                reader.readAsDataURL(file);
                strip.style.display = 'block';
            } else if (type === 'video') {
                vid.src = URL.createObjectURL(file);
                vid.style.display = 'block';
                strip.style.display = 'block';
            } else {
                strip.style.display = 'none';
            }
        }

        function sendAnnMessage() {
            const input = document.getElementById('annMsgInput');
            const msg = input.value.trim();
            if (!msg && !annAttachedFile) {
                input.style.outline = '2px solid #ef4444';
                input.placeholder = 'Please type a message first!';
                setTimeout(() => { input.style.outline = ''; input.placeholder = 'Message...'; }, 2500);
                return;
            }
            // Message sent feedback
            input.value = '';
            annAttachedFile = null;
            alert('Message sent!'); // replace with your toast if needed
        }

        document.getElementById('annMsgInput').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') sendAnnMessage();
        });
    </script>
</body>

</html>