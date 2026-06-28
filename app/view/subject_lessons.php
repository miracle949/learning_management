<?php
function lUrl($subject, $moduleId, $lessonId)
{
    return "/learning_management/public/?url=subject_lessons&subject="
        . urlencode($subject) . "&id={$moduleId}&lesson={$lessonId}";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iLearn-CSS | Lessons</title>
    <link rel="stylesheet" href="../css_folder/lessons.css">
    <link rel="stylesheet" href="../css_folder/components.css">
    <link rel="stylesheet" href="../bootstrap_folder/css/bootstrap.min.css">
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">

    <style>
        /* =============================================
           ILEARN-CSS DESIGN TOKENS
        ============================================= */
        :root {
            --green-neon: #00FF88;
            --green-mid: #00C96B;
            --green-dark: #00894A;
            --green-light: #E8FBF2;
            --blue-elec: #00CFFF;
            --blue-mid: #0099CC;
            --blue-dark: #006B99;
            --orange-warn: #FF6B00;
            --yellow-volt: #FFD700;
            --red-led: #FF3B3B;
            --purple-chip: #9B5DE5;

            --bg-darkest: #050D14;
            --bg-dark: #0A1628;
            --bg-panel: #0F2040;
            --bg-card: #0E1E35;
            --bg-surface: #112244;

            --border-dim: rgba(0, 255, 136, 0.12);
            --border-glow: rgba(0, 207, 255, 0.30);

            --text-white: #FFFFFF;
            --text-bright: #E8F4FF;
            --text-muted: rgba(200, 220, 255, 0.55);

            --page-bg: #F5F8FB;
            --page-card: #FFFFFF;
            --page-surface: #EAF0F8;
            --page-border: #D6E3F0;
            --page-border2: #B8CCDF;
            --page-muted: #4A6B8A;
            --page-text: #0A2540;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            background: var(--page-bg);
            color: var(--page-text);
            -webkit-font-smoothing: antialiased;
            height: 100%;
            overflow: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
        }

        /* =============================================
           LAYOUT SHELL
        ============================================= */
        .lessons-shell {
            display: grid;
            grid-template-columns: 252px 1fr;
            height: 100vh;
            overflow: hidden;
        }

        /* =============================================
           LEFT SIDEBAR
        ============================================= */
        .lessons-sidebar {
            height: 100vh;
            overflow-y: auto;
            background: var(--bg-card);
            border-right: 1px solid var(--border-dim);
            display: flex;
            flex-direction: column;
            z-index: 10;
            position: relative;
        }

        .lessons-sidebar::-webkit-scrollbar {
            width: 3px;
        }

        .lessons-sidebar::-webkit-scrollbar-thumb {
            background: var(--border-dim);
            border-radius: 3px;
        }

        /* brand */
        .sb-brand {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: start;
            align-items: start;
            gap: 12px;
            /* padding: 22px 18px 18px; */
            padding: 20px 16px 20px;
            /* border-bottom: 1px solid var(--border-dim); */
        }

        .sb-brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--green-neon), var(--blue-elec));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 16px rgba(0, 255, 136, 0.35);
            flex-shrink: 0;
        }

        .sb-brand-mark svg {
            width: 17px;
            height: 17px;
        }

        .sb-brand-text .name {
            font-weight: 700;
            font-size: 14.5px;
            color: var(--text-white);
        }

        .sb-brand-text .name span {
            color: var(--green-neon);
        }

        .sb-brand-text .sub {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        /* progress */
        .sb-progress-block {
            position: relative;
            /* padding: 14px 16px; */
            padding: 0px 16px 14px;
            border-bottom: 1px solid var(--border-dim);
        }

        .sb-progress-block h5 {
            color: #FFFFFF;
            font-size: 14.5px;
            line-height: 20px;
            font-weight: 600;
            margin: 0 0 15px;
        }

        .sb-progress-label {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 7px;
        }

        .sb-progress-label .pl-title {
            /* font-size: 9px; */
            font-size: 11.5px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .sb-progress-label .pl-pct {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--green-neon);
        }

        .sb-bar-track {
            height: 8px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            overflow: hidden;
        }

        .sb-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--green-dark), var(--green-neon));
            border-radius: 3px;
            box-shadow: 0 0 8px rgba(0, 255, 136, 0.4);
            transition: width .6s ease;
        }

        /* ── Sidebar nav list ── */
        .sb-lesson-list {
            position: relative;
            z-index: 1;
            flex: 1;
            /* padding: 4px 8px 20px; */
            padding: 4px 16px 20px;
            overflow-y: auto;
        }

        .sb-lesson-list::-webkit-scrollbar {
            width: 3px;
        }

        .sb-lesson-list::-webkit-scrollbar-thumb {
            background: var(--border-dim);
            border-radius: 3px;
        }

        /* Group label */
        .sb-nav-group-label {
            /* font-size: 9px; */
            font-size: 10.5px;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 14px 8px 5px;
            opacity: 0.65;
        }

        /* Nav item */
        .sb-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 8px;
            border-radius: 9px;
            margin-bottom: 1px;
            cursor: pointer;
            transition: background .15s;
            position: relative;
            text-decoration: none;
            color: inherit;
        }

        .sb-nav-item:hover {
            background: rgba(0, 207, 255, 0.06);
        }

        .sb-nav-item.active {
            background: rgba(0, 255, 136, 0.09);
        }

        .sb-nav-item.sb-nav-done .sb-nav-title {
            color: rgba(200, 220, 255, 0.75);
        }

        /* Icon box */
        .sb-nav-icon {
            /* width: 28px;
            height: 28px; */
            width: 35px;
            height: 35px;
            flex-shrink: 0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sb-nav-icon svg {
            width: 13px;
            height: 13px;
        }

        .sb-nav-icon .fa {
            font-size: 14px;
        }

        .icon-type-lesson {
            background: rgba(0, 137, 74, 0.14);
            color: var(--green-mid);
        }

        .icon-type-lesson.done {
            background: rgba(0, 137, 74, 0.22);
            color: var(--green-neon);
        }

        .icon-type-lesson.active-dot {
            background: rgba(0, 255, 136, 0.15);
            color: var(--green-neon);
        }

        .icon-type-video {
            background: rgba(255, 107, 0, 0.12);
            color: var(--orange-warn);
        }

        .icon-type-flash {
            background: rgba(0, 153, 204, 0.12);
            color: var(--blue-mid);
        }

        .icon-type-image {
            background: rgba(255, 215, 0, 0.12);
            color: #b38a00;
        }

        .icon-type-activity {
            background: rgba(155, 93, 229, 0.12);
            color: var(--purple-chip);
        }

        .icon-type-activity.done {
            background: rgba(0, 137, 74, 0.18);
            color: var(--green-mid);
        }

        .icon-type-quiz {
            background: rgba(0, 153, 204, 0.12);
            color: var(--blue-mid);
        }

        .icon-type-quiz.done {
            background: rgba(0, 137, 74, 0.18);
            color: var(--green-mid);
        }

        /* Text info */
        .sb-nav-info {
            flex: 1;
            min-width: 0;
        }

        .sb-nav-title {
            /* font-size: 11.5px; */
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .sb-nav-item.active .sb-nav-title {
            color: var(--green-neon);
            font-weight: 600;
        }

        .sb-nav-meta {
            /* font-size: 9.5px; */
            font-size: 11.5px;
            color: var(--text-muted);
            opacity: 0.6;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Completion dot */
        .sb-nav-check {
            /* width: 16px;
            height: 16px; */
            width: 30px;
            height: 30px;
            border-radius: 50%;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 137, 74, 0.15);
            color: var(--green-mid);
        }

        .sb-nav-check-done {
            background: rgba(0, 137, 74, 0.22);
            color: var(--green-neon);
            display: none;
        }

        /* sidebar footer */
        .sb-footer {
            position: relative;
            z-index: 1;
            margin-top: auto;
            padding: 12px;
            border-top: 1px solid var(--border-dim);
            flex-shrink: 0;
            display: none;
        }

        .sb-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 11px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-dim);
        }

        .sb-avatar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--green-mid), var(--blue-mid));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
            color: var(--bg-darkest);
            flex-shrink: 0;
        }

        .sb-user-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-bright);
        }

        .sb-user-id {
            font-size: 9.5px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        /* =============================================
           MAIN CONTENT AREA
        ============================================= */
        .lessons-main {
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            background: var(--page-bg);
        }

        /* TOP NAV BAR */
        .lessons-topbar {
            background: var(--page-card);
            border-bottom: 1px solid var(--page-border);
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 5;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            /* color: var(--green-dark); */
            /* padding: 7px 14px; */
            color: var(--text-muted);
            border-radius: 8px;
            /* background: rgba(0, 137, 74, 0.08); */
            /* border: 1px solid rgba(0, 137, 74, 0.2); */
            transition: background .15s, transform .15s;
        }

        .back-btn .fa{
            font-size: 10px;
        }

        .back-btn:hover {
            /* background: rgba(0, 137, 74, 0.14); */
            transform: translateX(-2px);
        }

        .back-btn svg {
            width: 13px;
            height: 13px;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12.5px;
            color: var(--page-muted);
        }

        .topbar-breadcrumb .sep {
            opacity: 0.4;
        }

        .topbar-breadcrumb .current {
            color: var(--page-text);
            font-weight: 600;
        }

        .topbar-right {
            /* width: 100%; */
            display: flex;
            /* justify-content: space-between; */
            align-items: center;
            gap: 12px;
        }

        .bell-wrap {
            position: relative;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: var(--page-card);
            border: 1px solid var(--page-border);
        }

        .bell-wrap svg {
            width: 16px;
            height: 16px;
            color: var(--page-muted);
        }

        .bell-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red-led);
            box-shadow: 0 0 5px var(--red-led);
        }

        .top-avatar {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--green-mid), var(--blue-mid));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12.5px;
            color: var(--bg-darkest);
        }

        .topbar-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .18s, box-shadow .18s;
            text-decoration: none;
        }

        .btn-prev-top {
            background: var(--page-surface);
            border: 1.5px solid var(--page-border);
            color: var(--page-text);
        }

        .btn-prev-top:hover {
            background: var(--page-border);
        }

        .btn-next-top {
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            border: none;
            color: #fff;
        }

        .btn-next-top:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 137, 74, 0.3);
        }

        .btn-completed-top {
            background: var(--page-surface);
            border: 1.5px solid rgba(0, 137, 74, 0.3);
            color: var(--green-dark);
            cursor: default;
            opacity: 0.85;
        }

        /* =============================================
           MODULE HERO
        ============================================= */
        .module-hero {
            margin: 24px 28px 0;
            border-radius: 16px;
            overflow: hidden;
            background: var(--page-card);
            border: 1px solid var(--page-border);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .module-hero-banner {
            height: 8px;
            background: linear-gradient(90deg, var(--green-dark), var(--green-neon), var(--blue-elec));
        }

        .module-hero-body {
            padding: 22px 26px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .module-hero-left {
            flex: 1;
            min-width: 0;
        }

        .module-hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--green-dark);
            background: rgba(0, 137, 74, 0.08);
            border: 1px solid rgba(0, 137, 74, 0.2);
            padding: 4px 12px;
            border-radius: 99px;
            margin-bottom: 10px;
        }

        .module-hero-tag svg {
            width: 11px;
            height: 11px;
        }

        .module-hero-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--page-text);
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .module-hero-desc {
            font-size: 13.5px;
            color: var(--page-muted);
            line-height: 1.6;
            margin: 0;
        }

        .module-hero-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            flex-shrink: 0;
        }

        .hero-stat {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12.5px;
            color: var(--page-muted);
            background: var(--page-surface);
            border: 1px solid var(--page-border);
            border-radius: 99px;
            padding: 6px 16px;
        }

        .hero-stat svg {
            width: 13px;
            height: 13px;
            color: var(--green-dark);
        }

        .hero-stat-num {
            font-weight: 700;
            color: var(--page-text);
        }

        /* =============================================
           CONTENT WRAPPER
        ============================================= */
        .lessons-content-wrap {
            padding: 35px 28px 60px;
        }

        .lesson-title-row {
            display: flex;
            flex-direction: column;
            align-items: start;
            /* gap: 14px; */
            gap: 18px;
            margin-bottom: 20px;
        }

        .lesson-num-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 5px 12px;
            border-radius: 99px;
            background: rgba(0, 137, 74, 0.09);
            border: 1px solid rgba(0, 137, 74, 0.22);
            color: var(--green-dark);
            white-space: nowrap;
        }

        .lesson-main-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--page-text);
            line-height: 1.3;
        }

        /* =============================================
           SECTIONS
        ============================================= */
        .ls-section {
            margin-bottom: 28px;
        }

        .ls-section-head {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 16px;
        }

        .ls-section-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ls-section-icon svg {
            width: 15px;
            height: 15px;
        }

        .icon-lesson {
            background: rgba(0, 137, 74, 0.12);
            color: var(--green-dark);
        }

        .icon-video {
            background: rgba(255, 107, 0, 0.12);
            color: var(--orange-warn);
        }

        .icon-image {
            background: rgba(255, 215, 0, 0.12);
            color: #996600;
        }

        .icon-flash {
            background: rgba(0, 153, 204, 0.12);
            color: var(--blue-mid);
        }

        .icon-activity {
            background: rgba(155, 93, 229, 0.12);
            color: var(--purple-chip);
        }

        .icon-quiz {
            background: rgba(0, 207, 255, 0.12);
            color: var(--blue-mid);
        }

        .ls-section-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--page-text);
        }

        .ls-section-divider {
            flex: 1;
            height: 1px;
            background: var(--page-border);
        }

        .ls-section-count {
            font-size: 10px;
            color: var(--page-muted);
            background: var(--page-surface);
            border: 1px solid var(--page-border);
            padding: 2px 8px;
            border-radius: 99px;
        }

        /* Lesson text */
        .lesson-text-card {
            font-size: 14.5px;
            line-height: 1.85;
            color: var(--page-text);
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            max-width: 100%;
        }

        /* Video */
        .video-card {
            background: var(--page-card);
            border: 1px solid var(--page-border);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 16px;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
            transition: box-shadow .2s, transform .2s;
        }

        .video-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.09);
            transform: translateY(-2px);
        }

        .video-card-banner {
            height: 5px;
            background: linear-gradient(90deg, var(--orange-warn), #FFAA44);
        }

        .video-card iframe {
            width: 100%;
            height: 300px;
            display: block;
            border: none;
        }

        .video-card-info {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .video-type-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: rgba(255, 107, 0, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--orange-warn);
            flex-shrink: 0;
        }

        .video-type-icon svg {
            width: 14px;
            height: 14px;
        }

        .video-card-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--page-text);
        }

        /* Images */
        .img-grid {
            display: grid;
            gap: 14px;
            margin-top: 1.5rem;
        }

        .img-item {
            border-radius: 12px;
            overflow: hidden;
            background: var(--page-card);
            border: 1px solid var(--page-border);
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, border-color .2s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
        }

        .img-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.10);
            border-color: var(--page-border2);
        }

        .img-item img {
            width: 100%;
            height: 385px;
            display: block;
        }

        .img-item-cap {
            padding: 10px 12px;
            font-size: 12.5px;
            color: var(--page-muted);
            border-top: 1px solid var(--page-border);
        }

        /* Flashcards */
        .fc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .fc-item {
            height: 150px;
            perspective: 1000px;
            cursor: pointer;
        }

        .fc-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform .45s cubic-bezier(.4, 0, .2, 1);
            border-radius: 12px;
        }

        .fc-item.flipped .fc-inner {
            transform: rotateY(180deg);
        }

        .fc-front,
        .fc-back {
            position: absolute;
            inset: 0;
            backface-visibility: hidden;
            border-radius: 12px;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1.5px solid;
        }

        .fc-front {
            background: linear-gradient(135deg, #f0fdf4, #e8fbf0);
            border-color: #bbf7d0;
        }

        .fc-back {
            background: linear-gradient(135deg, #f5f3ff, #ede9fe);
            border-color: #ddd6fe;
            transform: rotateY(180deg);
        }

        .fc-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .12em;
            display: block;
            margin-bottom: 8px;
            opacity: .55;
        }

        .fc-front .fc-label {
            color: #065f46;
        }

        .fc-back .fc-label {
            color: #5b21b6;
        }

        .fc-text {
            font-size: 12.5px;
            font-weight: 600;
            line-height: 1.45;
            display: block;
        }

        .fc-front .fc-text {
            color: #065f46;
        }

        .fc-back .fc-text {
            color: #4c1d95;
        }

        .fc-hint {
            font-size: 10px;
            color: #aaa;
            margin-top: 8px;
            display: block;
        }

        /* Callouts */
        .callout {
            border-radius: 12px;
            padding: 15px 18px;
            margin: 16px 0;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .callout.info {
            background: rgba(0, 153, 204, 0.07);
            border: 1px solid rgba(0, 153, 204, 0.2);
        }

        .callout.warning {
            background: rgba(255, 107, 0, 0.07);
            border: 1px solid rgba(255, 107, 0, 0.2);
        }

        .callout.success {
            background: rgba(0, 137, 74, 0.07);
            border: 1px solid rgba(0, 137, 74, 0.2);
        }

        .callout-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .callout.info .callout-icon {
            background: rgba(0, 153, 204, 0.15);
            color: var(--blue-mid);
        }

        .callout.warning .callout-icon {
            background: rgba(255, 107, 0, 0.15);
            color: var(--orange-warn);
        }

        .callout.success .callout-icon {
            background: rgba(0, 137, 74, 0.15);
            color: var(--green-dark);
        }

        .callout-icon svg {
            width: 15px;
            height: 15px;
        }

        .callout-body .cb-title {
            font-size: 12.5px;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .callout.info .cb-title {
            color: var(--blue-mid);
        }

        .callout.warning .cb-title {
            color: var(--orange-warn);
        }

        .callout.success .cb-title {
            color: var(--green-dark);
        }

        .callout-body p {
            font-size: 13px;
            color: var(--page-muted);
            line-height: 1.6;
        }

        /* Activity */
        .activity-block {
            margin-bottom: 24px;
        }

        .activity-hero-card {
            background: linear-gradient(135deg, #5B2E94, var(--purple-chip));
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }

        .activity-hero-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 22px 22px;
        }

        .act-hero-tag {
            position: relative;
            z-index: 1;
            display: inline-block;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            background: rgba(0, 0, 0, 0.25);
            color: rgba(255, 255, 255, 0.8);
            padding: 3px 10px;
            border-radius: 99px;
            margin-bottom: 8px;
        }

        .act-hero-title {
            position: relative;
            z-index: 1;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        .act-hero-desc {
            position: relative;
            z-index: 1;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.55;
            margin-bottom: 12px;
        }

        .act-meta-pills {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
        }

        .meta-pill {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 99px;
        }

        .pill-white {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .pill-green {
            background: rgba(0, 137, 74, 0.12);
            color: var(--green-dark);
            border: 1px solid rgba(0, 137, 74, 0.22);
        }

        .pill-orange {
            background: rgba(255, 107, 0, 0.10);
            color: var(--orange-warn);
            border: 1px solid rgba(255, 107, 0, 0.22);
        }

        .pill-purple {
            background: rgba(155, 93, 229, 0.10);
            color: var(--purple-chip);
            border: 1px solid rgba(155, 93, 229, 0.22);
        }

        .submitted-notice {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(0, 137, 74, 0.07);
            border: 1px solid rgba(0, 137, 74, 0.25);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 16px;
        }

        .submitted-check {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(0, 137, 74, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green-dark);
            flex-shrink: 0;
        }

        .submitted-check svg {
            width: 18px;
            height: 18px;
        }

        .submitted-notice-text .sn-title {
            font-weight: 700;
            font-size: 13.5px;
            margin-bottom: 2px;
            color: var(--green-dark);
        }

        .submitted-notice-text .sn-sub {
            font-size: 12px;
            color: var(--page-muted);
        }

        .question-card {
            background: var(--page-card);
            border: 1px solid var(--page-border);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            transition: box-shadow .2s;
        }

        .question-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .q-num-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--purple-chip);
            margin-bottom: 7px;
        }

        .q-text {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--page-text);
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .mc-choice {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border);
            cursor: pointer;
            font-size: 13.5px;
            color: var(--page-text);
            margin-bottom: 8px;
            transition: border-color .18s, background .18s;
            user-select: none;
        }

        .mc-choice:hover {
            border-color: var(--purple-chip);
            background: rgba(155, 93, 229, 0.05);
        }

        .mc-choice.selected {
            border-color: var(--purple-chip);
            background: rgba(155, 93, 229, 0.08);
            color: #4c1d95;
        }

        .mc-letter {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: var(--page-surface);
            border: 1px solid var(--page-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11.5px;
            color: var(--page-muted);
            flex-shrink: 0;
            transition: background .18s, color .18s;
        }

        .mc-choice.selected .mc-letter {
            background: var(--purple-chip);
            color: #fff;
            border-color: var(--purple-chip);
        }

        .review-choice {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border);
            font-size: 13.5px;
            color: var(--page-text);
            margin-bottom: 8px;
            pointer-events: none;
        }

        .activity-answer {
            width: 100%;
            background: var(--page-card);
            border: 1.5px solid var(--page-border);
            border-radius: 9px;
            padding: 12px 14px;
            font-size: 14px;
            resize: vertical;
            min-height: 90px;
            color: var(--page-text);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box;
        }

        .activity-answer:focus {
            border-color: var(--purple-chip);
            box-shadow: 0 0 0 3px rgba(155, 93, 229, 0.12);
        }

        /* Quiz */
        .quiz-hero-card {
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .quiz-hero-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 22px 22px;
        }

        .quiz-hero-inner {
            position: relative;
            z-index: 1;
        }

        .quiz-hero-tag {
            display: inline-block;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            background: rgba(0, 0, 0, 0.22);
            color: rgba(255, 255, 255, 0.8);
            padding: 3px 10px;
            border-radius: 99px;
            margin-bottom: 8px;
        }

        .quiz-hero-title {
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 6px;
        }

        .quiz-hero-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 12px;
        }

        .quiz-stats-strip {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .quiz-stat {
            text-align: center;
        }

        .quiz-stat .qs-val {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }

        .quiz-stat .qs-lbl {
            font-size: 10.5px;
            color: rgba(255, 255, 255, 0.55);
            margin-top: 2px;
        }

        .q-card {
            background: var(--page-card);
            border: 1px solid var(--page-border);
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        .q-number {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--blue-mid);
            margin-bottom: 7px;
        }

        .q-question {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--page-text);
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .q-choice {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border);
            cursor: pointer;
            font-size: 13.5px;
            color: var(--page-text);
            margin-bottom: 8px;
            transition: border-color .18s, background .18s;
            user-select: none;
        }

        .q-choice:hover {
            border-color: var(--blue-mid);
            background: rgba(0, 153, 204, 0.05);
        }

        .q-choice.selected {
            border-color: var(--blue-mid);
            background: rgba(0, 153, 204, 0.08);
        }

        .q-choice.selected .choice-letter {
            background: var(--blue-mid);
            color: #fff;
            border-color: var(--blue-mid);
        }

        .choice-letter {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: var(--page-surface);
            border: 1px solid var(--page-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11.5px;
            color: var(--page-muted);
            flex-shrink: 0;
            transition: background .18s, color .18s;
        }

        .quiz-nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
            background: var(--page-card);
            border: 1px solid var(--page-border);
            border-radius: 12px;
            padding: 14px 18px;
        }

        .quiz-status {
            font-size: 12.5px;
            color: var(--page-muted);
        }

        .quiz-nav-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-qnav-prev {
            padding: 9px 18px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border);
            background: var(--page-card);
            color: var(--page-text);
            font-size: 13px;
            font-weight: 600;
            transition: border-color .18s;
        }

        .btn-qnav-prev:hover {
            border-color: var(--blue-mid);
            color: var(--blue-mid);
        }

        .btn-qnav-next,
        .btn-submit-quiz {
            padding: 9px 20px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--blue-dark), var(--blue-mid));
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            transition: transform .18s, box-shadow .18s;
        }

        .btn-qnav-next:hover,
        .btn-submit-quiz:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0, 153, 204, 0.3);
        }

        .btn-qnav-next:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        .page-indicator {
            font-size: 12.5px;
            color: var(--page-muted);
        }

        /* Lock notice */
        .lock-notice {
            display: none;
            align-items: center;
            gap: 9px;
            background: rgba(255, 107, 0, 0.07);
            border: 1px solid rgba(255, 107, 0, 0.25);
            border-radius: 10px;
            padding: 11px 16px;
            font-size: 13px;
            color: var(--orange-warn);
            position: sticky;
            top: 0;
            z-index: 4;
            margin: 0 0 16px 0;
            border-radius: 0;
        }

        .lock-notice svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        /* Lightbox */
        .db-lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(5, 13, 20, 0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(6px);
        }

        .db-lightbox.open {
            display: flex;
        }

        .db-lightbox img {
            max-width: 90vw;
            max-height: 88vh;
            border-radius: 14px;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.6);
        }

        .db-lightbox-close {
            position: absolute;
            top: 22px;
            right: 28px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .db-lightbox-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 80px 40px;
            color: var(--page-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }

        .empty-state svg {
            width: 42px;
            height: 42px;
            margin-bottom: 14px;
            opacity: 0.35;
            display: block;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .lessons-shell {
                grid-template-columns: 1fr;
            }

            .lessons-sidebar {
                display: none;
            }
        }

        @media (max-width: 600px) {
            .lessons-topbar {
                padding: 0 16px;
            }

            .lessons-content-wrap {
                padding: 16px 16px 60px;
            }

            .module-hero {
                margin: 16px 16px 0;
            }

            .img-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="lessons-shell">

        <?php include("../components/offcanvas.php"); ?>

        <!-- =============================================
             LEFT SIDEBAR
        ============================================= -->
        <aside class="lessons-sidebar">

            <!-- Brand -->
            <div class="sb-brand">
                <!-- <div class="sb-brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#050D14" stroke-width="2">
                        <path d="M9 3v4M15 3v4M9 17v4M15 17v4M3 9h4M3 15h4M17 9h4M17 15h4" stroke-linecap="round" />
                        <rect x="7" y="7" width="10" height="10" rx="1.5" />
                    </svg>
                </div> -->
                <!-- <div class="sb-brand-text"> -->
                <!-- <div class="name">iLearn<span>-CSS</span></div>
                    <div class="sub">CSS Student Portal</div> -->
                <!-- <img src="../images/iLearn-7.png" alt=""> -->
                <!-- </div> -->
                <a class="back-btn" href="/learning_management/public/?url=modules&subject=<?= urlencode($subject) ?>">
                    <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 12H5M11 6l-6 6 6 6" />
                    </svg> -->
                    <i class="fa fa-chevron-left"></i>

                    Back to Modules
                </a>
            </div>

            <?php $progressPct = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0; ?>

            <!-- Progress -->
            <div class="sb-progress-block">
                <h5><?= htmlspecialchars($module['title'] ?? '') ?></h5>
                <div class="sb-progress-label">
                    <span class="pl-title">Module Progress</span>
                    <span class="pl-pct" id="progressPercent"><?= $progressPct ?>%</span>
                </div>
                <div class="sb-bar-track">
                    <div class="sb-bar-fill" id="progressBar" style="width:<?= $progressPct ?>%"></div>
                </div>
            </div>

            <!-- ── Sidebar Nav ── -->
            <div class="sb-lesson-list">

                <!-- LESSONS -->
                <div class="sb-nav-group-label">Lessons</div>
                <?php foreach ($lessons as $i => $l):
                    $isActive = ($l['id'] == $lessonId);
                    $isDone = $lessonCompletion[$l['id']] ?? false;
                    $rawTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $l['title']);
                    $dotClass = $isActive ? 'active-dot' : ($isDone ? 'done' : 'pending');
                    $itemClass = $isActive ? 'active' : '';
                    ?>
                    <a class="sb-nav-item <?= $itemClass ?>" href="<?= lUrl($subject, $moduleId, $l['id']) ?>">
                        <div class="sb-nav-icon icon-type-lesson <?= $dotClass ?>">
                            <?php if ($isDone): ?>
                                <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M20 6L9 17l-5-5" />
                                </svg> -->
                                <i class="fa fa-check"></i>
                            <?php else: ?>
                                <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                </svg> -->
                                <i class="fa fa-book-open"></i>
                            <?php endif; ?>
                        </div>
                        <div class="sb-nav-info">
                            <div class="sb-nav-title">Lesson <?= $i + 1 ?>: <?= htmlspecialchars($rawTitle) ?></div>
                            <div class="sb-nav-meta"><?= $isDone ? 'Completed' : 'Not started' ?></div>
                        </div>
                        <?php if ($isDone): ?>
                            <div class="sb-nav-check sb-nav-check-done">
                                <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="10"
                                    height="10">
                                    <path d="M20 6L9 17l-5-5" />
                                </svg> -->
                                <i class="fa fa-check"></i>
                            </div>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>



            </div><!-- /sb-lesson-list -->

            <!-- Footer user -->
            <div class="sb-footer">
                <div class="sb-user">
                    <div class="sb-avatar">
                        <?php
                        $initials = 'MA';
                        if (!empty($studentName)) {
                            $parts = explode(' ', trim($studentName));
                            $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                        }
                        echo htmlspecialchars($initials);
                        ?>
                    </div>
                    <div>
                        <div class="sb-user-name"><?= htmlspecialchars($studentName ?? 'Student') ?></div>
                        <div class="sb-user-id">LRN: <?= htmlspecialchars($studentLrn ?? $studentId) ?></div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- =============================================
             MAIN CONTENT
        ============================================= -->
        <div class="lessons-main">

            <!-- TOP NAV BAR -->
            <div class="lessons-topbar">
                <div class="topbar-left">
                    <!-- <a class="back-btn"
                        href="/learning_management/public/?url=modules&subject=<?= urlencode($subject) ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M19 12H5M11 6l-6 6 6 6" />
                        </svg>
                        Back to Modules
                    </a> -->
                    <!-- <div class="topbar-breadcrumb">
                        <span class="sep">›</span>
                        <span><?= htmlspecialchars($module['title'] ?? '') ?></span>
                    </div> -->
                </div>
                <div class="topbar-right">
                    <?php if ($lesson): ?>
                        <?php if ($prevLessonId): ?>
                            <a class="topbar-nav-btn btn-prev-top" href="<?= lUrl($subject, $moduleId, $prevLessonId) ?>"
                                id="prevBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14"
                                    height="14">
                                    <path d="M19 12H5M11 6l-6 6 6 6" />
                                </svg>
                                Prev
                            </a>
                        <?php endif; ?>

                        <?php if ($nextLessonId): ?>
                            <a class="topbar-nav-btn btn-next-top" href="<?= lUrl($subject, $moduleId, $nextLessonId) ?>"
                                id="nextBtn">
                                Next
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14"
                                    height="14">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        <?php else: ?>
                            <?php
                            $studentModel2 = new Students();
                            $lastLessonDone = $studentId ? $studentModel2->isLessonCompleted($lessonId, $studentId) : false;
                            ?>
                            <?php if ($lastLessonDone): ?>
                                <span class="topbar-nav-btn btn-completed-top" id="nextBtn">
                                    Completed
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14"
                                        height="14">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <path d="M22 4L12 14.01l-3-3" />
                                    </svg>
                                </span>
                            <?php else: ?>
                                <button class="topbar-nav-btn btn-next-top" id="nextBtn" type="button">
                                    Finish
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14"
                                        height="14">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                        <path d="M22 4L12 14.01l-3-3" />
                                    </svg>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- <div class="bell-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.7 21a2 2 0 0 1-3.4 0" />
                        </svg>
                        <span class="bell-dot"></span>
                    </div>
                    <div class="top-avatar"><?= htmlspecialchars($initials ?? 'MA') ?></div> -->
                </div>
            </div>

            <!-- MODULE HERO -->
            <!-- <div class="module-hero">
                <div class="module-hero-banner"></div>
                <div class="module-hero-body">
                    <div class="module-hero-left">
                        <div class="module-hero-tag">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            <?= htmlspecialchars($module['subject_name'] ?? 'Module') ?>
                        </div>
                        <h2 class="module-hero-title"><?= htmlspecialchars($module['title'] ?? '') ?></h2>
                        <?php if (!empty($module['description'])): ?>
                            <p class="module-hero-desc"><?= nl2br(htmlspecialchars($module['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="module-hero-right">
                        <div class="hero-stat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            <span><span class="hero-stat-num"><?= $totalLessons ?></span> Lessons</span>
                        </div>
                        <div class="hero-stat">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <path d="M22 4L12 14.01l-3-3" />
                            </svg>
                            <span><span class="hero-stat-num"><?= $completedCount ?></span> Completed</span>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- LESSON CONTENT -->
            <div class="lessons-content-wrap">

                <?php if (!$lesson): ?>
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                        <p>Choose a lesson from the sidebar to get started.</p>
                    </div>

                <?php else:
                    $cleanTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $lesson['title']);
                    ?>

                    <!-- LESSON TITLE -->
                    <div class="lesson-title-row">
                        <span class="lesson-num-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11"
                                height="11">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Lesson <?= $currentIndex ?>
                        </span>
                        <h3 class="lesson-main-title" id="lesson-title"><?= htmlspecialchars($cleanTitle) ?></h3>
                    </div>

                    <!-- 1. LESSON TEXT -->
                    <?php if (!empty($lesson['content'])): ?>
                        <div class="ls-section">
                            <div class="ls-section-head">
                                <div class="ls-section-divider"></div>
                            </div>
                            <div class="lesson-text-card">
                                <?= nl2br(htmlspecialchars($lesson['content'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 2. VIDEOS -->
                    <?php if (!empty($videos)): ?>
                        <div class="ls-section" id="section-videos">
                            <div class="ls-section-head">
                                <div class="ls-section-icon icon-video">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M23 7l-7 5 7 5V7z" />
                                        <rect x="1" y="5" width="15" height="14" rx="2" />
                                    </svg>
                                </div>
                                <span class="ls-section-title">Videos</span>
                                <div class="ls-section-divider"></div>
                                <span class="ls-section-count"><?= count($videos) ?></span>
                            </div>
                            <?php foreach ($videos as $vid): ?>
                                <div class="video-card">
                                    <div class="video-card-banner"></div>
                                    <iframe src="<?= htmlspecialchars(youtubeEmbed($vid['file_path'])) ?>" allowfullscreen
                                        loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                    </iframe>
                                    <?php if (!empty($vid['title'])): ?>
                                        <div class="video-card-info">
                                            <div class="video-type-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M23 7l-7 5 7 5V7z" />
                                                    <rect x="1" y="5" width="15" height="14" rx="2" />
                                                </svg>
                                            </div>
                                            <span class="video-card-title"><?= htmlspecialchars($vid['title']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- 3. IMAGES -->
                    <?php if (!empty($images)): ?>
                        <div class="ls-section" id="section-images">
                            <div class="img-grid">
                                <?php foreach ($images as $img): ?>
                                    <div class="img-item" onclick="dbLightbox('<?= htmlspecialchars($img['file_path']) ?>')">
                                        <img src="<?= htmlspecialchars($img['file_path']) ?>"
                                            alt="<?= htmlspecialchars($img['title'] ?? '') ?>" loading="lazy">
                                        <?php if (!empty($img['title'])): ?>
                                            <div class="img-item-cap"><?= htmlspecialchars($img['title']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 4. FLASHCARDS -->
                    <?php if (!empty($flashcards)): ?>
                        <div class="ls-section" id="section-flashcards">
                            <div class="ls-section-head">
                                <div class="ls-section-icon icon-flash">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="5" width="20" height="14" rx="2" />
                                        <path d="M2 10h20" />
                                    </svg>
                                </div>
                                <span class="ls-section-title">Flashcards</span>
                                <div class="ls-section-divider"></div>
                                <span class="ls-section-count"><?= count($flashcards) ?> cards</span>
                            </div>
                            <div class="callout info">
                                <div class="callout-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 16v-4M12 8h.01" />
                                    </svg>
                                </div>
                                <div class="callout-body">
                                    <div class="cb-title">How to Use Flashcards</div>
                                    <p>Click any card to flip it and reveal the answer. Click again to go back to the question.
                                    </p>
                                </div>
                            </div>
                            <div class="fc-grid">
                                <?php foreach ($flashcards as $fc): ?>
                                    <div class="fc-item" onclick="this.classList.toggle('flipped')">
                                        <div class="fc-inner">
                                            <div class="fc-front">
                                                <span class="fc-label">Question</span>
                                                <span class="fc-text"><?= htmlspecialchars($fc['card_front']) ?></span>
                                                <span class="fc-hint">Tap to reveal →</span>
                                            </div>
                                            <div class="fc-back">
                                                <span class="fc-label">Answer</span>
                                                <span class="fc-text"><?= htmlspecialchars($fc['card_back']) ?></span>
                                                <span class="fc-hint">← Tap to go back</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- 5. ACTIVITIES -->
                    <?php if (!empty($activityData)): ?>
                        <div class="ls-section" id="section-activities">
                            <div class="ls-section-head">
                                <div class="ls-section-icon icon-activity">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </div>
                                <span class="ls-section-title">Activities</span>
                                <div class="ls-section-divider"></div>
                                <span class="ls-section-count"><?= count($activityData) ?></span>
                            </div>

                            <?php foreach ($activityData as $actId => $data):
                                $act = $data['activity'];
                                $questions = $data['questions'];
                                $isSubmitted = ($data['submission'] !== null);
                                ?>
                                <div class="activity-block">
                                    <div class="activity-hero-card">
                                        <div class="act-hero-tag">✏️ Hands-On Activity</div>
                                        <div class="act-hero-title"><?= htmlspecialchars($act['title']) ?></div>
                                        <?php if (!empty($act['instructions'])): ?>
                                            <div class="act-hero-desc"><?= htmlspecialchars($act['instructions']) ?></div>
                                        <?php endif; ?>
                                        <div class="act-meta-pills">
                                            <span class="meta-pill pill-white"><?= count($questions) ?> Questions</span>
                                            <span class="meta-pill pill-white">⭐ <?= (int) $act['total_points'] ?> pts</span>
                                            <?php if ($isSubmitted): ?>
                                                <span class="meta-pill pill-white">✓ Submitted</span>
                                            <?php else: ?>
                                                <span class="meta-pill pill-white">Answer Required</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if ($isSubmitted): ?>
                                        <div class="submitted-notice">
                                            <div class="submitted-check">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                    <path d="M22 4L12 14.01l-3-3" />
                                                </svg>
                                            </div>
                                            <div class="submitted-notice-text">
                                                <div class="sn-title">Activity Submitted</div>
                                                <div class="sn-sub">You have already completed this activity. Review your answers below.
                                                </div>
                                            </div>
                                        </div>

                                        <?php foreach ($questions as $qi => $q): ?>
                                            <div class="question-card">
                                                <div class="q-num-label">Question <?= $qi + 1 ?></div>
                                                <div class="q-text"><?= htmlspecialchars($q['question']) ?></div>
                                                <?php if ($q['question_type'] === 'multiple_choice'):
                                                    $ltrs = ['A', 'B', 'C', 'D'];
                                                    $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                                    $li = 0;
                                                    foreach ($ch as $key => $val):
                                                        if ($val === null)
                                                            continue;
                                                        $isCorrect = strtolower($key) === strtolower($q['correct_ans'] ?? '');
                                                        ?>
                                                        <div class="review-choice"
                                                            style="<?= $isCorrect ? 'border-color:#22c55e;background:rgba(34,197,94,0.06);' : '' ?>">
                                                            <span class="mc-letter"
                                                                style="<?= $isCorrect ? 'background:#22c55e;color:#fff;border-color:#22c55e;' : '' ?>"><?= $ltrs[$li++] ?></span>
                                                            <?= htmlspecialchars($val) ?>
                                                            <?php if ($isCorrect): ?>
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" width="14"
                                                                    height="14" style="margin-left:auto">
                                                                    <path d="M20 6L9 17l-5-5" />
                                                                </svg>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <div
                                                        style="background:var(--page-surface);border:1.5px solid var(--page-border);border-radius:9px;padding:12px 14px;font-size:13.5px;color:var(--page-muted);font-style:italic;">
                                                        Essay question — written response recorded
                                                    </div>
                                                    <?php if (!empty($q['model_answer'])): ?>
                                                        <div class="callout success" style="margin-top:8px;">
                                                            <div class="callout-icon">
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path
                                                                        d="M9 18h6M10 22h4M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17H8v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7z" />
                                                                </svg>
                                                            </div>
                                                            <div class="callout-body">
                                                                <div class="cb-title">Model Answer</div>
                                                                <p><?= nl2br(htmlspecialchars($q['model_answer'])) ?></p>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php else: ?>
                                        <div class="activity-answers-wrapper" data-activity-id="<?= (int) $actId ?>">
                                            <?php foreach ($questions as $qi => $q): ?>
                                                <div class="question-card">
                                                    <div class="q-num-label">Question <?= $qi + 1 ?></div>
                                                    <div class="q-text"><?= htmlspecialchars($q['question']) ?></div>
                                                    <?php if ($q['question_type'] === 'multiple_choice'):
                                                        $ltrs = ['A', 'B', 'C', 'D'];
                                                        $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                                        $li = 0;
                                                        ?>
                                                        <input type="hidden" id="mc_hidden_<?= (int) $q['id'] ?>" value="">
                                                        <?php foreach ($ch as $key => $val):
                                                            if ($val === null)
                                                                continue;
                                                            ?>
                                                            <div class="mc-choice" data-qid="<?= (int) $q['id'] ?>"
                                                                data-act-id="<?= (int) $actId ?>" data-key="<?= $key ?>" onclick="pickMC(this)">
                                                                <span class="mc-letter"><?= $ltrs[$li++] ?></span>
                                                                <?= htmlspecialchars($val) ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <textarea class="activity-answer" data-qid="<?= (int) $q['id'] ?>"
                                                            data-act-id="<?= (int) $actId ?>" placeholder="Type your answer here…"
                                                            rows="4"></textarea>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div><!-- /activity-block -->
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- 6. QUIZZES -->
                    <?php if (!empty($quizData)): ?>
                        <?php
                        $allQzQuestions = [];
                        $firstQzDone = true;
                        foreach ($quizData as $qzId => $data) {
                            if (!$data['result'])
                                $firstQzDone = false;
                            foreach ($data['questions'] as $q) {
                                $allQzQuestions[] = [
                                    'q' => $q,
                                    'qzId' => (int) $qzId,
                                    'passing_score' => (int) $data['quiz']['passing_score'],
                                    'result' => $data['result'],
                                    'quiz' => $data['quiz'],
                                ];
                            }
                        }
                        $grandTotal = count($allQzQuestions);
                        $firstData = reset($quizData);
                        $firstQz = $firstData['quiz'];
                        $questionsPerPage = 5;
                        $totalPages = $grandTotal > 0 ? (int) ceil($grandTotal / $questionsPerPage) : 1;
                        ?>

                        <div class="ls-section" id="section-quizzes">
                            <div class="ls-section-head">
                                <div class="ls-section-icon icon-quiz">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 11l3 3L22 4" />
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                                    </svg>
                                </div>
                                <span class="ls-section-title">Quiz</span>
                                <div class="ls-section-divider"></div>
                                <span class="ls-section-count"><?= $grandTotal ?> questions</span>
                            </div>

                            <?php if ($firstQzDone): ?>
                                <?php foreach ($quizData as $qzId => $data):
                                    if (!$data['result'])
                                        continue;
                                    ?>
                                    <div class="quiz-hero-card">
                                        <div class="quiz-hero-inner">
                                            <div class="quiz-hero-tag">📋 Quiz Results</div>
                                            <div class="quiz-hero-title"><?= htmlspecialchars($firstQz['title']) ?></div>
                                            <?php if (!empty($firstQz['instructions'])): ?>
                                                <div class="quiz-hero-desc"><?= htmlspecialchars($firstQz['instructions']) ?></div>
                                            <?php endif; ?>
                                            <div class="quiz-stats-strip">
                                                <div class="quiz-stat">
                                                    <div class="qs-val"><?= (int) $data['result']['score'] ?></div>
                                                    <div class="qs-lbl">Your Score</div>
                                                </div>
                                                <div class="quiz-stat">
                                                    <div class="qs-val"><?= (int) $data['result']['total'] ?></div>
                                                    <div class="qs-lbl">Total Points</div>
                                                </div>
                                                <div class="quiz-stat">
                                                    <div class="qs-val"><?= (int) $firstQz['passing_score'] ?></div>
                                                    <div class="qs-lbl">Passing Score</div>
                                                </div>
                                                <div class="quiz-stat">
                                                    <div class="qs-val"><?= $grandTotal ?></div>
                                                    <div class="qs-lbl">Questions</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $studentAnswers = [];
                                    if (!empty($data['result']['answers_json']))
                                        $studentAnswers = json_decode($data['result']['answers_json'], true) ?? [];
                                    ?>
                                    <?php foreach ($data['questions'] as $qi => $q):
                                        $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                        $qLtrs = ['A', 'B', 'C', 'D'];
                                        $studentPicked = strtolower($studentAnswers[$q['id']] ?? '');
                                        ?>
                                        <div class="q-card">
                                            <p class="q-number">Question <?= $qi + 1 ?></p>
                                            <p class="q-question"><?= htmlspecialchars($q['question']) ?></p>
                                            <div>
                                                <?php $li = 0;
                                                foreach ($ch as $key => $val):
                                                    if ($val === null)
                                                        continue;
                                                    $isCorrect = strtolower($key) === strtolower($q['correct_ans']);
                                                    $isPicked = ($key === $studentPicked);
                                                    $isWrong = ($isPicked && !$isCorrect);
                                                    if ($isCorrect) {
                                                        $bs = 'border-color:#22c55e;background:rgba(34,197,94,0.06);';
                                                        $ls = 'background:#22c55e;color:#fff;border-color:#22c55e;';
                                                        $ic = '<svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" width="14" height="14" style="margin-left:auto"><path d="M20 6L9 17l-5-5"/></svg>';
                                                    } elseif ($isWrong) {
                                                        $bs = 'border-color:#ef4444;background:rgba(239,68,68,0.06);';
                                                        $ls = 'background:#ef4444;color:#fff;border-color:#ef4444;';
                                                        $ic = '<svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" width="14" height="14" style="margin-left:auto"><path d="M18 6L6 18M6 6l12 12"/></svg>';
                                                    } else {
                                                        $bs = 'border-color:var(--page-border);';
                                                        $ls = '';
                                                        $ic = '';
                                                    }
                                                    ?>
                                                    <div class="q-choice" style="pointer-events:none;<?= $bs ?>">
                                                        <span class="choice-letter" style="<?= $ls ?>"><?= $qLtrs[$li++] ?></span>
                                                        <?= htmlspecialchars($val) ?>
                                                        <?= $ic ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <div class="quiz-hero-card">
                                    <div class="quiz-hero-inner">
                                        <div class="quiz-hero-tag">📋 Quiz</div>
                                        <div class="quiz-hero-title"><?= htmlspecialchars($firstQz['title']) ?></div>
                                        <?php if (!empty($firstQz['instructions'])): ?>
                                            <div class="quiz-hero-desc"><?= htmlspecialchars($firstQz['instructions']) ?></div>
                                        <?php endif; ?>
                                        <div class="quiz-stats-strip">
                                            <div class="quiz-stat">
                                                <div class="qs-val"><?= $grandTotal ?></div>
                                                <div class="qs-lbl">Questions</div>
                                            </div>
                                            <div class="quiz-stat">
                                                <div class="qs-val"><?= (int) $firstQz['passing_score'] ?></div>
                                                <div class="qs-lbl">Passing Score</div>
                                            </div>
                                            <div class="quiz-stat">
                                                <div class="qs-val"><?= $totalPages ?></div>
                                                <div class="qs-lbl">Pages</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php foreach ($allQzQuestions as $qi => $item):
                                    $q = $item['q'];
                                    $qLtrs = ['A', 'B', 'C', 'D'];
                                    $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                    $pageIdx = (int) floor($qi / $questionsPerPage);
                                    ?>
                                    <div class="q-card unified-q-card" id="unified_q<?= $qi ?>" data-qzid="<?= $item['qzId'] ?>"
                                        data-page="<?= $pageIdx ?>" style="<?= $pageIdx > 0 ? 'display:none;' : '' ?>">
                                        <p class="q-number">Question <?= $qi + 1 ?> of <?= $grandTotal ?></p>
                                        <p class="q-question"><?= htmlspecialchars($q['question']) ?></p>
                                        <div>
                                            <?php $li = 0;
                                            foreach ($ch as $key => $val):
                                                if ($val === null)
                                                    continue;
                                                ?>
                                                <div class="q-choice unified-choice" data-qi="<?= $qi ?>" data-qid="<?= (int) $q['id'] ?>"
                                                    data-key="<?= $key ?>" data-qzid="<?= $item['qzId'] ?>" onclick="unifiedPick(this)">
                                                    <span class="choice-letter"><?= $qLtrs[$li++] ?></span>
                                                    <?= htmlspecialchars($val) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="quiz-nav-bar">
                                    <span class="quiz-status" id="unified_status">0 / <?= $grandTotal ?> answered</span>
                                    <div class="quiz-nav-btns">
                                        <button class="btn-qnav-prev" id="unified_prev" style="display:none;"
                                            onclick="unifiedPageNav(-1)">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13"
                                                height="13">
                                                <path d="M19 12H5M11 6l-6 6 6 6" />
                                            </svg>
                                            Prev
                                        </button>
                                        <span class="page-indicator" id="unified_page_indicator">Page 1 of <?= $totalPages ?></span>
                                        <?php if ($grandTotal > 5): ?>
                                            <button class="btn-qnav-next" id="unified_next" onclick="unifiedPageNav(1)">
                                                Next
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13"
                                                    height="13">
                                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                <?php endif; /* end $lesson check */ ?>
            </div><!-- /lessons-content-wrap -->

        </div><!-- /lessons-main -->
    </div><!-- /lessons-shell -->

    <!-- LIGHTBOX -->
    <div class="db-lightbox" id="dbLightbox" onclick="dbLightboxClose()">
        <button class="db-lightbox-close" onclick="dbLightboxClose()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>
        <img id="dbLightboxImg" src="" alt="">
    </div>

    <!-- PHP data for lessons.js -->
    <script>
        var LESSON_DATA = {
            lessonId: <?= (int) $lessonId ?>,
            moduleId: <?= (int) $moduleId ?>,
            subject: '<?= htmlspecialchars($subject) ?>',
            studentId: <?= (int) $studentId ?>,
            activities: <?= json_encode(array_values(array_map(function ($d) {
                return [
                    'id' => (int) $d['activity']['id'],
                    'required' => count($d['questions']),
                    'done' => ($d['submission'] !== null),
                    'questions' => array_map(function ($q) {
                        return [
                            'id' => (int) $q['id'],
                            'question' => $q['question'],
                            'question_type' => $q['question_type'],
                            'choice_a' => $q['choice_a'] ?? null,
                            'choice_b' => $q['choice_b'] ?? null,
                            'choice_c' => $q['choice_c'] ?? null,
                            'choice_d' => $q['choice_d'] ?? null,
                            'correct_ans' => $q['correct_ans'] ?? null,
                            'model_answer' => $q['model_answer'] ?? null,
                        ];
                    }, $d['questions']),
                ];
            }, $activityData))) ?>,
            quizzes: <?= json_encode(array_values(array_map(function ($d) {
                return [
                    'id' => (int) $d['quiz']['id'],
                    'required' => count($d['questions']),
                    'passing_score' => (int) $d['quiz']['passing_score'],
                    'done' => ($d['result'] !== null),
                ];
            }, $quizData))) ?>
        };

        /* Smooth scroll helper — scrolls inside .lessons-main, not window */
        function scrollToSection(id) {
            var el = document.getElementById(id);
            if (!el) return;
            var main = document.querySelector('.lessons-main');
            if (main) {
                var offset = el.getBoundingClientRect().top - main.getBoundingClientRect().top + main.scrollTop - 16;
                main.scrollTo({ top: offset, behavior: 'smooth' });
            } else {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        }
    </script>

    <?php $hasActiveQuiz = !empty($quizData) && isset($firstQzDone) && !$firstQzDone; ?>
    <?php if ($hasActiveQuiz): ?>
        <script>
            (function () {
                const questionsPerPage = <?= (int) $questionsPerPage ?>;
                const grandTotal = <?= (int) $grandTotal ?>;
                const totalPages = <?= (int) $totalPages ?>;
                let currentPage = 0;

                function showPage(page) {
                    document.querySelectorAll('.unified-q-card').forEach(el => el.style.display = 'none');
                    document.querySelectorAll(`.unified-q-card[data-page="${page}"]`).forEach(el => el.style.display = 'block');
                    var ind = document.getElementById('unified_page_indicator');
                    if (ind) ind.textContent = `Page ${page + 1} of ${totalPages}`;
                    var prev = document.getElementById('unified_prev');
                    if (prev) prev.style.display = page > 0 ? 'inline-flex' : 'none';
                    var next = document.getElementById('unified_next');
                    if (next) {
                        if (grandTotal > 5 && page < totalPages - 1) {
                            next.style.display = 'inline-flex';
                            next.innerHTML = 'Next <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
                            next.onclick = () => unifiedPageNav(1);
                        } else {
                            next.style.display = 'none';
                        }
                    }
                    currentPage = page;
                }

                window.unifiedPageNav = function (dir) {
                    var newPage = currentPage + dir;
                    if (newPage < 0 || newPage >= totalPages) return;
                    document.querySelectorAll('.unified-q-card').forEach(el => el.style.display = 'none');
                    document.querySelectorAll(`.unified-q-card[data-page="${newPage}"]`).forEach(el => el.style.display = 'block');
                    var ind = document.getElementById('unified_page_indicator');
                    if (ind) ind.textContent = `Page ${newPage + 1} of ${totalPages}`;
                    var prev = document.getElementById('unified_prev');
                    if (prev) prev.style.display = newPage > 0 ? 'inline-flex' : 'none';
                    var next = document.getElementById('unified_next');
                    if (next) next.style.display = (grandTotal > 5 && newPage < totalPages - 1) ? 'inline-flex' : 'none';
                    currentPage = newPage;
                };

                showPage(0);
            })();
        </script>
    <?php endif; ?>

    <!-- <script src="../js_folder/lessons.js"></script> -->
    <script src="../js_folder/lessons.js?v=<?= time() ?>"></script>
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>


</html>