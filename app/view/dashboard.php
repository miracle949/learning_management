<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css_folder/style.css">
    <link rel="stylesheet" href="../css_folder/components.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>
    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>


        <?php include("../components/sidebar.php"); ?>

        <?php
        // Fallback defaults in case controller didn't pass these
        $enrolledCount = $enrolledCount ?? 0;
        $pendingCount = $pendingCount ?? 0;
        $pendingAssignments = $pendingAssignments ?? [];
        $announcements = $announcements ?? [];

        if (!function_exists('shs_time_ago')) {
            function shs_time_ago($datetime)
            {
                $timestamp = strtotime($datetime);
                if (!$timestamp)
                    return '';
                $diff = time() - $timestamp;
                if ($diff < 0)
                    $diff = 0;                 // ✅ clamp — never show a negative "ago"
                if ($diff < 60)
                    return 'just now';
                $mins = (int) floor($diff / 60);
                if ($mins < 60)
                    return $mins . ' minute' . ($mins == 1 ? '' : 's') . ' ago';
                $hours = (int) floor($mins / 60);
                if ($hours < 24)
                    return $hours . ' hour' . ($hours == 1 ? '' : 's') . ' ago';
                $days = (int) floor($hours / 24);
                if ($days < 7)
                    return $days . ' day' . ($days == 1 ? '' : 's') . ' ago';
                return date('M j, Y', $timestamp);
            }
        }

        ?>

        <div class="rightbar">

            <!-- <div class="hero-bg"></div>
            <div class="stars" id="stars"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div> -->

            <?php if (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 12'): ?>

                <!-- <h4><?= htmlspecialchars($_SESSION["name"]) ?></h4> -->

                <!-- <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <a href="/learning_management/public/?url=classes">Browse Classes <i
                                    class="fa fa-arrow-right"></i></a>

                        </div>
                    </div>
                </div> -->

                <div class="welcome-nav">
                    <!-- <div class="burger-button">
                        <button><i class="fa fa-bars"></i></button>
                    </div> -->
                    <div class="welcome-nav-text">
                        <div class="burger-button">
                            <button><i class="fa fa-bars"></i></button>
                        </div>
                        <div class="welcome-text">
                            <h2>Student Dashboard</h2>

                            <p>Wednesday, June 18, 2026 · CSS Batch 2026</p>
                        </div>
                    </div>

                    <div class="welcome-nav-acc">
                        <button>
                            <div class="notification-icon">
                                <i class="fa fa-message"></i>
                            </div>
                        </button>
                        <button>
                            <div class="notification-icon">
                                <i class="fa fa-bell"></i>
                            </div>
                        </button>

                        <button>
                            <?php
                            $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                            echo $initial;
                            ?>
                        </button>
                    </div>
                </div>

                <h2 class="mobile-heading">Hello, <?= htmlspecialchars($_SESSION["name"]) ?> - Good Day!</h2>
                <p class="mobile-paragraph">Hope you're having a wonderful day. Let's make the most of it!</p>

                <div class="welcome-banner">
                    <div class="welcome-text">
                        <h2>Hello, <?= htmlspecialchars($_SESSION["name"]) ?> - Good Day!</h2>
                        <p>Hope you're having a wonderful day. Let's make the most of it!</p>

                        <div class="date-batch">
                            <div class="date">
                                <i class="fa fa-calendar"></i>

                                <?= $_SESSION['grade_level'] ?>
                                <?= $_SESSION['section'] ?>
                            </div>
                            <div class="batch">
                                <i class="fa fa-graduation-cap"></i>

                                CSS Batch 2026
                            </div>
                        </div>

                        <div class="buttons-group">
                            <a href="#">
                                <i class="fa fa-book-open"></i>
                                Browse Classes <i class="fa fa-arrow-right"></i></a>

                            <a href="#">Continue Learning</a>
                        </div>
                    </div>
                    <div class="welcome-img">
                        <div class="speech-bubble">
                            <strong>I'm, BonBon</strong>
                            <p id="bonbonMessage"></p>
                        </div>
                        <img class="image1" src="../images/robot-ai6.png" alt="">
                        <img class="image2" src="../images/robot-ai5.png" alt="">
                    </div>
                </div>

                <div class="card-parent-box">
                    <div class="card-box">
                        <a href="/learning_management/public/?url=assignments">
                            <div class="data_text">
                                <div class="data-head">Pending Tasks</div>
                                <p><?= $pendingCount ?></p>
                                <div class="stat-data"><?= $pendingDueThisWeek ?> due this week</div>
                            </div>
                            <div class="data_icon"><i class="fa fa-clock"></i></div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="/learning_management/public/?url=assignments">
                            <div class="data_text">
                                <div class="data-head">Due Soon</div>
                                <p><?= $dueSoonCount ?></p>
                                <div class="stat-data"><i class="fa fa-clock"></i> Next 7 days</div>
                            </div>
                            <div class="data_icon"><i class="fa fa-book-open"></i></div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="#">
                            <div class="data_text">
                                <div class="data-head">Completed Task</div>
                                <p><?= $completedCount ?></p>
                                <div class="stat-data"><i class="fa fa-arrow-up"></i> +<?= $completedThisWeek ?> this week
                                </div>
                            </div>
                            <div class="data_icon"><i class="fa fa-check-circle"></i></div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="#">
                            <div class="data_text">
                                <div class="data-head">Overall Progress</div>
                                <p><?= $overallProgressPercent ?>%</p>
                                <div class="stat-data"><i class="fa fa-arrow-up"></i> +<?= $overallProgressDelta ?>% this
                                    week</div>
                            </div>
                            <div class="data_icon"><i class="fa fa-line-chart"></i></div>
                        </a>
                    </div>
                </div>

                <div class="main-parent">
                    <div class="parent-recent-announcement">
                        <div class="perform performance-trend">
                            <div class="header">
                                <h3>My Recent Activity</h3>
                                <p>Your latest actions</p>
                            </div>
                            <?php if (!empty($recentActivities)): ?>
                                <div class="body" style="height: 355px; overflow-y: auto;">
                                    <?php foreach ($recentActivities as $act):
                                        $typeMap = [
                                            'module_opened' => ['fa-folder-open', 'Opened module', 'blue'],
                                            'lesson_opened' => ['fa-book-open', 'Opened lesson', 'blue'],
                                            'activity_completed' => ['fa-pencil', 'Completed activity', 'purple'],
                                            'quiz_completed' => ['fa-clipboard-check', 'Completed quiz', 'green'],
                                            'flashcards_viewed' => ['fa-layer-group', 'Reviewed flashcards', 'amber'],
                                        ];
                                        [$icon, $label, $tone] = $typeMap[$act['activity_type']] ?? ['fa-circle-check', 'Activity', 'blue'];
                                        ?>
                                        <div class="activity-row">
                                            <div class="activity-icon activity-icon--<?= $tone ?>">
                                                <i class="fa <?= $icon ?>"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-top">
                                                    <h4><?= htmlspecialchars($act['title']) ?></h4>
                                                    <small><?= shs_time_ago($act['created_at']) ?></small>
                                                </div>
                                                <div class="activity-bottom">
                                                    <span
                                                        class="activity-label activity-label--<?= $tone ?>"><?= htmlspecialchars($label) ?></span>
                                                    <div class="divider">
                                                        |
                                                    </div>
                                                    <?php if (!empty($act['subject_name'])): ?>
                                                        <span
                                                            class="activity-subject"><?= htmlspecialchars($act['subject_name']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="body is-empty" style="height: 280px;">
                                    <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                    <p class="m-0" style="font-size:14px; color: var(--text-dim);">No recent activity yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="perform performance-trend">
                            <div class="header">
                                <!-- <div class="header-icon">
                                <i class="fa fa-bullhorn"></i>
                            </div> -->
                                <h3>Announcements</h3>
                                <p>Latest Updates</p>
                            </div>
                            <?php if (!empty($announcements)): ?>
                                <div class="body" style="height: 355px; overflow-y: auto;">

                                    <?php foreach ($announcements as $ann): ?>
                                        <div class="progress-box">
                                            <div class="announcement-icon">
                                                <i class="fa fa-bell"></i>
                                            </div>
                                            <div class="announcement-box">
                                                <div class="title-announce">
                                                    <div class="title-parent">
                                                        <h4>
                                                            <?= htmlspecialchars($ann['title']) ?>
                                                        </h4>
                                                        <p>
                                                            <?= nl2br(htmlspecialchars($ann['message'])) ?>
                                                        </p>
                                                    </div>

                                                    <!-- <span>
                                                        <?= htmlspecialchars($ann['subject_name']) ?>
                                                    </span> -->

                                                    <div class="view">
                                                        <small>
                                                            <?= date('F j, Y', strtotime($ann['created_at'])) ?>

                                                        </small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php endforeach; ?>



                                </div>
                            <?php else: ?>

                                <div class="body is-empty" style="height: 280px;">
                                    <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                    <p class="m-0" style="font-size:14.5px;">No announcements yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="parent-continue-upcoming">
                        <div class="update continue-learning">
                            <div class="header">
                                <h3>Your current progress</h3>
                                <p>Pick up right where you left off</p>
                            </div>
                            <?php if (!empty($inProgressModules)): ?>
                                <div class="body" style="height: 355px; overflow-y: auto;">
                                    <?php foreach ($inProgressModules as $ip):
                                        $pct = (int) round($ip['completion_percentage']);
                                        $circumference = 169.6; // 2 * π * r(27)
                                        $offset = round($circumference * (1 - max(0, min(100, $pct)) / 100), 1);
                                        ?>
                                        <div class="parent-update">
                                            <div class="parent-sub-update">
                                                <div class="update-progress-ring">
                                                    <svg viewBox="0 0 64 64">
                                                        <defs>
                                                            <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                                <stop offset="0%" stop-color="#ffb347" />
                                                                <stop offset="100%" stop-color="#ff7a00" />
                                                            </linearGradient>
                                                        </defs>
                                                        <circle class="ring-track" cx="32" cy="32" r="27" fill="none"
                                                            stroke-width="7" />
                                                        <circle class="ring-fill" cx="32" cy="32" r="27" fill="none"
                                                            stroke-width="7" stroke-linecap="round" stroke="url(#ringGradient)"
                                                            stroke-dasharray="<?= $circumference ?>"
                                                            stroke-dashoffset="<?= $offset ?>" />
                                                    </svg>
                                                    <span class="ring-pct"><?= $pct ?>%</span>
                                                </div>
                                                <div class="update-box">
                                                    <div class="update-text">
                                                        <p><?= htmlspecialchars($ip['title']) ?></p>
                                                        <span class="update-eyebrow">
                                                            <?= !empty($ip['current_topic'])
                                                                ? htmlspecialchars($ip['current_topic'])
                                                                : htmlspecialchars($ip['subject_name']) ?>
                                                        </span>


                                                        <?php if (!empty($ip['next_lesson_title'])): ?>
                                                            <span class="update-next">
                                                                Next up: <?= htmlspecialchars($ip['next_lesson_title']) ?>
                                                                — Lesson <?= (int) $ip['next_lesson_number'] ?> of
                                                                <?= (int) $ip['total_lessons'] ?>
                                                            </span>
                                                        <?php endif; ?>

                                                        <div class="update-submeta">
                                                            <span class="update-submeta-item">
                                                                <i class="fa fa-check"></i>
                                                                <?= (int) $ip['completed_lessons'] ?> of
                                                                <?= (int) $ip['total_lessons'] ?> lessons done
                                                            </span>
                                                            <?php if (!empty($ip['last_accessed_at'])): ?>
                                                                <span class="update-submeta-item">
                                                                    <i class="fa fa-rotate-right"></i>
                                                                    Last opened <?= shs_time_ago($ip['last_accessed_at']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="/learning_management/public/?url=subject_lessons&subject=<?= urlencode($ip['subject_code']) ?>&id=<?= (int) $ip['id'] ?>"
                                                class="continue-link">
                                                Continue <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="body is-empty" style="height: 220px;">
                                    <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                    <p class="m-0" style="font-size:14.5px; color: var(--text-dim);">No modules in progress
                                        right now.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="update upcoming-deadlines">
                            <div class="header">
                                <h3>Upcoming deadlines</h3>
                                <p>Classworks due across your subjects</p>
                            </div>
                            <?php if (!empty($upcomingDeadlines)): ?>
                                <div class="body" style="height: 355px; overflow-y: auto;">
                                    <?php foreach ($upcomingDeadlines as $pending): ?>
                                        <div class="parent-update">
                                            <div class="parent-sub-update">
                                                <div class="update-icon">
                                                    <i class="fa fa-calendar-days"></i>
                                                </div>
                                                <div class="update-box">
                                                    <div class="update-text">
                                                        <p>
                                                            <?= htmlspecialchars($pending['task']) ?>
                                                        </p>
                                                        <span>
                                                            Due:
                                                            <?= date('F j, Y', strtotime($pending['due_date'])) ?>
                                                            <?php if (!empty($pending['due_time'])): ?>
                                                                ·
                                                                <?= date('h:i A', strtotime($pending['due_time'])) ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="upcoming-icon">
                                                Upcoming
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="body is-empty" style="height: 280px;">
                                    <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                    <p>No upcoming deadlines in the next 30 days.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            <?php elseif (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 11'): ?>

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                        </div>
                    </div>

                    <div class="welcome-body">
                        <div class="buttons-group">
                            <i class="fa fa-book-open"></i>
                            <a href="/learning_management/public/?url=classes">Browse Classes <i
                                    class="fa fa-arrow-right"></i></a>

                        </div>
                    </div>
                </div>

            <?php else: ?>

                <div class="welcoming">
                    <h2>No grade level or section assigned to your account. Please contact your administrator.</h2>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        (function typewriter() {
            const el = document.getElementById('bonbonMessage');
            if (!el) return;

            const message = "Welcome! I'm your learning assistant, ready to guide you through your journey!";
            const speed = 28;
            let i = 0;

            const cursor = document.createElement('span');
            cursor.className = 'typing-cursor';
            el.appendChild(cursor);

            function type() {
                if (i < message.length) {
                    cursor.insertAdjacentText('beforebegin', message.charAt(i));
                    i++;
                    setTimeout(type, speed);
                } else {
                    setTimeout(() => cursor.remove(), 1200);
                }
            }

            type();
        })();
    </script>

</body>

</html>