<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'dashboard';
?>
<div class="sidebar">
    <!-- <div class="sidebar-logo">
        <i class="fa fa-user-circle"></i>
        <p>Student Portal</p>
    </div> -->
    <div class="sidebar-logo">
        <!-- <div class="logo-icon">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
        <div class="logo-text">
            <p><b>i</b>Learn</p>
        </div> -->
        <img src="../images/iLearn-7.png" alt="">
    </div>
    <div class="sidebar-menu">
        <ul>
            <div class="sidebar-category">
                <h5>Menu</h5>
            </div>

            <li class="<?= $current_url === 'dashboard' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=dashboard" class="text-decoration-none">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- <li class="<?= ($current_url === 'classes' || $current_url === 'subjects') ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=classes" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>My Subject</span>
                </a>
            </li> -->
            <!-- <li class="<?= ($current_url === 'classes' || $current_url === 'subjects') ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=classes" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>My Subject</span>
                </a>
            </li>
            <li class="<?= ($current_url === 'classes' || $current_url === 'subjects') ? 'active' : '' ?>"
                id="mySubjectItem">
                <a href="#" class="text-decoration-none" onclick="toggleSubjectMenu(event)">
                    <i class="fa fa-book-open"></i>
                    <span>My Subject</span>
                    <i class="fa fa-chevron-down ms-auto" id="subjectChevron"
                        style="font-size:11px; transition: transform 0.2s;"></i>
                </a>
                <ul class="subject-submenu" id="subjectSubmenu">
                    <li>
                        <a href="/learning_management/public/?url=subjects&subject=css"
                            class="text-decoration-none <?= ($current_url === 'subjects' && ($_GET['subject'] ?? '') === 'css') ? 'active-sub' : '' ?>">
                            <i class="fa fa-circle" style="font-size:6px;"></i>
                            <span>Computer System Servicing</span>
                        </a>
                    </li>
                </ul>
            </li> -->

            <li class="<?= ($current_url === 'classes' || $current_url === 'subjects') ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=subjects&subject=css" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>My Subject</span>
                </a>
            </li>

            <!-- <li class="<?= $current_url === 'module_all' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=module_all" class="text-decoration-none">
                    <i class="fa fa-book"></i>
                    <span>Modules</span>
                </a>
            </li> -->

            <li class="<?= $current_url === 'module_all' || $current_url === 'modules' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=modules&subject=css" class="text-decoration-none">
                    <i class="fa fa-book"></i>
                    <span>Modules</span>
                </a>
            </li>


            <div class="sidebar-category">
                <h5>Monitoring</h5>
            </div>

            <li class="<?= $current_url === 'assignments' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=assignments" class="text-decoration-none">
                    <i class="fa fa-calendar-check"></i>
                    <span>Assignments</span>
                </a>
            </li>

            <li class="<?= $current_url === 'progress' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=progress" class="text-decoration-none">
                    <i class="fa fa-chart-line"></i>
                    <span>Progress</span>
                </a>
            </li>

            <div class="sidebar-category">
                <h5>Account</h5>
            </div>

            <li class="<?= $current_url === 'my_profile' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-user"></i>
                    <span>My Profile</span>
                </a>
            </li>

            <li class="<?= $current_url === 'notifications' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-user"></i>
                    <span>Notifications</span>
                </a>
            </li>

            <li class="<?= $current_url === 'settings' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Settings</span>
                </a>
            </li>

            <form action="?url=logout" method="post">
                <li>
                    <a href="#">
                        <i class="fa fa-sign-out"></i>
                        <button type="submit">Logout</button>
                    </a>
                </li>
            </form>
        </ul>

        <div class="account">
            <div class="initial">
                <h5>
                    <?php
                    $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                    echo $initial;
                    ?>
                </h5>
            </div>
            <div class="first-last">
                <p>
                    <?= htmlspecialchars($_SESSION["name"]) ?>
                </p>
                <span>LRN: 123456789012</span>
            </div>
        </div>
    </div>
    <!-- <div class="sidebar-footer">
        <i class="fa fa-circle-question"></i>
        <p>Help & information</p>
    </div> -->
</div>