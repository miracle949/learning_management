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

/* ==========================
   ACTIVITY STAGE STATE
   (mirrors UNIFIED_QZ, but for the one-question-at-a-time
   hands-on activity carousel)
========================== */
var ACT_STAGE = {
    cur: 0
};

/* ==========================
   DRAG & DROP — ALL ITEMS SHOWN, RANDOM CATEGORY HINTS
========================== */
var DD_STAGE = {}; // gameTitle => { items, categoryCounts, solvedCounts, answers, currentCategory }
var _ddDraggedEl = null;

function ddFindSection(gameTitle) {
    return document.querySelector('.dd-stage-section[data-game-title="' + CSS.escape(gameTitle) + '"]');
}

// Real definitions for common categories, used as a clue so the student
// can reason out which items belong there without being told directly.
// Kept intentionally short (roughly one line each) so the speech bubble
// never grows tall enough to push the BonBon mascot off-screen — long,
// two-sentence clues were the actual root cause of the overflow, not
// just something a transform could patch around.

function ddCategoryDescriptions(cat, hint) {
    var trimmedHint = (hint || '').trim();

    if (trimmedHint) {
        // Straight description pulled from dragdrop_category_description —
        // no randomized phrasing, just "Category — description".
        return '"' + cat + '" — ' + trimmedHint;
    }

    // Generic fallback — no description was set for this category in the DB.
    return 'Sort the cards that belong under "' + cat + '".';
}

function ddInitState(gameTitle, itemsFromDom, categoryHints) {
    if (!DD_STAGE[gameTitle]) {
        var categoryCounts = {};
        (itemsFromDom || []).forEach(function (it) {
            categoryCounts[it.category] = (categoryCounts[it.category] || 0) + 1;
        });
        DD_STAGE[gameTitle] = {
            items: itemsFromDom || [],
            categoryCounts: categoryCounts,
            categoryHints: categoryHints || {},   // NEW — admin-authored per-category hint text
            solvedCounts: {},
            answers: {},
            currentCategory: null
        };
    }
    return DD_STAGE[gameTitle];
}

function ddSetBubble(gameTitle, text) {
    var section = ddFindSection(gameTitle);
    var qEl = section && section.querySelector('.dd-question-text');
    if (qEl) qEl.textContent = text;
    var overlayMsg = document.getElementById('ddOverlayMessage');
    if (overlayMsg) overlayMsg.textContent = text;

    // Re-measure after the new (possibly longer/shorter) text has been
    // painted. rAF x2 + a short trailing timeout covers both same-frame
    // reflow and any late image/font layout shifts.
    adjustBonBonPosition('#ddOverlay');
}

function ddPickNextCategoryHint(gameTitle) {
    var state = DD_STAGE[gameTitle];
    if (!state) return;

    var totalPlaced = Object.keys(state.answers).length;
    if (totalPlaced >= state.items.length) {
        ddSetBubble(gameTitle, "You've placed every item! Tap Finish to see your score.");
        state.currentCategory = null;
        return;
    }

    var categories = Object.keys(state.categoryCounts);
    var next = categories[Math.floor(Math.random() * categories.length)];
    state.currentCategory = next;
    ddSetBubble(gameTitle, ddCategoryDescriptions(next, state.categoryHints[next]));
}

function ddWireBoard(section) {
    if (section.dataset.wired) return;
    section.dataset.wired = '1';

    var gameTitle = section.dataset.gameTitle;
    var board = section.querySelector('.dd-puzzle-board');
    var bank = section.querySelector('.dd-item-row[data-role="bank"]');

    // Delegate dragstart/dragend so it also works for cards moved
    // between zones later (event delegation avoids re-binding).
    board.addEventListener('dragstart', function (e) {
        var item = e.target.closest('.dd-card');
        if (!item) return;
        _ddDraggedEl = item;
        item.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', item.dataset.item);
    });
    board.addEventListener('dragend', function (e) {
        var item = e.target.closest('.dd-card');
        if (!item) return;
        item.classList.remove('dragging');
        _ddDraggedEl = null;

        // NEW — safety net: dragleave doesn't always fire reliably when a
        // placed item is dragged back out of the card it started in, which
        // left the cyan "drag-over" highlight stuck. Clear it from every
        // target card whenever any drag ends, no matter how it ended.
        section.querySelectorAll('.dd-target-card.drag-over').forEach(function (c) {
            c.classList.remove('drag-over');
        });
    });

    board.querySelectorAll('.dd-target-card').forEach(function (card) {
        card.addEventListener('dragover', function (e) {
            e.preventDefault();
            card.classList.add('drag-over');
        });
        card.addEventListener('dragleave', function () {
            card.classList.remove('drag-over');
        });
        card.addEventListener('drop', function (e) {
            e.preventDefault();
            card.classList.remove('drag-over');
            ddDropInto(gameTitle, card.querySelector('.dd-socket'));
        });
    });

    // Dropping a placed card back onto the bank removes its answer.
    if (bank) {
        bank.addEventListener('dragover', function (e) {
            e.preventDefault();
        });
        bank.addEventListener('drop', function (e) {
            e.preventDefault();
            if (_ddDraggedEl && _ddDraggedEl.classList.contains('placed')) {
                ddReturnToBank(gameTitle, _ddDraggedEl);
            }
        });
    }
}

