<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admins</title>
    <link rel="stylesheet" href="../css_folder/student_records.css">
    <link rel="stylesheet" href="../css_folder/admin_users.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
        
    </style>
</head>


<body>


    <div class="container-fluid p-0">

        <?php include("../super_admin_folder/sidebar.php") ?>

        <div class="rightbar">
            <!-- MAIN -->
            <main class="main">

                <div class="main-header">
                    <div class="main-text-header">
                        <h2>Admins</h2>
                        <p>Manage sub-admin accounts</p>
                    </div>
                </div>

                <div class="main-button-header">
                    <button type="button" class="btn" id="btn-add-admin"
                        style="background-color: var(--neon-cyan); color:#fff; font-weight:600; border-radius:28px; padding:9px 22px; border:none;">
                        <i class="fa fa-user-plus me-1"></i> Add Admin
                    </button>
                </div>

                <div class="main-body">

                    <!-- ADMIN STATS -->
                    <div class="ml-stats-grid">
                        <div class="ml-stat-card">
                            <div>
                                <div class="ml-stat-label">Total Admin Accounts</div>
                                <div class="ml-stat-value"><?= $totalAdmins ?? 0 ?></div>
                                <div class="ml-stat-desc">All registered sub-admin accounts</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-users"></i></div>
                        </div>
                        <div class="ml-stat-card">
                            <div>
                                <div class="ml-stat-label">Active Now</div>
                                <div class="ml-stat-value"><?= $activeAdminsNow ?? 0 ?></div>
                                <div class="ml-stat-desc">Signed in within the last 5 minutes</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-circle-dot"></i></div>
                        </div>
                        <div class="ml-stat-card">
                            <div>
                                <div class="ml-stat-label">Added This Month</div>
                                <div class="ml-stat-value"><?= $addedThisMonth ?? 0 ?></div>
                                <div class="ml-stat-desc">New admin accounts this month</div>
                            </div>
                            <div class="ml-stat-icon"><i class="fa fa-user-plus"></i></div>
                        </div>
                    </div>

                    <!-- FILTER FORM -->
                    <form method="GET" action="" id="filter-form">
                        <input type="hidden" name="url" value="super_admin_admin_users">
                        <input type="hidden" name="page" id="page-input" value="1">

                        <div class="search-parent-enrolled">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="search" name="search" id="search-input" class="form-control"
                                    placeholder="Search name or username..."
                                    value="<?= htmlspecialchars($search ?? '') ?>">
                            </div>
                        </div>
                    </form>

                    <!-- TABLE -->
                    <div class="table-parent">
                        <table class="table">
                            <thead>
                                <tr style="background-color: #ddd;">
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($admins)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No admins found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($admins as $adminRow): ?>
                                        <?php $initial = strtoupper(substr(trim($adminRow['name']), 0, 1) ?: '?'); ?>
                                        <tr class="admins-data"
                                            data-name="<?= htmlspecialchars(strtolower($adminRow['name'])) ?>"
                                            data-username="<?= htmlspecialchars(strtolower($adminRow['username'])) ?>">
                                            <td>
                                                <div class="name-cell">
                                                    <span class="avatar-badge"><?= htmlspecialchars($initial) ?></span>
                                                    <?= htmlspecialchars($adminRow['name']) ?>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($adminRow['username']) ?></td>
                                            <td><?= !empty($adminRow['created_at']) ? date('M j, Y', strtotime($adminRow['created_at'])) : '—' ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary btn-edit-admin"
                                                    data-admin-id="<?= $adminRow['id'] ?>"
                                                    data-name="<?= htmlspecialchars($adminRow['name'], ENT_QUOTES) ?>"
                                                    data-username="<?= htmlspecialchars($adminRow['username'], ENT_QUOTES) ?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger btn-delete-admin"
                                                    data-admin-id="<?= $adminRow['id'] ?>"
                                                    data-name="<?= htmlspecialchars($adminRow['name'], ENT_QUOTES) ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <?php if (($totalPages ?? 0) > 1): ?>
                        <div class="pagination-parent">
                            <small class="text-muted">
                                Showing <?= min($offset + $limit, $totalAdmins) ?> of
                                <?= $totalAdmins ?> admins
                            </small>
                            <ul class="pagination">
                                <?php
                                $filterParams = http_build_query([
                                    'url' => 'super_admin_admin_users',
                                    'search' => $search ?? '',
                                ]);
                                ?>

                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page - 1 ?>">
                                        <i class="fa fa-chevron-left"></i>
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= $filterParams ?>&page=<?= $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?<?= $filterParams ?>&page=<?= $page + 1 ?>">
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div><!-- /main-body -->

            </main>
        </div>
    </div>

    <!-- ADD ADMIN MODAL -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i>Add Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/learning_management/public/?url=create_super_admin_Admin">
                    <div class="modal-body" style="padding: 16px 20px;">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required minlength="8">
                        </div>

                    </div>

                    <div class="modal-footer" style="display: flex; padding: 16px 22px; gap: 10px;">
                        <button type="button" class="btn btn-secondary"
                            style="background: none; border: 1px solid #e4e7eb; border-radius: 50px; padding: 9px 20px; font-size: 13px; font-weight: 700; color: #6b7280;"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn"
                            style="background-color: var(--neon-cyan); color: #ffffff; font-weight: 600; border-radius: 28px;">
                            <i class="fa fa-user-plus me-1"></i> Create Admin
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- EDIT ADMIN MODAL -->
    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user-edit me-2"></i>Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="/learning_management/public/?url=update_super_admin_Admin">
                    <div class="modal-body" style="padding: 16px 20px;">

                        <input type="hidden" name="admin_id" id="edit_admin_id">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" id="edit_admin_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" id="edit_admin_username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password <span class="text-muted">(leave blank to keep
                                    current)</span></label>
                            <input type="password" name="password" class="form-control" minlength="8">
                        </div>

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

    <!-- DELETE ADMIN FORM (hidden, submitted after confirm) -->
    <form method="POST" action="/learning_management/public/?url=delete_super_admin_Admin" id="delete-admin-form"
        style="display:none;">
        <input type="hidden" name="admin_id" id="delete_admin_id">
    </form>

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

    <!-- TOAST CONTAINER -->
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
            const form = document.getElementById('filter-form');

            document.getElementById('search-input').addEventListener('input', debounce(() => {
                document.getElementById('page-input').value = 1;
                form.submit();
            }, 400));

            function debounce(fn, delay) {
                let timer;
                return function (...args) {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, args), delay);
                };
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const addModal = new bootstrap.Modal(document.getElementById('addAdminModal'));
            const editModal = new bootstrap.Modal(document.getElementById('editAdminModal'));

            document.getElementById('btn-add-admin').addEventListener('click', function () {
                addModal.show();
            });

            document.addEventListener('click', function (e) {
                const editBtn = e.target.closest('.btn-edit-admin');
                if (editBtn) {
                    document.getElementById('edit_admin_id').value = editBtn.dataset.adminId;
                    document.getElementById('edit_admin_name').value = editBtn.dataset.name;
                    document.getElementById('edit_admin_username').value = editBtn.dataset.username;
                    editModal.show();
                    return;
                }

                const deleteBtn = e.target.closest('.btn-delete-admin');
                if (deleteBtn) {
                    const name = deleteBtn.dataset.name;
                    if (confirm(`Delete admin account "${name}"? This cannot be undone.`)) {
                        document.getElementById('delete_admin_id').value = deleteBtn.dataset.adminId;
                        document.getElementById('delete-admin-form').submit();
                    }
                }
            });
        });
    </script>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>

</html>