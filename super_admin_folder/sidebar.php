<style>
    .container-fluid .sidebar {
        width: 225px;
        height: 100%;
        background-color: #ffffff;
        position: fixed;
        left: 0;
        top: 0;
        border-right: 1px solid rgba(0, 0, 0, 0.1);
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .container-fluid .sidebar .sidebar-menu ul {
        padding: 0.3px 1rem 0px 1rem;
        /* margin-top: 1rem; */
    }

    .container-fluid .sidebar .sidebar-menu ul .sidebar-category h5 {
        margin: 10px 6px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        color: #808080;
    }

    .container-fluid .sidebar .sidebar-menu ul li {
        list-style: none;
        line-height: 50px;
        padding: 0 10px;
        border-radius: 10px;
        margin-top: 0.5rem;
    }

    .container-fluid .sidebar .sidebar-menu ul li:hover {
        background-color: #E7E8EB;
        border-radius: 10px;
    }

    .container-fluid .sidebar .sidebar-menu ul li a {
        display: flex;
        justify-content: left;
        align-items: center;
        gap: 1rem;
        color: var(--green-text);
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
    }

    .container-fluid .sidebar .sidebar-menu ul li a .fa {
        font-size: 18px;
    }

    .container-fluid .sidebar .sidebar-menu ul li.active {
        background-color: var(--green);
        color: #ffffff;
    }

    .container-fluid .sidebar .sidebar-menu ul li.active a {
        color: #ffffff;
    }

    .container-fluid .sidebar .sidebar-footer {
        /* padding: 1rem; */
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        /* height: 300px; */
        /* height: 100%; */
        /* height: 200px; */
        display: flex;
        justify-content: left;
        align-items: center;
        gap: 1rem;
        font-size: 15.5px;
        font-weight: 600;
        color: var(--green-text);
        /* padding: 0px 26px 0px 26px; */
        padding: 26px;
    }


    .container-fluid .sidebar .sidebar-footer .fa {
        font-size: 18px;
    }

    .container-fluid .sidebar .sidebar-footer p {
        margin: 0;
        /* font-size: 13.5px; */
    }
</style>

<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'super_admin';
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
            <li class="<?= ($current_url ?? '') === 'super_admin' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=super_admin">
                    <i class="fa fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <div class="sidebar-category">
                <h5>Content</h5>
            </div>
            <li class="<?= ($current_url ?? '') === 'classes_teacher' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Modules</span>
                </a>
            </li>
            <li class="<?= ($current_url ?? '') === 'activities' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=activities">
                    <i class="fa fa-pencil"></i>
                    <span>Activities</span>
                </a>
            </li>
            <li class="<?= ($current_url ?? '') === 'modules' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-clipboard-list"></i>
                    <span>Assignments</span>
                </a>
            </li>
            <div class="sidebar-category">
                <h5>User Management</h5>
            </div>
            <!-- <li>
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