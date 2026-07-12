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
            --neon-cyan: #0077cc;
            --neon-blue: #0055aa;
            --electric-purple: #5533cc;
            --deep-navy: #ffffff;
            --panel-dark: #f4f7fb;
            --panel-mid: #e8eef8;
            --panel-border: rgba(0, 100, 200, 0.15);
            --text-bright: #0a1a2e;
            --text-dim: #4a6080;
            --accent-gold: #cc7700;
            --danger-red: #cc2244;
            --bg-main: #ffffff;
            --bg-section-alt: #f0f5fc;
            --panel-bg: #f5f7fb;
            --hero-height: 100px;
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
            /* background: var(--page-bg); */
            background-image: radial-gradient(circle at 15% 0%, rgba(0, 119, 204, 0.06), transparent 45%), radial-gradient(circle at 100% 30%, rgba(85, 51, 204, 0.06), transparent 40%), repeating-linear-gradient(0deg, rgba(0, 100, 200, 0.035) 0px, rgba(0, 100, 200, 0.035) 1px, transparent 1px, transparent 42px), repeating-linear-gradient(90deg, rgba(0, 100, 200, 0.035) 0px, rgba(0, 100, 200, 0.035) 1px, transparent 1px, transparent 42px);
            /* background-color: var(--neon-cyan); */
            color: var(--page-text);
            /* -webkit-font-smoothing: antialiased; */
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
           WELCOME / MODULE SPLASH SCREEN
        ============================================= */
        .module-splash {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            /* background: radial-gradient(circle at 30% 20%, rgba(0, 119, 204, 0.10), transparent 55%),
                radial-gradient(circle at 80% 80%, rgba(85, 51, 204, 0.08), transparent 50%),
                #f4f8fd; */
            background-color: var(--neon-cyan);
            animation: splashFadeIn .5s ease both;
        }

        .module-splash::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(255, 255, 255, 0.05) 0 2px, transparent 2px 26px);
            pointer-events: none;
        }

        @keyframes splashFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .module-splash.splash-exit {
            animation: splashFadeOut .45s ease forwards;
            pointer-events: none;
        }

        @keyframes splashFadeOut {
            to {
                opacity: 0;
                transform: scale(1.04);
            }
        }

        .speech-bubble-progress {
            margin-top: 5px;
        }

        .sb-progress-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
        }

        .sb-progress-row .sbp-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-dim);
            opacity: 0.75;
        }

        .sb-progress-row .sbp-pct {
            font-size: 12px;
            font-weight: 700;
            /* color: var(--neon-cyan); */
        }

        /* ── DASH / SEGMENTED STYLE (bubble) ── */
        .sbp-segments {
            display: flex;
            gap: 4px;
        }

        .sbp-segment {
            flex: 1;
            height: 15px;
            border-radius: 4px;
            background-color: rgba(0, 119, 204, 0.10);
        }

        .sbp-segment.filled {
            background: linear-gradient(135deg, #ffb347, #ff7a00);
        }

        .sbp-segment.filled.completed {
            background-color: var(--neon-cyan);
            background-image: none;
        }

        .speech-bubble {
            position: absolute;
            /* left: 155px; */
            left: -300px;
            /* left: -315px; */
            /* right: -185px; */
            /* right: -510px; */
            /* top: 2px; */
            /* top: -200px; */
            /* top: -50px; */
            /* width: 148px; */
            /* width: 175px; */
            width: 300px;
            /* background: #fff; */
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
            /* display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column; */
            /* box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18); */
            /* display: none; */
            /* animation: bubblePop 4s ease-in-out infinite; */
        }


        .speech-bubble strong {
            display: block;
            color: var(--neon-cyan);
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 14.5px;
        }

        .speech-bubble p {
            margin: 0;
            font-size: 13.5px;
            color: var(--text-dim);
            line-height: 22px;
        }

        .speech-bubble p .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 14px;
            background: var(--neon-cyan);
            margin-left: 2px;
            vertical-align: middle;
            animation: cursorBlink 0.8s step-end infinite;
        }

        @keyframes cursorBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 50px;
            /* top: 60px; */
            width: 12px;
            height: 12px;
            background: #fff;
            transform: rotate(45deg);
        }

        @keyframes bubblePop {

            0%,
            8% {
                opacity: 0;
                transform: translateY(6px) scale(.92);
            }

            16%,
            84% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            92%,
            100% {
                opacity: 0;
                transform: translateY(6px) scale(.92);
            }
        }

        .splash-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 420px;
            /* padding: 0 24px; */
            animation: splashCardUp .55s cubic-bezier(.2, .8, .2, 1) both .1s;
            margin-left: 15rem;
        }

        /* Bare-minimum reset so this is legible on the cyan bg — style freely */
        .splash-module-meta {
            color: #fff;
            width: 520px;
            /* display: flex;
            flex-direction: column;
            gap: 1rem; */
            margin: -3rem 0 2rem;
            text-align: center;
            display: none;
        }

        .splash-module-position {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 15px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(6px);
            font-size: 13.5px;
            font-weight: 600;
            /* margin: 0 0 10px; */
            /* margin: 0; */
        }

        .splash-module-title {
            font-size: 28px;
            font-family: "Orbitron", sans-serif;
            text-align: center;
            margin: 10px 0 10px;
            line-height: 40px;
            /* margin: 0; */
        }

        .splash-module-desc {
            font-size: 14.5px;
            text-align: center;
        }

        @keyframes splashCardUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .splash-card img {
            /* margin-bottom: 18px; */
            /* animation: splashBotFloat 2.6s ease-in-out infinite; */
        }

        @keyframes splashBotFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .splash-bot-icon i {
            font-size: 30px;
            color: #fff;
        }

        .splash-greet {
            font-size: 21px;
            font-weight: 700;
            color: var(--text-bright);
            margin-bottom: 16px;
        }

        .splash-greet span {
            color: var(--neon-cyan);
        }

        .splash-bubble {
            background: #fff;
            border: 1px solid var(--panel-border);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 8px 24px rgba(0, 50, 100, 0.08);
            margin-bottom: 26px;
        }

        .splash-bubble p {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-dim);
        }

        .splash-btn {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            padding: 7px 26px;
            border-radius: 99px;
            /* box-shadow: 0 10px 24px rgba(0, 119, 204, 0.3); */
            transition: transform .18s ease, box-shadow .18s ease;
            margin: 1rem 0 0;
        }

        .splash-btn.state-continue {
            background: linear-gradient(135deg, #ffb347, #ff7a00);
        }

        .splash-btn.state-review,
        .splash-btn.state-start {
            background-color: var(--neon-cyan);
        }

        .splash-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0, 119, 204, 0.38);
        }

        .splash-btn i {
            font-size: 12px;
            transition: transform .18s ease;
        }

        .splash-btn:hover i {
            transform: translateX(3px);
        }

        /* Hide the shell until the splash is dismissed, then reveal it */
        .lessons-shell {
            opacity: 0;
        }

        .lessons-shell.shell-visible {
            opacity: 1;
            transition: opacity .4s ease;
        }

        .lessons-shell.shell-visible .lessons-sidebar {
            animation: sidebarSlideIn .55s cubic-bezier(.2, .8, .2, 1) both;
        }

        .lessons-shell.shell-visible .lessons-main {
            animation: mainFadeUp .55s cubic-bezier(.2, .8, .2, 1) both .12s;
        }

        @keyframes sidebarSlideIn {
            from {
                opacity: 0;
                transform: translateX(-26px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes mainFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* If JS is disabled, don't leave the page blank */
        .no-js .module-splash {
            display: none;
        }

        .no-js .lessons-shell {
            opacity: 1;
        }

        /* =============================================
           SKIP-SPLASH STATE
           Added so that once a user has dismissed the
           BonBon splash for a given module (tracked via
           sessionStorage), reloading the page or moving
           between lessons (Prev/Next full-page links)
           does NOT show the splash again.
        ============================================= */
        .skip-splash .module-splash {
            display: none !important;
        }

        .skip-splash .lessons-shell {
            opacity: 1 !important;
        }

        /* =============================================
           LIST / LESSON VIEW TOGGLE
           Two states live on #lessonsShell:
             .view-list   -> only the lesson list shows
             .view-lesson -> only the topbar + lesson
                              content shows (list hidden)
           State is remembered per-module in sessionStorage
           so Prev/Next and reloads keep whichever view
           the student was last in.
        ============================================= */
        .lessons-shell.view-list .lessons-main {
            display: none;
        }

        .lessons-shell.view-lesson .lessons-sidebar {
            display: none;
        }

        .topbar-back-list {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted, var(--text-dim));
            padding: 7px 14px;
            border-radius: 8px;
            border: 1.5px solid var(--panel-border);
            background: var(--page-card, #fff);
            transition: border-color .15s ease, color .15s ease, transform .15s ease;
        }

        .topbar-back-list svg {
            width: 12px;
            height: 12px;
        }

        .topbar-back-list:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
            transform: translateX(-2px);
        }

        /* =============================================
           LAYOUT SHELL
           Single full-width column (no side-by-side sidebar).
           The lesson list sits on top, and once a lesson is
           selected, the topbar + that lesson's content simply
           continue underneath it in the same column. The shell
           itself is the one scroll container for the whole page.
        ============================================= */
        .lessons-shell {
            display: block;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Lesson content area (topbar + sections) — stacks directly
           below the lesson list, full width, no independent scroll. */
        .lessons-main {
            display: flex;
            flex-direction: column;
            /* height: 100vh; */
            /* background: var(--page-bg, #f7f9fc); */
            /* border-top: 1px solid var(--panel-border); */
            margin: 0;
            padding: 0;
            position: relative;
        }

        /* NOTE: .robot-page used to be positioned relative to .lessons-main,
           whose height changes with lesson content length — that's why the
           robot/bubble jumped around. It's now anchored to .lesson-hero
           instead (a fixed-height element), see rule further down. */

        .speech-bubble {
            position: absolute;
            /* left: -158px; */
            left: -315px;
            /* right: -185px; */
            /* right: -510px; */
            /* top: 2px; */
            top: 60px;
            /* top: -50px; */
            /* width: 148px; */
            /* width: 175px; */
            width: 300px;
            /* background: #fff; */
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
            /* box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18); */
            /* display: none; */
            /* animation: bubblePop 4s ease-in-out infinite; */
        }

        .speech-bubble strong {
            display: block;
            color: var(--neon-cyan);
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 14.5px;
        }

        .speech-bubble p {
            margin: 0;
            font-size: 13.5px;
            color: var(--text-dim);
            line-height: 22px;
        }

        .speech-bubble p .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 14px;
            background: var(--neon-cyan);
            margin-left: 2px;
            vertical-align: middle;
            animation: cursorBlink 0.8s step-end infinite;
        }

        @keyframes cursorBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 20px;
            /* top: 60px; */
            width: 12px;
            height: 12px;
            background: #fff;
            transform: rotate(-45deg);
        }

        .speech-bubble.bubble-3::after {
            right: -6px;
            top: 42.5px;
            transform: rotate(-45deg);
            /* transform: rotate(100deg); */
        }

        @keyframes bubblePop {

            0%,
            8% {
                opacity: 0;
                transform: translateY(6px) scale(.92);
            }

            16%,
            84% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            92%,
            100% {
                opacity: 0;
                transform: translateY(6px) scale(.92);
            }
        }

        /* =============================================
           LESSON LIST (TOP SECTION)
        ============================================= */
        .lessons-sidebar {
            width: 100%;
            min-height: auto;
            /* background-color: #FFFFFF; */
            border-right: none;
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

        /* =============================================
           MODULE OVERVIEW HERO (full-width top banner)
        ============================================= */
        .ov-hero {
            /* background: linear-gradient(135deg, var(--neon-blue), var(--neon-cyan) 65%, var(--electric-purple)); */
            /* padding: 34px 30px; */
            padding: 34px 30px;
            color: #fff;
            /* position: relative; */
            /* overflow: hidden; */
            background-color: var(--neon-cyan);
            display: flex;
            justify-content: space-between;
            /* align-items: center; */
        }

        .ov-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(120deg, rgba(255, 255, 255, 0.05) 0 2px, transparent 2px 26px);
            pointer-events: none;
        }

        .ov-hero .ov-text {
            width: 650px;
        }

        .ov-hero .ov-image {
            /* width: 150%; */
            position: relative;
        }


        .speech-bubble {
            position: absolute;
            left: -300px;
            /* left: -315px; */
            /* right: -185px; */
            /* right: -510px; */
            /* top: 2px; */
            /* top: 25px; */
            /* top: -50px; */
            /* width: 148px; */
            /* width: 175px; */
            width: 300px;
            /* background: #fff; */
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
            /* box-shadow: 0 8px 18px rgba(0, 0, 0, 0.18); */
            /* display: none; */
            /* animation: bubblePop 4s ease-in-out infinite; */
        }

        .bubble-1 {
            top: -10px;
        }

        .bubble-2 {
            top: 20px;
        }

        .speech-bubble strong {
            display: block;
            color: var(--neon-cyan);
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 14.5px;
        }

        .speech-bubble p {
            margin: 0;
            font-size: 13.5px;
            color: var(--text-dim);
            line-height: 22px;
        }

        .speech-bubble p .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 14px;
            background: var(--neon-cyan);
            margin-left: 2px;
            vertical-align: middle;
            animation: cursorBlink 0.8s step-end infinite;
        }

        @keyframes cursorBlink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 40px;
            /* top: 60px; */
            width: 12px;
            height: 12px;
            background: #fff;
            transform: rotate(45deg);
        }

        .ov-hero-back {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform .15s ease, color .15s ease;
        }

        .ov-hero-back:hover {
            transform: translateX(-3px);
            color: #fff;
        }

        /* .ov-hero-top {} */

        .ov-hero-top .ov-hero-sub {
            margin: 0 0 10px;
        }

        .ov-hero-top .ov-hero-parent {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 15px;
        }

        .ov-hero-top .ov-parent-lesson {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 15px;
        }

        .ov-hero-icon {
            width: 54px;
            height: 54px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .ov-hero-tag {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 5px;
        }

        .ov-hero-title {
            font-size: 28px;
            font-weight: 700;
            font-family: "Orbitron", sans-serif;
            line-height: 1.25;
        }

        .ov-hero-sub {
            position: relative;
            z-index: 1;
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 16px;
            line-height: 25px;
        }

        .ov-hero-track {
            position: relative;
            z-index: 1;
            height: 15px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.22);
            overflow: hidden;
            max-width: 900px;
        }

        .ov-hero-fill {
            height: 100%;
            border-radius: 99px;
            background: #fff;
            transition: width .6s ease;
        }

        /* Blue hero banner reused at the top of the lesson CONTENT view,
           so it's always obvious which lesson/topic you're looking at. */
        .lesson-hero {
            position: relative;
            /* NEW: anchor for .robot-page inside it */
            padding: 34px 30px;
            display: block;
            border-bottom: 1px solid #CADFF5;
        }

        .lesson-hero .lesson-hero-text {
            width: 600px;
        }

        .lesson-hero .ov-hero-back {
            margin-bottom: 14px;
        }

        .lesson-hero .ov-hero-tag {
            margin-bottom: 4px;
        }

        .lesson-hero .ov-hero-title {
            font-size: 28px;
        }

        .lesson-hero .ov-hero-sub {
            margin-bottom: 0;
        }

        /* NEW: .robot-page now lives INSIDE .lesson-hero, so it's positioned
           relative to the hero's fixed-ish height, not the whole
           .lessons-main column (which grows with lesson content length).
           Anchored near the hero's own top padding (not vertically
           centered) so the negative offset on the speech bubble never
           pushes it above the hero and clips against the viewport. */
        .lesson-hero .robot-page {
            position: absolute;
            right: 3%;
            top: 100px;
        }

        .lesson-hero .robot-page .bubble-3 {
            top: -14px;
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
            border-bottom: 1px solid var(--border);
        }

        .sb-progress-block h5 {
            /* color: #FFFFFF; */
            color: var(--text-bright);
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
            height: 15px;
            /* background: rgba(255, 255, 255, 0.07); */
            background: rgba(0, 119, 204, 0.08);
            border-radius: 3px;
            overflow: hidden;
        }

        .sb-bar-fill {
            height: 100%;
            /* background: linear-gradient(90deg, var(--neon-blue), var(--neon-cyan)); */
            background-color: var(--neon-cyan);
            border-radius: 3px;
            box-shadow: 0 0 8px rgba(0, 255, 136, 0.4);
            transition: width .6s ease;
        }

        /* ── Sidebar nav list ── */
        .sb-lesson-list {
            position: relative;
            z-index: 1;
            /* width: 100%; */
            /* margin: 0 auto; */
            margin: 0px 180px;
            /* padding: 28px 20px 60px; */
            padding: 28px 20px 35px;
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

        /* Nav item — card style, animated in on load */
        .sb-nav-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            border-radius: 16px;
            margin-bottom: 18px;
            cursor: pointer;
            background: var(--panel-bg);
            border: 1px solid var(--panel-border);
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background .18s ease;
            position: relative;
            text-decoration: none;
            color: inherit;
            opacity: 0;
            transform: translateX(-18px);
            animation: sbItemIn .5s cubic-bezier(.2, .8, .2, 1) forwards;
        }

        @keyframes sbItemIn {
            from {
                opacity: 0;
                transform: translateX(-18px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sb-nav-item:hover {
            transform: translateX(3px) translateY(-1px);
            border-color: rgba(0, 119, 204, 0.35);
            box-shadow: 0 8px 20px rgba(0, 90, 180, 0.12);
            background: #ffffff;
        }

        .sb-nav-item.active {
            background: linear-gradient(135deg, rgba(0, 119, 204, 0.09), rgba(85, 51, 204, 0.05));
            border-color: var(--neon-cyan);
            box-shadow: 0 6px 18px rgba(0, 119, 204, 0.16);
        }

        .sb-nav-item.sb-nav-done .sb-nav-title {
            /* color: rgba(200, 220, 255, 0.75); */
            color: var(--text-bright);
        }

        /* Chevron affordance on the right, like a clickable list card */
        .sb-nav-chevron {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            opacity: .45;
            transition: transform .18s ease, opacity .18s ease, color .18s ease;
        }

        .sb-nav-chevron svg {
            width: 20px;
            height: 20px;
        }

        .sb-nav-item:hover .sb-nav-chevron {
            opacity: 1;
            transform: translateX(3px);
            color: var(--neon-cyan);
        }

        .sb-nav-item.active .sb-nav-chevron {
            opacity: 1;
            color: var(--neon-cyan);
        }

        /* "Done" pill badge, shown next to the lesson title like the reference list */
        .sb-nav-done-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #0a8f4f;
            background: rgba(0, 168, 89, 0.12);
            border: 1px solid rgba(0, 168, 89, 0.28);
            padding: 1px 7px;
            border-radius: 99px;
            margin-left: 7px;
            vertical-align: middle;
        }

        /* Icon box */
        .sb-nav-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sb-nav-icon svg {
            width: 16px;
            height: 16px;
        }

        .sb-nav-icon .fa {
            font-size: 16px;
        }

        .icon-type-lesson {
            /* background: rgba(0, 137, 74, 0.14); */
            background: rgba(0, 119, 204, 0.08);
            color: var(--neon-cyan);
        }

        .icon-type-lesson.done {
            /* background: rgba(0, 137, 74, 0.22); */
            background-color: var(--neon-cyan);
            color: var(--deep-navy);
        }

        .icon-type-lesson.active-dot {
            /* background: rgba(0, 255, 136, 0.15);
            color: var(--green-neon); */
            background-color: var(--neon-cyan);
            /* background: rgba(0, 119, 204, 0.08); */
            color: var(--deep-navy);
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
            font-size: 15.5px;
            font-weight: 600;
            color: var(--text-dim);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .sb-nav-item.active .sb-nav-title {
            color: var(--text-bright);
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

        /* Bottom nav bar */

        .lesson-bottom-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid var(--panel-border);
        }

        .lesson-bottom-nav .topbar-nav-btn {
            padding: 10px 22px;
        }

        /* =============================================
           TOP NAV BAR
        ============================================= */
        .lessons-topbar {
            background: var(--page-card, #fff);
            border-bottom: 1px solid var(--page-border, var(--panel-border));
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 5;
            position: sticky;
            bottom: 0;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
            /* flex-shrink: 0; */
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
            color: var(--text-muted, var(--text-dim));
            border-radius: 8px;
            transition: background .15s, transform .15s;
        }

        .back-btn .fa {
            font-size: 10px;
        }

        .back-btn:hover {
            transform: translateX(-2px);
        }

        .back-btn svg {
            width: 13px;
            height: 13px;
        }

        .topbar-breadcrumb {
            display: none;
            align-items: center;
            gap: 7px;
            font-size: 12.5px;
            color: var(--page-muted, var(--text-dim));
        }

        .topbar-breadcrumb .sep {
            opacity: 0.4;
        }

        .topbar-breadcrumb .current {
            color: var(--page-text, var(--text-bright));
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
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
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
        }

        .bell-wrap svg {
            width: 16px;
            height: 16px;
            color: var(--page-muted, var(--text-dim));
        }

        .bell-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red-led, var(--danger-red));
            box-shadow: 0 0 5px var(--red-led, var(--danger-red));
        }

        .top-avatar {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--green-mid, var(--neon-cyan)), var(--blue-mid, var(--neon-blue)));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12.5px;
            color: var(--bg-darkest, #fff);
        }

        .topbar-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 9px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .18s, box-shadow .18s;
            text-decoration: none;
        }

        .btn-prev-top {
            background: var(--page-surface, #fff);
            border: 1.5px solid var(--panel-border);
            color: var(--text-dim);
        }

        .btn-prev-top:hover {
            background: var(--page-border, var(--panel-border));
        }

        .btn-next-top {
            background-color: var(--neon-cyan);
            border: none;
            color: #fff;
        }

        .btn-next-top:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 137, 74, 0.3);
        }

        .btn-completed-top {
            background: var(--page-surface, #fff);
            border: 1.5px solid rgba(0, 137, 74, 0.3);
            color: var(--green-dark, #0a8f4f);
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
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .module-hero-banner {
            height: 8px;
            background: linear-gradient(90deg, var(--green-dark, var(--neon-blue)), var(--green-neon, var(--neon-cyan)), var(--blue-elec, var(--electric-purple)));
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
            color: var(--green-dark, var(--neon-cyan));
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
            color: var(--page-text, var(--text-bright));
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .module-hero-desc {
            font-size: 13.5px;
            color: var(--page-muted, var(--text-dim));
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
            color: var(--page-muted, var(--text-dim));
            background: var(--page-surface, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            border-radius: 99px;
            padding: 6px 16px;
        }

        .hero-stat svg {
            width: 13px;
            height: 13px;
            color: var(--green-dark, var(--neon-cyan));
        }

        .hero-stat-num {
            font-weight: 700;
            color: var(--page-text, var(--text-bright));
        }

        /* =============================================
           CONTENT WRAPPER
        ============================================= */
        .lessons-content-wrap {
            padding: 55px 28px 35px;
            margin: 0 180px;

        }

        .lesson-title-row {
            display: flex;
            flex-direction: column;
            align-items: start;
            gap: 15px;
            margin-bottom: 15px;
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
            background: rgba(0, 119, 204, 0.08);
            border: 1px solid var(--panel-border);
            /* border: 1px solid #CADFF5; */
            color: var(--neon-cyan);
            /* color: var(--deep-navy); */
            white-space: nowrap;
        }

        .lesson-main-title {
            font-size: 23px;
            font-weight: 700;
            color: var(--page-text, var(--text-bright));
            line-height: 1.3;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .lesson-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 4px 12px;
            border-radius: 99px;
        }

        .lesson-status-pill.is-done {
            color: #0a8f4f;
            background: rgba(0, 168, 89, 0.12);
            border: 1px solid rgba(0, 168, 89, 0.28);
        }

        .lesson-status-pill.is-pending {
            color: var(--accent-gold);
            background: rgba(204, 119, 0, 0.1);
            border: 1px solid rgba(204, 119, 0, 0.25);
        }

        /* =============================================
           SECTIONS
        ============================================= */
        .ls-section {
            margin-bottom: 28px;
        }

        .ls-section h4 {
            font-size: 18px;
            font-weight: 600;
            /* margin: 15px 0 8px; */
        }

        .ls-section-head {
            display: flex;
            align-items: center;
            gap: 9px;
            /* margin-bottom: 16px; */
            margin-bottom: 25px;
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
            color: var(--green-dark, var(--neon-cyan));
        }

        .icon-video {
            background: rgba(255, 107, 0, 0.12);
            color: var(--orange-warn, #ff6b00);
        }

        .icon-image {
            background: rgba(255, 215, 0, 0.12);
            color: #996600;
        }

        .icon-flash {
            background: rgba(0, 153, 204, 0.12);
            color: var(--blue-mid, var(--neon-cyan));
        }

        .icon-activity {
            background: rgba(155, 93, 229, 0.12);
            color: var(--purple-chip, var(--electric-purple));
        }

        .icon-quiz {
            background: rgba(0, 207, 255, 0.12);
            color: var(--blue-mid, var(--neon-cyan));
        }

        .ls-section-title {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--page-text, var(--text-bright));
        }

        .ls-section-divider {
            flex: 1;
            height: 1px;
            background: var(--border, var(--panel-border));
        }

        .ls-section-count {
            font-size: 10px;
            color: var(--page-muted, var(--text-dim));
            background: var(--page-surface, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            padding: 2px 8px;
            border-radius: 99px;
        }

        /* Lesson text */
        .lesson-text-card {
            font-size: 14.5px;
            line-height: 1.85;
            color: var(--page-text, var(--text-bright));
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            max-width: 100%;
            margin: 0 0 20px;
        }

        /* Video */
        .video-card {
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
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
            background: linear-gradient(90deg, var(--orange-warn, #ff6b00), #FFAA44);
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
            color: var(--orange-warn, #ff6b00);
            flex-shrink: 0;
        }

        .video-type-icon svg {
            width: 14px;
            height: 14px;
        }

        .video-card-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--page-text, var(--text-bright));
        }

        /* Images */
        .img-grid {
            display: grid;
            gap: 14px;
            margin-top: 1.5rem;
        }

        .img-item {
            width: 100%;
            aspect-ratio: 16 / 7;
            border-radius: 12px;
            overflow: hidden;
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, border-color .2s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
            margin: 0 0 25px;
        }

        .img-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.10);
            border-color: var(--page-border2, var(--panel-border));
        }

        .img-item img {
            width: 100%;
            /* height: 385px; */
            height: 100%;
            display: block;
        }

        .img-item-cap {
            padding: 10px 12px;
            font-size: 12.5px;
            color: var(--page-muted, var(--text-dim));
            border-top: 1px solid var(--page-border, var(--panel-border));
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
            color: var(--blue-mid, var(--neon-cyan));
        }

        .callout.warning .callout-icon {
            background: rgba(255, 107, 0, 0.15);
            color: var(--orange-warn, #ff6b00);
        }

        .callout.success .callout-icon {
            background: rgba(0, 137, 74, 0.15);
            color: var(--green-dark, var(--neon-cyan));
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
            color: var(--blue-mid, var(--neon-cyan));
        }

        .callout.warning .cb-title {
            color: var(--orange-warn, #ff6b00);
        }

        .callout.success .cb-title {
            color: var(--green-dark, var(--neon-cyan));
        }

        .callout-body p {
            font-size: 13px;
            color: var(--page-muted, var(--text-dim));
            line-height: 1.6;
        }

        /* Activity */
        .activity-block {
            margin-bottom: 24px;
        }

        .activity-hero-card {
            background: linear-gradient(135deg, #5B2E94, var(--purple-chip, var(--electric-purple)));
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
            color: var(--green-dark, var(--neon-cyan));
            border: 1px solid rgba(0, 137, 74, 0.22);
        }

        .pill-orange {
            background: rgba(255, 107, 0, 0.10);
            color: var(--orange-warn, #ff6b00);
            border: 1px solid rgba(255, 107, 0, 0.22);
        }

        .pill-purple {
            background: rgba(155, 93, 229, 0.10);
            color: var(--purple-chip, var(--electric-purple));
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
            color: var(--green-dark, var(--neon-cyan));
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
            color: var(--green-dark, var(--neon-cyan));
        }

        .submitted-notice-text .sn-sub {
            font-size: 12px;
            color: var(--page-muted, var(--text-dim));
        }

        .question-card {
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
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
            color: var(--purple-chip, var(--electric-purple));
            margin-bottom: 7px;
        }

        .q-text {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--page-text, var(--text-bright));
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .mc-choice {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border, var(--panel-border));
            cursor: pointer;
            font-size: 13.5px;
            color: var(--page-text, var(--text-bright));
            margin-bottom: 8px;
            transition: border-color .18s, background .18s;
            user-select: none;
        }

        .mc-choice:hover {
            border-color: var(--purple-chip, var(--electric-purple));
            background: rgba(155, 93, 229, 0.05);
        }

        .mc-choice.selected {
            border-color: var(--purple-chip, var(--electric-purple));
            background: rgba(155, 93, 229, 0.08);
            color: #4c1d95;
        }

        .mc-letter {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: var(--page-surface, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11.5px;
            color: var(--page-muted, var(--text-dim));
            flex-shrink: 0;
            transition: background .18s, color .18s;
        }

        .mc-choice.selected .mc-letter {
            background: var(--purple-chip, var(--electric-purple));
            color: #fff;
            border-color: var(--purple-chip, var(--electric-purple));
        }

        .review-choice {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border, var(--panel-border));
            font-size: 13.5px;
            color: var(--page-text, var(--text-bright));
            margin-bottom: 8px;
            pointer-events: none;
        }

        .activity-answer {
            width: 100%;
            background: var(--page-card, #fff);
            border: 1.5px solid var(--page-border, var(--panel-border));
            border-radius: 9px;
            padding: 12px 14px;
            font-size: 14px;
            resize: vertical;
            min-height: 90px;
            color: var(--page-text, var(--text-bright));
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            box-sizing: border-box;
        }

        .activity-answer:focus {
            border-color: var(--purple-chip, var(--electric-purple));
            box-shadow: 0 0 0 3px rgba(155, 93, 229, 0.12);
        }

        /* Quiz */
        .quiz-hero-card {
            background: linear-gradient(135deg, var(--blue-dark, var(--neon-blue)), var(--blue-mid, var(--neon-cyan)));
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
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
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
            color: var(--blue-mid, var(--neon-cyan));
            margin-bottom: 7px;
        }

        .q-question {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--page-text, var(--text-bright));
            line-height: 1.5;
            margin-bottom: 14px;
        }

        .q-choice {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 14px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border, var(--panel-border));
            cursor: pointer;
            font-size: 13.5px;
            color: var(--page-text, var(--text-bright));
            margin-bottom: 8px;
            transition: border-color .18s, background .18s;
            user-select: none;
        }

        .q-choice:hover {
            border-color: var(--blue-mid, var(--neon-cyan));
            background: rgba(0, 153, 204, 0.05);
        }

        .q-choice.selected {
            border-color: var(--blue-mid, var(--neon-cyan));
            background: rgba(0, 153, 204, 0.08);
        }

        .q-choice.selected .choice-letter {
            background: var(--blue-mid, var(--neon-cyan));
            color: #fff;
            border-color: var(--blue-mid, var(--neon-cyan));
        }

        .choice-letter {
            width: 26px;
            height: 26px;
            border-radius: 7px;
            background: var(--page-surface, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11.5px;
            color: var(--page-muted, var(--text-dim));
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
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            border-radius: 12px;
            padding: 14px 18px;
        }

        .quiz-status {
            font-size: 12.5px;
            color: var(--page-muted, var(--text-dim));
        }

        .quiz-nav-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-qnav-prev {
            padding: 9px 18px;
            border-radius: 9px;
            border: 1.5px solid var(--page-border, var(--panel-border));
            background: var(--page-card, #fff);
            color: var(--page-text, var(--text-bright));
            font-size: 13px;
            font-weight: 600;
            transition: border-color .18s;
        }

        .btn-qnav-prev:hover {
            border-color: var(--blue-mid, var(--neon-cyan));
            color: var(--blue-mid, var(--neon-cyan));
        }

        .btn-qnav-next,
        .btn-submit-quiz {
            padding: 9px 20px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--blue-dark, var(--neon-blue)), var(--blue-mid, var(--neon-cyan)));
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
            color: var(--page-muted, var(--text-dim));
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
            color: var(--orange-warn, #ff6b00);
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
            color: var(--page-muted, var(--text-dim));
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
            .ov-hero {
                padding: 22px 20px 26px;
            }

            .sb-lesson-list {
                padding: 20px 16px 50px;
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

            .splash-card {
                max-width: 320px;
            }
        }
    </style>
</head>

<body>

    <!-- =============================================
         SKIP-SPLASH CHECK
         Runs immediately on every load. If this module's
         splash has already been dismissed this session
         (see sessionStorage flag set below), add the
         skip-splash class to <html> BEFORE the splash
         markup paints, so the robot screen never flashes
         on Prev/Next navigation or on page reload.
    ============================================= -->
    <script>
        (function () {
            var moduleId = <?= (int) ($moduleId ?? 0) ?>;
            var storageKey = 'splash_dismissed_module_' + moduleId;
            var dismissed = sessionStorage.getItem(storageKey);

            var navType = 'navigate';
            try {
                var navEntries = performance.getEntriesByType('navigation');
                if (navEntries && navEntries.length) {
                    navType = navEntries[0].type;
                } else if (performance.navigation) {
                    navType = performance.navigation.type === 1 ? 'reload' : 'navigate';
                }
            } catch (e) { /* ignore, default to 'navigate' */ }

            var cameFromWithinLessons = document.referrer.indexOf('url=subject_lessons') !== -1;

            var shouldSkip = dismissed && (navType === 'reload' || navType === 'back_forward' || cameFromWithinLessons);

            if (shouldSkip) {
                document.documentElement.classList.add('skip-splash');
            } else {
                sessionStorage.removeItem(storageKey);
            }
        })();
    </script>

    <?php
    $progressPct = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

    $sbTotalSegments = 5;
    $sbFilledSegments = (int) round(($progressPct / 100) * $sbTotalSegments);
    $sbIsFinished = ($progressPct >= 100);

    if ($progressPct <= 0) {
        $moduleState = 'start';
        $splashBtnLabel = 'Start Now';
        $splashMsg = "You haven't started this module yet. Let's dive in!";
    } elseif ($progressPct >= 100) {
        $moduleState = 'review';
        $splashBtnLabel = 'Module Review';
        $splashMsg = "You've completed this module. Feel free to review any lesson, anytime.";
    } else {
        $moduleState = 'continue';
        $splashBtnLabel = 'Continue Learning';
        $splashMsg = "You're {$progressPct}% through this module — keep up the the great progress.";
    }

    $splashFirstName = 'Student';
    if (!empty($studentName)) {
        $nameParts = explode(' ', trim($studentName));
        $splashFirstName = $nameParts[0];
    }

    // =============================================
    // BONBON — DYNAMIC LESSON MESSAGE (bubble-3)
    // ------------------------------------------------------------
    // Instead of a hardcoded string, build BonBon's message for
    // the lesson-page speech bubble (#bonbonMessage3) from the
    // current lesson's title/topic and completion status. Falls
    // back to a generic greeting when there is no active lesson
    // (e.g. the empty-state "choose a lesson" view).
    // =============================================
    $bonbonLessonMessage = "Welcome! I'm your learning assistant, ready to guide you through your journey!";

    if (!empty($lesson)) {
        $bonbonCleanTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $lesson['title']);
        $bonbonIsDone = $lessonCompletion[$lessonId] ?? false;

        if ($bonbonIsDone) {
            $bonbonLessonMessage = "Nice work — you've already completed \"{$bonbonCleanTitle}\"! Feel free to review it anytime.";
        } elseif (!empty($lesson['topic'])) {
            $bonbonLessonMessage = "Let's dive into \"{$bonbonCleanTitle}\" — today we're covering {$lesson['topic']}!";
        } else {
            $bonbonLessonMessage = "Let's dive into \"{$bonbonCleanTitle}\"! I'll be right here if you need a hand.";
        }
    }
    ?>

    <div class="module-splash" id="moduleSplash">
        <div class="splash-module-meta">
            <span class="splash-module-position">
                <i class="fa fa-book-open"></i>

                Module
                <?= $modulePosition ?> of
                <?= $moduleTotal ?>
            </span>
            <h2 class="splash-module-title">
                <?= htmlspecialchars($module['title'] ?? '') ?>
            </h2>
            <?php if (!empty($module['description'])): ?>
                <p class="splash-module-desc">
                    <?= nl2br(htmlspecialchars($module['description'])) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="splash-card">

            <div class="splash-bot-icon">
                <img src="../images/robot-lesson1.png" alt="">
            </div>
            <div class="speech-bubble bubble-1">
                <strong>BonBon</strong>
                <p id="bonbonMessage"></p>
            </div>
        </div>
    </div>

    <div class="lessons-shell" id="lessonsShell">

        <?php include("../components/offcanvas.php"); ?>

        <aside class="lessons-sidebar">

            <div class="ov-hero">
                <div class="ov-text">
                    <a class="ov-hero-back"
                        href="/learning_management/public/?url=modules&subject=<?= urlencode($subject) ?>">
                        <i class="fa fa-arrow-left"></i>
                        Back to Modules
                    </a>
                    <div class="ov-hero-top">
                        <div class="ov-hero-parent">
                            <div class="ov-hero-icon">
                                <i class="fa fa-book-open"></i>
                            </div>
                            <div>
                                <div class="ov-hero-tag">
                                    <?= htmlspecialchars($module['subject_name'] ?? $subject) ?>
                                </div>
                                <div class="ov-hero-title">
                                    <?= htmlspecialchars($module['title'] ?? '') ?>
                                </div>
                            </div>
                        </div>
                        <!-- <?php if (!empty($module['description'])): ?>
                            <div class="ov-hero-sub">
                                <?= htmlspecialchars($module['description']) ?>
                            </div>
                        <?php endif; ?> -->
                    </div>
                    <div class="ov-hero-sub">
                        <?= $completedCount ?> of
                        <?= $totalLessons ?> lessons completed
                    </div>
                    <div class="ov-hero-track">
                        <div class="ov-hero-fill" style="width:<?= $progressPct ?>%"></div>
                    </div>
                </div>
                <div class="ov-image">
                    <div class="speech-bubble bubble-2">
                        <strong>BonBon</strong>

                        <p id="bonbonMessage2"></p>

                    </div>
                    <img src="../images/robot-ai9.png" alt="">
                </div>
            </div>

            <div class="sb-lesson-list">

                <div class="sb-nav-group-label">Lessons</div>
                <?php foreach ($lessons as $i => $l):
                    $isActive = ($l['id'] == $lessonId);
                    $isDone = $lessonCompletion[$l['id']] ?? false;
                    $rawTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $l['title']);
                    $dotClass = $isActive ? 'active-dot' : ($isDone ? 'done' : 'pending');
                    $itemClass = $isActive ? 'active' : '';
                    ?>
                    <a class="sb-nav-item <?= $itemClass ?><?= $isDone ? ' sb-nav-done' : '' ?>"
                        href="<?= lUrl($subject, $moduleId, $l['id']) ?>" style="animation-delay: <?= $i * 0.06 ?>s;">
                        <div class="sb-nav-icon icon-type-lesson <?= $dotClass ?>">
                            <?php if ($isDone): ?>
                                <i class="fa fa-check"></i>
                            <?php else: ?>
                                <i class="fa fa-book-open"></i>
                            <?php endif; ?>
                        </div>
                        <div class="sb-nav-info">
                            <div class="sb-nav-title">
                                Lesson <?= $i + 1 ?>: <?= htmlspecialchars($rawTitle) ?>
                                <?php if ($isDone): ?>
                                    <span class="sb-nav-done-badge"><i class="fa fa-check"></i> Done</span>
                                <?php endif; ?>
                            </div>
                            <div class="sb-nav-meta"><?= $isDone ? 'Completed' : 'Not started' ?></div>
                        </div>
                        <span class="sb-nav-chevron">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path d="M9 6l6 6-6 6" />
                            </svg>
                        </span>
                    </a>
                <?php endforeach; ?>



            </div><!-- /sb-lesson-list -->

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

        <div class="lessons-main">

            <?php if ($lesson):
                $heroCleanTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $lesson['title']);
                ?>
                <div class="ov-hero lesson-hero">
                    <div class="lesson-hero-text">
                        <button type="button" class="ov-hero-back js-back-to-list">
                            <i class="fa fa-arrow-left"></i>
                            Lessons
                        </button>
                        <div class="ov-hero-top">
                            <div class="ov-parent-lesson">
                                <div class="ov-hero-icon">
                                    <i class="fa fa-book-open"></i>
                                </div>
                                <div>
                                    <div class="ov-hero-tag">Lesson
                                        <?= $currentIndex ?> ·
                                        <?= htmlspecialchars($module['title'] ?? '') ?>
                                    </div>
                                    <div class="ov-hero-title">
                                        <?= htmlspecialchars($heroCleanTitle) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($module['description'])): ?>
                            <div class="ov-hero-sub">
                                <?= htmlspecialchars($module['description']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- MOVED: .robot-page now lives inside .lesson-hero so its
                         position is anchored to this fixed-height banner,
                         not to .lessons-main (whose height changes with
                         lesson content length, which caused the jumping). -->
                    <div class="robot-page">
                        <div class="speech-bubble bubble-3">
                            <strong>BonBon</strong>
                            <p id="bonbonMessage3"></p>
                        </div>
                        <img src="../images/robot-ai10.png" alt="">
                    </div>
                </div>
            <?php endif; ?>

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
                    $isCurrentLessonDone = $lessonCompletion[$lessonId] ?? false;
                    ?>

                    <div class="lesson-title-row">
                        <span class="lesson-num-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="11"
                                height="11">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Lesson <?= $currentIndex ?>
                        </span>
                        <h3 class="lesson-main-title" id="lesson-title">
                            <?= htmlspecialchars($cleanTitle) ?>
                        </h3>
                    </div>

                    <?php if (!empty($contentBlocks)): ?>
                        <div class="ls-section" id="section-content">
                            <?php if (!empty($lesson['topic'])): ?>
                                <h4><?= htmlspecialchars($lesson['topic']) ?></h4>
                            <?php endif; ?>

                            <?php foreach ($contentBlocks as $block): ?>

                                <?php if ($block['type'] === 'text' && trim($block['body'] ?? '') !== ''): ?>
                                    <div class="lesson-text-card">
                                        <?php if (!empty($block['title'])): ?>
                                            <h4 class="lesson-text-block-title" style="margin-bottom:8px;">
                                                <?= htmlspecialchars($block['title']) ?>
                                            </h4>
                                        <?php endif; ?>
                                        <?= nl2br(htmlspecialchars($block['body'])) ?>
                                    </div>

                                    <?php if (!empty($block['key_idea'])): ?>
                                        <div class="callout info d-flex align-items-center">
                                            <div class="callout-icon">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="M12 16v-4M12 8h.01" />
                                                </svg>
                                            </div>
                                            <div class="callout-body">
                                                <p class="m-0"><strong>Key idea:</strong> <?= nl2br(htmlspecialchars($block['key_idea'])) ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                <?php elseif ($block['type'] === 'image'): ?>
                                    <div class="img-item" onclick="dbLightbox('<?= htmlspecialchars($block['file_path']) ?>')">
                                        <img src="<?= htmlspecialchars($block['file_path']) ?>"
                                            alt="<?= htmlspecialchars($block['title'] ?? '') ?>" loading="lazy">
                                        <?php if (!empty($block['title'])): ?>
                                            <div class="img-item-cap"><?= htmlspecialchars($block['title']) ?></div>
                                        <?php endif; ?>
                                    </div>

                                <?php elseif ($block['type'] === 'video'): ?>
                                    <div class="video-card" style="margin-bottom:16px;">
                                        <div class="video-card-banner"></div>
                                        <iframe src="<?= htmlspecialchars(youtubeEmbed($block['file_path'])) ?>" allowfullscreen
                                            loading="lazy"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                        </iframe>
                                        <?php if (!empty($block['title'])): ?>
                                            <div class="video-card-info">
                                                <div class="video-type-icon">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M23 7l-7 5 7 5V7z" />
                                                        <rect x="1" y="5" width="15" height="14" rx="2" />
                                                    </svg>
                                                </div>
                                                <span class="video-card-title"><?= htmlspecialchars($block['title']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

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

                <?php if ($lesson): ?>
                    <div class="lesson-bottom-nav">
                        <?php if ($prevLessonId): ?>
                            <a class="topbar-nav-btn btn-prev-top" href="<?= lUrl($subject, $moduleId, $prevLessonId) ?>"
                                id="prevBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20"
                                    height="20">
                                    <path d="M19 12H5M11 6l-6 6 6 6" />
                                </svg>
                                Prev
                            </a>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>

                        <?php if ($nextLessonId): ?>
                            <a class="topbar-nav-btn btn-next-top" href="<?= lUrl($subject, $moduleId, $nextLessonId) ?>"
                                id="nextBtn">
                                Next
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20"
                                    height="20">
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
                    </div>
                <?php endif; ?>

            </div><!-- /lessons-content-wrap -->


        </div><!-- /lessons-content-wrap -->

    </div><!-- /lessons-main -->
    </div><!-- /lessons-shell -->

    <div class="db-lightbox" id="dbLightbox" onclick="dbLightboxClose()">
        <button class="db-lightbox-close" onclick="dbLightboxClose()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
                <path d="M18 6L6 18M6 6l12 12" />
            </svg>
        </button>
        <img id="dbLightboxImg" src="" alt="">
    </div>

    <noscript>
        <style>
            .module-splash {
                display: none;
            }

            .lessons-shell {
                opacity: 1;
            }
        </style>
    </noscript>

    <script>
        function startBonbonBubble3Typewriter() {
            const el = document.getElementById('bonbonMessage3');
            if (!el || el.dataset.typed) return;
            el.dataset.typed = '1';

            const message = <?= json_encode($bonbonLessonMessage) ?>;
            const speed = 28;
            let i = 0;

            const cursor = document.createElement('span');
            cursor.className = 'typing-cursor';
            el.appendChild(cursor);

            function type() {
                if (i < message.length) {
                    cursor.insertAdjacentText('beforebegin', message.charAt(i));
                    i++;
                    setTimeout(type, speed);
                } else {
                    setTimeout(() => cursor.remove(), 1200);
                }
            }
            type();
        }

        // Run every load, regardless of splash state
        document.addEventListener('DOMContentLoaded', startBonbonBubble3Typewriter);
    </script>

    <script>
        function startBonbonBubble2Typewriter() {
            const el = document.getElementById('bonbonMessage2');
            if (!el || el.dataset.typed) return;
            el.dataset.typed = '1';

            const message = "Ready to learn something new? Every lesson is an opportunity to build your knowledge and improve your skills. Stay curious, complete the activities and quizzes.";
            const speed = 28;
            let i = 0;

            const cursor = document.createElement('span');
            cursor.className = 'typing-cursor';
            el.appendChild(cursor);

            function type() {
                if (i < message.length) {
                    cursor.insertAdjacentText('beforebegin', message.charAt(i));
                    i++;
                    setTimeout(type, speed);
                } else {
                    setTimeout(() => cursor.remove(), 1200);
                }
            }
            type();
        }

        document.addEventListener('DOMContentLoaded', startBonbonBubble2Typewriter);
    </script>

    <script>
        (function () {

            const el = document.getElementById("bonbonMessage");

            const message = <?= json_encode("Welcome back, $splashFirstName! $splashMsg") ?>;

            let i = 0;
            const speed = 30;

            function type() {

                if (i < message.length) {

                    el.textContent += message.charAt(i);
                    i++;

                    setTimeout(type, speed);

                } else {

                    el.insertAdjacentHTML("afterend", `
                    <div class="speech-bubble-progress">
                        <div class="sb-progress-row">
                            <span class="sbp-label">Progress</span>
                            <span class="sbp-pct" style="color:<?= $moduleState !== 'continue' ? 'var(--neon-cyan)' : '#ff7a00' ?>;"><?= $progressPct ?>%</span>
                        </div>
                        <div class="sbp-segments">
                            <?php for ($i = 1; $i <= $sbTotalSegments; $i++): ?>
                                <div class="sbp-segment<?= $i <= $sbFilledSegments ? ' filled' . ($sbIsFinished ? ' completed' : '') : '' ?>"></div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <button type="button"
                            class="splash-btn state-<?= htmlspecialchars($moduleState) ?>"
                            id="splashContinueBtn"
                            data-state="<?= htmlspecialchars($moduleState) ?>">
                        <span><?= htmlspecialchars($splashBtnLabel) ?></span>
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    `);

                    requestAnimationFrame(function () {
                        var fill = document.getElementById('sbProgressFill');
                        if (!fill) return;
                        requestAnimationFrame(function () {
                            fill.style.width = "<?= $progressPct ?>%";
                        });
                    });

                }

            }

            if (!document.documentElement.classList.contains('skip-splash')) {
                type();
            }

        })();
    </script>

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

    <script>
        (function () {
            var splash = document.getElementById('moduleSplash');
            var shell = document.getElementById('lessonsShell');

            if (!splash || !shell) return;

            splash.addEventListener('click', function (e) {
                var btn = e.target.closest('#splashContinueBtn');
                if (!btn) return;

                sessionStorage.setItem('splash_dismissed_module_<?= (int) $moduleId ?>', '1');

                splash.classList.add('splash-exit');
                setTimeout(function () {
                    splash.style.display = 'none';
                    shell.classList.add('shell-visible');
                    startBonbonBubble2Typewriter();
                    startBonbonBubble3Typewriter();
                }, 460);
            });
        })();
    </script>

    <script>
        (function () {
            var moduleId = <?= (int) ($moduleId ?? 0) ?>;
            var viewKey = 'lessons_view_module_' + moduleId;
            var shell = document.getElementById('lessonsShell');
            var backBtns = document.querySelectorAll('.js-back-to-list');

            if (!shell) return;

            function applyView(view) {
                shell.classList.remove('view-list', 'view-lesson');
                shell.classList.add(view === 'lesson' ? 'view-lesson' : 'view-list');
                if (view === 'lesson') {
                    startBonbonBubble3Typewriter();   // ✅ add this
                }
            }

            var savedView = sessionStorage.getItem(viewKey) || 'list';
            applyView(savedView);

            document.querySelectorAll('.sb-nav-item').forEach(function (item) {
                item.addEventListener('click', function (e) {
                    sessionStorage.setItem(viewKey, 'lesson');
                    if (item.classList.contains('active')) {
                        e.preventDefault();
                        applyView('lesson');
                    }
                });
            });

            ['prevBtn', 'nextBtn'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el && el.tagName === 'A') {
                    el.addEventListener('click', function () {
                        sessionStorage.setItem(viewKey, 'lesson');
                    });
                }
            });

            backBtns.forEach(function (backBtn) {
                backBtn.addEventListener('click', function () {
                    sessionStorage.setItem(viewKey, 'list');
                    applyView('list');
                });
            });
        })();
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

    <script src="../js_folder/lessons.js?v=<?= time() ?>"></script>
    <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>


</html>