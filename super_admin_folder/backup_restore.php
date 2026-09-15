<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup & Restore</title>
    <link rel="stylesheet" href="../css_folder/backup_restore.css">
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
                        <h2>Backup & Restore</h2>
                        <p>Protect your data with manual backups and restore points</p>
                    </div>

                </div>

                <div class="main-button-header">
                    <form method="POST" action="/learning_management/public/?url=create_backup">
                        <button type="submit" class="br-primary-btn" id="createBackupBtn">
                            <i class="fa fa-database me-1"></i> Create Backup Now
                        </button>
                    </form>
                </div>

                <div class="main-body">

                    <!-- STAT CARDS -->
                    <div class="br-stats-grid">
                        <div class="br-stat-card">

                            <div>
                                <div class="br-stat-label">Total backups</div>
                                <div class="br-stat-value"><?= (int) $stats['total_backups'] ?></div>
                                <div class="br-stat-desc">All backups created to date</div>
                            </div>
                            <div class="br-stat-icon br-stat-icon-blue">
                                <i class="fa fa-database"></i>
                            </div>
                        </div>
                        <div class="br-stat-card">

                            <div>
                                <div class="br-stat-label">Storage used</div>
                                <div class="br-stat-value"><?= number_format((float) $stats['total_size_mb'], 1) ?> MB
                                </div>
                                <div class="br-stat-desc">Total space used by backups</div>
                            </div>
                            <div class="br-stat-icon br-stat-icon-purple"><i class="fa fa-hard-drive"></i></div>
                        </div>
                        <!-- <div class="br-stat-card">

                            <div>
                                <div class="br-stat-value">
                                    <?= $lastBackup ? htmlspecialchars(date('M j, g:i A', strtotime($lastBackup['created_at']))) : 'Never' ?>
                                </div>
                                <div class="br-stat-label">Last backup</div>
                            </div>
                            <div class="br-stat-icon <?= $lastBackup ? 'br-stat-icon-green' : 'br-stat-icon-gray' ?>">
                                <i class="fa fa-clock"></i>
                            </div>
                        </div> -->
                        <div class="br-stat-card">

                            <div>
                                <div class="br-stat-label">Failed backups</div>
                                <div class="br-stat-value"><?= (int) $stats['failed_count'] ?></div>
                                <div class="br-stat-desc">Backups that did not complete</div>
                            </div>
                            <div
                                class="br-stat-icon <?= $stats['failed_count'] > 0 ? 'br-stat-icon-red' : 'br-stat-icon-green' ?>">
                                <i class="fa fa-triangle-exclamation"></i>
                            </div>
                        </div>
                    </div>

                    <!-- BACKUP HISTORY -->
                    <div class="br-card">
                        <div class="br-card-header">
                            <h3>Backup history</h3>
                            <p>Download or remove previous backups</p>
                        </div>

                        <?php if (empty($backups)): ?>
                            <div class="br-empty">
                                <i class="fa fa-database"></i>
                                <p>No backups yet. Create your first one above.</p>
                            </div>
                        <?php else: ?>
                            <div class="br-table-wrap">
                                <table class="br-table">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($backups as $backup): ?>
                                            <tr>
                                                <td class="br-filename"><?= htmlspecialchars($backup['filename']) ?></td>
                                                <td><span
                                                        class="br-badge br-badge-type"><?= htmlspecialchars(ucfirst($backup['type'])) ?></span>
                                                </td>
                                                <td><?= number_format((float) $backup['size_mb'], 2) ?> MB</td>
                                                <td>
                                                    <span
                                                        class="br-badge <?= $backup['status'] === 'success' ? 'br-badge-success' : 'br-badge-failed' ?>">
                                                        <?= htmlspecialchars(ucfirst($backup['status'])) ?>
                                                    </span>
                                                </td>
                                                <td class="br-time"><?= htmlspecialchars($backup['time_ago']) ?></td>
                                                <td class="text-end">
                                                    <?php if ($backup['status'] === 'success'): ?>
                                                        <a href="/learning_management/public/?url=download_backup&id=<?= (int) $backup['id'] ?>"
                                                            class="br-icon-btn" title="Download">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                        <button type="button" class="br-icon-btn br-restore-trigger"
                                                            title="Restore this backup" data-backup-id="<?= (int) $backup['id'] ?>"
                                                            data-backup-name="<?= htmlspecialchars($backup['filename'], ENT_QUOTES) ?>">
                                                            <i class="fa fa-clock-rotate-left"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button"
                                                        class="br-icon-btn br-icon-btn-danger br-delete-trigger" title="Delete"
                                                        data-backup-id="<?= (int) $backup['id'] ?>"
                                                        data-backup-name="<?= htmlspecialchars($backup['filename'], ENT_QUOTES) ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- RESTORE FROM UPLOAD -->
                    <div class="br-card">
                        <div class="br-card-header">
                            <h3><i class="fa fa-upload me-2"></i>Restore from file</h3>
                            <p>Upload a .sql backup file to restore the database</p>
                        </div>
                        <form method="POST" action="/learning_management/public/?url=restore_backup"
                            enctype="multipart/form-data" class="br-upload-form" id="uploadRestoreForm">
                            <label class="br-file-drop" for="restoreFileInput">
                                <i class="fa fa-file-arrow-up"></i>
                                <span id="restoreFileLabel">Click to choose a .sql file, or drag it here</span>
                                <input type="file" id="restoreFileInput" name="restore_file" accept=".sql" hidden>
                            </label>
                            <button type="submit" class="br-danger-btn" id="uploadRestoreBtn" disabled>
                                <i class="fa fa-triangle-exclamation me-1"></i> Restore Database
                            </button>
                        </form>
                    </div>

                </div><!-- /main-body -->

            </main>
        </div>
    </div>

    <!-- CONFIRM MODAL (delete / restore) -->
    <div class="br-modal-overlay" id="confirmModalOverlay">
        <div class="br-modal">
            <div class="br-modal-icon" id="confirmModalIcon"><i class="fa fa-triangle-exclamation"></i></div>
            <h4 id="confirmModalTitle">Are you sure?</h4>
            <p id="confirmModalMessage"></p>
            <div class="br-modal-actions">
                <button type="button" class="br-secondary-btn" id="confirmModalCancel">Cancel</button>
                <button type="button" class="br-danger-btn" id="confirmModalConfirm">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Hidden forms submitted after confirmation -->
    <form method="POST" action="/learning_management/public/?url=delete_backup" id="deleteBackupForm">
        <input type="hidden" name="id" id="deleteBackupId">
    </form>
    <form method="POST" action="/learning_management/public/?url=restore_backup" id="restoreExistingForm">
        <input type="hidden" name="backup_id" id="restoreBackupId">
    </form>

    <script>
        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            const isError = type === 'error';
            const toast = document.createElement('div');
            toast.className = 'toast-notif' + (isError ? ' toast-error' : '');
            toast.innerHTML = `
            <div class="toast-icon"><i class="fa ${isError ? 'fa-times-circle' : 'fa-check-circle'}"></i></div>
            <div class="toast-body-text">
                <p class="toast-title">${isError ? 'Error' : 'Success'}</p>
                <p class="toast-msg">${escHtml(message)}</p>
            </div>
            <button class="toast-close" onclick="dismissToast(this.parentElement)"><i class="fa fa-times"></i></button>
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

            // File picker label + enable restore button
            const fileInput = document.getElementById('restoreFileInput');
            const fileLabel = document.getElementById('restoreFileLabel');
            const uploadBtn = document.getElementById('uploadRestoreBtn');
            fileInput.addEventListener('change', function () {
                if (this.files.length > 0) {
                    fileLabel.textContent = this.files[0].name;
                    uploadBtn.disabled = false;
                } else {
                    fileLabel.textContent = 'Click to choose a .sql file, or drag it here';
                    uploadBtn.disabled = true;
                }
            });

            // Confirm modal wiring
            const overlay = document.getElementById('confirmModalOverlay');
            const modalIcon = document.getElementById('confirmModalIcon');
            const modalTitle = document.getElementById('confirmModalTitle');
            const modalMessage = document.getElementById('confirmModalMessage');
            const modalCancel = document.getElementById('confirmModalCancel');
            const modalConfirm = document.getElementById('confirmModalConfirm');
            let pendingAction = null;

            function openModal(title, message, onConfirm) {
                modalTitle.textContent = title;
                modalMessage.textContent = message;
                pendingAction = onConfirm;
                overlay.classList.add('active');
            }
            function closeModal() {
                overlay.classList.remove('active');
                pendingAction = null;
            }
            modalCancel.addEventListener('click', closeModal);
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closeModal();
            });
            modalConfirm.addEventListener('click', function () {
                if (pendingAction) pendingAction();
                closeModal();
            });

            document.querySelectorAll('.br-delete-trigger').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.backupId;
                    const name = this.dataset.backupName;
                    openModal(
                        'Delete this backup?',
                        `"${name}" will be permanently deleted. This cannot be undone.`,
                        function () {
                            document.getElementById('deleteBackupId').value = id;
                            document.getElementById('deleteBackupForm').submit();
                        }
                    );
                });
            });

            document.querySelectorAll('.br-restore-trigger').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.dataset.backupId;
                    const name = this.dataset.backupName;
                    openModal(
                        'Restore this backup?',
                        `Restoring "${name}" will overwrite your current database. This cannot be undone.`,
                        function () {
                            document.getElementById('restoreBackupId').value = id;
                            document.getElementById('restoreExistingForm').submit();
                        }
                    );
                });
            });

            document.getElementById('uploadRestoreForm').addEventListener('submit', function (e) {
                if (!confirm('Restoring will overwrite your current database. This cannot be undone. Continue?')) {
                    e.preventDefault();
                }
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