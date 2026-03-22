<style>
    .container-fluid .rightbar .module-title {
        width: 100%;
        color: white;
        border-radius: 10px;
        /* border: 1px solid #e2e8f0; */
        border: 1px solid #E2E8E5;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .container-fluid .rightbar .module-title .module-picture {
        background-image: url('../images/philosophy_picture.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        height: 180px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
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
        padding: 1.8rem 2rem 1.8rem 2rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-links {
        width: 55%;
    }

    .container-fluid .rightbar .module-title .module-body .module-links span {
        color: #1A1A1A;
        text-transform: uppercase;
        color: var(--green);
        font-weight: 600;
        font-size: 13.5px;
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

    .container-fluid .rightbar .module-parent-progress .module-progress .progress-bar .progress {
        position: absolute;
        padding: 0.7rem;
        left: 0;
        width: 60%;
        background-color: #00C950;
        border-radius: 28px;
    }

    /* ── Fix: <a> wrapping module-progress must not affect layout ── */
    .module-parent-progress a {
        text-decoration: none;
        color: inherit;
        display: block;
    }
</style>

<div class="module-title">
    <!-- <h1>Introduction to Philosophy of Human Person</h1>
    <p><?= $_SESSION['section'] ?></p> -->

    <div class="module-picture">

    </div>
    <div class="module-body">
        <div class="module-body-child">
            <div class="module-links">
                <!-- <span>Currently Enrolled</span> -->
                <h1>Introduction to Philosophy of Human Person</h1>

                <div class="module-buttons">
                    <a href="/learning_management/public/?url=classes">Browse Courses</a>
                    <a href="#">Learn More</a>
                </div>
            </div>
            <div class="module-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in </p>
            </div>
        </div>
    </div>
</div>

<div class="parent">
    <div class="module-parent-progress">

        <?php
        $urlMap = [
            'module'       => 'module_view',
            'assignment'   => 'assignment_view',
            'announcement' => 'announcement_view',
        ];
        $labelMap = [
            'module'       => 'New Material',
            'assignment'   => 'New Assignments',
            'announcement' => 'Announcements',
        ];

        if (!empty($feedItems)):
            foreach ($feedItems as $item):
                $pageUrl = "/learning_management/public/?url={$urlMap[$item['type']]}&subject={$subject}&id={$item['id']}";
                $label   = $labelMap[$item['type']];
                $date    = date('M j', strtotime($item['date']));
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

        <!-- <div class="module-progress" data-module-id="module1">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Philosophy</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module1">Start
                        Now</a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module2">
            <div class="module-progress-header"></div>
            <div class="module-progress-body">
                <p>Philosophy</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module2">Start
                        Now</a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module3">
            <div class="module-progress-header"></div>
            <div class="module-progress-body">
                <p>Philosophy</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module3">Start
                        Now</a>
                </div>
            </div>
        </div>


        <div class="module-progress" data-module-id="module4">
            <div class="module-progress-header"></div>
            <div class="module-progress-body">
                <p>Philosophy</p>
                <h3>Introduction to Philosophy of Human Person</h3>

                <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
                    philosophical concepts.</p>

                <div class="title-progress-bar">
                    <span class="lessonText">0 of 10 lessons</span>
                    <span class="lessonPercent">0%</span>
                </div>

                <div class="parent-progress-bar">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                </div>

                <div class="footer-bar">
                    <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module4">Start
                        Now</a>
                </div>
            </div>
        </div> -->

    </div>
</div>