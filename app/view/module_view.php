<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module View</title>
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../css_folder/subjects.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        .mv-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--green);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .mv-back-link:hover {
            text-decoration: underline;
        }

        .mv-header-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
            border-radius: 50%;
            background: #e8f5ee;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green);
            font-size: 22px;
        }

        .mv-header-info small {
            font-size: 12px;
            /* color: #aaa; */
            color: #1a1a1a;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .mv-header-info h2 {
            font-size: 20px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 4px;
        }

        .mv-header-info p {
            font-size: 13.5px;
            /* color: #555; */
            color: #1a1a1a;
            margin: 0 0 2px;
        }

        .mv-date {
            font-size: 12px;
            /* color: #aaa; */
            color: #1a1a1a;
            margin-top: 4px;
            display: block;
        }

        .mv-attachments-card h5 {
            font-size: 14px;
            font-weight: 800;
            color: #333;
            margin-bottom: 1.2rem;
        }

        .mv-att-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            width: 140px;
        }

        .mv-att-card:hover .mv-att-icon {
            opacity: .85;
            transform: translateY(-2px);
        }

        .mv-att-icon {
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

        .mv-att-icon.pdf {
            background: #b0b0b0;
            color: #fff;
        }

        .mv-att-icon.image {
            background: #e8f5ee;
            color: var(--green);
        }

        .mv-att-icon.video {
            background: #1a1a2e;
            color: #fff;
        }

        .mv-att-icon.powerpoint {
            background: #d95700;
            color: #fff;
        }

        .mv-att-icon.word {
            background: #2b579a;
            color: #fff;
        }

        .mv-att-icon.other {
            background: #f5f6fa;
            color: #555;
        }

        .mv-att-icon span {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .mv-att-label {
            font-size: 12px;
            color: #555;
            text-align: center;
        }

        .mv-att-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .mv-att-badge.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .mv-att-badge.image {
            background: #dcfce7;
            color: #16a34a;
        }

        .mv-att-badge.video {
            background: #dbeafe;
            color: #2563eb;
        }

        .mv-att-badge.powerpoint {
            background: #fff0e6;
            color: #d95700;
        }

        .mv-att-badge.word {
            background: #e6f0ff;
            color: #2563eb;
        }

        .mv-not-found {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }

        .mv-not-found i {
            font-size: 48px;
            display: block;
            margin-bottom: 14px;
        }

        /* ── PDF MODAL ── */
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
            <div class="mv-page">

                <?php
                // $module      — from StudentsController::module_view()
                // $attachments — from StudentsController::module_view()
                // $subjectSlug — from StudentsController::module_view()
                ?>

                <?php if (!$module): ?>
                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="mv-back-link">
                        <i class="fa fa-arrow-left"></i> Back to Subject
                    </a>
                    <div class="mv-not-found">
                        <i class="fa fa-folder-open"></i>
                        <p>Module not found.</p>
                    </div>

                <?php else: ?>

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($module['slug']) ?>"
                        class="mv-back-link">
                        <i class="fa fa-arrow-left"></i> Back to <?= htmlspecialchars($module['subject_name']) ?>
                    </a>

                    <div class="mv-header-card">
                        <div class="mv-parent-icon">
                            <div class="mv-header-icon">
                                <i class="fa fa-layer-group"></i>
                            </div>
                            <div class="mv-header-info">
                                <small>New Material</small>
                                <h2><?= htmlspecialchars($module['title']) ?></h2>
                                <p><?= htmlspecialchars($module['description']) ?></p>
                                <span class="mv-date">Date Received:
                                    <?= date('M j', strtotime($module['posted_at'])) ?></span>
                            </div>
                        </div>

                        <hr>

                        <div class="mv-attachments-card">
                            <h5>Attachments</h5>
                            <?php if (empty($attachments)): ?>
                                <p style="color:#aaa;font-size:13px;padding-bottom:1rem;">No attachments yet.</p>
                            <?php else: ?>
                                <div class="mv-attachments-grid">
                                    <?php foreach ($attachments as $att):
                                        $type = $att['file_type'] ?? 'other';
                                        $iconMap = [
                                            'pdf' => 'fa-file-pdf',
                                            'image' => 'fa-image',
                                            'video' => 'fa-film',
                                            'powerpoint' => 'fa-file-powerpoint',
                                            'word' => 'fa-file-word',
                                            'other' => 'fa-file',
                                        ];
                                        $icon = $iconMap[$type] ?? 'fa-file';
                                        ?>
                                        <div class="mv-att-card" onclick="openModal(
                                                '<?= htmlspecialchars($att['file_path']) ?>',
                                                '<?= htmlspecialchars($att['file_name']) ?>',
                                                '<?= $type ?>'
                                            )">
                                            <div class="mv-att-icon <?= $type ?>">
                                                <i class="fa <?= $icon ?>"></i>
                                                <span><?= strtoupper($type) ?></span>
                                            </div>
                                            <span class="mv-att-label">
                                                <?= htmlspecialchars($att['file_name']) ?>
                                            </span>
                                            <span class="mv-att-badge <?= $type ?>">
                                                <i class="fa <?= $icon ?>"></i>
                                                <?= htmlspecialchars($att['file_name']) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

    <!-- ── PDF MODAL ── -->
    <div class="pdf-modal-overlay" id="pdfModalOverlay" onclick="handleOverlayClick(event)">
        <div class="pdf-modal">

            <!-- Modal Header -->
            <div class="pdf-modal-header">
                <div class="pdf-modal-header-left">
                    <i class="fa fa-file-pdf"></i>
                    <p class="pdf-modal-title" id="pdfModalTitle">Loading...</p>
                </div>
                <div class="pdf-modal-header-right">
                    <!-- Download button -->
                    <a class="pdf-modal-btn" id="pdfDownloadBtn" href="#" download target="_blank">
                        <i class="fa fa-download"></i> Download
                    </a>
                    <!-- Close button -->
                    <button class="pdf-modal-close" onclick="closeModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body — PDF iframe -->
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

            title.textContent = name;
            download.href = url;

            // Word/PowerPoint → use Google Docs viewer
            // PDF and others  → load directly
            if (type === 'word' || type === 'powerpoint') {
                iframe.src = 'https://docs.google.com/gview?url='
                    + encodeURIComponent(window.location.origin + url)
                    + '&embedded=true';
            } else {
                iframe.src = url;
            }

            overlay.classList.add('open');
            document.body.style.overflow = 'hidden'; // prevent background scroll
        }

        function closeModal() {
            const overlay = document.getElementById('pdfModalOverlay');
            const iframe = document.getElementById('pdfModalIframe');
            overlay.classList.remove('open');
            iframe.src = ''; // stop loading
            document.body.style.overflow = ''; // restore scroll
        }

        // Close modal when clicking the dark overlay (outside the modal box)
        function handleOverlayClick(e) {
            if (e.target === document.getElementById('pdfModalOverlay')) {
                closeModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>