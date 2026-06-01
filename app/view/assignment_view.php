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
            width: 100%;
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

        .av-header-info {
            width: 100%;
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
            margin: 0 0 0px;
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
            cursor: pointer;
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
            overflow-x: hidden;
            width: 140px;
        }

        /* ─── Submission area ─── */
        .av-message-card {
            margin-top: 2rem;
            /* padding-bottom: 2rem; */
        }

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

        .av-msg-btn.idle {
            background: #c0c0c0;
        }

        .av-msg-btn.ready {
            background: var(--green);
        }

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

        /* ─── Submitted file preview card ─── */
        .av-submitted-file-section {
            margin: 0 1.8rem 1.5rem;
        }

        .av-submitted-file-label {
            font-size: 12px;
            font-weight: 700;
            color: #166534;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .av-submitted-file-card {
            display: inline-flex;
            flex-direction: column;
            align-items: left;
            gap: 8px;
            cursor: pointer;
        }

        .av-submitted-file-card:hover .av-file-icon {
            opacity: .85;
            transform: translateY(-2px);
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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

        .av-message-actions.locked #msgToggleBtn {
            opacity: 1 !important;
            pointer-events: auto !important;
            color: var(--green, #4CAF7D);
        }

        .rightbar {
            padding: 1.8rem 1.4rem 1.8rem 1.4rem;
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

                    <?php
                    $dueDT = '';
                    if (!empty($assignment['due_date'])) {
                        $dueDT = $assignment['due_date'];
                        if (!empty($assignment['due_time'])) {
                            $dueDT .= ' ' . $assignment['due_time'];
                        }
                    }
                    $isOverdue = $dueDT && strtotime($dueDT) < time();
                    $submittedWhileOverdue = $isOverdue && $existingSubmission;
                    $cannotResubmit = $isOverdue;
                    ?>

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="av-back-link">
                        <i class="fa fa-arrow-left"></i> Back to Subject
                    </a>

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
                                        <?php if ($existingSubmission && isset($existingSubmission['points_earned']) && $existingSubmission['points_earned'] !== null): ?>
                                            <?php
                                            $percent = $assignment['points'] > 0
                                                ? ($existingSubmission['points_earned'] / $assignment['points']) * 100
                                                : 0;
                                            $scoreColor = $percent >= 75 ? '#4CAF7D' : '#C82525';
                                            ?>
                                            <span style="color:<?= $scoreColor ?>; font-size:15px; font-weight:600;">
                                                <?= (int) $existingSubmission['points_earned'] ?>
                                            </span>
                                            <span style="color:#aaa; font-size:15px; font-weight:600;">
                                                / <?= htmlspecialchars($assignment['points']) ?> pts
                                            </span>
                                        <?php else: ?>
                                            <?= htmlspecialchars($assignment['points']) ?> pts
                                        <?php endif; ?>
                                    </span>
                                    <div class="due-date">
                                        <?php if (!empty($assignment['due_date'])): ?>
                                            <?php
                                            $dueDateTime = $assignment['due_date'];
                                            if (!empty($assignment['due_time'])) {
                                                $dueDateTime .= ' ' . $assignment['due_time'];
                                            }
                                            $dueTimestamp = strtotime($dueDateTime);
                                            $isOverdue = $dueTimestamp < time();
                                            ?>
                                            <span class="av-due-badge"
                                                style="color: <?= $isOverdue ? '#ef4444' : 'var(--green)' ?>;">
                                                <i class="fa fa-<?= $isOverdue ? 'clock' : 'calendar-alt' ?>"></i>
                                                Due: <?= date('M j, Y', $dueTimestamp) ?> at <?= date('g:i A', $dueTimestamp) ?>
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

                            <?php if ($existingSubmission):

                                /*
                                 * Resolve the submitted file path.
                                 * Try common column names: file_path, submission_file, filename
                                 */
                                $subRawPath = $existingSubmission['file_path']
                                    ?? $existingSubmission['submission_file']
                                    ?? $existingSubmission['file_name']
                                    ?? $existingSubmission['filename']
                                    ?? $existingSubmission['filepath']
                                    ?? '';

                                $subFileName = $subRawPath ? basename($subRawPath) : '';
                                $subExt = strtolower(pathinfo($subFileName, PATHINFO_EXTENSION));
                                $subType = in_array($subExt, ['doc', 'docx'])
                                    ? 'word'
                                    : (in_array($subExt, ['ppt', 'pptx']) ? 'powerpoint' : 'pdf');
                                $subIcon = $subType === 'word'
                                    ? 'fa-file-word'
                                    : ($subType === 'powerpoint' ? 'fa-file-powerpoint' : 'fa-file-pdf');
                                ?>

                                <!-- ══ SUBMITTED STATE ══ -->

                                <?php if (!empty($subRawPath)): ?>
                                    <!-- Your submitted file card — clickable, opens in modal -->
                                    <div class="av-submitted-file-section" id="submittedFileSection">
                                        <p class="av-submitted-file-label">
                                            <i class="fa fa-check-circle"></i> Your Submitted File
                                        </p>
                                        <div class="av-submitted-file-card" onclick="openModal(
                                        '<?= htmlspecialchars($subRawPath) ?>',
                                        '<?= htmlspecialchars($subFileName) ?>',
                                        '<?= $subType ?>'
                                    )">
                                            <div class="av-file-icon <?= htmlspecialchars($subType) ?>">
                                                <i class="fa <?= $subIcon ?>"></i>
                                                <span><?= strtoupper($subType) ?></span>
                                            </div>
                                            <span class="av-file-badge <?= htmlspecialchars($subType) ?>">
                                                <i class="fa <?= $subIcon ?>"></i>
                                                <?= htmlspecialchars($subFileName) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>

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
                                    <?php if (!$isOverdue): ?>
                                        <!-- Only allow unsubmit if NOT overdue -->
                                        <button class="av-msg-btn danger" title="Unsubmit" onclick="confirmUnsubmit()">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    <?php else: ?>
                                        <!-- Overdue: lock the unsubmit button -->
                                        <button class="av-msg-btn" title="Cannot unsubmit after due date"
                                            style="background:#d1d5db;cursor:not-allowed;" disabled>
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <?php
                                // TEMPORARY DEBUG — remove after fixing
                                error_log('SUBMISSION DATA: ' . json_encode($existingSubmission));
                                // OR show in HTML source:
                                echo '<!-- ' . htmlspecialchars(json_encode($existingSubmission)) . ' -->';
                                ?>

                            <?php else: ?>

                                <!-- ══ NOT SUBMITTED STATE ══ -->

                                <?php if ($isOverdue): ?>
                                    <!-- ══ OVERDUE — cannot submit ══ -->
                                    <div class="av-message-box" style="background:#fff5f5; border-color:#fecaca; margin-bottom:0;">
                                        <div class="av-msg-left">
                                            <i class="fa fa-clock av-msg-status-icon" style="color:#ef4444;"></i>
                                            <div class="av-msg-text-col">
                                                <span class="av-msg-title" style="color:#dc2626;">Assignment Overdue</span>
                                                <span class="av-msg-sub">
                                                    Due date was <?= date('M j, Y', strtotime($dueDT)) ?> at
                                                    <?= date('g:i A', strtotime($dueDT)) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>


                                <?php else: ?>
                                    <!-- ══ NORMAL — can submit ══ -->
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
                                        <button class="av-msg-btn idle" id="msgActionBtn" title="Attach a file first"
                                            onclick="handleMsgBtn()">
                                            <i class="fa fa-paper-plane" id="msgBtnIcon"></i>
                                        </button>
                                    </div>

                                <?php endif; ?>


                            <?php endif; ?>

                            <!-- MESSAGE BOX — always rendered, toggled by JS -->
                            <div id="msgBoxWrapper" style="display:none; margin: 10px 20px 0;">
                                <div style="background:#fff; border:1px solid rgba(0,0,0,0.1); border-radius:10px;
                                    padding:10px 14px; display:flex; align-items:center; gap:10px;">
                                    <input type="text" id="msgInput" placeholder="Message ..."
                                        style="flex:1; border:none; outline:none; font-size:14px; color:#333; background:transparent;">
                                    <button class="av-msg-btn ready" title="Send message" id="msgSendBtn"
                                        onclick="sendMessage()">
                                        <i class="fa fa-paper-plane" id="msgSendIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <?php $lockAttach = $existingSubmission || $isOverdue; ?>

                            <div
                                style="display:flex; margin-top:1rem; border-radius:12px; border:1px solid rgba(0,0,0,0.1); background-color:#F0F0F0; overflow:hidden;">

                                <!-- Message button — ALWAYS clickable, never affected by lock -->
                                <button id="msgToggleBtn" title="Message" onclick="toggleMessageBox()"
                                    style="background:none; border:none; border-right:1px solid rgba(0,0,0,0.08); color:var(--green, #4CAF7D); font-size:23px; cursor:pointer; padding:10px 30px; flex-shrink:0; transition:background .15s;">
                                    <i class=" fa fa-comment"></i>
                                </button>

                                <!-- File/Image/Video buttons — disabled when submitted or overdue -->
                                <div id="attachActions"
                                    style="display:flex; flex:1; justify-content:space-around; align-items:center; padding:10px 20px; <?= $lockAttach ? 'opacity:.35; pointer-events:none;' : '' ?>">
                                    <button <?= !$lockAttach ? 'title="Attach file" onclick="document.getElementById(\'attachFileInput\').click()"' : 'title="Submissions closed"' ?>
                                        style="background:none; border:none; color:var(--green); font-size:23px; <?= $lockAttach ? 'cursor:not-allowed;' : 'cursor:pointer;' ?> padding:0;">
                                        <i class="fa fa-paperclip"></i>
                                    </button>
                                    <button <?= !$lockAttach ? 'title="Image" onclick="document.getElementById(\'attachImageInput\').click()"' : 'title="Submissions closed"' ?>
                                        style="background:none; border:none; color:var(--green); font-size:23px; <?= $lockAttach ? 'cursor:not-allowed;' : 'cursor:pointer;' ?> padding:0;">
                                        <i class="fa fa-image"></i>
                                    </button>
                                    <button <?= !$lockAttach ? 'title="Video" onclick="document.getElementById(\'attachVideoInput\').click()"' : 'title="Submissions closed"' ?>
                                        style="background:none; border:none; color:var(--green); font-size:23px; <?= $lockAttach ? 'cursor:not-allowed;' : 'cursor:pointer;' ?> padding:0;">
                                        <i class="fa fa-film"></i>
                                    </button>
                                </div>
                            </div>

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
        // ── Toast ─────────────────────────────────────────────────────────────────
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

        // ── Toggle Message Box ────────────────────────────────────────────────────
        let msgBoxVisible = false;
        function toggleMessageBox() {
            const wrapper = document.getElementById('msgBoxWrapper');
            const btn = document.getElementById('msgToggleBtn');
            if (!wrapper) return;
            msgBoxVisible = !msgBoxVisible;
            if (msgBoxVisible) {
                wrapper.style.display = 'block';
                wrapper.style.animation = 'fadeInDown .2s ease';
                btn.style.color = 'var(--green)';
                btn.style.background = 'rgba(0,201,80,0.1)';
                btn.style.borderRadius = '8px';
                setTimeout(() => { const i = document.getElementById('msgInput'); if (i) i.focus(); }, 50);
            } else {
                wrapper.style.display = 'none';
                btn.style.color = 'var(--green, #4CAF7D)';
                btn.style.background = '';
            }
        }

        // ── PDF Modal ─────────────────────────────────────────────────────────────
        function openModal(url, name, type) {
            const overlay = document.getElementById('pdfModalOverlay');
            const iframe = document.getElementById('pdfModalIframe');
            const title = document.getElementById('pdfModalTitle');
            const download = document.getElementById('pdfDownloadBtn');
            const icon = document.getElementById('pdfModalIcon');

            // Build the full server path.
            // If the stored path already starts with '/' treat it as absolute,
            // otherwise prefix with /learning_management/
            const fullPath = url.startsWith('/') ? url : '/learning_management/' + url;

            title.textContent = name;
            download.href = fullPath;
            download.setAttribute('download', name);

            icon.className = type === 'word' ? 'fa fa-file-word'
                : (type === 'pdf' ? 'fa fa-file-pdf'
                    : 'fa fa-file');
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

        // ── Send Message ──────────────────────────────────────────────────────────
        function sendMessage() {
            const input = document.getElementById('msgInput');
            const icon = document.getElementById('msgSendIcon');
            if (!input) return;

            const text = input.value.trim();
            if (!text) {
                input.style.outline = '2px solid #ef4444';
                input.placeholder = 'Please type a message first!';
                setTimeout(() => { input.style.outline = ''; input.placeholder = 'Message ...'; }, 2500);
                return;
            }

            icon.className = 'fa fa-spinner fa-spin';
            document.getElementById('msgSendBtn').disabled = true;

            const fd = new FormData();
            fd.append('assignment_id', '<?= $assignment["id"] ?? 0 ?>');
            fd.append('message', text);

            fetch('/learning_management/public/?url=send_assignment_message', { method: 'POST', body: fd })
                .then(r => r.text())
                .then(raw => {
                    let data;
                    try { data = JSON.parse(raw); } catch (e) { data = { success: false }; }
                    if (data.success) { input.value = ''; showToast('Message sent!', 'success'); }
                    else { showToast(data.message || 'Could not send message.', 'error'); }
                })
                // .catch(() => showToast('Network error. Please check your connection.', 'error'))
                .catch(() => showToast('Submission removed. You can re-submit..', 'error'))
                .finally(() => {
                    icon.className = 'fa fa-paper-plane';
                    document.getElementById('msgSendBtn').disabled = false;
                });
        }

        document.getElementById('msgInput').addEventListener('keydown', function (e) {
            if (e.key === 'Enter') sendMessage();
        });

        // ── Submission logic (only injected when NOT yet submitted) ───────────────
        <?php if (!$existingSubmission): ?>

            let attachedFile = null;
            let attachedType = null;
            let isSubmitting = false;

            window.msgBox = document.getElementById('msgBox');
            window.msgIcon = document.getElementById('msgIcon');
            window.msgTextCol = document.getElementById('msgTextCol');
            window.msgTitle = document.getElementById('msgTitle');
            window.msgPlaceholder = document.getElementById('msgPlaceholder');
            window.msgActionBtn = document.getElementById('msgActionBtn');
            window.msgBtnIcon = document.getElementById('msgBtnIcon');
            window.attachActions = document.getElementById('attachActions');

            function bindFileInputs() {
                document.getElementById('attachFileInput').addEventListener('change', function () {
                    if (this.files[0]) setFile(this.files[0], 'file');
                });
                document.getElementById('attachImageInput').addEventListener('change', function () {
                    if (this.files[0]) setFile(this.files[0], 'image');
                });
                document.getElementById('attachVideoInput').addEventListener('change', function () {
                    if (this.files[0]) setFile(this.files[0], 'video');
                });
            }
            bindFileInputs();

            function setFile(file, type) {
                attachedFile = file;
                attachedType = type;
                msgBox.classList.add('has-file');
                msgBox.style.border = '';
                msgBox.style.animation = '';
                msgPlaceholder.style.display = 'none';
                msgIcon.style.display = 'block';
                msgTextCol.style.display = 'flex';
                msgTitle.textContent = file.name;
                if (type === 'image') msgIcon.className = 'fa fa-image    av-msg-status-icon green';
                else if (type === 'video') msgIcon.className = 'fa fa-film     av-msg-status-icon green';
                else msgIcon.className = 'fa fa-file-pdf av-msg-status-icon green';
                msgBtnIcon.className = 'fa fa-paper-plane';
                msgActionBtn.classList.remove('idle', 'danger');
                msgActionBtn.classList.add('ready');
                msgActionBtn.title = 'Submit assignment';
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

            function handleMsgBtn() {
                if (isSubmitting) return;
                if (attachedFile) {
                    doSubmit();
                } else {
                    msgBox.style.border = '2px solid #ef4444';
                    msgBox.style.animation = 'none';
                    setTimeout(() => { msgBox.style.animation = 'shake .4s ease'; }, 10);
                    msgPlaceholder.textContent = 'Please attach a file first!';
                    showToast('Please attach a file before submitting.', 'warn');
                    setTimeout(() => { msgPlaceholder.textContent = 'No file insert...'; msgBox.style.border = ''; }, 3000);
                }
            }

            function doSubmit() {
                isSubmitting = true;
                msgActionBtn.disabled = true;
                msgBtnIcon.className = 'fa fa-spinner fa-spin';

                const fd = new FormData();
                fd.append('assignment_id', '<?= $assignment["id"] ?>');
                fd.append('comment', '');
                fd.append('submission_file', attachedFile);
                fd.append('file_type', attachedType);

                fetch('/learning_management/public/?url=submit_assignment', { method: 'POST', body: fd })
                    .then(r => r.text())
                    .then(text => {
                        let data;
                        try { data = JSON.parse(text); }
                        catch (e) { showToast('Server error. Please try again.', 'error'); resetToReady(); return; }

                        if (data.success) {
                            const now = new Date();
                            const dateStr = now.toLocaleString('en-US', {
                                month: 'short', day: 'numeric', year: 'numeric',
                                hour: 'numeric', minute: '2-digit', hour12: true
                            });
                            // Expects: data.file_path  (relative path stored in DB)
                            //          data.file_name  (original filename)
                            renderSubmittedState(
                                dateStr,
                                data.file_path || '',
                                data.file_name || attachedFile.name,
                                attachedType
                            );
                            showToast('Assignment submitted successfully!', 'success');
                            msgBoxVisible = false;
                        } else {
                            showToast(data.message || 'Submission failed. Please try again.', 'error');
                            resetToReady();
                        }
                    })
                    // .catch(() => { showToast('Network error. Please check your connection.', 'error'); resetToReady(); });
                    .catch(() => { showToast('Submission removed. You can re-submit..', 'error'); resetToReady(); });
                // .catch(() => showToast('Unsubmitted file already successfully!.', 'error'))
            }

            function resetToReady() {
                isSubmitting = false;
                msgActionBtn.disabled = false;
                msgBtnIcon.className = 'fa fa-paper-plane';
                msgActionBtn.classList.remove('idle', 'danger');
                msgActionBtn.classList.add('ready');
            }

            function renderSubmittedState(dateStr, filePath, fileName, fileType) {
                // Hide preview strip
                const preview = document.getElementById('attachPreview');
                if (preview) preview.style.display = 'none';

                // Remove any old submitted-file card
                ['submittedFileSection'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.remove();
                });

                const submissionArea = document.getElementById('submissionArea');

                // Insert submitted file card if we have a path
                if (filePath && fileName) {
                    const iconMap = { word: 'fa-file-word', powerpoint: 'fa-file-powerpoint', pdf: 'fa-file-pdf', file: 'fa-file-pdf', image: 'fa-file-image', video: 'fa-file-video' };
                    const badgeMap = { word: 'word', powerpoint: 'powerpoint', pdf: 'pdf', file: 'pdf', image: 'pdf', video: 'pdf' };
                    const icon = iconMap[fileType] || 'fa-file-pdf';
                    const badge = badgeMap[fileType] || 'pdf';
                    const label = fileType === 'word' ? 'WORD' : (fileType === 'powerpoint' ? 'PPT' : 'PDF');

                    const section = document.createElement('div');
                    section.className = 'av-submitted-file-section';
                    section.id = 'submittedFileSection';
                    section.innerHTML = `
                    <p class="av-submitted-file-label">
                        <i class="fa fa-check-circle"></i> Your Submitted File
                    </p>
                    <div class="av-submitted-file-card"
                         onclick="openModal('${filePath.replace(/'/g, "\\'")}', '${fileName.replace(/'/g, "\\'")}', '${fileType}')">
                                                                                                                                    <div class="av-file-icon ${badge}">
                                                                                                                                        <i class="fa ${icon}"></i>
                                                                                                                                        <span>${label}</span>
                                                                                                                                    </div>
                                                                                                                                    <span class="av-file-badge ${badge}">
                                                                                                                                        <i class="fa ${icon}"></i>
                                                                                                                                        ${fileName}
                                                                                                                                    </span>
                                                                                                                                </div>`;

                    // Insert before the first child (above the msgBox)
                    submissionArea.insertBefore(section, submissionArea.firstChild);
                }

                // Update status bar → submitted
                msgBox.classList.remove('has-file');
                msgBox.classList.add('submitted');
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

                // Button → RED X (unsubmit)
                msgActionBtn.disabled = false;
                msgActionBtn.classList.remove('idle', 'ready');
                msgActionBtn.classList.add('danger');
                msgActionBtn.title = 'Unsubmit';
                msgBtnIcon.className = 'fa fa-times';
                msgActionBtn.onclick = confirmUnsubmit;
                isSubmitting = false;

                attachActions.classList.add('locked');
                attachActions.style.opacity = '0.35';
                attachActions.style.pointerEvents = 'none';

                // Disable file inputs so they can't be triggered at all
                ['attachFileInput', 'attachImageInput', 'attachVideoInput'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.disabled = true;
                });

                attachedFile = null;
                attachedType = null;
            }

        <?php endif; ?>

        // ── Unsubmit ──────────────────────────────────────────────────────────────
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

            const fd = new FormData();
            fd.append('assignment_id', '<?= $assignment["id"] ?? 0 ?>');

            fetch('/learning_management/public/?url=unsubmit_assignment', { method: 'POST', body: fd })
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
                        // Reload after short delay so toast is visible
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    } else {
                        closeUnsubmitDialog();
                        showToast(data.message || 'Could not unsubmit. Please try again.', 'error');
                        btn.disabled = false; btn.textContent = 'Yes, Unsubmit';
                    }
                })
                .catch(() => {
                    closeUnsubmitDialog();
                    // showToast('Network error. Please check your connection.', 'error');
                    showToast('Submission removed. You can re-submit.', 'error');
                    btn.disabled = false; btn.textContent = 'Yes, Unsubmit';
                });
        }

        function renderUnsubmittedState() {
            const submissionArea = document.getElementById('submissionArea');

            submissionArea.innerHTML = `
        <input type="file" id="attachFileInput" accept=".pdf,.doc,.docx,.ppt,.pptx" style="display:none">
        <input type="file" id="attachImageInput" accept="image/*" style="display:none">
        <input type="file" id="attachVideoInput" accept="video/*" style="display:none">

        <div id="attachPreview" style="display:none; margin:0 20px 10px; padding:10px;
                background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;">
            <img id="previewImage" style="display:none; max-width:100%; max-height:200px; border-radius:8px;">
            <video id="previewVideo" controls style="display:none; max-width:100%; max-height:200px; border-radius:8px;"></video>
        </div>

        <div class="av-message-box" id="msgBox">
            <div class="av-msg-left">
                <i class="fa fa-file av-msg-status-icon green" id="msgIcon" style="display:none;"></i>
                <div class="av-msg-text-col" id="msgTextCol" style="display:none;">
                    <span class="av-msg-title" id="msgTitle"></span>
                </div>
                <span class="av-msg-placeholder" id="msgPlaceholder">No file insert...</span>
            </div>
            <button class="av-msg-btn idle" id="msgActionBtn" title="Attach a file first" onclick="handleMsgBtn()">
                <i class="fa fa-paper-plane" id="msgBtnIcon"></i>
            </button>
        </div>

        <div id="msgBoxWrapper" style="display:none; margin: 10px 20px 0;">
            <div style="background:#fff; border:1px solid rgba(0,0,0,0.1); border-radius:10px;
                    padding:10px 14px; display:flex; align-items:center; gap:10px;">
                <input type="text" id="msgInput" placeholder="Message ..."
                    style="flex:1; border:none; outline:none; font-size:14px; color:#333; background:transparent;">
                <button class="av-msg-btn ready" title="Send message" id="msgSendBtn" onclick="sendMessage()">
                    <i class="fa fa-paper-plane" id="msgSendIcon"></i>
                </button>
            </div>
        </div>

        <!-- NEW unified action bar — message always active, others enabled after unsubmit -->
        <div style="display:flex; margin-top:1rem; border-radius:12px; border:1px solid rgba(0,0,0,0.1); background-color:#F0F0F0; overflow:hidden;">
            <button id="msgToggleBtn" title="Message" onclick="toggleMessageBox()"
    style="background:none; border:none; border-right:1px solid rgba(0,0,0,0.08); color:var(--green, #4CAF7D); font-size:23px; cursor:pointer; padding:10px 30px; flex-shrink:0; transition:background .15s;">
                <i class="fa fa-comment"></i>
            </button>
            <div id="attachActions" style="display:flex; flex:1; justify-content:space-around; align-items:center; padding:10px 20px;">
                <button title="Attach file" onclick="document.getElementById('attachFileInput').click()"
                    style="background:none; border:none; color:var(--green); font-size:23px; cursor:pointer; padding:0;">
                    <i class="fa fa-paperclip"></i>
                </button>
                <button title="Image" onclick="document.getElementById('attachImageInput').click()"
                    style="background:none; border:none; color:var(--green); font-size:23px; cursor:pointer; padding:0;">
                    <i class="fa fa-image"></i>
                </button>
                <button title="Video" onclick="document.getElementById('attachVideoInput').click()"
                    style="background:none; border:none; color:var(--green); font-size:23px; cursor:pointer; padding:0;">
                    <i class="fa fa-film"></i>
                </button>
            </div>
        </div>`;

            // Reset state vars
            attachedFile = null;
            attachedType = null;
            isSubmitting = false;
            msgBoxVisible = false;

            // Re-grab DOM refs
            window.msgBox = document.getElementById('msgBox');
            window.msgIcon = document.getElementById('msgIcon');
            window.msgTextCol = document.getElementById('msgTextCol');
            window.msgTitle = document.getElementById('msgTitle');
            window.msgPlaceholder = document.getElementById('msgPlaceholder');
            window.msgActionBtn = document.getElementById('msgActionBtn');
            window.msgBtnIcon = document.getElementById('msgBtnIcon');
            window.attachActions = document.getElementById('attachActions');

            document.getElementById('msgInput').addEventListener('keydown', function (e) {
                if (e.key === 'Enter') sendMessage();
            });

            bindFileInputs();
        }
    </script>

    <script>
        // ── Live due-date watcher ─────────────────────────────────────────────────
        (function () {
    <?php if (!$assignment): ?> return; <?php endif; ?>

            // Snapshot of the due date+time the page was loaded with
            let knownDueDate = <?= json_encode($assignment['due_date'] ?? '') ?>;
            let knownDueTime = <?= json_encode($assignment['due_time'] ?? '') ?>;
            const assignmentId = <?= (int) ($assignment['id'] ?? 0) ?>;

            if (!assignmentId) return;

            function pollDueDate() {
                fetch('/learning_management/public/?url=get_assignment_due&id=' + assignmentId, {
                    cache: 'no-store'
                })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.due_date) return;

                        const changed =
                            data.due_date !== knownDueDate ||
                            (data.due_time ?? '') !== (knownDueTime ?? '');

                        if (changed) {
                            // Update our snapshot so we don't reload repeatedly
                            knownDueDate = data.due_date;
                            knownDueTime = data.due_time ?? '';
                            // Reload so PHP re-renders the overdue/submit state correctly
                            window.location.reload();
                        }
                    })
                    .catch(() => { /* silently ignore network hiccups */ });
            }

            // Poll every 8 seconds
            setInterval(pollDueDate, 8000);
        })();
    </script>
</body>

</html>