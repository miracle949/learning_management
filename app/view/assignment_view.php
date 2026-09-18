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
            color: var(--neon-cyan);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .av-back-link:hover {
            text-decoration: underline;
        }

        .mv-attachments-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-wrap: wrap;
        }

        .av-header-card {
            margin: 15px 0 0;
        }

        .av-header-top {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            border: 1px solid var(--border);
            background-color: var(--neon-cyan);
            padding: 2rem;
            width: 100%;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
        }

        .av-header-top::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(255, 255, 255, 0.05) 0 2px, transparent 2px 26px);
            pointer-events: none;
        }

        .av-header-icon {
            width: 54px;
            height: 54px;
            background-color: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--neon-cyan);
            font-size: 22px;
            flex-shrink: 0;
        }

        .av-body-card {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .av-header-info {
            width: 100%;
        }

        .av-header-info small {
            font-size: 12px;
            color: #ffffff;
            font-weight: 500;
            display: block;
            margin: 0 0 6px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .divider {
            font-size: 13.5px;
            color: #ffffff;
        }

        .av-header-info h2 {
            font-size: 24px;
            font-weight: 800;
            font-family: "Orbitron", sans-serif;
            color: #ffffff;
            margin: 0 0 6px;
        }

        .av-date-parent {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .av-date {
            font-size: 13.5px;
            color: #ffffff;
            display: block;
        }

        .date-received {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .av-points {
            font-size: 13.5px;
            font-weight: 500;
            color: #ffffff;
        }

        .av-due-badge {
            display: inline-block;
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .av-desc-card {
            padding: 0.5rem 0;
        }

        .av-template-card {
            margin: 1rem 0 0;
        }

        .av-template-card h5 {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-bright);
            margin-bottom: 1rem;
            font-family: "Orbitron", sans-serif;
        }

        .av-file-card {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .av-file-card:hover .av-file-icon {
            opacity: .85;
            transform: translateY(-2px);
        }

        .av-file-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 32px;
            transition: opacity .18s, transform .18s;
            background-color: #ffffff;
            flex-shrink: 0;
        }

        .av-file-icon .fa {
            font-size: 17px;
            color: var(--neon-cyan);
        }

        .av-file-icon.word {
            background: #2b579a;
            color: #fff;
        }

        .av-file-icon.word .fa {
            color: #fff;
        }

        .av-file-icon.pdf {
            background: #b0b0b0;
            color: #fff;
        }

        .av-file-icon.pdf .fa {
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
            border-radius: 20px;
            color: var(--text-bright);
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

        .av-instructions-card p {
            margin: 20px 0 0;
            font-size: 15px;
            color: var(--text-bright);
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
        }

        .av-desc-text {
            font-size: 14.5px;
            color: var(--text-dim);
        }

        .av-instructions-card .av-instruction {
            color: var(--text-dim);
            font-size: 14.5px;
            margin: 8px 0 0;
        }

        .mv-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-dim);
            text-decoration: none;
            margin-bottom: 1.2rem;
        }

        .rightbar {
            padding: 1.8rem 1.4rem;
        }

        /* ══════════════════════════════════════════════════════
           SUBMISSION PANEL
           ══════════════════════════════════════════════════════ */
        .sub-card {
            margin-top: 2.5rem;
            background: var(--panel, #f7faff);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.6rem 1.8rem;
            /* box-shadow: 0 1px 2px rgba(10, 26, 46, .04), 0 8px 24px rgba(0, 85, 170, .06); */
        }

        .sub-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .sub-head h5 {
            font-family: "Orbitron", sans-serif;
            font-size: 15px;
            font-weight: 800;
            color: var(--text-bright);
            margin: 0;
        }

        .sub-status {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-dim);
        }

        .sub-status.ok {
            color: var(--neon-blue);
        }

        .sub-status.late {
            color: var(--danger-red);
        }

        .sub-lead {
            font-size: 13.5px;
            color: var(--text-dim);
            margin: 6px 0 0;
        }

        /* ── type switcher ── */
        .sub-switch {
            display: flex;
            gap: 4px;
            padding: 4px;
            margin: 16px 0 18px;
            background: var(--panel-mid, #e8eef8);
            border-radius: 14px;
            border: 1px solid var(--border);
            overflow-x: auto;
        }

        .sub-switch button {
            flex: 1;
            min-width: 108px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 12px;
            border: 0;
            background: transparent;
            border-radius: 10px;
            color: var(--text-dim);
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            transition: background .18s, color .18s, box-shadow .18s;
        }

        .sub-switch button i {
            font-size: 14px;
        }

        .sub-switch button.active {
            background: #ffffff;
            color: var(--neon-blue);
            box-shadow: 0 1px 3px rgba(0, 85, 170, .14);
        }

        /* ── drop zone ── */
        .sub-drop {
            position: relative;
            border: 2px dashed var(--cyan-dim, rgba(0, 119, 204, .22));
            border-radius: 18px;
            background: var(--bg-section-alt, #f0f5fc);
            padding: 34px 22px;
            text-align: center;
            transition: border-color .18s, background .18s;
            cursor: pointer;
        }

        .sub-drop.is-over {
            border-color: var(--neon-cyan);
            background: rgba(0, 119, 204, .07);
        }

        .sub-drop .bubble {
            width: 56px;
            height: 56px;
            margin: 0 auto 14px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--neon-cyan);
            font-size: 21px;
        }

        .sub-drop h6 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--text-bright);
            margin: 0 0 6px;
        }

        .sub-drop .dz-text {
            margin: 0 0 16px;
            color: var(--text-dim);
            font-size: 13px;
        }

        .sub-browse {
            border: 1px solid var(--neon-cyan);
            background: #ffffff;
            color: var(--neon-blue);
            font-weight: 700;
            font-size: 13px;
            padding: 9px 20px;
            border-radius: 999px;
            cursor: pointer;
            transition: background .15s, color .15s;
        }

        .sub-browse:hover {
            background: var(--neon-cyan);
            color: #fff;
        }

        .sub-hint {
            margin-top: 14px;
            font-size: 11.5px;
            color: var(--text-dim);
        }

        /* ── picked file row ── */
        .sub-files {
            list-style: none;
            margin: 18px 0 0;
            padding: 0;
            display: grid;
            gap: 10px;
        }

        .sub-file {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .icon-info{
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sf-name{
            font-size: 13.5px;
            font-weight: 600;
        }

        .sf-sub{
            font-size: 11.5px;
        }

        .sub-thumb {
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--panel-mid, #e8eef8);
            color: var(--neon-blue);
            font-size: 20px;
        }

        .sub-thumb img,
        .sub-thumb video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .sub-file .sf-info {
            flex: 1;
            min-width: 0;
        }

        .sub-file .sf-name {
            font-weight: 700;
            font-size: 13.5px;
            color: var(--text-bright);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sf-name-row {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
        }

        .sf-name-row .sf-name {
            flex: 1;
            min-width: 0;
        }

        .sf-rename-input {
            flex: 1;
            min-width: 0;
            font: inherit;
            font-weight: 700;
            font-size: 13.5px;
            color: var(--text-bright);
            border: 1px solid var(--neon-cyan);
            border-radius: 6px;
            padding: 2px 6px;
            background: #ffffff;
        }

        .sf-rename-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 119, 204, .12);
        }

        .sf-ext {
            font-size: 12px;
            color: var(--text-dim);
            font-weight: 600;
            flex-shrink: 0;
        }

        .sf-mini-btn {
            border: none;
            background: transparent;
            color: var(--text-dim);
            width: 24px;
            height: 24px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            font-size: 12px;
            transition: color .15s, background .15s;
        }

        .sf-mini-btn:hover {
            color: var(--neon-blue);
            background: var(--panel-mid, #e8eef8);
        }

        .sub-file .sf-actions {
            display: flex;
            gap: 6px;
            flex-shrink: 0;
        }

        .sub-file .sf-sub {
            font-size: 11.5px;
            color: var(--text-dim);
            margin-top: 1px;
        }

        .sf-ok {
            color: var(--neon-blue);
            font-weight: 700;
        }

        .sf-err {
            color: var(--danger-red);
            font-weight: 700;
        }

        .sf-bar {
            height: 5px;
            border-radius: 99px;
            background: var(--panel-mid, #e8eef8);
            overflow: hidden;
            margin-top: 7px;
        }

        .sf-bar i {
            display: block;
            height: 100%;
            width: 0;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--neon-cyan), var(--electric-purple));
            transition: width .3s ease;
        }

        .sub-iconbtn {
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text-dim);
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 34px;
            cursor: pointer;
            transition: color .15s, border-color .15s, background .15s;
        }

        .sub-iconbtn:hover {
            color: var(--danger-red);
            border-color: rgba(204, 34, 68, .4);
            background: rgba(204, 34, 68, .06);
        }

        .sub-empty {
            margin-top: 16px;
            text-align: center;
            color: var(--text-dim);
            font-size: 12.5px;
        }

        /* ── note ── */
        .sub-note {
            margin-top: 22px;
        }

        .sub-note label {
            display: block;
            font-weight: 700;
            font-size: 13px;
            color: var(--text-bright);
            margin-bottom: 8px;
        }

        .sub-note label span {
            color: var(--text-dim);
            font-weight: 400;
        }

        .sub-note textarea {
            width: 100%;
            min-height: 96px;
            resize: vertical;
            padding: 13px 15px;
            font-size: 14px;
            color: var(--text-bright);
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .sub-note textarea::placeholder {
            color: var(--text-dim);
        }

        .sub-note textarea:focus {
            outline: none;
            border-color: var(--neon-cyan);
            box-shadow: 0 0 0 3px rgba(0, 119, 204, .12);
        }

        /* ── actions ── */
        .sub-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .sub-count {
            font-size: 12.5px;
            color: var(--text-dim);
        }

        .sub-btns {
            display: flex;
            gap: 10px;
        }

        .sub-ghost {
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text-dim);
            padding: 10px 20px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }

        .sub-ghost:hover {
            color: var(--text-bright);
            border-color: var(--cyan-dim);
        }

        .sub-send {
            border: 0;
            padding: 10px 26px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            background: linear-gradient(120deg, var(--neon-cyan), var(--electric-purple));
            box-shadow: 0 6px 16px rgba(0, 85, 170, .22);
            display: inline-flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s, opacity .15s;
        }

        .sub-send:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(0, 85, 170, .28);
        }

        .sub-send:disabled {
            opacity: .45;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* ── submitted / overdue banners ── */
        .sub-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            margin-top: 16px;
        }

        .sub-banner.done {
            background: rgba(0, 119, 204, .07);
            border: 1px solid var(--cyan-dim);
        }

        .sub-banner.late {
            background: rgba(204, 34, 68, .06);
            border: 1px solid rgba(204, 34, 68, .25);
        }

        .sub-banner .sb-icon {
            font-size: 20px;
            flex-shrink: 0;
            color: var(--neon-cyan);
        }

        .sub-banner.late .sb-icon {
            color: var(--danger-red);
        }

        .sub-banner .sb-text {
            flex: 1;
            min-width: 0;
        }

        .sub-banner .sb-title {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--neon-blue);
        }

        .sub-banner.late .sb-title {
            color: var(--danger-red);
        }

        .sub-banner .sb-sub {
            font-size: 12px;
            color: var(--text-dim);
            margin-top: 1px;
        }

        .sub-unsubmit {
            border: 1px solid rgba(204, 34, 68, .35);
            background: #fff;
            color: var(--danger-red);
            font-weight: 700;
            font-size: 12.5px;
            padding: 8px 16px;
            border-radius: 999px;
            cursor: pointer;
            flex-shrink: 0;
        }

        .sub-unsubmit:hover {
            background: var(--danger-red);
            color: #fff;
        }

        .sub-locked {
            border: 1px solid var(--border);
            background: var(--panel-mid, #e8eef8);
            color: var(--text-dim);
            font-size: 12.5px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 999px;
            cursor: not-allowed;
            flex-shrink: 0;
        }

        /* ── submitted file card ── */
        .sub-done-file {
            margin-top: 16px;
        }

        .sub-done-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--neon-blue);
            margin-bottom: .6rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .sub-done-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: border-color .15s, transform .15s;
        }

        .sub-done-card:hover {
            border-color: var(--cyan-dim);
            transform: translateY(-1px);
        }

        /* ── teacher message row ── */
        .sub-msg-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 14px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 8px 8px 8px 15px;
        }

        .sub-msg-row input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            color: var(--text-bright);
            background: transparent;
        }

        .sub-msg-row input::placeholder {
            color: var(--text-dim);
        }

        .sub-msg-send {
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--neon-cyan);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: transform .15s;
        }

        .sub-msg-send:hover {
            transform: scale(1.08);
        }

        .sub-msg-toggle {
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--text-dim);
            font-size: 12.5px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 999px;
            cursor: pointer;
            margin-top: 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .sub-msg-toggle:hover,
        .sub-msg-toggle.open {
            color: var(--neon-blue);
            border-color: var(--cyan-dim);
        }

        .av-not-found {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-dim);
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

        .pdf-modal-media {
            width: 100%;
            height: 100%;
            display: none;
            align-items: center;
            justify-content: center;
            background: #111;
        }

        .pdf-modal-media img,
        .pdf-modal-media video {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .pdf-modal-fallback {
            width: 100%;
            height: 100%;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .pdf-modal-fallback i {
            font-size: 46px;
            color: var(--neon-cyan);
        }

        .pdf-modal-fallback p {
            color: #ccc;
            font-size: 13.5px;
            margin: 0;
            max-width: 320px;
        }

        .pdf-modal-docx {
            width: 100%;
            height: 100%;
            display: none;
            overflow-y: auto;
            background: #ffffff;
        }

        .pdf-modal-docx .docx-inner {
            max-width: 720px;
            margin: 0 auto;
            padding: 40px 48px;
            font-size: 14.5px;
            line-height: 1.7;
            color: var(--text-bright);
        }

        .pdf-modal-docx .docx-inner img {
            max-width: 100%;
        }

        .pdf-modal-docx .docx-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 100%;
            color: var(--text-dim);
            font-size: 13.5px;
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
            color: var(--danger-red);
            margin-bottom: .8rem;
        }

        .unsubmit-dialog h4 {
            font-size: 16px;
            font-weight: 800;
            color: var(--text-bright);
            margin-bottom: .4rem;
        }

        .unsubmit-dialog p {
            font-size: 13px;
            color: var(--text-dim);
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
            width: 100%;    
        }

        .ud-cancel {
            background: var(--panel-mid, #e8eef8);
            color: var(--text-bright);
        }

        .ud-confirm {
            background: var(--danger-red);
            color: #fff;
        }

        /* ─── Turn-in confirm dialog ─── */
        .unsubmit-dialog.confirm-turnin .ud-icon {
            color: var(--neon-cyan);
        }

        .unsubmit-dialog.confirm-turnin .ud-confirm {
            background: linear-gradient(120deg, var(--neon-cyan), var(--electric-purple));
        }

        .ud-file {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
            background: var(--bg-section-alt, #f0f5fc);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 1.2rem;
        }

        .ud-file .ud-file-icon {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--neon-cyan);
            font-size: 17px;
        }

        .ud-file .ud-file-icon img,
        .ud-file .ud-file-icon video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .ud-file .ud-file-meta {
            min-width: 0;
        }

        .ud-file .ud-file-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-bright);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ud-file .ud-file-size {
            font-size: 11.5px;
            color: var(--text-dim);
            margin-top: 1px;
        }

        /* ─── Toast ─── */
        #avToast {
            position: fixed;
            bottom: 28px;
            left: 50%;
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
            background: var(--neon-blue);
        }

        #avToast.error {
            background: var(--danger-red);
        }

        #avToast.warn {
            background: var(--accent-gold);
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

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translate(-50%, 20px);
            }

            to {
                opacity: 1;
                transform: translate(-50%, 0);
            }
        }

        @keyframes toastOut {
            from {
                opacity: 1;
                transform: translate(-50%, 0);
            }

            to {
                opacity: 0;
                transform: translate(-50%, 20px);
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

        @media (max-width: 640px) {
            .sub-card {
                padding: 1.2rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include("../components/offcanvas.php"); ?>
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
                    $lockAttach = $existingSubmission || $isOverdue;
                    ?>

                    <div class="navbar-bread">
                        <div class="bread-crambs">
                            Dashboard <i class="fa fa-chevron-right"></i>
                            My Subject
                            <i class="fa fa-chevron-right"></i>
                            Classwork
                            <i class="fa fa-chevron-right"></i>
                            <b><?= htmlspecialchars($assignment['task']) ?></b>
                        </div>

                        <div class="notification">
                            <button><i class="fa fa-bell"></i></button>
                        </div>
                    </div>

                    <a href="/learning_management/public/?url=subjects&subject=<?= htmlspecialchars($subjectSlug) ?>"
                        class="mv-back-link">
                        <i class="fa fa-chevron-left"></i>
                    </a>

                    <div class="av-header-card">
                        <div class="av-body-card">
                            <div class="av-header-top">
                                <div class="av-header-icon">
                                    <i class="fa fa-file-alt"></i>
                                </div>
                                <div class="av-header-info">
                                    <small>New Assignment</small>
                                    <h2><?= htmlspecialchars($assignment['task']) ?></h2>
                                    <div class="date-received">
                                        <span class="av-date">
                                            Date Received: <?= date('M j', strtotime($assignment['posted_at'])) ?>
                                        </span>

                                        <div class="divider">|</div>

                                        <div class="due-date">
                                            <?php if (!empty($assignment['due_date'])):
                                                $dueTimestamp = strtotime($dueDT);
                                                ?>
                                                <span class="av-due-badge" style="color: <?= $isOverdue ? '#dc2626' : '#ffffff' ?>;
                                                    background: <?= $isOverdue ? '#fff5f5' : 'transparent' ?>;
                                                    border: 1px solid <?= $isOverdue ? '#fecaca' : 'transparent' ?>;">
                                                    <i class="fa fa-<?= $isOverdue ? 'clock' : 'calendar-alt' ?>"></i>
                                                    Due: <?= date('M j, Y', $dueTimestamp) ?> at
                                                    <?= date('g:i A', $dueTimestamp) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="divider">|</div>

                                        <span class="av-points">
                                            <?php if ($existingSubmission && isset($existingSubmission['points_earned']) && $existingSubmission['points_earned'] !== null): ?>
                                                <?php
                                                $percent = $assignment['points'] > 0
                                                    ? ($existingSubmission['points_earned'] / $assignment['points']) * 100
                                                    : 0;
                                                $scoreColor = $percent >= 75 ? '#0077cc' : '#cc2244';
                                                ?>
                                                <span style="color:<?= $scoreColor ?>; font-size:15px; font-weight:600;">
                                                    <?= (int) $existingSubmission['points_earned'] ?>
                                                </span>
                                                <span style="color:#ffffff; font-size:15px; font-weight:600;">
                                                    / <?= htmlspecialchars($assignment['points']) ?> pts
                                                </span>
                                            <?php else: ?>
                                                <?= htmlspecialchars($assignment['points']) ?> pts
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="av-instructions-card">
                                <div class="av-desc-text">
                                    <?= nl2br(htmlspecialchars($assignment['description'])) ?>
                                </div>
                                <p>Instructions:</p>
                                <div class="av-instruction">
                                    <?= nl2br(htmlspecialchars($assignment['instructions'])) ?>
                                </div>
                            </div>

                            <?php if (!empty($templates)): ?>
                                <div class="av-template-card">
                                    <h5>Attached Files</h5>
                                    <?php foreach ($templates as $tpl):
                                        $tplType = $tpl['file_type'] ?? 'other';
                                        $tplIcon = $tplType === 'word' ? 'fa-file-word' : 'fa-file-pdf';
                                        ?>
                                        <div class="mv-attachments-grid">
                                            <div class="av-file-card" onclick="openModal(
                                                '<?= htmlspecialchars($tpl['file_path']) ?>',
                                                '<?= htmlspecialchars($tpl['file_name']) ?>',
                                                '<?= $tplType ?>')">
                                                <div class="av-file-icon <?= htmlspecialchars($tplType) ?>">
                                                    <i class="fa <?= $tplIcon ?>"></i>
                                                </div>
                                                <span class="av-file-badge <?= htmlspecialchars($tplType) ?>">
                                                    <?= htmlspecialchars($tpl['file_name']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>


                        <!-- ══════════════ SUBMISSION PANEL ══════════════ -->
                        <div class="sub-card" id="submissionArea">

                            <input type="file" id="attachFileInput" accept=".pdf,.doc,.docx,.ppt,.pptx" style="display:none">
                            <input type="file" id="attachImageInput" accept="image/*" style="display:none">
                            <input type="file" id="attachVideoInput" accept="video/*" style="display:none">

                            <?php if ($existingSubmission):

                                $subRawPath = $existingSubmission['file_path']
                                    ?? $existingSubmission['submission_file']
                                    ?? $existingSubmission['file_name']
                                    ?? $existingSubmission['filename']
                                    ?? $existingSubmission['filepath']
                                    ?? '';

                                $subFileName = $subRawPath ? basename($subRawPath) : '';
                                $subExt = strtolower(pathinfo($subFileName, PATHINFO_EXTENSION));
                                if (in_array($subExt, ['doc', 'docx'])) {
                                    $subType = 'word';
                                    $subIcon = 'fa-file-word';
                                } elseif (in_array($subExt, ['ppt', 'pptx'])) {
                                    $subType = 'powerpoint';
                                    $subIcon = 'fa-file-powerpoint';
                                } elseif (in_array($subExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'heic'])) {
                                    $subType = 'image';
                                    $subIcon = 'fa-file-image';
                                } elseif (in_array($subExt, ['mp4', 'mov', 'webm', 'avi', 'mkv'])) {
                                    $subType = 'video';
                                    $subIcon = 'fa-file-video';
                                } else {
                                    $subType = 'pdf';
                                    $subIcon = 'fa-file-pdf';
                                }
                                ?>

                                <!-- ── SUBMITTED STATE ── -->
                                <div class="sub-head">
                                    <h5>Your submission</h5>
                                    <span class="sub-status ok">Turned in</span>
                                </div>

                                <?php if (!empty($subRawPath)): ?>
                                    <div class="sub-done-file" id="submittedFileSection">
                                        <p class="sub-done-label"><i class="fa fa-check-circle"></i> Your submitted file</p>
                                        <div class="sub-done-card" onclick="openModal(
                                            '<?= htmlspecialchars($subRawPath) ?>',
                                            '<?= htmlspecialchars($subFileName) ?>',
                                            '<?= $subType ?>')">
                                            <div class="icon-info">
                                                <div class="sub-thumb"><i class="fa <?= $subIcon ?>"></i></div>
                                                <div class="sf-info">
                                                    <div class="sf-name"><?= htmlspecialchars($subFileName) ?></div>
                                                    <div class="sf-sub"><?= strtoupper($subExt ?: 'FILE') ?> · tap to preview</div>
                                                </div>
                                            </div>
                                            <span class="sub-iconbtn" style="cursor:pointer"><i class="fa fa-eye"></i></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="sub-banner done" id="msgBox">
                                    <i class="fa fa-check-circle sb-icon"></i>
                                    <div class="sb-text">
                                        <div class="sb-title">Assignment submitted</div>
                                        <div class="sb-sub">
                                            Submitted on
                                            <?= date('M j, Y g:i A', strtotime($existingSubmission['submitted_at'] ?? 'now')) ?>
                                        </div>
                                    </div>
                                    <?php if (!$isOverdue): ?>
                                        <button class="sub-unsubmit" onclick="confirmUnsubmit()">Unsubmit</button>
                                    <?php else: ?>
                                        <button class="sub-locked" disabled><i class="fa fa-lock"></i> Locked</button>
                                    <?php endif; ?>
                                </div>

                            <?php elseif ($isOverdue): ?>

                                <!-- ── OVERDUE STATE ── -->
                                <div class="sub-head">
                                    <h5>Your submission</h5>
                                    <span class="sub-status late">Missed the deadline</span>
                                </div>

                                <div class="sub-banner late">
                                    <i class="fa fa-clock sb-icon"></i>
                                    <div class="sb-text">
                                        <div class="sb-title">Submissions are closed</div>
                                        <div class="sb-sub">
                                            The deadline was <?= date('M j, Y', strtotime($dueDT)) ?> at
                                            <?= date('g:i A', strtotime($dueDT)) ?>. Message your teacher if you need an extension.
                                        </div>
                                    </div>
                                </div>

                            <?php else: ?>

                                <!-- ── OPEN FOR SUBMISSION ── -->
                                <div class="sub-head">
                                    <h5>Your submission</h5>
                                    <span class="sub-status" id="subStatus">Not submitted yet</span>
                                </div>
                                <p class="sub-lead">Pick what you're turning in, then drop your file below.</p>

                                <div class="sub-switch" id="subSwitch">
                                    <button type="button" class="active" data-kind="file">
                                        <i class="fa fa-file-alt"></i> Document
                                    </button>
                                    <button type="button" data-kind="image">
                                        <i class="fa fa-image"></i> Image
                                    </button>
                                    <button type="button" data-kind="video">
                                        <i class="fa fa-film"></i> Video
                                    </button>
                                </div>

                                <div class="sub-drop" id="subDrop">
                                    <div class="bubble" id="dropIcon"><i class="fa fa-file-alt"></i></div>
                                    <h6 id="dropTitle">Drop your document here</h6>
                                    <p class="dz-text" id="dropText">PDF, Word or PowerPoint — up to 25 MB</p>
                                    <button type="button" class="sub-browse" id="subBrowse">Choose file</button>
                                    <div class="sub-hint" id="dropHint">One file per submission.</div>
                                </div>

                                <ul class="sub-files" id="subFiles"></ul>
                                <div class="sub-empty" id="subEmpty">Nothing attached yet.</div>

                                <div class="sub-note">
                                    <label for="subComment">Note for your teacher <span>(optional)</span></label>
                                    <textarea id="subComment"
                                        placeholder="Anything your teacher should know about this submission?"></textarea>
                                </div>

                                <div class="sub-actions">
                                    <span class="sub-count" id="subCount">No file · 0 KB</span>
                                    <div class="sub-btns">
                                        <button type="button" class="sub-ghost" id="subClear">Clear</button>
                                        <button type="button" class="sub-send" id="subSend" disabled>
                                            Turn in <i class="fa fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <!-- ── Message your teacher (always available) ── -->
                            <button class="sub-msg-toggle" id="msgToggleBtn" onclick="toggleMessageBox()">
                                <i class="fa fa-comment"></i> Message your teacher
                            </button>

                            <div id="msgBoxWrapper" style="display:none;">
                                <div class="sub-msg-row">
                                    <input type="text" id="msgInput" placeholder="Write a message…">
                                    <button class="sub-msg-send" id="msgSendBtn" title="Send message" onclick="sendMessage()">
                                        <i class="fa fa-paper-plane" id="msgSendIcon"></i>
                                    </button>
                                </div>
                            </div>

                        </div><!-- /sub-card -->
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
                    <button class="pdf-modal-close" onclick="closeModal()"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="pdf-modal-body">
                <iframe class="pdf-modal-iframe" id="pdfModalIframe" src=""></iframe>
                <div class="pdf-modal-media" id="pdfModalMedia"></div>
                <div class="pdf-modal-docx" id="pdfModalDocx"></div>
                <div class="pdf-modal-fallback" id="pdfModalFallback">
                    <i class="fa fa-file-alt"></i>
                    <p>This file type can't be previewed in the browser yet. Download it to take a look, or check back after you turn it in.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Turn-in confirm dialog -->
    <div class="unsubmit-overlay" id="turninOverlay" onclick="if(event.target===this)closeTurninDialog()">
        <div class="unsubmit-dialog confirm-turnin">
            <div class="ud-icon"><i class="fa fa-paper-plane"></i></div>
            <h4>Turn in this assignment?</h4>
            <p>Your teacher will be able to see this submission right away.</p>

            <div class="ud-file" id="turninFileRow">
                <div class="ud-file-icon" id="turninFileIcon"><i class="fa fa-file-alt"></i></div>
                <div class="ud-file-meta">
                    <div class="ud-file-name" id="turninFileName"></div>
                    <div class="ud-file-size" id="turninFileSize"></div>
                </div>
            </div>

            <div class="ud-btns">
                <button class="ud-cancel" onclick="closeTurninDialog()">No, not yet</button>
                <button class="ud-confirm" id="turninConfirmBtn" onclick="confirmTurnin()">Yes, turn it in</button>
            </div>
        </div>
    </div>

    <!-- Unsubmit confirm dialog -->
    <div class="unsubmit-overlay" id="unsubmitOverlay" onclick="if(event.target===this)closeUnsubmitDialog()">
        <div class="unsubmit-dialog">
            <div class="ud-icon"><i class="fa fa-exclamation-triangle"></i></div>
            <h4>Unsubmit assignment?</h4>
            <p>This removes your submission. You can turn it in again before the deadline.</p>
            <div class="ud-btns">
                <button class="ud-cancel" onclick="closeUnsubmitDialog()">Cancel</button>
                <button class="ud-confirm" id="unsubmitConfirmBtn" onclick="doUnsubmit()">Yes, unsubmit</button>
            </div>
        </div>
    </div>

    <div id="avToast"></div>

    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
    <script>
        // ── Toast ─────────────────────────────────────────────────────────────
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

        // ── Message box toggle ────────────────────────────────────────────────
        let msgBoxVisible = false;
        function toggleMessageBox() {
            const wrapper = document.getElementById('msgBoxWrapper');
            const btn = document.getElementById('msgToggleBtn');
            if (!wrapper) return;
            msgBoxVisible = !msgBoxVisible;
            wrapper.style.display = msgBoxVisible ? 'block' : 'none';
            btn.classList.toggle('open', msgBoxVisible);
            if (msgBoxVisible) {
                wrapper.style.animation = 'fadeInDown .2s ease';
                setTimeout(() => { const i = document.getElementById('msgInput'); if (i) i.focus(); }, 50);
            }
        }

        // ── PDF / file modal ──────────────────────────────────────────────────
        // isLocal=true is used for a file the student just picked but hasn't
        // submitted yet (url is a blob: URL, nothing is fetched from the server).
        function openModal(url, name, type, isLocal) {
            const overlay = document.getElementById('pdfModalOverlay');
            const iframe = document.getElementById('pdfModalIframe');
            const media = document.getElementById('pdfModalMedia');
            const docx = document.getElementById('pdfModalDocx');
            const fallback = document.getElementById('pdfModalFallback');
            const fallbackText = fallback.querySelector('p');
            const title = document.getElementById('pdfModalTitle');
            const download = document.getElementById('pdfDownloadBtn');
            const icon = document.getElementById('pdfModalIcon');

            // Reset all panes
            iframe.style.display = 'none';
            iframe.src = '';
            media.style.display = 'none';
            media.innerHTML = '';
            docx.style.display = 'none';
            docx.innerHTML = '';
            fallback.style.display = 'none';

            const fullPath = isLocal
                ? url
                : (url.startsWith('/') ? url : '/learning_management/' + url);

            title.textContent = name;
            download.href = fullPath;
            download.setAttribute('download', name);

            const iconMap = {
                word: 'fa fa-file-word', powerpoint: 'fa fa-file-powerpoint',
                image: 'fa fa-file-image', video: 'fa fa-file-video', pdf: 'fa fa-file-pdf'
            };
            icon.className = iconMap[type] || 'fa fa-file';
            icon.style.color = type === 'word' ? '#2b579a' : (type === 'pdf' ? '#dc2626' : '#0077cc');

            if (type === 'image') {
                media.style.display = 'flex';
                media.innerHTML = '<img alt="">';
                media.querySelector('img').src = fullPath;

            } else if (type === 'video') {
                media.style.display = 'flex';
                media.innerHTML = '<video controls autoplay></video>';
                media.querySelector('video').src = fullPath;

            } else if (type === 'word') {
                // Word docs are rendered client-side with mammoth.js by fetching
                // the bytes ourselves — this works whether the file is a local
                // blob (not submitted yet) or already stored on the server,
                // because the browser fetches it directly rather than relying
                // on Google's viewer, which can't reach non-public URLs.
                docx.style.display = 'block';
                docx.innerHTML = '<div class="docx-loading"><i class="fa fa-spinner fa-spin"></i> Rendering preview…</div>';

                if (typeof mammoth === 'undefined') {
                    docx.innerHTML = '<div class="docx-loading">Preview isn\'t available right now.</div>';
                } else {
                    fetch(fullPath)
                        .then(r => r.arrayBuffer())
                        .then(buf => mammoth.convertToHtml({ arrayBuffer: buf }))
                        .then(result => {
                            docx.innerHTML = '<div class="docx-inner">' + result.value + '</div>';
                        })
                        .catch(() => {
                            docx.innerHTML = '<div class="docx-loading">Couldn\'t render this document. Try downloading it instead.</div>';
                        });
                }

            } else if (type === 'powerpoint') {
                // No reliable renderer exists for PowerPoint in the browser —
                // showing a clear fallback beats a viewer that silently fails.
                fallbackText.textContent = isLocal
                    ? "PowerPoint files can't be previewed before you turn them in. Download it to check the slides, or preview it here again once it's submitted."
                    : "PowerPoint files can't be previewed in the browser. Download it to check the slides.";
                fallback.style.display = 'flex';

            } else {
                // pdf, or anything else the browser can render directly
                iframe.style.display = 'block';
                iframe.src = fullPath;
            }

            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('pdfModalOverlay').classList.remove('open');
            document.getElementById('pdfModalIframe').src = '';
            const media = document.getElementById('pdfModalMedia');
            media.querySelectorAll('video').forEach(v => v.pause());
            media.innerHTML = '';
            document.getElementById('pdfModalDocx').innerHTML = '';
            document.body.style.overflow = '';
        }

        function handleOverlayClick(e) {
            if (e.target === document.getElementById('pdfModalOverlay')) closeModal();
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── Send message ──────────────────────────────────────────────────────
        function sendMessage() {
            const input = document.getElementById('msgInput');
            const icon = document.getElementById('msgSendIcon');
            if (!input) return;

            const text = input.value.trim();
            if (!text) {
                input.placeholder = 'Type a message first';
                setTimeout(() => { input.placeholder = 'Write a message…'; }, 2500);
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
                    if (data.success) { input.value = ''; showToast('Message sent.', 'success'); }
                    else { showToast(data.message || 'Could not send the message.', 'error'); }
                })
                .catch(() => showToast('Network problem. Check your connection and try again.', 'error'))
                .finally(() => {
                    icon.className = 'fa fa-paper-plane';
                    document.getElementById('msgSendBtn').disabled = false;
                });
        }

        (function () {
            const i = document.getElementById('msgInput');
            if (i) i.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
        })();

        // ── Turn-in dialog (safe in every state) ──────────────────────────────
        function closeTurninDialog() {
            const o = document.getElementById('turninOverlay');
            if (o) o.classList.remove('open');
        }

        // ── Submission panel (only when open for submission) ──────────────────
        <?php if (!$existingSubmission && !$isOverdue): ?>

            const KINDS = {
                file: {
                    input: 'attachFileInput',
                    icon: 'fa-file-alt',
                    title: 'Drop your document here',
                    text: 'PDF, Word or PowerPoint — up to 25 MB',
                    limit: 25 * 1024 * 1024,
                    test: f => !f.type.startsWith('image/') && !f.type.startsWith('video/')
                },
                image: {
                    input: 'attachImageInput',
                    icon: 'fa-image',
                    title: 'Drop your image here',
                    text: 'JPG, PNG, HEIC or WebP — up to 25 MB',
                    limit: 25 * 1024 * 1024,
                    test: f => f.type.startsWith('image/')
                },
                video: {
                    input: 'attachVideoInput',
                    icon: 'fa-film',
                    title: 'Drop your video here',
                    text: 'MP4, MOV or WebM — up to 200 MB',
                    limit: 200 * 1024 * 1024,
                    test: f => f.type.startsWith('video/')
                }
            };

            let attachedFile = null;
            let attachedName = '';
            let attachedType = 'file';      // category sent to the server: file / image / video
            let attachedPreviewType = 'pdf'; // what the preview modal renders: pdf / word / powerpoint / image / video
            let attachedURL = null;
            let attachError = null;
            let currentKind = 'file';
            let isSubmitting = false;
            let isRenaming = false;

            const dropZone = document.getElementById('subDrop');
            const fileList = document.getElementById('subFiles');
            const emptyMsg = document.getElementById('subEmpty');
            const countEl = document.getElementById('subCount');
            const sendBtn = document.getElementById('subSend');

            function humanSize(b) {
                if (b < 1024) return b + ' B';
                if (b < 1048576) return (b / 1024).toFixed(0) + ' KB';
                return (b / 1048576).toFixed(1) + ' MB';
            }

            function splitName(name) {
                const dot = name.lastIndexOf('.');
                if (dot <= 0) return { base: name, ext: '' };
                return { base: name.slice(0, dot), ext: name.slice(dot) };
            }

            // The "Document" tab covers PDF, Word and PowerPoint — but the
            // preview modal needs to know which one it actually is, since each
            // renders a different way (or shows a fallback for PPT).
            function detectPreviewType(category, filename) {
                if (category !== 'file') return category; // image / video
                const ext = splitName(filename).ext.toLowerCase().replace('.', '');
                if (['doc', 'docx'].includes(ext)) return 'word';
                if (['ppt', 'pptx'].includes(ext)) return 'powerpoint';
                return 'pdf';
            }

            function setKind(kind) {
                currentKind = kind;
                const c = KINDS[kind];
                document.getElementById('dropIcon').innerHTML = '<i class="fa ' + c.icon + '"></i>';
                document.getElementById('dropTitle').textContent = c.title;
                document.getElementById('dropText').textContent = c.text;
                document.querySelectorAll('#subSwitch button').forEach(b =>
                    b.classList.toggle('active', b.dataset.kind === kind));
            }

            document.querySelectorAll('#subSwitch button').forEach(b =>
                b.addEventListener('click', () => setKind(b.dataset.kind)));

            function openPicker() {
                document.getElementById(KINDS[currentKind].input).click();
            }
            document.getElementById('subBrowse').addEventListener('click', e => { e.stopPropagation(); openPicker(); });
            dropZone.addEventListener('click', openPicker);

            ['attachFileInput', 'attachImageInput', 'attachVideoInput'].forEach(id => {
                document.getElementById(id).addEventListener('change', function () {
                    if (this.files[0]) setFile(this.files[0]);
                    this.value = '';
                });
            });

            // Drag & drop
            ['dragenter', 'dragover'].forEach(ev => dropZone.addEventListener(ev, e => {
                e.preventDefault(); dropZone.classList.add('is-over');
            }));
            ['dragleave', 'drop'].forEach(ev => dropZone.addEventListener(ev, e => {
                e.preventDefault();
                if (ev === 'dragleave' && dropZone.contains(e.relatedTarget)) return;
                dropZone.classList.remove('is-over');
            }));
            dropZone.addEventListener('drop', e => {
                if (e.dataTransfer && e.dataTransfer.files.length) setFile(e.dataTransfer.files[0]);
            });

            function setFile(file) {
                const c = KINDS[currentKind];

                if (!c.test(file)) {
                    showToast('That file does not match the ' + currentKind + ' tab. Switch tabs and try again.', 'warn');
                    return;
                }

                if (attachedURL) URL.revokeObjectURL(attachedURL);

                attachedFile = file;
                attachedName = file.name;
                attachedType = currentKind;
                attachedPreviewType = detectPreviewType(currentKind, file.name);
                attachedURL = URL.createObjectURL(file);
                attachError = file.size > c.limit
                    ? 'Too large — keep it under ' + (c.limit / 1048576) + ' MB'
                    : null;

                renderFile();
            }

            function clearFile() {
                if (attachedURL) URL.revokeObjectURL(attachedURL);
                attachedFile = null; attachedName = ''; attachedURL = null; attachError = null;
                isRenaming = false;
                renderFile();
            }
            document.getElementById('subClear').addEventListener('click', clearFile);

            function renderFile() {
                fileList.innerHTML = '';

                if (!attachedFile) {
                    emptyMsg.style.display = 'block';
                    countEl.textContent = 'No file · 0 KB';
                    sendBtn.disabled = true;
                    return;
                }

                emptyMsg.style.display = 'none';
                const { base, ext } = splitName(attachedName);

                const li = document.createElement('li');
                li.className = 'sub-file';

                const thumb = document.createElement('div');
                thumb.className = 'sub-thumb';
                if (attachedType === 'image') {
                    thumb.innerHTML = '<img alt="">';
                    thumb.querySelector('img').src = attachedURL;
                } else if (attachedType === 'video') {
                    thumb.innerHTML = '<video muted playsinline></video>';
                    thumb.querySelector('video').src = attachedURL;
                } else {
                    const docIcon = { word: 'fa-file-word', powerpoint: 'fa-file-powerpoint', pdf: 'fa-file-pdf' }[attachedPreviewType] || 'fa-file-alt';
                    thumb.innerHTML = '<i class="fa ' + docIcon + '"></i>';
                }

                const info = document.createElement('div');
                info.className = 'sf-info';
                const status = attachError
                    ? '<span class="sf-err">' + attachError + '</span>'
                    : '<span class="sf-ok">Ready to turn in</span>';

                const nameRowHTML = isRenaming
                    ? '<div class="sf-name-row">' +
                        '<input type="text" class="sf-rename-input" id="sfRenameInput">' +
                        '<span class="sf-ext"></span>' +
                        '<button type="button" class="sf-mini-btn" id="sfRenameSave" title="Save name"><i class="fa fa-check"></i></button>' +
                      '</div>'
                    : '<div class="sf-name-row">' +
                        '<span class="sf-name"></span>' +
                        '<button type="button" class="sf-mini-btn" id="sfRenameStart" title="Rename"><i class="fa fa-pen"></i></button>' +
                      '</div>';

                info.innerHTML = nameRowHTML +
                    '<div class="sf-sub">' + humanSize(attachedFile.size) + ' · ' + status + '</div>' +
                    '<div class="sf-bar"><i id="sfBar" style="width:' + (attachError ? 0 : 100) + '%"></i></div>';

                if (isRenaming) {
                    const input = info.querySelector('#sfRenameInput');
                    input.value = base;
                    info.querySelector('.sf-ext').textContent = ext;
                    info.querySelector('#sfRenameSave').addEventListener('click', () => saveRename(input.value));
                    input.addEventListener('keydown', e => {
                        if (e.key === 'Enter') saveRename(input.value);
                        if (e.key === 'Escape') { isRenaming = false; renderFile(); }
                    });
                } else {
                    info.querySelector('.sf-name').textContent = attachedName;
                    info.querySelector('#sfRenameStart').addEventListener('click', () => {
                        isRenaming = true;
                        renderFile();
                        const el = document.getElementById('sfRenameInput');
                        if (el) { el.focus(); el.select(); }
                    });
                }

                const actions = document.createElement('div');
                actions.className = 'sf-actions';

                const viewBtn = document.createElement('button');
                viewBtn.type = 'button';
                viewBtn.className = 'sub-iconbtn';
                viewBtn.title = 'Preview this file';
                viewBtn.innerHTML = '<i class="fa fa-eye"></i>';
                viewBtn.addEventListener('click', () =>
                    openModal(attachedURL, attachedName, attachedPreviewType, true));

                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'sub-iconbtn';
                rm.title = 'Remove file';
                rm.innerHTML = '<i class="fa fa-times"></i>';
                rm.addEventListener('click', clearFile);

                actions.append(viewBtn, rm);
                li.append(thumb, info, actions);
                fileList.appendChild(li);

                countEl.textContent = '1 file · ' + humanSize(attachedFile.size);
                sendBtn.disabled = !!attachError;
            }

            function saveRename(newBase) {
                const clean = newBase.trim().replace(/[\\/:*?"<>|]/g, '');
                const { ext } = splitName(attachedName);
                attachedName = (clean || splitName(attachedName).base) + ext;
                attachedPreviewType = detectPreviewType(attachedType, attachedName);
                isRenaming = false;
                renderFile();
            }

            // ── Turn in (asks first) ──────────────────────────────────────────
            sendBtn.addEventListener('click', askTurnin);

            function askTurnin() {
                if (isSubmitting || !attachedFile || attachError) return;

                document.getElementById('turninFileName').textContent = attachedName;
                document.getElementById('turninFileSize').textContent =
                    humanSize(attachedFile.size) + ' · ' +
                    (attachedType === 'image' ? 'Image' : attachedType === 'video' ? 'Video' : 'Document');

                const iconBox = document.getElementById('turninFileIcon');
                if (attachedType === 'image') {
                    iconBox.innerHTML = '<img alt="">';
                    iconBox.querySelector('img').src = attachedURL;
                } else if (attachedType === 'video') {
                    iconBox.innerHTML = '<video muted playsinline></video>';
                    iconBox.querySelector('video').src = attachedURL;
                } else {
                    iconBox.innerHTML = '<i class="fa fa-file-alt"></i>';
                }

                const btn = document.getElementById('turninConfirmBtn');
                btn.disabled = false;
                btn.textContent = 'Yes, turn it in';

                document.getElementById('turninOverlay').classList.add('open');
            }

            function confirmTurnin() {
                const btn = document.getElementById('turninConfirmBtn');
                btn.disabled = true;
                btn.textContent = 'Turning in…';
                closeTurninDialog();
                doSubmit();
            }

            // Esc closes the confirm dialog without submitting
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeTurninDialog();
            });

            function doSubmit() {
                if (isSubmitting || !attachedFile || attachError) return;
                isSubmitting = true;
                sendBtn.disabled = true;
                sendBtn.innerHTML = 'Turning in… <i class="fa fa-spinner fa-spin"></i>';

                const bar = document.getElementById('sfBar');
                if (bar) bar.style.width = '0%';

                const fd = new FormData();
                fd.append('assignment_id', '<?= $assignment["id"] ?>');
                fd.append('comment', document.getElementById('subComment').value.trim());
                const renamedFile = new File([attachedFile], attachedName, { type: attachedFile.type });
                fd.append('submission_file', renamedFile);
                fd.append('file_type', attachedType);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/learning_management/public/?url=submit_assignment');

                xhr.upload.onprogress = e => {
                    if (e.lengthComputable && bar) {
                        bar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
                    }
                };

                xhr.onload = () => {
                    let data;
                    try { data = JSON.parse(xhr.responseText); }
                    catch (e) { showToast('Server error. Please try again.', 'error'); resetSendBtn(); return; }

                    if (data.success) {
                        const dateStr = new Date().toLocaleString('en-US', {
                            month: 'short', day: 'numeric', year: 'numeric',
                            hour: 'numeric', minute: '2-digit', hour12: true
                        });
                        renderSubmittedState(
                            dateStr,
                            data.file_path || '',
                            data.file_name || attachedName,
                            attachedPreviewType
                        );
                        showToast('Assignment turned in.', 'success');
                    } else {
                        showToast(data.message || 'Submission failed. Please try again.', 'error');
                        resetSendBtn();
                    }
                };

                xhr.onerror = () => {
                    showToast('Network problem. Check your connection and try again.', 'error');
                    resetSendBtn();
                };

                xhr.send(fd);
            }

            function resetSendBtn() {
                isSubmitting = false;
                sendBtn.disabled = false;
                sendBtn.innerHTML = 'Turn in <i class="fa fa-paper-plane"></i>';
            }

            function renderSubmittedState(dateStr, filePath, fileName, fileType) {
                const area = document.getElementById('submissionArea');
                const iconMap = {
                    word: 'fa-file-word', powerpoint: 'fa-file-powerpoint',
                    image: 'fa-file-image', video: 'fa-file-video',
                    file: 'fa-file-alt', pdf: 'fa-file-pdf'
                };
                const icon = iconMap[fileType] || 'fa-file-alt';
                const ext = (fileName.split('.').pop() || 'file').toUpperCase();

                const fileCard = filePath ? `
                    <div class="sub-done-file" id="submittedFileSection">
                        <p class="sub-done-label"><i class="fa fa-check-circle"></i> Your submitted file</p>
                        <div class="sub-done-card"
                             onclick="openModal('${filePath.replace(/'/g, "\\'")}', '${fileName.replace(/'/g, "\\'")}', '${fileType}')">
                            <div class="icon-info">
                                <div class="sub-thumb"><i class="fa ${icon}"></i></div>
                                <div class="sf-info">
                                    <div class="sf-name">${fileName}</div>
                                    <div class="sf-sub">${ext} · tap to preview</div>
                                </div>
                            </div>
                            <span class="sub-iconbtn"><i class="fa fa-eye"></i></span>
                        </div>
                    </div>` : '';

                area.innerHTML = `
                    <div class="sub-head">
                        <h5>Your submission</h5>
                        <span class="sub-status ok">Turned in</span>
                    </div>
                    ${fileCard}
                    <div class="sub-banner done">
                        <i class="fa fa-check-circle sb-icon"></i>
                        <div class="sb-text">
                            <div class="sb-title">Assignment submitted</div>
                            <div class="sb-sub">Submitted on ${dateStr}</div>
                        </div>
                        <button class="sub-unsubmit" onclick="confirmUnsubmit()">Unsubmit</button>
                    </div>

                    <button class="sub-msg-toggle" id="msgToggleBtn" onclick="toggleMessageBox()">
                        <i class="fa fa-comment"></i> Message your teacher
                    </button>
                    <div id="msgBoxWrapper" style="display:none;">
                        <div class="sub-msg-row">
                            <input type="text" id="msgInput" placeholder="Write a message…">
                            <button class="sub-msg-send" id="msgSendBtn" title="Send message" onclick="sendMessage()">
                                <i class="fa fa-paper-plane" id="msgSendIcon"></i>
                            </button>
                        </div>
                    </div>`;

                msgBoxVisible = false;
                isSubmitting = false;
                document.getElementById('msgInput')
                    .addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
            }

            setKind('file');
            renderFile();

        <?php endif; ?>

        // ── Unsubmit ──────────────────────────────────────────────────────────
        function confirmUnsubmit() {
            document.getElementById('unsubmitOverlay').classList.add('open');
        }
        function closeUnsubmitDialog() {
            document.getElementById('unsubmitOverlay').classList.remove('open');
        }

        function doUnsubmit() {
            const btn = document.getElementById('unsubmitConfirmBtn');
            btn.disabled = true;
            btn.textContent = 'Removing…';

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
                        btn.disabled = false; btn.textContent = 'Yes, unsubmit';
                        return;
                    }
                    if (data.success) {
                        closeUnsubmitDialog();
                        showToast('Submission removed. You can turn it in again.', 'success');
                        setTimeout(() => window.location.reload(), 1200);
                    } else {
                        closeUnsubmitDialog();
                        showToast(data.message || 'Could not unsubmit. Please try again.', 'error');
                        btn.disabled = false; btn.textContent = 'Yes, unsubmit';
                    }
                })
                .catch(() => {
                    closeUnsubmitDialog();
                    showToast('Network problem. Check your connection and try again.', 'error');
                    btn.disabled = false; btn.textContent = 'Yes, unsubmit';
                });
        }
    </script>

    <script>
        // ── Live due-date watcher ─────────────────────────────────────────────
        (function () {
            <?php if (!$assignment): ?> return; <?php endif; ?>

            let knownDueDate = <?= json_encode($assignment['due_date'] ?? '') ?>;
            let knownDueTime = <?= json_encode($assignment['due_time'] ?? '') ?>;
            const assignmentId = <?= (int) ($assignment['id'] ?? 0) ?>;

            if (!assignmentId) return;

            function pollDueDate() {
                fetch('/learning_management/public/?url=get_assignment_due&id=' + assignmentId, { cache: 'no-store' })
                    .then(r => r.json())
                    .then(data => {
                        if (!data.due_date) return;
                        const changed =
                            data.due_date !== knownDueDate ||
                            (data.due_time ?? '') !== (knownDueTime ?? '');
                        if (changed) {
                            knownDueDate = data.due_date;
                            knownDueTime = data.due_time ?? '';
                            window.location.reload();
                        }
                    })
                    .catch(() => { });
            }

            setInterval(pollDueDate, 8000);
        })();
    </script>
</body>

</html>