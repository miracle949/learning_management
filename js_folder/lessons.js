/* ==========================
   ANSWER STORE
========================== */
var ANSWERS = {
    activities: {},  // { actId: { qId: answer } }
    quizzes: {}      // { qzId:  { qId: answer } }
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
    var cards = document.querySelectorAll('.unified-q-card');
    UNIFIED_QZ.total = cards.length;

    // Wire up essay textarea tracking
    document.querySelectorAll('.activity-answer').forEach(function (textarea) {
        var qid = textarea.dataset.qid;
        var actId = parseInt(textarea.dataset.actId);
        textarea.addEventListener('input', function () {
            if (!ANSWERS.activities[actId]) ANSWERS.activities[actId] = {};
            if (textarea.value.trim()) {
                ANSWERS.activities[actId][qid] = textarea.value.trim();
            } else {
                delete ANSWERS.activities[actId][qid];
            }
            checkLessonComplete();
        });
    });

    checkLessonComplete();

    // NEXT / FINISH button
    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn && nextBtn.tagName === 'A') {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (nextBtn.classList.contains('disabled')) return;
            if (nextBtn.innerHTML.indexOf('Completed') !== -1) return;
            var href = nextBtn.getAttribute('href');
            var isFinish = (href === '#' || !href);
            saveAndGo(href, isFinish);
        });
    }

    // PREV button — always free, no save needed
    var prevBtn = document.getElementById('prevBtn');
    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            if (prevBtn.classList.contains('disabled')) return;
            e.preventDefault();
            var href = prevBtn.getAttribute('href');
            if (href && href !== '#') window.location.href = href;
        });
    }

    // SIDEBAR links — always free, just save quiz state in background
    document.querySelectorAll('.sidebar-menu li a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            saveQuizAndNavigate(link.getAttribute('href'));
        });
    });
});

/* ==========================
   ACTIVITY MC PICKER
========================== */
function pickMC(el) {
    var qid = el.dataset.qid;
    var actId = parseInt(el.dataset.actId);
    var key = el.dataset.key;

    el.closest('.question-card').querySelectorAll('.mc-choice').forEach(function (c) {
        c.classList.remove('selected');
    });
    el.classList.add('selected');

    if (!ANSWERS.activities[actId]) ANSWERS.activities[actId] = {};
    ANSWERS.activities[actId][qid] = key;

    var hidden = document.getElementById('mc_hidden_' + qid);
    if (hidden) hidden.value = key;

    checkLessonComplete();
}

/* ==========================
   UNIFIED QUIZ PICKER
========================== */
function unifiedPick(el) {
    var qid = el.dataset.qid;
    var key = el.dataset.key;
    var qzid = parseInt(el.dataset.qzid);

    el.closest('.q-card').querySelectorAll('.q-choice').forEach(function (c) {
        c.classList.remove('selected');
    });
    el.classList.add('selected');

    UNIFIED_QZ.ans[qid] = key;
    if (!ANSWERS.quizzes[qzid]) ANSWERS.quizzes[qzid] = {};
    ANSWERS.quizzes[qzid][qid] = key;

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
    if (nxt) nxt.style.display = UNIFIED_QZ.cur === UNIFIED_QZ.total - 1 ? 'none' : 'inline-flex';
}

/* ==========================
   CHECK IF ALL ANSWERED
   Locks Next Lesson AND Finish until pending items are all answered.
   If there are NO pending items, always unlocked.
========================== */
function checkLessonComplete() {
    if (typeof LESSON_DATA === 'undefined') return;

    var nextBtn = document.getElementById('nextBtn');
    var lockNotice = document.getElementById('lessonLockNotice');

    var isFinish = nextBtn && nextBtn.tagName === 'A' &&
        (nextBtn.getAttribute('href') === '#' || !nextBtn.getAttribute('href'));

    // Count what's still pending (not yet done on server)
    var hasPending = false;
    LESSON_DATA.activities.forEach(function (act) { if (!act.done) hasPending = true; });
    LESSON_DATA.quizzes.forEach(function (qz) { if (!qz.done) hasPending = true; });

    // Nothing to answer → always free to proceed
    if (!hasPending) {
        _unlock(nextBtn, lockNotice);
        return;
    }

    // Check all pending activities are answered
    var allDone = true;
    LESSON_DATA.activities.forEach(function (act) {
        if (act.done) return;
        var answered = ANSWERS.activities[act.id] || {};
        if (Object.keys(answered).length < act.required) allDone = false;
    });

    // Check all pending quiz questions answered
    if (allDone) {
        var totalRequired = 0;
        LESSON_DATA.quizzes.forEach(function (qz) { if (!qz.done) totalRequired += qz.required; });
        if (Object.keys(UNIFIED_QZ.ans).length < totalRequired) allDone = false;
    }

    if (allDone) {
        _unlock(nextBtn, lockNotice);
    } else {
        _lock(nextBtn, lockNotice, isFinish);
    }
}