function ddDropInto(gameTitle, socket) {
    if (!_ddDraggedEl) return;
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) return;

    var slot = socket.querySelector('.dd-socket-slot');
    var targetCard = socket.closest('.dd-target-card');
    var item = _ddDraggedEl;
    var chosenCategory = socket.dataset.category;

    // Always accept the drop, right or wrong — correctness is only
    // revealed later, on the Finish/results screen.
    slot.appendChild(item);
    item.classList.remove('dragging');
    item.classList.add('placed');
    // Stays draggable so the student can re-drag it to another
    // category, or back into the item bank to remove it.
    item.setAttribute('draggable', 'true');

    ddAddRemoveBtn(item, gameTitle);

    state.answers[item.dataset.item] = chosenCategory; // plain word, no JSON
    targetCard.classList.add('has-items');

    ddSetFeedback(section, '"' + item.dataset.item + '" placed in "' + chosenCategory + '".');
    ddUpdateBoardNav(gameTitle);
    ddPickNextCategoryHint(gameTitle);
}

// Adds a small × button to a placed card so it can be removed
// back to the item bank with a tap, without needing to drag.
function ddAddRemoveBtn(item, gameTitle) {
    if (item.querySelector('.dd-card-remove')) return; // already has one
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'dd-card-remove';
    btn.innerHTML = '&times;';
    btn.setAttribute('aria-label', 'Remove');
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        ddReturnToBank(gameTitle, item);
    });
    item.style.position = 'relative';
    item.appendChild(btn);
}

// Moves a placed card back into the item bank and clears its answer.
function ddReturnToBank(gameTitle, item) {
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) return;

    var bank = section.querySelector('.dd-item-row[data-role="bank"]');
    if (!bank) return;

    delete state.answers[item.dataset.item];

    item.classList.remove('placed');
    var removeBtn = item.querySelector('.dd-card-remove');
    if (removeBtn) removeBtn.remove();

    // Move the card back into the bank FIRST — only after it's actually
    // out of the socket will slot.children.length correctly read 0.
    bank.appendChild(item);

    // NOW clear the "has-items" highlight on any target zone that's empty
    section.querySelectorAll('.dd-target-card').forEach(function (card) {
        var slot = card.querySelector('.dd-socket-slot');
        if (slot && !slot.children.length) card.classList.remove('has-items');
    });

    var section2 = ddFindSection(gameTitle);
    ddSetFeedback(section2, '"' + item.dataset.item + '" moved back to the item bank.');
    ddUpdateBoardNav(gameTitle);
    ddPickNextCategoryHint(gameTitle);
}

function ddSetFeedback(section, message) {
    var el = section.querySelector('.dd-feedback');
    if (!el) return;
    el.textContent = message;
    el.classList.remove('is-correct', 'is-wrong');
}

function ddSpawnParticles(targetCard, emoji) {
    if (!targetCard) return;
    var particle = document.createElement('span');
    particle.textContent = emoji || '✨';
    particle.style.cssText = [
        'position:absolute', 'top:50%', 'left:50%', 'transform:translate(-50%,-50%)',
        'font-size:22px', 'pointer-events:none', 'opacity:1',
        'transition:transform .6s ease, opacity .6s ease', 'z-index:5'
    ].join(';');
    targetCard.style.position = targetCard.style.position || 'relative';
    targetCard.appendChild(particle);
    requestAnimationFrame(function () {
        particle.style.transform = 'translate(-50%, -140%) scale(1.4)';
        particle.style.opacity = '0';
    });
    setTimeout(function () { particle.remove(); }, 650);
}

function ddUpdateBoardNav(gameTitle) {
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) return;

    var total = state.items.length;
    var placed = Object.keys(state.answers).length;

    var counter = section.querySelector('.dd-counter');
    if (counter) counter.textContent = placed + ' of ' + total + ' placed';

    // NEW — actually fill the bar
    var fill = section.querySelector('.dd-progress-fill');
    if (fill) {
        var pct = total > 0 ? Math.round((placed / total) * 100) : 0;
        fill.style.width = pct + '%';
    }

    var finishBtn = section.querySelector('.dd-finish-btn');
    if (finishBtn) {
        var allPlaced = (placed >= total);
        finishBtn.disabled = !allPlaced;
        finishBtn.style.display = allPlaced ? 'flex' : 'none';
    }
}

