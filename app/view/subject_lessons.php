<?php

function lUrl($subject, $moduleId, $lessonId)
{
    return "/learning_management/public/?url=subject_lessons&subject="
        . urlencode($subject) . "&id={$moduleId}&lesson={$lessonId}";
}

/**
 * Convert a YouTube URL (watch, youtu.be, embed, shorts, etc.)
 * into a safe embeddable URL. Returns null if no valid video ID
 * can be extracted, so callers can skip rendering the video block
 * instead of fataling.
 */
function youtubeEmbed($url)
{
    if (empty($url) || !is_string($url)) {
        return null;
    }

    $videoId = null;

    // Covers: watch?v=ID, youtu.be/ID, embed/ID, v/ID, shorts/ID
    if (
        preg_match(
            '~(?:youtube\.com/(?:watch\?v=|embed/|v/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~',
            $url,
            $matches
        )
    ) {
        $videoId = $matches[1];
    }

    if (!$videoId) {
        return null;
    }

    return 'https://www.youtube.com/embed/' . $videoId;
}

function ddIconForLabel($label)
{
    $l = strtolower($label);
    if (strpos($l, 'cpu') !== false || strpos($l, 'processor') !== false)
        return 'fa-microchip';
    if (strpos($l, 'ram') !== false || strpos($l, 'memory') !== false)
        return 'fa-memory';
    if (strpos($l, 'gpu') !== false || strpos($l, 'graphics') !== false)
        return 'fa-tv';
    if (strpos($l, 'psu') !== false || strpos($l, 'power') !== false)
        return 'fa-bolt';
    if (strpos($l, 'hdd') !== false || strpos($l, 'hard drive') !== false)
        return 'fa-hdd';
    if (strpos($l, 'ssd') !== false)
        return 'fa-save';
    if (strpos($l, 'motherboard') !== false)
        return 'fa-sitemap';
    if (strpos($l, 'cooler') !== false || strpos($l, 'fan') !== false)
        return 'fa-snowflake';
    return 'fa-cube';
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
        :root {
            --neon-cyan: #0077cc;
            --neon-light: #33e6ff;
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
            --panel-edge: #1c2a63;
            --panel-bg: #f5f7fb;
            --hero-height: 100px;
            --bg-deep: #0a0e27;
            --bg-mid: #151b3d;
            --arcade-cyan: #33e6ff;
            --arcade-pink: #ff2e97;
            --panel: #0f1a45;
            --text-light: #eaf3ff;
            --neon-green: #39ff9e;
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
            background-image: radial-gradient(circle at 15% 0%, rgba(0, 119, 204, 0.06), transparent 45%), radial-gradient(circle at 100% 30%, rgba(85, 51, 204, 0.06), transparent 40%), repeating-linear-gradient(0deg, rgba(0, 100, 200, 0.035) 0px, rgba(0, 100, 200, 0.035) 1px, transparent 1px, transparent 42px), repeating-linear-gradient(90deg, rgba(0, 100, 200, 0.035) 0px, rgba(0, 100, 200, 0.035) 1px, transparent 1px, transparent 42px);
            color: var(--page-text);
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

        .back-module {
            position: fixed;
            left: 30px;
            top: 30px;
        }

        .back-module a {
            font-size: 20px;
            color: #ffffff;
        }

        .module-splash {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
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
        }

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
            left: -300px;
            width: 300px;
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
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
            animation: splashCardUp .55s cubic-bezier(.2, .8, .2, 1) both .1s;
            margin-left: 15rem;
        }

        .splash-module-meta {
            color: #fff;
            width: 520px;
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
        }

        .splash-module-title {
            font-size: 28px;
            font-family: "Orbitron", sans-serif;
            text-align: center;
            margin: 10px 0 10px;
            line-height: 40px;
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
        }

        .splash-btn i {
            font-size: 12px;
            transition: transform .18s ease;
        }

        .splash-btn:hover i {
            transform: translateX(3px);
        }

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

        .no-js .module-splash {
            display: none;
        }

        .no-js .lessons-shell {
            opacity: 1;
        }

        .skip-splash .module-splash {
            display: none !important;
        }

        .skip-splash .lessons-shell {
            opacity: 1 !important;
        }

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

        .lessons-shell {
            display: block;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .lessons-main {
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .speech-bubble {
            position: absolute;
            left: -315px;
            top: 60px;
            width: 300px;
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
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

        .speech-bubble::after {
            content: '';
            position: absolute;
            right: -6px;
            top: 20px;
            width: 12px;
            height: 12px;
            background: #fff;
            transform: rotate(-45deg);
        }

        .speech-bubble.bubble-3::after {
            right: -6px;
            top: 42.5px;
            transform: rotate(-45deg);
        }

        .lessons-sidebar {
            width: 100%;
            min-height: auto;
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

        .bubble-quiz {
            position: absolute;
            left: 260px;
            top: 3px;
            width: 350px;
            background-color: var(--bg-main);
            color: var(--text-bright);
            font-family: var(--font-body);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            line-height: 1.4;
            padding: 16px 19px;
            border-radius: 14px;
            z-index: 1;
        }

        .bubble-quiz strong {
            display: block;
            color: var(--neon-cyan);
            font-family: "Orbitron", sans-serif;
            font-weight: 700;
            margin-bottom: 2px;
            font-size: 14.5px;
        }

        .bubble-quiz p {
            margin: 0;
            font-size: 13.5px;
            color: var(--text-dim);
            line-height: 22px;
        }

        .bubble-quiz p .typing-cursor {
            display: inline-block;
            width: 2px;
            height: 14px;
            background: var(--neon-cyan);
            margin-left: 2px;
            vertical-align: middle;
            animation: cursorBlink 0.8s step-end infinite;
        }

        .bubble-quiz::after {
            content: '';
            position: absolute;
            left: -6px;
            top: 20px;
            width: 12px;
            height: 12px;
            background: #fff;
            border: 1px solid var(--border);
            transform: rotate(-45deg);
            z-index: -1;
            display: none;
        }

        .ov-hero {
            padding: 34px 30px;
            color: #fff;
            background-color: var(--neon-cyan);
            display: flex;
            justify-content: space-between;
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
            position: relative;
        }

        .bubble-1 {
            top: -10px;
        }

        .bubble-2 {
            top: 20px;
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

        .lesson-hero {
            position: relative;
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

        .sb-progress-block {
            position: relative;
            padding: 0px 16px 14px;
            border-bottom: 1px solid var(--border);
        }

        .sb-progress-block h5 {
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
            background: rgba(0, 119, 204, 0.08);
            border-radius: 3px;
            overflow: hidden;
        }

        .sb-bar-fill {
            height: 100%;
            background-color: var(--neon-cyan);
            border-radius: 3px;
            box-shadow: 0 0 8px rgba(0, 255, 136, 0.4);
            transition: width .6s ease;
        }

        .sb-lesson-list {
            position: relative;
            z-index: 1;
            margin: 0px 180px;
            padding: 28px 20px 35px;
        }

        .sb-nav-group-label {
            font-size: 10.5px;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 14px 8px 5px;
            opacity: 0.65;
        }

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
            color: var(--text-bright);
        }

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
            background: rgba(0, 119, 204, 0.08);
            color: var(--neon-cyan);
        }

        .icon-type-lesson.done {
            background-color: var(--neon-cyan);
            color: var(--deep-navy);
        }

        .icon-type-lesson.active-dot {
            background-color: var(--neon-cyan);
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
            font-size: 11.5px;
            color: var(--text-muted);
            opacity: 0.6;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-nav-check {
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
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
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
            border: 1.5px solid var(--neon-cyan);
            color: var(--neon-cyan);
            cursor: default;
            opacity: 0.85;
        }

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

        .lessons-content-wrap {
            padding: 55px 28px 35px;
            margin: 0 150px;
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
            color: var(--neon-cyan);
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

        .ls-section {
            margin-bottom: 28px;
        }

        .ls-section h4 {
            font-size: 18px;
            font-weight: 600;
        }

        .ls-section-head {
            display: none;
            align-items: center;
            gap: 9px;
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

        .video-card {
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            border-radius: 14px;
            overflow: hidden;
            margin: 20px 0 20px;
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
            height: 350px;
            display: block;
            border: none;
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
            font-size: 23px;
            color: var(--text-bright);
            font-weight: 700;
        }

        .img-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 1.5rem;
            margin-bottom: 20px;
        }

        .img-grid.img-grid-1 {
            grid-template-columns: 1fr;
        }

        .img-grid.img-grid-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .img-grid.img-grid-1 .img-item {
            aspect-ratio: 16 / 9;
        }

        .img-item {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            border-radius: 12px;
            overflow: hidden;
            background: var(--page-card, #fff);
            border: 1px solid var(--page-border, var(--panel-border));
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, border-color .2s;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
        }

        .img-item img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .img-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.10);
            border-color: var(--page-border2, var(--panel-border));
        }

        .img-item-desc {
            font-size: 14.5px;
            color: var(--text-dim);
            margin: 0 0 16px;
            display: none;
        }

        .fc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
            margin: 20px 0 0;
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
            font-size: 11.5px;
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

        .callout-idea {
            border-radius: 12px;
            padding: 15px 18px;
            margin: 16px 0;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .callout-idea.info {
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

        .callout-idea .callout-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .callout .callout-icon {
            display: none;
        }

        .callout-idea.info .callout-icon {
            background: rgba(0, 153, 204, 0.15);
            color: var(--blue-mid, var(--neon-cyan));
        }

        .callout-idea.warning .callout-icon {
            background: rgba(255, 107, 0, 0.15);
            color: var(--orange-warn, #ff6b00);
        }

        .callout-idea.success .callout-icon {
            background: rgba(0, 137, 74, 0.15);
            color: var(--green-dark, var(--neon-cyan));
        }

        .callout-idea .callout-icon svg {
            width: 15px;
            height: 15px;
        }

        .callout-body .cb-title {
            font-size: 23px;
            font-weight: 700;
            margin: 10px 0 0;
        }

        .callout.info .cb-title {
            color: var(--text-bright);
        }

        .callout.warning .cb-title {
            color: var(--orange-warn, #ff6b00);
        }

        .callout.success .cb-title {
            color: var(--green-dark, var(--neon-cyan));
        }

        .callout-body p {
            font-size: 14.5px;
            color: var(--text-bright);
            margin: 0;
        }

        .activity-block {
            margin-bottom: 24px;
        }

        .activity-hero-card {
            margin-bottom: 16px;
            position: relative;
            overflow: hidden;
        }

        .activity-hero-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
            background-size: 22px 22px;
        }

        .act-hero-tag {
            display: inline-block;
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
            color: var(--neon-cyan);
            white-space: nowrap;
        }

        .act-hero-title {
            position: relative;
            z-index: 1;
            font-size: 23px;
            font-weight: 700;
            color: var(--text-bright);
            margin: 10px 0 0;
        }

        .act-hero-desc {
            position: relative;
            z-index: 1;
            font-size: 14.5px;
            color: var(--text-bright);
            line-height: 1.55;
            /* margin-bottom: 12px; */
        }

        .act-meta-pills {
            position: relative;
            z-index: 1;
            display: none;
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
            /* background: var(--page-card, #fff); */
            /* border: 1px solid var(--page-border, var(--panel-border)); */
            color: var(--text-light);
            background: var(--panel);
            border: 1px solid var(--panel-edge);
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
            /* color: var(--neon-cyan); */
            color: #ffffff;
            margin-bottom: 7px;
        }

        .q-text {
            font-size: 14.5px;
            font-weight: 600;
            /* color: var(--page-text, var(--text-bright)); */
            color: #ffffff;
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
            border-radius: 28px;
            /* border-width: 2px; */
            border: 2px solid var(--page-border, var(--panel-border));
            font-size: 13.5px;
            /* color: var(--page-text, var(--text-bright)); */
            /* color: #ffffff; */
            /* color: #ff4d6d; */
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
            border-color: var(--neon-cyan);
            box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.12);
        }

        .quiz-hero-inner {
            position: relative;
            z-index: 1;
            margin: 0 0 30px;
        }

        .quiz-hero-tag {
            display: inline-block;
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
            color: var(--neon-cyan);
            white-space: nowrap;
        }

        .flash-hero-tag {
            display: inline-block;
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
            color: var(--neon-cyan);
            white-space: nowrap;
        }

        .quiz-hero-title {
            font-size: 23px;
            font-weight: 700;
            color: var(--text-bright);
            margin: 10px 0 0;
        }

        .quiz-hero-desc {
            font-size: 14.5px;
            color: var(--text-bright);
            margin: 0;
        }

        .quiz-stats-strip {
            display: none;
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
            font-size: 14.5px;
            color: var(--page-muted, var(--text-dim));
            display: none;
        }

        .quiz-nav-btns {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-qnav-prev {
            padding: 9px 18px;
            border-radius: 9px;
            background: var(--panel);
            border: 1px solid var(--panel-edge);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            color: var(--text-dim);
            font-size: 14.5px;
            font-weight: 600;
            transition: border-color .18s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-qnav-prev:hover {
            border-color: var(--blue-mid, var(--neon-cyan));
            color: var(--blue-mid, var(--neon-cyan));
        }

        .btn-qnav-next {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-qnav-next,
        .btn-submit-quiz {
            padding: 9px 20px;
            border-radius: 9px;
            color: #04121c;
            letter-spacing: .04em;
            background: linear-gradient(90deg, var(--neon-light), var(--neon-green));
            box-shadow: 0 8px 24px rgba(51, 230, 255, .35);
            font-size: 14.5px;
            font-weight: 700;
            transition: transform .18s, box-shadow .18s;
            font-family: "Orbitron", sans-serif;
            text-transform: uppercase;
        }

        .btn-qnav-next:hover,
        .btn-submit-quiz:hover {
            transform: translateY(-1px);
        }

        .btn-qnav-next:disabled {
            opacity: .4;
            box-shadow: 0 8px 24px rgba(51, 230, 255, .35);
            cursor: not-allowed;
        }

        .page-indicator {
            font-size: 12.5px;
            color: var(--page-muted, var(--text-dim));
            display: none;
        }

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

        @media (max-width: 900px) {
            .img-grid {
                grid-template-columns: repeat(2, 1fr);
            }

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

        .img-grid-header {
            margin-top: 1.5rem;
        }

        .img-grid-header .img-grid-title {
            font-size: 23px;
            font-weight: 700;
            color: var(--text-bright);
            margin: 0 0 4px;
        }

        .img-grid-header .img-grid-meta {
            font-size: 13.5px;
            color: var(--text-dim);
        }

        :root {
            --qz-card-bg: #2b1b3d;
            --qz-blue: #3a6ea5;
            --qz-teal: #1f9e8f;
            --qz-orange: #e8a13c;
            --qz-pink: #d9506e;
        }

        .content-quiz-cta {
            display: flex;
            justify-content: start;
            margin: 10px 0 26px;
            position: relative;
        }

        .content-quiz-cta img {
            width: 240px;
            height: 260px;
        }

        .btn-take-quiz {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 99px;
            background-color: var(--neon-cyan);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .bubble-quiz .btn-take-quiz {
            opacity: 0;
            transform: translateY(6px);
            pointer-events: none;
            max-height: 0;
            margin-top: 0;
            overflow: hidden;
            transition: opacity .3s ease, transform .3s ease, max-height .3s ease, margin-top .3s ease;
        }

        .bubble-quiz .btn-take-quiz.btn-visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
            max-height: 40px;
            margin-top: 7px;
        }

        .btn-take-quiz:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0, 119, 204, 0.3);
        }

        .btn-take-quiz:active,
        .btn-quiz-continue:active {
            transform: scale(.94);
        }

        .btn-take-quiz i {
            font-size: 12px;
        }

        .bubble-quiz .btn-quiz-continue {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
            gap: 5px;
            color: var(--neon-cyan);
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
            padding: 0;
            border-radius: 0;
            background: none;
            border: none;
            opacity: 0;
            transform: translateY(6px);
            pointer-events: none;
            max-height: 0;
            margin-top: 0;
            overflow: hidden;
            transition: opacity .3s ease, transform .3s ease, max-height .3s ease, margin-top .3s ease, color .18s ease;
        }

        .bubble-quiz .btn-quiz-continue.btn-visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
            max-height: 40px;
            margin-top: 7px;
        }

        .bubble-quiz .btn-quiz-continue:hover {
            color: var(--neon-blue);
        }

        .bubble-quiz .btn-quiz-continue i {
            font-size: 12.5px;
        }

        .bb-skip-typing {
            display: none;
            margin-top: 6px;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-dim);
            opacity: 0.55;
            cursor: pointer;
            transition: opacity .15s ease, color .15s ease;
            justify-content: end;
        }

        .bb-skip-typing:hover {
            opacity: 1;
            color: var(--neon-cyan);
        }

        .qz-stage {
            margin: 0 auto;
        }

        .qz-counter {
            text-align: center;
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-dim);
            margin-bottom: 10px;
        }

        .qz-progress-track {
            height: 20px;
            border-radius: 99px;
            background: rgba(51, 230, 255, 0.12);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .qz-progress-fill {
            height: 100%;
            border-radius: 99px;
            background-color: var(--arcade-cyan);
            transition: width .35s ease;
        }

        .qz-card {
            background: linear-gradient(160deg, var(--panel), #0a1230);
            border: 1px solid var(--panel-edge);
            border-radius: 20px;
            padding: 46px 36px;
            min-height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
            margin-bottom: 22px;
        }

        .qz-card::before {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 18px;
            padding: 2px;
            background: linear-gradient(120deg, var(--neon-cyan), transparent 30%, transparent 70%, var(--neon-pink));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: .6;
            pointer-events: none;
        }

        .qz-question-text {
            color: #fff;
            font-size: 21px;
            font-weight: 700;
            line-height: 1.45;
        }

        .qz-zoom-icon {
            position: absolute;
            right: 20px;
            bottom: 16px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.14);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .qz-zoom-icon svg {
            width: 15px;
            height: 15px;
        }

        .qz-choices {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .qz-choice-btn {
            display: flex;
            align-items: center;
            padding: 20px 22px;
            border-radius: 14px;
            /* color: var(--text-dim);
            border: 1px solid var(--border); */
            font-size: 15.5px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            /* background-color: #ffffff; */
            color: var(--text-light);
            background: var(--panel);
            border: 1px solid var(--panel-edge);
            justify-content: space-between;
        }

        .qz-choice-btn.qz-review-choice {
            cursor: default;
            pointer-events: none;
        }

        .qz-choice-btn.qz-correct {
            /* border-color: #22c55e;
            background: rgba(34, 197, 94, 0.08);
            color: #15803d; */
            border: 2px solid var(--neon-green);
            background: rgba(57, 255, 158, .10);
            color: var(--neon-green);
        }

        .qz-choice-btn.qz-correct .qz-choice-letter {
            border: 2px solid var(--neon-green);
            background: rgba(57, 255, 158, .10);
            color: var(--neon-green);
        }

        .qz-choice-btn.qz-wrong {
            /* background: rgba(239, 68, 68, 0.08);
            color: #b91c1c; */
            border: 2px solid #ff4d6d;
            background: rgba(255, 77, 109, .10);
            color: #ff4d6d;
        }

        .qz-choice-btn.qz-wrong .qz-choice-letter {
            border: 2px solid #ff4d6d;
            color: #ff4d6d;
        }

        .qz-choice-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.06);
        }

        .qz-choice-btn.selected {
            background: rgba(0, 119, 204, 0.08);
            color: var(--neon-cyan);
        }

        .qz-choice-btn[data-color="0"] {
            /* border: 1px solid var(--border); */
        }

        .qz-choice-btn[data-color="1"] {
            /* border: 1px solid var(--border); */
        }

        .qz-choice-btn[data-color="2"] {
            /* border: 1px solid var(--border); */
        }

        .qz-choice-btn[data-color="3"] {
            /* border: 1px solid var(--border); */
        }

        .qz-choice-inner {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .qz-choice-letter {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--panel-edge, var(--border));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: var(--text-dim);
            flex-shrink: 0;
            transition: background .18s ease, color .18s ease, border-color .18s ease;
        }

        .qz-choice-btn.selected .qz-choice-letter {
            background-color: var(--neon-cyan);
            color: #fff;
            border-color: var(--neon-cyan);
        }

        .qz-choice-btn.qz-correct .qz-choice-letter {
            /* background-color: #22c55e; */
            /* color: #fff; */
            /* color: #22c55e; */
            /* border-color: #22c55e; */
            border: 2px solid var(--neon-green);
            color: var(--neon-green);
        }

        /* .qz-choice-btn.qz-wrong .qz-choice-letter {
            background-color: #ef4444;
            color: #fff;
            border-color: #ef4444;
        } */

        .qz-overlay.open .qz-choice-letter {
            /* background: rgba(255, 255, 255, 0.04);
            border-color: var(--panel-edge);
            color: var(--text-light); */
        }

        .qz-overlay .qz-choice-letter {
            background: rgba(255, 255, 255, 0.04);
            border-color: var(--panel-edge);
            color: var(--text-light);
        }

        .qz-overlay.open .qz-choice-btn.selected .qz-choice-letter {
            background-color: var(--arcade-cyan);
            color: var(--bg-deep);
            border-color: var(--arcade-cyan);
        }

        .qz-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99998;
            background: #ffffff;
            overflow-y: auto;
            padding: 30px 24px 30px;
            opacity: 0;
        }

        .qz-overlay.open {
            display: flex;
            flex-direction: row-reverse;
            /* BonBon (first in HTML) ends up on the right */
            align-items: flex-start;
            justify-content: center;
            gap: 50px;
            padding: 40px 60px;
            animation: qzOverlayIn .42s cubic-bezier(.2, .8, .2, 1) forwards;
            background: radial-gradient(circle at 15% 10%, rgba(51, 230, 255, 0.10), transparent 40%), radial-gradient(circle at 85% 90%, rgba(255, 46, 151, 0.10), transparent 40%), linear-gradient(180deg, var(--bg-deep) 0%, var(--bg-mid) 100%);
        }

        .BonBon-parent {
            position: sticky;
            top: 40px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 260px;
            margin-top: -100px;
            align-self: flex-start;
            /* no longer stretches to match tall content — stays a fixed size */
            height: auto;
            max-height: 340px;
        }


        .bonbon-pole-wrap {
            order: 3;
            flex: 0 0 auto;
            /* fixed length instead of growing to fill all leftover space */
            width: 100%;
            height: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
            margin-left: 20px;
        }

        .bonbon-pole {
            /* width: 14px; */
            width: 100%;
            height: 100%;
            /* position: absolute; */
            /* back to a real pole width, not 100% */
            /* flex: 1 1 auto; */
            /* grows to fill 100% of the wrap's height on its own */
            align-self: stretch;
            /* keep this — makes sure it fills top-to-bottom, not just centered */
            border-radius: 99px;
            background: linear-gradient(180deg, rgba(51, 230, 255, .55), rgba(51, 230, 255, .12));
            border: 1.5px solid rgba(51, 230, 255, .5);
            box-shadow: inset 0 0 10px rgba(51, 230, 255, .25), 0 0 14px rgba(51, 230, 255, .15);
        }

        .bonbon-spring-wrap {
            order: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            margin-top: -6px;
            animation: bonbonSpringSquash 2.4s ease-in-out infinite;
            transform-origin: top center;
        }

        .bonbon-spring-wrap span {
            display: block;
            width: 70px;
            height: 8px;
            border-radius: 50%;
            background: linear-gradient(180deg, rgba(51, 230, 255, .5), rgba(51, 230, 255, .15));
            border: 1.5px solid rgba(51, 230, 255, .5);
        }

        .bonbon-spring-wrap span:nth-child(2) {
            width: 58px;
        }

        .bonbon-spring-wrap span:nth-child(3) {
            width: 46px;
        }

        /* @keyframes bonbonSpringSquash {

            0%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(0.5);
            }
        } */

        .BonBon-parent img {
            width: 200px;
            height: auto;
            pointer-events: none;
            order: 2;
            /* image now comes after the bubble */
            animation: bonbonSpring 2.4s ease-in-out infinite;
        }

        /* 
        @keyframes bonbonSpring {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-14px) scale(1.03);
            }
        } */

        .speech-bubble.drop-drag {
            position: relative;
            left: auto;
            right: auto;
            top: auto;
            width: 100%;
            order: 1;
            /* bubble renders first, on top */
            margin-top: 0;
            /* margin-bottom: 16px; */
            margin-bottom: 30px;
            /* space now goes below instead of above */
            pointer-events: auto;
        }

        /* Flip the little arrow to point DOWN toward the robot, since the bubble is now above it */
        .speech-bubble.drop-drag::after {
            right: auto;
            left: 50%;
            top: auto;
            bottom: -6px;
            transform: translateX(-50%) rotate(45deg);
        }

        /* @keyframes bonbonSpring {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-14px) scale(1.03);
            }
        } */

        .qz-overlay #section-quizzes,
        .qz-overlay #section-activity-stage {
            max-width: 700px;
            width: 100%;
            margin: 0;
        }

        .qz-overlay #drop-drag {
            max-width: 800px;
            width: 100%;
            /* margin: 0 auto; */
        }

        /* #ddOverlay {
            position: relative;
        } */

        /* .speech-bubble.drop-drag {
            position: absolute;
            left: auto;
            top: auto;
            width: 100%;
            margin-top: 16px;
            pointer-events: auto;
        } */

        /* .BonBon-parent {
            position: absolute;
            left: 40px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 260px;
            z-index: 2;
            pointer-events: none;
        }

        .BonBon-parent img {
            width: 200px;
            height: auto;
            pointer-events: none;
        }

        .speech-bubble.drop-drag {
            position: relative;
            left: auto;
            top: auto;
            width: 100%;
            margin-top: 16px;
            pointer-events: auto;
        }

        .speech-bubble.drop-drag::after {
            right: auto;
            left: 50%;
            top: -6px;
            transform: translateX(-50%) rotate(45deg);
        } */

        .qz-overlay.open::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(51, 230, 255, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(51, 230, 255, 0.05) 1px, transparent 1px);
            background-size: 36px 36px;
            mask-image: radial-gradient(circle at 50% 40%, black 10%, transparent 75%);
            pointer-events: none;
            z-index: 0;
        }

        .qz-overlay.qz-closing {
            animation: qzOverlayOut .28s ease forwards;
        }

        @keyframes qzOverlayIn {
            from {
                opacity: 0;
                transform: translateY(28px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes qzOverlayOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(20px) scale(.97);
            }
        }

        .qz-overlay #section-quizzes {
            max-width: 800px;
            margin: 0 auto;
        }

        /* .dd-nav-back {
            max-width: 800px;
            margin: 0 auto;
        }

        .dd-footer {
            max-width: 800px;
            margin: 0 auto;
        }

        .dd-parent-header {
            max-width: 800px;
            margin: 0 auto;
        } */

        /* .qz-overlay #drop-drag{
            max-width: 800px;
            margin: 0 auto;
        } */

        .qz-overlay.open #section-quizzes {
            animation: qzContentUp .45s cubic-bezier(.2, .8, .2, 1) .05s both;
        }

        @keyframes qzContentUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-exit-quiz {
            display: inline-flex;
            gap: 7px;
            font-size: 14px;
            font-weight: 600;
            background: var(--panel);
            border: 1px solid var(--panel-edge);
            color: var(--neon-cyan);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            transition: border-color .15s ease, color .15s ease, transform .15s ease;
        }

        .btn-exit-quiz:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
            transform: translateX(-2px);
        }

        .qz-nav-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 26px;
        }

        .qz-results {
            max-width: 800px;
            margin: 40px auto 0;
        }

        .qz-results h2 {
            font-size: 21px;
            text-align: center;
            font-weight: 600;
        }

        .qz-result-card {
            background-color: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px 22px;
            margin: 25px 0 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .qz-result-label {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-bright);
            margin-bottom: 14px;
        }

        .qz-accuracy-row {
            position: relative;
        }

        .qz-accuracy-track {
            height: 22px;
            border-radius: 99px;
            background: rgba(51, 230, 255, 0.12);
            overflow: hidden;
        }

        .qz-accuracy-fill {
            height: 100%;
            border-radius: 99px;
            background-color: var(--arcade-cyan);
            transition: width .8s ease;
        }

        .qz-accuracy-pct {
            position: absolute;
            top: 50%;
            right: -6px;
            transform: translateY(-50%);
            font-size: 12.5px;
            font-weight: 700;
            color: var(--neon-cyan);
            background: #fff;
            border: 1px solid var(--border);
            padding: 3px 10px;
            border-radius: 99px;
        }

        .qz-result-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .qz-result-count {
            font-size: 12px;
            color: var(--text-dim);
        }

        .qz-stat-pills {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .qz-stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 99px;
        }

        .qz-stat-pill.pill-correct {
            border: 2px solid var(--neon-green);
            background: rgba(57, 255, 158, .10);
            color: var(--neon-green);
        }

        .qz-stat-pill.pill-incorrect {
            border: 2px solid #ff4d6d;
            background: rgba(255, 77, 109, .10);
            color: #ff4d6d;
        }

        .qz-results-actions {
            display: flex;
            justify-content: end;
            margin-top: 20px;
        }

        .qz-nav-row #qzPrevBtn {
            display: inline-flex;
        }

        .btn-qnav-prev .fa {
            font-size: 13.5px;
        }

        .qz-nav-row #qzNextBtn {
            margin-left: auto;
        }

        .btn-qnav-next .fa {
            font-size: 13.5px;
        }

        .qz-overlay.open .btn-exit-quiz {
            color: var(--arcade-cyan);
            position: relative;
            z-index: 1;
        }

        .qz-overlay.open .qz-counter {
            color: var(--arcade-cyan);
            position: relative;
            z-index: 1;
        }

        .qz-overlay.open .qz-stage,
        .qz-overlay.open .qz-results {
            position: relative;
            z-index: 1;
        }

        /* .qz-overlay.open .qz-choice-btn {
            color: var(--text-light);
            background: var(--panel);
            border: 1px solid var(--panel-edge);
        } */

        .qz-overlay.open .qz-choice-btn.selected {
            background: rgba(51, 230, 255, 0.12);
            color: var(--arcade-cyan);
            border-color: var(--arcade-cyan);
        }

        .qz-overlay.open .btn-qnav-prev {
            color: #d8e6f5;
            background: var(--panel);
            border: 1px solid var(--panel-edge);
            font-family: "Orbitron", sans-serif;
            text-transform: uppercase;
        }

        .qz-overlay.open .quiz-status {
            color: #d8e6f5;
        }

        .qz-overlay.open .qz-result-card {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(51, 230, 255, 0.2);
        }

        .qz-overlay.open .qz-result-label,
        .qz-overlay.open .qz-results h2 {
            color: #ffffff;
        }

        .qz-overlay.open .qz-result-count {
            color: #d8e6f5;
        }

        .qz-overlay.open .qz-accuracy-pct {
            background: rgba(255, 255, 255, 0.08);
            color: var(--arcade-cyan);
            border-color: rgba(51, 230, 255, 0.3);
        }

        .qz-activity-textarea {
            width: 100%;
            /* max-width: 560px; */
            min-height: 150px;
            /* margin: 0 auto; */
            display: block;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 14.5px;
            resize: vertical;
            border: 1.5px solid var(--page-border, var(--panel-border));
            background: var(--page-card, #fff);
            color: var(--page-text, var(--text-bright));
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .qz-activity-textarea:focus {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.12);
        }

        .qz-overlay.open .qz-activity-textarea {
            background: var(--panel);
            border-color: var(--panel-edge);
            color: var(--text-light);
        }

        .qz-overlay.open .qz-activity-textarea:focus {
            border-color: var(--arcade-cyan);
            box-shadow: 0 0 0 3px rgba(51, 230, 255, 0.15);
        }

        .qz-overlay.open #section-activity-stage {
            max-width: 800px;
            margin: 0 auto;
        }

        /* ============ GAME-STYLE DRAG & DROP ============ */
        .dd-board {
            /* max-width: 1000px; */
            /* margin: 0 auto; */
            position: relative;
        }

        .dd-hud {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .dd-hud-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: "Orbitron", sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-light);
            letter-spacing: .03em;
        }

        .dd-hud-title i {
            color: var(--arcade-cyan);
            font-size: 18px;
        }

        .dd-hud-stats {
            display: flex;
            gap: 10px;
        }

        .dd-hud-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 12.5px;
            font-weight: 700;
            font-family: "Orbitron", sans-serif;
        }

        .dd-hud-pill.streak {
            background: rgba(255, 170, 0, 0.12);
            border: 1.5px solid rgba(255, 170, 0, 0.35);
            color: #ffb020;
        }

        .dd-hud-pill.score {
            background: rgba(57, 255, 158, 0.1);
            border: 1.5px solid rgba(57, 255, 158, 0.3);
            color: var(--neon-green);
        }

        .dd-board-header {
            margin-bottom: 20px;
        }

        .dd-puzzle-board {
            display: flex;
            justify-content: center;
            /* align-items: center */
            flex-direction: column;
            gap: 28px;
            max-width: 800px;
            margin: 0 auto;
        }

        .dd-target-row,
        .dd-item-row {
            display: grid;
            grid-template-columns: repeat(5, max-content);
            justify-content: center;
            gap: 16px;
        }

        .dd-item-row {
            /* max-width: 800px; */
            /* margin: 0 auto; */
        }

        .dd-target-card {
            width: 150px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            transition: transform .15s ease;
        }

        .dd-target-card.drag-over {
            transform: scale(1.03);
        }

        .dd-target-visual {
            height: 100px;
            border-radius: 14px 14px 0 0;
            background: var(--panel);
            border: 1.5px solid var(--panel-edge);
            border-bottom: none;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .dd-target-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dd-target-visual i {
            font-size: 32px;
            color: var(--arcade-cyan);
        }

        .dd-target-label {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-light);
            padding: 6px 4px;
            background: rgba(255, 255, 255, 0.04);
            border-left: 1.5px solid var(--panel-edge);
            border-right: 1.5px solid var(--panel-edge);
        }

        .dd-socket {
            min-height: 64px;
            border: 1.5px dashed var(--panel-edge);
            border-radius: 0 0 14px 14px;
            background: rgba(255, 255, 255, 0.015);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            transition: border-color .18s ease, background .18s ease;
        }

        .dd-target-card.drag-over .dd-socket {
            border-color: var(--arcade-cyan);
            background: rgba(51, 230, 255, 0.08);
        }

        .dd-socket-slot {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            justify-content: center;
        }

        .dd-socket-slot:empty::before {
            content: attr(data-placeholder);
            font-size: 11.5px;
            font-style: italic;
            color: var(--text-dim);
            opacity: 0.55;
        }

        .dd-target-card.solved .dd-target-visual,
        .dd-target-card.solved .dd-socket {
            border-color: var(--neon-green);
        }

        .dd-target-card.solved .dd-target-label {
            color: var(--neon-green);
        }

        .dd-target-card.zone-shake {
            animation: ddZoneShake .4s ease;
        }

        @keyframes ddZoneShake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-6px);
            }

            50% {
                transform: translateX(6px);
            }

            75% {
                transform: translateX(-4px);
            }
        }

        .dd-card {
            width: 110px;
            display: flex;
            flex-direction: column;
            border: 1.5px solid var(--panel-edge);
            border-radius: 14px;
            overflow: hidden;
            cursor: grab;
            background: var(--panel);
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease, opacity .18s ease;
            user-select: none;
        }

        .dd-card:active {
            cursor: grabbing;
        }

        .dd-card:hover {
            border-color: var(--arcade-cyan);
            box-shadow: 0 6px 16px rgba(51, 230, 255, .2);
            transform: translateY(-2px);
        }

        .dd-card.dragging {
            opacity: .25;
        }

        .dd-card.locked {
            cursor: default;
            border-color: var(--arcade-cyan);
            width: 100px;
        }

        .dd-card.locked:hover {
            transform: none;
        }

        .dd-card.shake-wrong {
            animation: ddShakeWrong .45s ease;
            border-color: #ff4d6d !important;
        }

        @keyframes ddShakeWrong {

            0%,
            100% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(7px);
            }

            60% {
                transform: translateX(-5px);
            }

            80% {
                transform: translateX(3px);
            }
        }

        .dd-card-visual {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            overflow: hidden;
        }

        .dd-card-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .dd-card-visual i {
            font-size: 26px;
            color: var(--arcade-cyan);
        }

        .dd-card-label {
            text-align: center;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--text-light);
            padding: 5px 4px;
        }

        .dd-card-subtitle {
            text-align: center;
            font-size: 11px;
            color: var(--text-dim);
            opacity: 0.65;
            padding: 0 6px 6px;
            line-height: 1.3;
        }

        .dd-card.locked .dd-card-visual i,
        .dd-card.locked .dd-card-label {
            color: var(--arcade-cyan);
        }

        .dd-card.dd-wrong {
            border-color: #ff4d6d !important;
        }

        .dd-card.dd-wrong .dd-card-visual i,
        .dd-card.dd-wrong .dd-card-label {
            color: #ff4d6d;
        }

        .dd-target-card.dd-wrong-zone .dd-socket {
            border-color: #ff4d6d;
        }

        .dd-question-card {
            background: linear-gradient(160deg, var(--panel), #0a1230);
            border: 1px solid var(--panel-edge);
            border-radius: 16px;
            padding: 22px 26px;
            margin-bottom: 20px;
            text-align: center;
        }

        .dd-question-text {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.5;
        }

        .dd-results {
            max-width: 800px;
            /* margin: 20px auto 0; */
        }

        .dd-results h2 {
            font-size: 21px;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
        }

        .dd-review-list {
            margin-top: 16px;
        }

        .dd-review-section {
            width: 100%;
            max-width: 800px;
            /* margin: 0 auto; */
        }

        .dd-card.placed {
            cursor: grab;
            border-color: var(--arcade-cyan);
            width: 100px;
        }

        .dd-card.placed:hover {
            box-shadow: 0 6px 16px rgba(51, 230, 255, .25);
        }

        .dd-card-remove {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #ff4d6d;
            color: #fff;
            font-size: 13px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--panel);
            cursor: pointer;
            z-index: 2;
            display: none;
        }

        .dd-card-remove:hover {
            background: #e0334f;
        }

        .dd-target-card.has-items .dd-target-visual {
            border-color: var(--arcade-cyan);
            background: rgba(51, 230, 255, 0.06);
        }

        .dd-target-card.has-items .dd-socket {
            border-style: solid;
            border-color: var(--arcade-cyan);
            background: rgba(51, 230, 255, 0.08);
            transition: background .18s ease, border-color .18s ease;
        }
    </style>
