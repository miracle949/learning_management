<nav class="main-nav">
    <div class="nav-title">
        <button class="menu-bar" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
            aria-controls="staticBackdrop">
            <div class="fa fa-bars"></div>
        </button>

        <!-- <h3 class="m-0">Learning Management</h3> -->
        <!-- <img src="../images/ilearn-logo3.jpg" alt=""> -->
        <!-- <div class="image-logo">
            <img src="../images/ilearn-logo4.png" alt="">
            <div class="logo-text">
                <p><b>i</b>Learn</p>
            </div>
        </div> -->

        <div class="search-parent">
            <i class="fa fa-search"></i>
            <div class="search">
                <input type="search" name="" id="" placeholder="Search...">
            </div>
        </div>
    </div>

    <!-- <div class="nav-list">
        <ul>
            <li>
                <a href="/learning_management/public/?url=dashboard">Home</a>
            </li>

            <li>
                <a href="/learning_management/public/?url=subjects_all">Courses</a>
            </li>

            <li>
                <a href="#">Progress</a>
            </li>
        </ul>
    </div> -->

    <div class="nav-acc">
        <div class="nav-list">
            <button><i class="fa fa-message"></i></button>
            <button><i class="fa fa-bell"></i></button>
        </div>
        <!-- <button><i class="fas fa-grip"></i></button> -->
        <div class="drop-name">
            <p>Jaira V. Bono</p>
            <span>Student</span>
        </div>
        <div class="dropdown">

            <a href="#" class="dropdown-parent" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                <button>
                    <?php
                    $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                    echo $initial;
                    ?>
                </button>
            </a>

            <ul class="dropdown-menu">
                <div class="d-flex justify-content-left align-items-center dropdown-profile gap-2">
                    <button>
                        <?php
                        $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                        echo $initial;
                        ?>
                    </button>
                    <li style="line-height: 25px;">
                        <span class="fw-semibold"><?= $_SESSION['email'] ?></span>
                        <span><?= $_SESSION['section'] ?></span>
                    </li>
                </div>

                <hr>

                <li>

                    <a href="#">
                        <div class="icon-parent">
                            <i class="fa fa-user"></i>
                        </div>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="icon-parent">
                            <i class="fa fa-lock"></i>
                        </div>
                        <span>Reset Password</span>
                    </a>
                </li>
                <form action="?url=logout" method="post">
                    <li>
                        <a href="#">
                            <div class="icon-parent">
                                <i class="fa fa-sign-out"></i>
                            </div>
                            <button type="submit">Logout</button>
                        </a>

                    </li>
                </form>
            </ul>

        </div>
    </div>
</nav>