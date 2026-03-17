<style>
    /* ── TABS ── */
    .view-tabs {
        display: flex;
        gap: 0;
        border-bottom: 1px solid #e5e7eb;
        background: #fafafa;
        padding: 0 4px;
        margin: 1rem 0 0;
        overflow-x: auto;
    }

    .tab-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 12px 18px;
        font-size: 13px;
        font-weight: 500;
        color: #666;
        cursor: pointer;
        border: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        background: none;
        font-family: 'Poppins', sans-serif;
        white-space: nowrap;
        transition: color 0.2s, border-color 0.2s;
    }

    .tab-btn:hover {
        color: #333;
    }

    .tab-btn.active-tab {
        color: var(--green);
        border-bottom-color: var(--green);
        font-weight: 600;
    }

    .tab-count-badge {
        background: #e5e7eb;
        color: #666;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 10px;
    }

    .tab-btn.active-tab .tab-count-badge {
        background: var(--green-light);
        color: var(--green);
    }

    .tab-panel {
        display: none;
        padding: 20px 0;
    }

    /* ── VIDEOS ── */
    .video-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 16px;
        background: #fff;
    }

    .video-thumb iframe {
        width: 100%;
        height: 280px;
        display: block;
    }

    .video-info {
        padding: 12px 16px;
    }

    .video-title {
        font-weight: 600;
        font-size: 14px;
        margin: 0 0 4px;
        color: #1a1a2e;
    }

    .video-meta {
        font-size: 12px;
        color: #888;
    }

    /* ── IMAGES ── */
    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 14px;
    }

    .image-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }

    .image-card img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        display: block;
    }

    .image-info {
        padding: 10px 12px;
    }

    .image-title {
        font-weight: 600;
        font-size: 13px;
        margin: 0 0 3px;
        color: #1a1a2e;
    }

    .image-desc {
        font-size: 12px;
        color: #888;
    }

    /* ── EMPTY ── */
    .empty-tab {
        text-align: center;
        padding: 40px;
        color: #aaa;
    }

    .empty-tab i {
        font-size: 32px;
        display: block;
        margin-bottom: 10px;
    }

    .empty-tab p {
        font-size: 14px;
        margin: 0;
    }

    /* ── ACTIVITY ── */
    .activity-intro {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
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

    .pill-red {
        background: #fef2f2;
        color: #991b1b;
    }

    .activity-question {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px 18px;
        margin-bottom: 14px;
        background: #fff;
    }

    .aq-num {
        font-size: 11px;
        font-weight: 700;
        color: var(--orange);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin: 0 0 6px;
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
        transition: border-color 0.2s;
    }

    .activity-answer:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255, 138, 101, 0.1);
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
        transition: border-color 0.2s, background 0.2s;
    }

    .mc-label:hover {
        border-color: var(--orange);
        background: #fff8f5;
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

    .activity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        padding: 0 1rem
    }

    .btn-submit-activity {
        padding: 10px 24px;
        border-radius: 22px;
        border: none;
        background: var(--orange);
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(255, 138, 101, 0.35);
        transition: transform 0.15s;
    }

    .btn-submit-activity:hover {
        transform: translateY(-1px);
    }

    .activity-success {
        text-align: center;
        padding: 40px 20px;
    }

    .success-icon {
        font-size: 52px;
        margin-bottom: 12px;
    }

    .activity-success h4 {
        font-size: 20px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .activity-success p {
        font-size: 14px;
        color: #555;
    }

    .success-badge {
        display: inline-block;
        margin-top: 14px;
        padding: 6px 18px;
        border-radius: 20px;
        background: #ecfdf5;
        color: #065f46;
        font-size: 13px;
        font-weight: 600;
    }

    /* ── QUIZ ── */
    .quiz-intro {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
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
        margin-bottom: 20px;
    }

    .result-score {
        font-size: 44px;
        font-weight: 800;
        color: var(--green);
        line-height: 1;
        margin-bottom: 8px;
    }

    .result-label {
        font-size: 14px;
        color: #555;
        margin: 0 0 8px;
    }

    .result-badge {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 20px;
        font-size: 13px;
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

    .quiz-page-indicator {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding: 0 1rem;
    }

    .quiz-page-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
    }

    .quiz-dots {
        display: flex;
        gap: 6px;
    }

    .q-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #e5e7eb;
    }

    .q-dot.dot-answered {
        background: #a78bfa;
    }

    .q-dot.dot-active {
        background: var(--green);
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
        letter-spacing: 0.06em;
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
        transition: border-color 0.2s, background 0.2s;
    }

    .q-choice:hover {
        border-color: var(--green);
        background: var(--green-light);
    }

    .q-choice.selected {
        border-color: var(--green);
        background: var(--green-light);
        color: var(--green-dark);
        font-weight: 500;
    }

    .q-choice.correct {
        border-color: #10b981;
        background: #ecfdf5;
        color: #065f46;
        font-weight: 500;
    }

    .q-choice.wrong {
        border-color: #ef4444;
        background: #fef2f2;
        color: #991b1b;
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
    }

    .q-choice.selected .choice-letter {
        background: var(--green);
        color: #fff;
    }

    .q-choice.correct .choice-letter {
        background: #10b981;
        color: #fff;
    }

    .q-choice.wrong .choice-letter {
        background: #ef4444;
        color: #fff;
    }

    .quiz-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 16px;
        padding: 0 1rem;
    }

    .quiz-status {
        font-size: 13px;
        color: #888;
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
        transition: transform 0.15s;
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
        transition: border-color 0.2s;
    }

    .btn-quiz-prev:hover {
        border-color: var(--green);
        color: var(--green);
    }

    .btn-retry-quiz {
        padding: 10px 22px;
        border-radius: 22px;
        border: 1.5px solid var(--green);
        background: #fff;
        color: var(--green);
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-retry-quiz:hover {
        background: var(--green-light);
    }

    .sidebar-menu li.active-lesson a {
        color: var(--green);
        font-weight: 600;
    }

    .sidebar-menu li.active-lesson .lesson-icon-status {
        color: var(--green);
    }
</style>

<!-- ── EXACT ORIGINAL navbar_lessons ── -->
<div class="navbar-lessons">
    <div class="navbar-title">
        <!-- UNCHANGED — your original back link -->
        <a href="/learning_management/public/?url=subjects&subject=philosophy">
            <i class="fa fa-arrow-left"></i>
            <span>Back</span>
        </a>

        <span>|</span>

        <p>Introduction to Philosophy of Human Person</p>
    </div>

    <div class="navbar-progress">
        <div class="progress-title">
            <p>Module Progress</p>
            <span id="progressPercent">0%</span>
        </div>

        <div class="parent-progress">
            <div class="progress-lesson" id="progressBar"></div>
        </div>
    </div>
</div>

<!-- ── EXACT ORIGINAL body-lessons + tabs added inside view-lessons ── -->
<div class="body-lessons">
    <h2>Introduction to Philosophy of Human Person</h2>

    <div class="sidebar-lessons">
        <h4>Lessons</h4>
        <div class="sidebar-menu">
            <ul>
                <!-- Dynamically built by philosophy.js -->
            </ul>
        </div>
    </div>

    <div class="view-lessons">

        <!-- ORIGINAL view-title -->
        <div class="view-title">
            <i class="fa fa-book-open"></i>
            <span id="lesson-count">Lesson 1 of 10</span>
        </div>

        <h3 id="lesson-title"></h3>

        <!-- ── TABS (new addition) ── -->
        <div class="view-tabs">
            <button class="tab-btn active-tab" data-tab="lesson" onclick="switchTab('lesson')">
                📝 Lesson <span class="tab-count-badge" id="tab-lesson-count">1</span>
            </button>
            <button class="tab-btn" data-tab="videos" onclick="switchTab('videos')">
                ▶ Videos <span class="tab-count-badge" id="tab-video-count">0</span>
            </button>
            <button class="tab-btn" data-tab="images" onclick="switchTab('images')">
                🖼 Images <span class="tab-count-badge" id="tab-image-count">0</span>
            </button>
            <button class="tab-btn" data-tab="activities" onclick="switchTab('activities')">
                ✏️ Activities <span class="tab-count-badge" id="tab-activity-count">0</span>
            </button>
            <button class="tab-btn" data-tab="quiz" onclick="switchTab('quiz')">
                📋 Quiz <span class="tab-count-badge" id="tab-quiz-count">0</span>
            </button>
        </div>

        <!-- ── TAB PANELS ── -->
        <div id="panel-lesson" class="tab-panel" style="display:block;">
            <div class="view-lessons-body" id="lesson-body"></div>
        </div>

        <div id="panel-videos" class="tab-panel">
            <div id="videos-list"></div>
        </div>

        <div id="panel-images" class="tab-panel">
            <div class="images-grid" id="images-list"></div>
        </div>

        <div id="panel-activities" class="tab-panel"></div>
        <div id="panel-quiz" class="tab-panel"></div>

        <hr>

        <!-- ORIGINAL footer pagination — unchanged -->
        <div class="view-lessons-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination m-0">
                    <li class="page-item">
                        <a class="page-link" href="#" id="prevBtn">
                            <i class="fa fa-chevron-left"></i>
                            <span>Previous Lesson</span>
                        </a>
                    </li>

                    <li class="page-item disabled">
                        <span id="page-indicator">1 / 10</span>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#" id="nextBtn">
                            <span>Next Lesson</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div><!-- /view-lessons -->
</div><!-- /body-lessons -->