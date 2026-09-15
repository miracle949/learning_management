<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Profile</title>
    <link rel="stylesheet" href="../css_folder/school_profile.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <main class="main">

                <div class="main-header">
                    <div class="main-text-header">
                        <h2>School Profile</h2>
                        <p>The school's official identity, shown on reports and the landing page</p>
                    </div>
                </div>

                <div class="main-body">

                    <form method="POST" action="/learning_management/public/?url=save_school_profile"
                        id="school-profile-form">

                        <div class="sp-save-bar">
                            <button type="submit" class="sp-save-btn">
                                <i class="fa fa-save me-1"></i> Save Changes
                            </button>
                        </div>

                        <div class="sp-card sp-tabbed-card">

                            <div class="sp-tabs" role="tablist">
                                <button type="button" class="sp-tab active" data-tab="info">
                                    <i class="fa fa-school"></i> School Information
                                </button>
                                <button type="button" class="sp-tab" data-tab="academic">
                                    <i class="fa fa-graduation-cap"></i> Academic Setup
                                </button>
                                <button type="button" class="sp-tab" data-tab="videos">
                                    <i class="fa fa-circle-play"></i> Strands in Action
                                </button>
                            </div>

                            <!-- SCHOOL INFORMATION -->
                            <div class="sp-tab-panel" data-panel="info">
                                <div class="sp-panel-header">
                                    <h3>School Information</h3>
                                    <p>Basic identity details used across reports and the public site</p>
                                </div>
                                <div class="sp-grid-2">
                                    <div class="sp-field">
                                        <label>School Name</label>
                                        <input type="text" name="school_name" class="form-control"
                                            value="<?= htmlspecialchars($profile['school_name'] ?? '') ?>" required>
                                    </div>
                                    <div class="sp-field">
                                        <label>DepEd School ID</label>
                                        <input type="text" name="deped_school_id" class="form-control"
                                            placeholder="e.g. 305432"
                                            value="<?= htmlspecialchars($profile['deped_school_id'] ?? '') ?>">
                                    </div>
                                    <div class="sp-field">
                                        <label>Region / Division</label>
                                        <input type="text" name="region_division" class="form-control"
                                            placeholder="e.g. Region IV-A · Division of Dasmariñas / Cavite"
                                            value="<?= htmlspecialchars($profile['region_division'] ?? '') ?>">
                                    </div>
                                    <div class="sp-field">
                                        <label>Principal / School Head</label>
                                        <input type="text" name="principal_name" class="form-control"
                                            placeholder="Full name"
                                            value="<?= htmlspecialchars($profile['principal_name'] ?? '') ?>">
                                    </div>
                                    <div class="sp-field">
                                        <label>Address</label>
                                        <input type="text" name="address" class="form-control"
                                            value="<?= htmlspecialchars($profile['address'] ?? '') ?>">
                                    </div>
                                    <div class="sp-field">
                                        <label>Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control"
                                            placeholder="e.g. 0917 000 0000"
                                            value="<?= htmlspecialchars($profile['contact_number'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- ACADEMIC SETUP -->
                            <div class="sp-tab-panel d-none" data-panel="academic">
                                <div class="sp-panel-header">
                                    <h3>Academic Setup</h3>
                                    <p>School year, grade levels, and which strands are currently offered</p>
                                </div>

                                <div class="sp-grid-2" style="margin-bottom: 1.75rem;">
                                    <div class="sp-field">
                                        <label>Current School Year</label>
                                        <input type="text" name="current_school_year" class="form-control"
                                            placeholder="e.g. 2026-2027"
                                            value="<?= htmlspecialchars($profile['current_school_year'] ?? '') ?>">
                                    </div>
                                    <div class="sp-field">
                                        <label>Grade Levels Offered</label>
                                        <input type="text" name="grade_levels_offered" class="form-control"
                                            placeholder="e.g. Grade 11, Grade 12"
                                            value="<?= htmlspecialchars($profile['grade_levels_offered'] ?? '') ?>">
                                    </div>
                                </div>

                                <label class="sp-subsection-label">Strands Offered</label>
                                <div class="sp-card-grid">
                                    <?php if (empty($strandSettings)): ?>
                                        <p class="text-muted mb-0">No strands configured yet.</p>
                                    <?php else: ?>
                                        <?php foreach ($strandSettings as $strand): ?>
                                            <?php
                                            $track = trim($strand['track'] ?? '');
                                            $trackClass = stripos($track, 'tvl') !== false ? 'sp-badge-tvl' : 'sp-badge-academic';
                                            ?>
                                            <div class="sp-strand-card">
                                                <div class="sp-strand-card-top">
                                                    <div>
                                                        <div class="sp-strand-name">
                                                            <?= htmlspecialchars($strand['strand_code'] . ' — ' . $strand['strand_name']) ?>
                                                        </div>
                                                        <?php if ($track !== ''): ?>
                                                            <span
                                                                class="sp-badge <?= $trackClass ?>"><?= htmlspecialchars($track) ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <label class="sp-toggle">
                                                        <input type="checkbox" name="strands_offered[]"
                                                            value="<?= (int) $strand['id'] ?>" <?= (int) $strand['is_offered'] === 1 ? 'checked' : '' ?>>
                                                        <span class="sp-toggle-slider"></span>
                                                    </label>
                                                </div>
                                                <div class="sp-field">
                                                    <label>Description (shown on landing page)</label>
                                                    <textarea name="strand_description[<?= (int) $strand['id'] ?>]"
                                                        class="form-control"
                                                        rows="3"><?= htmlspecialchars($strand['short_description'] ?? '') ?></textarea>
                                                </div>
                                                <div class="sp-field">
                                                    <label>Image URL</label>
                                                    <input type="text" name="strand_image[<?= (int) $strand['id'] ?>]"
                                                        class="form-control" placeholder="../images/example.jpg"
                                                        value="<?= htmlspecialchars($strand['image_url'] ?? '') ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- STRANDS IN ACTION VIDEOS -->
                            <div class="sp-tab-panel d-none" data-panel="videos">
                                <div class="sp-panel-header">
                                    <h3>Strands in Action Videos</h3>
                                    <p>Video highlights featured on the public landing page</p>
                                </div>
                                <div class="sp-card-grid">
                                    <?php foreach ($landingVideos as $video): ?>
                                        <div class="sp-strand-card">
                                            <div class="sp-strand-card-top">
                                                <div class="sp-video-title-row">
                                                    <span class="sp-video-number"><?= (int) $video['id'] ?></span>
                                                    <span class="sp-strand-name">Video #<?= (int) $video['id'] ?></span>
                                                </div>
                                                <label class="sp-toggle">
                                                    <input type="checkbox" name="video_active[<?= (int) $video['id'] ?>]"
                                                        value="1" <?= (int) $video['is_active'] === 1 ? 'checked' : '' ?>>
                                                    <span class="sp-toggle-slider"></span>
                                                </label>
                                            </div>
                                            <div class="sp-field">
                                                <label>Title</label>
                                                <input type="text" name="video_title[<?= (int) $video['id'] ?>]"
                                                    class="form-control" value="<?= htmlspecialchars($video['title']) ?>">
                                            </div>
                                            <div class="sp-field">
                                                <label>YouTube Video ID</label>
                                                <input type="text" name="video_youtube_id[<?= (int) $video['id'] ?>]"
                                                    class="form-control" placeholder="e.g. tK2N9dO5mZY"
                                                    value="<?= htmlspecialchars($video['youtube_video_id']) ?>">
                                            </div>
                                            <div class="sp-grid-2">
                                                <div class="sp-field">
                                                    <label>Category Label</label>
                                                    <input type="text" name="video_category[<?= (int) $video['id'] ?>]"
                                                        class="form-control" placeholder="e.g. Academic Track"
                                                        value="<?= htmlspecialchars($video['category_label'] ?? '') ?>">
                                                </div>
                                                <div class="sp-field">
                                                    <label>Duration Label</label>
                                                    <input type="text" name="video_duration[<?= (int) $video['id'] ?>]"
                                                        class="form-control" placeholder="e.g. 14 min"
                                                        value="<?= htmlspecialchars($video['duration_label'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                        </div>

                    </form>

                </div><!-- /main-body -->

            </main>
        </div>
    </div>

    <script>
        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            const isError = type === 'error';
            const toast = document.createElement('div');
            toast.className = 'toast-notif' + (isError ? ' toast-error' : '');
            toast.innerHTML = `
            <div class="toast-icon">
                <i class="fa ${isError ? 'fa-times-circle' : 'fa-check-circle'}"></i>
            </div>
            <div class="toast-body-text">
                <p class="toast-title">${isError ? 'Error' : 'Success'}</p>
                <p class="toast-msg">${escHtml(message)}</p>
            </div>
            <button class="toast-close" onclick="dismissToast(this.parentElement)">
                <i class="fa fa-times"></i>
            </button>
        `;
            container.appendChild(toast);
            setTimeout(() => dismissToast(toast), 3500);
        }

        function dismissToast(toast) {
            if (!toast || toast.classList.contains('toast-hiding')) return;
            toast.classList.add('toast-hiding');
            setTimeout(() => toast.remove(), 300);
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.sp-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('.sp-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const target = this.dataset.tab;
                    document.querySelectorAll('.sp-tab-panel').forEach(panel => {
                        panel.classList.toggle('d-none', panel.dataset.panel !== target);
                    });
                });
            });
        });
    </script>

    <div id="toast-container"></div>

    <?php
    $flash = $_SESSION['flash'] ?? null;
    $currentPage = $_GET['url'] ?? '';
    if ($flash && $flash['page'] === $currentPage):
        unset($_SESSION['flash']);
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showToast('<?= $flash['type'] ?>', '<?= addslashes(htmlspecialchars($flash['message'])) ?>');
            });
        </script>
    <?php elseif ($flash && $flash['page'] !== $currentPage): ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>