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

            <li class="<?= $current_url === 'subjects_all' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=subjects_all" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>My Subjects</span>
                </a>
            </li>

            <li class="<?= $current_url === 'modules' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
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

            <li class="<?= $current_url === 'classes' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-gear"></i>
                    <span>Classes</span>
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