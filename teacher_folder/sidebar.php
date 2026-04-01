<div class="sidebar">
    <div class="sidebar-logo">
        <i class="fa fa-user-circle"></i>
        <p>Teacher Portal</p>
    </div>
    <div class="sidebar-menu">
        <ul>
            <li class="<?= ($current_url ?? '') === 'teacher' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=teacher">
                    <i class="fa fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="<?= ($current_url ?? '') === 'classes_teacher' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=classes_teacher">
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
                <a href="#"><i class="fa fa-home"></i><span>Calendar</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-home"></i><span>Settings</span></a>
            </li>
        </ul>
    </div>
</div>