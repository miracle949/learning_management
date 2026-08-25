<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'teacher';
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
            <li class="<?= $current_url === 'teacher' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=teacher" class="text-decoration-none">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <div class="sidebar-category">
                <h5>Content</h5>
            </div>
            <li class="<?= $current_url === 'classes_teacher' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=classes_teacher" class="text-decoration-none">
                    <i class="fa fa-book-open"></i>
                    <span>Classes</span>
                </a>
            </li>
            <li class="<?= $current_url === 'modules_teacher' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=modules_teacher" class="text-decoration-none">
                    <i class="fa fa-book"></i>
                    <span>Modules</span>
                </a>
            </li>
            <li class="<?= $current_url === 'assignments' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-pen"></i>
                    <span>Assignments</span>
                </a>
            </li>

            <div class="sidebar-category">
                <h5>Evaluation</h5>
            </div>
            <li class="<?= $current_url === 'reports' ? 'active' : '' ?>">
                <a href="#" class="text-decoration-none">
                    <i class="fa fa-chart-line"></i>
                    <span>Reports</span>
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
                <span>Teacher</span>
            </div>
        </div>
    </div>
</div>