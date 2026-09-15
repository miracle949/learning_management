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

    <style>
        /* -- Toast Notification (same pattern as student_users.php) -- */

        .main-text-header h2 {
            font-size: 24px;
            margin: 0 0 5px;
            font-weight: 600;
        }

        .main-text-header p {
            font-size: 14.5px;
            margin: 0;
            /* color: #808080; */
            color: var(--text-dim);
            font-weight: 500;
        }

        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast-notif {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-radius: 12px;
            padding: 14px 18px;
            min-width: 280px;
            max-width: 380px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #00C950;
            pointer-events: all;
            animation: toastIn .35s cubic-bezier(.34, 1.56, .64, 1) forwards;
            position: relative;
            overflow: hidden;
        }

        .toast-notif.toast-error {
            border-left-color: #e53e3e;
        }

        .toast-notif.toast-hiding {
            animation: toastOut .3s ease forwards;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
            color: #00C950;
        }

        .toast-notif.toast-error .toast-icon {
            background: #fff5f5;
            color: #e53e3e;
        }

        .toast-body-text {
            flex: 1;
        }

        .toast-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 2px;
        }

        .toast-msg {
            font-size: 12.5px;
            color: #6b7280;
            margin: 0;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 14px;
            padding: 0;
            line-height: 1;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #374151;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #00C950;
            border-radius: 0 0 0 12px;
            animation: toastProgress 3.5s linear forwards;
            width: 100%;
        }

        .toast-notif.toast-error .toast-progress {
            background: #e53e3e;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(60px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateX(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateX(60px) scale(0.9);
            }
        }

        @keyframes toastProgress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* -- School Profile page -- */
        .sp-card {
            background: #fff;
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.5rem;
        }

        .sp-card h3 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 1.2rem;
            color: #1a1a1a;
        }

        .sp-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem 1.5rem;
        }

        @media (max-width: 768px) {
            .sp-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .sp-field label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dim);
            margin-bottom: 6px;
            display: block;
        }

        .sp-field input,
        .sp-field select,
        .sp-filed textarea {
            font-size: 14px;
        }

        .form-control{
            font-size: 14px;
        }
        

        .sp-strand-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .sp-strand-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 4px;
            border-bottom: 1px solid var(--border);
        }

        .sp-strand-row:last-child {
            border-bottom: none;
        }

        .sp-strand-name {
            font-size: 14.5px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .sp-strand-track {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-dim);
            margin-top: 2px;
        }

        /* Toggle switch, styled like the reference mockup */
        .sp-toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .sp-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .sp-toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #d1d5db;
            transition: .2s;
            border-radius: 24px;
        }

        .sp-toggle-slider::before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: #fff;
            transition: .2s;
            border-radius: 50%;
        }

        .sp-toggle input:checked+.sp-toggle-slider {
            background-color: #00C950;
        }

        .sp-toggle input:checked+.sp-toggle-slider::before {
            transform: translateX(20px);
        }

        .sp-save-bar {
            /* position: sticky;
            bottom: 0;
            display: flex;
            justify-content: flex-end;
            padding: 1rem 0;
            margin-top: -0.5rem; */
            margin: 1.5rem 0 0;
            display: flex;
            justify-content: end;
        }

        .sp-save-btn {
            background-color: var(--neon-cyan);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 28px;
            padding: 10px 28px;
            font-size: 14px;
        }
    </style>
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

                        <!-- SCHOOL INFORMATION -->
                        <div class="sp-card" style="margin-top: 1.5rem;">
                            <h3>School Information</h3>
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
                        <div class="sp-card">
                            <h3>Academic Setup</h3>
                            <div class="sp-grid-2" style="margin-bottom: 1.5rem;">
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

                            <label
                                style="font-size:13px; font-weight:600; color: var(--text-dim); display:block; margin-bottom: 6px;">
                                Strands Offered
                            </label>
                            <div class="sp-strand-list">
                                <?php if (empty($strandSettings)): ?>
                                    <p class="text-muted mb-0">No strands configured yet.</p>
                                <?php else: ?>
                                    <?php foreach ($strandSettings as $strand): ?>
                                        <div class="sp-strand-row"
                                            style="flex-direction:column; align-items:stretch; gap:10px;">
                                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                                <div>
                                                    <div class="sp-strand-name">
                                                        <?= htmlspecialchars($strand['strand_code'] . ' — ' . $strand['strand_name']) ?>
                                                    </div>
                                                    <div class="sp-strand-track"><?= htmlspecialchars($strand['track'] ?? '') ?>
                                                    </div>
                                                </div>
                                                <label class="sp-toggle">
                                                    <input type="checkbox" name="strands_offered[]"
                                                        value="<?= (int) $strand['id'] ?>" <?= (int) $strand['is_offered'] === 1 ? 'checked' : '' ?>>
                                                    <span class="sp-toggle-slider"></span>
                                                </label>
                                            </div>
                                            <div class="sp-grid-2">
                                                <div class="sp-field">
                                                    <label>Description (shown on landing page)</label>
                                                    <textarea name="strand_description[<?= (int) $strand['id'] ?>]"
                                                        class="form-control"
                                                        rows="2"><?= htmlspecialchars($strand['short_description'] ?? '') ?></textarea>
                                                </div>
                                                <div class="sp-field">
                                                    <label>Image URL</label>
                                                    <input type="text" name="strand_image[<?= (int) $strand['id'] ?>]"
                                                        class="form-control" placeholder="../images/example.jpg"
                                                        value="<?= htmlspecialchars($strand['image_url'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- STRANDS IN ACTION VIDEOS -->
                        <div class="sp-card">
                            <h3>Strands in Action Videos</h3>
                            <?php foreach ($landingVideos as $video): ?>
                                <div class="sp-strand-row" style="flex-direction:column; align-items:stretch; gap:10px;">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <div class="sp-strand-name">Video #<?= (int) $video['id'] ?></div>
                                        <label class="sp-toggle">
                                            <input type="checkbox" name="video_active[<?= (int) $video['id'] ?>]" value="1"
                                                <?= (int) $video['is_active'] === 1 ? 'checked' : '' ?>>
                                            <span class="sp-toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="sp-grid-2">
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