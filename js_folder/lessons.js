/* ==========================
   ANSWER STORE
========================== */
var ANSWERS = {
    activities: {},
    quizzes: {}
};

/* ==========================
   UNIFIED QUIZ STATE
========================== */
var UNIFIED_QZ = {
    cur: 0,
    total: 0,
    ans: {}
};

/* =========================================================
   NEXT / PREV / FINISH — DELEGATED CLICK HANDLER
   -----------------------------------------------------------
   This is registered immediately (NOT inside DOMContentLoaded,
   NOT nested after other init code). That means:
     - It works even if it loads before the button exists yet.
     - It can never be skipped because some unrelated init code
       (e.g. checkLessonComplete) threw an error first.
     - It still works after nextBtn.innerHTML is replaced with
       "Saving…", because the listener lives on `document`, not
       on the button itself.
========================================================= */
document.addEventListener('click', function (e) {
    var nextBtn = e.target.closest('#nextBtn');
    if (nextBtn) {
        handleNextBtnClick(e, nextBtn);
        return;
    }

    var prevBtn = e.target.closest('#prevBtn');
    if (prevBtn) {
        handlePrevBtnClick(e, prevBtn);
    }
});

function handleNextBtnClick(e, nextBtn) {
    e.preventDefault();
    console.log('[lessons.js] nextBtn clicked');

    // Completed badge (rendered as <span>) — nothing to do
    if (nextBtn.tagName === 'SPAN') {
        console.log('[lessons.js] is completed span, ignoring');
        return;
    }
    if (nextBtn.classList.contains('btn-completed-top')) {
        console.log('[lessons.js] has btn-completed-top, ignoring');
        return;
    }
    if (nextBtn.classList.contains('disabled')) {
        console.log('[lessons.js] is disabled (activities/quiz incomplete), ignoring');
        return;
    }

    // For <a> tags: get href. For <button>: href is null → isFinish = true
    var href = nextBtn.getAttribute('href') || '';
    var isFinish = (href === '#' || href === '' || href === null);
    console.log('[lessons.js] href:', href, '| isFinish:', isFinish);

    saveAndGo(href, isFinish);
}

function handlePrevBtnClick(e, prevBtn) {
    if (prevBtn.classList.contains('disabled')) return;
    e.preventDefault();
    var href = prevBtn.getAttribute('href');
    if (href && href !== '#') window.location.href = href;
}

