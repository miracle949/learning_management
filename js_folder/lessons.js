/* ==========================
   ANSWER STORE
========================== */
var ANSWERS = {
    activities: {},  // { actId: { qId: answer } }
    quizzes: {}   // { qzId:  { qId: answer } }
};

/* ==========================
   UNIFIED QUIZ STATE
========================== */
var UNIFIED_QZ = {
    cur: 0,
    total: 0,
    ans: {}   // { qid: key }
};

document.addEventListener('DOMContentLoaded', function () {
    // Count total unified quiz questions
    var cards = document.querySelectorAll('.unified-q-card');
    UNIFIED_QZ.total = cards.length;
});

function unifiedPick(el) {
    var qi = parseInt(el.dataset.qi);
    var qid = el.dataset.qid;
    var key = el.dataset.key;
    var uid = el.dataset.uid;
    var qzid = parseInt(el.dataset.qzid);

    // Deselect siblings in same card
    el.closest('.q-choices').querySelectorAll('.q-choice').forEach(function (c) {
        c.classList.remove('selected');
    });
    el.classList.add('selected');

    // Store answer
    UNIFIED_QZ.ans[qid] = key;
    if (!ANSWERS.quizzes[qzid]) ANSWERS.quizzes[qzid] = {};
    ANSWERS.quizzes[qzid][qid] = key;

    // Update status
    var cnt = document.getElementById('unified_status');
    if (cnt) cnt.textContent = Object.keys(UNIFIED_QZ.ans).length + ' / ' + UNIFIED_QZ.total + ' answered';

    // Enable Next button (or hide if last question)
    var nxt = document.getElementById('unified_next');
    var last = qi === UNIFIED_QZ.total - 1;
    if (nxt) {
        if (last) {
            nxt.style.display = 'none';
        } else {
            nxt.disabled = false;
            nxt.style.display = 'inline-flex';
        }
    }

    checkLessonComplete();
}

function unifiedNav(dir) {
    var cards = document.querySelectorAll('.unified-q-card');
    if (!cards.length) return;

    // Hide current
    if (cards[UNIFIED_QZ.cur]) cards[UNIFIED_QZ.cur].style.display = 'none';

    UNIFIED_QZ.cur = Math.max(0, Math.min(UNIFIED_QZ.total - 1, UNIFIED_QZ.cur + dir));

    // Show new
    if (cards[UNIFIED_QZ.cur]) cards[UNIFIED_QZ.cur].style.display = 'block';

    var prev = document.getElementById('unified_prev');
    var nxt = document.getElementById('unified_next');

    if (prev) prev.style.display = UNIFIED_QZ.cur > 0 ? 'inline-flex' : 'none';

    // Enable Next only if current question already answered
    if (nxt) {
        var curCard = cards[UNIFIED_QZ.cur];
        var firstChoice = curCard ? curCard.querySelector('.q-choice') : null;
        var curQid = firstChoice ? firstChoice.dataset.qid : null;
        var alreadyAnswered = curQid && UNIFIED_QZ.ans[curQid];
        var isLast = UNIFIED_QZ.cur === UNIFIED_QZ.total - 1;

        if (isLast) {
            nxt.style.display = 'none';
        } else {
            nxt.style.display = 'inline-flex';
            nxt.disabled = !alreadyAnswered;
        }
    }
}

/* ==========================
   ACTIVITY ANSWER HELPERS
========================== */
function lessonPickMC(label, qid, key) {
    var choices = label.closest('.mc-choices');
    if (choices) {
        choices.querySelectorAll('.mc-label').forEach(function (l) {
            l.classList.remove('selected');
        });
    }
    label.classList.add('selected');

    var form = label.closest('.activity-answers-form');
    if (!form) return;
    var actId = parseInt(form.dataset.actId);
    if (!ANSWERS.activities[actId]) ANSWERS.activities[actId] = {};
    ANSWERS.activities[actId][qid] = key;

    checkLessonComplete();
}

function lessonTextAnswer(textarea, qid) {
    var form = textarea.closest('.activity-answers-form');
    if (!form) return;
    var actId = parseInt(form.dataset.actId);
    if (!ANSWERS.activities[actId]) ANSWERS.activities[actId] = {};
    if (textarea.value.trim()) {
        ANSWERS.activities[actId][qid] = textarea.value.trim();
    } else {
        delete ANSWERS.activities[actId][qid];
    }
    checkLessonComplete();
}