function _unlock(nextBtn, lockNotice) {
    if (nextBtn) {
        nextBtn.style.opacity = '';
        nextBtn.style.pointerEvents = '';
        nextBtn.style.cursor = '';
        nextBtn.classList.remove('disabled');
    }
    if (lockNotice) lockNotice.style.display = 'none';
}

function _lock(nextBtn, lockNotice, isFinish) {
    if (nextBtn) {
        nextBtn.style.opacity = '0.45';
        nextBtn.style.pointerEvents = 'none';
        nextBtn.style.cursor = 'not-allowed';
        nextBtn.classList.add('disabled');
    }
    if (lockNotice) {
        var msgEl = lockNotice.querySelector('span');
        if (msgEl) {
            msgEl.textContent = isFinish
                ? 'Answer all activities and quizzes in this lesson to finish.'
                : 'Answer all activities and quizzes in this lesson to proceed to the next lesson.';
        }
        lockNotice.style.display = 'flex';
    }
}

/* ==========================
   CORE: SAVE + NAVIGATE
   Called for EVERY Next Lesson and Finish click.

   Flow:
   1. Disable button, show spinner
   2. Submit any pending activities (one by one)
   3. POST to save_lesson_answers — this ALWAYS marks lesson complete in DB
      (even if there were no activities/quizzes — the endpoint handles it)
   4. Update sidebar icon + progress bar from server response
   5. Navigate to next lesson / show Completed on last lesson
========================== */
function saveAndGo(href, isFinish) {
    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;
    var studentId = LESSON_DATA ? LESSON_DATA.studentId : 0;

    // 1. Disable button immediately — no double clicks
    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.style.pointerEvents = 'none';
        nextBtn.style.opacity = '0.75';
        nextBtn.innerHTML = '<span>Saving…</span> <i class="fa fa-spinner fa-spin"></i>';
    }

    // 2. Collect pending activities
    var pendingActivities = [];
    if (LESSON_DATA) {
        LESSON_DATA.activities.forEach(function (act) {
            if (!act.done) {
                var answers = ANSWERS.activities[act.id] || {};
                if (Object.keys(answers).length > 0) {
                    pendingActivities.push({ id: act.id, answers: answers });
                }
            }
        });
    }

    // 3. Submit activities then mark lesson complete
    submitActivitiesSequentially(pendingActivities, lessonId, studentId, function () {

        // POST to save_lesson_answers — ALWAYS called, even with empty quizzes.
        // The server uses lesson_id to mark this lesson as completed for the student.
        var payload = {
            lesson_id: lessonId,
            activities: {},
            quizzes: buildQuizPayload()
        };

        fetch('/learning_management/public/?url=save_lesson_answers', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function (r) { return r.json().catch(function () { return {}; }); })
            .then(function (resp) {
                // Show correct-answer review for activities
                renderActivityReview();

                // Update sidebar + progress bar.
                // Prefer server-returned counts; fall back to DOM count.
                var newCompleted = (resp && typeof resp.completed_count === 'number')
                    ? resp.completed_count : null;
                var newTotal = (resp && typeof resp.total_lessons === 'number')
                    ? resp.total_lessons : null;

                updateProgressUI(newCompleted, newTotal);

                if (isFinish) {
                    // Last lesson — stay on page, show Completed state
                    markLessonFinished();
                } else {
                    // Mid-lesson — brief pause so student sees the green check, then go
                    setTimeout(function () { window.location.href = href; }, 700);
                }
            })
            .catch(function () {
                // Even on network error, still try to navigate
                renderActivityReview();
                updateProgressUI(null, null);
                if (isFinish) {
                    markLessonFinished();
                } else {
                    setTimeout(function () { window.location.href = href; }, 700);
                }
            });
    });
}

