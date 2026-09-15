<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles & Permissions</title>
    <link rel="stylesheet" href="../css_folder/roles_permissions.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
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
                transform: translateX(60px) scale(.9);
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
                transform: translateX(60px) scale(.9);
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

        .rp-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            margin-top: 1.5rem;
        }

        .rp-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .rp-row:last-child {
            border-bottom: none;
        }

        .rp-admin-name {
            font-size: 14.5px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .rp-admin-username {
            font-size: 12.5px;
            color: var(--text-dim);
            margin-top: 2px;
        }

        .rp-manage-btn {
            border: 1px solid var(--border);
            background: #fff;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .rp-perm-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 4px;
            border-bottom: 1px solid var(--border);
        }

        .rp-perm-row:last-child {
            border-bottom: none;
        }

        .rp-toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .rp-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .rp-toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #d1d5db;
            transition: .2s;
            border-radius: 24px;
        }

        .rp-toggle-slider::before {
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

        .rp-toggle input:checked+.rp-toggle-slider {
            background-color: #00C950;
        }

        .rp-toggle input:checked+.rp-toggle-slider::before {
            transform: translateX(20px);
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
                        <h2>Roles & Permissions</h2>
                        <p>Control which pages each admin account can access</p>
                    </div>
                </div>

                <div class="main-body">

                    <div class="rp-card">
                        <?php if (empty($admins)): ?>
                            <div class="rp-row">
                                <p class="text-muted mb-0">No admin accounts yet. Create one from the Admins page first.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($admins as $adminRow): ?>
                                <?php
                                $perms = $adminPermissions[$adminRow['id']] ?? [];
                                $allowedCount = count(array_filter($perms));
                                $totalCount = count($permissionPages);
                                ?>
                                <div class="rp-row">
                                    <div>
                                        <div class="rp-admin-name">
                                            <?= htmlspecialchars($adminRow['name']) ?>
                                        </div>
                                        <div class="rp-admin-username">
                                            <?= htmlspecialchars($adminRow['username']) ?> ·
                                            <?= $allowedCount ?>/
                                            <?= $totalCount ?> pages allowed
                                        </div>
                                    </div>
                                    <button type="button" class="rp-manage-btn btn-manage-access"
                                        data-user-id="<?= (int) $adminRow['id'] ?>"
                                        data-name="<?= htmlspecialchars($adminRow['name'], ENT_QUOTES) ?>"
                                        data-perms='<?= htmlspecialchars(json_encode($perms), ENT_QUOTES) ?>'>
                                        <i class="fa fa-lock me-1"></i> Manage Access
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div><!-- /main-body -->

            </main>
        </div>
    </div>

    <!-- MANAGE ACCESS MODAL -->
    <div class="modal fade" id="managePermsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock me-2"></i>Manage Access — <span
                            id="perm_admin_name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/learning_management/public/?url=save_admin_permissions">
                    <div class="modal-body" style="padding: 16px 20px;">
                        <input type="hidden" name="user_id" id="perm_user_id">

                        <?php foreach ($permissionPages as $pageKey => $pageLabel): ?>
                            <div class="rp-perm-row">
                                <span>
                                    <?= htmlspecialchars($pageLabel) ?>
                                </span>
                                <label class="rp-toggle">
                                    <input type="checkbox" name="allowed_pages[]" value="<?= htmlspecialchars($pageKey) ?>"
                                        class="perm-checkbox" data-page-key="<?= htmlspecialchars($pageKey) ?>">
                                    <span class="rp-toggle-slider"></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="modal-footer" style="display: flex; padding: 16px 22px; gap: 10px;">
                        <button type="button" class="btn btn-secondary"
                            style="background: none; border: 1px solid #e4e7eb; border-radius: 50px; padding: 9px 20px; font-size: 13px; font-weight: 700; color: #6b7280;"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn"
                            style="background-color: var(--neon-cyan); color: #ffffff; font-weight: 600; border-radius: 28px;">
                            <i class="fa fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('managePermsModal'));

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.btn-manage-access');
                if (!btn) return;

                document.getElementById('perm_user_id').value = btn.dataset.userId;
                document.getElementById('perm_admin_name').textContent = btn.dataset.name;

                const perms = JSON.parse(btn.dataset.perms || '{}');
                document.querySelectorAll('.perm-checkbox').forEach(cb => {
                    cb.checked = !!perms[cb.dataset.pageKey];
                });

                modal.show();
            });
        });
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>