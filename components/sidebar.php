<?php
    $current_url = isset($_GET['url']) ? $_GET['url'] : 'dashboard';
?>
<div class="sidebar">
    <div class="sidebar-logo">
        <i class="fa fa-user-circle"></i>
        <p>Student Portal</p>
    </div>
    <div class="sidebar-menu">
        <ul>
            <li class="<?= $current_url === 'dashboard' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=dashboard" class="text-decoration-none">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="<?= ($current_url === 'classes' || $current_url === 'subjects') ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=classes" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>Classes</span>
                </a>
            </li>

            <li class="<?= $current_url === 'module_all' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=module_all" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Modules</span>
                </a>
            </li>

            <li class="<?= $current_url === 'assignments' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Assignments</span>
                </a>
            </li>

            <li class="<?= $current_url === 'progress' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Progress</span>
                </a>
            </li>

            <li class="<?= $current_url === 'grades' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Grades</span>
                </a>
            </li>

            <li class="<?= $current_url === 'calendar' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Calendar</span>
                </a>
            </li>
        </ul>
    </div>
</div>