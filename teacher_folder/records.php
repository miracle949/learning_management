<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records</title>
    <link rel="stylesheet" href="../css_folder/records.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <nav>
            <div class="nav-logo">
                <a href="/learning_management/public/?url=teacher"><i class="fa fa-arrow-left"></i></a>
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

            <h3>
                <?= htmlspecialchars($classInfo['subject_name'] ?? 'Unknown Subject') ?>
            </h3>
            <div class="main-text">
                <p>
                    Computer System Servicing
                </p>
                <span>•</span>
                <span>
                    <?= htmlspecialchars($classInfo['grade'] ?? '') ?>
                </span>
                <span>-</span>
                <span>
                    <?= htmlspecialchars($classInfo['section'] ?? '') ?>
                </span>
            </div>

            <div class="card-box-parent">
                <div class="card-box">
                    <div class="card-text">
                        <span>Students</span>
                        <p>28</p>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-users"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Modules</span>
                        <p>5</p>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                </div>

                <div class="card-box">
                    <div class="card-text">
                        <span>Lessons</span>
                        <p>10</p>
                    </div>
                    <div class="card-icon">
                        <i class="fa fa-file"></i>
                    </div>
                </div>
            </div>

            <!-- <div class="progress-announce">
                <a href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>">
                    <div class="student-progress">
                        <div class="student-icon">
                            <i class="fa fa-arrow-up-from-bracket"></i>
                        </div>
                        <div class="student-text">
                            <p>Upload</p>
                            <span>Add lessons, videos, images</span>
                        </div>
                    </div>
                </a>

                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <div class="announcement">
                        <div class="announce-icon">
                            <i class="fa fa-bullhorn"></i>
                        </div>
                        <div class="announce-text">
                            <p>Announcement</p>
                            <span>Post updates for students</span>
                        </div>
                    </div>
                </button>
            </div> -->

            <!-- Modal announcement -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="post">
                            <div class="modal-body">

                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <label>Title</label>
                                        <input type="text" name="" class="form-control mt-1"
                                            placeholder="Enter announcement title...">
                                    </div>

                                    <div class="col-lg-12 mt-2">
                                        <label>Content</label>
                                        <textarea name="" placeholder="Enter lessons content..."
                                            class="form-control mt-1" rows="6"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Post Announcement</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- <div class="search-parent">
                <div class="search-full-parent">
                    <div class="search-form">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="search" name="" class="form-control" placeholder="Search by name or email...">
                        </div>
                    </div>

                    <div class="search-level">
                        <select name="" class="form-select">
                            <option value="">Grade Level</option>
                            <option value="Grade 12">Grade 12</option>
                            <option value="Grade 11">Grade 11</option>
                        </select>
                    </div>

                    <div class="search-section">
                        <select name="" class="form-select">
                            <option value="">All Sections</option>
                            <option value="CSS 12-1">CSS 12-1</option>
                            <option value="CSS 12-2">CSS 12-2</option>
                            <option value="CSS 11-1">CSS 11-1</option>
                            <option value="CSS 11-2">CSS 11-2</option>
                        </select>
                    </div>
                </div>

                <div class="filter">
                    <i class="fa fa-filter"></i>
                    <span>Showing 4 of 4 students</span>
                </div>
            </div> -->

            <div class="parent-table">

                <div class="search-full-parent">
                    <div class="search-form">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="search" name="" class="form-control" placeholder="Search by name or email...">
                        </div>
                    </div>

                    <div class="search-level">
                        <select name="" class="form-select">
                            <option value="">Grade Level</option>
                            <option value="Grade 12">Grade 12</option>
                            <option value="Grade 11">Grade 11</option>
                        </select>
                    </div>

                    <div class="search-section">
                        <select name="" class="form-select">
                            <option value="">All Sections</option>
                            <option value="CSS 12-1">CSS 12-1</option>
                            <option value="CSS 12-2">CSS 12-2</option>
                            <option value="CSS 11-1">CSS 11-1</option>
                            <option value="CSS 11-2">CSS 11-2</option>
                        </select>
                    </div>
                </div>

                <div class="filter">
                    <i class="fa fa-filter"></i>
                    <span>Showing 4 of 4 students</span>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Grade / Section</th>
                            <th>Enrolled Classes</th>
                            <th>Overall Progress</th>
                            <th>Avg. Quiz Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <div class="actions">
                                    <i class="fa fa-eye"></i>
                                    <span>View Details</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2023-001241</td>
                            <td>Rogelio A. Amoyan</td>
                            <td>
                                <span>Grade 12</span>
                                <span>CSS-12-1</span>
                            </td>
                            <td>3</td>
                            <td>
                                <div class="parent-progress">
                                    <div class="progress-bar">
                                        <div class="progress">

                                        </div>
                                    </div>

                                    <span>40%</span>
                                </div>
                            </td>
                            <td>80%</td>
                            <td>
                                <i class="fa fa-eye"></i>
                                <span>View Details</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>


    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>