</head>

<body>
    <script>
        function onDomReady(fn) {
            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                fn();
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }
    </script>

    <script>
        (function () {
            var moduleId = <?= (int) ($moduleId ?? 0) ?>;
            var lessonId = <?= (int) ($lessonId ?? 0) ?>;
            var splashKey = 'splash_dismissed_module_' + moduleId;
            var quizKey = 'quiz_open_lesson_' + lessonId;
            var activityKey = 'activity_open_lesson_' + lessonId; // NEW
            var dragdropKey = 'dragdrop_open_lesson_' + lessonId; // NEW

            var dismissed = sessionStorage.getItem(splashKey);
            var quizWasOpen = sessionStorage.getItem(quizKey) === '1';
            var activityWasOpen = sessionStorage.getItem(activityKey) === '1'; // NEW
            var dragdropWasOpen = !!sessionStorage.getItem(dragdropKey); // NEW

            var navType = 'navigate';
            try {
                var navEntries = performance.getEntriesByType('navigation');
                if (navEntries && navEntries.length) {
                    navType = navEntries[0].type;
                } else if (performance.navigation) {
                    navType = performance.navigation.type === 1 ? 'reload' : 'navigate';
                }
            } catch (e) { }

            var cameFromWithinLessons = document.referrer.indexOf('url=subject_lessons') !== -1;

            var shouldSkip = quizWasOpen || activityWasOpen || dragdropWasOpen ||
                (dismissed && (navType === 'reload' || navType === 'back_forward' || cameFromWithinLessons));

            if (shouldSkip) {
                document.documentElement.classList.add('skip-splash');
                if (quizWasOpen || activityWasOpen || dragdropWasOpen) sessionStorage.setItem(splashKey, '1');
            } else {
                sessionStorage.removeItem(splashKey);
            }
        })();
    </script>

    <?php

    $progressPct = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

    $sbTotalSegments = 5;
    $sbFilledSegments = (int) round(($progressPct / 100) * $sbTotalSegments);
    $sbIsFinished = ($progressPct >= 100);

    if ($progressPct >= 100) {
        $moduleState = 'review';
        $splashBtnLabel = 'Module Review';
        $splashMsg = "You've completed this module. Feel free to review any lesson, anytime.";
    } elseif ($progressPct > 0 || !empty($isModuleStarted)) {
        $moduleState = 'continue';
        $splashBtnLabel = 'Continue Learning';
        $splashMsg = $progressPct > 0
            ? "You're {$progressPct}% through this module — keep up the great progress."
            : "Let's pick up where you left off.";
    } else {
        $moduleState = 'start';
        $splashBtnLabel = 'Start Now';
        $splashMsg = "You haven't started this module yet. Let's dive in!";
    }

    $splashFirstName = 'Student';
    if (!empty($studentName)) {
        $nameParts = explode(' ', trim($studentName));
        $splashFirstName = $nameParts[0];
    }

    $bonbonLessonMessage = "Welcome! I'm your learning assistant, ready to guide you through your journey!";

    $quizAlreadyDone = false;
    if (!empty($quizData)) {
        $quizAlreadyDone = true;
        foreach ($quizData as $qzData) {
            if (!$qzData['result']) {
                $quizAlreadyDone = false;
                break;
            }
        }
    }

    $bonbonGreetName = (!empty($splashFirstName) && $splashFirstName !== 'Student') ? ", {$splashFirstName}" : "";

    $bonbonDragDropGreeting = "Hi{$bonbonGreetName}! I'm BonBon, your matching buddy. Let's see if you can drag each item into the right category!";
    $bonbonDragDropMessage = "Drag each item's card and drop it into the category it belongs to. Tap \"Take the Activity\" below when you're ready.";

    if ($quizAlreadyDone) {
        $bonbonQuizGreeting = "Hi{$bonbonGreetName}! I'm BonBon, your quiz buddy. You've already completed this quiz — nice work!";
        $bonbonQuizMessage = "Want a refresher? Reviewing your answers is a great way to lock in what you've learned. Tap \"Review the Quiz\" below whenever you're ready.";
    } else {
        $bonbonQuizGreeting = "Hi{$bonbonGreetName}! I'm BonBon, your quiz buddy. Quick reminder — there's a short quiz ahead so you can check how well you understood this lesson.";
        $bonbonQuizMessage = "Take your time and answer honestly — it's the best way to see what's clicking and what could use another look. Tap \"Take the Quiz\" below when you're ready.";
    }

    // ADD THE TWO NEW LINES RIGHT HERE:
    $bonbonActivityGreeting = "Hi{$bonbonGreetName}! I'm BonBon, your activity buddy. Time to put what you've learned into practice!";
    $bonbonActivityMessage = "Answer each question one at a time — tap \"Take the Activity\" below when you're ready.";

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
        <div class="back-module">
            <a href="/learning_management/public/?url=modules&subject=css">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
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

    <div class="lessons-shell<?= $lesson ? ' view-lesson' : ' view-list' ?>" id="lessonsShell">

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

                    $firstQzDoneEarly = true;
                    $allQzQuestions = [];
                    $grandTotal = 0;
                    $firstQz = null;
                    $totalPages = 1;

                    if (!empty($quizData)) {
                        foreach ($quizData as $qzData) {
                            if (!$qzData['result']) {
                                $firstQzDoneEarly = false;
                            }
                        }
                        foreach ($quizData as $qzId => $data) {
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
                    }

                    $firstQzDone = $firstQzDoneEarly;

                    // Precompute a flat list of NOT-YET-SUBMITTED activity
                    // questions across all activities in this lesson, so
                    // they can be answered one-at-a-time in the same
                    // fullscreen stage style as the quiz.
                    $pendingActivityQuestions = [];
                    $anyPendingActivity = false;
                    if (!empty($activityData)) {
                        foreach ($activityData as $pendActId => $pendData) {
                            if ($pendData['submission'] === null) {
                                $anyPendingActivity = true;
                                foreach ($pendData['questions'] as $pq) {
                                    $pendingActivityQuestions[] = [
                                        'q' => $pq,
                                        'actId' => (int) $pendActId,
                                        'activity' => $pendData['activity'],
                                    ];
                                }
                            }
                        }
                    }
                    $actGrandTotal = count($pendingActivityQuestions);
                    ?>

                    <div class="lesson-body" id="lessonBody">

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

                                <?php $inImgGrid = false; ?>
                                <?php
                                $imgGroupSize = [];
                                $runStart = null;
                                $runCount = 0;
                                foreach ($contentBlocks as $idx => $block) {
                                    if ($block['type'] === 'image') {
                                        if ($runStart === null) {
                                            $runStart = $idx;
                                        }
                                        $runCount++;
                                    } else {
                                        if ($runStart !== null) {
                                            $imgGroupSize[$runStart] = $runCount;
                                        }
                                        $runStart = null;
                                        $runCount = 0;
                                    }
                                }
                                if ($runStart !== null) {
                                    $imgGroupSize[$runStart] = $runCount;
                                }
                                ?>
                                <?php foreach ($contentBlocks as $idx => $block): ?>

                                    <?php if ($block['type'] === 'text' && (trim($block['title'] ?? '') !== '' || trim($block['body'] ?? '') !== '')):
                                        if ($inImgGrid) {
                                            echo '</div></div>';
                                            $inImgGrid = false;
                                        }
                                        ?>
                                        <div class="lesson-text-card">
                                            <div class="lesson-text-card">
                                                <?php if (trim($block['body'] ?? '') !== ''): ?>
                                                    <?= $block['body'] ?>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (!empty($block['key_idea'])): ?>
                                                <div class="callout-idea info d-flex align-items-center">
                                                    <div class="callout-icon">
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <circle cx="12" cy="12" r="10" />
                                                            <path d="M12 16v-4M12 8h.01" />
                                                        </svg>
                                                    </div>
                                                    <div class="callout-body">
                                                        <p class="m-0"><strong>Key idea:</strong>
                                                            <?= nl2br(htmlspecialchars($block['key_idea'])) ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        <?php elseif ($block['type'] === 'image'):
                                        if (!$inImgGrid) {
                                            $groupTitle = trim($block['title'] ?? '');
                                            $groupSize = $imgGroupSize[$idx] ?? 1;

                                            $gridClass = 'img-grid';
                                            if ($groupSize === 1) {
                                                $gridClass .= ' img-grid-1';
                                            } elseif ($groupSize === 2) {
                                                $gridClass .= ' img-grid-2';
                                            }

                                            echo '<div class="img-grid-wrap">';
                                            if ($groupTitle !== '') {
                                                echo '<div class="img-grid-header">';
                                                echo '<h3 class="img-grid-title">' . htmlspecialchars($groupTitle) . '</h3>';
                                                echo '<div class="img-grid-meta">Reference gallery</div>';
                                                echo '</div>';
                                            }
                                            echo '<div class="' . $gridClass . '">';
                                            $inImgGrid = true;
                                        }
                                        ?>
                                            <div class="img-item" onclick="dbLightbox('<?= htmlspecialchars($block['file_path']) ?>')">
                                                <img src="<?= htmlspecialchars($block['file_path']) ?>"
                                                    alt="<?= htmlspecialchars($block['title'] ?? '') ?>" loading="lazy">
                                            </div>

                                        <?php elseif ($block['type'] === 'video' && !empty($block['file_path'])):
                                        if ($inImgGrid) {
                                            echo '</div></div>';
                                            $inImgGrid = false;
                                        }
                                        $embedUrl = youtubeEmbed($block['file_path']);
                                        if (!empty($embedUrl)): ?>
                                                <?php if (!empty($block['title'])): ?>
                                                    <div class="video-card-info">
                                                        <span class="video-card-title">
                                                            <?= htmlspecialchars($block['title']) ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="video-card">
                                                    <div class="video-card-banner"></div>
                                                    <iframe src="<?= htmlspecialchars($embedUrl) ?>" allowfullscreen loading="lazy"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                                    </iframe>
                                                </div>
                                            <?php endif;
                                    endif; ?>

                                    <?php endforeach; ?>

                                    <?php if ($inImgGrid) {
                                        echo '</div></div>';
                                        $inImgGrid = false;
                                    } ?>

                                    <?php if (!empty($quizData)): ?>
                                        <div class="quiz-hero-card">
                                            <div class="quiz-hero-inner">
                                                <div class="quiz-hero-tag">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        width="11" height="11">
                                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                                    </svg>
                                                    Quiz
                                                </div>
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
                                        <div class="content-quiz-cta" id="quizCta">
                                            <img src="../images/robot-ai5.png" alt="">
                                            <div class="speech-bubble bubble-quiz">
                                                <strong>BonBon</strong>
                                                <div id="bonbonQuizGreetingStage" <?= $quizAlreadyDone ? ' style="display:none;"' : '' ?>>
                                                    <p id="bonbonMessage-quiz-greeting"></p>
                                                    <span class="bb-skip-typing" id="skipGreetingTyping"
                                                        onclick="skipBonbonGreeting()">Skip »</span>
                                                    <button type="button" class="btn-quiz-continue" id="btnQuizContinue">
                                                        Continue
                                                    </button>
                                                </div>
                                                <div id="bonbonQuizMessageStage" <?= $quizAlreadyDone ? '' : ' style="display:none;"' ?>>
                                                    <p id="bonbonMessage-quiz"></p>
                                                    <span class="bb-skip-typing" id="skipQuizMsgTyping"
                                                        onclick="skipBonbonQuizMsg()">Skip »</span>
                                                    <button type="button" class="btn-take-quiz" onclick="openQuizStage()">
                                                        <i class="fa fa-bolt"></i>
                                                        <?= (isset($firstQzDoneEarly) && $firstQzDoneEarly) ? 'Review the Quiz' : 'Take the Quiz' ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            if (!empty($flashcards)): ?>
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
                                            <div class="flash-hero-tag">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    width="11" height="11">
                                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                                </svg>
                                                Flashcards
                                            </div>
                                            <div class="cb-title">How to Use Flashcards</div>
                                            <p>Click any card to flip it and reveal the answer. Click again to go back to the
                                                question.
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
                                        if (!$isSubmitted)
                                            continue; // handled by the activity stage below
                                        ?>
                                        <div class="activity-block">
                                            <div class="activity-hero-card">
                                                <div class="act-hero-tag">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        width="11" height="11">
                                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                                    </svg>
                                                    Hands-On Activity
                                                </div>
                                                <div class="act-hero-title"><?= htmlspecialchars($act['title']) ?></div>
                                                <?php if (!empty($act['instructions'])): ?>
                                                    <div class="act-hero-desc">
                                                        <?= nl2br(htmlspecialchars($act['instructions'])) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="act-meta-pills">
                                                    <span class="meta-pill pill-white"><?= count($questions) ?> Questions</span>
                                                    <span class="meta-pill pill-white">⭐ <?= (int) $act['total_points'] ?>
                                                        pts</span>
                                                    <span class="meta-pill pill-white">✓ Submitted</span>
                                                </div>
                                            </div>

                                            <div class="submitted-notice">
                                                <div class="submitted-check">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                                        <path d="M22 4L12 14.01l-3-3" />
                                                    </svg>
                                                </div>
                                                <div class="submitted-notice-text">
                                                    <div class="sn-title">Activity Submitted</div>
                                                    <div class="sn-sub">You have already completed this activity. Review your
                                                        answers
                                                        below.
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
                                                                    <svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5"
                                                                        width="14" height="14" style="margin-left:auto">
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

                                        </div><!-- /activity-block -->
                                    <?php endforeach; ?>

                                    <?php if ($anyPendingActivity): ?>
                                        <div class="activity-hero-card">
                                            <div class="act-hero-tag">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    width="11" height="11">
                                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                                                </svg>
                                                Hands-On Activity
                                            </div>
                                            <div class="act-hero-title">Ready to practice?</div>
                                            <div class="act-hero-desc"><?= $actGrandTotal ?>
                                                question<?= $actGrandTotal === 1 ? '' : 's' ?> waiting for you below.</div>
                                        </div>

                                        <div class="content-quiz-cta" id="activityCta">
                                            <img src="../images/robot-ai5.png" alt="">
                                            <div class="speech-bubble bubble-quiz">
                                                <strong>BonBon</strong>
                                                <div id="bonbonActivityGreetingStage">
                                                    <p id="bonbonMessage-activity-greeting"></p>
                                                    <span class="bb-skip-typing" id="skipActivityGreetingTyping"
                                                        onclick="skipBonbonActivityGreeting()">Skip »</span>
                                                    <button type="button" class="btn-quiz-continue" id="btnActivityContinue">
                                                        Continue
                                                    </button>
                                                </div>
                                                <div id="bonbonActivityMessageStage" style="display:none;">
                                                    <p id="bonbonMessage-activity"></p>
                                                    <span class="bb-skip-typing" id="skipActivityMsgTyping"
                                                        onclick="skipBonbonActivityMsg()">Skip »</span>
                                                    <button type="button" class="btn-take-quiz" onclick="openActivityStage()">
                                                        <i class="fa fa-pencil"></i>
                                                        Take the Activity
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ls-section" id="section-activity-stage" style="display:none;">
                                            <button type="button" class="btn-exit-quiz" onclick="closeActivityStage()">
                                                <i class="fa fa-arrow-left"></i>
                                            </button>
                                            <div class="qz-stage" id="actStage">
                                                <div class="qz-counter" id="actCounter">Question 1 of <?= $actGrandTotal ?></div>
                                                <div class="qz-progress-track">
                                                    <div class="qz-progress-fill" id="actProgressFill"
                                                        style="width: <?= $actGrandTotal ? round(100 / $actGrandTotal) : 0 ?>%">
                                                    </div>
                                                </div>

                                                <?php foreach ($pendingActivityQuestions as $qi => $item):
                                                    $pq = $item['q'];
                                                    $pActId = $item['actId'];
                                                    $isMc = ($pq['question_type'] === 'multiple_choice');
                                                    $pch = ['a' => $pq['choice_a'], 'b' => $pq['choice_b'], 'c' => $pq['choice_c'], 'd' => $pq['choice_d']];
                                                    ?>
                                                    <div class="qz-question-block act-question-block" data-qi="<?= $qi ?>"
                                                        data-act-id="<?= $pActId ?>" style="<?= $qi > 0 ? 'display:none;' : '' ?>">
                                                        <div class="qz-card">
                                                            <div class="qz-question-text"><?= htmlspecialchars($pq['question']) ?></div>
                                                        </div>
                                                        <?php if ($isMc):
                                                            $pLtrs = ['A', 'B', 'C', 'D'];
                                                            $pci = 0;
                                                            ?>
                                                            <div class="qz-choices">
                                                                <?php foreach ($pch as $pkey => $pval):
                                                                    if ($pval === null)
                                                                        continue;
                                                                    ?>
                                                                    <div class="qz-choice-btn" data-color="<?= $pci ?>"
                                                                        data-qid="<?= (int) $pq['id'] ?>" data-act-id="<?= $pActId ?>"
                                                                        data-key="<?= $pkey ?>" onclick="pickMC(this)">
                                                                        <span class="qz-choice-inner">
                                                                            <span class="qz-choice-letter"><?= $pLtrs[$pci] ?></span>
                                                                            <span class="qz-choice-text"><?= htmlspecialchars($pval) ?></span>
                                                                        </span>
                                                                    </div>
                                                                    <?php $pci++; endforeach; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="qz-fill-blank">
                                                                <textarea class="activity-answer qz-activity-textarea"
                                                                    data-qid="<?= (int) $pq['id'] ?>" data-act-id="<?= $pActId ?>"
                                                                    placeholder="Type your answer here…" rows="4"></textarea>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>

                                                <div class="qz-nav-row">
                                                    <button class="btn-qnav-prev" id="actPrevBtn" style="visibility:hidden;"
                                                        onclick="actNav(-1)">
                                                        <i class="fa fa-chevron-left"></i>
                                                        Prev
                                                    </button>
                                                    <button class="btn-qnav-next" id="actNextBtn" onclick="actNav(1)" disabled>
                                                        Next
                                                        <i class="fa fa-chevron-right"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            $pendingDragDrops = [];
                            foreach ($dragDropData as $ddTitle => $ddInfo) {
                                if ($ddInfo['submission'] === null)
                                    $pendingDragDrops[$ddTitle] = $ddInfo;
                            }
                            ?>

                            <?php if (!empty($dragDropData)): ?>
                                <div class="ls-section" id="section-dragdrop">

                                    <?php foreach ($dragDropData as $ddTitle => $ddInfo):
                                        if ($ddInfo['submission'] === null)
                                            continue;
                                        $ddAnswers = $ddInfo['submission']['answers'] ?? [];

                                        $ddReviewCorrect = 0;
                                        foreach ($ddInfo['items'] as $item) {
                                            $picked = $ddAnswers[$item['label']] ?? null;
                                            if ($picked !== null && strcasecmp($picked, $item['category']) === 0) {
                                                $ddReviewCorrect++;
                                            }
                                        }
                                        $ddReviewTotal = count($ddInfo['items']);
                                        $ddReviewAccuracy = $ddReviewTotal > 0 ? round(($ddReviewCorrect / $ddReviewTotal) * 100) : 0;
                                        $ddReviewIncorrect = $ddReviewTotal - $ddReviewCorrect;
                                        ?>
                                        <div class="content-quiz-cta">
                                            <img src="../images/robot-ai5.png" alt="">
                                            <div class="speech-bubble bubble-quiz">
                                                <strong>BonBon</strong>
                                                <p>Nice work — you've already completed "<?= htmlspecialchars($ddTitle) ?>"! Want to
                                                    see which ones you got right?</p>
                                                <button type="button" class="btn-take-quiz btn-visible"
                                                    onclick="openDragDropReviewStage('<?= htmlspecialchars(addslashes($ddTitle), ENT_QUOTES) ?>')">
                                                    <i class="fa fa-list-check"></i> Review the Activity
                                                </button>
                                            </div>
                                        </div>

                                        <div class="ls-section dd-review-section"
                                            data-game-title="<?= htmlspecialchars($ddTitle) ?>" style="display:none;">
                                            <button type="button" class="btn-exit-quiz"
                                                onclick="closeDragDropReviewStage('<?= htmlspecialchars(addslashes($ddTitle), ENT_QUOTES) ?>')">
                                                <i class="fa fa-arrow-left"></i>
                                            </button>

                                            <div class="dd-results" style="display:block;">
                                                <h2>Matching Results</h2>
                                                <div class="qz-result-card">
                                                    <div class="qz-result-label">Accuracy</div>
                                                    <div class="qz-accuracy-row">
                                                        <div class="qz-accuracy-track">
                                                            <div class="qz-accuracy-fill" style="width:<?= $ddReviewAccuracy ?>%">
                                                            </div>
                                                        </div>
                                                        <span class="qz-accuracy-pct"><?= $ddReviewAccuracy ?>%</span>
                                                    </div>
                                                </div>
                                                <div class="qz-result-card">
                                                    <div class="qz-result-row">
                                                        <div class="qz-result-label">Performance Stats</div>
                                                        <span class="qz-result-count"><?= $ddReviewTotal ?> items</span>
                                                    </div>
                                                    <div class="qz-stat-pills">
                                                        <span class="qz-stat-pill pill-correct">
                                                            <i class="fa fa-check"></i> <?= $ddReviewCorrect ?> Correct
                                                        </span>
                                                        <span class="qz-stat-pill pill-incorrect">
                                                            <i class="fa fa-times"></i> <?= $ddReviewIncorrect ?> Incorrect
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="dd-review-list">
                                                    <?php foreach ($ddInfo['items'] as $item):
                                                        $picked = $ddAnswers[$item['label']] ?? null;
                                                        $isCorrect = ($picked !== null && strcasecmp($picked, $item['category']) === 0);
                                                        ?>
                                                        <div class="question-card">
                                                            <div class="q-num-label">Item</div>
                                                            <div class="q-text"><?= htmlspecialchars($item['label']) ?></div>
                                                            <?php if (!empty($item['subtitle'])): ?>
                                                                <div style="font-size:12.5px;color:var(--text-dim);margin:-6px 0 10px;">
                                                                    <?= htmlspecialchars($item['subtitle']) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="review-choice"
                                                                style="<?= $isCorrect ? 'border-color:var(--neon-green);background: rgba(57, 255, 158, .10); color: var(--neon-green)' : 'border-color:#ff4d6d; background: rgba(255, 77, 109, .10);     color: #ff4d6d;' ?>">
                                                                <span
                                                                    style="font-weight:700; font-size: 13.5px; color:<?= $isCorrect ? 'var(--neon-green)' : '#ff4d6d' ?>;">
                                                                    <?= $isCorrect ? '✓ Correct' : '✗ Incorrect' ?>
                                                                </span>
                                                                Your answer:
                                                                <?= htmlspecialchars($picked ?? '—') ?>
                                                                <?php if (!$isCorrect): ?>
                                                                    <span style="margin-left:auto;color:#ef4444;">Correct:
                                                                        <?= htmlspecialchars($item['category']) ?></span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php foreach ($pendingDragDrops as $ddTitle => $ddInfo): ?>
                                        <div class="activity-hero-card">
                                            <div class="act-hero-tag">Matching Activity</div>
                                            <div class="act-hero-title">
                                                <?= htmlspecialchars($ddTitle) ?>
                                            </div>
                                            <?php if (!empty($ddInfo['game']['instructions'])): ?>
                                                <div class="act-hero-desc">
                                                    <?= nl2br(htmlspecialchars($ddInfo['game']['instructions'])) ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="act-hero-desc"><?= count($ddInfo['items']) ?>
                                                    item<?= count($ddInfo['items']) === 1 ? '' : 's' ?> to match.</div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="content-quiz-cta">
                                            <img src="../images/robot-ai5.png" alt="">
                                            <div class="speech-bubble bubble-quiz">
                                                <strong>BonBon</strong>
                                                <div class="bonbon-dd-greeting-stage">
                                                    <p class="bonbon-dd-greeting-text"
                                                        data-msg="<?= htmlspecialchars($bonbonDragDropGreeting) ?>"></p>
                                                    <span class="bb-skip-typing skip-dd-greeting">Skip »</span>
                                                    <button type="button"
                                                        class="btn-quiz-continue btn-dd-continue">Continue</button>
                                                </div>
                                                <div class="bonbon-dd-message-stage" style="display:none;">
                                                    <p class="bonbon-dd-message-text"
                                                        data-msg="<?= htmlspecialchars($bonbonDragDropMessage) ?>"></p>
                                                    <span class="bb-skip-typing skip-dd-msg">Skip »</span>
                                                    <button type="button" class="btn-take-quiz"
                                                        onclick="openDragDropStage('<?= htmlspecialchars(addslashes($ddTitle), ENT_QUOTES) ?>')">
                                                        <i class="fa fa-arrows-alt"></i> Take the Activity
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $itemsForJs = array_map(function ($it) {
                                            return [
                                                'label' => $it['label'],
                                                'subtitle' => $it['subtitle'],
                                                'category' => $it['category'],
                                                'image' => $it['image'] ?? null,
                                                'icon' => ddIconForLabel($it['label']),
                                            ];
                                        }, array_values($ddInfo['items']));
                                        ?>
                                        <div class="ls-section dd-stage-section" id="drop-drag"
                                            data-game-title="<?= htmlspecialchars($ddTitle) ?>" style="display:none;">
                                            <div class="dd-nav-back">
                                                <button type="button" class="btn-exit-quiz"
                                                    onclick="closeDragDropStage('<?= htmlspecialchars(addslashes($ddTitle), ENT_QUOTES) ?>')">
                                                    <i class="fa fa-arrow-left"></i>
                                                </button>
                                            </div>

                                            <?php
                                            // Build a { "Category Name": "hint text" } map for the JS to read.
// Missing/empty hints are simply omitted — the JS fallback handles those.
                                            $categoryHintsForJs = [];
                                            foreach (($ddInfo['category_hints'] ?? []) as $catName => $hintText) {
                                                if (trim((string) $hintText) !== '') {
                                                    $categoryHintsForJs[$catName] = trim((string) $hintText);
                                                }
                                            }
                                            ?>
                                            <div class="dd-board" data-game-title="<?= htmlspecialchars($ddTitle) ?>"
                                                data-items='<?= htmlspecialchars(json_encode($itemsForJs), ENT_QUOTES) ?>'
                                                data-category-hints='<?= htmlspecialchars(json_encode($categoryHintsForJs), ENT_QUOTES) ?>'>

                                                <div class="dd-parent-header">
                                                    <div class="dd-hud">
                                                        <div class="dd-hud-title"><i class="fa fa-gamepad"></i> Component Matching
                                                            Challenge</div>
                                                        <div class="dd-feedback"></div>
                                                    </div>
                                                    <div class="dd-board-header">
                                                        <div class="qz-counter dd-counter">0 of <?= count($ddInfo['items']) ?>
                                                            placed</div>
                                                        <div class="qz-progress-track">
                                                            <div class="qz-progress-fill dd-progress-fill" style="width:0%"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="dd-question-card">
                                                    <div class="dd-question-text"></div>
                                                </div> -->

                                                <?php $shuffledCats = $ddInfo['categories'];
                                                shuffle($shuffledCats); ?>
                                                <div class="dd-puzzle-board">
                                                    <div class="dd-target-row">
                                                        <?php foreach ($shuffledCats as $cat):
                                                            $catIcon = ddIconForLabel($cat); ?>
                                                            <div class="dd-target-card" data-category="<?= htmlspecialchars($cat) ?>">
                                                                <div class="dd-target-visual"><i class="fa <?= $catIcon ?>"></i></div>
                                                                <div class="dd-target-label"><?= htmlspecialchars($cat) ?></div>
                                                                <div class="dd-socket" data-category="<?= htmlspecialchars($cat) ?>">
                                                                    <div class="dd-socket-slot" data-placeholder="Drop here"></div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <?php $shuffledItems = $ddInfo['items'];
                                                    shuffle($shuffledItems); ?>
                                                    <div class="dd-item-row" data-role="bank">
                                                        <?php foreach ($shuffledItems as $item):
                                                            $itemIcon = ddIconForLabel($item['label']);
                                                            $itemImg = !empty($item['image']) ? $item['image'] : null;
                                                            ?>
                                                            <div class="dd-card" draggable="true"
                                                                data-item="<?= htmlspecialchars($item['label']) ?>"
                                                                data-category="<?= htmlspecialchars($item['category']) ?>">
                                                                <div class="dd-card-visual">
                                                                    <?php if ($itemImg): ?>
                                                                        <img src="<?= htmlspecialchars($itemImg) ?>" alt=""
                                                                            onerror="this.replaceWith(Object.assign(document.createElement('i'), {className:'fa <?= $itemIcon ?>'}));">
                                                                    <?php else: ?>
                                                                        <i class="fa <?= $itemIcon ?>"></i>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="dd-card-label"><?= htmlspecialchars($item['label']) ?></div>
                                                                <?php if (!empty($item['subtitle'])): ?>
                                                                    <div class="dd-card-subtitle"><?= htmlspecialchars($item['subtitle']) ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="dd-footer">
                                                    <div class="qz-nav-row">
                                                        <div></div>
                                                        <button class="btn-qnav-next dd-finish-btn" disabled style="display:none;"
                                                            onclick="ddSubmit('<?= htmlspecialchars(addslashes($ddTitle), ENT_QUOTES) ?>')">
                                                            Finish <i class="fa fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dd-results" style="display:none;">
                                                <h2>Matching Results</h2>
                                                <div class="qz-result-card">
                                                    <div class="qz-result-label">Accuracy</div>
                                                    <div class="qz-accuracy-row">
                                                        <div class="qz-accuracy-track">
                                                            <div class="qz-accuracy-fill" style="width:0%"></div>
                                                        </div>
                                                        <span class="qz-accuracy-pct">0%</span>
                                                    </div>
                                                </div>
                                                <div class="dd-review-list"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </div><!-- /lesson-body -->

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

                            <div class="ls-section" id="section-quizzes" style="display:none;">
                                <button type="button" class="btn-exit-quiz" onclick="closeQuizStage()">
                                    <i class="fa fa-arrow-left"></i>

                                </button>
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

                                    <?php
                                    $allQzReviewQuestions = [];
                                    foreach ($quizData as $qzId => $data) {
                                        if (!$data['result'])
                                            continue;
                                        $studentAnswers = [];
                                        if (!empty($data['result']['answers_json'])) {
                                            $studentAnswers = json_decode($data['result']['answers_json'], true) ?? [];
                                        }
                                        foreach ($data['questions'] as $q) {
                                            $allQzReviewQuestions[] = [
                                                'q' => $q,
                                                'qzId' => (int) $qzId,
                                                'studentAnswer' => strtolower($studentAnswers[$q['id']] ?? ''),
                                            ];
                                        }
                                    }
                                    $reviewGrandTotal = count($allQzReviewQuestions);

                                    $reviewCorrect = 0;
                                    foreach ($allQzReviewQuestions as $reviewItem) {
                                        $reviewCorrectAns = strtolower($reviewItem['q']['correct_ans'] ?? '');
                                        if ($reviewItem['studentAnswer'] === $reviewCorrectAns) {
                                            $reviewCorrect++;
                                        }
                                    }
                                    $reviewIncorrect = $reviewGrandTotal - $reviewCorrect;
                                    $reviewAccuracy = $reviewGrandTotal > 0 ? round(($reviewCorrect / $reviewGrandTotal) * 100) : 0;
                                    ?>



                                    <div class="qz-stage" id="qzReviewStage">
                                        <div class="qz-counter" id="qzReviewCounter">Question 1 of <?= $reviewGrandTotal ?></div>
                                        <div class="qz-progress-track">
                                            <div class="qz-progress-fill" id="qzReviewProgressFill"
                                                style="width: <?= $reviewGrandTotal ? round(100 / $reviewGrandTotal) : 0 ?>%"></div>
                                        </div>

                                        <?php foreach ($allQzReviewQuestions as $qi => $item):
                                            $q = $item['q'];
                                            $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                            $correctAns = strtolower($q['correct_ans']);
                                            $studentPicked = $item['studentAnswer'];
                                            ?>
                                            <div class="qz-question-block qz-review-block" data-qi="<?= $qi ?>"
                                                style="<?= $qi > 0 ? 'display:none;' : '' ?>">
                                                <div class="qz-card">
                                                    <div class="qz-question-text"><?= htmlspecialchars($q['question']) ?></div>
                                                </div>
                                                <div class="qz-choices">
                                                    <?php $ci = 0;
                                                    $qzLetters = ['A', 'B', 'C', 'D'];
                                                    foreach ($ch as $key => $val):
                                                        if ($val === null)
                                                            continue;
                                                        $isCorrect = ($key === $correctAns);
                                                        $isPicked = ($key === $studentPicked);
                                                        $isWrong = ($isPicked && !$isCorrect);
                                                        $stateClass = $isCorrect ? 'qz-correct' : ($isWrong ? 'qz-wrong' : '');
                                                        ?>
                                                        <div class="qz-choice-btn qz-review-choice <?= $stateClass ?>"
                                                            data-color="<?= $ci ?>">
                                                            <span class="qz-choice-inner">
                                                                <span class="qz-choice-letter"><?= $qzLetters[$ci] ?></span>
                                                                <span class="qz-choice-text"><?= htmlspecialchars($val) ?></span>
                                                            </span>
                                                            <?php if ($isCorrect): ?>
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                                    width="16" height="16">
                                                                    <path d="M20 6L9 17l-5-5" />
                                                                </svg>
                                                            <?php elseif ($isWrong): ?>
                                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                                                    width="16" height="16">
                                                                    <path d="M18 6L6 18M6 6l12 12" />
                                                                </svg>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php $ci++; endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <div class="qz-nav-row">
                                            <button class="btn-qnav-prev" id="qzReviewPrevBtn" style="visibility:hidden;"
                                                onclick="qzReviewNav(-1)">
                                                <i class="fa fa-chevron-left"></i>
                                                Prev
                                            </button>
                                            <button class="btn-qnav-next" id="qzReviewNextBtn" onclick="qzReviewNav(1)">
                                                Next
                                                <i class="fa fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="qz-results" id="qzReviewResults" style="display:none;">
                                        <h2>Quiz Results</h2>
                                        <div class="qz-result-card">
                                            <div class="qz-result-label">Accuracy</div>
                                            <div class="qz-accuracy-row">
                                                <div class="qz-accuracy-track">
                                                    <div class="qz-accuracy-fill" style="width:<?= $reviewAccuracy ?>%"></div>
                                                </div>
                                                <span class="qz-accuracy-pct"><?= $reviewAccuracy ?>%</span>
                                            </div>
                                        </div>
                                        <div class="qz-result-card">
                                            <div class="qz-result-row">
                                                <div class="qz-result-label">Performance Stats</div>
                                                <span class="qz-result-count"><?= $reviewGrandTotal ?> questions</span>
                                            </div>
                                            <div class="qz-stat-pills">
                                                <span class="qz-stat-pill pill-correct">
                                                    <i class="fa fa-check"></i> <?= $reviewCorrect ?> Correct
                                                </span>
                                                <span class="qz-stat-pill pill-incorrect">
                                                    <i class="fa fa-times"></i> <?= $reviewIncorrect ?> Incorrect
                                                </span>
                                            </div>
                                        </div>
                                        <div class="qz-results-actions">
                                            <button type="button" class="btn-qnav-next" id="qzReviewResultsBackBtn">
                                                <i class="fa fa-arrow-left"></i> Back to Quiz Review
                                            </button>
                                        </div>
                                    </div>

                                <?php else: ?>


                                    <div class="qz-stage" id="qzStage">
                                        <div class="qz-counter" id="qzCounter">Question 1 of <?= $grandTotal ?></div>
                                        <div class="qz-progress-track">
                                            <div class="qz-progress-fill" id="qzProgressFill"
                                                style="width: <?= $grandTotal ? round(100 / $grandTotal) : 0 ?>%"></div>
                                        </div>

                                        <?php foreach ($allQzQuestions as $qi => $item):
                                            $q = $item['q'];
                                            $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                            ?>
                                            <div class="qz-question-block" data-qi="<?= $qi ?>" data-qzid="<?= $item['qzId'] ?>"
                                                style="<?= $qi > 0 ? 'display:none;' : '' ?>">
                                                <div class="qz-card">
                                                    <div class="qz-question-text"><?= htmlspecialchars($q['question']) ?></div>
                                                </div>
                                                <div class="qz-choices">
                                                    <?php $ci = 0;
                                                    $qzLetters = ['A', 'B', 'C', 'D'];
                                                    foreach ($ch as $key => $val):
                                                        if ($val === null)
                                                            continue;
                                                        ?>
                                                        <div class="qz-choice-btn" data-color="<?= $ci ?>" data-qid="<?= (int) $q['id'] ?>"
                                                            data-key="<?= $key ?>" data-qzid="<?= $item['qzId'] ?>"
                                                            data-correct="<?= ($key === strtolower($q['correct_ans'] ?? '')) ? '1' : '0' ?>"
                                                            onclick="qzPick(this)">
                                                            <span class="qz-choice-inner">
                                                                <span class="qz-choice-letter"><?= $qzLetters[$ci] ?></span>
                                                                <span class="qz-choice-text"><?= htmlspecialchars($val) ?></span>
                                                            </span>
                                                        </div>
                                                        <?php $ci++; endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <div class="qz-nav-row">
                                            <button class="btn-qnav-prev" id="qzPrevBtn" style="visibility:hidden;"
                                                onclick="qzNav(-1)">
                                                <i class="fa fa-chevron-left"></i>
                                                Prev
                                            </button>
                                            <button class="btn-qnav-next" id="qzNextBtn" onclick="qzNav(1)" disabled>
                                                Next
                                                <i class="fa fa-chevron-right"></i>
                                            </button>
                                        </div>

                                        <div class="quiz-status" id="unified_status" style="text-align:center;margin-top:10px;">
                                            0 / <?= $grandTotal ?> answered</div>
                                    </div>

                                    <div class="qz-results" id="qzResults" style="display:none;">
                                        <h2>Quiz Results</h2>
                                        <div class="qz-result-card">
                                            <div class="qz-result-label">Accuracy</div>
                                            <div class="qz-accuracy-row">
                                                <div class="qz-accuracy-track">
                                                    <div class="qz-accuracy-fill" id="qzAccuracyFill" style="width:0%"></div>
                                                </div>
                                                <span class="qz-accuracy-pct" id="qzAccuracyPct">0%</span>
                                            </div>
                                        </div>
                                        <div class="qz-result-card">
                                            <div class="qz-result-row">
                                                <div class="qz-result-label">Performance Stats</div>
                                                <span class="qz-result-count" id="qzResultCount">0 questions</span>
                                            </div>
                                            <div class="qz-stat-pills">
                                                <span class="qz-stat-pill pill-correct" id="qzPillCorrect">
                                                    <i class="fa fa-check"></i> 0 Correct
                                                </span>
                                                <span class="qz-stat-pill pill-incorrect" id="qzPillIncorrect">
                                                    <i class="fa fa-times"></i> 0 Incorrect
                                                </span>
                                            </div>
                                        </div>
                                        <div class="qz-results-actions">
                                            <button type="button" class="btn-qnav-next" id="qzResultsContinueBtn">
                                                Continue <i class="fa fa-arrow-right"></i>
                                            </button>
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
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <?php $lastLessonDone = $isCurrentLessonDone ?? ($lessonCompletion[$lessonId] ?? false); ?>
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

            </div><!-- /lessons-main -->
        </div><!-- /lessons-shell -->

        <div class="qz-overlay" id="qzOverlay">
            <!-- <div class="BonBon-parent">
                <img src="../images/robot-ai10.png" alt="">
                <div class="bonbon-pole-wrap">
                    <div class="bonbon-pole"></div>
                </div>
                <div class="speech-bubble drop-drag">
                    <strong>BonBon</strong>
                    <p><?= htmlspecialchars($bonbonQuizGreeting ?? "Hi! I'm BonBon, your quiz buddy. Quick reminder — there's a short quiz ahead so you can check how well you understood this lesson.") ?>
                    </p>
                </div>
            </div> -->
        </div>

        <div class="qz-overlay" id="actOverlay">
            <!-- <div class="BonBon-parent">
                <img src="../images/robot-ai10.png" alt="">
                <div class="bonbon-pole-wrap">
                    <div class="bonbon-pole"></div>
                </div>
                <div class="speech-bubble drop-drag">
                    <strong>BonBon</strong>
                    <p id="actOverlayMessage">
                        <?= htmlspecialchars($bonbonDragDropGreeting ?? "Let's match these up!") ?>
                    </p>
                </div>
            </div> -->
        </div>

        <div class="qz-overlay" id="ddOverlay">
            <div class="BonBon-parent">
                <img src="../images/robot-ai10.png" alt="">
                <div class="bonbon-pole-wrap">
                    <div class="bonbon-pole"></div>
                </div>
                <div class="speech-bubble drop-drag">
                    <strong>BonBon</strong>
                    <p id="ddOverlayMessage">
                        <?= htmlspecialchars($bonbonDragDropGreeting ?? "Let's match these up!") ?>
                    </p>
                </div>
            </div>
        </div>

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

                .lessons-shell.shell-scroll-lock {
                    overflow: hidden;
                }
            </style>
        </noscript>

        <script>
            function openQuizStage() {
                var quizSection = document.getElementById('section-quizzes');
                var overlay = document.getElementById('qzOverlay');
                if (!quizSection || !overlay) return;

                if (overlay.parentElement !== document.body) {
                    document.body.appendChild(overlay);
                }

                if (!document.getElementById('qzHomeMarker')) {
                    var marker = document.createElement('div');
                    marker.id = 'qzHomeMarker';
                    marker.style.display = 'none';
                    quizSection.parentNode.insertBefore(marker, quizSection);
                }

                overlay.appendChild(quizSection);
                quizSection.style.display = 'block';
                overlay.classList.remove('qz-closing');
                overlay.classList.add('open');
                overlay.scrollTop = 0;

                document.body.dataset.prevOverflow = document.body.style.overflow || '';
                document.body.style.overflow = 'hidden';

                var shell = document.getElementById('lessonsShell');
                if (shell) shell.classList.add('shell-scroll-lock');

                if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
                    sessionStorage.setItem('quiz_open_lesson_' + LESSON_DATA.lessonId, '1');
                }
            }

            (function () {
                if (typeof LESSON_DATA === 'undefined' || !LESSON_DATA.lessonId) return;
                var flagKey = 'quiz_open_lesson_' + LESSON_DATA.lessonId;

                if (sessionStorage.getItem(flagKey) === '1') {
                    document.addEventListener('DOMContentLoaded', function () {
                        if (document.getElementById('section-quizzes')) {
                            openQuizStage();
                        }
                    });
                }
            })();

            function closeQuizStage() {
                var quizSection = document.getElementById('section-quizzes');
                var overlay = document.getElementById('qzOverlay');
                var marker = document.getElementById('qzHomeMarker');
                if (!quizSection || !overlay) return;

                overlay.classList.add('qz-closing');

                setTimeout(function () {
                    if (marker && marker.parentNode) {
                        marker.parentNode.insertBefore(quizSection, marker);
                    }
                    quizSection.style.display = 'none';
                    overlay.classList.remove('open', 'qz-closing');

                    document.body.style.overflow = document.body.dataset.prevOverflow || '';

                    var shell = document.getElementById('lessonsShell');
                    if (shell) shell.classList.remove('shell-scroll-lock');

                    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
                        sessionStorage.removeItem('quiz_open_lesson_' + LESSON_DATA.lessonId);
                    }
                    scrollToSection('quizCta');
                }, 280);
            }
        </script>

        <script>
            function openActivityStage() {
                var stage = document.getElementById('section-activity-stage');
                var overlay = document.getElementById('actOverlay');
                if (!stage || !overlay) return;

                if (overlay.parentElement !== document.body) {
                    document.body.appendChild(overlay);
                }

                if (!document.getElementById('actHomeMarker')) {
                    var marker = document.createElement('div');
                    marker.id = 'actHomeMarker';
                    marker.style.display = 'none';
                    stage.parentNode.insertBefore(marker, stage);
                }

                overlay.appendChild(stage);
                stage.style.display = 'block';
                overlay.classList.remove('qz-closing');
                overlay.classList.add('open');
                overlay.scrollTop = 0;

                document.body.dataset.prevOverflow = document.body.style.overflow || '';
                document.body.style.overflow = 'hidden';

                var shell = document.getElementById('lessonsShell');
                if (shell) shell.classList.add('shell-scroll-lock');

                if (typeof actUpdateNav === 'function') actUpdateNav();

                // NEW: remember that the activity stage is open, so a reload can restore it
                if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
                    sessionStorage.setItem('activity_open_lesson_' + LESSON_DATA.lessonId, '1');
                }
            }

            function closeActivityStage() {
                var stage = document.getElementById('section-activity-stage');
                var overlay = document.getElementById('actOverlay');
                var marker = document.getElementById('actHomeMarker');
                if (!stage || !overlay) return;

                overlay.classList.add('qz-closing');

                setTimeout(function () {
                    if (marker && marker.parentNode) {
                        marker.parentNode.insertBefore(stage, marker);
                    }
                    stage.style.display = 'none';
                    overlay.classList.remove('open', 'qz-closing');

                    document.body.style.overflow = document.body.dataset.prevOverflow || '';

                    var shell = document.getElementById('lessonsShell');
                    if (shell) shell.classList.remove('shell-scroll-lock');

                    // NEW: clear the flag once the user actually leaves the stage
                    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
                        sessionStorage.removeItem('activity_open_lesson_' + LESSON_DATA.lessonId);
                    }

                    scrollToSection('activityCta');
                }, 280);
            }
        </script>

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

            onDomReady(startBonbonBubble3Typewriter);
        </script>

        <script>
            var _bbQuizGreetingFinish = null;
            var _bbQuizMsgFinish = null;

            function startBonbonQuizGreetingTypewriter() {
                const el = document.getElementById('bonbonMessage-quiz-greeting');
                if (!el || el.dataset.typed) return;
                el.dataset.typed = '1';

                const message = <?= json_encode($bonbonQuizGreeting ?? '') ?>;
                const speed = 28;
                let i = 0;
                let done = false;

                const cursor = document.createElement('span');
                cursor.className = 'typing-cursor';
                el.appendChild(cursor);

                const skipEl = document.getElementById('skipGreetingTyping');
                if (skipEl) skipEl.style.display = 'flex';

                function finish() {
                    if (done) return;
                    done = true;
                    cursor.remove();
                    el.textContent = message;
                    if (skipEl) skipEl.style.display = 'none';
                    const btn = document.getElementById('btnQuizContinue');
                    if (btn) btn.classList.add('btn-visible');
                }
                _bbQuizGreetingFinish = finish;

                function type() {
                    if (done) return;
                    if (i < message.length) {
                        cursor.insertAdjacentText('beforebegin', message.charAt(i));
                        i++;
                        setTimeout(type, speed);
                    } else {
                        setTimeout(finish, 200);
                    }
                }
                type();
            }

            function skipBonbonGreeting() {
                if (_bbQuizGreetingFinish) _bbQuizGreetingFinish();
            }

            function typeQuizMessage() {
                const el = document.getElementById('bonbonMessage-quiz');
                if (!el || el.dataset.typed) return;
                el.dataset.typed = '1';

                const message = <?= json_encode($bonbonQuizMessage ?? '') ?>;
                const speed = 28;
                let i = 0;
                let done = false;

                const cursor = document.createElement('span');
                cursor.className = 'typing-cursor';
                el.appendChild(cursor);

                const skipEl = document.getElementById('skipQuizMsgTyping');
                if (skipEl) skipEl.style.display = 'flex';

                function finish() {
                    if (done) return;
                    done = true;
                    cursor.remove();
                    el.textContent = message;
                    if (skipEl) skipEl.style.display = 'none';
                    const btn = document.querySelector('#bonbonQuizMessageStage .btn-take-quiz');
                    if (btn) btn.classList.add('btn-visible');
                }
                _bbQuizMsgFinish = finish;

                function type() {
                    if (done) return;
                    if (i < message.length) {
                        cursor.insertAdjacentText('beforebegin', message.charAt(i));
                        i++;
                        setTimeout(type, speed);
                    } else {
                        setTimeout(finish, 200);
                    }
                }
                type();
            }

            function skipBonbonQuizMsg() {
                if (_bbQuizMsgFinish) _bbQuizMsgFinish();
            }

            // Entry point kept so the existing call from the splash-exit handler
            // still works, and it also fires correctly on skip-splash reloads.
            function startBonbonQuizTypewriter() {
                <?php if ($quizAlreadyDone): ?>
                    typeQuizMessage();
                <?php else: ?>
                    startBonbonQuizGreetingTypewriter();
                <?php endif; ?>
            }

            onDomReady(function () {
                if (!document.getElementById('quizCta')) return;

                if (document.documentElement.classList.contains('skip-splash')) {
                    startBonbonQuizTypewriter();
                }

                const continueBtn = document.getElementById('btnQuizContinue');
                if (continueBtn) {
                    continueBtn.addEventListener('click', function () {
                        const greetingStage = document.getElementById('bonbonQuizGreetingStage');
                        const messageStage = document.getElementById('bonbonQuizMessageStage');
                        if (greetingStage) greetingStage.style.display = 'none';
                        if (messageStage) messageStage.style.display = 'block';
                        typeQuizMessage();
                    });
                }
            });
        </script>

        <script>
            var _bbActGreetingFinish = null;
            var _bbActMsgFinish = null;

            function startBonbonActivityGreetingTypewriter() {
                const el = document.getElementById('bonbonMessage-activity-greeting');
                if (!el || el.dataset.typed) return;
                el.dataset.typed = '1';

                const message = <?= json_encode($bonbonActivityGreeting ?? '') ?>;
                const speed = 28;
                let i = 0;
                let done = false;

                const cursor = document.createElement('span');
                cursor.className = 'typing-cursor';
                el.appendChild(cursor);

                const skipEl = document.getElementById('skipActivityGreetingTyping');
                if (skipEl) skipEl.style.display = 'flex';

                function finish() {
                    if (done) return;
                    done = true;
                    cursor.remove();
                    el.textContent = message;
                    if (skipEl) skipEl.style.display = 'none';
                    const btn = document.getElementById('btnActivityContinue');
                    if (btn) btn.classList.add('btn-visible');
                }
                _bbActGreetingFinish = finish;

                function type() {
                    if (done) return;
                    if (i < message.length) {
                        cursor.insertAdjacentText('beforebegin', message.charAt(i));
                        i++;
                        setTimeout(type, speed);
                    } else {
                        setTimeout(finish, 200);
                    }
                }
                type();
            }

            function skipBonbonActivityGreeting() {
                if (_bbActGreetingFinish) _bbActGreetingFinish();
            }

            function startBonbonActivityTypewriter() {
                const el = document.getElementById('bonbonMessage-activity');
                if (!el || el.dataset.typed) return;
                el.dataset.typed = '1';

                const message = <?= json_encode($bonbonActivityMessage ?? '') ?>;
                const speed = 28;
                let i = 0;
                let done = false;

                const cursor = document.createElement('span');
                cursor.className = 'typing-cursor';
                el.appendChild(cursor);

                const skipEl = document.getElementById('skipActivityMsgTyping');
                if (skipEl) skipEl.style.display = 'flex';

                function finish() {
                    if (done) return;
                    done = true;
                    cursor.remove();
                    el.textContent = message;
                    if (skipEl) skipEl.style.display = 'none';
                    const btn = document.querySelector('#bonbonActivityMessageStage .btn-take-quiz');
                    if (btn) btn.classList.add('btn-visible');
                }
                _bbActMsgFinish = finish;

                function type() {
                    if (done) return;
                    if (i < message.length) {
                        cursor.insertAdjacentText('beforebegin', message.charAt(i));
                        i++;
                        setTimeout(type, speed);
                    } else {
                        setTimeout(finish, 200);
                    }
                }
                type();
            }

            function skipBonbonActivityMsg() {
                if (_bbActMsgFinish) _bbActMsgFinish();
            }

            onDomReady(function () {
                if (!document.getElementById('bonbonMessage-activity-greeting')) return;

                startBonbonActivityGreetingTypewriter();

                const continueBtn = document.getElementById('btnActivityContinue');
                if (continueBtn) {
                    continueBtn.addEventListener('click', function () {
                        const greetingStage = document.getElementById('bonbonActivityGreetingStage');
                        const messageStage = document.getElementById('bonbonActivityMessageStage');
                        if (greetingStage) greetingStage.style.display = 'none';
                        if (messageStage) messageStage.style.display = 'block';
                        startBonbonActivityTypewriter();
                    });
                }
            });
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

            onDomReady(startBonbonBubble2Typewriter);
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
            fetch('/learning_management/public/?url=mark_flashcards_viewed', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'lesson_id=' + <?= (int) $lessonId ?>
            });
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
                }, $quizData))) ?>,
                dragdrops: <?= json_encode(array_map(function ($title, $d) {
                    return [
                        'title' => $title,
                        'required' => count($d['items']),
                        'done' => ($d['submission'] !== null),
                    ];
                }, array_keys($dragDropData), $dragDropData)) ?>
            };

            (function () {
                if (typeof LESSON_DATA === 'undefined' || !LESSON_DATA.lessonId) return;
                var flagKey = 'quiz_open_lesson_' + LESSON_DATA.lessonId;

                if (sessionStorage.getItem(flagKey) === '1') {
                    document.addEventListener('DOMContentLoaded', function () {
                        if (document.getElementById('section-quizzes')) {
                            openQuizStage();
                        }
                    });
                }
            })();

            // PASTE THE ACTIVITY IIFE HERE — this is the position that actually works
            (function () {
                if (typeof LESSON_DATA === 'undefined' || !LESSON_DATA.lessonId) return;
                var actFlagKey = 'activity_open_lesson_' + LESSON_DATA.lessonId;

                if (sessionStorage.getItem(actFlagKey) === '1') {
                    document.addEventListener('DOMContentLoaded', function () {
                        if (document.getElementById('section-activity-stage')) {
                            openActivityStage();
                        }
                    });
                }
            })();

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
                        shell.classList.remove('shell-scroll-lock');
                        startBonbonBubble2Typewriter();
                        startBonbonBubble3Typewriter();
                        startBonbonQuizTypewriter();
                    }, 460);
                });
            })();
        </script>

        <script>
            document.querySelectorAll('.dd-stage-section').forEach(function (section) {
                var wrap = section.previousElementSibling; // content-quiz-cta
                if (!wrap || !wrap.classList.contains('content-quiz-cta')) return;

                var greetEl = wrap.querySelector('.bonbon-dd-greeting-text');
                var msgEl = wrap.querySelector('.bonbon-dd-message-text');
                var greetStage = wrap.querySelector('.bonbon-dd-greeting-stage');
                var msgStage = wrap.querySelector('.bonbon-dd-message-stage');
                var continueBtn = wrap.querySelector('.btn-dd-continue');
                var skipGreet = wrap.querySelector('.skip-dd-greeting');
                var skipMsg = wrap.querySelector('.skip-dd-msg');

                function typeInto(el, text, onDone, skipEl) {
                    if (el.dataset.typed) return;
                    el.dataset.typed = '1';
                    var i = 0, done = false;
                    var cursor = document.createElement('span');
                    cursor.className = 'typing-cursor';
                    el.appendChild(cursor);
                    if (skipEl) skipEl.style.display = 'flex';

                    function finish() {
                        if (done) return;
                        done = true;
                        cursor.remove();
                        el.textContent = text;
                        if (skipEl) skipEl.style.display = 'none';
                        if (onDone) onDone();
                    }
                    if (skipEl) skipEl.onclick = finish;

                    (function type() {
                        if (done) return;
                        if (i < text.length) { cursor.insertAdjacentText('beforebegin', text.charAt(i)); i++; setTimeout(type, 28); }
                        else setTimeout(finish, 200);
                    })();
                }

                typeInto(greetEl, <?php /* not php here, static JS below */ ?> greetEl.dataset.msg || greetEl.textContent, function () {
                    if (continueBtn) continueBtn.classList.add('btn-visible');
            }, skipGreet);

            if (continueBtn) {
                continueBtn.addEventListener('click', function () {
                    greetStage.style.display = 'none';
                    msgStage.style.display = 'block';
                    typeInto(msgEl, msgEl.dataset.msg || msgEl.textContent, function () {
                        var takeBtn = wrap.querySelector('.btn-take-quiz');
                        if (takeBtn) takeBtn.classList.add('btn-visible');
                    }, skipMsg);
                });
            }
            });
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
                        startBonbonBubble3Typewriter();
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
                document.addEventListener('DOMContentLoaded', function () {
                    var shell = document.getElementById('lessonsShell');
                    var isSkip = document.documentElement.classList.contains('skip-splash');
                    if (shell && !isSkip) {
                        shell.classList.add('shell-scroll-lock');
                    }
                });
            </script>
        <?php endif; ?>

        <script src="../js_folder/lessons.js?v=<?= time() ?>"></script>
        <script defer src="../bootstrap_folder/js/bootstrap.bundle.min.js"></script>
</body>


</html>