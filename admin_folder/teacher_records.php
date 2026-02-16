<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Records</title>
    <link rel="stylesheet" href="../css_folder/teacher_records.css">

    <!-- bootstrap link -->
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">
        <!-- Modal 1 -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Create New Teacher</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12 mt-3">
                                    <label>Name</label>
                                    <input type="text" name="" class="form-control mt-1" placeholder="Enter name">
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label>Email</label>
                                    <input type="text" name="" class="form-control mt-1" placeholder="Enter email">
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label>Password</label>
                                    <input type="password" name="" class="form-control mt-1" placeholder="Enter password">
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label>Assigned Subjects</label>
                                    <div class="accordion mt-1">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                    aria-expanded="false" aria-controls="collapseThree">
                                                    Grade 12
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Introduction to
                                                                    Philosophy of Human Person
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Understanding Culture Society and Politics
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Computer System Servicing
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Physical Education
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Inquiries, Investigation
                                                                    and Immersion
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Entrepreneurship
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Work Immersion
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                    aria-expanded="false" aria-controls="collapseFour">
                                                    Grade 11
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Media Information Literature
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Pagbasa at Pagsusuri
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Computer System Servicing
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Physical Education
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Reading and Writing
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                   Practical Research
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    Statistics and Probability
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3">
                                    <label>Assigned Sections</label>
                                    <div class="accordion mt-1">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                    aria-expanded="false" aria-controls="collapseFive">
                                                    Grade 12
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">

                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    CSS 12-1
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    CSS 12-2
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                                    aria-expanded="false" aria-controls="collapseSix">
                                                    Grade 11
                                                </button>
                                            </h2>
                                            <div id="collapseSix" class="accordion-collapse collapse"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    CSS 11-1
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 col-md-12 mt-3">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="checkDefault">
                                                                <label class="form-check-label" for="checkDefault">
                                                                    CSS 11-2
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <nav>
            <a href="/learning_management/public/?url=admin"><i class="fa fa-arrow-left"></i></a>

            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                    class="fa fa-plus-circle"></i>
                Add Teachers</button>
        </nav>

        <main>
            <div class="search-parent">
                <div class="search-full-parent">
                    <div class="search-form">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="search" name="" class="form-control" placeholder="Search by name or email...">
                        </div>
                    </div>

                    <!-- <div class="search-level">
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
                    </div> -->
                </div>

                <div class="filter">
                    <i class="fa fa-filter"></i>
                    <span>Showing 4 of 4 students</span>
                </div>
            </div>

            <div class="table-parent">
                <h3>All Teachers</h3>

                <hr>

                <div class="teachers-parents-data">

                    <div class="teachers-data">
                        <div class="teachers-text">
                            <p>Dr. Robert Martines</p>
                            <p>robert.martines@gmail.com</p>

                            <div class="subject">
                                <span>Information Technology</span>

                                <span>2 classes</span>
                            </div>

                            <div class="assign-section">
                                <span>Assigned Sections:</span>

                                <div class="sections">
                                    <span>Grade 12 - Section A</span>

                                    <span>Grade 11 - Section B</span>
                                </div>
                            </div>
                        </div>
                        <div class="teachers-button">
                            <button>
                                <i class="fa fa-edit"></i>
                                <span>Edit Details</span>
                            </button>
                        </div>
                    </div>

                    <div class="teachers-data">
                        <div class="teachers-text">
                            <p>Dr. Robert Martines</p>
                            <p>robert.martines@gmail.com</p>

                            <div class="subject">
                                <span>Information Technology</span>

                                <span>2 classes</span>
                            </div>

                            <div class="assign-section">
                                <span>Assigned Sections:</span>

                                <div class="sections">
                                    <span>Grade 12 - Section A</span>

                                    <span>Grade 11 - Section B</span>
                                </div>
                            </div>
                        </div>
                        <div class="teachers-button">
                            <button>
                                <i class="fa fa-edit"></i>
                                <span>Edit Details</span>
                            </button>
                        </div>
                    </div>

                    <div class="teachers-data">
                        <div class="teachers-text">
                            <p>Dr. Robert Martines</p>
                            <p>robert.martines@gmail.com</p>

                            <div class="subject">
                                <span>Information Technology</span>

                                <span>2 classes</span>
                            </div>

                            <div class="assign-section">
                                <span>Assigned Sections:</span>

                                <div class="sections">
                                    <span>Grade 12 - Section A</span>

                                    <span>Grade 11 - Section B</span>
                                </div>
                            </div>
                        </div>
                        <div class="teachers-button">
                            <button>
                                <i class="fa fa-edit"></i>
                                <span>Edit Details</span>
                            </button>
                        </div>
                    </div>

                    <div class="teachers-data">
                        <div class="teachers-text">
                            <p>Dr. Robert Martines</p>
                            <p>robert.martines@gmail.com</p>

                            <div class="subject">
                                <span>Information Technology</span>

                                <span>2 classes</span>
                            </div>

                            <div class="assign-section">
                                <span>Assigned Sections:</span>

                                <div class="sections">
                                    <span>Grade 12 - Section A</span>

                                    <span>Grade 11 - Section B</span>
                                </div>
                            </div>
                        </div>
                        <div class="teachers-button">
                            <button>
                                <i class="fa fa-edit"></i>
                                <span>Edit Details</span>
                            </button>
                        </div>
                    </div>

                    <div class="teachers-data">
                        <div class="teachers-text">
                            <p>Dr. Robert Martines</p>
                            <p>robert.martines@gmail.com</p>

                            <div class="subject">
                                <span>Information Technology</span>

                                <span>2 classes</span>
                            </div>

                            <div class="assign-section">
                                <span>Assigned Sections:</span>

                                <div class="sections">
                                    <span>Grade 12 - Section A</span>

                                    <span>Grade 11 - Section B</span>
                                </div>
                            </div>
                        </div>
                        <div class="teachers-button">
                            <button>
                                <i class="fa fa-edit"></i>
                                <span>Edit Details</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>