function openDragDropStage(gameTitle) {
    var section = ddFindSection(gameTitle);
    var overlay = document.getElementById('ddOverlay');
    if (!section || !overlay) return;

    if (overlay.parentElement !== document.body) document.body.appendChild(overlay);

    if (!section.dataset.homeMarked) {
        var marker = document.createElement('div');
        marker.className = 'dd-home-marker';
        marker.style.display = 'none';
        marker.dataset.gameTitle = gameTitle;
        section.parentNode.insertBefore(marker, section);
        section.dataset.homeMarked = '1';
    }

    overlay.appendChild(section);
    section.style.display = 'block';
    overlay.classList.remove('qz-closing');
    overlay.classList.add('open');
    overlay.scrollTop = 0;

    document.body.dataset.prevOverflow = document.body.style.overflow || '';
    document.body.style.overflow = 'hidden';

    var board = section.querySelector('.dd-board');
    var items = [];
    var categoryHints = {};
    try { items = JSON.parse(board.dataset.items || '[]'); } catch (e) { items = []; }
    try { categoryHints = JSON.parse(board.dataset.categoryHints || '{}'); } catch (e) { categoryHints = {}; }

    ddInitState(gameTitle, items, categoryHints);
    ddWireBoard(section);
    ddUpdateBoardNav(gameTitle);
    ddPickNextCategoryHint(gameTitle);
    adjustBonBonPosition('#ddOverlay', 450);

    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
        sessionStorage.setItem('dragdrop_open_lesson_' + LESSON_DATA.lessonId, gameTitle);
    }
    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.moduleId) {
        sessionStorage.setItem('lessons_view_module_' + LESSON_DATA.moduleId, 'lesson');
    }
}

function closeDragDropStage(gameTitle) {
    var section = ddFindSection(gameTitle);
    var overlay = document.getElementById('ddOverlay');
    var marker = document.querySelector('.dd-home-marker[data-game-title="' + CSS.escape(gameTitle) + '"]');
    if (!section || !overlay) return;

    var state = DD_STAGE[gameTitle];
    if (state && state.justCompleted) {
        // Just finished this activity — reload so the lesson page re-renders
        // from the server with "Review the Activity" instead of the stale
        // "Take the Activity" markup.
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('dragdrop_open_lesson_' + LESSON_DATA.lessonId);
        }
        window.location.reload();
        return;
    }

    overlay.classList.add('qz-closing');
    setTimeout(function () {
        if (marker && marker.parentNode) marker.parentNode.insertBefore(section, marker);
        section.style.display = 'none';
        overlay.classList.remove('open', 'qz-closing');
        document.body.style.overflow = document.body.dataset.prevOverflow || '';
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('dragdrop_open_lesson_' + LESSON_DATA.lessonId);
        }
    }, 280);
}

function ddReviewFindSection(gameTitle) {
    return document.querySelector('.dd-review-section[data-game-title="' + CSS.escape(gameTitle) + '"]');
}

function openDragDropReviewStage(gameTitle) {
    var section = ddReviewFindSection(gameTitle);
    var overlay = document.getElementById('ddOverlay');
    if (!section || !overlay) return;

    if (overlay.parentElement !== document.body) {
        document.body.appendChild(overlay);
    }

    if (!section.dataset.homeMarked) {
        var marker = document.createElement('div');
        marker.className = 'dd-review-home-marker';
        marker.style.display = 'none';
        marker.dataset.gameTitle = gameTitle;
        section.parentNode.insertBefore(marker, section);
        section.dataset.homeMarked = '1';
    }

    overlay.appendChild(section);
    section.style.display = 'block';
    overlay.classList.remove('qz-closing');
    overlay.classList.add('open');
    overlay.scrollTop = 0;

    document.body.dataset.prevOverflow = document.body.style.overflow || '';
    document.body.style.overflow = 'hidden';

    var msgEl = document.getElementById('ddOverlayMessage');
    if (msgEl) msgEl.textContent = 'Here\'s how you did on "' + gameTitle + '"!';

    adjustBonBonPosition('#ddOverlay', 450);

    // Remember that the review stage is open, so a reload restores it
    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
        sessionStorage.setItem('dragdrop_review_open_lesson_' + LESSON_DATA.lessonId, gameTitle);
    }
    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.moduleId) {
        sessionStorage.setItem('lessons_view_module_' + LESSON_DATA.moduleId, 'lesson');
    }
}

