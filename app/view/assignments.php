<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/assignments.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="parent-card">
                <div class="box-card">
                    <div class="box-text">
                        <p>Pending</p>

                        <h4>3</h4>
                    </div>
                    <div class="box-icon">
                        <i class="fa fa-clock"></i>
                    </div>
                </div>

                <div class="box-card">
                    <div class="box-text">
                        <p>Completed</p>

                        <h4>2</h4>
                    </div>
                    <div class="box-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>

                <div class="box-card">
                    <div class="box-text">
                        <p>Graded</p>

                        <h4>1</h4>
                    </div>
                    <div class="box-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>

            <div class="parent-pending">
                <div class="pendings">
                    <h3>Pending Submissions</h3>

                    <div class="pending-parent-card">
                        <div class="pending-card">
                            <p>Hlelo</p>
                        </div>
                    </div>
                </div>

                <div class="complete-grade">
                    <div class="complete-parent">
                        <h3>Completed Task</h3>

                        <div class="complete-box">
                            <h4>qweqwe</h4>
                        </div>
                    </div>

                    <div class="graded">
                        <h3>Graded Task</h3>

                        <div class="graded-box">
                            <h4>qweqwe</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>