/* =========================================================
   PAGE INIT (everything non-click-related)
========================================================= */
document.addEventListener('DOMContentLoaded', function () {
    try {
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

        var nextBtnEl = document.getElementById('nextBtn');
        console.log('[lessons.js] nextBtn found:', nextBtnEl ? nextBtnEl.tagName : 'NOT FOUND');
        console.log('[lessons.js] init complete — delegated click handler is active');
    } catch (err) {
        // Even if something here breaks, it can no longer take the
        // Next/Prev/Finish buttons down with it.
        console.error('[lessons.js] init error (buttons are unaffected):', err);
    }
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
========================== */
function checkLessonComplete() {
    try {
        if (typeof LESSON_DATA === 'undefined') return;

        var nextBtn = document.getElementById('nextBtn');
        var lockNotice = document.getElementById('lessonLockNotice');

        if (!nextBtn) return;
        if (nextBtn.tagName === 'SPAN') return;
        if (nextBtn.classList.contains('btn-completed-top')) return;

        var href = nextBtn.getAttribute('href') || '';
        var isFinish = (href === '#' || href === '');

        var hasPending = false;
        (LESSON_DATA.activities || []).forEach(function (act) { if (!act.done) hasPending = true; });
        (LESSON_DATA.quizzes || []).forEach(function (qz) { if (!qz.done) hasPending = true; });

        if (!hasPending) {
            _unlock(nextBtn, lockNotice);
            return;
        }

        var allDone = true;

        (LESSON_DATA.activities || []).forEach(function (act) {
            if (act.done) return;
            var answered = ANSWERS.activities[act.id] || {};
            if (Object.keys(answered).length < act.required) allDone = false;
        });

        if (allDone) {
            var totalRequired = 0;
            (LESSON_DATA.quizzes || []).forEach(function (qz) { if (!qz.done) totalRequired += qz.required; });
            if (Object.keys(UNIFIED_QZ.ans).length < totalRequired) allDone = false;
        }

        if (allDone) {
            _unlock(nextBtn, lockNotice);
        } else {
            _lock(nextBtn, lockNotice, isFinish);
        }
    } catch (err) {
        console.error('[lessons.js] checkLessonComplete error:', err);
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
========================== */
function saveAndGo(href, isFinish) {
    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;
    var moduleId = LESSON_DATA ? LESSON_DATA.moduleId : 0;
    var subject = LESSON_DATA ? LESSON_DATA.subject : '';

    console.log('[saveAndGo] lessonId:', lessonId, '| moduleId:', moduleId, '| subject:', subject, '| isFinish:', isFinish);

    var nextBtn = document.getElementById('nextBtn');
    if (nextBtn) {
        nextBtn.style.pointerEvents = 'none';
        nextBtn.style.opacity = '0.75';
        nextBtn.innerHTML = '<span>Saving\u2026</span>';
    }

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

    submitActivitiesSequentially(pendingActivities, lessonId, LESSON_DATA ? LESSON_DATA.studentId : 0, function () {

        var payload = {
            lesson_id: lessonId,
            activities: {},
            quizzes: buildQuizPayload()
        };

        console.log('[saveAndGo] posting save_lesson_answers, payload:', payload);

        fetch('/learning_management/public/?url=save_lesson_answers', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(function (r) {
                console.log('[saveAndGo] save_lesson_answers HTTP status:', r.status);
                return r.json().catch(function () { return {}; });
            })
            .then(function (resp) {
                console.log('[saveAndGo] save_lesson_answers response:', resp);
                renderActivityReview();
                updateProgressUI(
                    (resp && typeof resp.completed_count === 'number') ? resp.completed_count : null,
                    (resp && typeof resp.total_lessons === 'number') ? resp.total_lessons : null
                );

                if (isFinish) {
                    doFinishModule(moduleId, subject);
                } else {
                    setTimeout(function () { window.location.href = href; }, 400);
                }
            })
            .catch(function (err) {
                console.error('[saveAndGo] save_lesson_answers FAILED:', err);
                if (isFinish) {
                    doFinishModule(moduleId, subject);
                } else {
                    window.location.href = href;
                }
            });
    });
}

/* ==========================
   FINISH MODULE
========================== */
function doFinishModule(moduleId, subject) {
    console.log('[doFinishModule] moduleId:', moduleId, '| subject:', subject);

    if (!moduleId) {
        console.warn('[doFinishModule] no moduleId — redirecting directly');
        window.location.href = '/learning_management/public/?url=modules&subject=' + encodeURIComponent(subject);
        return;
    }

    var fd = new FormData();
    fd.append('module_id', moduleId);

    fetch('/learning_management/public/?url=finish_module', {
        method: 'POST',
        body: fd
    })
        .then(function (r) {
            console.log('[doFinishModule] finish_module HTTP status:', r.status);
            return r.json().catch(function () { return {}; });
        })
        .then(function (data) {
            console.log('[doFinishModule] finish_module response:', data);
            showFinishToast();
            setTimeout(function () {
                window.location.href = '/learning_management/public/?url=modules&subject=' + encodeURIComponent(subject);
            }, 800);
        })
        .catch(function (err) {
            console.error('[doFinishModule] finish_module FAILED:', err);
            window.location.href = '/learning_management/public/?url=modules&subject=' + encodeURIComponent(subject);
        });
}

/* ==========================
   UPDATE SIDEBAR + PROGRESS BAR
========================== */
function updateProgressUI(serverCompleted, serverTotal) {
    var domTotal = 0;
    var domCompleted = 0;
    document.querySelectorAll('.sb-nav-item').forEach(function (item) {
        if (item.querySelector('.sb-nav-icon.icon-type-lesson')) {
            domTotal++;
            if (item.querySelector('.sb-nav-check-done')) domCompleted++;
        }
    });

    var total = (serverTotal !== null) ? serverTotal : domTotal;
    var completed = (serverCompleted !== null) ? serverCompleted : domCompleted;

    if (total > 0) {
        var pct = Math.round((completed / total) * 100);
        var bar = document.getElementById('progressBar');
        var pctLabel = document.getElementById('progressPercent');
        if (bar) { bar.style.transition = 'width 0.6s ease'; bar.style.width = pct + '%'; }
        if (pctLabel) pctLabel.textContent = pct + '%';
    }
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
========================== */
function renderActivityReview() {
    if (typeof LESSON_DATA === 'undefined') return;

    LESSON_DATA.activities.forEach(function (act) {
        if (act.done) return;

        var wrapper = document.querySelector('.activity-answers-wrapper[data-activity-id="' + act.id + '"]');
        if (!wrapper) return;

        var questions = act.questions || [];
        if (!questions.length) return;

        var html = ''
            + '<div class="submitted-notice">'
            + '<div class="submitted-check">'
            + '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18">'
            + '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>'
            + '<path d="M22 4L12 14.01l-3-3"/>'
            + '</svg>'
            + '</div>'
            + '<div class="submitted-notice-text">'
            + '<div class="sn-title">Activity Submitted</div>'
            + '<div class="sn-sub">You have already completed this activity. Review your answers below.</div>'
            + '</div>'
            + '</div>';

        var letters = ['A', 'B', 'C', 'D'];

        questions.forEach(function (q, qi) {
            html += '<div class="question-card">';
            html += '<div class="q-num-label">Question ' + (qi + 1) + '</div>';
            html += '<div class="q-text">' + escHtml(q.question) + '</div>';

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

                    var bStyle = isCorrect
                        ? 'border-color:#22c55e;background:#f0fdf4;'
                        : isWrong
                            ? 'border-color:#ef4444;background:#fef2f2;'
                            : '';
                    var lStyle = isCorrect
                        ? 'background:#22c55e;color:#fff;border-color:#22c55e;'
                        : isWrong
                            ? 'background:#ef4444;color:#fff;border-color:#ef4444;'
                            : '';
                    var iconHtml = isCorrect
                        ? '<svg viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" width="14" height="14" style="margin-left:auto"><path d="M20 6L9 17l-5-5"/></svg>'
                        : isWrong
                            ? '<svg viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5" width="14" height="14" style="margin-left:auto"><path d="M18 6L6 18M6 6l12 12"/></svg>'
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
                            + 'Your answer: ' + pickedLetter + '</p>';
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
   FINISH TOAST
========================== */
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
    toast.innerHTML = '&#10003; Module completed!';
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
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') dbLightboxClose();
});