<style>
    :root {
        --green: #4CAF7D;
        --green-dark: #2D6A4F;
        --green-light: #E8F5EE;
        --green-mid: #c8e6d6;
        --orange: #FF8A65;
        --orange-light: #FFF3EF;
        --orange-dark: #bf5b3a;
        --bg: #F9FBF9;
        --white: #ffffff;
        --card-border: rgba(0, 0, 0, 0.07);
        --text: #2D6A4F;
        --text-dark: #1a3a2a;
        --text-muted: #7a9a8a;
        --sidebar-bg: #ffffff;
        --shadow: 0 2px 12px rgba(76, 175, 125, 0.08);
        --background-icon: #2d3748;
        --green-text: #4a6a58;
    }

    .container-fluid nav {
        width: calc(100% - 235px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 2rem 0.8rem 2rem;
        /* margin-left: 235px; */
        /* background-color: #ffffff; */
        /* background-color: #C0D2FF; */
        /* background-color: #ffffff; */
        /* background-color: #5BCA3F; */
        background-color: #ffffff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        border-left: 1px solid rgba(0, 0, 0, 0.1);
        /* border-bottom: 1px solid rgba(0, 0, 0, 0.2); */
        /* box-shadow: 0 4px 16px rgba(108, 63, 232, 0.10), 0 1px 4px rgba(0, 0, 0, 0.06); */
        position: fixed;
        top: 0;
        z-index: 100;
    }

    .container-fluid nav button {
        border: none;
        outline: none;
    }

    .container-fluid nav .nav-title {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.7rem;
    }

    .container-fluid nav .search-parent {
        /* background-color: #ffffff; */
        background-color: #F7F9F8;
        padding: 0.4rem 0.8rem 0.4rem 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 380px;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .container-fluid nav .search-parent .fa {
        color: #99A1AF;
    }

    .container-fluid nav .search-parent .search input {
        width: 330px;
        border: none;
        outline: none;
        font-size: 14.5px;
        background-color: #F5F8FF;
    }

    .container-fluid nav .nav-title h3 {
        font-size: 20px;
        color: #484B4A;
    }

    .container-fluid nav .nav-title button {
        background: none;
        border: none;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container-fluid nav .nav-title button .fa {
        font-size: 20px;
    }

    .container-fluid nav .nav-title .menu-bar {
        display: none;
    }

    .container-fluid nav .nav-acc .nav-list button {
        font-size: 16px;
    }

    .container-fluid nav .nav-acc .nav-list button .notification-icon {
        padding: 8px 13px;
        background-color: var(--green-light);
        border-radius: 10px;
    }

    .container-fluid nav .nav-acc .nav-list button .notification-icon .fa {
        color: var(--green-dark);
    }

    .container-fluid nav .nav-acc {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
    }

    .container-fluid nav .nav-acc .fa-plus,
    .fa-grip {
        font-size: 20px;
    }

    .container-fluid nav .nav-acc .nav-list {
        display: flex;
        gap: 0.9rem;
    }

    .container-fluid nav .nav-acc .nav-list button {
        border: none;
        background: none;
        color: #364153;
    }

    .container-fluid nav .nav-acc .drop-name p {
        margin: 0;
        /* font-weight: 600; */
        font-size: 14px;
        font-weight: 600;
        /* color: #F9E8E3; */
    }

    .container-fluid nav .nav-acc .drop-name {
        display: flex;
        flex-direction: column;
        justify-content: end;
        align-items: end;
        border-left: 1px solid #364153;
        padding: 0 0 0 1rem;
        color: #364153;
    }

    .container-fluid nav .nav-acc .drop-name span {
        font-size: 13px;
        /* color: #FFE9E9; */
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-profile button {
        width: 55px;
        height: 45px;
        background-color: var(--green);
        color: #ffffff;
        border-radius: 50%;
        font-size: 17px;
        font-weight: bold;
        padding: 0;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-profile {
        padding: 0rem 0 0.3rem 0;
        /* width: 100%; */
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-profile li span:nth-child(2) {
        font-size: 14px;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu {
        /* padding: 1rem 3rem 1rem 3rem; */
        /* width: 300px; */
        padding: 1.5rem;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu hr {
        margin: 9px 0 9px 0;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu a.dropdown-item {
        padding: 0 0 0 10px;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu li a {
        color: black;
        display: flex;
        justify-content: left;
        align-items: center;
        gap: 0.8rem;
        font-size: 15.5px;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu li {
        line-height: 50px;
        /* display: flex;
    align-items: center;
    gap: 15px; */
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu li a .icon-parent {
        background-color: var(--green-light);
        /* padding: 10px; */
        color: var(--green);
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-menu li a button {
        all: unset;
    }

    .container-fluid nav .nav-acc .dropdown a {
        text-decoration: none;
    }

    .container-fluid nav .nav-acc .dropdown .dropdown-parent button {
        width: 40px;
        height: 40px;
        /* background-color: #8839FC; */
        /* background-color: #212529; */
        /* background: linear-gradient(to right, #4F5CFE, #7B2DFB); */
        color: #ffffff;
        background-color: var(--green);
        border-radius: 50%;
        font-size: 18px;
    }
</style>
<nav class="main-nav">
    <div class="nav-title">
        <button class="menu-bar" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
            aria-controls="staticBackdrop">
            <div class="fa fa-bars"></div>
        </button>

        <div class="search-parent">
            <i class="fa fa-search"></i>
            <div class="search">
                <input type="search" name="" id="" placeholder="Search...">
            </div>
        </div>
    </div>

    <div class="nav-acc">
        <div class="nav-list">
            <button>
                <div class="notification-icon">
                    <i class="fa fa-message"></i>
                </div>
            </button>
            <button>
                <div class="notification-icon">
                    <i class="fa fa-bell"></i>
                </div>
            </button>
        </div>
        <!-- <button><i class="fas fa-grip"></i></button> -->
        <div class="drop-name">
            <p><?= htmlspecialchars($_SESSION["name"]) ?></p>
            <span>Super Admin</span>
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
                    <button style="width: 45px; height: 45px;">
                        <?php
                        $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                        echo $initial;
                        ?>
                    </button>
                    <li style="line-height: 25px;">
                        <span class="fw-semibold"><?= $_SESSION['email'] ?></span>
                        <!-- <span><?= $_SESSION['section'] ?></span> -->
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