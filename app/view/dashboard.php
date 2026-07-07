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
                    <div class="welcome-nav-text">
                        <h2>Student Dashboard</h2>

                        <p>Wednesday, June 18, 2026 · CSS Batch 2026</p>
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
                        <img src="../images/robot-ai6.png" alt="">
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
                                    <div class="body is-empty" style="height: 233px;">
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

                                    <div class="body is-empty" style="height: 233px;">
                                        <i class="fa fa-check-circle" style="font-size: 20px; color: var(--text-dim)"></i>
                                        <p class="m-0" style="font-size:14.5px;">No announcements yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                        <div class="perform enrolled-subjects">
                            <div class="header">
                                <div class="header-icon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <h3>My Modules</h3>
                            </div>

                            <div class="body" style="height: 320px; overflow-y: auto;">

                                <div class="module-progress">
                                    <div class="module-header">
                                        <h4>Module 1 - Hardware Assembly & Dissambly</h4>

                                        <span>70%</span>
                                    </div>

                                    <div class="parent-progress-percent">
                                        <div class="progress"></div>
                                    </div>

                                    <div class="module-footer">
                                        <p>Hardware</p>

                                        <a href="#">View progress <i class="fa fa-arrow-right"></i> </a>
                                    </div>
                                </div>

                                <div class="module-progress">
                                    <div class="module-header">
                                        <h4>Module 2 - OS Installation & Configuration</h4>

                                        <span>50%</span>
                                    </div>

                                    <div class="parent-progress-percent">
                                        <div class="progress"></div>
                                    </div>

                                    <div class="module-footer">
                                        <p>Software</p>

                                        <a href="#">View progress <i class="fa fa-arrow-right"></i> </a>
                                    </div>
                                </div>

                                <div class="module-progress">
                                    <div class="module-header">
                                        <h4>Module 3 - Network Setup & Cabling</h4>

                                        <span>70%</span>
                                    </div>

                                    <div class="parent-progress-percent">
                                        <div class="progress"></div>
                                    </div>

                                    <div class="module-footer">
                                        <p>Networking</p>

                                        <a href="#">View progress <i class="fa fa-arrow-right"></i> </a>
                                    </div>
                                </div>

                                <div class="module-progress">
                                    <div class="module-header">
                                        <h4>Module 4 - Hardware Assembly & Dissambly</h4>

                                        <span>70%</span>
                                    </div>

                                    <div class="parent-progress-percent">
                                        <div class="progress"></div>
                                    </div>

                                    <div class="module-footer">
                                        <p>Repair</p>

                                        <a href="#">View progress <i class="fa fa-arrow-right"></i> </a>
                                    </div>
                                </div>
                                <!-- <div class="progress-box">
                                    <h4>Introduction to Philosophy of Human Person</h4>

                                    <div class="parent-progress">
                                        <div class="progress-header">
                                            <p>Progress</p>
                                            <span>75%</span>
                                        </div>
                                        <div class="parent-progress-percent">
                                            <div class="progress"></div>
                                        </div>
                                    </div>

                                    <div class="view">
                                        <a href="#">View Progress <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>

                                <div class="progress-box">
                                    <h4>Introduction to Philosophy of Human Person</h4>

                                    <div class="parent-progress">
                                        <div class="progress-header">
                                            <p>Progress</p>
                                            <span>75%</span>
                                        </div>
                                        <div class="parent-progress-percent">
                                            <div class="progress"></div>
                                        </div>
                                    </div>

                                    <div class="view">
                                        <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>

                                <div class="progress-box">
                                    <h4>Introduction to Philosophy of Human Person</h4>

                                    <div class="parent-progress">
                                        <div class="progress-header">
                                            <p>Progress</p>
                                            <span>75%</span>
                                        </div>
                                        <div class="parent-progress-percent">
                                            <div class="progress"></div>
                                        </div>
                                    </div>

                                    <div class="view">
                                        <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>

                                <div class="progress-box">
                                    <h4>Introduction to Philosophy of Human Person</h4>

                                    <div class="parent-progress">
                                        <div class="progress-header">
                                            <p>Progress</p>
                                            <span>75%</span>
                                        </div>
                                        <div class="parent-progress-percent">
                                            <div class="progress"></div>
                                        </div>
                                    </div>

                                    <div class="view">
                                        <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="right-dashboard">
                        <div class="right-dashboard-nav">
                            <div class="nav-icon">
                                <!-- <div class="right-nav-icon">
                                    <i class="fa fa-user"></i>
                                </div> -->
                                <i class="fa fa-user"></i>
                                <h3>Profile</h3>
                            </div>

                            <!-- <button>
                                <i class="fa fa-pencil"></i>
                            </button> -->
                        </div>

                        <div class="right-dashboard-body">
                            <div class="profile-icon">
                                <?php
                                $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                                echo $initial;
                                ?>
                            </div>

                            <h4><?= htmlspecialchars($_SESSION["name"]) ?></h4>
                            <p>Senior High Student</p>

                            <div class="parent-calendar">
                                <div class="calendar-nav">
                                    <i class="fa fa-chevron-left" id="calPrev" style="cursor:pointer;"></i>
                                    <h5 id="calMonthLabel">July 2024</h5>
                                    <i class="fa fa-chevron-right" id="calNext" style="cursor:pointer;"></i>
                                </div>
                                <div class="cal-grid" id="calGrid"></div>
                            </div>

                            <div class="card-divider"></div>

                            <div class="upcoming-parent">
                                <div class="nav-icon">
                                    <!-- <div class="upcoming-nav">
                                        <i class="fa fa-clock"></i>
                                    </div> -->
                                    <i class="fa fa-clock"></i>
                                    <h3>Upcoming deadlines</h3>
                                </div>

                                <div class="upcoming-parent-box">
                                    <div class="upcoming-box">
                                        <div class="upcoming-icon">
                                            <i class="fa fa-calendar-days"></i>
                                        </div>
                                        <div class="upcoming-text">
                                            <h5>Quiz 3 - IP Addressing</h5>
                                            <p>Due: June 20, 2026 <b>| 08:00 PM</b></p>
                                        </div>
                                    </div>

                                    <div class="upcoming-box">
                                        <div class="upcoming-icon">
                                            <i class="fa fa-calendar-days"></i>
                                        </div>
                                        <div class="upcoming-text">
                                            <h5>Quiz 3 - IP Addressing</h5>
                                            <p>Due: June 20, 2026 <b>| 10:00 AM</b></p>
                                        </div>
                                    </div>

                                    <div class="upcoming-box">
                                        <div class="upcoming-icon">
                                            <i class="fa fa-calendar-days"></i>
                                        </div>
                                        <div class="upcoming-text">
                                            <h5>Quiz 3 - IP Addressing</h5>
                                            <p>Due: June 20, 2026 <b>| 05:00 PM</b></p>
                                        </div>
                                    </div>

                                    <div class="upcoming-box">
                                        <div class="upcoming-icon">
                                            <i class="fa fa-calendar-days"></i>
                                        </div>
                                        <div class="upcoming-text">
                                            <h5>Quiz 3 - IP Addressing</h5>
                                            <p>Due: June 20, 2026 <b>| 06:00 PM</b></p>
                                        </div>
                                    </div>

                                    <div class="upcoming-box">
                                        <div class="upcoming-icon">
                                            <i class="fa fa-calendar-days"></i>
                                        </div>
                                        <div class="upcoming-text">
                                            <h5>Quiz 3 - IP Addressing</h5>
                                            <p>Due: June 20, 2026 <b>| 06:00 PM</b></p>
                                        </div>
                                    </div>

                                    <div class="upcoming-box">
                                        <div class="upcoming-icon">
                                            <i class="fa fa-calendar-days"></i>
                                        </div>
                                        <div class="upcoming-text">
                                            <h5>Quiz 3 - IP Addressing</h5>
                                            <p>Due: June 20, 2026 <b>| 06:00 PM</b></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="parent-calendar">
                                <div class="calendar-nav">
                                    <i class="fa fa-chevron-left"></i>
                                    <h5>July 2024</h5>
                                    <i class="fa fa-chevron-right"></i>
                                </div>

                                <div class="calendar-body">
                                    <div class="date">
                                        <p>M</p>
                                        <div class="circle">
                                            <span>15</span>
                                        </div>
                                    </div>

                                    <div class="date">
                                        <p>T</p>
                                        <div class="circle">
                                            <span>16</span>
                                        </div>
                                    </div>

                                    <div class="date">
                                        <p>W</p>
                                        <div class="circle">
                                            <span>17</span>
                                        </div>
                                    </div>

                                    <div class="date">
                                        <p>T</p>
                                        <div class="circle">
                                            <span>18</span>
                                        </div>
                                    </div>

                                    <div class="date">
                                        <p>F</p>
                                        <div class="circle">
                                            <span>19</span>
                                        </div>
                                    </div>

                                    <div class="date">
                                        <p>S</p>
                                        <div class="circle">
                                            <span>20</span>
                                        </div>
                                    </div>

                                    <div class="date">
                                        <p>S</p>
                                        <div class="circle">
                                            <span>21</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="cal-grid" id="calGrid">
                                </div>
                            </div> -->
                        </div>

                    </div>
                </div>

            <?php elseif (!empty($_SESSION['grade_level']) && $_SESSION['grade_level'] === 'Grade 11'): ?>

                <div class="welcome-user">
                    <div class="welcome-text">

                        <h2>Welcome, <?= htmlspecialchars($_SESSION["name"]) ?>! 👋</h2>
                        <div class="d-flex gap-2">
                            <span><?= htmlspecialchars($_SESSION["grade_level"]) ?></span>
                            <!-- <span>—</span> -->
                            <span><?= htmlspecialchars($_SESSION["section"]) ?></span>
                            <!-- <span>Here's what's happening with your learning today</span> -->
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

                <div class="card-parent-box">
                    <div class="card-box">
                        <a href="/learning_management/public/?url=classes">
                            <div class="data_text">
                                <span>Enrolled Classes</span>
                                <p>
                                    <?= $enrolledCount ?>
                                </p>
                            </div>
                            <div class="data_icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <a href="/learning_management/public/?url=assignments">
                            <div class="data_text">
                                <span>Pending Tasks</span>
                                <p>
                                    <?= $pendingCount ?>
                                </p>
                            </div>
                            <div class="data_icon">
                                <i class="fa fa-clock"></i>
                            </div>
                        </a>
                    </div>

                    <div class="card-box">
                        <div class="data_text">
                            <span>Completed Task</span>
                            <p><?= $completedCount ?></p>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>

                    <!-- <div class="card-box">
                        <div class="data_text">
                            <span>Progress</span>
                            <p>Static</p>
                        </div>
                        <div class="data_icon">
                            <i class="fa fa-chart-line"></i>
                        </div>
                    </div> -->
                </div>

                <div class="parent-performance">
                    <div class="update subject-performance">
                        <div class="header">
                            <div class="header-icon">
                                <i class="fa fa-clock"></i>
                            </div>
                            <h3>Pending Tasks</h3>
                        </div>
                        <div class="body">
                            <?php if (!empty($pendingAssignments)): ?>
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
                                    <div class="update-box">
                                        <p><?= htmlspecialchars($item['task']) ?></p>
                                        <span>Due date:
                                            <?= !empty($item['due_date']) ? date('F j, Y', strtotime($item['due_date'])) : 'No due date' ?></span>
                                        <?php if ($daysLeft): ?><span><?= htmlspecialchars($daysLeft) ?></span><?php endif; ?>
                                        <a
                                            href="/learning_management/public/?url=assignment_view&subject=<?= urlencode($item['subject_code']) ?>&id=<?= $item['id'] ?>">
                                            View Task <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted m-0" style="font-size: 14.5px;">No pending tasks.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="perform performance-trend">
                        <div class="header">
                            <i class="fa fa-question-circle"></i>
                            <h3>Announcements</h3>
                        </div>
                        <div class="body">
                            <?php if (!empty($announcements)): ?>
                                <?php foreach ($announcements as $ann): ?>
                                    <div class="progress-box">
                                        <h4><?= htmlspecialchars($ann['subject_name']) ?></h4>
                                        <p style="font-size:14.5px;font-weight:500;color:#111827;margin:0;">Title:
                                            <?= htmlspecialchars($ann['title']) ?>
                                        </p>
                                        <p style="font-size:14.5px;color:#212529;margin:0;">
                                            <?= nl2br(htmlspecialchars($ann['message'])) ?>
                                        </p>
                                        <div class="view" style="margin-top:8px;">
                                            <small style="color:#9ca3af;">
                                                <?= date('F j, Y', strtotime($ann['created_at'])) ?>

                                            </small>
                                        </div>

                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted m-0" style="font-size:14.5px;">No announcements yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="perform enrolled-subjects">
                    <div class="header">
                        <i class="fa fa-book"></i>
                        <h3>My Modules</h3>
                    </div>

                    <div class="body">
                        <!-- <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div>

                        <div class="progress-box">
                            <h4>Introduction to Philosophy of Human Person</h4>

                            <div class="parent-progress">
                                <div class="progress-header">
                                    <p>Progress</p>
                                    <span>75%</span>
                                </div>
                                <div class="parent-progress-percent">
                                    <div class="progress"></div>
                                </div>
                            </div>

                            <div class="view">
                                <a href="">View Progress <i class="fa fa-arrow-right"></i></a>
                            </div>
                        </div> -->
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
            const speed = 28; // ms per character — lower = faster
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

        // ── Mini Calendar ──
        const today = new Date();
        let calYear = today.getFullYear();
        let calMonth = today.getMonth(); // 0-indexed

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const dayNames = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];

        // events on certain dates (day numbers)
        const events = [20, 22, 28];

        function renderCalendar() {
            const label = document.getElementById('calMonthLabel');
            const grid = document.getElementById('calGrid');

            label.textContent = `${monthNames[calMonth]} ${calYear}`;
            grid.innerHTML = '';

            // Day name headers
            dayNames.forEach(d => {
                const el = document.createElement('div');
                el.className = 'cal-day-name';
                el.textContent = d;
                grid.appendChild(el);
            });

            // First day of month (Mon=0)
            const firstDay = new Date(calYear, calMonth, 1).getDay();
            const startOffset = (firstDay === 0) ? 6 : firstDay - 1;

            // Days in previous month
            const prevMonthDays = new Date(calYear, calMonth, 0).getDate();
            for (let i = startOffset - 1; i >= 0; i--) {
                const el = document.createElement('div');
                el.className = 'cal-day other-month';
                el.textContent = prevMonthDays - i;
                grid.appendChild(el);
            }

            // Days in current month
            const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
            for (let d = 1; d <= daysInMonth; d++) {
                const el = document.createElement('div');
                el.className = 'cal-day';

                const isToday = (calYear === today.getFullYear() && calMonth === today.getMonth() && d === today.getDate());
                if (isToday) el.classList.add('today');
                if (events.includes(d) && !isToday) el.classList.add('has-event');

                el.textContent = d;
                grid.appendChild(el);
            }

            // Fill remaining
            const totalCells = grid.querySelectorAll('.cal-day, .cal-day.other-month').length;
            const remaining = 42 - totalCells - startOffset;
            for (let d = 1; d <= remaining && d <= 14; d++) {
                const el = document.createElement('div');
                el.className = 'cal-day other-month';
                el.textContent = d;
                grid.appendChild(el);
            }
        }

        document.getElementById('calPrev').addEventListener('click', () => {
            calMonth--;
            if (calMonth < 0) { calMonth = 11; calYear--; }
            renderCalendar();
        });
        document.getElementById('calNext').addEventListener('click', () => {
            calMonth++;
            if (calMonth > 11) { calMonth = 0; calYear++; }
            renderCalendar();
        });

        renderCalendar();
    </script>

</body>

</html>