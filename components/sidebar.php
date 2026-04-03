<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'dashboard';
?>
<div class="sidebar">
    <!-- <div class="sidebar-logo">
        <i class="fa fa-user-circle"></i>
        <p>Student Portal</p>
    </div> -->
    <div class="sidebar-logo">
        <div class="logo-icon">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
        <div class="logo-text">
            <p><b>i</b>Learn</p>
        </div>
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

            <li class="<?= ($current_url === 'classes' || $current_url === 'subjects') ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=classes" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>Classes</spanz>
                </a>
            </li>

            <li class="<?= $current_url === 'module_all' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=module_all" class="text-decoration-none">
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

            <li class="<?= $current_url === 'settings' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidebar-footer">
        <i class="fa fa-circle-question"></i>
        <p>Help & information</p>
    </div>
</div>