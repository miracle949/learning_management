<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment View</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        .av-page {
            width: 100%;
        }

        .av-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--green);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .av-back-link:hover {
            text-decoration: underline;
        }

        .av-header-card {
            background: #fff;
            border: 1px solid #E2E8E5;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .06);
        }

        .av-header-top {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            padding: 2rem 1.8rem 0rem 1.8rem;
        }

        .av-header-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background-color: #E8F5EE;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green);
            font-size: 22px;
        }

        .av-header-info small {
            font-size: 12px;
            color: #aaa;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .av-header-info h2 {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 6px;
            line-height: 1.4;
        }

        .av-date-parent {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .av-date {
            font-size: 13.5px;
            color: #aaa;
            display: block;
        }

        .date-received {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .av-points {
            font-size: 13.5px;
            font-weight: 600;
        }

        .av-due-badge {
            display: inline-block;
            /* background-color: #E8F5EE; */
            color: var(--green);
            font-size: 13.5px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 20px;
        }

        .av-desc-card {
            padding: 0.5rem 0;
        }

        .av-desc-card h5 {
            font-size: 14px;
            font-weight: 800;
            color: #333;
            margin-bottom: .8rem;
        }

        .av-desc-card p {
            font-size: 14px;
            color: #444;
            line-height: 1.7;
            margin: 0;
            border-left: 4px solid var(--green);
            padding-left: 14px;
        }

        .av-template-card {
            padding: 1.5rem 1.8rem;
        }

        .av-template-card h5 {
            font-size: 14px;
            font-weight: 800;
            color: #333;
            margin-bottom: 1rem;
        }

        .av-file-card:hover .av-file-icon {
            opacity: .85;
            transform: translateY(-2px);
        }

        .av-file-icon {
            width: 140px;
            height: 110px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 32px;
            color: #DB0001;
            transition: opacity .18s, transform .18s;
            background-color: #F0F0F0;
        }

        .av-file-icon.word {
            background: #2b579a;
            color: #fff;
        }

        .av-file-icon.pdf {
            background: #b0b0b0;
            color: #fff;
        }

        .av-file-icon span {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .av-file-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 700;
            padding: 10px;
            border-radius: 20px;
        }

        .av-file-badge.word {
            background: #dbeafe;
            color: #2563eb;
        }

        .av-file-badge.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        /* ─── Submission area ─── */
        .av-message-card {
            margin-top: 3rem;
        }

        /*
         * BUTTON STATES (clearly defined):
         *   idle      = grey paper-plane  → no file attached, click = shake warning
         *   ready     = GREEN paper-plane → file attached, click = SUBMIT
         *   submitted = RED X             → already submitted, click = unsubmit dialog
         */
        .av-message-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 10px 16px;
            margin: 0 20px;
            background-color: #F0F0F0;
            transition: background .2s, border-color .2s;
        }

        .av-message-box.has-file {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
        }

        .av-message-box.submitted {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            margin-bottom: 0;
        }

        .av-msg-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .av-msg-status-icon {
            font-size: 20px;
            flex-shrink: 0;
            color: #aaa;
            transition: color .2s;
        }

        .av-msg-status-icon.green {
            color: #00C950;
        }

        .av-msg-text-col {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .av-msg-title {
            font-size: 13px;
            font-weight: 800;
            color: #166534;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .av-msg-sub {
            font-size: 12px;
            color: #555;
            margin-top: 1px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .av-msg-placeholder {
            font-size: 14px;
            color: #aaa;
        }

        /* Base button */
        .av-msg-btn {
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            flex-shrink: 0;
            font-size: 14px;
            transition: background .18s, transform .15s;
        }

        .av-msg-btn:hover {
            transform: scale(1.08);
        }

        /* grey paper-plane: no file */
        .av-msg-btn.idle {
            background: #c0c0c0;
        }

        /* green paper-plane: file ready to submit */
        .av-msg-btn.ready {
            background: var(--green);
        }

        /* red X: already submitted → click to unsubmit */
        .av-msg-btn.danger {
            background: #ef4444;
        }

        .av-msg-btn.danger:hover {
            background: #dc2626;
        }

        .av-message-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.2rem;
            margin-top: 1rem;
            padding: 10px 50px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background-color: #F0F0F0;
            transition: opacity .2s;
        }

        .av-message-actions button {
            background: none;
            border: none;
            color: var(--green);
            font-size: 23px;
            cursor: pointer;
            padding: 0;
            transition: color .18s;
        }

        .av-message-actions.locked {
            opacity: .4;
            pointer-events: none;
        }

        .av-not-found {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }

        .av-not-found i {
            font-size: 48px;
            display: block;
            margin-bottom: 14px;
        }

        /* ─── PDF Modal ─── */
        .pdf-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn .2s ease;
        }

        .pdf-modal-overlay.open {
            display: flex;
        }

        .pdf-modal {
            background: #1a1a1a;
            border-radius: 14px;
            overflow: hidden;
            width: 90vw;
            max-width: 1000px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.5);
            animation: slideUp .25s ease;
        }

        .av-instructions-card {
            padding: 0 2rem 2rem 2rem;
            margin-top: 2rem;
        }

        .av-instructions-card p {
            margin: 0;
            font-size: 14.5px;
        }

        .av-instructions-card p:nth-child(1) {
            font-weight: 700;
        }

        .av-instructions-card p:nth-child(2) {
            margin: 0.8rem 0;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(8px);
            }

            60% {
                transform: translateX(-6px);
            }

            80% {
                transform: translateX(6px);
            }
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        .pdf-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: #2d2d2d;
            flex-shrink: 0;
        }

        .pdf-modal-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pdf-modal-header-left i {
            color: #dc2626;
            font-size: 20px;
        }

        .pdf-modal-title {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .pdf-modal-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pdf-modal-btn {
            background: rgba(255, 255, 255, .1);
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .18s;
            text-decoration: none;
        }

        .pdf-modal-btn:hover {
            background: rgba(255, 255, 255, .2);
            color: #fff;
        }

        .pdf-modal-close {
            background: rgba(255, 255, 255, .1);
            border: none;
            border-radius: 8px;
            color: #fff;
            width: 34px;
            height: 34px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .18s;
        }

        .pdf-modal-close:hover {
            background: #dc2626;
        }

        .pdf-modal-body {
            flex: 1;
            overflow: hidden;
        }

        .pdf-modal-iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        /* ─── Unsubmit dialog ─── */
        .unsubmit-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .unsubmit-overlay.open {
            display: flex;
        }

        .unsubmit-dialog {
            background: #fff;
            border-radius: 14px;
            padding: 2rem 2rem 1.5rem;
            max-width: 360px;
            width: 90%;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .18);
            text-align: center;
            animation: slideUp .22s ease;
        }

        .unsubmit-dialog .ud-icon {
            font-size: 38px;
            color: #ef4444;
            margin-bottom: .8rem;
        }

        .unsubmit-dialog h4 {
            font-size: 16px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: .4rem;
        }

        .unsubmit-dialog p {
            font-size: 13px;
            color: #666;
            margin-bottom: 1.4rem;
        }

        .unsubmit-dialog .ud-btns {
            display: flex;
            gap: .8rem;
            justify-content: center;
        }

        .unsubmit-dialog .ud-btns button {
            border: none;
            border-radius: 8px;
            padding: 9px 22px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .ud-cancel {
            background: #f3f4f6;
            color: #333;
        }

        .ud-confirm {
            background: #ef4444;
            color: #fff;
        }

        /* ─── Toast ─── */
        #avToast {
            position: fixed;
            bottom: 28px;
            left: 45%;
            transform: translateX(-50%);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #fff;
            min-width: 220px;
            max-width: 360px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .18);
            pointer-events: none;
            opacity: 0;
        }

        #avToast.show {
            animation: toastIn .3s ease forwards;
        }

        #avToast.hide {
            animation: toastOut .3s ease forwards;
        }

        #avToast.success {
            background: #166534;
        }

        #avToast.error {
            background: #b91c1c;
        }

        #avToast.warn {
            background: #92400e;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="av-page">

                <?php if (!$assignment): ?>
                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="av-back-link">
                        <i class="fa fa-arrow-left"></i> Back to Subject
                    </a>
                    <div class="av-not-found">
                        <i class="fa fa-folder-open"></i>
                        <p>Assignment not found.</p>
                    </div>

                <?php else: ?>

                    <div class="av-header-card">
                        <div class="av-header-top">
                            <div class="av-header-icon">
                                <i class="fa fa-file-alt"></i>
                            </div>
                            <div class="av-header-info">
                                <small>New Assignment</small>
                                <h2><?= htmlspecialchars($assignment['task']) ?></h2>
                                <div class="av-desc-card">
                                    <h5>Description</h5>
                                    <p><?= htmlspecialchars($assignment['description']) ?></p>
                                </div>
                                <div class="date-received">
                                    <span class="av-date">
                                        Date Received:
                                        <?= date('M j', strtotime($assignment['posted_at'])) ?>
                                    </span>
                                </div>
                                <div class="av-date-parent">
                                    <span class="av-points">
                                        <?= htmlspecialchars($assignment['points']) ?> pts
                                    </span>
                                    <div class="due-date">
                                        <?php if (!empty($assignment['due_date'])): ?>
                                            <span class="av-due-badge">
                                                <i class="fa fa-calendar-alt"></i>
                                                Due Date: <?= date('M j, Y', strtotime($assignment['due_date'])) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="av-instructions-card">
                            <p>Instructions:</p>
                            <p><?= nl2br(htmlspecialchars($assignment['instructions'])) ?></p>
                        </div>

                        <?php if (!empty($templates)): ?>
                            <div class="av-template-card">
                                <h5>Template / Files</h5>
                                <?php foreach ($templates as $tpl):
                                    $tplType = $tpl['file_type'] ?? 'other';
                                    $tplIcon = $tplType === 'word' ? 'fa-file-word' : 'fa-file-pdf';
                                    ?>
                                    <div class="av-file-card" onclick="openModal(
                                         '<?= htmlspecialchars($tpl['file_path']) ?>',
                                         '<?= htmlspecialchars($tpl['file_name']) ?>',
                                         '<?= $tplType ?>'
                                     )">
                                        <div class="av-file-icon <?= htmlspecialchars($tplType) ?>">
                                            <i class="fa <?= $tplIcon ?>"></i>
                                            <span><?= strtoupper($tplType) ?></span>
                                        </div>
                                        <span class="av-file-badge <?= htmlspecialchars($tplType) ?>">
                                            <i class="fa <?= $tplIcon ?>"></i>
                                            <?= htmlspecialchars($tpl['file_name']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- ══ SUBMISSION AREA ══ -->
                        <div class="av-message-card" id="submissionArea">

                            <input type="file" id="attachFileInput" accept=".pdf,.doc,.docx,.ppt,.pptx"
                                style="display:none">
                            <input type="file" id="attachImageInput" accept="image/*" style="display:none">
                            <input type="file" id="attachVideoInput" accept="video/*" style="display:none">

                            <?php if ($existingSubmission): ?>
                                <!-- ══ STATE: ALREADY SUBMITTED ══
                                 Box  = green tint
                                 Icon = green checkmark
                                 Btn  = RED X  →  click = unsubmit dialog
                            -->
                                <div class="av-message-box submitted" id="msgBox">
                                    <div class="av-msg-left">
                                        <i class="fa fa-check-circle av-msg-status-icon green"></i>
                                        <div class="av-msg-text-col">
                                            <span class="av-msg-title">Assignment Submitted</span>
                                            <span class="av-msg-sub">
                                                Submitted on
                                                <?= date('M j, Y g:i A', strtotime($existingSubmission['submitted_at'] ?? 'now')) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- RED X = unsubmit -->
                                    <button class="av-msg-btn danger" title="Unsubmit" onclick="confirmUnsubmit()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                                <div class="av-message-actions locked">
                                    <button><i class="fa fa-paperclip"></i></button>
                                    <button><i class="fa fa-image"></i></button>
                                    <button><i class="fa fa-film"></i></button>
                                </div>

                            <?php else: ?>
                                <!-- ══ STATE: NOT YET SUBMITTED ══
                                 No file  → grey paper-plane  → click = warn
                                 Has file → GREEN paper-plane → click = SUBMIT
                                 (X never appears here — only after submission)
                            -->
                                <div id="attachPreview" style="display:none; margin:0 20px 10px; padding:10px;
                                        background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;">
                                    <img id="previewImage"
                                        style="display:none; max-width:100%; max-height:200px; border-radius:8px;">
                                    <video id="previewVideo" controls
                                        style="display:none; max-width:100%; max-height:200px; border-radius:8px;"></video>
                                </div>

                                <div class="av-message-box" id="msgBox">
                                    <div class="av-msg-left">
                                        <i class="fa fa-file av-msg-status-icon green" id="msgIcon" style="display:none;"></i>
                                        <div class="av-msg-text-col" id="msgTextCol" style="display:none;">
                                            <span class="av-msg-title" id="msgTitle"></span>
                                        </div>
                                        <span class="av-msg-placeholder" id="msgPlaceholder">No file insert...</span>
                                    </div>
                                    <!--
                                    idle  class = grey  paper-plane (no file)
                                    ready class = green paper-plane (file attached, ready to submit)
                                    danger class = red X (submitted — set by JS after submit, or via PHP above)
                                -->
                                    <button class="av-msg-btn idle" id="msgActionBtn" title="Attach a file first"
                                        onclick="handleMsgBtn()">
                                        <i class="fa fa-paper-plane" id="msgBtnIcon"></i>
                                    </button>
                                </div>

                                <div class="av-message-actions" id="attachActions">
                                    <button title="Attach file" onclick="document.getElementById('attachFileInput').click()">
                                        <i class="fa fa-paperclip"></i>
                                    </button>
                                    <button title="Image" onclick="document.getElementById('attachImageInput').click()">
                                        <i class="fa fa-image"></i>
                                    </button>
                                    <button title="Video" onclick="document.getElementById('attachVideoInput').click()">
                                        <i class="fa fa-film"></i>
                                    </button>
                                </div>

                            <?php endif; ?>
                        </div><!-- /av-message-card -->
                    </div><!-- /av-header-card -->

                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- PDF Modal -->
    <div class="pdf-modal-overlay" id="pdfModalOverlay" onclick="handleOverlayClick(event)">
        <div class="pdf-modal">
            <div class="pdf-modal-header">
                <div class="pdf-modal-header-left">
                    <i class="fa fa-file-pdf" id="pdfModalIcon"></i>
                    <p class="pdf-modal-title" id="pdfModalTitle">Loading...</p>
                </div>
                <div class="pdf-modal-header-right">
                    <a class="pdf-modal-btn" id="pdfDownloadBtn" href="#" download target="_blank">
                        <i class="fa fa-download"></i> Download
                    </a>
                    <button class="pdf-modal-close" onclick="closeModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="pdf-modal-body">
                <iframe class="pdf-modal-iframe" id="pdfModalIframe" src=""></iframe>
            </div>
        </div>
    </div>

    <!-- Unsubmit confirm dialog -->
    <div class="unsubmit-overlay" id="unsubmitOverlay" onclick="if(event.target===this)closeUnsubmitDialog()">
        <div class="unsubmit-dialog">
            <div class="ud-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Unsubmit Assignment?</h4>
            <p>This will delete your submission. You can re-submit afterwards.</p>
            <div class="ud-btns">
                <button class="ud-cancel" onclick="closeUnsubmitDialog()">Cancel</button>
                <button class="ud-confirm" id="unsubmitConfirmBtn" onclick="doUnsubmit()">Yes, Unsubmit</button>
            </div>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="avToast"></div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Toast ──────────────────────────────────────────────────────────────────
        let _toastTimer = null;
        function showToast(msg, type = 'error') {
            const t = document.getElementById('avToast');
            const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warn: 'fa-exclamation-circle' };
            t.innerHTML = '<i class="fa ' + (icons[type] || icons.error) + '"></i> ' + msg;
            t.className = 'show ' + type;
            if (_toastTimer) clearTimeout(_toastTimer);
            _toastTimer = setTimeout(() => {
                t.className = 'hide ' + type;
                setTimeout(() => { t.className = ''; }, 320);
            }, 3500);
        }

        // ── PDF Modal ──────────────────────────────────────────────────────────────
        function openModal(url, name, type) {
            const overlay = document.getElementById('pdfModalOverlay');
            const iframe = document.getElementById('pdfModalIframe');
            const title = document.getElementById('pdfModalTitle');
            const download = document.getElementById('pdfDownloadBtn');
            const icon = document.getElementById('pdfModalIcon');
            const fullPath = '/learning_management/' + url;
            title.textContent = name;
            download.href = fullPath;
            icon.className = type === 'word' ? 'fa fa-file-word' : (type === 'pdf' ? 'fa fa-file-pdf' : 'fa fa-file');
            icon.style.color = type === 'word' ? '#2b579a' : '#dc2626';
            iframe.src = (type === 'word' || type === 'powerpoint')
                ? 'https://docs.google.com/gview?url=' + encodeURIComponent(window.location.origin + fullPath) + '&embedded=true'
                : fullPath;
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('pdfModalOverlay').classList.remove('open');
            document.getElementById('pdfModalIframe').src = '';
            document.body.style.overflow = '';
        }
        function handleOverlayClick(e) {
            if (e.target === document.getElementById('pdfModalOverlay')) closeModal();
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── Submission logic (only injected when NOT yet submitted) ────────────────
        <?php if (!$existingSubmission): ?>

            let attachedFile = null;
            let attachedType = null;
            let isSubmitting = false;

            const msgBox = document.getElementById('msgBox');
            const msgIcon = document.getElementById('msgIcon');
            const msgTextCol = document.getElementById('msgTextCol');
            const msgTitle = document.getElementById('msgTitle');
            const msgPlaceholder = document.getElementById('msgPlaceholder');
            const msgActionBtn = document.getElementById('msgActionBtn');
            const msgBtnIcon = document.getElementById('msgBtnIcon');
            const attachActions = document.getElementById('attachActions');

            // File picker listeners
            document.getElementById('attachFileInput').addEventListener('change', function () {
                if (this.files[0]) setFile(this.files[0], 'file');
            });
            document.getElementById('attachImageInput').addEventListener('change', function () {
                if (this.files[0]) setFile(this.files[0], 'image');
            });
            document.getElementById('attachVideoInput').addEventListener('change', function () {
                if (this.files[0]) setFile(this.files[0], 'video');
            });

            function setFile(file, type) {
                attachedFile = file;
                attachedType = type;

                // Box turns green tint
                msgBox.classList.add('has-file');
                msgBox.style.border = '';
                msgBox.style.animation = '';

                // Show file icon + name inside box
                msgPlaceholder.style.display = 'none';
                msgIcon.style.display = 'block';
                msgTextCol.style.display = 'flex';
                msgTitle.textContent = file.name;

                if (type === 'image') msgIcon.className = 'fa fa-image av-msg-status-icon green';
                else if (type === 'video') msgIcon.className = 'fa fa-film  av-msg-status-icon green';
                else msgIcon.className = 'fa fa-file-pdf av-msg-status-icon green';

                // ★ Button → GREEN paper-plane (ready to submit)
                //   NOT red X — that only appears after submission
                msgBtnIcon.className = 'fa fa-paper-plane';
                msgActionBtn.classList.remove('idle', 'danger');
                msgActionBtn.classList.add('ready');
                msgActionBtn.title = 'Submit assignment';

                // Show image/video thumbnail strip
                showPreviewStrip(file, type);
            }

            function showPreviewStrip(file, type) {
                const strip = document.getElementById('attachPreview');
                const img = document.getElementById('previewImage');
                const vid = document.getElementById('previewVideo');
                img.style.display = 'none';
                vid.style.display = 'none';

                if (type === 'image') {
                    const reader = new FileReader();
                    reader.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
                    reader.readAsDataURL(file);
                    strip.style.display = 'block';
                } else if (type === 'video') {
                    vid.src = URL.createObjectURL(file);
                    vid.style.display = 'block';
                    strip.style.display = 'block';
                } else {
                    strip.style.display = 'none';
                }
            }

            function clearFile() {
                attachedFile = null; attachedType = null;

                msgBox.classList.remove('has-file');
                msgBox.style.border = '';
                msgBox.style.animation = '';

                msgPlaceholder.style.display = 'block';
                msgPlaceholder.textContent = 'No file insert...';
                msgIcon.style.display = 'none';
                msgTextCol.style.display = 'none';
                msgTitle.textContent = '';

                // Back to grey idle paper-plane
                msgBtnIcon.className = 'fa fa-paper-plane';
                msgActionBtn.classList.remove('ready', 'danger');
                msgActionBtn.classList.add('idle');
                msgActionBtn.title = 'Attach a file first';

                document.getElementById('attachPreview').style.display = 'none';
                document.getElementById('previewImage').src = '';
                document.getElementById('previewVideo').src = '';
                ['attachFileInput', 'attachImageInput', 'attachVideoInput']
                    .forEach(id => document.getElementById(id).value = '');
            }

            function handleMsgBtn() {
                if (isSubmitting) return;

                if (attachedFile) {
                    // GREEN paper-plane clicked with file ready → SUBMIT
                    doSubmit();
                } else {
                    // GREY paper-plane clicked with no file → warn
                    msgBox.style.border = '2px solid #ef4444';
                    msgBox.style.animation = 'none';
                    setTimeout(() => { msgBox.style.animation = 'shake .4s ease'; }, 10);
                    msgPlaceholder.textContent = 'Please attach a file first!';
                    showToast('Please attach a file before submitting.', 'warn');
                    setTimeout(() => { msgPlaceholder.textContent = 'No file insert...'; }, 3000);
                    setTimeout(() => { msgBox.style.border = ''; }, 3000);
                }
            }

            function doSubmit() {
                isSubmitting = true;
                msgActionBtn.disabled = true;
                msgBtnIcon.className = 'fa fa-spinner fa-spin';

                const formData = new FormData();
                formData.append('assignment_id', '<?= $assignment["id"] ?>');
                formData.append('comment', '');
                formData.append('submission_file', attachedFile);
                formData.append('file_type', attachedType);

                fetch('/learning_management/public/?url=submit_assignment', { method: 'POST', body: formData })
                    .then(r => r.text())
                    .then(text => {
                        let data;
                        try { data = JSON.parse(text); }
                        catch (e) {
                            showToast('Server error. Please try again.', 'error');
                            resetToReady(); return;
                        }
                        if (data.success) {
                            renderSubmittedState('Just now');
                            showToast('Assignment submitted successfully!', 'success');
                        } else {
                            showToast(data.message || 'Submission failed. Please try again.', 'error');
                            resetToReady();
                        }
                    })
                    .catch(() => {
                        showToast('Network error. Please check your connection.', 'error');
                        resetToReady();
                    });
            }

            function resetToReady() {
                // File still attached — keep green paper-plane
                isSubmitting = false;
                msgActionBtn.disabled = false;
                msgBtnIcon.className = 'fa fa-paper-plane';
                msgActionBtn.classList.remove('idle', 'danger');
                msgActionBtn.classList.add('ready');
            }

            function renderSubmittedState(dateStr) {
                document.getElementById('attachPreview').style.display = 'none';

                // Box → submitted state
                msgBox.classList.remove('has-file');
                msgBox.classList.add('submitted');

                // Left: green checkmark + "Assignment Submitted"
                msgIcon.className = 'fa fa-check-circle av-msg-status-icon green';
                msgIcon.style.display = 'block';
                msgTextCol.style.display = 'flex';
                msgTitle.textContent = 'Assignment Submitted';

                let sub = msgTextCol.querySelector('.av-msg-sub');
                if (!sub) {
                    sub = document.createElement('span');
                    sub.className = 'av-msg-sub';
                    msgTextCol.appendChild(sub);
                }
                sub.textContent = 'Submitted on ' + dateStr;
                msgPlaceholder.style.display = 'none';

                // ★ NOW the button becomes RED X (unsubmit)
                msgActionBtn.disabled = false;
                msgActionBtn.classList.remove('idle', 'ready');
                msgActionBtn.classList.add('danger');
                msgActionBtn.title = 'Unsubmit';
                msgBtnIcon.className = 'fa fa-times';
                isSubmitting = false;

                // Wire X to unsubmit dialog
                msgActionBtn.onclick = confirmUnsubmit;

                // Lock attachment row
                attachActions.classList.add('locked');
            }

        <?php endif; ?>

        // ── Unsubmit ───────────────────────────────────────────────────────────────
        function confirmUnsubmit() {
            document.getElementById('unsubmitOverlay').classList.add('open');
        }
        function closeUnsubmitDialog() {
            document.getElementById('unsubmitOverlay').classList.remove('open');
        }
        function doUnsubmit() {
            const btn = document.getElementById('unsubmitConfirmBtn');
            btn.disabled = true;
            btn.textContent = 'Removing...';

            const formData = new FormData();
            formData.append('assignment_id', '<?= $assignment["id"] ?? 0 ?>');

            fetch('/learning_management/public/?url=unsubmit_assignment', { method: 'POST', body: formData })
                .then(r => r.text())
                .then(text => {
                    let data;
                    try { data = JSON.parse(text); }
                    catch (e) {
                        closeUnsubmitDialog();
                        showToast('Server error. Please try again.', 'error');
                        btn.disabled = false; btn.textContent = 'Yes, Unsubmit';
                        return;
                    }
                    if (data.success) {
                        closeUnsubmitDialog();
                        showToast('Submission removed. You can re-submit.', 'success');
                        renderUnsubmittedState(); // ← instead of window.location.reload()
                    } else {
                        closeUnsubmitDialog();
                        showToast(data.message || 'Could not unsubmit. Please try again.', 'error');
                        btn.disabled = false; btn.textContent = 'Yes, Unsubmit';
                    }
                })
                .catch(() => {
                    closeUnsubmitDialog();
                    showToast('Network error. Please check your connection.', 'error');
                    btn.disabled = false; btn.textContent = 'Yes, Unsubmit';
                });
        }

        function renderUnsubmittedState() {
            const msgBox = document.getElementById('msgBox');
            const msgIcon = document.getElementById('msgIcon');
            const msgTextCol = document.getElementById('msgTextCol');
            const msgTitle = document.getElementById('msgTitle');
            const msgPlaceholder = document.getElementById('msgPlaceholder');
            const msgActionBtn = document.getElementById('msgActionBtn');
            const msgBtnIcon = document.getElementById('msgBtnIcon');
            const attachActions = document.getElementById('attachActions');

            // Restore box to "has-file" green tint (file is still attached)
            msgBox.classList.remove('submitted');
            msgBox.classList.add('has-file');

            // Restore left side: file icon + filename
            msgIcon.className = 'fa fa-file-pdf av-msg-status-icon green';
            msgIcon.style.display = 'block';
            msgTextCol.style.display = 'flex';
            msgTitle.textContent = attachedFile ? attachedFile.name : '';

            // Remove the "Submitted on..." sub-label if it exists
            const sub = msgTextCol.querySelector('.av-msg-sub');
            if (sub) sub.remove();

            msgPlaceholder.style.display = 'none';

            // Button → GREEN paper-plane (ready to re-submit)
            msgActionBtn.classList.remove('idle', 'danger');
            msgActionBtn.classList.add('ready');
            msgActionBtn.title = 'Submit assignment';
            msgActionBtn.disabled = false;
            msgActionBtn.onclick = handleMsgBtn; // restore original handler
            msgBtnIcon.className = 'fa fa-paper-plane';

            // Unlock attachment row
            attachActions.classList.remove('locked');

            isSubmitting = false;
        }

    </script>
</body>

</html>