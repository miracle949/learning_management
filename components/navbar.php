<nav class="main-nav">
    <div class="nav-title">
        <button class="menu-bar" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
            aria-controls="staticBackdrop">
            <div class="fa fa-bars"></div>
        </button>

        <h3 class="m-0">Learning Management</h3>
    </div>

    <div class="nav-acc">
        <button><i class="fas fa-grip"></i></button>
        <div class="dropdown">

            <a href="#" class="dropdown-parent" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                <button>
                    <?php
                    $initial = isset($_SESSION['firstname']) ? strtoupper(substr($_SESSION['firstname'], 0, 1)) : '';
                    echo $initial;
                    ?>
                </button>
            </a>

            <ul class="dropdown-menu">
                <div class="d-flex justify-content-left align-items-center dropdown-profile gap-2">
                    <button>
                        <?php
                        $initial = isset($_SESSION['firstname']) ? strtoupper(substr($_SESSION['firstname'], 0, 1)) : '';
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