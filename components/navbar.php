<nav class="main-nav">
    <div class="nav-title">
        <button class="menu-bar" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
            aria-controls="staticBackdrop">
            <div class="fa fa-bars"></div>
        </button>
    </div>

    <div class="nav-acc">
        <div class="nav-list">
            <!-- + button now opens Join Class modal -->
            <button data-bs-toggle="modal" data-bs-target="#joinClassModal" title="Join a Class">
                <div class="notification-icon">
                    <i class="fa fa-plus"></i>
                </div>
            </button>
            <button>
                <div class="notification-icon">
                    <i class="fa fa-message"></i>
                </div>
            </button>
            <button>
                <div class="notification-icon">
                    <i class="fa fa-bell"></i>
                </div>
            </button>
        </div>

        <div class="drop-name">
            <p><?= htmlspecialchars($_SESSION["name"]) ?></p>
            <span>Student</span>
        </div>

        <div class="dropdown">
            <a href="#" class="dropdown-parent" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <button>
                    <?php
                    $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                    echo $initial;
                    ?>
                </button>
            </a>

            <ul class="dropdown-menu">
                <div class="d-flex justify-content-left align-items-center dropdown-profile gap-2">
                    <button>
                        <?php
                        $initial = isset($_SESSION['name']) ? strtoupper(substr($_SESSION['name'], 0, 1)) : '';
                        echo $initial;
                        ?>
                    </button>
                    <li style="line-height: 25px;">
                        <span class="fw-semibold"><?= $_SESSION['email'] ?></span>
                        <span><?= $_SESSION['section'] ?></span>
                    </li>
                </div>

                <hr>

                <li>
                    <a href="#">
                        <div class="icon-parent"><i class="fa fa-user"></i></div>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="icon-parent"><i class="fa fa-lock"></i></div>
                        <span>Reset Password</span>
                    </a>
                </li>
                <form action="?url=logout" method="post">
                    <li>
                        <a href="#">
                            <div class="icon-parent"><i class="fa fa-sign-out"></i></div>
                            <button type="submit">Logout</button>
                        </a>
                    </li>
                </form>
            </ul>
        </div>
    </div>
</nav>

<!-- ══════════════════════════════════════════════════════════
     JOIN CLASS MODAL  (Google Classroom-style)
     ══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="joinClassModal" tabindex="-1" aria-labelledby="joinClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content join-class-modal">

            <!-- Header -->
            <div class="join-modal-header">
                <h5 class="join-modal-title" id="joinClassModalLabel">
                    <i class="fa fa-graduation-cap me-2"></i>Join a Class
                </h5>
                <button type="button" class="join-modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <!-- Body: idle state -->
            <div class="join-modal-body" id="joinModalBody">
                <p class="join-modal-hint">
                    Ask your teacher for the class code, then enter it here.
                </p>

                <div class="join-code-field">
                    <input type="text" id="classCodeInput" class="join-code-input" placeholder="Class code"
                        maxlength="20" autocomplete="off" spellcheck="false">
                    <label class="join-code-label" for="classCodeInput">Class code</label>
                </div>

                <p class="join-code-hint">
                    Use a class code consisting of 7 letters or numbers with no spaces or special characters.
                </p>

                <!-- Error alert (hidden by default) -->
                <div class="join-alert join-alert-error d-none" id="joinErrorAlert">
                    <i class="fa fa-circle-exclamation me-2"></i>
                    <span id="joinErrorMsg">No class found with that code.</span>
                </div>

                <!-- Already enrolled alert (hidden by default) -->
                <div class="join-alert join-alert-warn d-none" id="joinWarnAlert">
                    <i class="fa fa-triangle-exclamation me-2"></i>
                    <span>You are already enrolled in this class.</span>
                </div>
            </div>

            <!-- Success state (hidden by default) -->
            <div class="join-modal-body d-none" id="joinSuccessBody">
                <div class="join-success-icon">
                    <i class="fa fa-check"></i>
                </div>
                <h6 class="join-success-title">Enrolled Successfully!</h6>
                <p class="join-success-subject" id="joinSuccessSubjectName"></p>
                <p class="join-success-hint">Redirecting you to the subject…</p>
                <div class="progress join-progress">
                    <div id="joinProgressBar" class="progress-bar bg-success" style="width:0%"></div>
                </div>
            </div>

            <!-- Footer -->
            <div class="join-modal-footer" id="joinModalFooter">
                <button type="button" class="join-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="join-btn-submit" id="joinClassBtn" disabled>Join</button>
            </div>

        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     JOIN CLASS STYLES
     ══════════════════════════════════════════════════════════ -->