function closeDragDropReviewStage(gameTitle) {
    var section = ddReviewFindSection(gameTitle);
    var overlay = document.getElementById('ddOverlay');
    var marker = document.querySelector('.dd-review-home-marker[data-game-title="' + CSS.escape(gameTitle) + '"]');
    if (!section || !overlay) return;

    overlay.classList.add('qz-closing');
    setTimeout(function () {
        if (marker && marker.parentNode) marker.parentNode.insertBefore(section, marker);
        section.style.display = 'none';
        overlay.classList.remove('open', 'qz-closing');
        document.body.style.overflow = document.body.dataset.prevOverflow || '';

        // Clear the flag once the user actually leaves the review
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('dragdrop_review_open_lesson_' + LESSON_DATA.lessonId);
        }
    }, 280);
}

function ddSubmit(gameTitle) {
    var state = DD_STAGE[gameTitle];
    if (!state) return;
    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;

    var fd = new FormData();
    fd.append('lesson_id', lessonId);
    fd.append('game_title', gameTitle);
    Object.keys(state.answers).forEach(function (item) {
        fd.append('answers[' + item + ']', state.answers[item]);
    });

    console.log('[ddSubmit] sending', { lessonId: lessonId, gameTitle: gameTitle, answers: state.answers });

    fetch('/learning_management/public/?url=submit_dragdrop', { method: 'POST', body: fd })
        .then(function (r) {
            console.log('[ddSubmit] HTTP status:', r.status);
            return r.text().then(function (text) {
                console.log('[ddSubmit] raw response:', text);
                var json;
                try { json = JSON.parse(text); } catch (e) { json = null; }
                return json;
            });
        })
        .then(function (resp) {
            if (!resp || resp.ok !== true) {
                console.error('[ddSubmit] server rejected save:', resp);
                alert('There was a problem saving your answers: ' + (resp && resp.msg ? resp.msg : 'unknown error') + '. Please try again.');
                return;
            }

            var correct = (typeof resp.score === 'number') ? resp.score : state.items.length;
            var total = (typeof resp.total === 'number') ? resp.total : state.items.length;

            state.justCompleted = true; // flag this so closing the overlay reloads instead of just hiding

            if (LESSON_DATA && LESSON_DATA.dragdrops) {
                LESSON_DATA.dragdrops.forEach(function (dd) { if (dd.title === gameTitle) dd.done = true; });
            }
            checkLessonComplete();
            ddShowResults(gameTitle, correct, total);
        })
        .catch(function (err) {
            console.error('[ddSubmit] fetch FAILED:', err);
            alert('Could not reach the server to save your answers. Please check your connection and try again.');
        });

}

/* =========================================================
   FIXED: BonBon overlay repositioning
   -----------------------------------------------------------
   Old behavior only nudged the box up with a transform, which
   could get overridden by `position: sticky` recalculating on
   reflow/scroll, and it was measured before webfonts/images had
   finished loading — so long category messages still overflowed
   past the bottom of the screen.

   Fix:
   1. Reading getBoundingClientRect() forces a synchronous layout,
      so we don't need to guess with rAF timing for the *text*
      itself — but we still allow an optional delay for callers
      that fire this right as the overlay is opening (before the
      robot image has necessarily painted).
   2. We re-run the measurement again once the robot image
      reports "loaded", since its intrinsic height (200px wide,
      height:auto) isn't known until then and can silently throw
      off the calculation on first paint.
   3. We clear any previous transform before measuring so stale
      offsets don't compound.
========================================================= */
function adjustBonBonPosition(overlaySelector, delay) {
    var overlay = document.querySelector(overlaySelector);
    var parent = overlay && overlay.querySelector('.BonBon-parent');
    if (!overlay || !parent) return;

    function measure() {
        parent.style.transform = '';

        // Force layout to settle (two rAFs is enough for text reflow +
        // animation class changes; getBoundingClientRect below forces
        // a synchronous layout read).
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                var rect = parent.getBoundingClientRect();
                var overlayRect = overlay.getBoundingClientRect();
                var buffer = 24;
                var overflow = rect.bottom - overlayRect.bottom + buffer;

                if (overflow > 0) {
                    parent.style.transform = 'translateY(-' + overflow + 'px)';
                } else {
                    parent.style.transform = '';
                }
            });
        });
    }

    if (delay) {
        setTimeout(measure, delay);
    } else {
        measure();
    }

    // Re-measure once the robot image itself has finished loading —
    // its real height isn't known beforehand, and a late layout shift
    // from the image was the main reason long messages still hung off
    // the bottom of the screen even after the transform was applied.
    var img = parent.querySelector('img');
    if (img && !img.complete) {
        img.addEventListener('load', measure, { once: true });
    }

    // Also re-measure on resize while this overlay is open, since a
    // window resize changes overlayRect.bottom without re-triggering
    // any of the code paths above.
    if (!overlay.dataset.resizeBound) {
        overlay.dataset.resizeBound = '1';
        window.addEventListener('resize', function () {
            if (overlay.classList.contains('open')) measure();
        });
    }
}

