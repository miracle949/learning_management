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
            margin-bottom: 1.4rem;
        }

        .av-header-top {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            padding: 2rem 1.8rem;
        }

        .av-header-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background: #fff7ed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d97706;
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

        .av-date {
            font-size: 12px;
            color: #aaa;
            display: block;
            margin-top: 4px;
        }

        .av-due-badge {
            display: inline-block;
            background: #fef3c7;
            color: #d97706;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            margin-top: 10px;
        }

        .av-desc-card {
            padding: 1.5rem 1.8rem 0.5rem 1.8rem;
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
            border-left: 4px solid #d97706;
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
            transition: opacity .18s, transform .18s;
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

        .av-file-label {
            font-size: 12px;
            color: #555;
            text-align: center;
        }

        .av-file-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
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

        .av-message-card {
            margin-top: 3rem;
        }

        .av-message-box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 16px;
            margin: 0 20px;
            background: #fafafa;
        }

        .av-message-box input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            color: #333;
            font-family: inherit;
        }

        .av-message-box button {
            background: var(--green);
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
        }

        .av-message-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.2rem;
            margin-top: 1rem;
            padding: 20px 50px;
            border-top: 1px solid #ddd;
        }

        .av-message-actions button {
            background: none;
            border: none;
            color: #aaa;
            font-size: 22px;
            cursor: pointer;
            transition: color .18s;
            padding: 0;
        }

        .av-message-actions button:hover {
            color: var(--green);
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

        /* ── PDF MODAL (same as module_view) ── */
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
            color: #ffffff;
            margin: 0;
        }

        .pdf-modal-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pdf-modal-btn {
            background: rgba(255, 255, 255, 0.1);
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
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .pdf-modal-close {
            background: rgba(255, 255, 255, 0.1);
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
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
        <?php include("../components/navbar.php"); ?>
        <?php include("../components/sidebar.php"); ?>

        <div class="rightbar">
            <div class="av-page">

                <?php
                // $assignment  — from StudentsController::assignment_view()
                // $templates   — from StudentsController::assignment_view()
                // $subjectSlug — from StudentsController::assignment_view()
                ?>

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

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($assignment['slug']) ?>"
                        class="av-back-link">
                        <i class="fa fa-arrow-left"></i> Back to <?= htmlspecialchars($assignment['subject_name']) ?>
                    </a>

                    <div class="av-header-card">
                        <div class="av-header-top">
                            <div class="av-header-icon">
                                <i class="fa fa-file-alt"></i>
                            </div>
                            <div class="av-header-info">
                                <small>New Assignment</small>
                                <h2><?= htmlspecialchars($assignment['title']) ?></h2>
                                <span class="av-date">Date Received:
                                    <?= date('M j', strtotime($assignment['posted_at'])) ?></span>
                                <?php if (!empty($assignment['due_date'])): ?>
                                    <span class="av-due-badge">
                                        <i class="fa fa-calendar-alt"></i>
                                        Due Date: <?= date('M j, Y', strtotime($assignment['due_date'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="av-desc-card">
                            <h5>Description</h5>
                            <p><?= htmlspecialchars($assignment['description']) ?></p>
                        </div>

                        <!-- Template Files — clicking opens modal instead of new tab -->
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
                                        <span class="av-file-label">
                                            <?= htmlspecialchars($tpl['file_name']) ?>
                                        </span>
                                        <span class="av-file-badge <?= htmlspecialchars($tplType) ?>">
                                            <i class="fa <?= $tplIcon ?>"></i>
                                            <?= htmlspecialchars($tpl['file_name']) ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="av-message-card">
                            <div class="av-message-box">
                                <input type="text" id="assignMsgInput" placeholder="No attachment file..." disabled>
                                <button title="Send"><i class="fa fa-paper-plane"></i></button>
                            </div>
                            <div class="av-message-actions">
                                <button title="Attach file"><i class="fa fa-paperclip"></i></button>
                                <button title="Image"><i class="fa fa-image"></i></button>
                                <button title="Video"><i class="fa fa-film"></i></button>
                                <button title="Emoji"><i class="fa fa-smile"></i></button>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- ── PDF MODAL ── -->
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

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script>
        function openModal(url, name, type) {
            const overlay = document.getElementById('pdfModalOverlay');
            const iframe = document.getElementById('pdfModalIframe');
            const title = document.getElementById('pdfModalTitle');
            const download = document.getElementById('pdfDownloadBtn');
            const icon = document.getElementById('pdfModalIcon');

            title.textContent = name;
            download.href = url;

            // Change icon color based on file type
            icon.className = type === 'word'
                ? 'fa fa-file-word'
                : (type === 'pdf' ? 'fa fa-file-pdf' : 'fa fa-file');
            icon.style.color = type === 'word' ? '#2b579a' : '#dc2626';

            // Word/PowerPoint → Google Docs viewer
            // PDF and others  → load directly
            if (type === 'word' || type === 'powerpoint') {
                iframe.src = 'https://docs.google.com/gview?url='
                    + encodeURIComponent(window.location.origin + url)
                    + '&embedded=true';
            } else {
                iframe.src = url;
            }

            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const overlay = document.getElementById('pdfModalOverlay');
            const iframe = document.getElementById('pdfModalIframe');
            overlay.classList.remove('open');
            iframe.src = '';
            document.body.style.overflow = '';
        }

        // Close when clicking dark overlay outside the modal
        function handleOverlayClick(e) {
            if (e.target === document.getElementById('pdfModalOverlay')) {
                closeModal();
            }
        }

        // Close with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>