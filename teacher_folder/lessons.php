<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css_folder/teacher_lessons.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <nav>
            <div class="nav-logo">
                <a href="/learning_management/public/?url=records"><i class="fa fa-arrow-left"></i></a>
            </div>



            <!-- <form action="?url=logout" method="post">
                <button><i class="fa fa-sign-out"></i> Logout</button>
            </form> -->
            <div class="nav-menu">
                <button>
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </nav>

        <main>
            <div class="card-parent-box">
                <div class="card-header">
                    <h3>Content</h3>

                    <div class="buttons">
                        <button>
                            <i class="fa fa-file"></i>
                            Lessons
                        </button>

                        <button>
                            <i class="fa fa-video"></i>
                            <span>Video</span>
                        </button>

                        <button>
                            <div class="fa fa-image"></div>
                            <span>Image</span>
                        </button>

                        <button>
                            <i class="fa fa-circle-question"></i>
                            <span>Activity</span>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-body-header">
                        <div class="card-icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <p>Lesson 1</p>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-4">
                            <label>Title *</label>
                            <input type="text" name="" class="form-control mt-1" placeholder="Enter lessons title">
                        </div>

                        <div class="col-lg-12 mt-4">
                            <label>Content *</label>
                            <textarea name="" placeholder="Enter lessons content" class="form-control mt-1" rows="6"></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <h3>Additional Materials (Optional)</h3>

                    <div class="card-materials">
                        <i class="fa fa-arrow-up-from-bracket"></i>
                        <p>Upload supplementary materials</p>
                        <span>PDF, Powerpoint, Word Document (max 50MB)</span>
                        <input type="file" name="" id="">
                    </div>
                </div>

                <div class="card-submit">
                    <button>Cancel</button>
                    <button>Save Lessons</button>
                </div>
            </div>
        </main>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>