function ddShowResults(gameTitle, correct, total) {
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) { closeDragDropStage(gameTitle); return; }

    var board = section.querySelector('.dd-board');
    var results = section.querySelector('.dd-results');
    if (board) board.style.display = 'none';

    var pct = total > 0 ? Math.round((correct / total) * 100) : 0;
    var scoreMsg = 'You matched ' + correct + ' out of ' + total + ' correctly (' + pct + '%)! ' +
        (pct === 100 ? 'Perfect score!' : pct >= 70 ? 'Great job!' : 'Nice try — review your answers below.');
    ddSetBubble(gameTitle, scoreMsg);

    if (results) {
        results.style.display = 'block';
        var fill = results.querySelector('.qz-accuracy-fill');
        var pctLabel = results.querySelector('.qz-accuracy-pct');
        if (fill) requestAnimationFrame(function () { fill.style.width = pct + '%'; });
        if (pctLabel) pctLabel.textContent = pct + '%';

        var list = results.querySelector('.dd-review-list');
        if (list) {
            list.innerHTML = '';
            state.items.forEach(function (item) {
                var given = state.answers[item.label];
                var isCorrect = given === item.category;
                var row = document.createElement('div');
                row.className = 'question-card';
                row.innerHTML =
                    '<div class="q-num-label">Item</div>' +
                    '<div class="q-text">' + escHtml(item.label) + '</div>' +
                    (item.subtitle ? '<div style="font-size:12.5px;color:var(--text-dim);margin:-6px 0 10px;">' + escHtml(item.subtitle) + '</div>' : '') +
                    '<div class="review-choice" style="' + (isCorrect ? 'border-color:#22c55e;background:#f0fdf4;' : 'border-color:#ef4444;background:#fef2f2;') + '">' +
                    '<span style="font-weight:700;margin-right:8px;color:' + (isCorrect ? '#22c55e' : '#ef4444') + ';">' +
                    (isCorrect ? '✓ Correct' : '✗ Incorrect') +
                    '</span>' +
                    'Your answer: ' + escHtml(given || '—') +
                    (!isCorrect ? '<span style="margin-left:auto;color:#ef4444;">Correct: ' + escHtml(item.category) + '</span>' : '') +
                    '</div>';
                list.appendChild(row);
            });
        }
    }

}

/* =========================================================
   NEXT / PREV / FINISH — DELEGATED CLICK HANDLER
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
    if (typeof LESSON_DATA === 'undefined' || !LESSON_DATA.lessonId) return;
    var ddFlag = sessionStorage.getItem('dragdrop_open_lesson_' + LESSON_DATA.lessonId);
    if (ddFlag) {
        openDragDropStage(ddFlag);
    }
    // Restore the review overlay if it was open before the reload
    var ddReviewFlag = sessionStorage.getItem('dragdrop_review_open_lesson_' + LESSON_DATA.lessonId);
    if (ddReviewFlag) {
        openDragDropReviewStage(ddReviewFlag);
    }
    try {
        var qzBlocks = document.querySelectorAll('.qz-question-block:not(.act-question-block)');
        UNIFIED_QZ.total = qzBlocks.length;

        var actBlocks = document.querySelectorAll('.act-question-block');

        // Wire up essay/short-answer textarea tracking — this also
        // covers the new activity-stage textareas since they share
        // the .activity-answer class.
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
                if (typeof actUpdateNav === 'function') actUpdateNav();
            });
        });

        checkLessonComplete();

        if (qzBlocks.length) {
            qzRestoreSelection(qzBlocks[0]);
            qzUpdateNav();
        }

        if (actBlocks.length) {
            actRestoreSelection(actBlocks[0]);
            actUpdateNav();
        }

        var nextBtnEl = document.getElementById('nextBtn');
        console.log('[lessons.js] nextBtn found:', nextBtnEl ? nextBtnEl.tagName : 'NOT FOUND');
        console.log('[lessons.js] init complete — delegated click handler is active');
    } catch (err) {
        console.error('[lessons.js] init error (buttons are unaffected):', err);
    }
});


/* ==========================
   ACTIVITY MC PICKER
   -----------------------------------------------------------
   Used both by the old inline activity form (.question-card)
   and the new activity-stage carousel (.qz-question-block).
========================== */
function pickMC(el) {
    var qid = el.dataset.qid;
    var actId = parseInt(el.dataset.actId);
    var key = el.dataset.key;

    var container = el.closest('.question-card') || el.closest('.qz-question-block');
    if (container) {
        container.querySelectorAll('.mc-choice, .qz-choice-btn').forEach(function (c) {
            c.classList.remove('selected');
        });
    }
    el.classList.add('selected');

    if (!ANSWERS.activities[actId]) ANSWERS.activities[actId] = {};
    ANSWERS.activities[actId][qid] = key;

    var hidden = document.getElementById('mc_hidden_' + qid);
    if (hidden) hidden.value = key;

    checkLessonComplete();
    if (typeof actUpdateNav === 'function') actUpdateNav();
}

