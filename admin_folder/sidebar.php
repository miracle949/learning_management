<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'admin';
?>
<div class="sidebar">
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
            <li class="<?= ($current_url ?? '') === 'admin' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=admin">
                    <i class="fa fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <!-- <li class="<?= ($current_url ?? '') === 'classes_teacher' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-book-open"></i>
                    <span>Classes</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Modules</span>
                </a>
            </li>
            <div class="sidebar-category">
                <h5>Monitoring</h5>
            </div>
            <li>
                <a href="#">
                    <i class="fa fa-calendar-check"></i>
                    <span>Assignments</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-chart-line"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li>
                <a href="#"><i class="fa fa-home"></i><span>Settings</span></a>
            </li> -->
        </ul>
    </div>
    <!-- <div class="sidebar-footer">
        <i class="fa fa-circle-question"></i>
        <p>Help & information</p>
    </div> -->
</div>