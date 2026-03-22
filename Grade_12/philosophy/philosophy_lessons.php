<?php
// Variables passed from StudentsController::subject_lessons():
// $module, $lessons, $lessonId, $lesson
// $images, $videos, $flashcards, $activities, $quizzes
// $activityData, $quizData
// $lessonCompletion, $completedCount, $totalLessons
// $subject, $moduleId, $studentId

function youtubeEmbed(string $url): string
{
    $id = '';
    if (preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $m))
        $id = $m[1];
    elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $m))
        $id = $m[1];
    return $id ? "https://www.youtube.com/embed/{$id}" : $url;
}

$progressPct = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
$currentIndex = 1;
$prevLessonId = null;
$nextLessonId = null;
foreach ($lessons as $i => $l) {
    if ($l['id'] == $lessonId) {
        $currentIndex = $i + 1;
        $prevLessonId = $lessons[$i - 1]['id'] ?? null;
        $nextLessonId = $lessons[$i + 1]['id'] ?? null;
        break;
    }
}

function lUrl(string $subject, int $moduleId, int $lessonId): string
{
    return "/learning_management/public/?url=subject_lessons&subject={$subject}&id={$moduleId}&lesson={$lessonId}";
}
?>

<style>
    /* ── INLINE LESSON CONTENT ── */
    .lesson-section {
        margin-bottom: 28px;
    }

    .lesson-section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--green);
        margin: 0 0 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--green-light, #f0fdf4);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── VIDEOS ── */
    .video-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 16px;
        background: #fff;
    }

    .video-card iframe {
        width: 100%;
        height: 280px;
        display: block;
        border: none;
    }

    .video-info {
        padding: 12px 16px;
    }

    .video-title {
        font-weight: 600;
        font-size: 14px;
        margin: 0;
        color: #1a1a2e;
    }

    /* ── IMAGES ── */
    .db-images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 14px;
    }

    .db-image-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        cursor: pointer;
        transition: box-shadow .2s;
    }

    .db-image-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, .09);
    }

    .db-image-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .db-image-caption {
        padding: 10px 12px;
        font-size: 13px;
        color: #555;
    }

    /* ── FLASHCARDS ── */
    .fc-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 14px;
    }

    .fc-item {
        height: 140px;
        perspective: 1000px;
        cursor: pointer;
    }

    .fc-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        transition: transform .4s;
        border-radius: 10px;
    }

    .fc-item.flipped .fc-inner {
        transform: rotateY(180deg);
    }

    .fc-front,
    .fc-back {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 1px solid #e5e7eb;
    }

    .fc-front {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .fc-back {
        background: #faf5ff;
        border-color: #e9d5ff;
        transform: rotateY(180deg);
    }

    .fc-label {
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        opacity: .5;
        margin-bottom: 5px;
        display: block;
    }

    .fc-front .fc-label {
        color: #065f46;
    }

    .fc-back .fc-label {
        color: #6b21a8;
    }

    .fc-text {
        font-size: 14px;
        font-weight: 600;
        line-height: 1.4;
        color: #065f46;
        display: block;
    }

    .fc-back .fc-text {
        color: #4c1d95;
    }

    .fc-hint {
        font-size: 10px;
        color: #aaa;
        margin-top: 6px;
        display: block;
    }

    /* ── ACTIVITY ── */
    .activity-block {
        margin-bottom: 24px;
    }

    .activity-intro {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 16px;
    }

    .activity-intro-icon {
        font-size: 28px;
        flex-shrink: 0;
    }

    .activity-intro-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 4px;
    }

    .activity-intro-desc {
        font-size: 13px;
        color: #555;
        margin: 0;
    }

    .activity-meta-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .meta-pill {
        font-size: 11px;
        font-weight: 500;
        padding: 3px 10px;
        border-radius: 20px;
    }

    .pill-purple {
        background: #ede9ff;
        color: #5c44e8;
    }

    .pill-green {
        background: #ecfdf5;
        color: #065f46;
    }

    .pill-gray {
        background: #f1f5f9;
        color: #64748b;
    }

    .submitted-notice {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        color: #15803d;
        font-size: 14px;
        font-weight: 600;
    }

    .submitted-notice i {
        font-size: 24px;
        display: block;
        margin-bottom: 8px;
    }

    .activity-question {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 12px;
        background: #fff;
    }

    .aq-num {
        font-size: 11px;
        font-weight: 700;
        color: var(--orange, #f59e0b);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin: 0 0 5px;
    }

    .aq-text {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 12px;
        line-height: 1.5;
    }

    .activity-answer {
        width: 100%;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        font-family: 'Poppins', sans-serif;
        resize: vertical;
        min-height: 80px;
        box-sizing: border-box;
        transition: border-color .2s;
    }

    .activity-answer:focus {
        outline: none;
        border-color: #f59e0b;
    }

    .mc-choices {
        display: grid;
        gap: 8px;
    }

    .mc-label {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        cursor: pointer;
        font-size: 14px;
        transition: border-color .2s, background .2s;
    }

    .mc-label:hover {
        border-color: #f59e0b;
        background: #fffbeb;
    }

    .mc-label input[type="radio"] {
        display: none;
    }

    .mc-letter {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: #555;
        flex-shrink: 0;
    }

    .activity-submit-row {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px;
    }

    .btn-submit-activity {
        padding: 10px 24px;
        border-radius: 22px;
        border: none;
        background: #f59e0b;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(245, 158, 11, .35);
        transition: transform .15s;
    }

    .btn-submit-activity:hover {
        transform: translateY(-1px);
    }

    /* ── QUIZ ── */
    .quiz-block {
        margin-bottom: 24px;
    }

    .quiz-intro {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 16px;
    }

    .quiz-intro-icon {
        font-size: 28px;
        flex-shrink: 0;
    }

    .quiz-intro-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 4px;
    }

    .quiz-intro-desc {
        font-size: 13px;
        color: #555;
        margin: 0;
    }

    .quiz-result {
        text-align: center;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
        padding: 24px;
        margin-bottom: 16px;
    }

    .result-score {
        font-size: 25px;
        font-weight: 800;
        color: var(--green);
        line-height: 1;
        margin-bottom: 8px;
    }

    .result-label {
        font-size: 13.5px;
        color: #555;
        margin: 0 0 8px;
    }

    .result-badge {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
    }

    .badge-pass {
        background: #ecfdf5;
        color: #065f46;
    }

    .badge-fail {
        background: #fef2f2;
        color: #991b1b;
    }

    .q-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px 20px;
        margin-bottom: 14px;
        background: #fff;
    }

    .q-number {
        font-size: 11px;
        font-weight: 700;
        color: var(--green);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin: 0 0 6px;
    }

    .q-text {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a2e;
        margin: 0 0 14px;
        line-height: 1.5;
    }

    .q-choices {
        display: grid;
        gap: 8px;
    }

    .q-choice {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1.5px solid #e5e7eb;
        cursor: pointer;
        font-size: 14px;
        color: #333;
        transition: border-color .2s, background .2s;
        user-select: none;
    }

    .q-choice:hover {
        border-color: var(--green);
        background: var(--green-light, #f0fdf4);
    }

    .q-choice.selected {
        border-color: var(--green);
        background: var(--green-light, #f0fdf4);
        color: var(--green-dark, #065f46);
        font-weight: 500;
    }

    .choice-letter {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        color: #555;
        transition: background .2s, color .2s;
    }

    .q-choice.selected .choice-letter {
        background: var(--green);
        color: #fff;
    }

    .quiz-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
        padding: 0 0.5rem;
    }

    .quiz-nav button{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .quiz-status {
        font-size: 13px;
        color: #888;
    }

    .quiz-dots {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .q-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #e5e7eb;
        transition: background .2s;
    }

    .q-dot.dot-answered {
        background: #a78bfa;
    }

    .q-dot.dot-active {
        background: var(--green);
    }

    .btn-quiz-next,
    .btn-submit-quiz {
        padding: 10px 22px;
        border-radius: 22px;
        border: none;
        background: var(--green);
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: transform .15s;
    }

    .btn-quiz-next:disabled {
        background: #d1d5db;
        cursor: not-allowed;
    }

    .btn-quiz-next:not(:disabled):hover,
    .btn-submit-quiz:hover {
        transform: translateY(-1px);
    }

    .btn-quiz-prev {
        padding: 10px 20px;
        border-radius: 22px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        color: #555;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: border-color .2s;
    }

    .btn-quiz-prev:hover {
        border-color: var(--green);
        color: var(--green);
    }

    /* ── SIDEBAR ACTIVE/DONE (unchanged) ── */
    .sidebar-menu li.active-lesson>a {
        color: var(--green) !important;
        font-weight: 600 !important;
    }

    .sidebar-menu li.active-lesson .fa {
        color: var(--green) !important;
    }

    .sidebar-menu li.done-lesson .fa-check {
        color: var(--green) !important;
    }

    .sidebar-menu li .fa-circle {
        color: var(--green) !important;
    }

    /* ── LIGHTBOX ── */
    .db-lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .86);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .db-lightbox.open {
        display: flex;
    }

    .db-lightbox img {
        max-width: 92vw;
        max-height: 90vh;
        border-radius: 10px;
    }

    .db-lightbox-close {
        position: absolute;
        top: 20px;
        right: 26px;
        color: #fff;
        font-size: 28px;
        cursor: pointer;
        background: none;
        border: none;
    }
</style>

<!-- ── NAVBAR (progress unchanged) ── -->
<div class="navbar-lessons">
    <div class="navbar-title">
        <!-- <a href="/learning_management/public/?url=modules&subject=<?= htmlspecialchars($subject) ?>">
            <i class="fa fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <span>|</span>
        <p><?= htmlspecialchars($module['title'] ?? 'Module') ?></p> -->
    </div>
    <div class="navbar-progress">
        <div class="progress-title">
            <p>Module Progress</p>
            <span id="progressPercent"><?= $progressPct ?>%</span>
        </div>
        <div class="parent-progress">
            <div class="progress-lesson" id="progressBar" style="width:<?= $progressPct ?>%"></div>
        </div>
    </div>
</div>

<!-- ── MODULE TITLE CARD ── -->
<style>
    .module-header-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin: 1.2rem 1.5rem;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
    }

    .module-header-card .module-header-banner {
        height: 10px;
        background: linear-gradient(90deg, var(--green) 0%, #34d399 100%);
    }

    .module-header-card .module-header-body {
        padding: 20px 24px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .module-header-card .module-header-left {
        flex: 1;
        min-width: 0;
    }

    .module-header-card .module-header-tag {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--green);
        margin: 0 0 6px;
    }

    .module-header-card .module-header-title {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0 0 8px;
        line-height: 1.3;
    }

    .module-header-card .module-header-desc {
        font-size: 13.5px;
        color: #555;
        margin: 0;
        line-height: 1.6;
    }

    .module-header-card .module-header-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        flex-shrink: 0;
    }

    .module-header-card .module-stat {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #666;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 5px 14px;
    }

    .module-header-card .module-stat i {
        color: var(--green);
        font-size: 12px;
    }

    .module-stat-num {
        font-weight: 700;
        color: #1a1a2e;
    }
</style>

<div class="module-header-card">
    <div class="module-header-banner"></div>
    <div class="module-header-body">
        <div class="module-header-left">
            <p class="module-header-tag">📚 <?= htmlspecialchars($module['subject_name'] ?? 'Module') ?></p>
            <h2 class="module-header-title"><?= htmlspecialchars($module['title'] ?? '') ?></h2>
            <?php if (!empty($module['description'])): ?>
                <p class="module-header-desc"><?= htmlspecialchars($module['description']) ?></p>
            <?php endif; ?>
        </div>
        <div class="module-header-right">
            <div class="module-stat">
                <i class="fa fa-book-open"></i>
                <span><span class="module-stat-num"><?= $totalLessons ?></span> Lessons</span>
            </div>
            <div class="module-stat">
                <i class="fa fa-check-circle"></i>
                <span><span class="module-stat-num"><?= $completedCount ?></span> Completed</span>
            </div>
        </div>
    </div>
</div>

<!-- ── BODY ── -->
<div class="body-lessons">
    <h2 style="display:none;"><?= htmlspecialchars($module['title'] ?? '') ?></h2>

    <!-- SIDEBAR (unchanged) -->
    <div class="sidebar-lessons">
        <h4>Lessons</h4>
        <div class="sidebar-menu">
            <ul>
                <?php foreach ($lessons as $i => $l):
                    $isActive = ($l['id'] == $lessonId);
                    $isDone = $lessonCompletion[$l['id']] ?? false;
                    $cls = trim(($isActive ? 'active-lesson ' : '') . ($isDone ? 'done-lesson' : ''));
                    $lessonNum = $i + 1;
                    // Strip existing "Lesson N:" prefix if present, then add sequential number
                    $rawTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $l['title']);
                    ?>
                    <li class="<?= $cls ?>">
                        <a href="<?= lUrl($subject, $moduleId, $l['id']) ?>">
                            <?php if ($isDone): ?>
                                <i class="fa fa-check lesson-icon-status"></i>
                            <?php else: ?>
                                <i class="fa fa-circle lesson-icon-status"></i>
                            <?php endif; ?>
                            <span>Lesson <?= $lessonNum ?>: <?= htmlspecialchars($rawTitle) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- VIEW LESSONS -->
    <div class="view-lessons">

        <?php if (!$lesson): ?>
            <div style="text-align:center;padding:60px 40px;color:#aaa;">
                <i class="fa fa-book-open" style="font-size:32px;display:block;margin-bottom:10px;"></i>
                <p style="font-size:14px;margin:0;">Choose a lesson from the sidebar.</p>
            </div>
        <?php else: ?>

            <div class="view-title">
                <i class="fa fa-book-open"></i>
                <span id="lesson-count">Lesson <?= $currentIndex ?> of <?= $totalLessons ?></span>
            </div>

            <?php $cleanTitle = preg_replace('/^Lesson\s*\d+\s*:\s*/i', '', $lesson['title']); ?>
            <h3 id="lesson-title">Lesson <?= $currentIndex ?>: <?= htmlspecialchars($cleanTitle) ?></h3>

            <!-- ── INLINE CONTENT STARTS HERE ── -->
            <div class="view-lessons-body" style="padding: 20px 1.9rem;">

                <!-- 1. LESSON TEXT CONTENT -->
                <?php if (!empty($lesson['content'])): ?>
                    <div class="lesson-section">
                        <?= $lesson['content'] ?>
                    </div>
                <?php endif; ?>

                <!-- 2. VIDEOS -->
                <?php if (!empty($videos)): ?>
                    <div class="lesson-section">
                        <p class="lesson-section-title"><i class="fa fa-video"></i> Videos</p>
                        <?php foreach ($videos as $vid): ?>
                            <div class="video-card">
                                <iframe src="<?= htmlspecialchars(youtubeEmbed($vid['video_url'])) ?>" allowfullscreen
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                </iframe>
                                <?php if (!empty($vid['title'])): ?>
                                    <div class="video-info">
                                        <p class="video-title"><?= htmlspecialchars($vid['title']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- 3. IMAGES -->
                <?php if (!empty($images)): ?>
                    <div class="lesson-section">
                        <p class="lesson-section-title"><i class="fa fa-image"></i> Images</p>
                        <div class="db-images-grid">
                            <?php foreach ($images as $img): ?>
                                <div class="db-image-card" onclick="dbLightbox('<?= htmlspecialchars($img['image_path']) ?>')">
                                    <img src="<?= htmlspecialchars($img['image_path']) ?>"
                                        alt="<?= htmlspecialchars($img['caption'] ?? '') ?>" loading="lazy">
                                    <?php if (!empty($img['caption'])): ?>
                                        <div class="db-image-caption"><?= htmlspecialchars($img['caption']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 4. FLASHCARDS -->
                <?php if (!empty($flashcards)): ?>
                    <div class="lesson-section">
                        <p class="lesson-section-title"><i class="fa fa-clone"></i> Flashcards</p>
                        <div class="fc-grid">
                            <?php foreach ($flashcards as $fc): ?>
                                <div class="fc-item" onclick="this.classList.toggle('flipped')">
                                    <div class="fc-inner">
                                        <div class="fc-front">
                                            <span class="fc-label">Question</span>
                                            <span class="fc-text"><?= htmlspecialchars($fc['front']) ?></span>
                                            <span class="fc-hint">Tap to reveal</span>
                                        </div>
                                        <div class="fc-back">
                                            <span class="fc-label">Answer</span>
                                            <span class="fc-text"><?= htmlspecialchars($fc['back']) ?></span>
                                            <span class="fc-hint">Tap to go back</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 5. ACTIVITIES -->
                <?php if (!empty($activityData)): ?>
                    <div class="lesson-section">
                        <p class="lesson-section-title"><i class="fa fa-pencil-alt"></i> Activities</p>
                        <?php foreach ($activityData as $actId => $data):
                            $act = $data['activity'];
                            $questions = $data['questions'];
                            $submission = $data['submission'];
                            ?>
                            <div class="activity-block">
                                <div class="activity-intro">
                                    <div class="activity-intro-icon">✏️</div>
                                    <div>
                                        <p class="activity-intro-title"><?= htmlspecialchars($act['title']) ?></p>
                                        <?php if (!empty($act['instructions'])): ?>
                                            <p class="activity-intro-desc"><?= htmlspecialchars($act['instructions']) ?></p>
                                        <?php endif; ?>
                                        <div class="activity-meta-pills">
                                            <span class="meta-pill pill-purple"><?= count($questions) ?> Questions</span>
                                            <span class="meta-pill pill-green">⭐ <?= (int) $act['total_points'] ?> pts</span>
                                            <?php if ($act['time_limit']): ?>
                                                <span class="meta-pill pill-gray">⏱ <?= (int) $act['time_limit'] ?> min</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($submission): ?>
                                    <div class="submitted-notice">
                                        <i class="fa fa-check-circle"></i>
                                        Activity submitted! Your answers have been recorded.
                                    </div>
                                <?php else: ?>
                                    <div class="activity-answers-form" data-act-id="<?= (int) $act['id'] ?>">

                                        <?php foreach ($questions as $qi => $q): ?>
                                            <div class="activity-question">
                                                <p class="aq-num">Question <?= $qi + 1 ?></p>
                                                <p class="aq-text"><?= htmlspecialchars($q['question']) ?></p>
                                                <?php if ($q['question_type'] === 'multiple_choice'): ?>
                                                    <div class="mc-choices">
                                                        <?php
                                                        $ltrs = ['A', 'B', 'C', 'D'];
                                                        $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                                        $li = 0;
                                                        foreach ($ch as $key => $val):
                                                            if ($val === null)
                                                                continue; ?>
                                                            <label class="mc-label"
                                                                onclick="lessonPickMC(this, <?= (int) $q['id'] ?>, '<?= $key ?>')">
                                                                <span class="mc-letter"><?= $ltrs[$li++] ?></span>
                                                                <?= htmlspecialchars($val) ?>
                                                            </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <textarea class="activity-answer" data-qid="<?= (int) $q['id'] ?>"
                                                        placeholder="Write your answer here..." rows="3"
                                                        oninput="lessonTextAnswer(this, <?= (int) $q['id'] ?>)"></textarea>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- 6. QUIZZES — all merged into one paginated flow -->
                <?php if (!empty($quizData)): ?>
                    <div class="lesson-section">
                        <p class="lesson-section-title"><i class="fa fa-clipboard-list"></i> Quiz</p>

                        <?php
                        // Collect ALL questions across all quizzes into one flat list
                        $allQzQuestions = [];
                        $firstQzDone = true; // assume done unless one isn't
                        foreach ($quizData as $qzId => $data) {
                            if (!$data['result'])
                                $firstQzDone = false;
                            foreach ($data['questions'] as $q) {
                                $allQzQuestions[] = [
                                    'q' => $q,
                                    'qzId' => (int) $qzId,
                                    'uid' => 'dbqz_' . $qzId,
                                    'passing_score' => (int) $data['quiz']['passing_score'],
                                    'result' => $data['result'],
                                    'quiz' => $data['quiz'],
                                ];
                            }
                        }
                        $grandTotal = count($allQzQuestions);

                        // Show quiz intro from first quiz
                        $firstData = reset($quizData);
                        $firstQz = $firstData['quiz'];
                        ?>

                        <div class="quiz-intro">
                            <div class="quiz-intro-icon">📋</div>
                            <div>
                                <p class="quiz-intro-title"><?= htmlspecialchars($firstQz['title']) ?></p>
                                <?php if (!empty($firstQz['instructions'])): ?>
                                    <p class="quiz-intro-desc"><?= htmlspecialchars($firstQz['instructions']) ?></p>
                                <?php endif; ?>
                                <div class="activity-meta-pills">
                                    <span class="meta-pill pill-purple"><?= $grandTotal ?> Questions</span>
                                    <?php if ($firstQz['time_limit']): ?>
                                        <span class="meta-pill pill-gray">⏱ <?= (int) $firstQz['time_limit'] ?> min</span>
                                    <?php endif; ?>
                                    <span class="meta-pill pill-green">Passing: <?= (int) $firstQz['passing_score'] ?>%</span>
                                </div>
                            </div>
                        </div>

                        <?php if ($firstQzDone): ?>
                                <?php
                                // Show results for each quiz
                                foreach ($quizData as $qzId => $data):
                                    if (!$data['result'])
                                        continue;
                                    $pct = $data['result']['total'] > 0
                                        ? round(($data['result']['score'] / $data['result']['total']) * 100) : 0;
                                    ?>
                                    <div class="quiz-result">
                                        <div class="result-score"><?= $pct ?>%</div>
                                        <p class="result-label">You scored <?= (int) $data['result']['score'] ?> out of <?= (int) $data['result']['total'] ?></p>
                                        <span class="result-badge <?= $data['result']['passed'] ? 'badge-pass' : 'badge-fail' ?>">
                                            <?= $data['result']['passed'] ? '✅ Passed!' : '❌ Failed — keep studying!' ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                        <?php else: ?>
                                <?php
                                $ltrs = ['A', 'B', 'C', 'D'];
                                foreach ($allQzQuestions as $qi => $item):
                                    $q = $item['q'];
                                    $uid = $item['uid'];
                                    $ch = ['a' => $q['choice_a'], 'b' => $q['choice_b'], 'c' => $q['choice_c'], 'd' => $q['choice_d']];
                                    ?>
                                    <div class="q-card unified-q-card"
                                         id="unified_q<?= $qi ?>"
                                         data-uid="<?= $uid ?>"
                                         data-qzid="<?= $item['qzId'] ?>"
                                         style="<?= $qi > 0 ? 'display:none;' : '' ?>">
                                        <p class="q-number">Question <?= $qi + 1 ?> of <?= $grandTotal ?></p>
                                        <p class="q-text"><?= htmlspecialchars($q['question']) ?></p>
                                        <div class="q-choices">
                                            <?php $li = 0;
                                            foreach ($ch as $key => $val):
                                                if ($val === null)
                                                    continue; ?>
                                                <div class="q-choice unified-choice"
                                                     data-qi="<?= $qi ?>"
                                                     data-qid="<?= (int) $q['id'] ?>"
                                                     data-key="<?= $key ?>"
                                                     data-uid="<?= $uid ?>"
                                                     data-qzid="<?= $item['qzId'] ?>"
                                                     onclick="unifiedPick(this)">
                                                    <span class="choice-letter"><?= $ltrs[$li++] ?></span>
                                                    <?= htmlspecialchars($val) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="quiz-nav">
                                    <span class="quiz-status" id="unified_status">0 / <?= $grandTotal ?> answered</span>
                                    <div style="display:flex;gap:8px;align-items:center;">
                                        <button class="btn-quiz-prev" id="unified_prev" style="display:none;"
                                                onclick="unifiedNav(-1)">
                                            <i class="fa fa-chevron-left"></i> Prev
                                        </button>
                                        <button class="btn-quiz-next" id="unified_next" disabled
                                                onclick="unifiedNav(1)">
                                            Next <i class="fa fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div><!-- /view-lessons-body -->

            <hr>

            <!-- FOOTER NAV -->
            <div class="view-lessons-footer">

                <!-- Lock notice — hidden by JS when all answered -->
                <div id="lessonLockNotice"
                     style="display:none;background:#fff7ed;border:1px solid #fed7aa;border-radius:10px;padding:12px 18px;margin-bottom:14px;font-size:13px;color:#c2410c;align-items:center;gap:8px;">
                    <i class="fa fa-lock"></i>
                    <span>Answer all activities and quizzes in this lesson to proceed.</span>
                </div>

                <nav aria-label="Lesson navigation">
                    <ul class="pagination m-0">
                        <li class="page-item">
                            <a class="page-link <?= !$prevLessonId ? 'disabled' : '' ?>"
                               href="<?= $prevLessonId ? lUrl($subject, $moduleId, $prevLessonId) : '#' ?>"
                               id="prevBtn">
                                <i class="fa fa-chevron-left"></i>
                                <span>Previous Lesson</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <span id="page-indicator"><?= $currentIndex ?> / <?= $totalLessons ?></span>
                        </li>
                        <li class="page-item">
                            <?php if ($nextLessonId): ?>
                                    <a class="page-link" href="<?= lUrl($subject, $moduleId, $nextLessonId) ?>" id="nextBtn">
                                        <span>Next Lesson</span>
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                            <?php else: ?>
                                    <a class="page-link" href="#" id="nextBtn"
                                       style="background:var(--green) !important;color:#fff !important;border-color:var(--green) !important;font-weight:600;">
                                        <span>Finish</span>
                                        <i class="fa fa-check"></i>
                                    </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </nav>
            </div>

        <?php endif; ?>
    </div><!-- /view-lessons -->
</div><!-- /body-lessons -->

<!-- LIGHTBOX -->
<div class="db-lightbox" id="dbLightbox" onclick="dbLightboxClose()">
    <button class="db-lightbox-close" onclick="dbLightboxClose()"><i class="fa fa-times"></i></button>
    <img id="dbLightboxImg" src="" alt="">
</div>

<!-- NO <script> TAG HERE — all JS is in philosophy.js which subject_lessons.php loads -->
<script>
// PHP-provided data for JS answer tracking
var LESSON_DATA = {
    lessonId:   <?= (int) $lessonId ?>,
    moduleId:   <?= (int) $moduleId ?>,
    subject:    '<?= htmlspecialchars($subject) ?>',
    activities: <?= json_encode(array_values(array_map(function ($d) {
        return [
            'id' => (int) $d['activity']['id'],
            'required' => count($d['questions']),
            'done' => ($d['submission'] !== null)
        ];
    }, $activityData))) ?>,
    quizzes: <?= json_encode(array_values(array_map(function ($d) {
        return [
            'id' => (int) $d['quiz']['id'],
            'uid' => 'dbqz_' . $d['quiz']['id'],
            'required' => count($d['questions']),
            'passing_score' => (int) $d['quiz']['passing_score'],
            'done' => ($d['result'] !== null)
        ];
    }, $quizData))) ?>
};
</script>