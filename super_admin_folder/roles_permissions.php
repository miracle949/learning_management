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
</head>

<body>

    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <main class="main">

                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Roles & Permissions</h2>
                        <p>Define what each role can access</p>
                    </div>
                </div>

                <div class="main-body">

                    <div class="rp-card">

                        <div class="rp-card-header">
                            <h3><i class="fa fa-user-shield me-2"></i>Roles & permissions</h3>
                            <p>Control what each role can see and do</p>
                        </div>

                        <div class="rp-tabs" role="tablist">
                            <?php $first = true; ?>
                            <?php foreach ($roleDefinitions as $roleKey => $perms): ?>
                                <button type="button" class="rp-tab <?= $first ? 'active' : '' ?>"
                                    data-role="<?= htmlspecialchars($roleKey) ?>" role="tab"
                                    aria-selected="<?= $first ? 'true' : 'false' ?>">
                                    <?= htmlspecialchars(ucfirst($roleKey)) ?>
                                </button>
                                <?php $first = false; ?>
                            <?php endforeach; ?>
                        </div>

                        <?php $first = true; ?>
                        <?php foreach ($roleDefinitions as $roleKey => $perms): ?>
                            <div class="rp-perm-panel <?= $first ? '' : 'd-none' ?>"
                                data-role-panel="<?= htmlspecialchars($roleKey) ?>">
                                <?php foreach ($perms as $permKey => $def): ?>
                                    <?php $isAllowed = $rolePermissions[$roleKey][$permKey] ?? $def['default']; ?>
                                    <div class="rp-perm-row">
                                        <div>
                                            <div class="rp-perm-title"><?= htmlspecialchars($def['label']) ?></div>
                                            <div class="rp-perm-desc"><?= htmlspecialchars($def['description']) ?></div>
                                        </div>
                                        <label class="rp-toggle">
                                            <input type="checkbox" class="role-perm-checkbox"
                                                data-role="<?= htmlspecialchars($roleKey) ?>"
                                                data-key="<?= htmlspecialchars($permKey) ?>" <?= $isAllowed ? 'checked' : '' ?>>
                                            <span class="rp-toggle-slider"></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php $first = false; ?>
                        <?php endforeach; ?>

                    </div>

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

            // Tab switching
            document.querySelectorAll('.rp-tab').forEach(tab => {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('.rp-tab').forEach(t => {
                        t.classList.remove('active');
                        t.setAttribute('aria-selected', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    const role = this.dataset.role;
                    document.querySelectorAll('.rp-perm-panel').forEach(panel => {
                        panel.classList.toggle('d-none', panel.dataset.rolePanel !== role);
                    });
                });
            });

            // Instant-save toggles
            document.querySelectorAll('.role-perm-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const checkbox = this;
                    const role = checkbox.dataset.role;
                    const key = checkbox.dataset.key;
                    const allowed = checkbox.checked;

                    checkbox.disabled = true;

                    fetch('/learning_management/public/?url=toggle_role_permission', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `role=${encodeURIComponent(role)}&permission_key=${encodeURIComponent(key)}&allowed=${allowed ? 1 : 0}`
                    })
                        .then(res => res.json())
                        .then(data => {
                            checkbox.disabled = false;
                            if (data.success) {
                                showToast('success', 'Permission updated.');
                            } else {
                                checkbox.checked = !allowed;
                                showToast('error', data.message || 'Failed to update permission.');
                            }
                        })
                        .catch(() => {
                            checkbox.disabled = false;
                            checkbox.checked = !allowed;
                            showToast('error', 'Network error. Please try again.');
                        });
                });
            });
        });
    </script>

    <div id="toast-container"></div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>