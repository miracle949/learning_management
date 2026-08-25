<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'super_admin';
?>
<div class="sidebar">
    <div class="sidebar-logo">
        <img src="../images/logo1.png" alt="">
        <h3>SHS Strand</h3>
    </div>
    <div class="sidebar-menu">
        <ul>
            <div class="sidebar-category">
                <h5>Main</h5>
            </div>
            <li class="<?= $current_url === 'super_admin' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=super_admin" class="text-decoration-none">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <div class="sidebar-category">
                <h5>Content</h5>
            </div>
            <li class="<?= $current_url === 'classes_teacher' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-book"></i>
                    <span>Modules</span>
                </a>
            </li>
            <li class="<?= $current_url === 'activities' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=activities" class="text-decoration-none">
                    <i class="fa fa-pencil"></i>
                    <span>Activities</span>
                </a>
            </li>
            <li class="<?= $current_url === 'modules' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-clipboard-list"></i>
                    <span>Assignments</span>
                </a>
            </li>

            <div class="sidebar-category">
                <h5>User Management</h5>
            </div>
            <li class="<?= $current_url === 'super_admin_teacher_users' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=super_admin_teacher_users" class="text-decoration-none">
                    <i class="fa fa-chalkboard-user"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li class="<?= $current_url === 'super_admin_student_users' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=super_admin_student_users" class="text-decoration-none">
                    <i class="fa fa-users"></i>
                    <span>Students</span>
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
                <p><?= htmlspecialchars($_SESSION["name"]) ?></p>
                <span>Super Admin</span>
            </div>
        </div>
    </div>
</div>