/* ==========================
   CHECK IF ALL ANSWERED
========================== */
function checkLessonComplete() {
    if (typeof LESSON_DATA === 'undefined') return;

    var allDone = true;

    // Check activities
    LESSON_DATA.activities.forEach(function (act) {
        if (act.done) return;
        var answered = ANSWERS.activities[act.id] || {};
        if (Object.keys(answered).length < act.required) allDone = false;
    });

    // Check quizzes — use unified total
    if (!allDone) {
        // Already failed above
    } else {
        var totalRequired = 0;
        LESSON_DATA.quizzes.forEach(function (qz) {
            if (!qz.done) totalRequired += qz.required;
        });
        if (Object.keys(UNIFIED_QZ.ans).length < totalRequired) allDone = false;
    }

    var nextBtn = document.getElementById('nextBtn');
    var lockNotice = document.getElementById('lessonLockNotice');

    if (nextBtn) {
        if (allDone) {
            nextBtn.style.opacity = '';
            nextBtn.style.pointerEvents = '';
            nextBtn.style.cursor = '';
            nextBtn.classList.remove('disabled');
        } else {
            nextBtn.style.opacity = '0.45';
            nextBtn.style.pointerEvents = 'none';
            nextBtn.style.cursor = 'not-allowed';
            nextBtn.classList.add('disabled');
        }
    }

    if (lockNotice) lockNotice.style.display = allDone ? 'none' : 'flex';
}

/* ==========================
   SAVE ANSWERS + NAVIGATE
========================== */
function saveAndGo(href, isFinish) {
    var lessonId = (typeof LESSON_DATA !== 'undefined') ? LESSON_DATA.lessonId : 0;

    var payload = {
        lesson_id: lessonId,
        activities: ANSWERS.activities,
        quizzes: {}
    };

    if (typeof LESSON_DATA !== 'undefined') {
        LESSON_DATA.quizzes.forEach(function (qz) {
            var ans = ANSWERS.quizzes[qz.id];
            if (ans && Object.keys(ans).length > 0) {
                payload.quizzes[qz.id] = {
                    answers: ans,
                    passing_score: qz.passing_score
                };
            }
        });
    }

    fetch('/learning_management/public/?url=save_lesson_answers', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(function () { doNavigate(href, isFinish); })
        .catch(function () { doNavigate(href, isFinish); });
}

function doNavigate(href, isFinish) {
    if (isFinish) {
        var p = new URLSearchParams(window.location.search);
        window.location.href = '/learning_management/public/?url=modules&subject=' + (p.get('subject') || '');
    } else {
        window.location.href = href;
    }
}

/* ==========================
   TAB SWITCHER (kept for compatibility)
========================== */
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(function (p) { p.style.display = 'none'; });
    document.querySelectorAll('.tab-btn').forEach(function (b) { b.classList.remove('active-tab'); });
    var panel = document.getElementById('panel-' + name);
    if (panel) panel.style.display = 'block';
    if (btn) btn.classList.add('active-tab');
}

/* ==========================
   PROGRESS BAR + NAV INIT
========================== */
document.addEventListener('DOMContentLoaded', function () {

    // Progress bar
    var lessonCountEl = document.getElementById('lesson-count');
    var currentIndex = 1, totalLessons = 1;
    if (lessonCountEl) {
        var m = lessonCountEl.textContent.match(/(\d+)\s+of\s+(\d+)/);
        if (m) { currentIndex = parseInt(m[1]); totalLessons = parseInt(m[2]); }
    }
    (function () {
        if (!totalLessons) return;
        var pct = Math.round((currentIndex / totalLessons) * 100);
        var bar = document.getElementById('progressBar');
        var text = document.getElementById('progressPercent');
        if (bar) bar.style.width = pct + '%';
        if (text) text.textContent = pct + '%';
    })();

    // Initial lock check
    checkLessonComplete();

    // NEXT / FINISH
    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (nextBtn.classList.contains('disabled')) return;
            var href = nextBtn.getAttribute('href');
            var isFinish = (href === '#' || !href);
            saveAndGo(href, isFinish);
        });
    }

    // PREV
    var prevBtn = document.getElementById('prevBtn');
    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            if (prevBtn.classList.contains('disabled')) return;
            e.preventDefault();
            var href = prevBtn.getAttribute('href');
            if (href && href !== '#') window.location.href = href;
        });
    }

    // SIDEBAR
    document.querySelectorAll('.sidebar-menu li a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            saveAndGo(link.getAttribute('href'), false);
        });
    });
});

/* ==========================
   LIGHTBOX
========================== */
function dbLightbox(src) {
    document.getElementById('dbLightboxImg').src = src;
    document.getElementById('dbLightbox').classList.add('open');
}
function dbLightboxClose() {
    document.getElementById('dbLightbox').classList.remove('open');
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') dbLightboxClose();
});