/* ==========================
   UPDATE SIDEBAR ICON + PROGRESS BAR
   Uses server counts when available; DOM count as fallback.
========================== */
function updateProgressUI(serverCompleted, serverTotal) {
    // Flip sidebar icon for current lesson → green check
    var activeLi = document.querySelector('.sidebar-menu li.active-lesson');
    if (activeLi) {
        activeLi.classList.add('done-lesson');
        var icon = activeLi.querySelector('.lesson-icon-status');
        if (icon) {
            icon.classList.remove('fa-circle');
            icon.classList.add('fa-check');
        }
    }

    // Count done lessons from sidebar DOM (reflects the flip above)
    var domTotal = 0;
    var domCompleted = 0;
    document.querySelectorAll('.sidebar-menu li').forEach(function (li) {
        domTotal++;
        if (li.classList.contains('done-lesson')) domCompleted++;
    });

    var total = (serverTotal !== null) ? serverTotal : domTotal;
    var completed = (serverCompleted !== null) ? serverCompleted : domCompleted;

    // Module header "X Completed" stat
    document.querySelectorAll('.module-stat-num').forEach(function (el) {
        var parent = el.closest('.module-stat');
        if (parent && parent.textContent.includes('Completed')) {
            el.textContent = completed;
        }
    });

    // Navbar progress bar + percentage
    if (total > 0) {
        var pct = Math.round((completed / total) * 100);
        var bar = document.getElementById('progressBar');
        var pctLabel = document.getElementById('progressPercent');
        if (bar) { bar.style.transition = 'width 0.6s ease'; bar.style.width = pct + '%'; }
        if (pctLabel) pctLabel.textContent = pct + '%';
    }
}

/* ==========================
   SIDEBAR-ONLY NAVIGATION
   Always free — saves quiz answers in background then navigates.
========================== */
function saveQuizAndNavigate(href) {
    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;
    var payload = { lesson_id: lessonId, activities: {}, quizzes: buildQuizPayload() };
    fetch('/learning_management/public/?url=save_lesson_answers', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
        .then(function () { window.location.href = href; })
        .catch(function () { window.location.href = href; });
}

/* ==========================
   BUILD QUIZ PAYLOAD HELPER
========================== */
function buildQuizPayload() {
    var payload = {};
    if (LESSON_DATA) {
        LESSON_DATA.quizzes.forEach(function (qz) {
            var ans = ANSWERS.quizzes[qz.id];
            if (ans && Object.keys(ans).length > 0) {
                payload[qz.id] = { answers: ans, passing_score: qz.passing_score };
            }
        });
    }
    return payload;
}

/* ==========================
   SUBMIT ACTIVITIES ONE BY ONE
========================== */
function submitActivitiesSequentially(list, lessonId, studentId, callback) {
    if (!list.length) { callback(); return; }

    var item = list[0];
    var rest = list.slice(1);
    var formData = new FormData();

    formData.append('activity_id', item.id);
    formData.append('lesson_id', lessonId);
    formData.append('student_id', studentId);
    Object.keys(item.answers).forEach(function (qid) {
        formData.append('answers[' + qid + ']', item.answers[qid]);
    });

    fetch('/learning_management/public/?url=submit_activity', {
        method: 'POST',
        body: formData
    })
        .then(function (r) { return r.json().catch(function () { return {}; }); })
        .then(function () { submitActivitiesSequentially(rest, lessonId, studentId, callback); })
        .catch(function () { submitActivitiesSequentially(rest, lessonId, studentId, callback); });
}

/* ==========================
   RENDER ACTIVITY REVIEW
   Swaps the live answer form for a read-only correct-answer review.
   MC:    correct answer = green, student's wrong pick = red
   Essay: student's answer shown + model answer below
========================== */
function renderActivityReview() {
    if (typeof LESSON_DATA === 'undefined') return;

    LESSON_DATA.activities.forEach(function (act) {
        if (act.done) return; // server already rendered this one

        var wrapper = document.querySelector('.activity-answers-wrapper[data-activity-id="' + act.id + '"]');
        if (!wrapper) return;

        var questions = act.questions || [];
        if (!questions.length) return;

        var html = '<div class="submitted-notice"><i class="fa fa-check-circle"></i>Activity submitted!</div>';
        var letters = ['A', 'B', 'C', 'D'];

        questions.forEach(function (q, qi) {
            html += '<div class="activity-question">';
            html += '<p class="aq-num">Question ' + (qi + 1) + '</p>';
            html += '<p class="aq-text">' + escHtml(q.question) + '</p>';

            if (q.question_type === 'multiple_choice') {
                var choices = [
                    { key: 'a', val: q.choice_a },
                    { key: 'b', val: q.choice_b },
                    { key: 'c', val: q.choice_c },
                    { key: 'd', val: q.choice_d }
                ];
                var studentPicked = ((ANSWERS.activities[act.id] || {})[q.id] || '').toLowerCase();
                var correctAns = (q.correct_ans || '').toLowerCase();
                var li = 0;

                choices.forEach(function (ch) {
                    if (!ch.val) return;
                    var isCorrect = (ch.key === correctAns);
                    var isPicked = (ch.key === studentPicked);
                    var isWrong = (isPicked && !isCorrect);

                    var bStyle = isCorrect ? 'border-color:#22c55e;background:#f0fdf4;'
                        : isWrong ? 'border-color:#ef4444;background:#fef2f2;'
                            : '';
                    var lStyle = isCorrect ? 'background:#22c55e;color:#fff;'
                        : isWrong ? 'background:#ef4444;color:#fff;'
                            : '';
                    var iconHtml = isCorrect
                        ? '<i class="fa fa-check" style="margin-left:auto;color:#22c55e;"></i>'
                        : isWrong
                            ? '<i class="fa fa-times" style="margin-left:auto;color:#ef4444;"></i>'
                            : '';

                    html += '<div class="review-choice" style="' + bStyle + '">';
                    html += '<span class="mc-letter" style="' + lStyle + '">' + letters[li] + '</span>';
                    html += escHtml(ch.val);
                    html += iconHtml;
                    html += '</div>';
                    li++;
                });

                if (studentPicked && studentPicked !== correctAns) {
                    var pickedLetter = '';
                    choices.forEach(function (ch, idx) {
                        if (ch.key === studentPicked) pickedLetter = letters[idx];
                    });
                    if (pickedLetter) {
                        html += '<p style="font-size:12px;color:#ef4444;margin:6px 0 0;">'
                            + '<i class="fa fa-info-circle"></i> Your answer: ' + pickedLetter + '</p>';
                    }
                }

            } else {
                var studentAnswer = ((ANSWERS.activities[act.id] || {})[q.id] || '');
                html += '<div style="background:#fffbeb;border:1.5px solid #f59e0b;border-radius:8px;'
                    + 'padding:12px 14px;font-size:14px;color:#92400e;margin-bottom:8px;">';
                html += '<strong style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;'
                    + 'color:#f59e0b;display:block;margin-bottom:4px;">Your Answer</strong>';
                html += studentAnswer
                    ? escHtml(studentAnswer)
                    : '<em style="opacity:.5">No answer provided</em>';
                html += '</div>';
                if (q.model_answer) {
                    html += '<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;'
                        + 'padding:10px 14px;font-size:13px;color:#065f46;">';
                    html += '<strong>Model Answer:</strong> ' + escHtml(q.model_answer);
                    html += '</div>';
                }
            }

            html += '</div>';
        });

        wrapper.innerHTML = html;
    });
}

function escHtml(str) {
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str || '')));
    return d.innerHTML;
}

