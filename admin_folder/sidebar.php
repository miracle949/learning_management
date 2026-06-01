<?php
$current_url = isset($_GET['url']) ? $_GET['url'] : 'admin';
?>
<style>
    @font-face {
        font-family: 'Poppins';
        src: url('../Poppins/Poppins-Regular.ttf') format('truetype');
        font-weight: 400;
    }

    @font-face {
        font-family: 'Titan';
        src: url('../Titan_One/TitanOne-Regular.ttf') format('truetype');
        font-weight: 400;
        font-style: normal;
    }

    * {
        font-family: 'Poppins', sans-serif;
    }

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

    .container-fluid .rightbar {
        width: calc(100% - 235px);
        /* height: 100vh; */
        margin-top: 68px;
        /* overflow-y: auto; */
        margin-left: 235px;
        border-left: 1px solid rgba(0, 0, 0, 0.1);
        background-color: #F7F9F8;
    }

    .container-fluid {
        height: 100vh;
        /* overflow-y: auto; */
    }

    .container-fluid .sidebar-logo {
        /* height: 130px; */
        height: 100px;
        /* background-color: #5BCA3F; */
        display: flex;
        justify-content: center;
        align-items: center;
        /* flex-direction: column; */
        gap: 1rem;
        /* margin-top: 1.8rem; */
        /* padding-bottom: 1rem; */
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* 
.container-fluid .sidebar-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.8rem;
} */

    .container-fluid .sidebar-logo p {
        /* font-size: 17px; */
        font-size: 22px;
        /* font-weight: bold; */
        margin: 0;
        font-family: "Titan", sans-serif;
        color: var(--green);
    }

    .container-fluid .sidebar-logo p b {
        color: #212529;
        font-family: "Titan", sans-serif;
    }

    .container-fluid .sidebar-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        gap: 0.5rem;
    }

    .container-fluid .sidebar-logo .logo-icon {
        /* padding: 8px; */
        padding: 11px;
        background-color: var(--green-light);
        border-radius: 10px;
    }

    .container-fluid .sidebar-logo .logo-icon .fa-solid {
        color: var(--green);
        /* font-size: 18px; */
        font-size: 20px;
    }


    .container-fluid .sidebar .sidebar-menu {
        margin-top: 1rem;
    }
</style>
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
                <h5>Main</h5>
            </div>
            <li class="<?= ($current_url ?? '') === 'admin' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=admin">
                    <i class="fa fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <div class="sidebar-category">
                <h5>Content</h5>
            </div>
            <li class="<?= ($current_url ?? '') === 'classes_teacher' ? 'active' : '' ?>">
                <a href="#">
                    <i class="fa fa-book-open"></i>
                    <span>Modules</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>Assignments</span>
                </a>
            </li>
            <div class="sidebar-category">
                <h5>User Management</h5>
            </div>
            <li class="<?= ($current_url ?? '') === 'teacher_users' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=teacher_users">
                    <i class="fa fa-chalkboard-user"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li class="<?= ($current_url ?? '') === 'student_users' ? 'active' : '' ?>">
                <a href="/learning_management/public/?url=student_users">
                    <i class="fa fa-users"></i>
                    <span>Students</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-gear"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- <div class="sidebar-footer">
        <i class="fa fa-circle-question"></i>
        <p>Help & information</p>
    </div> -->
</div>