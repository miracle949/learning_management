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
                        <a href="/learning_management/public/?url=classes">
                            <div class="data_icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div class="data_text">
                                <p>
                                    <?= $enrolledCount ?>
                                </p>
                                <div class="data-head">Enrolled Classes</div>
                                <div class="stat-data"><i class="fa fa-arrow-up"></i> All active</div>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="/learning_management/public/?url=assignments">
                            <div class="data_icon">
                                <i class="fa fa-clock"></i>
                            </div>
                            <div class="data_text">
                                <p>
                                    <?= $pendingCount ?>
                                </p>
                                <div class="data-head">Pending Tasks</div>
                                <div class="stat-data">Due this week</div>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="#">
                            <div class="data_icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="data_text">
                                <p>
                                    <?= $completedCount ?>
                                </p>
                                <div class="data-head">Completed Task</div>
                                <div class="stat-data"><i class="fa fa-arrow-up"></i> +2 this week</div>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <a href="#">
                                <div class="data_icon">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <div class="data_text">
                                    <p>
                                        62%
                                    </p>
                                    <div class="data-head">Overall Progress</div>
                                    <div class="stat-data"><i class="fa fa-arrow-up"></i> +8 this week</div>
                                </div>
                            </a>
                        </div>
                        <!-- <div class="data_icon">
                                    <i class="fa fa-check-circle"></i>
                                </div> -->
                    </div>
                </div>

                <div class="main-parent">
                    <div class="side-dashboard">
                        <div class="parent-performance">

                            <div class="update subject-performance">
                                <div class="header">
                                    <div class="header-icon">
                                        <i class="fa fa-clock"></i>
                                    </div>
                                    <h3>Pending Tasks</h3>
                                </div>
                                <?php if (!empty($pendingAssignments)): ?>
                                    <div class="body" style="height: 313px; overflow-y: auto;">

                                        <?php foreach ($pendingAssignments as $item):
                                            $daysLeft = '';
                                            if (!empty($item['due_date'])) {
                                                $now = new DateTime('today');
                                                $due = new DateTime($item['due_date']);

                                                if ($due < $now) {
                                                    continue; // skip overdue
                                                }

                                                $interval = $now->diff($due);

                                                if ($interval->days == 0) {
                                                    $daysLeft = 'Due today';
                                                } elseif ($interval->y == 0 && $interval->m == 0) {
                                                    // Less than a month away — show in days
                                                    $daysLeft = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' left';
                                                } else {
                                                    // A month or more away — show in months
                                                    $months = ($interval->y * 12) + $interval->m;
                                                    // round up if there are extra days left over (e.g. 1 month 20 days -> "2 months left")
                                                    if ($interval->d >= 15) {
                                                        $months++;
                                                    }
                                                    $daysLeft = $months . ' month' . ($months > 1 ? 's' : '') . ' left';
                                                }
                                            }
                                            ?>
                                            <div class="parent-update">
                                                <div class="update-icon">
                                                    <i class="fa fa-file-pen"></i>
                                                </div>
                                                <div class="update-box">
                                                    <div class="update-text">
                                                        <p>
                                                            <?= htmlspecialchars($item['task']) ?>
                                                        </p>
                                                        <span>

                                                            <?= htmlspecialchars($item['subject_name']) ?>

                                                            ·

                                                            Due:
                                                            <?= !empty($item['due_date']) ? date('F j, Y', strtotime($item['due_date'])) : 'No due date' ?>
                                                        </span>
                                                    </div>

                                                    <div class="update-links">
                                                        <?php if ($daysLeft): ?><span>
                                                                <?= htmlspecialchars($daysLeft) ?>
                                                            </span>
                                                        <?php endif; ?>

                                                        <a
                                                            href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                                            View Task <i class="fa fa-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>


                                    </div>
                                <?php else: ?>
                                    <div class="body is-empty" style="height: 285px;">
                                        <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                        <p class="m-0" style="font-size: 14.5px; color: var(--text-dim);">No pending tasks.
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="perform performance-trend">
                                <div class="header">
                                    <div class="header-icon">
                                        <i class="fa fa-bullhorn"></i>
                                    </div>
                                    <h3>Announcements</h3>
                                </div>
                                <?php if (!empty($announcements)): ?>
                                    <div class="body" style="height: 323px; overflow-y: auto;">

                                        <?php foreach ($announcements as $ann): ?>
                                            <div class="progress-box">
                                                <div class="announcement-icon">
                                                    <i class="fa fa-bell"></i>
                                                </div>
                                                <div class="announcement-box">
                                                    <div class="title-announce">
                                                        <h4>
                                                            <?= htmlspecialchars($ann['title']) ?>
                                                        </h4>
                                                        <p>
                                                            <?= nl2br(htmlspecialchars($ann['message'])) ?>
                                                        </p>
                                                        <span>
                                                            <?= htmlspecialchars($ann['subject_name']) ?>
                                                        </span>
                                                    </div>
                                                    <div class="view">
                                                        <small>
                                                            <?= date('F j, Y', strtotime($ann['created_at'])) ?>

                                                        </small>
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
                    </div>
                    <div class="right-dashboard parent-performance">
                        <div class="update missing-task">
                            <div class="header">
                                <div class="header-icon header-icon-danger">
                                    <i class="fa fa-triangle-exclamation"></i>
                                </div>
                                <h3>Missing Task</h3>
                            </div>
                            <div class="body is-empty" style="height: 285px;">
                                <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                <p class="m-0" style="font-size: 14.5px; color: var(--text-dim);">No missing tasks.</p>
                            </div>
                            <!-- Example populated state — mirror this markup per missing item:
                            <div class="body" style="height: 190px; overflow-y: auto;">
                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-file-circle-exclamation"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 2 - Subnetting</p>
                                            <span>Networking · Was due: June 14, 2026</span>
                                        </div>
                                        <div class="update-links">
                                            <span>3 days overdue</span>
                                            <a href="#">View Task <i class="fa fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>

                        <div class="update upcoming-deadlines">
                            <div class="header">
                                <div class="header-icon">
                                    <i class="fa fa-clock"></i>
                                </div>
                                <h3>Upcoming deadlines</h3>
                            </div>
                            <div class="body" style="height: 280px; overflow-y: auto;">

                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 3 - IP Addressing</p>
                                            <span>Due: June 20, 2026 · 08:00 PM</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 3 - IP Addressing</p>
                                            <span>Due: June 20, 2026 · 10:00 AM</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 3 - IP Addressing</p>
                                            <span>Due: June 20, 2026 · 05:00 PM</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 3 - IP Addressing</p>
                                            <span>Due: June 20, 2026 · 06:00 PM</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 3 - IP Addressing</p>
                                            <span>Due: June 20, 2026 · 06:00 PM</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="parent-update">
                                    <div class="update-icon">
                                        <i class="fa fa-calendar-days"></i>
                                    </div>
                                    <div class="update-box">
                                        <div class="update-text">
                                            <p>Quiz 3 - IP Addressing</p>
                                            <span>Due: June 20, 2026 · 06:00 PM</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="perform enrolled-subjects">
                    <div class="header">
                        <div class="header-icon">
                            <i class="fa fa-book"></i>
                        </div>
                        <h3>My Modules</h3>
                    </div>

                    <div class="body modules-chart">

                        <div class="chart-row">
                            <div class="chart-yaxis">
                                <span>100%</span>
                                <span>75%</span>
                                <span>50%</span>
                                <span>25%</span>
                                <span>0%</span>
                            </div>
                            <div class="chart-bars">
                                <div class="chart-col" data-label="Module 1 · Hardware">
                                    <span class="chart-val">70%</span>
                                    <div class="chart-bar" style="height:70%; background: var(--neon-cyan);"></div>
                                </div>
                                <div class="chart-col" data-label="Module 2 · Software">
                                    <span class="chart-val">50%</span>
                                    <div class="chart-bar" style="height:50%; background: #5533CC;"></div>
                                </div>
                                <div class="chart-col" data-label="Module 3 · Networking">
                                    <span class="chart-val">70%</span>
                                    <div class="chart-bar" style="height:70%; background: #1A9E5C;"></div>
                                </div>
                                <div class="chart-col" data-label="Module 4 · Repair">
                                    <span class="chart-val">70%</span>
                                    <div class="chart-bar" style="height:70%; background: #CC7700;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="chart-legend">
                            <div class="chart-legend-spacer"></div>
                            <div class="chart-legend-items">
                                <a href="#"><i style="background: var(--neon-cyan);"></i>Module 1 · Hardware</a>
                                <a href="#"><i style="background: #5533CC;"></i>Module 2 · Software</a>
                                <a href="#"><i style="background: #1A9E5C;"></i>Module 3 · Networking</a>
                                <a href="#"><i style="background: #CC7700;"></i>Module 4 · Repair</a>
                            </div>
                        </div> -->
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