/* ==========================
   MARK LAST LESSON FINISHED
   Only called for the Finish button (last lesson).
   Mid-lesson navigation just calls window.location.href.
========================== */
function markLessonFinished() {
    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.style.pointerEvents = 'none';
        nextBtn.style.cursor = 'default';
        nextBtn.style.opacity = '1';
        nextBtn.innerHTML = '<span>Completed</span> <i class="fa fa-check-double"></i>';
        nextBtn.style.background = '#16a34a';
        nextBtn.classList.remove('disabled');
    }

    var lockNotice = document.getElementById('lessonLockNotice');
    if (lockNotice) lockNotice.style.display = 'none';

    showFinishToast();
}

function showFinishToast() {
    var toast = document.createElement('div');
    toast.style.cssText = [
        'position:fixed', 'bottom:28px', 'left:50%', 'transform:translateX(-50%)',
        'background:#16a34a', 'color:#fff', 'padding:12px 28px', 'border-radius:30px',
        'font-size:14px', 'font-weight:600', 'box-shadow:0 4px 20px rgba(0,0,0,.18)',
        'z-index:99999', 'display:flex', 'align-items:center', 'gap:8px',
        'opacity:0', 'transition:opacity .3s'
    ].join(';');
    toast.innerHTML = '<i class="fa fa-check-circle"></i> Lesson completed!';
    document.body.appendChild(toast);
    setTimeout(function () { toast.style.opacity = '1'; }, 50);
    setTimeout(function () {
        toast.style.opacity = '0';
        setTimeout(function () { toast.remove(); }, 400);
    }, 3200);
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
   LIGHTBOX
========================== */
function dbLightbox(src) {
    document.getElementById('dbLightboxImg').src = src;
    document.getElementById('dbLightbox').classList.add('open');
}
function dbLightboxClose() {
    document.getElementById('dbLightbox').classList.remove('open');
}
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') dbLightboxClose(); });