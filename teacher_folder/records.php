<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($classInfo['subject_name'] ?? 'Class') ?></title>
    <link rel="stylesheet" href="../css_folder/records.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ── HERO BANNER ── */
        .hero-banner {
            width: 100%;
            height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
            /* border-top-left-radius: 20px;
            border-top-right-radius: 20px; */
            z-index: 1;
            display: flex;
            justify-content: start;
            align-items: center;
            padding: 24px 28px;
            border-radius: 20px;
            background-color: var(--neon-cyan);
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(255, 255, 255, 0.05) 0 2px, transparent 2px 26px);
            pointer-events: none;
        }

        /* .hero-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: rgba(0, 0, 0, .30);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        } */

        /* Hero overlay */
        .hero-overlay-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }

        .class-badge {
            display: inline-block;
            background: #1447e6;
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .03em;
            margin-bottom: 8px;
        }

        .hero-title {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
        }

        .hero-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, .85);
            margin-bottom: 6px;
        }

        .hero-tagline {
            font-size: 13px;
            color: rgba(255, 255, 255, .7);
            max-width: 520px;
        }

        /* ── HERO INFO ── */
        .hero-info {
            /* background: #fff; */
            padding: 16px 0px 0;
            /* border: 1px solid #e4e7eb; */
            position: relative;
            z-index: 2;
        }

        /* ── TABS ── */
        .tabs {
            display: flex;
            gap: 0;
            padding: 0;
            background: transparent;
            border-bottom: 1px solid var(--border);
            /* background: #fff; */
            /* margin-top: .5rem; */
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            color: #6b7280;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: color .15s, border-color .15s;
        }

        .tab-btn i {
            font-size: 13px;
        }

        .tab-btn.active {
            color: var(--neon-cyan);
            border-bottom-color: var(--neon-cyan);
        }

        .tab-btn:hover:not(.active) {
            color: #111;
        }

        /* ── MAIN CONTENT ── */
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* ══════════════════════════════
           STREAM CARDS
        ══════════════════════════════ */

        #tab-stream.active {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .stream-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            height: 100%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            transition: box-shadow .15s;
        }

        .stream-card:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, .09);
        }

        /* Tinted card backgrounds by post type — matches the inspiration mock */
        .stream-card.type-announcement {
            /* background: #eff6ff;
            border-color: #bfdbfe; */
            background-color: #ffffff;
        }

        .stream-card.type-material {
            /* background: #f0fdf4;
            border-color: #bbf7d0; */
            background-color: #ffffff;
        }

        .stream-card.type-assignment {
            /* background: #fff7ed;
            border-color: #fed7aa; */
            background-color: #ffffff;
        }

        .stream-card-inner {
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        /* Green stacked-layers icon (material) */
        .stream-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #dcfce7;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stack-lines {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .stack-lines span {
            display: block;
            border-radius: 2px;
            background: #16a34a;
            width: 20px;
            height: 4px;
        }

        .stack-lines span:nth-child(1) {
            opacity: .4;
        }

        .stack-lines span:nth-child(2) {
            opacity: .7;
        }

        .stack-lines span:nth-child(3) {
            opacity: 1;
        }

        /* Announcement avatar */
        .anm-avatar {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #1447e6;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
        }

        .stream-body {
            flex: 1;
            min-width: 0;
        }

        .stream-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #1447e6;
            margin-bottom: 5px;
        }

        .stream-line {
            font-size: 15px;
            /* font-size: 18px; */
            font-weight: 700;
            /* color: #111827; */
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .stream-line strong {
            font-weight: 500;
            font-size: 13px;
            color: var(--text-dim);
        }

        .stream-anm-text {
            font-size: 13.5px;
            /* color: #374151; */
            color: var(--text-dim);
            line-height: 1.55;
            margin-top: 4px;
        }

        .stream-menu {
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 18px;
            padding: 2px 6px;
            border-radius: 6px;
            flex-shrink: 0;
            letter-spacing: .05em;
        }

        .stream-menu:hover {
            background: rgba(0, 0, 0, .06);
        }

        /* ── CARD DROPDOWN (edit menu) ── */
        .card-menu-wrap {
            position: relative;
        }

        .card-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            min-width: 140px;
            overflow: hidden;
            z-index: 50;
        }

        .card-dropdown.open {
            display: block;
        }

        .card-dropdown a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            text-decoration: none;
            cursor: pointer;
        }

        .card-dropdown a:hover {
            background: #f3f4f6;
        }

        /* Files inside stream card */
        .stream-files {
            padding: 0 16px 10px 70px;
        }

        .stream-file {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .stream-file:last-child {
            margin-bottom: 0;
        }

        .stream-file i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .sf-name {
            flex: 1;
            font-weight: 600;
            color: #111827;
        }

        .sf-meta {
            color: #9ca3af;
            font-size: 11px;
            white-space: nowrap;
        }

        .sf-dl {
            color: #9ca3af;
            text-decoration: none;
            transition: color .12s;
        }

        .sf-dl:hover {
            color: #009e3e;
        }

        .stream-footer {
            padding: 9px 16px;
            border-top: 1px solid rgba(0, 0, 0, .06);
            font-size: 12px;
            color: #9ca3af;
        }

        /* ══════════════════════════════
           CLASSWORK — assignments + student submissions
        ══════════════════════════════ */
        .cw-section-title {
            font-size: 11px;
            font-weight: 800;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin: 0 0 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .cw-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e4e7eb;
        }

        /* Assignment card in classwork */
        .assign-card {
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            overflow: hidden;
            transition: box-shadow .15s;
            cursor: pointer;
        }

        .assign-card:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, .09);
        }

        .assign-inner {
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .assign-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #ffedd4;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .assign-icon .stack-lines span {
            background: #f54900;
        }

        .assign-body {
            flex: 1;
            min-width: 0;
        }

        .assign-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #f54900;
            margin-bottom: 6px;
        }

        .assign-line {
            font-size: 13px;
            color: #111827;
            margin-bottom: 2px;
        }

        .assign-line strong {
            font-weight: 700;
        }

        .assign-footer {
            padding: 9px 16px;
            border-top: 1px solid #f0f2f5;
            font-size: 12px;
            color: #9ca3af;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .submit-points {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .assign-pts {
            font-size: 12px;
            font-weight: 700;
            color: #374151;
        }

        /* Submission list inside assignment (accordion) */
        .assign-submissions {
            border-top: 1px solid #e4e7eb;
            display: none;
            padding: 12px 16px;
            background: #fafafa;
        }

        .assign-submissions.open {
            display: block;
        }

        .sub-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 8px;
            margin-bottom: 7px;
            font-size: 12px;
        }

        .sub-row:last-child {
            margin-bottom: 0;
        }

        .sub-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dbeafe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: #1d4ed8;
            flex-shrink: 0;
        }

        .sub-name {
            flex: 1;
            font-weight: 600;
            color: #111827;
        }

        .sub-date {
            color: #9ca3af;
            font-size: 11px;
        }

        .sub-status {
            font-size: 11px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 20px;
        }

        .sub-status.submitted {
            background: #dcfce7;
            color: #16a34a;
        }

        .sub-status.late {
            background: #fef9c3;
            color: #ca8a04;
        }

        .sub-status.missing {
            background: #fee2e2;
            color: #dc2626;
        }

        .sub-file-link {
            color: #9ca3af;
            text-decoration: none;
            transition: color .12s;
        }

        .sub-file-link:hover {
            color: #009e3e;
        }

        /* Submission count badge */
        .sub-count-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 9px;
            border-radius: 20px;
            color: #16a34a;
            margin-left: 8px;
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 38px;
            opacity: .18;
            display: block;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 13px;
            line-height: 1.6;
        }

        /* ── CREATE DROPDOWN ── */
        .create-wrap {
            display: flex;
            justify-content: end;
        }

        .create-menu {
            display: none;
            position: absolute;
            bottom: 90%;
            right: 70%;
            background: #fff;
            border: 1px solid #e4e7eb;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            min-width: 180px;
            overflow: hidden;
        }

        .create-menu.open {
            display: block;
        }

        .create-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            text-decoration: none;
            transition: background .12s;
        }

        .create-menu a:hover {
            background: #f3f4f6;
        }

        .create-menu a i {
            width: 18px;
            text-align: center;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: var(--neon-cyan);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 10px 24px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s, transform .15s;
        }

        .btn-create:hover {
            background-color: var(--neon-cyan);
            transform: translateY(-2px);
            color: #fff;
        }

        /* ── CREATE CONTENT MODAL ── */
        .cc-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .cc-overlay.open {
            display: flex;
        }

        .cc-modal {
            background: #fff;
            border-radius: 14px;
            width: 92%;
            max-width: 640px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .18);
        }

        .cc-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #e4e7eb;
            flex-shrink: 0;
        }

        .cc-modal-header h5 {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        .cc-modal-header button {
            background: none;
            border: none;
            font-size: 18px;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .cc-modal-header button:hover {
            background: #f3f4f6;
        }

        .cc-type-switch {
            display: flex;
            gap: 8px;
            padding: 14px 22px 14px;
            flex-shrink: 0;
            border-bottom: 1px solid var(--border);
        }

        .cc-type-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            border: 1.5px solid #e4e7eb;
            background: #fff;
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            cursor: pointer;
        }

        .cc-type-btn.active {
            background: var(--neon-cyan);
            border-color: var(--neon-cyan);
            color: #fff;
        }

        .cc-panel {
            display: none;
            overflow-y: auto;
            /* padding: 16px 22px 0; */
            /* padding: 1.5rem 22px 0; */
            padding: 1.5rem 0 0 flex: 1;
            min-height: 0;
        }

        .cc-panel.active {
            display: block;
        }

        .cc-tabbar {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            /* padding-bottom: 10px; */
            /* border-bottom: 1px solid #e4e7eb; */
            /* margin-bottom: 16px; */
            padding: 20px 22px 16px;
            /* margin-bottom: 16px; */
        }

        .cc-tab {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 8px 7px 14px;
            /* border-radius: 8px 8px 0 0; */
            border-radius: 8px;
            background: #f3f4f6;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .cc-tab.active {
            background: var(--neon-cyan);
            color: #fff;
        }

        .cc-tab .cc-tab-label {
            cursor: pointer;
        }

        .cc-tab-x {
            background: none;
            border: none;
            color: inherit;
            opacity: .7;
            font-size: 15px;
            line-height: 1;
            cursor: pointer;
            padding: 2px 6px;
            border-radius: 6px;
        }

        .cc-tab-x:hover {
            opacity: 1;
            background: rgba(0, 0, 0, .12);
        }

        .cc-add-tab-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            border: 1.5px dashed #d1d5db;
            background: none;
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            cursor: pointer;
            flex-shrink: 0;
        }

        .cc-add-tab-btn:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        .cc-tabpanel {
            display: none;
            padding: 0 22px 0;
        }

        .cc-tabpanel.active {
            display: block;
        }

        .cc-field {
            margin-bottom: 1rem;
        }

        .cc-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
        }

        .cc-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: border-color .15s;
        }

        .cc-input:focus {
            border-color: var(--neon-cyan);
        }

        .cc-textarea {
            resize: vertical;
            min-height: 110px;
        }

        .cc-pdf-item {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 9px 12px;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .cc-pdf-item i {
            color: #e53e3e;
            font-size: 15px;
        }

        .cc-pdf-item span {
            flex: 1;
            color: #374151;
            word-break: break-all;
        }

        .cc-pdf-remove {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 13px;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .cc-pdf-remove:hover {
            color: #ef4444;
        }

        .cc-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 16px 22px;
            /* border-top: 1px solid #f0f2f5; */
            border-top: 1px solid var(--border);
            margin-top: 8px;
            position: sticky;
            bottom: 0;
            background: #fff;
        }

        .cc-btn-cancel {
            background: none;
            border: 1px solid #e4e7eb;
            border-radius: 50px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 700;
            color: #6b7280;
            cursor: pointer;
        }

        .cc-btn-submit {
            background: var(--neon-cyan);
            border: none;
            border-radius: 50px;
            padding: 9px 24px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
        }

        .pdf-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .75);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .pdf-modal-overlay.open {
            display: flex;
        }

        .pdf-modal {
            background: #1e1e1e;
            border-radius: 12px;
            width: 90vw;
            max-width: 960px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .pdf-modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: #2b2b2b;
            border-bottom: 1px solid #3a3a3a;
            flex-shrink: 0;
        }

        .pdf-icon {
            width: 32px;
            height: 32px;
            background: #e53e3e;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
        }

        .pdf-modal-title {
            flex: 1;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .pdf-modal-actions {
            display: flex;
            gap: 8px;
        }

        .pdf-modal-actions a,
        .pdf-modal-actions button {
            background: #3a3a3a;
            border: none;
            border-radius: 8px;
            color: #d1d5db;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pdf-modal-actions a:hover,
        .pdf-modal-actions button:hover {
            background: #4a4a4a;
            color: #fff;
        }

        .btn-close-pdf {
            font-size: 16px;
            padding: 7px 12px !important;
        }

        .pdf-modal-body {
            flex: 1;
            overflow: hidden;
        }

        .pdf-modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .main-parent-box {
            display: flex;
            justify-content: space-between;
            gap: 1.5rem;
            align-items: flex-start;
            width: 100%;
        }

        #tab-people {
            width: 100%;
        }

        /* Layout with sidebar */
        .content-layout {
            display: flex;
            /* gap: 1rem; */
            flex-direction: column;
            align-items: flex-start;
            /* background-color: #ffffff; */
            /* border: 1px solid #e4e7eb; */
            border-top: none;
            /* box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04); */
            /* padding: 1.5rem; */
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .content-layout main {
            flex: 1;
            min-width: 0;
        }

        .sidebar-col {
            width: 300px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .info-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .info-card-header {
            font-size: 13px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #9ca3af;
        }

        .info-value {
            font-weight: 600;
            color: #111827;
            text-align: right;
        }

        .donut-wrap {
            display: flex;
            justify-content: center;
            margin: 10px 0 18px;
        }

        .donut {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .donut-hole {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #1447e6;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #374151;
            margin-bottom: 8px;
        }

        .legend-row b {
            margin-left: auto;
        }

        .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot-blue {
            background: #1447e6;
        }

        .dot-green {
            background: #00c950;
        }

        .dot-orange {
            background: #f59e0b;
        }

        .total-students {
            border-top: 1px solid #f0f2f5;
            margin-top: 10px;
            padding-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }

        .total-students b {
            color: #111827;
        }

        /* Icon variants + assignment due chip */
        .icon-blue {
            background: #dbeafe;
        }

        .icon-blue i {
            color: #1447e6;
        }

        .icon-orange {
            background: #ffedd4;
        }

        .icon-orange i {
            color: #f54900;
        }

        .due-chip {
            background: #fef3c7;
            color: #b45309;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        /* People tab: no toolbar row, main content goes full width */
        .content-layout.people-active .content-toolbar {
            display: none !important;
        }

        .content-layout.people-active .main-parent-box>main {
            width: 100%;
            flex: 1 1 100%;
        }

        .content-layout.people-active .sidebar-col {
            display: none !important;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0">
        <?php include("sidebar.php"); ?>

        <div class="rightbar">

            <?php
            /* ── Helpers ── */
            function getInitials(string $name): string
            {
                $parts = explode(' ', trim($name));
                $i = strtoupper(substr($parts[0], 0, 1));
                if (count($parts) > 1)
                    $i .= strtoupper(substr(end($parts), 0, 1));
                return $i;
            }
            $teacherName = $_SESSION['name'] ?? 'Teacher';
            $initials = getInitials($teacherName);

            /* ── Dynamic banner by subject name ── */
            function getBannerBg(string $subject): string
            {
                $n = strtolower($subject);
                if (str_contains($n, 'phil'))
                    return "url('../images/philosophy_picture.jpg')";
                if (str_contains($n, 'ucsp') || str_contains($n, 'cultur'))
                    return "url('../images/ucsp_picture.jpg')";
                if (str_contains($n, 'comput') || str_contains($n, 'css'))
                    return "url('../images/computer_picture.jpg')";
                if (str_contains($n, 'physical') || $n === 'pe')
                    return "url('../images/pe_picture.jpg')";
                if (str_contains($n, 'inquir') || str_contains($n, '3i'))
                    return "url('../images/3i_picture.jpg')";
                if (str_contains($n, 'entrep'))
                    return "url('../images/entrep_picture.jpg')";
                if (str_contains($n, 'work') || str_contains($n, 'immersion'))
                    return "url('../images/work_picture.jpg')";
                if (str_contains($n, 'media') || str_contains($n, 'information'))
                    return "url('../images/media_picture.jpg')";
                return "url('../images/philosophy_picture.jpg')";
            }
            $bannerBg = getBannerBg($classInfo['subject_name'] ?? '');

            /* ── Fetch announcements, assignments, and submissions ── */
            $tid = $_SESSION['teacher_id'] ?? 0;
            $announcements = ($subject_id && $teacherModel) ? $teacherModel->getAnnouncements($subject_id, $tid, $section_id) : [];

            $assignments = ($subject_id && $teacherModel) ? $teacherModel->getAssignments($subject_id, $tid, $section_id) : [];

            $progressStats = ($subject_id && $teacherModel)
                ? $teacherModel->getClassProgressStats($subject_id, $tid, $section_id ?? 0)
                : ['submitted' => 0, 'graded' => 0, 'pending' => 0, 'percentage' => 0, 'total_students' => 0];

            // For each assignment, load student submissions
            foreach ($assignments as &$asgn) {
                $asgn['submissions'] = ($teacherModel) ? $teacherModel->getSubmissions($asgn['id']) : [];
            }
            unset($asgn);
            ?>

            <!-- INVITE STUDENT MODAL -->
            <div id="inviteOverlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);
     z-index:9999;align-items:center;justify-content:center;" onclick="closeInviteModal()">
                <div style="background:var(--bs-body-bg,#fff);border-radius:14px;width:90%;max-width:480px;
              overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.15);" onclick="event.stopPropagation()">

                    <!-- Header -->
                    <div
                        style="padding:18px 20px 14px;border-bottom:1px solid #e4e7eb;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <h5 style="font-size:16px;font-weight:700;color:#111827;margin:0;">Invite Students</h5>
                            <p style="font-size:12px;color:#6b7280;margin:2px 0 0;">
                                <?= htmlspecialchars($classInfo['subject_name'] ?? '') ?> &middot;
                                <?= htmlspecialchars($classInfo['section'] ?? '') ?>
                            </p>
                        </div>
                        <button onclick="closeInviteModal()" style="background:none;border:none;font-size:18px;
              cursor:pointer;color:#9ca3af;padding:4px 8px;border-radius:8px;line-height:1;">✕</button>
                    </div>

                    <!-- Body -->
                    <div style="padding:16px 20px;">
                        <div style="position:relative;margin-bottom:10px;">
                            <span
                                style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:13px;">&#9906;</span>
                            <input type="text" id="inviteSearch" placeholder="Search name or email…"
                                oninput="filterInviteStudents()" style="width:100%;padding:8px 12px 8px 30px;font-size:13px;border:1px solid #e4e7eb;
                      border-radius:50px;outline:none;background:#f9fafb;color:#111827;">
                        </div>

                        <!-- Select all -->
                        <label
                            style="display:flex;align-items:center;gap:8px;padding:6px 10px;margin-bottom:6px;
                    background:#f3f4f6;border-radius:8px;font-size:12px;color:#6b7280;cursor:pointer;user-select:none;">
                            <input type="checkbox" id="inviteSelectAll" onchange="toggleInviteAll()"
                                style="accent-color:#00C950;width:14px;height:14px;">
                            Select all
                        </label>

                        <!-- Student list -->
                        <div id="inviteStudentList"
                            style="max-height:210px;overflow-y:auto;border:1px solid #e4e7eb;border-radius:10px;">
                            <?php foreach ($approvedStudents ?? [] as $stu):
                                $initials = strtoupper(substr($stu['name'], 0, 1));
                                $parts = explode(' ', trim($stu['name']));
                                if (count($parts) > 1)
                                    $initials .= strtoupper(substr(end($parts), 0, 1));

                                // Get invitation status from enrollment_invitations
                                $inviteStatus = 'none';
                                if ($teacherModel) {
                                    $inv = $teacherModel->getInvitationStatus(
                                        $stu['email'],
                                        (int) $subject_id,
                                        (int) ($section_id ?? 0)
                                    );
                                    if ($inv)
                                        $inviteStatus = $inv['status']; // 'pending' | 'accepted' | 'expired'
                                }

                                $isPending = ($inviteStatus === 'pending');
                                $isAccepted = ($inviteStatus === 'accepted');
                                $isDisabled = $isPending || $isAccepted;
                                ?>
                                <label class="invite-stu-row" style="display:flex;align-items:center;gap:15px;padding:15px 12px;
               border-bottom:1px solid #f0f2f5;
               cursor:<?= $isDisabled ? 'not-allowed' : 'pointer' ?>;
               opacity:<?= $isDisabled ? '0.55' : '1' ?>;
               transition:background .1s;" onmouseover="<?= !$isDisabled ? "this.style.background='#f9fafb'" : '' ?>"
                                    onmouseout="<?= !$isDisabled ? "this.style.background=''" : '' ?>">

                                    <input type="checkbox" class="invite-stu-check"
                                        value="<?= htmlspecialchars($stu['email']) ?>"
                                        data-name="<?= htmlspecialchars(strtolower($stu['name'])) ?>"
                                        data-email="<?= htmlspecialchars(strtolower($stu['email'])) ?>" <?= $isDisabled ? 'disabled' : '' ?> onchange="updateInviteCount()"
                                        style="accent-color:#00C950;width:14px;height:14px;flex-shrink:0;">

                                    <div style="width:30px;height:30px;border-radius:50%;background:#e8f5ee;flex-shrink:0;
                    display:flex;align-items:center;justify-content:center;
                    font-size:11px;font-weight:700;color:#009e3e;">
                                        <?= $initials ?>
                                    </div>

                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:13px;font-weight:600;color:#111827;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars($stu['name']) ?>
                                        </div>
                                        <div style="font-size:11px;color:#9ca3af;
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <?= htmlspecialchars($stu['email']) ?>
                                        </div>
                                    </div>

                                    <!-- Invitation status badge -->
                                    <?php if ($isPending): ?>
                                        <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;
                         background:#fef9c3;color:#ca8a04;white-space:nowrap;flex-shrink:0;
                         border:1px solid #fde68a;">
                                            ⏳ Pending
                                        </span>
                                    <?php elseif ($isAccepted): ?>
                                        <span style="font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;
                         background:#dcfce7;color:#16a34a;white-space:nowrap;flex-shrink:0;
                         border:1px solid #bbf7d0;">
                                            ✓ Enrolled
                                        </span>
                                    <?php endif; ?>

                                </label>
                            <?php endforeach; ?>
                            <?php if (empty($approvedStudents)): ?>
                                <p style="text-align:center;padding:20px;font-size:13px;color:#9ca3af;">
                                    No approved students found.
                                </p>
                            <?php endif; ?>
                        </div>

                        <p id="inviteSelectedCount"
                            style="font-size:12px;color:#009e3e;font-weight:600;margin-top:8px;min-height:16px;"></p>
                    </div>

                    <!-- Footer -->
                    <div
                        style="padding:14px 20px;border-top:1px solid #e4e7eb;display:flex;justify-content:space-between;align-items:center;">
                        <button onclick="closeInviteModal()" style="background:none;border:1px solid #e4e7eb;border-radius:50px;
                     padding:8px 18px;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">
                            Cancel
                        </button>
                        <button id="inviteSendBtn" onclick="submitInvitations()" disabled style="background:#00C950;border:none;border-radius:50px;padding:8px 22px;
                     font-size:13px;font-weight:700;color:#fff;cursor:pointer;
                     display:flex;align-items:center;gap:6px;opacity:0.5;">
                            <i class="fa fa-paper-plane"></i> Send Invitations
                        </button>
                    </div>
                </div>
            </div>

            <!-- TOAST -->
            <div id="inviteToast" style="display:none;position:fixed;bottom:24px;right:24px;
     background:#111827;color:#fff;padding:12px 20px;border-radius:50px;
     font-size:13px;font-weight:600;z-index:10000;box-shadow:0 4px 20px rgba(0,0,0,.2);">
            </div>

            <!-- CREATE CONTENT MODAL -->
            <div id="createContentOverlay" class="cc-overlay" onclick="if(event.target===this) closeCreateModal()">
                <div class="cc-modal" onclick="event.stopPropagation()">
                    <div class="cc-modal-header">
                        <h5>Create Content</h5>
                        <button type="button" onclick="closeCreateModal()">✕</button>
                    </div>

                    <div class="cc-type-switch">
                        <button type="button" class="cc-type-btn active" data-type="module"
                            onclick="switchCreateType('module')">
                            <i class="fa fa-layer-group"></i> Modules
                        </button>
                        <button type="button" class="cc-type-btn" data-type="announcement"
                            onclick="switchCreateType('announcement')">
                            <i class="fa fa-bullhorn"></i> Announcements
                        </button>
                    </div>

                    <!-- MODULE PANEL -->
                    <div class="cc-panel active" id="cc-panel-module">
                        <form id="cc-module-form" method="POST" action="/learning_management/public/?url=save_lessons"
                            enctype="multipart/form-data">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                            <input type="hidden" name="save_type" value="classes_feed">

                            <div class="cc-tabbar" id="cc-module-tabbar">
                                <!-- <button type="button" class="cc-add-tab-btn" id="cc-add-module-tab">
                                    <i class="fa fa-plus"></i> Add Module
                                </button> -->
                                <button type="button" class="cc-add-tab-btn" id="cc-add-module-tab">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="cc-tabpanels" id="cc-module-panels"></div>

                            <div class="cc-modal-footer">
                                <button type="button" class="cc-btn-cancel" onclick="closeCreateModal()">Cancel</button>
                                <button type="submit" class="cc-btn-submit">Save to Stream</button>
                            </div>
                        </form>
                    </div>

                    <!-- ANNOUNCEMENT PANEL -->
                    <div class="cc-panel" id="cc-panel-announcement">
                        <form id="cc-announcement-form" method="POST"
                            action="/learning_management/public/?url=save_announcement" enctype="multipart/form-data">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">

                            <div class="cc-tabbar" id="cc-ann-tabbar">
                                <button type="button" class="cc-add-tab-btn" id="cc-add-ann-tab">
                                    <i class="fa fa-plus"></i> 
                                </button>
                            </div>
                            <div class="cc-tabpanels" id="cc-ann-panels"></div>

                            <div class="cc-modal-footer">
                                <button type="button" class="cc-btn-cancel" onclick="closeCreateModal()">Cancel</button>
                                <button type="submit" class="cc-btn-submit">Post Announcements</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- CREATE ASSIGNMENT MODAL -->
            <div id="createAssignmentOverlay" class="cc-overlay"
                onclick="if(event.target===this) closeCreateAssignmentModal()">
                <div class="cc-modal" onclick="event.stopPropagation()">
                    <div class="cc-modal-header">
                        <h5>Create Classwork</h5>
                        <button type="button" onclick="closeCreateAssignmentModal()">✕</button>
                    </div>

                    <div class="cc-panel active" id="cc-panel-assignment" style="padding-top:0;">
                        <form id="cc-assignment-form" method="POST"
                            action="/learning_management/public/?url=save_assignment" enctype="multipart/form-data">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">

                            <div class="cc-tabbar" id="cc-assign-tabbar">
                                <button type="button" class="cc-add-tab-btn" id="cc-add-assign-tab">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <div class="cc-tabpanels" id="cc-assign-panels"></div>

                            <div class="cc-modal-footer">
                                <button type="button" class="cc-btn-cancel"
                                    onclick="closeCreateAssignmentModal()">Cancel</button>
                                <button type="submit" class="cc-btn-submit">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EDIT ANNOUNCEMENT MODAL -->
            <div id="editAnnouncementOverlay" class="cc-overlay"
                onclick="if(event.target===this) closeEditAnnouncementModal()">
                <div class="cc-modal" style="max-width:520px;" onclick="event.stopPropagation()">
                    <div class="cc-modal-header">
                        <h5>Edit Announcement</h5>
                        <button type="button" onclick="closeEditAnnouncementModal()">✕</button>
                    </div>
                    <div class="cc-panel active" style="padding-top:1.5rem;">
                        <form id="edit-announcement-form" method="POST"
                            action="/learning_management/public/?url=update_announcement">
                            <input type="hidden" name="announcement_id" id="edit-ann-id">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                            <div style="padding:0 22px;">
                                <div class="cc-field">
                                    <label class="cc-label">Title *</label>
                                    <input type="text" name="title" id="edit-ann-title" class="cc-input" required>
                                </div>
                                <div class="cc-field">
                                    <label class="cc-label">Message *</label>
                                    <textarea name="body" id="edit-ann-body" class="cc-input cc-textarea"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="cc-modal-footer">
                                <button type="button" class="cc-btn-cancel"
                                    onclick="closeEditAnnouncementModal()">Cancel</button>
                                <button type="submit" class="cc-btn-submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EDIT MODULE (Stream material) MODAL -->
            <div id="editModuleOverlay" class="cc-overlay" onclick="if(event.target===this) closeEditModuleModal()">
                <div class="cc-modal" style="max-width:520px;" onclick="event.stopPropagation()">
                    <div class="cc-modal-header">
                        <h5>Edit Material</h5>
                        <button type="button" onclick="closeEditModuleModal()">✕</button>
                    </div>
                    <div class="cc-panel active" style="padding-top:1.5rem;">
                        <form id="edit-module-form" method="POST"
                            action="/learning_management/public/?url=update_cf_module" enctype="multipart/form-data">
                            <input type="hidden" name="module_id" id="edit-mod-id">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                            <input type="hidden" name="remove_file" id="edit-mod-remove-file" value="">
                            <div style="padding:0 22px;">
                                <div class="cc-field">
                                    <label class="cc-label">Title *</label>
                                    <input type="text" name="title" id="edit-mod-title" class="cc-input" required>
                                </div>
                                <div class="cc-field">
                                    <label class="cc-label">Description</label>
                                    <textarea name="description" id="edit-mod-description"
                                        class="cc-input cc-textarea"></textarea>
                                </div>
                                <div class="cc-field">
                                    <label class="cc-label">Attached File</label>

                                    <!-- Current file chip (shown if one exists and hasn't been removed) -->
                                    <div id="edit-mod-current-file" class="cc-pdf-item" style="display:none;">
                                        <i class="fa fa-file-pdf"></i>
                                        <span id="edit-mod-current-file-name"></span>
                                        <button type="button" class="cc-pdf-remove"
                                            onclick="removeCurrentModuleFile()">&times;</button>
                                    </div>

                                    <!-- New file chosen to replace it -->
                                    <div id="edit-mod-new-file" class="cc-pdf-item" style="display:none;">
                                        <i class="fa fa-file-pdf"></i>
                                        <span id="edit-mod-new-file-name"></span>
                                        <button type="button" class="cc-pdf-remove"
                                            onclick="clearNewModuleFile()">&times;</button>
                                    </div>

                                    <button type="button" class="cc-add-tab-btn" id="edit-mod-add-file-btn"
                                        style="width:100%;justify-content:center;margin-top:8px;">
                                        <i class="fa fa-plus"></i> <span id="edit-mod-add-file-label">Add File</span>
                                    </button>
                                    <input type="file" name="material_file" id="edit-mod-file-input"
                                        accept=".pdf,.ppt,.pptx,.doc,.docx" style="display:none;">
                                </div>
                            </div>
                            <div class="cc-modal-footer">
                                <button type="button" class="cc-btn-cancel"
                                    onclick="closeEditModuleModal()">Cancel</button>
                                <button type="submit" class="cc-btn-submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EDIT ASSIGNMENT MODAL -->
            <div id="editAssignmentOverlay" class="cc-overlay"
                onclick="if(event.target===this) closeEditAssignmentModal()">
                <div class="cc-modal" onclick="event.stopPropagation()">
                    <div class="cc-modal-header">
                        <h5>Edit Classwork</h5>
                        <button type="button" onclick="closeEditAssignmentModal()">✕</button>
                    </div>
                    <div class="cc-panel active" style="padding-top:1.5rem;">
                        <form id="edit-assignment-form" method="POST"
                            action="/learning_management/public/?url=update_assignment_details"
                            enctype="multipart/form-data">
                            <input type="hidden" name="assignment_id" id="edit-asg-id">
                            <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                            <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                            <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                            <input type="hidden" name="remove_file" id="edit-asg-remove-file" value="">
                            <div style="padding:0 22px;">
                                <div class="cc-field">
                                    <label class="cc-label">Title *</label>
                                    <input type="text" name="title" id="edit-asg-title" class="cc-input" required>
                                </div>
                                <div class="cc-field">
                                    <label class="cc-label">Description</label>
                                    <textarea name="description" id="edit-asg-description"
                                        class="cc-input cc-textarea"></textarea>
                                </div>
                                <div class="cc-field">
                                    <label class="cc-label">Task</label>
                                    <input type="text" name="task" id="edit-asg-task" class="cc-input">
                                </div>
                                <div class="cc-field">
                                    <label class="cc-label">Instructions</label>
                                    <textarea name="instructions" id="edit-asg-instructions"
                                        class="cc-input cc-textarea"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="cc-field">
                                            <label class="cc-label">Type</label>
                                            <select name="type" id="edit-asg-type" class="cc-input form-select">
                                                <option value="Seatwork">Seatwork</option>
                                                <option value="Homework">Homework</option>
                                                <option value="Project">Project</option>
                                                <option value="Quiz">Quiz</option>
                                                <option value="Exam">Exam</option>
                                                <option value="Performance">Performance Task</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="cc-field">
                                            <label class="cc-label">Points</label>
                                            <input type="number" name="points" id="edit-asg-points" class="cc-input"
                                                min="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="cc-field">
                                    <label class="cc-label">Attached File</label>

                                    <!-- Current file chip -->
                                    <div id="edit-asg-current-file" class="cc-pdf-item" style="display:none;">
                                        <i class="fa fa-file-pdf"></i>
                                        <span id="edit-asg-current-file-name"></span>
                                        <button type="button" class="cc-pdf-remove"
                                            onclick="removeCurrentAssignmentFile()">&times;</button>
                                    </div>

                                    <!-- New file chosen to replace it -->
                                    <div id="edit-asg-new-file" class="cc-pdf-item" style="display:none;">
                                        <i class="fa fa-file-pdf"></i>
                                        <span id="edit-asg-new-file-name"></span>
                                        <button type="button" class="cc-pdf-remove"
                                            onclick="clearNewAssignmentFile()">&times;</button>
                                    </div>

                                    <button type="button" class="cc-add-tab-btn" id="edit-asg-add-file-btn"
                                        style="width:100%;justify-content:center;margin-top:8px;">
                                        <i class="fa fa-plus"></i> <span id="edit-asg-add-file-label">Add File</span>
                                    </button>
                                    <input type="file" name="assignment_edit_file" id="edit-asg-file-input"
                                        accept=".pdf,.ppt,.pptx,.doc,.docx" style="display:none;">
                                </div>

                                <input type="hidden" name="due_date" id="edit-asg-due-date">
                                <input type="hidden" name="due_time" id="edit-asg-due-time">
                            </div>
                            <div class="cc-modal-footer">
                                <button type="button" class="cc-btn-cancel"
                                    onclick="closeEditAssignmentModal()">Cancel</button>
                                <button type="submit" class="cc-btn-submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- HERO BANNER -->
            <!-- <div class="hero-banner" style="background-image:<?= $bannerBg ?>;"> -->
            <div class="hero-banner">
                <div class="hero-overlay-content">
                    <?php if (!empty($classInfo['subject_code'])): ?>
                        <span class="class-badge"><?= htmlspecialchars($classInfo['subject_code']) ?></span>
                    <?php endif; ?>
                    <h2 class="hero-title"><?= htmlspecialchars($classInfo['subject_name'] ?? '') ?></h2>
                    <p class="hero-subtitle">
                        <?= htmlspecialchars($classInfo['subject_code'] ?? '') ?>
                        <?php if (!empty($classInfo['grade'])): ?> &middot;
                            <?= htmlspecialchars($classInfo['grade']) ?><?php endif; ?>
                        <?php if (!empty($classInfo['section'])): ?> &middot;
                            <?= htmlspecialchars($classInfo['section']) ?><?php endif; ?>
                    </p>
                    <p class="hero-tagline">Manage your class content, engage with students, and track their learning
                        progress.</p>
                </div>
            </div>

            <!-- HERO INFO (tabs only now) -->
            <div class="hero-info">
                <div class="tabs">
                    <button class="tab-btn active" data-tab="stream"><i class="fa fa-list"></i> Stream</button>
                    <button class="tab-btn" data-tab="classwork"><i class="fa fa-clipboard-list"></i> Classwork</button>
                    <button class="tab-btn" data-tab="people"><i class="fa fa-users"></i> People</button>
                </div>
            </div>


            <div class="content-layout">
                <div class="content-toolbar">

                    <!-- STREAM TOOLBAR -->
                    <div class="stream-toolbar toolbar-pane active" id="toolbar-stream">
                        <div class="filter-pills">
                            <div class="filter-dropdown">
                                <button type="button" class="filter-pill"
                                    onclick="toggleFilterDropdown('typeFilterMenu')">
                                    <span id="typeFilterLabel">All Posts</span> <i class="fa fa-chevron-down"></i>
                                </button>
                                <div class="filter-dropdown-menu" id="typeFilterMenu">
                                    <a href="javascript:void(0)" data-value="all">All Posts</a>
                                    <a href="javascript:void(0)" data-value="announcement">Announcements</a>
                                    <a href="javascript:void(0)" data-value="module">Materials</a>
                                </div>
                            </div>
                            <div class="filter-dropdown">
                                <button type="button" class="filter-pill"
                                    onclick="toggleFilterDropdown('sortFilterMenu')">
                                    <span id="sortFilterLabel">Most Recent</span> <i class="fa fa-chevron-down"></i>
                                </button>
                                <div class="filter-dropdown-menu" id="sortFilterMenu">
                                    <a href="javascript:void(0)" data-value="recent">Most Recent</a>
                                    <a href="javascript:void(0)" data-value="oldest">Oldest First</a>
                                </div>
                            </div>
                        </div>
                        <div class="create-wrap" style="position:relative;">
                            <div class="create-menu" id="createMenu"></div>
                            <!-- <a class="btn-create"
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>&tab=stream">
                                <i class="fa fa-plus"></i> Create
                            </a> -->
                            <button type="button" class="btn-create" onclick="openCreateModal()">
                                <i class="fa fa-plus"></i> Create
                            </button>
                        </div>
                    </div>

                    <!-- CLASSWORK TOOLBAR -->
                    <div class="stream-toolbar toolbar-pane" id="toolbar-classwork" style="justify-content:flex-end;">
                        <div class="create-wrap" style="position:relative;">
                            <div class="create-menu" id="createMenu2"></div>
                            <!-- <a class="btn-create"
                                href="/learning_management/public/?url=lessons&id=<?= $subject_id ?>&grade_id=<?= $grade_level_id ?>&section_id=<?= $section_id ?? 0 ?>&tab=classwork">
                                <i class="fa fa-plus"></i> Create
                            </a> -->
                            <button type="button" class="btn-create" onclick="openCreateAssignmentModal()">
                                <i class="fa fa-plus"></i> Create
                            </button>
                        </div>
                    </div>

                    <!-- PEOPLE TOOLBAR (Invite Student button lives inside the people tab itself) -->
                </div>
                <div class="main-parent-box">
                    <main>

                        <!-- ════════════ STREAM TAB ════════════ -->
                        <div class="tab-pane active" id="tab-stream">

                            <?php
                            // Merge modules + announcements into chronological feed (newest first)
                            $feed = [];
                            foreach ($cfModules ?? [] as $m) {
                                $feed[] = ['type' => 'module', 'data' => $m, 'time' => strtotime($m['posted_at'])];
                            }
                            foreach ($announcements as $a) {
                                $feed[] = ['type' => 'announcement', 'data' => $a, 'time' => strtotime($a['posted_at'])];
                            }
                            usort($feed, fn($a, $b) => $b['time'] - $a['time']);
                            ?>

                            <?php if (empty($feed)): ?>
                                <div class="empty-state">
                                    <i class="fa fa-layer-group"></i>
                                    <p>No materials posted yet.<br>Click <strong>+ Create</strong> to start.</p>
                                </div>
                            <?php endif; ?>

                            <?php foreach ($feed as $item):
                                $d = $item['data'];
                                ?>

                                <?php if ($item['type'] === 'announcement'): ?>
                                    <!-- ANNOUNCEMENT CARD -->
                                    <div class="stream-card type-announcement" data-post-type="announcement"
                                        data-time="<?= $item['time'] ?>">
                                        <div class="stream-card-inner">
                                            <div class="anm-avatar"><?= $initials ?></div>
                                            <div class="stream-body">
                                                <div class="stream-label">Announcement</div>
                                                <div class="stream-line">
                                                    <?= htmlspecialchars($d['title'] ?? '') ?>
                                                </div>
                                                <?php if (!empty($d['body'])): ?>
                                                    <div class="stream-anm-text"><?= htmlspecialchars($d['body']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-menu-wrap">
                                                <button class="stream-menu" onclick="toggleCardMenu(event, this)">⋮</button>
                                                <div class="card-dropdown">
                                                    <a href="javascript:void(0)" onclick="openEditAnnouncement(this)"
                                                        data-id="<?= (int) $d['id'] ?>"
                                                        data-title="<?= htmlspecialchars($d['title'] ?? '', ENT_QUOTES) ?>"
                                                        data-body="<?= htmlspecialchars($d['body'] ?? '', ENT_QUOTES) ?>">
                                                        <i class="fa fa-pen"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stream-footer">
                                            Date: <?= date('M d, Y', strtotime($d['posted_at'])) ?>
                                        </div>
                                    </div>

                                <?php elseif ($item['type'] === 'module'): ?>
                                    <!-- MODULE CARD -->
                                    <div class="stream-card type-material" data-post-type="module"
                                        data-time="<?= $item['time'] ?>">
                                        <div class="stream-card-inner">
                                            <div class="stream-icon">
                                                <div class="stack-lines"><span></span><span></span><span></span></div>
                                            </div>
                                            <div class="stream-body">
                                                <div class="stream-label" style="color:#16a34a;">New Material</div>
                                                <div class="stream-line"><?= htmlspecialchars($d['title']) ?></div>
                                                <?php if (!empty($d['description'])): ?>
                                                    <div class="stream-anm-text"><?= htmlspecialchars($d['description']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-menu-wrap">
                                                <button class="stream-menu" onclick="toggleCardMenu(event, this)">⋮</button>
                                                <div class="card-dropdown">
                                                    <a href="javascript:void(0)" onclick="openEditModule(this)"
                                                        data-id="<?= (int) $d['id'] ?>"
                                                        data-title="<?= htmlspecialchars($d['title'] ?? '', ENT_QUOTES) ?>"
                                                        data-description="<?= htmlspecialchars($d['description'] ?? '', ENT_QUOTES) ?>"
                                                        data-file-name="<?= htmlspecialchars($d['file_name'] ?? '', ENT_QUOTES) ?>">
                                                        <i class="fa fa-pen"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($d['materials'])): ?>
                                            <div class="stream-files">
                                                <?php foreach ($d['materials'] as $mat):
                                                    if (empty($mat['file_name']) || empty($mat['file_path']))
                                                        continue;
                                                    $ext = strtolower(pathinfo($mat['file_name'], PATHINFO_EXTENSION));

                                                    // Pick icon + color based on extension
                                                    if (in_array($ext, ['ppt', 'pptx'])) {
                                                        $ico = 'fa-file-powerpoint';
                                                        $icoColor = '#d04423';
                                                    } elseif (in_array($ext, ['doc', 'docx'])) {
                                                        $ico = 'fa-file-word';
                                                        $icoColor = '#1e5ebb';
                                                    } else {
                                                        $ico = 'fa-file-pdf';
                                                        $icoColor = '#e53e3e';
                                                    }

                                                    $sizeKb = $mat['file_size'] > 0 ? round($mat['file_size'] / 1024, 1) . ' KB' : '';
                                                    ?>
                                                    <div class="stream-file"
                                                        onclick="openFileViewer('<?= htmlspecialchars($mat['file_path']) ?>', '<?= htmlspecialchars($mat['file_name']) ?>', '<?= $ext ?>')"
                                                        style="cursor:pointer;">
                                                        <i class="fa <?= $ico ?>" style="color:<?= $icoColor ?>"></i>
                                                        <span class="sf-name"><?= htmlspecialchars($mat['file_name']) ?></span>
                                                        <?php if ($sizeKb): ?>
                                                            <span class="sf-meta"><?= strtoupper($ext) ?> · <?= $sizeKb ?></span>
                                                        <?php endif; ?>
                                                        <a class="sf-dl" href="<?= htmlspecialchars($mat['file_path']) ?>"
                                                            download="<?= htmlspecialchars($mat['file_name']) ?>" target="_blank"
                                                            onclick="event.stopPropagation()" title="Download">
                                                            <i class="fa fa-download"></i>
                                                        </a>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="stream-footer">
                                            Date: <?= date('M d, Y', $item['time']) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            <?php endforeach; ?>

                        </div><!-- /tab-stream -->


                        <!-- ════════════ CLASSWORK TAB ════════════ -->
                        <!-- Assignments + student submissions ONLY — nothing else leaks in here -->
                        <div class="tab-pane" id="tab-classwork">

                            <?php if (empty($assignments)): ?>
                                <div class="empty-state">
                                    <i class="fa fa-clipboard-list"></i>
                                    <p>No assignments yet.<br>Click <strong>+ Create</strong> to add one.</p>
                                </div>
                            <?php else: ?>

                                <?php foreach ($assignments as $asgn):
                                    $subCount = count($asgn['submissions'] ?? []);
                                    ?>
                                    <div class="assign-card">
                                        <!-- Assignment header -->
                                        <div class="assign-inner"
                                            onclick="window.location.href='/learning_management/public/?url=student_works&assignment_id=<?= $asgn['id'] ?>&subject_id=<?= $subject_id ?>'"
                                            style="cursor:pointer;">
                                            <div class="assign-icon">
                                                <div class="stack-lines"><span></span><span></span><span></span></div>
                                            </div>
                                            <div class="assign-body">
                                                <div class="assign-label">
                                                    New Assignment
                                                    <span
                                                        style="font-size:11px;font-weight:500;color:#6b7280;margin-left:8px;text-transform:none;">
                                                        <?= htmlspecialchars($classInfo['subject_name'] ?? '') ?>
                                                    </span>
                                                </div>
                                                <div class="assign-line">
                                                    <strong>Title:</strong> <?= htmlspecialchars($asgn['title']) ?>
                                                </div>
                                                <?php if (!empty($asgn['description'])): ?>
                                                    <div class="assign-line">
                                                        <strong>Description:</strong> <?= htmlspecialchars($asgn['description']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-menu-wrap" onclick="event.stopPropagation()">
                                                <button class="stream-menu" onclick="toggleCardMenu(event, this)">⋮</button>
                                                <div class="card-dropdown">
                                                    <a href="javascript:void(0)" onclick="openEditAssignment(this)"
                                                        data-id="<?= (int) $asgn['id'] ?>"
                                                        data-title="<?= htmlspecialchars($asgn['title'] ?? '', ENT_QUOTES) ?>"
                                                        data-description="<?= htmlspecialchars($asgn['description'] ?? '', ENT_QUOTES) ?>"
                                                        data-task="<?= htmlspecialchars($asgn['task'] ?? '', ENT_QUOTES) ?>"
                                                        data-instructions="<?= htmlspecialchars($asgn['instructions'] ?? '', ENT_QUOTES) ?>"
                                                        data-type="<?= htmlspecialchars($asgn['type'] ?? 'Seatwork', ENT_QUOTES) ?>"
                                                        data-points="<?= (int) ($asgn['points'] ?? 100) ?>"
                                                        data-due-date="<?= !empty($asgn['due_date']) ? date('Y-m-d', strtotime($asgn['due_date'])) : '' ?>"
                                                        data-due-time="<?= !empty($asgn['due_time']) ? date('H:i', strtotime($asgn['due_time'])) : '23:59' ?>"
                                                        data-file-name="<?= htmlspecialchars($asgn['file_name'] ?? '', ENT_QUOTES) ?>">
                                                        <i class="fa fa-pen"></i> Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (!empty($asgn['file_name']) && !empty($asgn['file_path'])): ?>
                                            <?php
                                            $ext = strtolower(pathinfo($asgn['file_name'], PATHINFO_EXTENSION));
                                            if (in_array($ext, ['ppt', 'pptx'])) {
                                                $ico = 'fa-file-powerpoint';
                                                $icoColor = '#d04423';
                                            } elseif (in_array($ext, ['doc', 'docx'])) {
                                                $ico = 'fa-file-word';
                                                $icoColor = '#1e5ebb';
                                            } else {
                                                $ico = 'fa-file-pdf';
                                                $icoColor = '#e53e3e';
                                            }
                                            $sizeKb = !empty($asgn['file_size']) && $asgn['file_size'] > 0
                                                ? round($asgn['file_size'] / 1024, 1) . ' KB' : '';
                                            ?>
                                            <div class="stream-files">
                                                <div class="stream-file"
                                                    onclick="event.stopPropagation(); openFileViewer('<?= htmlspecialchars($asgn['file_path']) ?>', '<?= htmlspecialchars($asgn['file_name']) ?>', '<?= $ext ?>')"
                                                    style="cursor:pointer;">
                                                    <i class="fa <?= $ico ?>" style="color:<?= $icoColor ?>"></i>
                                                    <span class="sf-name">
                                                        <?= htmlspecialchars($asgn['file_name']) ?>
                                                    </span>
                                                    <?php if ($sizeKb): ?>
                                                        <span class="sf-meta">
                                                            <?= strtoupper($ext) ?> ·
                                                            <?= $sizeKb ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <a class="sf-dl" href="<?= htmlspecialchars($asgn['file_path']) ?>"
                                                        download="<?= htmlspecialchars($asgn['file_name']) ?>"
                                                        onclick="event.stopPropagation()" title="Download">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="assign-footer">
                                            <span>Due Date:
                                                <?= !empty($asgn['due_date']) ? date('M d', strtotime($asgn['due_date'])) : '—' ?>
                                                <?= !empty($asgn['due_time']) ? ' at ' . date('h:i A', strtotime($asgn['due_time'])) : '' ?>
                                            </span>

                                            <div class="submit-points">
                                                <?php if ($subCount > 0): ?>
                                                    <span class="sub-count-badge"><?= $subCount ?> submitted</span>
                                                <?php endif; ?>

                                                <span class="assign-pts"><?= (int) ($asgn['points'] ?? 100) ?> pts</span>
                                            </div>
                                        </div>

                                        <!-- Student submissions (accordion) -->
                                        <div class="assign-submissions">
                                            <?php if (empty($asgn['submissions'])): ?>
                                                <p style="font-size:12px;color:#9ca3af;text-align:center;padding:.75rem 0;">
                                                    <i class="fa fa-inbox me-1"></i> No submissions yet.
                                                </p>
                                            <?php else: ?>
                                                <?php foreach ($asgn['submissions'] as $sub):
                                                    $stuInitials = getInitials($sub['student_name'] ?? 'S');
                                                    $status = $sub['status'] ?? 'submitted';
                                                    $statusLabel = ucfirst($status);

                                                    $subExt = '';
                                                    $subFilePath = '';
                                                    $subFileName = '';
                                                    $subOriginalName = '';
                                                    if (!empty($sub['file_path'])) {
                                                        $subFilePath = $sub['file_path'];
                                                        if (!str_starts_with($subFilePath, '/') && !str_starts_with($subFilePath, 'http')) {
                                                            $subFilePath = '/learning_management/' . $subFilePath;
                                                        }
                                                        $subFileName = basename($sub['file_path']);
                                                        $subExt = strtolower(pathinfo($subFileName, PATHINFO_EXTENSION));

                                                        // Strip the unique prefix (e.g. "69cf73c9475c0_") to get the original name
                                                        $subOriginalName = preg_replace('/^[a-f0-9]+_/', '', $subFileName);
                                                    }

                                                    // Pick icon based on extension
                                                    if (in_array($subExt, ['ppt', 'pptx'])) {
                                                        $subIco = 'fa-file-powerpoint';
                                                        $subIcoColor = '#d04423';
                                                    } elseif (in_array($subExt, ['doc', 'docx'])) {
                                                        $subIco = 'fa-file-word';
                                                        $subIcoColor = '#1e5ebb';
                                                    } elseif ($subExt === 'pdf') {
                                                        $subIco = 'fa-file-pdf';
                                                        $subIcoColor = '#e53e3e';
                                                    } else {
                                                        $subIco = 'fa-file-arrow-down';
                                                        $subIcoColor = '#6b7280';
                                                    }

                                                    // Format submitted time — show time if today, otherwise date + time
                                                    $submittedAt = '';
                                                    $submittedTime = '';
                                                    if (!empty($sub['submitted_at'])) {
                                                        $ts = strtotime($sub['submitted_at']);
                                                        $submittedAt = date('M d', $ts);
                                                        $submittedTime = date('h:i A', $ts); // e.g. 02:35 PM
                                                    }
                                                    ?>
                                                    <div class="sub-row">
                                                        <div class="sub-avatar"><?= $stuInitials ?></div>

                                                        <!-- Student name -->
                                                        <span
                                                            class="sub-name"><?= htmlspecialchars($sub['student_name'] ?? '—') ?></span>

                                                        <!-- File chip — shows cleaned original filename, clickable to open viewer -->
                                                        <?php if (!empty($sub['file_path'])): ?>
                                                            <a class="sub-file-chip" href="javascript:void(0)"
                                                                onclick="openFileViewer('<?= htmlspecialchars($subFilePath) ?>', '<?= htmlspecialchars($subFileName) ?>', '<?= $subExt ?>')"
                                                                title="View <?= htmlspecialchars($subOriginalName) ?>">
                                                                <i class="fa <?= $subIco ?>" style="color:<?= $subIcoColor ?>;"></i>
                                                                <span><?= htmlspecialchars($subOriginalName) ?></span>
                                                            </a>
                                                        <?php else: ?>
                                                            <span style="flex:1;"></span>
                                                        <?php endif; ?>

                                                        <!-- Submitted date + time instead of download icon -->
                                                        <?php if ($submittedAt): ?>
                                                            <span class="sub-date">
                                                                <?= $submittedAt ?> · <span
                                                                    style="color:#374151;font-weight:600;"><?= $submittedTime ?></span>
                                                            </span>
                                                        <?php endif; ?>

                                                        <span class="sub-status <?= $status ?>"><?= $statusLabel ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            <?php endif; ?>

                        </div><!-- /tab-classwork -->


                        <!-- ════════════ PEOPLE TAB ════════════ -->
                        <!-- Student roster ONLY — no assignment/classwork content here -->
                        <div class="tab-pane" id="tab-people">
                            <div class="people-header">
                                <div>
                                    <h4 style="font-size:16px;font-weight:800;color:#111827;margin:0;">
                                        <?= count($enrolledStudents ?? []) ?> students
                                    </h4>
                                </div>
                                <button onclick="openInviteModal()" style="background:#00C950;color:#fff;border:none;border-radius:50px;
   padding:10px 20px;font-size:13px;font-weight:700;cursor:pointer;
   display:flex;align-items:center;gap:8px;">
                                    <i class="fa fa-envelope"></i> Invite Student
                                </button>
                            </div>
                            <div class="list-people">
                                <?php if (empty($enrolledStudents)): ?>
                                    <div class="empty-state">
                                        <i class="fa fa-users"></i>
                                        <p>No students enrolled yet.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($enrolledStudents as $stu):
                                        $stuInitial = strtoupper(substr($stu['name'], 0, 1));
                                        $sectionLabel = $stu['section_name'];
                                        ?>
                                        <div class="student">
                                            <div class="student-header">
                                                <div class="icon">
                                                    <span><?= $stuInitial ?></span>
                                                </div>
                                                <p><?= htmlspecialchars($stu['name']) ?></p>
                                            </div>
                                            <div class="student-section">
                                                <p><?= htmlspecialchars($sectionLabel) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div><!-- /tab-people -->

                    </main>
                    <aside class="sidebar-col">
                        <div class="info-card">
                            <div class="info-card-header"><i class="fa fa-circle-info"></i> About this class</div>
                            <div class="info-row">
                                <span class="info-label">Subject Code</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($classInfo['subject_code'] ?? '—') ?>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Teacher</span>
                                <span class="info-value">
                                    <?= htmlspecialchars($teacherName) ?>
                                </span>
                            </div>
                            <?php if (!empty($classInfo['schedule'])): ?>
                                <div class="info-row">
                                    <span class="info-label">Schedule</span>
                                    <span class="info-value"><?= htmlspecialchars($classInfo['schedule']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($classInfo['room'])): ?>
                                <div class="info-row">
                                    <span class="info-label">Room</span>
                                    <span class="info-value"><?= htmlspecialchars($classInfo['room']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="info-card">
                            <div class="info-card-header"><i class="fa fa-chart-line"></i> Class Progress Overview</div>
                            <div class="donut-wrap">
                                <div class="donut"
                                    style="background: conic-gradient(#1447e6 <?= $progressStats['percentage'] * 3.6 ?>deg, #e9ecf1 0deg);">
                                    <div class="donut-hole">
                                        <?= $progressStats['percentage'] ?>%
                                    </div>
                                </div>
                            </div>
                            <div class="legend-row"><span class="dot dot-blue"></span> Submitted <b>
                                    <?= $progressStats['submitted'] ?>
                                </b></div>
                            <div class="legend-row"><span class="dot dot-green"></span> Graded <b>
                                    <?= $progressStats['graded'] ?>
                                </b></div>
                            <div class="legend-row"><span class="dot dot-orange"></span> Pending <b>
                                    <?= $progressStats['pending'] ?>
                                </b></div>
                            <div class="total-students">Total Students: <b>
                                    <?= $progressStats['total_students'] ?>
                                </b></div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>

        <!-- ANNOUNCEMENT MODAL -->
        <div class="modal fade" id="announceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="fa fa-bullhorn text-warning me-2"></i>New Announcement
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="/learning_management/public/?url=save_announcement">
                        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                        <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                        <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Announcement title…"
                                    required>
                            </div>
                            <div>
                                <label class="form-label fw-semibold">Message</label>
                                <textarea name="body" class="form-control" rows="5" placeholder="Write your message…"
                                    required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fa fa-bullhorn me-1"></i> Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ASSIGNMENT MODAL -->
        <div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="fa fa-clipboard-list text-primary me-2"></i>New Assignment
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="/learning_management/public/?url=save_assignment">
                        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                        <input type="hidden" name="grade_level_id" value="<?= $grade_level_id ?>">
                        <input type="hidden" name="section_id" value="<?= $section_id ?? 0 ?>">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Assignment title…"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4"
                                    placeholder="Instructions…"></textarea>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Due Date</label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Points</label>
                                    <input type="number" name="points" class="form-control" value="100" min="1">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-paper-plane me-1"></i> Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- /rightbar -->

    <!-- PDF VIEWER MODAL -->
    <div class="pdf-modal-overlay" id="pdfModalOverlay" onclick="closePdfViewer(event)">
        <div class="pdf-modal" onclick="event.stopPropagation()">
            <div class="pdf-modal-header">
                <div class="pdf-icon"><i class="fa fa-file-pdf"></i></div>
                <span class="pdf-modal-title" id="pdfModalTitle">Document</span>
                <div class="pdf-modal-actions">
                    <a id="pdfDownloadBtn" href="#" target="_blank">
                        <i class="fa fa-download"></i> Download
                    </a>
                    <button class="btn-close-pdf" onclick="closePdfViewerBtn()">✕</button>
                </div>
            </div>
            <div class="pdf-modal-body">
                <iframe id="pdfModalFrame" src="" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- SAVE SUCCESS TOAST -->
    <div id="saveToast" style="
    display:none;
    position:fixed;
    bottom:28px;
    right:28px;
    background:#111827;
    color:#fff;
    padding:13px 22px;
    border-radius:50px;
    font-size:13px;
    font-weight:600;
    z-index:10001;
    box-shadow:0 4px 20px rgba(0,0,0,.25);
    align-items:center;
    gap:10px;
">
        <span style="color:#00C950;font-size:16px;">✓</span>
        <span id="saveToastMsg">Saved successfully!</span>
    </div>


    </div>

    <script src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>



    <script>
        // ── CARD DROPDOWN (⋮ menu) ──
        window.toggleCardMenu = function (e, btn) {
            e.stopPropagation();
            const dropdown = btn.nextElementSibling;
            const isOpen = dropdown.classList.contains('open');
            document.querySelectorAll('.card-dropdown.open').forEach(d => d.classList.remove('open'));
            if (!isOpen) dropdown.classList.add('open');
        };

        window.addEventListener('click', function () {
            document.querySelectorAll('.card-dropdown.open').forEach(d => d.classList.remove('open'));
        });

        // ── EDIT ANNOUNCEMENT ──
        window.openEditAnnouncement = function (el) {
            document.getElementById('edit-ann-id').value = el.dataset.id;
            document.getElementById('edit-ann-title').value = el.dataset.title;
            document.getElementById('edit-ann-body').value = el.dataset.body;
            document.getElementById('editAnnouncementOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        };
        window.closeEditAnnouncementModal = function () {
            document.getElementById('editAnnouncementOverlay').classList.remove('open');
            document.body.style.overflow = '';
        };

        // ── EDIT MODULE (material) — with file handling ──
        window.openEditModule = function (el) {
            document.getElementById('edit-mod-id').value = el.dataset.id;
            document.getElementById('edit-mod-title').value = el.dataset.title;
            document.getElementById('edit-mod-description').value = el.dataset.description;

            // Reset file UI state
            document.getElementById('edit-mod-remove-file').value = '';
            document.getElementById('edit-mod-file-input').value = '';
            document.getElementById('edit-mod-new-file').style.display = 'none';

            const currentFileName = el.dataset.fileName || '';
            const currentBox = document.getElementById('edit-mod-current-file');
            const addBtnLabel = document.getElementById('edit-mod-add-file-label');

            if (currentFileName) {
                document.getElementById('edit-mod-current-file-name').textContent = currentFileName;
                currentBox.style.display = 'flex';
                addBtnLabel.textContent = 'Replace File';
            } else {
                currentBox.style.display = 'none';
                addBtnLabel.textContent = 'Add File';
            }

            document.getElementById('editModuleOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        };
        window.closeEditModuleModal = function () {
            document.getElementById('editModuleOverlay').classList.remove('open');
            document.body.style.overflow = '';
        };

        // Mark the existing material file for removal (clears it if no replacement is chosen)
        window.removeCurrentModuleFile = function () {
            document.getElementById('edit-mod-current-file').style.display = 'none';
            document.getElementById('edit-mod-remove-file').value = '1';
            document.getElementById('edit-mod-add-file-label').textContent = 'Add File';
        };

        window.clearNewModuleFile = function () {
            document.getElementById('edit-mod-file-input').value = '';
            document.getElementById('edit-mod-new-file').style.display = 'none';
        };

        document.getElementById('edit-mod-add-file-btn').addEventListener('click', function () {
            document.getElementById('edit-mod-file-input').click();
        });
        document.getElementById('edit-mod-file-input').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            document.getElementById('edit-mod-new-file-name').textContent = file.name;
            document.getElementById('edit-mod-new-file').style.display = 'flex';
            // A fresh upload overrides any pending "remove" state
            document.getElementById('edit-mod-remove-file').value = '';
            document.getElementById('edit-mod-current-file').style.display = 'none';
        });

        // ── EDIT ASSIGNMENT — with file handling ──
        window.openEditAssignment = function (el) {
            document.getElementById('edit-asg-id').value = el.dataset.id;
            document.getElementById('edit-asg-title').value = el.dataset.title;
            document.getElementById('edit-asg-description').value = el.dataset.description;
            document.getElementById('edit-asg-task').value = el.dataset.task;
            document.getElementById('edit-asg-instructions').value = el.dataset.instructions;
            document.getElementById('edit-asg-type').value = el.dataset.type || 'Seatwork';
            document.getElementById('edit-asg-points').value = el.dataset.points;
            document.getElementById('edit-asg-due-date').value = el.dataset.dueDate;
            document.getElementById('edit-asg-due-time').value = el.dataset.dueTime;

            // Reset file UI state
            document.getElementById('edit-asg-remove-file').value = '';
            document.getElementById('edit-asg-file-input').value = '';
            document.getElementById('edit-asg-new-file').style.display = 'none';

            const currentFileName = el.dataset.fileName || '';
            const currentBox = document.getElementById('edit-asg-current-file');
            const addBtnLabel = document.getElementById('edit-asg-add-file-label');

            if (currentFileName) {
                document.getElementById('edit-asg-current-file-name').textContent = currentFileName;
                currentBox.style.display = 'flex';
                addBtnLabel.textContent = 'Replace File';
            } else {
                currentBox.style.display = 'none';
                addBtnLabel.textContent = 'Add File';
            }

            document.getElementById('editAssignmentOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        };
        window.closeEditAssignmentModal = function () {
            document.getElementById('editAssignmentOverlay').classList.remove('open');
            document.body.style.overflow = '';
        };

        window.removeCurrentAssignmentFile = function () {
            document.getElementById('edit-asg-current-file').style.display = 'none';
            document.getElementById('edit-asg-remove-file').value = '1';
            document.getElementById('edit-asg-add-file-label').textContent = 'Add File';
        };

        window.clearNewAssignmentFile = function () {
            document.getElementById('edit-asg-file-input').value = '';
            document.getElementById('edit-asg-new-file').style.display = 'none';
        };

        document.getElementById('edit-asg-add-file-btn').addEventListener('click', function () {
            document.getElementById('edit-asg-file-input').click();
        });
        document.getElementById('edit-asg-file-input').addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            document.getElementById('edit-asg-new-file-name').textContent = file.name;
            document.getElementById('edit-asg-new-file').style.display = 'flex';
            document.getElementById('edit-asg-remove-file').value = '';
            document.getElementById('edit-asg-current-file').style.display = 'none';
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeEditAnnouncementModal();
                closeEditModuleModal();
                closeEditAssignmentModal();
            }
        });
    </script>

    <script>
        // ===============================
        // INVITE MODAL
        // ===============================
        window.openInviteModal = function () {
            document.getElementById('inviteOverlay').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };

        window.closeInviteModal = function () {
            document.getElementById('inviteOverlay').style.display = 'none';
            document.body.style.overflow = '';
        };

        window.filterInviteStudents = function () {
            const q = document.getElementById('inviteSearch').value.toLowerCase();
            document.querySelectorAll('.invite-stu-row').forEach(row => {
                const name = row.querySelector('.invite-stu-check').dataset.name;
                const email = row.querySelector('.invite-stu-check').dataset.email;
                row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
            });
            updateInviteCount();
        };

        window.toggleInviteAll = function () {
            const checked = document.getElementById('inviteSelectAll').checked;
            document.querySelectorAll('.invite-stu-check').forEach(c => {
                // Skip disabled checkboxes (pending/accepted students)
                if (!c.disabled && c.closest('.invite-stu-row').style.display !== 'none') {
                    c.checked = checked;
                }
            });
            updateInviteCount();
        };

        window.updateInviteCount = function () {
            const total = document.querySelectorAll('.invite-stu-check:checked').length;
            const countEl = document.getElementById('inviteSelectedCount');
            const btn = document.getElementById('inviteSendBtn');
            countEl.textContent = total ? `${total} student${total > 1 ? 's' : ''} selected` : '';
            btn.disabled = total === 0;
            btn.style.opacity = total === 0 ? '0.5' : '1';
        };

        // ── SINGLE definition — sends one by one with X-Requested-With header ──
        window.submitInvitations = function () {
            const emails = [...document.querySelectorAll('.invite-stu-check:checked')].map(c => c.value);
            if (!emails.length) return;

            const btn = document.getElementById('inviteSendBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending…';

            const promises = emails.map(email => {
                const form = new FormData();
                form.append('student_email', email);
                form.append('subject_id', '<?= $subject_id ?>');
                form.append('grade_level_id', '<?= $grade_level_id ?>');
                form.append('section_id', '<?= $section_id ?? 0 ?>');

                return fetch('/learning_management/public/?url=send_invitation', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: form
                })
                    .then(res => res.json())
                    .catch(() => ({ success: false }));
            });

            Promise.all(promises).then(results => {
                const successCount = results.filter(r => r && r.success).length;
                const toast = document.getElementById('inviteToast');

                closeInviteModal();

                toast.style.background = successCount > 0 ? '#111827' : '#dc2626';
                toast.textContent = successCount > 0
                    ? `✓ ${successCount} invitation${successCount > 1 ? 's' : ''} sent!`
                    : `✗ Failed to send. Please try again.`;
                toast.style.display = 'block';

                // Save tab preference BEFORE reload
                sessionStorage.setItem('activeTab', 'people');

                setTimeout(() => {
                    toast.style.display = 'none';
                    window.location.reload();
                }, 1500);
            });
        };

        // ===============================
        // CREATE MENU
        // ===============================
        window.toggleCreateMenu = function () {
            document.getElementById('createMenu')?.classList.toggle('open');
        };

        window.toggleCreateMenu2 = function () {
            document.getElementById('createMenu2')?.classList.toggle('open');
        };

        // ===============================
        // DOM READY
        // ===============================
        document.addEventListener("DOMContentLoaded", function () {

            // TABS
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            function activateTab(tabName) {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                document.querySelectorAll('.toolbar-pane').forEach(t => t.classList.remove('active'));

                const btn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
                const pane = document.getElementById('tab-' + tabName);
                const toolbar = document.getElementById('toolbar-' + tabName); // null for 'people' — intentional
                const contentLayout = document.querySelector('.content-layout');

                if (btn) btn.classList.add('active');
                if (pane) pane.classList.add('active');
                if (toolbar) toolbar.classList.add('active');

                // Toggle the people-only layout mode (hides toolbar + sidebar, expands main)
                if (contentLayout) {
                    contentLayout.classList.toggle('people-active', tabName === 'people');
                }
            }

            // Restore tab after reload (set by submitInvitations)
            const savedTab = sessionStorage.getItem('activeTab');
            if (savedTab) {
                activateTab(savedTab);
                sessionStorage.removeItem('activeTab');
            }

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    activateTab(btn.dataset.tab);
                });
            });

            // ── AUTO-TAB + TOAST on redirect back ──────────────────────
            (function () {
                const params = new URLSearchParams(window.location.search);
                const saved = params.get('saved'); // 'stream' | 'classwork' | null
                if (!saved) return;

                // Activate the right tab
                activateTab(saved === 'classwork' ? 'classwork' : 'stream');

                // Show toast
                const toast = document.getElementById('saveToast');
                const msg = document.getElementById('saveToastMsg');
                if (!toast) return;

                msg.textContent = saved === 'classwork'
                    ? 'Assignment created successfully!'
                    : 'Content created successfully!';

                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity .4s ease';
                    setTimeout(() => {
                        toast.style.display = 'none';
                        toast.style.opacity = '1';
                        toast.style.transition = '';
                    }, 400);
                }, 3000);

                // Clean URL (remove ?saved= without reload)
                const cleanUrl = window.location.href.replace(/&saved=[^&]*/, '');
                window.history.replaceState({}, '', cleanUrl);
            })();

            // CLOSE CREATE MENU ON OUTSIDE CLICK
            window.addEventListener('click', function (e) {
                const btn1 = document.getElementById('createBtn');
                const btn2 = document.getElementById('createBtn2');
                if (btn1 && !btn1.contains(e.target)) {
                    document.getElementById('createMenu')?.classList.remove('open');
                }
                if (btn2 && !btn2.contains(e.target)) {
                    document.getElementById('createMenu2')?.classList.remove('open');
                }
            });

            // PDF VIEWER
            window.openFileViewer = function (filePath, fileName, ext) {
                const viewable = ['pdf'];
                if (!filePath.startsWith('/') && !filePath.startsWith('http')) {
                    filePath = '/learning_management/' + filePath;
                }
                if (viewable.includes(ext.toLowerCase())) {
                    document.getElementById('pdfModalTitle').textContent = fileName;
                    document.getElementById('pdfModalFrame').src = filePath;
                    document.getElementById('pdfDownloadBtn').href = filePath;
                    document.getElementById('pdfModalOverlay').classList.add('open');
                    document.body.style.overflow = 'hidden';
                } else {
                    window.open(filePath, '_blank');
                }
            };

            window.closePdfViewer = function (e) {
                if (e && e.target !== document.getElementById('pdfModalOverlay')) return;
                closePdfViewerBtn();
            };

            window.closePdfViewerBtn = function () {
                document.getElementById('pdfModalOverlay').classList.remove('open');
                document.getElementById('pdfModalFrame').src = '';
                document.body.style.overflow = '';
            };

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closePdfViewerBtn();
            });
        });

        window.toggleFilterDropdown = function (id) {
            const menu = document.getElementById(id);
            const isOpen = menu.classList.contains('open');
            document.querySelectorAll('.filter-dropdown-menu').forEach(m => m.classList.remove('open'));
            if (!isOpen) menu.classList.add('open');
        };

        // Close dropdowns on outside click
        window.addEventListener('click', function (e) {
            if (!e.target.closest('.filter-dropdown')) {
                document.querySelectorAll('.filter-dropdown-menu').forEach(m => m.classList.remove('open'));
            }
        });

        let currentTypeFilter = 'all';
        let currentSort = 'recent';

        function applyStreamFilters() {
            const container = document.getElementById('tab-stream');
            const cards = Array.from(container.querySelectorAll('.stream-card'));

            // Filter by type
            cards.forEach(card => {
                const matches = currentTypeFilter === 'all' || card.dataset.postType === currentTypeFilter;
                card.style.display = matches ? '' : 'none';
            });

            // Sort by time (only re-orders visible + hidden together, safe either way)
            const sorted = cards.slice().sort((a, b) => {
                const ta = parseInt(a.dataset.time, 10) || 0;
                const tb = parseInt(b.dataset.time, 10) || 0;
                return currentSort === 'recent' ? tb - ta : ta - tb;
            });
            sorted.forEach(card => container.appendChild(card));

            // Show/hide empty-state message if everything is filtered out
            let emptyMsg = container.querySelector('.filter-empty-state');
            const anyVisible = cards.some(c => c.style.display !== 'none');
            if (!anyVisible) {
                if (!emptyMsg) {
                    emptyMsg = document.createElement('div');
                    emptyMsg.className = 'empty-state filter-empty-state';
                    emptyMsg.innerHTML = '<i class="fa fa-filter"></i><p>No posts match this filter.</p>';
                    container.appendChild(emptyMsg);
                }
                emptyMsg.style.display = '';
            } else if (emptyMsg) {
                emptyMsg.style.display = 'none';
            }
        }

        document.querySelectorAll('#typeFilterMenu a').forEach(a => {
            a.addEventListener('click', () => {
                currentTypeFilter = a.dataset.value;
                document.getElementById('typeFilterLabel').textContent = a.textContent;
                document.querySelectorAll('#typeFilterMenu a').forEach(x => x.classList.toggle('active', x === a));
                document.getElementById('typeFilterMenu').classList.remove('open');
                applyStreamFilters();
            });
        });

        document.querySelectorAll('#sortFilterMenu a').forEach(a => {
            a.addEventListener('click', () => {
                currentSort = a.dataset.value;
                document.getElementById('sortFilterLabel').textContent = a.textContent;
                document.querySelectorAll('#sortFilterMenu a').forEach(x => x.classList.toggle('active', x === a));
                document.getElementById('sortFilterMenu').classList.remove('open');
                applyStreamFilters();
            });
        });
    </script>

    <script>
        let ccModuleCount = 0;
        let ccAnnCount = 0;

        window.openCreateModal = function () {
            document.getElementById('createContentOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
            if (ccModuleCount === 0) addModuleTab();
            if (ccAnnCount === 0) addAnnouncementTab();
        };

        window.closeCreateModal = function () {
            document.getElementById('createContentOverlay').classList.remove('open');
            document.body.style.overflow = '';
        };

        window.switchCreateType = function (type) {
            document.querySelectorAll('.cc-type-btn').forEach(b => b.classList.toggle('active', b.dataset.type === type));
            document.getElementById('cc-panel-module').classList.toggle('active', type === 'module');
            document.getElementById('cc-panel-announcement').classList.toggle('active', type === 'announcement');
        };

        // ── MODULE TABS ──
        function activateModuleTab(idx) {
            document.querySelectorAll('#cc-module-tabbar .cc-tab').forEach(t => t.classList.toggle('active', +t.dataset.idx === idx));
            document.querySelectorAll('#cc-module-panels .cc-tabpanel').forEach(p => p.classList.toggle('active', +p.dataset.idx === idx));
        }

        function addModuleTab() {
            const idx = ccModuleCount++;
            const tabbar = document.getElementById('cc-module-tabbar');
            const panels = document.getElementById('cc-module-panels');

            const tab = document.createElement('div');
            tab.className = 'cc-tab';
            tab.dataset.idx = idx;
            tab.innerHTML = `<span class="cc-tab-label">Module</span><button type="button" class="cc-tab-x">&times;</button>`;
            tab.querySelector('.cc-tab-label').addEventListener('click', () => activateModuleTab(idx));
            tab.querySelector('.cc-tab-x').addEventListener('click', e => { e.stopPropagation(); removeModuleTab(idx); });
            tabbar.insertBefore(tab, document.getElementById('cc-add-module-tab'));

            const panel = document.createElement('div');
            panel.className = 'cc-tabpanel';
            panel.dataset.idx = idx;
            panel.innerHTML = `
        <div class="cc-field">
            <label class="cc-label">Module Title *</label>
            <input type="text" name="cf_module_title[]" class="cc-input" placeholder="e.g. Module ${idx + 1}: Week 1-2" required>
        </div>
        <div class="cc-field">
            <label class="cc-label">Description *</label>
            <textarea name="cf_module_description[]" class="cc-input cc-textarea" placeholder="Brief description of this module" required></textarea>
        </div>
        <div class="cc-field">
            <label class="cc-label">Attach PDF / Materials *</label>
            <div class="cc-pdf-list"></div>
            <button type="button" class="cc-add-file-btn cc-add-tab-btn" style="width:100%;justify-content:center;margin-top:8px;">
                <i class="fa fa-plus"></i> Add File
            </button>
            <input type="file" name="cf_module_pdf[${idx}][]" class="cc-file-input" accept=".pdf,.ppt,.pptx,.doc,.docx" multiple style="display:none;" required>
        </div>`;
            panels.appendChild(panel);

            const addFileBtn = panel.querySelector('.cc-add-file-btn');
            const fileInput = panel.querySelector('.cc-file-input');
            const pdfList = panel.querySelector('.cc-pdf-list');
            addFileBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => {
                Array.from(fileInput.files).forEach(file => {
                    const item = document.createElement('div');
                    item.className = 'cc-pdf-item';
                    item.innerHTML = `<i class="fa fa-file-pdf"></i><span>${file.name}</span><button type="button" class="cc-pdf-remove">&times;</button>`;
                    item.querySelector('.cc-pdf-remove').addEventListener('click', () => item.remove());
                    pdfList.appendChild(item);
                });
            });

            activateModuleTab(idx);
        }

        function removeModuleTab(idx) {
            const tabbar = document.getElementById('cc-module-tabbar');
            const panels = document.getElementById('cc-module-panels');
            const tab = tabbar.querySelector(`.cc-tab[data-idx="${idx}"]`);
            const panel = panels.querySelector(`.cc-tabpanel[data-idx="${idx}"]`);
            const wasActive = tab.classList.contains('active');
            tab.remove();
            panel.remove();

            if (tabbar.querySelectorAll('.cc-tab').length === 0) { addModuleTab(); return; }
            if (wasActive) activateModuleTab(+tabbar.querySelector('.cc-tab').dataset.idx);
        }

        document.getElementById('cc-add-module-tab').addEventListener('click', addModuleTab);

        // ── ANNOUNCEMENT TABS ──
        function activateAnnTab(idx) {
            document.querySelectorAll('#cc-ann-tabbar .cc-tab').forEach(t => t.classList.toggle('active', +t.dataset.idx === idx));
            document.querySelectorAll('#cc-ann-panels .cc-tabpanel').forEach(p => p.classList.toggle('active', +p.dataset.idx === idx));
        }

        function addAnnouncementTab() {
            const idx = ccAnnCount++;
            const tabbar = document.getElementById('cc-ann-tabbar');
            const panels = document.getElementById('cc-ann-panels');

            const tab = document.createElement('div');
            tab.className = 'cc-tab';
            tab.dataset.idx = idx;
            tab.innerHTML = `<span class="cc-tab-label">Announcement</span><button type="button" class="cc-tab-x">&times;</button>`;
            tab.querySelector('.cc-tab-label').addEventListener('click', () => activateAnnTab(idx));
            tab.querySelector('.cc-tab-x').addEventListener('click', e => { e.stopPropagation(); removeAnnTab(idx); });
            tabbar.insertBefore(tab, document.getElementById('cc-add-ann-tab'));

            const panel = document.createElement('div');
            panel.className = 'cc-tabpanel';
            panel.dataset.idx = idx;
            panel.innerHTML = `
        <div class="cc-field">
            <label class="cc-label">Title *</label>
            <input type="text" name="announcement_title[]" class="cc-input" placeholder="e.g. No class on Friday" required>
        </div>
        <div class="cc-field">
            <label class="cc-label">Message *</label>
            <textarea name="announcement_message[]" class="cc-input cc-textarea" placeholder="Write your announcement here..." required></textarea>
        </div>`;
            panels.appendChild(panel);
            activateAnnTab(idx);
        }

        function removeAnnTab(idx) {
            const tabbar = document.getElementById('cc-ann-tabbar');
            const panels = document.getElementById('cc-ann-panels');
            const tab = tabbar.querySelector(`.cc-tab[data-idx="${idx}"]`);
            const panel = panels.querySelector(`.cc-tabpanel[data-idx="${idx}"]`);
            const wasActive = tab.classList.contains('active');
            tab.remove();
            panel.remove();

            if (tabbar.querySelectorAll('.cc-tab').length === 0) { addAnnouncementTab(); return; }
            if (wasActive) activateAnnTab(+tabbar.querySelector('.cc-tab').dataset.idx);
        }

        document.getElementById('cc-add-ann-tab').addEventListener('click', addAnnouncementTab);
    </script>

    <script>
        let ccAssignCount = 0;

        window.openCreateAssignmentModal = function () {
            document.getElementById('createAssignmentOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
            if (ccAssignCount === 0) addAssignmentTab();
        };

        window.closeCreateAssignmentModal = function () {
            document.getElementById('createAssignmentOverlay').classList.remove('open');
            document.body.style.overflow = '';
        };

        function activateAssignTab(idx) {
            document.querySelectorAll('#cc-assign-tabbar .cc-tab').forEach(t => t.classList.toggle('active', +t.dataset.idx === idx));
            document.querySelectorAll('#cc-assign-panels .cc-tabpanel').forEach(p => p.classList.toggle('active', +p.dataset.idx === idx));
        }

        function addAssignmentTab() {
            const idx = ccAssignCount++;
            const tabbar = document.getElementById('cc-assign-tabbar');
            const panels = document.getElementById('cc-assign-panels');

            const tab = document.createElement('div');
            tab.className = 'cc-tab';
            tab.dataset.idx = idx;
            tab.innerHTML = `<span class="cc-tab-label">Assignment</span><button type="button" class="cc-tab-x">&times;</button>`;
            tab.querySelector('.cc-tab-label').addEventListener('click', () => activateAssignTab(idx));
            tab.querySelector('.cc-tab-x').addEventListener('click', e => { e.stopPropagation(); removeAssignmentTab(idx); });
            tabbar.insertBefore(tab, document.getElementById('cc-add-assign-tab'));

            const panel = document.createElement('div');
            panel.className = 'cc-tabpanel';
            panel.dataset.idx = idx;
            panel.innerHTML = `
        <div class="cc-field">
            <label class="cc-label">Title *</label>
            <input type="text" name="assignment_title[]" class="cc-input" placeholder="Enter Title" required>
        </div>
        <div class="cc-field">
            <label class="cc-label">Description</label>
            <textarea name="assignment_description[]" class="cc-input cc-textarea" placeholder="Enter Description"></textarea>
        </div>
        <div class="cc-field">
            <label class="cc-label">Task *</label>
            <input type="text" name="assignment_task[]" class="cc-input" placeholder="e.g. Essay, Research, Seatwork">
        </div>
        <div class="cc-field">
            <label class="cc-label">Instructions *</label>
            <textarea name="assignment_instructions[]" class="cc-input cc-textarea" placeholder="Detailed instructions for students..."></textarea>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="cc-field">
                    <label class="cc-label">Type *</label>
                    <select name="assignment_type[]" class="cc-input form-select">
                        <option value="Seatwork">Seatwork</option>
                        <option value="Homework">Homework</option>
                        <option value="Project">Project</option>
                        <option value="Quiz">Quiz</option>
                        <option value="Exam">Exam</option>
                        <option value="Performance">Performance Task</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="cc-field">
                    <label class="cc-label">Points *</label>
                    <input type="number" name="assignment_points[]" class="cc-input" placeholder="e.g. 100" min="1" value="100">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="cc-field">
                    <label class="cc-label">Due Date *</label>
                    <input type="date" name="assignment_due_date[]" class="cc-input">
                </div>
            </div>
            <div class="col-6">
                <div class="cc-field">
                    <label class="cc-label">Due Time *</label>
                    <input type="time" name="assignment_due_time[]" class="cc-input" value="23:59">
                </div>
            </div>
        </div>
        <div class="cc-field">
            <label class="cc-label">Upload Materials *</label>
            <div class="cc-pdf-list"></div>
            <button type="button" class="cc-add-file-btn cc-add-tab-btn" style="width:100%;justify-content:center;margin-top:8px;">
                <i class="fa fa-plus"></i> Add File
            </button>
            <input type="file" name="assignment_file[]" class="cc-file-input" accept=".pdf,.ppt,.pptx,.doc,.docx" style="display:none;" required>
        </div>`;
            panels.appendChild(panel);

            const addFileBtn = panel.querySelector('.cc-add-file-btn');
            const fileInput = panel.querySelector('.cc-file-input');
            const pdfList = panel.querySelector('.cc-pdf-list');
            addFileBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => {
                pdfList.innerHTML = '';
                const file = fileInput.files[0];
                if (!file) return;
                const item = document.createElement('div');
                item.className = 'cc-pdf-item';
                item.innerHTML = `<i class="fa fa-file-pdf"></i><span>${file.name}</span><button type="button" class="cc-pdf-remove">&times;</button>`;
                item.querySelector('.cc-pdf-remove').addEventListener('click', () => { fileInput.value = ''; item.remove(); });
                pdfList.appendChild(item);
            });

            activateAssignTab(idx);
        }

        function removeAssignmentTab(idx) {
            const tabbar = document.getElementById('cc-assign-tabbar');
            const panels = document.getElementById('cc-assign-panels');
            const tab = tabbar.querySelector(`.cc-tab[data-idx="${idx}"]`);
            const panel = panels.querySelector(`.cc-tabpanel[data-idx="${idx}"]`);
            const wasActive = tab.classList.contains('active');
            tab.remove();
            panel.remove();

            if (tabbar.querySelectorAll('.cc-tab').length === 0) { addAssignmentTab(); return; }
            if (wasActive) activateAssignTab(+tabbar.querySelector('.cc-tab').dataset.idx);
        }

        document.getElementById('cc-add-assign-tab').addEventListener('click', addAssignmentTab);
    </script>

</body>

</html>