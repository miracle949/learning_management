<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects</title>
    <link rel="stylesheet" href="../css_folder/all_subjects.css">
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

            <?php foreach ($subjects as $subject): ?>
                <div class="card-box-parent">
                    <div class="card-box">

                        <div class="card-box-picture"></div>

                        <div class="card-box-body">
                            <div class="card-body-text">
                                <p><?= htmlspecialchars($subject['subject_name']) ?></p>
                                <span>
                                    <?= !empty($subject['description'])
                                        ? htmlspecialchars($subject['description'])
                                        : 'Lorem ipsum dolor sit amet consectetur, adipisicing elit.' ?>
                                </span>
                            </div>

                            <div class="card-body-enrolled">
                                <?php if (in_array($subject['id'], $enrolledSubjectIds)): ?>
                                    <a
                                        href="/learning_management/public/?url=subjects&subject=<?= urlencode($subject['slug']) ?>">
                                        <span>Go to Subject</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>

                                <?php else: ?>

                                    <!-- Changed to button, stores redirect URL in data attribute -->
                                    <a href="#" class="enroll-btn"
                                        data-enroll-url="/learning_management/public/?url=subjects_all&enroll=1&subject_id=<?= $subject['id'] ?>&subject_slug=<?= urlencode($subject['slug']) ?>"
                                        data-redirect-url="/learning_management/public/?url=subjects&subject=<?= urlencode($subject['slug']) ?>"
                                        data-subject-name="<?= htmlspecialchars($subject['subject_name']) ?>">
                                        <span>Enroll Now</span>
                                        <i class="fa fa-arrow-right"></i>
                                    </a>

                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Enrollment Success Modal -->
    <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">

                    <!-- Success Icon -->
                    <div class="mb-3">
                        <div style="
                            width: 70px; height: 70px;
                            background: #d4edda;
                            border-radius: 50%;
                            display: flex; align-items: center; justify-content: center;
                            margin: 0 auto;
                        ">
                            <i class="fa fa-check" style="font-size: 2rem; color: #28a745;"></i>
                        </div>
                    </div>

                    <h5 class="modal-title fw-bold mb-1" id="enrollmentModalLabel">Enrollment Successful!</h5>
                    <p class="text-muted mb-0">You have successfully enrolled in <strong
                            id="modalSubjectName"></strong>.</p>
                    <p class="text-muted mt-1" style="font-size: 0.85rem;">Redirecting you to the subject...</p>

                    <!-- Progress bar -->
                    <div class="progress mt-3" style="height: 5px;">
                        <div id="redirectProgress" class="progress-bar bg-success" role="progressbar"
                            style="width: 0%;"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const enrollBtns = document.querySelectorAll('.enroll-btn');

            enrollBtns.forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();

                    const enrollUrl = this.dataset.enrollUrl;
                    const redirectUrl = this.dataset.redirectUrl;
                    const subjectName = this.dataset.subjectName;

                    // Step 1: Hit the enroll URL silently
                    fetch(enrollUrl)
                        .then(() => {
                            // Step 2: Show modal with subject name
                            document.getElementById('modalSubjectName').textContent = subjectName;

                            const modal = new bootstrap.Modal(document.getElementById('enrollmentModal'), {
                                backdrop: 'static',
                                keyboard: false
                            });
                            modal.show();

                            // Step 3: Animate progress bar over 2.5s, then redirect
                            const progressBar = document.getElementById('redirectProgress');
                            const duration = 2500;
                            const interval = 30;
                            const steps = duration / interval;
                            let current = 0;

                            const timer = setInterval(() => {
                                current++;
                                progressBar.style.width = ((current / steps) * 100) + '%';

                                if (current >= steps) {
                                    clearInterval(timer);
                                    window.location.href = redirectUrl;
                                }
                            }, interval);
                        })
                        .catch(() => {
                            // Fallback: just navigate directly if fetch fails
                            window.location.href = enrollUrl;
                        });
                });
            });
        });
    </script>

</body>

</html>