/* =========================================================
   QUIZIZZ-STYLE QUIZ STAGE
========================================================= */
function qzPick(el) {
    var qid = el.dataset.qid;
    var key = el.dataset.key;
    var qzid = parseInt(el.dataset.qzid);

    el.closest('.qz-question-block').querySelectorAll('.qz-choice-btn').forEach(function (c) {
        c.classList.remove('selected');
    });
    el.classList.add('selected');

    UNIFIED_QZ.ans[qid] = key;
    if (!ANSWERS.quizzes[qzid]) ANSWERS.quizzes[qzid] = {};
    ANSWERS.quizzes[qzid][qid] = key;

    var cnt = document.getElementById('unified_status');
    if (cnt) cnt.textContent = Object.keys(UNIFIED_QZ.ans).length + ' / ' + UNIFIED_QZ.total + ' answered';

    qzUpdateNav();
    checkLessonComplete();
}

function qzNav(dir) {
    var blocks = document.querySelectorAll('.qz-question-block:not(.act-question-block)');
    if (!blocks.length) return;

    var newIdx = Math.max(0, Math.min(blocks.length - 1, UNIFIED_QZ.cur + dir));
    if (newIdx === UNIFIED_QZ.cur) return;

    blocks[UNIFIED_QZ.cur].style.display = 'none';
    UNIFIED_QZ.cur = newIdx;
    blocks[UNIFIED_QZ.cur].style.display = 'block';

    qzRestoreSelection(blocks[UNIFIED_QZ.cur]);
    qzUpdateNav();
}

function qzRestoreSelection(block) {
    if (!block) return;
    block.querySelectorAll('.qz-choice-btn').forEach(function (btn) {
        var qid = btn.dataset.qid;
        var key = btn.dataset.key;
        if (!qid || !key) return;
        if (UNIFIED_QZ.ans[qid] === key) {
            btn.classList.add('selected');
        } else {
            btn.classList.remove('selected');
        }
    });
}

function qzUpdateNav() {
    var blocks = document.querySelectorAll('.qz-question-block:not(.act-question-block)');
    var total = blocks.length;
    if (!total) return;

    var counter = document.getElementById('qzCounter');
    if (counter) counter.textContent = 'Question ' + (UNIFIED_QZ.cur + 1) + ' of ' + total;

    var fill = document.getElementById('qzProgressFill');
    if (fill) fill.style.width = Math.round(((UNIFIED_QZ.cur + 1) / total) * 100) + '%';

    var prevBtn = document.getElementById('qzPrevBtn');
    if (prevBtn) prevBtn.style.visibility = UNIFIED_QZ.cur > 0 ? 'visible' : 'hidden';

    var nextBtn = document.getElementById('qzNextBtn');
    if (nextBtn) {
        var curBlock = blocks[UNIFIED_QZ.cur];
        var firstChoice = curBlock ? curBlock.querySelector('.qz-choice-btn') : null;
        var curQid = firstChoice ? firstChoice.dataset.qid : null;
        var answered = !!(curQid && UNIFIED_QZ.ans[curQid]);
        nextBtn.disabled = !answered;

        if (UNIFIED_QZ.cur === total - 1) {
            nextBtn.innerHTML = 'Finish <i class="fa fa-check"></i>';
            nextBtn.onclick = function () {
                checkLessonComplete();
                showQuizResults();
            };
        } else {
            nextBtn.innerHTML = 'Next <i class="fa fa-chevron-right"></i>';
            nextBtn.onclick = function () { qzNav(1); };
        }
    }
}