<style>
    /* ── Modal shell ── */
    .join-class-modal {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
        font-family: 'Google Sans', 'Segoe UI', sans-serif;
    }

    /* ── Header ── */
    .join-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 16px;
        background: #1e7e5e;
        /* matches your green theme */
        color: #fff;
    }

    .join-modal-title {
        font-size: 1.05rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: .01em;
    }

    .join-modal-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, .85);
        font-size: 1rem;
        cursor: pointer;
        line-height: 1;
        transition: color .15s;
    }

    .join-modal-close:hover {
        color: #fff;
    }

    /* ── Body ── */
    .join-modal-body {
        padding: 24px 24px 8px;
    }

    .join-modal-hint {
        font-size: .875rem;
        color: #444;
        margin-bottom: 20px;
    }

    /* ── Floating-label input (Google Classroom style) ── */
    .join-code-field {
        position: relative;
        margin-bottom: 8px;
    }

    .join-code-input {
        width: 100%;
        padding: 15px 14px 15px;
        font-size: .90rem;
        border: 1.5px solid #ccc;
        border-radius: 8px;
        outline: none;
        background: #fff;
        transition: border-color .2s;
        letter-spacing: .05em;
    }

    .join-code-input:focus {
        border-color: #1e7e5e;
    }

    .join-code-input:focus+.join-code-label,
    .join-code-input:not(:placeholder-shown)+.join-code-label {
        top: 6px;
        font-size: .72rem;
        color: #1e7e5e;
    }

    .join-code-label {
        position: absolute;
        top: 50%;
        left: 16px;
        transform: translateY(-50%);
        font-size: .95rem;
        color: #888;
        pointer-events: none;
        transition: top .15s, font-size .15s, color .15s;
        background: #fff;
        padding: 0 2px;
    }

    /* ── Hint text ── */
    .join-code-hint {
        font-size: .78rem;
        color: #777;
        margin-bottom: 14px;
        line-height: 1.5;
    }

    /* ── Alerts ── */
    .join-alert {
        display: flex;
        align-items: center;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .84rem;
        margin-bottom: 10px;
    }

    .join-alert-error {
        background: #fdecea;
        color: #c0392b;
        border: 1px solid #f5c6c2;
    }

    .join-alert-warn {
        background: #fff8e1;
        color: #b7690a;
        border: 1px solid #ffe082;
    }

    /* ── Success state ── */
    .join-success-icon {
        width: 72px;
        height: 72px;
        background: #d4edda;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }

    .join-success-icon .fa {
        font-size: 2rem;
        color: #28a745;
    }

    .join-success-title {
        text-align: center;
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 4px;
    }

    .join-success-subject {
        text-align: center;
        font-size: .9rem;
        color: #1e7e5e;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .join-success-hint {
        text-align: center;
        font-size: .82rem;
        color: #777;
        margin-bottom: 12px;
    }

    .join-progress {
        height: 5px;
        border-radius: 4px;
    }

    /* ── Footer ── */
    .join-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 12px 24px 20px;
        background: #fff;
    }

    .join-btn-cancel {
        background: none;
        border: none;
        color: #555;
        font-size: .9rem;
        font-weight: 500;
        padding: 8px 18px;
        border-radius: 6px;
        cursor: pointer;
        transition: background .15s;
    }

    .join-btn-cancel:hover {
        background: #f0f0f0;
    }

    .join-btn-submit {
        background: #1e7e5e;
        color: #fff;
        border: none;
        font-size: .9rem;
        font-weight: 600;
        padding: 8px 22px;
        border-radius: 6px;
        cursor: pointer;
        transition: background .15s, opacity .15s;
    }

    .join-btn-submit:disabled {
        background: #b2dfdb;
        cursor: not-allowed;
    }

    .join-btn-submit:not(:disabled):hover {
        background: #166347;
    }
