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

    checkLessonComplete();
}

function unifiedNav(dir) {
    var cards = document.querySelectorAll('.unified-q-card');
    if (!cards.length) return;

    if (cards[UNIFIED_QZ.cur]) cards[UNIFIED_QZ.cur].style.display = 'none';
    UNIFIED_QZ.cur = Math.max(0, Math.min(UNIFIED_QZ.total - 1, UNIFIED_QZ.cur + dir));
    if (cards[UNIFIED_QZ.cur]) cards[UNIFIED_QZ.cur].style.display = 'block';

    var prev = document.getElementById('unified_prev');
    var nxt = document.getElementById('unified_next');

    if (prev) prev.style.display = UNIFIED_QZ.cur > 0 ? 'inline-flex' : 'none';

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

    // Check quizzes
    if (allDone) {
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
        .then(function () {
            if (isFinish) {
                markLessonFinished();
            } else {
                window.location.href = href;
            }
        })
        .catch(function () {
            if (isFinish) {
                markLessonFinished();
            } else {
                window.location.href = href;
            }
        });
}

/* ==========================
   MARK LESSON AS FINISHED
   - Disable Finish button
   - Change label to "Completed"
   - Update progress bar to 100%
   - Update completed count in header
========================== */
function markLessonFinished() {
    // 1. Disable the Finish button and change its label
    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.style.pointerEvents = 'none';
        nextBtn.style.cursor = 'default';
        nextBtn.style.opacity = '1';
        nextBtn.innerHTML = '<span>Completed</span> <i class="fa fa-check-double"></i>';
        nextBtn.style.background = '#16a34a';
        nextBtn.classList.remove('disabled');
    }

    // 2. Update progress bar to 100%
    var progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = '100%';
        progressBar.style.transition = 'width 0.6s ease';
    }
    var progressPercent = document.getElementById('progressPercent');
    if (progressPercent) {
        progressPercent.textContent = '100%';
    }

    // 3. Update the "Completed" count in the module header stats
    // Find the module stat that shows "X Completed"
    var statNums = document.querySelectorAll('.module-stat-num');
    statNums.forEach(function (el) {
        var parent = el.closest('.module-stat');
        if (parent && parent.textContent.includes('Completed')) {
            // Increment the completed count by 1
            var current = parseInt(el.textContent) || 0;
            el.textContent = current + 1;
        }
    });

    // 4. Update the sidebar — mark current lesson as done (green check)
    var activeLi = document.querySelector('.sidebar-menu li.active-lesson');
    if (activeLi) {
        activeLi.classList.add('done-lesson');
        var icon = activeLi.querySelector('.lesson-icon-status');
        if (icon) {
            icon.classList.remove('fa-circle');
            icon.classList.add('fa-check');
        }
    }

    // 5. Show a brief success toast
    showFinishToast();
}

function showFinishToast() {
    var toast = document.createElement('div');
    toast.style.cssText = [
        'position:fixed',
        'bottom:28px',
        'left:50%',
        'transform:translateX(-50%)',
        'background:#16a34a',
        'color:#fff',
        'padding:12px 28px',
        'border-radius:30px',
        'font-size:14px',
        'font-weight:600',
        'box-shadow:0 4px 20px rgba(0,0,0,.18)',
        'z-index:99999',
        'display:flex',
        'align-items:center',
        'gap:8px',
        'opacity:0',
        'transition:opacity .3s'
    ].join(';');
    toast.innerHTML = '<i class="fa fa-check-circle"></i> Lesson completed!';
    document.body.appendChild(toast);

    // Fade in
    setTimeout(function () { toast.style.opacity = '1'; }, 50);

    // Fade out and remove after 3s
    setTimeout(function () {
        toast.style.opacity = '0';
        setTimeout(function () { toast.remove(); }, 400);
    }, 3000);
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
   TAB SWITCHER
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

    // Initial lock check
    checkLessonComplete();

    // NEXT / FINISH
    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn && nextBtn.tagName === 'A') {
        // Only attach click if it's an anchor (not already-completed div)
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (nextBtn.classList.contains('disabled')) return;
            // Extra guard — if button already says Completed, do nothing
            if (nextBtn.textContent.trim().startsWith('Completed')) return;
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