/* ==========================
   QUIZ RESULTS SCREEN
========================== */
function showQuizResults() {
    var stage = document.getElementById('qzStage');
    var results = document.getElementById('qzResults');

    if (!stage || !results) {
        var lessonNextBtn = document.getElementById('nextBtn');
        if (lessonNextBtn && !lessonNextBtn.classList.contains('disabled')) {
            lessonNextBtn.click();
        }
        return;
    }

    var buttons = document.querySelectorAll('.qz-choice-btn[data-correct]');
    var seen = {};
    var total = 0;
    var correct = 0;

    buttons.forEach(function (btn) {
        var qid = btn.dataset.qid;
        if (!qid || seen[qid]) return;
        seen[qid] = true;
        total++;

        var picked = UNIFIED_QZ.ans[qid];
        var correctBtn = document.querySelector('.qz-choice-btn[data-qid="' + qid + '"][data-correct="1"]');
        var correctKey = correctBtn ? correctBtn.dataset.key : null;

        if (picked && correctKey && picked === correctKey) correct++;
    });

    var incorrect = total - correct;
    var accuracy = total > 0 ? Math.round((correct / total) * 100) : 0;

    var countEl = document.getElementById('qzResultCount');
    var pillCorrect = document.getElementById('qzPillCorrect');
    var pillIncorrect = document.getElementById('qzPillIncorrect');
    var fill = document.getElementById('qzAccuracyFill');
    var pct = document.getElementById('qzAccuracyPct');

    if (countEl) countEl.textContent = total + ' question' + (total === 1 ? '' : 's');
    if (pillCorrect) pillCorrect.innerHTML = '<i class="fa fa-check"></i> ' + correct + ' Correct';
    if (pillIncorrect) pillIncorrect.innerHTML = '<i class="fa fa-times"></i> ' + incorrect + ' Incorrect';
    if (pct) pct.textContent = accuracy + '%';

    stage.style.display = 'none';
    results.style.display = 'block';

    var exitBtn = document.querySelector('#section-quizzes .btn-exit-quiz');
    if (exitBtn) exitBtn.style.display = 'none';

    if (fill) {
        fill.style.width = '0%';
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                fill.style.width = accuracy + '%';
            });
        });
    }

    var continueBtn = document.getElementById('qzResultsContinueBtn');
    if (continueBtn) {
        continueBtn.onclick = function () {
            var lessonNextBtn = document.getElementById('nextBtn');
            if (lessonNextBtn && !lessonNextBtn.classList.contains('disabled')) {
                lessonNextBtn.click();
            } else {
                if (exitBtn) exitBtn.style.display = '';
                closeQuizStage();
            }
        };
    }
}

/* =========================================================
   ACTIVITY STAGE — one question at a time, same visual
   language as the quiz (dark card + choices, or a simple
   fill-in-the-blank textarea for essay-style questions).
   Answers are tracked in the same ANSWERS.activities store
   used by the old inline form, so saving/submission logic
   (saveAndGo / submitActivitiesSequentially) needs no changes.
========================================================= */
function actNav(dir) {
    var blocks = document.querySelectorAll('.act-question-block');
    if (!blocks.length) return;

    var newIdx = Math.max(0, Math.min(blocks.length - 1, ACT_STAGE.cur + dir));
    if (newIdx === ACT_STAGE.cur) return;

    blocks[ACT_STAGE.cur].style.display = 'none';
    ACT_STAGE.cur = newIdx;
    blocks[ACT_STAGE.cur].style.display = 'block';

    actRestoreSelection(blocks[ACT_STAGE.cur]);
    actUpdateNav();
}

function actRestoreSelection(block) {
    if (!block) return;

    var textarea = block.querySelector('.qz-activity-textarea');
    if (textarea) {
        var actId = textarea.dataset.actId;
        var qid = textarea.dataset.qid;
        var stored = (actId && qid && ANSWERS.activities[actId]) ? ANSWERS.activities[actId][qid] : '';
        textarea.value = stored || '';
        return;
    }

    block.querySelectorAll('.qz-choice-btn').forEach(function (btn) {
        var actId = btn.dataset.actId;
        var qid = btn.dataset.qid;
        var key = btn.dataset.key;
        if (!actId || !qid || !key) return;
        var stored = ANSWERS.activities[actId] ? ANSWERS.activities[actId][qid] : null;
        if (stored === key) {
            btn.classList.add('selected');
        } else {
            btn.classList.remove('selected');
        }
    });
}

function actUpdateNav() {
    var blocks = document.querySelectorAll('.act-question-block');
    var total = blocks.length;
    if (!total) return;

    var counter = document.getElementById('actCounter');
    if (counter) counter.textContent = 'Question ' + (ACT_STAGE.cur + 1) + ' of ' + total;

    var fill = document.getElementById('actProgressFill');
    if (fill) fill.style.width = Math.round(((ACT_STAGE.cur + 1) / total) * 100) + '%';

    var prevBtn = document.getElementById('actPrevBtn');
    if (prevBtn) prevBtn.style.visibility = ACT_STAGE.cur > 0 ? 'visible' : 'hidden';

    var nextBtn = document.getElementById('actNextBtn');
    if (nextBtn) {
        var curBlock = blocks[ACT_STAGE.cur];
        var actId = curBlock ? curBlock.dataset.actId : null;
        var answered = false;

        var textarea = curBlock ? curBlock.querySelector('.qz-activity-textarea') : null;
        if (textarea) {
            var qid = textarea.dataset.qid;
            answered = !!(actId && qid && ANSWERS.activities[actId] && ANSWERS.activities[actId][qid]);
        } else {
            var firstChoice = curBlock ? curBlock.querySelector('.qz-choice-btn') : null;
            var cqid = firstChoice ? firstChoice.dataset.qid : null;
            answered = !!(actId && cqid && ANSWERS.activities[actId] && ANSWERS.activities[actId][cqid]);
        }

        nextBtn.disabled = !answered;

        if (ACT_STAGE.cur === total - 1) {
            nextBtn.innerHTML = 'Finish <i class="fa fa-check"></i>';
            nextBtn.onclick = function () {
                checkLessonComplete();
                closeActivityStage();
            };
        } else {
            nextBtn.innerHTML = 'Next <i class="fa fa-chevron-right"></i>';
            nextBtn.onclick = function () { actNav(1); };
        }
    }
}