</style>

<!-- ══════════════════════════════════════════════════════════
     JOIN CLASS SCRIPT
     ══════════════════════════════════════════════════════════ -->
<script>
    (function () {
        const modal = document.getElementById('joinClassModal');
        const input = document.getElementById('classCodeInput');
        const joinBtn = document.getElementById('joinClassBtn');
        const errorAlert = document.getElementById('joinErrorAlert');
        const errorMsg = document.getElementById('joinErrorMsg');
        const warnAlert = document.getElementById('joinWarnAlert');
        const modalBody = document.getElementById('joinModalBody');
        const successBody = document.getElementById('joinSuccessBody');
        const modalFooter = document.getElementById('joinModalFooter');
        const progressBar = document.getElementById('joinProgressBar');
        const successName = document.getElementById('joinSuccessSubjectName');

        // Reset modal state when closed / reopened
        modal.addEventListener('hidden.bs.modal', resetModal);
        function resetModal() {
            input.value = '';
            joinBtn.disabled = true;
            errorAlert.classList.add('d-none');
            warnAlert.classList.add('d-none');
            modalBody.classList.remove('d-none');
            successBody.classList.add('d-none');
            modalFooter.classList.remove('d-none');
            progressBar.style.width = '0%';
        }

        // Enable Join button only when something is typed
        input.addEventListener('input', function () {
            const code = this.value.trim();
            joinBtn.disabled = code.length < 3;
            // Hide alerts while typing
            errorAlert.classList.add('d-none');
            warnAlert.classList.add('d-none');
        });

        // Allow Enter key to submit
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !joinBtn.disabled) joinBtn.click();
        });

        // Join button click
        joinBtn.addEventListener('click', function () {
            const code = input.value.trim();
            if (!code) return;

            joinBtn.disabled = true;
            joinBtn.textContent = 'Joining…';
            errorAlert.classList.add('d-none');
            warnAlert.classList.add('d-none');

            fetch('/learning_management/public/?url=join_class', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'subject_code=' + encodeURIComponent(code)
            })
                .then(r => r.text())
                .then(function (res) {
                    console.log('RAW RESPONSE:', res);
                    const parsed = JSON.parse(res);

                    if (parsed.already_enrolled) {
                        warnAlert.classList.remove('d-none');
                        joinBtn.disabled = false;
                        joinBtn.textContent = 'Join';
                        return;
                    }

                    if (!parsed.ok) {  // ← was res.ok, change to parsed.ok
                        errorMsg.textContent = parsed.msg || 'No class found with that code.';
                        errorAlert.classList.remove('d-none');
                        joinBtn.disabled = false;
                        joinBtn.textContent = 'Join';
                        return;
                    }

                    // ── Success ──────────────────────────────────────
                    successName.textContent = parsed.subject_name;  // ← parsed
                    modalBody.classList.add('d-none');
                    successBody.classList.remove('d-none');
                    modalFooter.classList.add('d-none');

                    const duration = 2500, interval = 30;
                    const steps = duration / interval;
                    let current = 0;
                    const timer = setInterval(function () {
                        current++;
                        progressBar.style.width = ((current / steps) * 100) + '%';
                        if (current >= steps) {
                            clearInterval(timer);
                            window.location.href = parsed.redirect_url;  // ← parsed
                        }
                    }, interval);
                })
                .catch(function () {
                    errorMsg.textContent = 'Something went wrong. Please try again.';
                    errorAlert.classList.remove('d-none');
                    joinBtn.disabled = false;
                    joinBtn.textContent = 'Join';
                });
        });
    })();
</script>