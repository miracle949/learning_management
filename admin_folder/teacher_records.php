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
                        <h1 class="modal-title" id="exampleModalLabel">Create New Teacher</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="?url=createTeacher" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-6 mt-1">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control mt-1" placeholder="Enter name" required>
                                </div>

                                <div class="col-lg-6 mt-1">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control mt-1" placeholder="Enter email" required>
                                </div>

                                <div class="col-lg-12 mt-4">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control mt-1"
                                        placeholder="Enter password" required>
                                </div>

                                <div class="line">
                                    <hr style="margin: 2rem 0; width: 766px;">
                                </div>

                                <div class="col-lg-12">
                                    <label>Assigned Subjects</label>

                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon">
                                                    <span>G12</span>
                                                </div>
                                                <p>Grade 12</p>
                                            </div>

                                            <div class="selected">
                                                <span class="selected-count">0 selected</span>
                                            </div>
                                        </div>

                                        <div class="body-subjects">
                                            <div class="card-parent">
                                                <?php foreach ($grade12Subjects as $subject): ?>
                                                    <div class="card-box">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="<?= $subject['id'] ?>"
                                                                id="subject_<?= $subject['id'] ?>"
                                                                name="assigned_subjects[]">
                                                            <label class="form-check-label"
                                                                for="subject_<?= $subject['id'] ?>">
                                                                <?= htmlspecialchars($subject['subject_name']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon">
                                                    <span>G11</span>
                                                </div>
                                                <p>Grade 11</p>
                                            </div>

                                            <div class="selected">
                                                <span class="selected-count">0 selected</span>
                                            </div>
                                        </div>

                                        <div class="body-subjects">
                                            <div class="card-parent">
                                                <?php foreach ($grade11Subjects as $subject): ?>
                                                    <div class="card-box">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="<?= $subject['id'] ?>"
                                                                id="subject_<?= $subject['id'] ?>"
                                                                name="assigned_subjects[]">
                                                            <label class="form-check-label"
                                                                for="subject_<?= $subject['id'] ?>">
                                                                <?= htmlspecialchars($subject['subject_name']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="line">
                                    <hr style="margin: 2rem 0; width: 766px;">
                                </div>

                                <div class="col-lg-12">
                                    <label>Assigned Sections</label>

                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon">
                                                    <span>G12</span>
                                                </div>
                                                <p><?= htmlspecialchars($grade12Sections[0]['grade_name'] ?? 'Grade 12') ?>
                                                </p>
                                            </div>

                                            <div class="selected">
                                                <span class="selected-count">0 selected</span>
                                            </div>
                                        </div>

                                        <div class="body-subjects">
                                            <div class="card-parent">
                                                <?php foreach ($grade12Sections as $section): ?>
                                                    <div class="card-box">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="<?= $section['id'] ?>"
                                                                id="section_<?= $section['id'] ?>"
                                                                name="assigned_sections[]">
                                                            <label class="form-check-label"
                                                                for="section_<?= $section['id'] ?>">
                                                                <?= htmlspecialchars($section['section_name']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="parent-subjects mt-3">
                                        <div class="header-subjects">
                                            <div class="subject-text">
                                                <div class="subject-icon">
                                                    <span>G11</span>
                                                </div>
                                                <p><?= htmlspecialchars($grade11Sections[0]['grade_name'] ?? 'Grade 11') ?>
                                                </p>
                                            </div>

                                            <div class="selected">
                                                <span class="selected-count">0 selected</span>
                                            </div>
                                        </div>

                                        <div class="body-subjects">
                                            <div class="card-parent">
                                                <?php foreach ($grade11Sections as $section): ?>
                                                    <div class="card-box">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="<?= $section['id'] ?>"
                                                                id="section_<?= $section['id'] ?>"
                                                                name="assigned_sections[]">
                                                            <label class="form-check-label"
                                                                for="section_<?= $section['id'] ?>">
                                                                <?= htmlspecialchars($section['section_name']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create teacher</button>
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
            </div>

            <div class="table-parent">
                <h3>All Teachers</h3>

                <hr>

                <div class="teachers-parents-data">

                    <?php if (empty($teachers)): ?>
                        <p style="color: #999; padding: 20px;">No teachers found.</p>

                    <?php else: ?>
                        <?php foreach ($teachers as $teacher): ?>
                            <div class="teachers-data">
                                <div class="teachers-text">

                                    <div class="teachers-name-parent">
                                        <div class="teachers-initial">
                                            <span>
                                                <?php
                                                // Change $_SESSION['name'] to $teacher['name']
                                                $initial = isset($teacher['name']) ? strtoupper(substr($teacher['name'], 0, 1)) : '';
                                                echo $initial;
                                                ?>
                                            </span>
                                        </div>
                                        <div class="teachers-name">
                                            <p>
                                                <?= htmlspecialchars($teacher['name']) ?>
                                            </p>
                                            <p>
                                                <?= htmlspecialchars($teacher['email']) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="teachers-button">
                                        <div class="class-count">
                                            <p>
                                                <?= $teacher['class_count'] ?>
                                                <?= $teacher['class_count'] != 1 ? 'classes' : 'classes' ?>
                                            </p>
                                        </div>
                                        <button data-id="<?= $teacher['teacher_id'] ?>">
                                            <i class="fa fa-edit"></i>
                                            <span>Edit Details</span>
                                        </button>
                                    </div>

                                </div>

                                <div class="teachers-body">
                                    <div class="assign-subject">
                                        <span class="title">Assigned Subjects:</span>

                                        <div class="subject">
                                            <div class="parent-sub">
                                                <?php foreach ($teacher['subjects'] as $subject): ?>
                                                    <span>
                                                        <?= htmlspecialchars($subject) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="assign-section">
                                        <span class="title">Assigned Sections:</span>
                                        <div class="sections">
                                            <?php if (!empty($teacher['sections'])): ?>
                                                <?php foreach ($teacher['sections'] as $section): ?>
                                                    <span>

                                                        <?= htmlspecialchars($section) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <span style="color:#999;">No sections assigned</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </main>
    </div>

    <!-- bootstrap link javascript -->
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Loop through every .parent-subjects group (subjects AND sections)
            document.querySelectorAll('.parent-subjects').forEach(function (group) {

                const checkboxes = group.querySelectorAll('input[type="checkbox"]');
                const countSpan = group.querySelector('.selected-count');

                // Update the "X selected" text whenever a checkbox changes
                function updateCount() {
                    const checked = group.querySelectorAll('input[type="checkbox"]:checked').length;
                    const total = checkboxes.length;

                    if (countSpan) {
                        countSpan.textContent = checked + ' selected';
                    }
                }

                // Attach change listener to every checkbox inside this group
                checkboxes.forEach(function (cb) {
                    cb.addEventListener('change', updateCount);
                });

                // Run once on load so the count is correct from the start
                updateCount();
            });

        });
    </script>

</body>

</html>