/* ==========================
   QUIZ REVIEW NAVIGATION
========================== */
var QZ_REVIEW = { cur: 0 };

function qzReviewInit() {
    var blocks = document.querySelectorAll('.qz-review-block');
    if (!blocks.length) return;
    qzReviewUpdateNav();
}

function qzReviewNav(dir) {
    var blocks = document.querySelectorAll('.qz-review-block');
    if (!blocks.length) return;

    var newIdx = Math.max(0, Math.min(blocks.length - 1, QZ_REVIEW.cur + dir));
    if (newIdx === QZ_REVIEW.cur) return;

    blocks[QZ_REVIEW.cur].style.display = 'none';
    QZ_REVIEW.cur = newIdx;
    blocks[QZ_REVIEW.cur].style.display = 'block';

    qzReviewUpdateNav();
}

function qzReviewUpdateNav() {
    var blocks = document.querySelectorAll('.qz-review-block');
    var total = blocks.length;
    if (!total) return;

    var counter = document.getElementById('qzReviewCounter');
    if (counter) counter.textContent = 'Question ' + (QZ_REVIEW.cur + 1) + ' of ' + total;

    var fill = document.getElementById('qzReviewProgressFill');
    if (fill) fill.style.width = Math.round(((QZ_REVIEW.cur + 1) / total) * 100) + '%';

    var prevBtn = document.getElementById('qzReviewPrevBtn');
    if (prevBtn) prevBtn.style.visibility = QZ_REVIEW.cur > 0 ? 'visible' : 'hidden';

    var nextBtn = document.getElementById('qzReviewNextBtn');
    if (nextBtn) {
        nextBtn.style.display = 'flex';

        if (QZ_REVIEW.cur === total - 1) {
            nextBtn.innerHTML = 'View Result <i class="fa fa-arrow-right"></i>';
            nextBtn.onclick = function () { showReviewResults(); };
        } else {
            nextBtn.innerHTML = 'Next <i class="fa fa-chevron-right"></i>';
            nextBtn.onclick = function () { qzReviewNav(1); };
        }
    }
}

/* ==========================
   QUIZ REVIEW RESULTS SCREEN
========================== */
function showReviewResults() {
    var stage = document.getElementById('qzReviewStage');
    var results = document.getElementById('qzReviewResults');
    if (!stage || !results) return;

    stage.style.display = 'none';
    results.style.display = 'block';

    var exitBtn = document.querySelector('#section-quizzes .btn-exit-quiz');
    if (exitBtn) exitBtn.style.display = 'none';

    var backBtn = document.getElementById('qzReviewResultsBackBtn');
    if (backBtn) {
        backBtn.onclick = function () {
            results.style.display = 'none';
            stage.style.display = 'block';
            if (exitBtn) exitBtn.style.display = '';
        };
    }
}

document.addEventListener('DOMContentLoaded', qzReviewInit);

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
        (LESSON_DATA.dragdrops || []).forEach(function (dd) { if (!dd.done) hasPending = true; });

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

        (LESSON_DATA.dragdrops || []).forEach(function (dd) {
            if (dd.done) return;
            var state = DD_STAGE[dd.title];
            if (!state || Object.keys(state.answers).length < dd.required) allDone = false;
        });

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
            activities: ANSWERS.activities,
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
    var lb = document.getElementById('dbLightbox');

    if (lb.parentElement !== document.body) {
        document.body.appendChild(lb);
    }

    document.getElementById('dbLightboxImg').src = src;
    lb.classList.add('open');

    document.body.dataset.prevOverflow = document.body.style.overflow || '';
    document.body.style.overflow = 'hidden';
}
function dbLightboxClose() {
    var lb = document.getElementById('dbLightbox');
    lb.classList.remove('open');
    document.body.style.overflow = document.body.dataset.prevOverflow || '';
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') dbLightboxClose();
});