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

var QZ_LOCKED = false;

var QUIZ_JUST_COMPLETED = false;

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

var DD_PER_PAGE = 3;

var DD_NOTE_COLORS = ['#fff6e5', '#ffe8c2', '#e3f2e8', '#efe3f5', '#e6f0ff'];

function ddInitState(gameTitle, itemsFromDom, categoryHints) {
    if (!DD_STAGE[gameTitle]) {
        var items = itemsFromDom || [];
        var categoryCounts = {};
        items.forEach(function (it) {
            categoryCounts[it.category] = (categoryCounts[it.category] || 0) + 1;
        });
        DD_STAGE[gameTitle] = {
            items: items,
            categoryCounts: categoryCounts,
            categoryHints: categoryHints || {},
            solvedCounts: {},
            answers: {},
            currentCategory: null,
            selectedItemEl: null,
            page: 0,
            totalPages: Math.max(1, Math.ceil(items.length / DD_PER_PAGE))
        };
    }
    return DD_STAGE[gameTitle];
}

// Items that belong to a given page (same order as the shuffled bank)
function ddPageItems(state, page) {
    return state.items.slice(page * DD_PER_PAGE, page * DD_PER_PAGE + DD_PER_PAGE);
}

function ddPageComplete(state, page) {
    return ddPageItems(state, page).every(function (it) {
        return !!state.answers[it.label];
    });
}

// Tags every card with the page it belongs to (done once)
// Tags every card with its page, note color and a slight tilt (done once)
function ddAssignPages(section, state) {
    section.querySelectorAll('.dd-card').forEach(function (card) {
        if (card.dataset.page !== undefined) return;

        var idx = -1;
        state.items.some(function (it, i) {
            if (it.label === card.dataset.item) { idx = i; return true; }
            return false;
        });
        idx = Math.max(idx, 0);

        card.dataset.page = Math.floor(idx / DD_PER_PAGE);
        card.style.setProperty('--note-bg', DD_NOTE_COLORS[idx % DD_NOTE_COLORS.length]);
        card.style.setProperty('--note-tilt', (Math.random() * 2 - 1).toFixed(2) + 'deg');
    });
}

// Recomputes "has-items" / "Drop here" per category based on VISIBLE cards only
function ddRefreshZones(section) {
    section.querySelectorAll('.dd-target-card').forEach(function (card) {
        var slot = card.querySelector('.dd-socket-slot');
        if (!slot) return;
        var visible = Array.prototype.filter.call(
            slot.querySelectorAll('.dd-card'),
            function (c) { return c.style.display !== 'none'; }
        ).length;
        card.classList.toggle('has-items', visible > 0);
        slot.classList.toggle('is-empty', visible === 0);
    });
}

// Shows only the current page's cards (in the bank AND in the categories)
function ddRenderPage(gameTitle) {
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) return;

    section.querySelectorAll('.dd-card, .dd-card-placeholder').forEach(function (el) {
        var onPage = parseInt(el.dataset.page, 10) === state.page;
        el.style.display = onPage ? '' : 'none';
    });

    if (state.selectedItemEl && parseInt(state.selectedItemEl.dataset.page, 10) !== state.page) {
        state.selectedItemEl.classList.remove('selected');
        state.selectedItemEl = null;
    }

    ddRefreshZones(section);
}

var DD_CATS_PER_PAGE = 3;

function ddShuffle(arr) {
    var a = arr.slice();
    for (var i = a.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var t = a[i]; a[i] = a[j]; a[j] = t;
    }
    return a;
}

// The 3 categories for a page: the correct categories of that page's
// cards, topped up with random other categories, then shuffled.
// Saved per page so Prev/Next shows the same boxes again.
function ddGetPageCategories(section, state, page) {
    state.pageCategories = state.pageCategories || {};
    if (state.pageCategories[page]) return state.pageCategories[page];

    if (!state.allCategories) {
        state.allCategories = Array.prototype.map.call(
            section.querySelectorAll('.dd-target-card .dd-socket'),
            function (s) { return s.dataset.category; }
        );
    }

    var cats = [];
    ddPageItems(state, page).forEach(function (it) {
        if (cats.indexOf(it.category) === -1) cats.push(it.category);
    });

    var others = ddShuffle(state.allCategories.filter(function (c) {
        return cats.indexOf(c) === -1;
    }));
    while (cats.length < DD_CATS_PER_PAGE && others.length) {
        cats.push(others.shift());
    }

    state.pageCategories[page] = ddShuffle(cats);
    return state.pageCategories[page];
}

// Hides every category box, then shows ONLY this page's 3 (in shuffled order)
function ddShowPageCategories(gameTitle) {
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) return;

    var row = section.querySelector('.dd-target-row');
    if (!row) return;

    var cards = Array.prototype.slice.call(row.querySelectorAll('.dd-target-card'));
    var pageCats = ddGetPageCategories(section, state, state.page);

    cards.forEach(function (c) { c.style.setProperty('display', 'none', 'important'); });

    pageCats.forEach(function (cat) {
        var card = cards.filter(function (c) {
            return c.querySelector('.dd-socket').dataset.category === cat;
        })[0];
        if (card) {
            card.style.removeProperty('display');
            row.appendChild(card);
        }
    });

    ddRefreshZones(section);
}

function ddPageNav(gameTitle, dir) {
    var state = DD_STAGE[gameTitle];
    var section = ddFindSection(gameTitle);
    if (!state || !section) return;

    var newPage = state.page + dir;
    if (newPage < 0 || newPage >= state.totalPages) return;

    if (dir > 0 && !ddPageComplete(state, state.page)) {
        var left = ddPageItems(state, state.page).filter(function (it) {
            return !state.answers[it.label];
        }).length;

        ddSetBubble(gameTitle, 'Place the ' + left + ' remaining card' + (left === 1 ? '' : 's') +
            ' on this page into their categories before going to the next page!');

        section.querySelectorAll('.dd-item-row[data-role="bank"] .dd-card').forEach(function (card) {
            if (card.style.display === 'none') return;
            card.classList.remove('shake-wrong');
            void card.offsetWidth;
            card.classList.add('shake-wrong');
            setTimeout(function () { card.classList.remove('shake-wrong'); }, 450);
        });
        return;
    }

    state.page = newPage;
    ddRenderPage(gameTitle);
    ddShowPageCategories(gameTitle);   // only 3 boxes
    ddUpdateBoardNav(gameTitle);
    ddPickNextCategoryHint(gameTitle);
}

// function ddSetBubble(gameTitle, text) {
//     var section = ddFindSection(gameTitle);
//     var qEl = section && section.querySelector('.dd-question-text');
//     if (qEl) qEl.textContent = text;
//     var overlayMsg = document.getElementById('ddOverlayMessage');
//     if (overlayMsg) overlayMsg.textContent = text;

//     adjustBonBonPosition('#ddOverlay');
// }

function ddSetBubble(gameTitle, text) {
    var overlayMsg = document.getElementById('ddOverlayMessage');
    if (overlayMsg) overlayMsg.textContent = text;

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

    if (state.totalPages > 1 && state.page < state.totalPages - 1 && ddPageComplete(state, state.page)) {
        ddSetBubble(gameTitle, 'Nice! Every card on this page is placed. Tap "Next" to go to page ' +
            (state.page + 2) + ' of ' + state.totalPages + '.');
        state.currentCategory = null;
        return;
    }

    var categories = (state.pageCategories && state.pageCategories[state.page])
        ? state.pageCategories[state.page]
        : Object.keys(state.categoryCounts);
    var candidates = categories.filter(function (c) { return c !== state.currentCategory; });
    if (!candidates.length) candidates = categories;

    var next = candidates[Math.floor(Math.random() * candidates.length)];
    state.currentCategory = next;
    ddSetBubble(gameTitle, ddCategoryDescriptions(next, state.categoryHints[next]));
}

/* ===== Pointer-based drag & drop (replaces native HTML5 drag) ===== */
var _ddPtr = null;            // active drag info
var _ddSuppressClick = false; // swallow the click that follows a drag
var _ddDocBound = false;

function ddMakeGhost(card, rect) {
    var g = card.cloneNode(true);
    g.classList.remove('dragging', 'selected', 'placed');
    g.removeAttribute('draggable');
    var rm = g.querySelector('.dd-card-remove');
    if (rm) rm.remove();
    g.style.cssText =
        'position:fixed;left:' + rect.left + 'px;top:' + rect.top + 'px;' +
        'width:' + rect.width + 'px;height:' + rect.height + 'px;max-width:none;margin:0;' +
        'z-index:100000;pointer-events:none;opacity:1;transition:none;' +
        'transform:rotate(2deg) scale(1.04);cursor:grabbing;' +
        'box-shadow:0 8px 0 #a06b31, 0 18px 30px rgba(0,0,0,.45);';
    document.body.appendChild(g);
    return g;
}

function ddZoneFromPoint(x, y, section) {
    var el = document.elementFromPoint(x, y);
    if (!el) return null;
    var zone = el.closest('.dd-target-card');
    return (zone && section.contains(zone)) ? zone : null;
}

function ddClearHover(section) {
    section.querySelectorAll('.dd-target-card.drag-over').forEach(function (c) {
        c.classList.remove('drag-over');
    });
}

function ddBindDocPointer() {
    if (_ddDocBound) return;
    _ddDocBound = true;

    document.addEventListener('pointermove', function (e) {
        var p = _ddPtr;
        if (!p) return;

        if (!p.moved) {
            if (Math.abs(e.clientX - p.startX) < 5 && Math.abs(e.clientY - p.startY) < 5) return;
            p.moved = true;
            p.ghost = ddMakeGhost(p.card, p.rect);
            p.card.classList.add('dragging');
            document.body.style.userSelect = 'none';
        }

        e.preventDefault();
        p.ghost.style.left = (e.clientX - p.offX) + 'px';
        p.ghost.style.top = (e.clientY - p.offY) + 'px';

        var zone = ddZoneFromPoint(e.clientX, e.clientY, p.section);
        if (zone !== p.zone) {
            if (p.zone) p.zone.classList.remove('drag-over');
            if (zone) zone.classList.add('drag-over');
            p.zone = zone;
        }
    }, { passive: false });

    function finish(e, cancelled) {
        var p = _ddPtr;
        if (!p) return;
        _ddPtr = null;
        if (!p.moved) return;                       // it was just a click

        document.body.style.userSelect = '';
        _ddSuppressClick = true;
        setTimeout(function () { _ddSuppressClick = false; }, 0);

        var zone = cancelled ? null : ddZoneFromPoint(e.clientX, e.clientY, p.section);
        var el = cancelled ? null : document.elementFromPoint(e.clientX, e.clientY);
        var overBank = !!(el && el.closest('.dd-item-row[data-role="bank"]'));

        if (p.ghost) p.ghost.remove();
        p.card.classList.remove('dragging');
        ddClearHover(p.section);

        _ddDraggedEl = p.card;
        if (zone) {
            ddDropInto(p.gameTitle, zone.querySelector('.dd-socket'));
        } else if (overBank && p.card.classList.contains('placed')) {
            ddReturnToBank(p.gameTitle, p.card);
        }
        _ddDraggedEl = null;
    }

    document.addEventListener('pointerup', function (e) { finish(e, false); });
    document.addEventListener('pointercancel', function (e) { finish(e, true); });
}

function ddWireBoard(section) {
    if (section.dataset.wired) return;
    section.dataset.wired = '1';

    var gameTitle = section.dataset.gameTitle;
    var board = section.querySelector('.dd-puzzle-board');

    ddBindDocPointer();

    // Turn off native drag completely; we handle it with pointer events
    section.querySelectorAll('.dd-card').forEach(function (c) {
        c.setAttribute('draggable', 'false');
        c.style.touchAction = 'none';
    });
    board.addEventListener('dragstart', function (e) { e.preventDefault(); });

    board.addEventListener('pointerdown', function (e) {
        if (e.pointerType === 'mouse' && e.button !== 0) return;
        if (e.target.closest('.dd-card-remove')) return;
        var card = e.target.closest('.dd-card');
        if (!card) return;

        var rect = card.getBoundingClientRect();
        _ddPtr = {
            card: card, section: section, gameTitle: gameTitle,
            startX: e.clientX, startY: e.clientY,
            offX: e.clientX - rect.left, offY: e.clientY - rect.top,
            rect: rect, ghost: null, moved: false, zone: null
        };
    });

    // Ignore the click that the browser fires right after a real drag
    board.addEventListener('click', function (e) {
        if (_ddSuppressClick) {
            e.stopPropagation();
            e.preventDefault();
        }
    }, true);

    // Tap-to-select / tap-to-place still works
    board.addEventListener('click', function (e) {
        if (e.target.closest('.dd-card-remove')) return;

        var clickedItem = e.target.closest('.dd-card');
        var clickedTarget = e.target.closest('.dd-target-card');
        var clickedBank = e.target.closest('.dd-item-row[data-role="bank"]');

        if (clickedItem) {
            ddSelectItem(gameTitle, clickedItem);
            return;
        }
        if (clickedTarget) {
            ddPlaceSelected(gameTitle, clickedTarget.querySelector('.dd-socket'));
            return;
        }
        if (clickedBank) {
            ddReturnSelectedToBank(gameTitle);
        }
    });
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

    var bank = section.querySelector('.dd-item-row[data-role="bank"]');
    if (bank && bank.contains(item)) {
        var placeholder = document.createElement('div');
        placeholder.className = 'dd-card-placeholder';
        placeholder.dataset.forItem = item.dataset.item;
        placeholder.dataset.page = item.dataset.page;   // keeps it on the right page
        bank.insertBefore(placeholder, item);
    }

    slot.appendChild(item);
    item.classList.remove('dragging');
    item.classList.add('placed');
    item.setAttribute('draggable', 'false');

    ddAddRemoveBtn(item, gameTitle);

    state.answers[item.dataset.item] = chosenCategory;

    ddRefreshZones(section);
    targetCard.classList.add('dd-just-filled');
    setTimeout(function () { targetCard.classList.remove('dd-just-filled'); }, 500);

    ddSetFeedback(section, '"' + item.dataset.item + '" placed in "' + chosenCategory + '".');
    ddUpdateBoardNav(gameTitle);
    ddPickNextCategoryHint(gameTitle);
}

function ddSelectItem(gameTitle, itemEl) {
    var section = ddFindSection(gameTitle);
    var state = DD_STAGE[gameTitle];
    if (!section || !state) return;

    // Clicking the already-selected item again deselects it
    if (state.selectedItemEl === itemEl) {
        itemEl.classList.remove('selected');
        state.selectedItemEl = null;
        ddPickNextCategoryHint(gameTitle);
        return;
    }

    section.querySelectorAll('.dd-card.selected').forEach(function (c) {
        c.classList.remove('selected');
    });

    itemEl.classList.add('selected');
    state.selectedItemEl = itemEl;

    if (itemEl.classList.contains('placed')) {
        ddSetBubble(gameTitle, 'Tap a different category to move "' + itemEl.dataset.item + '", or tap the items area above to remove it.');
    } else {
        ddSetBubble(gameTitle, 'Now tap the category "' + itemEl.dataset.item + '" belongs to.');
    }
}

function ddPlaceSelected(gameTitle, socket) {
    var state = DD_STAGE[gameTitle];
    if (!state || !state.selectedItemEl || !socket) return;

    var item = state.selectedItemEl;
    item.classList.remove('selected');
    state.selectedItemEl = null;

    // Reuse the existing drop logic by pretending this was a drag
    _ddDraggedEl = item;
    ddDropInto(gameTitle, socket);
    _ddDraggedEl = null;
}

function ddReturnSelectedToBank(gameTitle) {
    var state = DD_STAGE[gameTitle];
    if (!state || !state.selectedItemEl) return;

    var item = state.selectedItemEl;
    item.classList.remove('selected');
    state.selectedItemEl = null;

    if (item.classList.contains('placed')) {
        ddReturnToBank(gameTitle, item);
    }
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

    // NEW — find this item's placeholder and swap the card back into
    // that exact spot, instead of just appending to the end of the bank.
    var placeholder = bank.querySelector('.dd-card-placeholder[data-for-item="' + CSS.escape(item.dataset.item) + '"]');
    if (placeholder) {
        bank.insertBefore(item, placeholder);
        placeholder.remove();
    } else {
        bank.appendChild(item);
    }

    ddRefreshZones(section);

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

    var fill = section.querySelector('.dd-progress-fill');
    if (fill) fill.style.width = (total > 0 ? Math.round((placed / total) * 100) : 0) + '%';

    var multiPage = state.totalPages > 1;
    var isLastPage = state.page >= state.totalPages - 1;

    var indicator = section.querySelector('.dd-page-indicator');
    if (indicator) {
        indicator.textContent = 'Page ' + (state.page + 1) + ' of ' + state.totalPages;
        indicator.style.display = multiPage ? '' : 'none';
    }

    var prevBtn = section.querySelector('.dd-prev-btn');
    if (prevBtn) prevBtn.style.visibility = (multiPage && state.page > 0) ? 'visible' : 'hidden';

    var nextBtn = section.querySelector('.dd-next-btn');
    if (nextBtn) {
        nextBtn.style.display = (multiPage && !isLastPage) ? 'flex' : 'none';
        nextBtn.classList.toggle('is-locked', !ddPageComplete(state, state.page));
    }

    var finishBtn = section.querySelector('.dd-finish-btn');
    if (finishBtn) {
        var canFinish = (placed >= total) && isLastPage;
        finishBtn.disabled = !canFinish;
        finishBtn.style.display = canFinish ? 'flex' : 'none';
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

    var ddState = ddInitState(gameTitle, items, categoryHints);
    ddAssignPages(section, ddState);
    ddWireBoard(section);
    ddRenderPage(gameTitle);
    ddShowPageCategories(gameTitle);   // only 3 boxes
    ddUpdateBoardNav(gameTitle);
    ddPickNextCategoryHint(gameTitle);
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
   ARRANGE THE STEPS — drag to reorder, or use up/down arrows
========================================================= */
var ARR_STAGE = {}; // gameTitle => { order: [texts...] }
var _arrDraggedEl = null;

function arrFindSection(gameTitle) {
    return document.querySelector('.arr-stage-section[data-game-title="' + CSS.escape(gameTitle) + '"]');
}

function arrInitState(gameTitle, stepsFromDom) {
    if (!ARR_STAGE[gameTitle]) {
        ARR_STAGE[gameTitle] = {
            order: (stepsFromDom || []).map(function (s) { return s.text; }),
            locked: false
        };
    }
    return ARR_STAGE[gameTitle];
}

function arrRenderList(gameTitle) {
    var state = ARR_STAGE[gameTitle];
    var section = arrFindSection(gameTitle);
    if (!state || !section) return;

    var list = section.querySelector('.arr-list');
    if (!list) return;
    list.innerHTML = '';

    state.order.forEach(function (text, idx) {
        var isLast = (idx === state.order.length - 1);

        var row = document.createElement('div');
        row.className = 'arr-row';
        row.dataset.text = text;

        row.innerHTML =
            '<div class="arr-timeline-col">' +
            '<div class="arr-circle-num">' + (idx + 1) + '</div>' +
            (isLast ? '' : '<div class="arr-connector-line"></div>') +
            '</div>' +
            '<div class="arr-item" draggable="true">' +
            '<span class="arr-item-grip"><i class="fa fa-grip-vertical"></i></span>' +
            '<span class="arr-item-text"></span>' +
            '<span class="arr-item-controls">' +
            '<button type="button" class="arr-item-btn arr-up" aria-label="Move up"><i class="fa fa-chevron-up"></i></button>' +
            '<button type="button" class="arr-item-btn arr-down" aria-label="Move down"><i class="fa fa-chevron-down"></i></button>' +
            '</span>' +
            '</div>';
        row.querySelector('.arr-item-text').textContent = text;

        // Compute the button's row index dynamically at click-time instead
        // of closuring over idx — this way the buttons never go stale after
        // a reorder, since we never rebuild the list anymore.
        row.querySelector('.arr-up').addEventListener('click', function () {
            var rows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));
            var curIdx = rows.indexOf(row);
            arrMove(gameTitle, curIdx, -1);
        });
        row.querySelector('.arr-down').addEventListener('click', function () {
            var rows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));
            var curIdx = rows.indexOf(row);
            arrMove(gameTitle, curIdx, 1);
        });

        if (idx === 0) row.querySelector('.arr-up').disabled = true;
        if (isLast) row.querySelector('.arr-down').disabled = true;

        list.appendChild(row);
    });

    arrWireDrag(gameTitle, list);
}

// Relabels number circles, connector lines, and up/down disabled state
// to match the CURRENT DOM order — never destroys or recreates rows.
function arrUpdateRowLabels(gameTitle) {
    var section = arrFindSection(gameTitle);
    if (!section) return;
    var list = section.querySelector('.arr-list');
    if (!list) return;

    var rows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));
    var total = rows.length;

    rows.forEach(function (row, idx) {
        var numEl = row.querySelector('.arr-circle-num');
        if (numEl) numEl.textContent = (idx + 1);

        var isLast = (idx === total - 1);
        var timelineCol = row.querySelector('.arr-timeline-col');
        var connector = row.querySelector('.arr-connector-line');
        if (isLast && connector) connector.remove();
        if (!isLast && !connector && timelineCol) {
            var line = document.createElement('div');
            line.className = 'arr-connector-line';
            timelineCol.appendChild(line);
        }

        var upBtn = row.querySelector('.arr-up');
        var downBtn = row.querySelector('.arr-down');
        if (upBtn) upBtn.disabled = (idx === 0);
        if (downBtn) downBtn.disabled = isLast;
    });
}

// FLIP animation: measure current positions, reorder the DOM to match
// state.order, then animate each row from its old position to its new
// one — used by the up/down arrows so the swap slides instead of jumping.
function arrReorderWithFlip(gameTitle) {
    var section = arrFindSection(gameTitle);
    var state = ARR_STAGE[gameTitle];
    if (!section || !state) return;
    var list = section.querySelector('.arr-list');
    if (!list) return;

    var rows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));

    // FIRST — record starting positions keyed by text
    var firstRects = {};
    rows.forEach(function (row) {
        firstRects[row.dataset.text] = row.getBoundingClientRect();
    });

    // LAST — physically reorder the existing row elements (no recreation)
    state.order.forEach(function (text) {
        var row = rows.filter(function (r) { return r.dataset.text === text; })[0];
        if (row) list.appendChild(row);
    });

    arrUpdateRowLabels(gameTitle);

    // INVERT + PLAY
    var newRows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));
    newRows.forEach(function (row) {
        var first = firstRects[row.dataset.text];
        if (!first) return;
        var last = row.getBoundingClientRect();
        var deltaY = first.top - last.top;
        if (deltaY) {
            row.style.transition = 'none';
            row.style.transform = 'translateY(' + deltaY + 'px)';
            row.getBoundingClientRect(); // force reflow
            requestAnimationFrame(function () {
                row.style.transition = 'transform .32s cubic-bezier(.2,.8,.2,1)';
                row.style.transform = '';
            });
        }
    });
}

function arrMove(gameTitle, idx, dir) {
    var state = ARR_STAGE[gameTitle];
    if (!state || state.locked) return;
    var newIdx = idx + dir;
    if (newIdx < 0 || newIdx >= state.order.length) return;

    var movedText = state.order[idx];
    var tmp = state.order[idx];
    state.order[idx] = state.order[newIdx];
    state.order[newIdx] = tmp;

    arrReorderWithItemFlip(gameTitle, null);
    arrSetBubble(gameTitle, '"' + movedText + '" moved to position ' + (newIdx + 1) + '.');
}

function arrApplyLockState(gameTitle) {
    var section = arrFindSection(gameTitle);
    var state = ARR_STAGE[gameTitle];
    if (!section || !state) return;

    var list = section.querySelector('.arr-list');
    var locked = !!state.locked;

    if (list) {
        list.classList.toggle('arr-locked', locked);
        list.querySelectorAll('.arr-item').forEach(function (item) {
            item.setAttribute('draggable', locked ? 'false' : 'true');
        });
        if (locked) {
            list.querySelectorAll('.arr-item-btn').forEach(function (btn) { btn.disabled = true; });
        } else {
            arrUpdateRowLabels(gameTitle); // restores correct up/down disabled states at boundaries
        }
    }

    var editBtn = section.querySelector('.arr-edit-btn');
    var readyBtn = section.querySelector('.arr-ready-btn');
    var finishBtn = section.querySelector('.arr-finish-btn');

    if (editBtn) editBtn.style.visibility = locked ? 'visible' : 'hidden';
    if (readyBtn) readyBtn.style.display = locked ? 'none' : 'flex';
    if (finishBtn) { finishBtn.style.display = locked ? 'flex' : 'none'; finishBtn.disabled = false; }
}

function arrReady(gameTitle) {
    var state = ARR_STAGE[gameTitle];
    if (!state) return;
    state.locked = true;
    arrApplyLockState(gameTitle);
    arrSetBubble(gameTitle, 'Are you sure at your answer? Take a look at your arrangements? Tap "Finish" to submit it, or "Edit" if you\'d like to change something.');

    var botImg = document.getElementById('arrOverlayBot');
    var BonImgparent = document.getElementById('BonBonOverlay');
    if (botImg) botImg.src = '../images/drop-drag-left.png';
    BonImgparent.style.marginTop = '-100px';
}

function arrEdit(gameTitle) {
    var state = ARR_STAGE[gameTitle];
    if (!state) return;
    state.locked = false;
    arrApplyLockState(gameTitle);
    arrSetBubble(gameTitle, 'Drag the cards or use the arrows to rearrange the steps.');

    var botImg = document.getElementById('arrOverlayBot');
    if (botImg) botImg.src = '../images/robot-ai10.png';
}

// Reorders rows instantly (numbers relabel with zero animation, since the
// row itself never moves/animates) then FLIP-animates ONLY the .arr-item
// card inside each row, sliding it into its new slot.
function arrReorderWithItemFlip(gameTitle, pulseText) {
    var section = arrFindSection(gameTitle);
    var state = ARR_STAGE[gameTitle];
    if (!section || !state) return;
    var list = section.querySelector('.arr-list');
    if (!list) return;

    var rows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));

    // FIRST — record each ITEM's starting position (not the row's)
    var firstRects = {};
    rows.forEach(function (row) {
        var itemEl = row.querySelector('.arr-item');
        if (itemEl) firstRects[row.dataset.text] = itemEl.getBoundingClientRect();
    });

    // Reorder the rows instantly — numbers relabel with no animation since
    // the row itself is never transformed, only the item inside it.
    state.order.forEach(function (text) {
        var row = rows.filter(function (r) { return r.dataset.text === text; })[0];
        if (row) list.appendChild(row);
    });

    arrUpdateRowLabels(gameTitle);

    // LAST + INVERT + PLAY — animate ONLY the .arr-item elements
    var newRows = Array.prototype.slice.call(list.querySelectorAll('.arr-row'));
    newRows.forEach(function (row) {
        var itemEl = row.querySelector('.arr-item');
        var first = firstRects[row.dataset.text];
        if (!itemEl || !first) return;
        var last = itemEl.getBoundingClientRect();
        var deltaY = first.top - last.top;
        if (deltaY) {
            itemEl.style.transition = 'none';
            itemEl.style.transform = 'translateY(' + deltaY + 'px)';
            itemEl.getBoundingClientRect(); // force reflow
            requestAnimationFrame(function () {
                itemEl.style.transition = 'transform .32s cubic-bezier(.2,.8,.2,1)';
                itemEl.style.transform = '';
            });
        }
    });

    if (pulseText) {
        var pulsedRow = newRows.filter(function (r) { return r.dataset.text === pulseText; })[0];
        if (pulsedRow) {
            pulsedRow.classList.remove('arr-drop-pulse');
            void pulsedRow.offsetWidth;
            pulsedRow.classList.add('arr-drop-pulse');
            pulsedRow.addEventListener('animationend', function handler() {
                pulsedRow.classList.remove('arr-drop-pulse');
                pulsedRow.removeEventListener('animationend', handler);
            });
        }
    }
}

function arrSetBubble(gameTitle, text) {
    var overlayMsg = document.getElementById('arrOverlayMessage');
    if (overlayMsg) overlayMsg.textContent = text;
    adjustBonBonPosition('#arrOverlay');
}

// Drag no longer physically moves rows while hovering — that's what was
// dragging the number badge along and causing the ghosting. Rows stay put;
// we just show a drop-target line and reorder + FLIP the item on drop.
function arrWireDrag(gameTitle, list) {
    if (list.dataset.wired) return;
    list.dataset.wired = '1';

    list.addEventListener('dragstart', function (e) {
        var state = ARR_STAGE[gameTitle];
        if (state && state.locked) { e.preventDefault(); return; }
        var itemEl = e.target.closest('.arr-item');
        if (!itemEl) return;
        var row = itemEl.closest('.arr-row');
        if (!row) return;

        _arrDraggedEl = row;
        row.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', row.dataset.text);
        e.dataTransfer.setDragImage(itemEl, itemEl.offsetWidth / 2, itemEl.offsetHeight / 2);
    });

    list.addEventListener('dragend', function () {
        if (_arrDraggedEl) _arrDraggedEl.classList.remove('dragging');
        list.querySelectorAll('.arr-row.arr-drop-target').forEach(function (r) {
            r.classList.remove('arr-drop-target');
        });
        _arrDraggedEl = null;
    });

    list.addEventListener('dragover', function (e) {
        var state = ARR_STAGE[gameTitle];
        if (state && state.locked) return;
        e.preventDefault();
        if (!_arrDraggedEl) return;

        var afterEl = arrGetDragAfterElement(list, e.clientY);

        list.querySelectorAll('.arr-row.arr-drop-target').forEach(function (r) {
            r.classList.remove('arr-drop-target');
        });
        var target = afterEl || list.lastElementChild;
        if (target && target !== _arrDraggedEl) target.classList.add('arr-drop-target');

        list.dataset.dropAfter = afterEl ? afterEl.dataset.text : '';
        list.dataset.dropAtEnd = afterEl ? '' : '1';
    });

    list.addEventListener('drop', function (e) {
        var state = ARR_STAGE[gameTitle];
        if (state && state.locked) return;
        e.preventDefault();

        list.querySelectorAll('.arr-row.arr-drop-target').forEach(function (r) {
            r.classList.remove('arr-drop-target');
        });

        var draggedText = _arrDraggedEl.dataset.text;
        var afterText = list.dataset.dropAfter;
        var atEnd = list.dataset.dropAtEnd === '1';

        var order = state.order.slice();
        var fromIdx = order.indexOf(draggedText);
        if (fromIdx === -1) return;
        order.splice(fromIdx, 1);

        var toIdx;
        if (atEnd || !afterText) {
            toIdx = order.length;
        } else {
            toIdx = order.indexOf(afterText);
            if (toIdx === -1) toIdx = order.length;
        }
        order.splice(toIdx, 0, draggedText);
        state.order = order;

        arrReorderWithItemFlip(gameTitle, draggedText);

        var newPos = state.order.indexOf(draggedText) + 1;
        arrSetBubble(gameTitle, '"' + draggedText + '" moved to position ' + newPos + '.');
    });
}

function arrGetDragAfterElement(list, y) {
    var items = Array.prototype.slice.call(list.querySelectorAll('.arr-row:not(.dragging)'));
    return items.reduce(function (closest, child) {
        var box = child.getBoundingClientRect();
        var offset = y - box.top - box.height / 2;
        if (offset < 0 && offset > closest.offset) {
            return { offset: offset, element: child };
        } else {
            return closest;
        }
    }, { offset: -Infinity, element: null }).element;
}

function openArrangeStage(gameTitle) {
    var section = arrFindSection(gameTitle);
    var overlay = document.getElementById('arrOverlay');
    if (!section || !overlay) return;

    if (overlay.parentElement !== document.body) document.body.appendChild(overlay);

    if (!section.dataset.homeMarked) {
        var marker = document.createElement('div');
        marker.className = 'arr-home-marker';
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

    var board = section.querySelector('.arr-board');
    var steps = [];
    try { steps = JSON.parse(board.dataset.steps || '[]'); } catch (e) { steps = []; }
    var correctOrderRaw = [];
    try { correctOrderRaw = JSON.parse(board.dataset.correctOrder || '[]'); } catch (e) { correctOrderRaw = []; }

    var state = arrInitState(gameTitle, steps);
    if (!state.correctOrder) {
        state.correctOrder = correctOrderRaw.map(function (s) { return s.text; });
    }

    arrRenderList(gameTitle);
    arrApplyLockState(gameTitle);

    var botImg = document.getElementById('arrOverlayBot');
    if (botImg) botImg.src = (state && state.locked) ? '../images/drop-drag-left.png' : '../images/robot-ai10.png';

    var msgEl = document.getElementById('arrOverlayMessage');
    if (msgEl) {
        msgEl.textContent = (state && state.locked)
            ? 'Are you sure at your answer? Take a look at your arrangements? Tap "Finish" to submit it, or "Edit" if you\'d like to change something.'
            : 'The steps are shuffled. Drag the cards or use the arrows to arrange them in the correct order!';
    }
    adjustBonBonPosition('#arrOverlay', 450);

    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
        sessionStorage.setItem('arrange_open_lesson_' + LESSON_DATA.lessonId, gameTitle);
    }
    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.moduleId) {
        sessionStorage.setItem('lessons_view_module_' + LESSON_DATA.moduleId, 'lesson');
    }
}

function closeArrangeStage(gameTitle) {
    var section = arrFindSection(gameTitle);
    var overlay = document.getElementById('arrOverlay');
    var marker = document.querySelector('.arr-home-marker[data-game-title="' + CSS.escape(gameTitle) + '"]');
    if (!section || !overlay) return;

    var state = ARR_STAGE[gameTitle];
    if (state && state.justCompleted) {
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('arrange_open_lesson_' + LESSON_DATA.lessonId);
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
            sessionStorage.removeItem('arrange_open_lesson_' + LESSON_DATA.lessonId);
        }
    }, 280);
}

function arrReviewFindSection(gameTitle) {
    return document.querySelector('.arr-review-section[data-game-title="' + CSS.escape(gameTitle) + '"]');
}

function openArrangeReviewStage(gameTitle) {
    var section = arrReviewFindSection(gameTitle);
    var overlay = document.getElementById('arrOverlay');
    if (!section || !overlay) return;

    if (overlay.parentElement !== document.body) document.body.appendChild(overlay);

    if (!section.dataset.homeMarked) {
        var marker = document.createElement('div');
        marker.className = 'arr-review-home-marker';
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

    var msgEl = document.getElementById('arrOverlayMessage');
    if (msgEl) msgEl.textContent = 'Here\'s how you did on "' + gameTitle + '"!';
    adjustBonBonPosition('#arrOverlay', 450);

    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
        sessionStorage.setItem('arrange_review_open_lesson_' + LESSON_DATA.lessonId, gameTitle);
    }
}

function closeArrangeReviewStage(gameTitle) {
    var section = arrReviewFindSection(gameTitle);
    var overlay = document.getElementById('arrOverlay');
    var marker = document.querySelector('.arr-review-home-marker[data-game-title="' + CSS.escape(gameTitle) + '"]');
    if (!section || !overlay) return;

    overlay.classList.add('qz-closing');
    setTimeout(function () {
        if (marker && marker.parentNode) marker.parentNode.insertBefore(section, marker);
        section.style.display = 'none';
        overlay.classList.remove('open', 'qz-closing');
        document.body.style.overflow = document.body.dataset.prevOverflow || '';
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('arrange_review_open_lesson_' + LESSON_DATA.lessonId);
        }
    }, 280);
}

function arrSubmit(gameTitle) {
    var state = ARR_STAGE[gameTitle];
    if (!state) return;
    var section = arrFindSection(gameTitle);
    var finishBtn = section && section.querySelector('.arr-finish-btn');
    if (finishBtn) finishBtn.disabled = true;

    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;


    var fd = new FormData();
    fd.append('lesson_id', lessonId);
    fd.append('game_title', gameTitle);
    state.order.forEach(function (text) {
        fd.append('order[]', text);
    });

    fetch('/learning_management/public/?url=submit_arrange_steps', { method: 'POST', body: fd })
        .then(function (r) {
            return r.text().then(function (text) {
                console.log('[arrSubmit] raw response:', text);
                var json;
                try { json = JSON.parse(text); } catch (e) { json = null; }
                return json;
            });
        })
        .then(function (resp) {
            if (!resp || resp.ok !== true) {
                console.error('[arrSubmit] server rejected save:', resp);
                alert('There was a problem saving your answers: ' + (resp && resp.msg ? resp.msg : 'unknown error') + '. Please try again.');
                return;
            }
            var correct = (typeof resp.score === 'number') ? resp.score : state.order.length;
            var total = (typeof resp.total === 'number') ? resp.total : state.order.length;

            state.justCompleted = true;

            if (LESSON_DATA && LESSON_DATA.arrangesteps) {
                LESSON_DATA.arrangesteps.forEach(function (a) { if (a.title === gameTitle) a.done = true; });
            }
            checkLessonComplete();
            arrShowResults(gameTitle, correct, total);
        })
        .catch(function () {
            if (finishBtn) finishBtn.disabled = false;
            alert('Could not reach the server to save your answers. Please check your connection and try again.');
        });
}

function arrShowResults(gameTitle, correct, total) {
    var state = ARR_STAGE[gameTitle];
    var section = arrFindSection(gameTitle);
    if (!state || !section) { closeArrangeStage(gameTitle); return; }

    var board = section.querySelector('.arr-board');
    var results = section.querySelector('.dd-results');
    if (board) board.style.display = 'none';

    var pct = total > 0 ? Math.round((correct / total) * 100) : 0;
    var msgEl = document.getElementById('arrOverlayMessage');
    if (msgEl) {
        msgEl.textContent = 'You placed ' + correct + ' out of ' + total + ' steps correctly (' + pct + '%)! ' +
            (pct === 100 ? 'Perfect order!' : pct >= 70 ? 'Great job!' : 'Nice try — review your order below.');
    }

    if (results) {
        results.style.display = 'block';
        var fill = results.querySelector('.qz-accuracy-fill');
        var pctLabel = results.querySelector('.qz-accuracy-pct');
        if (fill) requestAnimationFrame(function () { fill.style.width = pct + '%'; });
        if (pctLabel) pctLabel.textContent = pct + '%';

        var list = results.querySelector('.dd-review-list');
        if (list) {
            list.innerHTML = '';
            var correctOrder = state.correctOrder || [];
            var totalSteps = state.order.length;

            state.order.forEach(function (text, idx) {
                var correctIdx = correctOrder.indexOf(text);
                var isCorrect = correctIdx === idx;
                var color = isCorrect ? 'var(--neon-green)' : '#ff4d6d';
                var isLast = (idx === totalSteps - 1);

                var row = document.createElement('div');
                row.className = 'arr-row';
                row.style.cursor = 'default';
                row.innerHTML =
                    '<div class="arr-timeline-col">' +
                    '<div class="arr-circle-num" style="border-color:' + color + ';color:' + color + ';">' + (idx + 1) + '</div>' +
                    (isLast ? '' : '<div class="arr-connector-line"></div>') +
                    '</div>' +
                    '<div class="arr-item" style="border-color:' + color + ';cursor:default;">' +
                    '<span class="arr-item-text" style="color:' + color + ';">' + escHtml(text) + '</span>' +
                    '</div>';
                list.appendChild(row);

                if (!isCorrect && correctIdx > -1) {
                    var note = document.createElement('div');
                    note.style.cssText = 'font-size:12px;color:#ff4d6d;margin:-8px 0 12px 54px;font-weight:600;';
                    note.textContent = 'Belongs at position ' + (correctIdx + 1) + ', not ' + (idx + 1) + '.';
                    list.appendChild(note);
                }
            });
        }
    }
}

/* =========================================================
   CONNECT THE DOTS — drag a line from a left dot to a right dot
========================================================= */
var CP_STAGE = {}; // gameTitle => { pairs, rightOrder, answers, colors, locked }
// var CP_COLORS = ['#d64541'];   
var CP_COLORS = ['#6b4423'];
var CP_NOTE_COLORS = ['#fff6e5', '#ffe8c2', '#e3f2e8', '#efe3f5', '#e6f0ff'];
var _cpDrag = null; // { gameTitle, leftText, tempLine }

function cpFindSection(gameTitle) {
    return document.querySelector('.cp-stage-section[data-game-title="' + CSS.escape(gameTitle) + '"]');
}

function cpInitState(gameTitle, pairs, rightShuffled) {
    if (!CP_STAGE[gameTitle]) {
        CP_STAGE[gameTitle] = {
            pairs: pairs || [],
            rightOrder: rightShuffled || [],
            answers: {},
            colors: {},
            locked: false
        };
    }
    return CP_STAGE[gameTitle];
}

function cpSetBubble(text) {
    var overlayMsg = document.getElementById('cpOverlayMessage');
    if (overlayMsg) overlayMsg.textContent = text;
    adjustBonBonPosition('#cpOverlay');
}

function cpRenderBoard(gameTitle) {
    var section = cpFindSection(gameTitle);
    var state = CP_STAGE[gameTitle];
    if (!section || !state) return;

    var leftCol = section.querySelector('.cp-column[data-role="left"]');
    var rightCol = section.querySelector('.cp-column[data-role="right"]');
    if (!leftCol || !rightCol) return;

    leftCol.querySelectorAll('.cp-item').forEach(function (el) { el.remove(); });
    rightCol.querySelectorAll('.cp-item').forEach(function (el) { el.remove(); });

    // Keep each note's tilt the same every time the board is re-rendered
    state.tilts = state.tilts || {};
    function tiltFor(key) {
        if (state.tilts[key] === undefined) {
            state.tilts[key] = (Math.random() * 1.6 - 0.8).toFixed(2) + 'deg';
        }
        return state.tilts[key];
    }

    function makeNote(text, side, idx) {
        var el = document.createElement('div');
        el.className = 'cp-item';
        el.dataset.text = text;
        el.dataset.side = side;
        el.style.setProperty('--note-bg', CP_NOTE_COLORS[idx % CP_NOTE_COLORS.length]);
        el.style.setProperty('--note-tilt', tiltFor(side + ':' + text));

        el.innerHTML = side === 'left'
            ? '<span class="cp-tape"></span><span class="cp-text">' + escHtml(text) + '</span><span class="cp-dot"></span>'
            : '<span class="cp-tape"></span><span class="cp-dot"></span><span class="cp-text">' + escHtml(text) + '</span>';
        return el;
    }

    state.pairs.forEach(function (p, i) {
        leftCol.appendChild(makeNote(p.left, 'left', i));
    });

    state.rightOrder.forEach(function (text, i) {
        rightCol.appendChild(makeNote(text, 'right', i));
    });
}

function cpDotCenter(itemEl, svg) {
    var dot = itemEl.querySelector('.cp-dot');
    var rect = dot.getBoundingClientRect();
    var svgRect = svg.getBoundingClientRect();
    return {
        x: rect.left + rect.width / 2 - svgRect.left,
        y: rect.top + rect.height / 2 - svgRect.top
    };
}

function cpRedrawLines(gameTitle) {
    var section = cpFindSection(gameTitle);
    var state = CP_STAGE[gameTitle];
    if (!section || !state) return;

    var board = section.querySelector('.cp-board');
    var svg = board.querySelector('.cp-lines-svg');
    if (!svg) return;
    svg.innerHTML = '';

    Object.keys(state.answers).forEach(function (leftText) {
        var rightText = state.answers[leftText];
        var color = state.colors[leftText] || CP_COLORS[0];

        var leftEl = board.querySelector('.cp-item[data-side="left"][data-text="' + CSS.escape(leftText) + '"]');
        var rightEl = board.querySelector('.cp-item[data-side="right"][data-text="' + CSS.escape(rightText) + '"]');
        if (!leftEl || !rightEl) return;

        var p1 = cpDotCenter(leftEl, svg);
        var p2 = cpDotCenter(rightEl, svg);

        var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', p1.x);
        line.setAttribute('y1', p1.y);
        line.setAttribute('x2', p2.x);
        line.setAttribute('y2', p2.y);
        line.setAttribute('stroke', color);
        line.setAttribute('stroke-width', '4');
        line.setAttribute('stroke-linecap', 'round');
        svg.appendChild(line);

        [[p1, leftEl], [p2, rightEl]].forEach(function (pair) {
            var c = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
            c.setAttribute('cx', pair[0].x);
            c.setAttribute('cy', pair[0].y);
            c.setAttribute('r', 8);
            c.setAttribute('fill', color);
            svg.appendChild(c);
            pair[1].querySelector('.cp-dot').style.background = color;
            pair[1].classList.add('cp-linked');
        });
    });

    // reset dots for items with no connection
    board.querySelectorAll('.cp-item').forEach(function (el) {
        var text = el.dataset.text;
        var isLinked = (el.dataset.side === 'left')
            ? !!state.answers[text]
            : Object.values(state.answers).indexOf(text) !== -1;
        if (!isLinked) {
            el.classList.remove('cp-linked');
            var dot = el.querySelector('.cp-dot');
            dot.style.background = '';
            dot.style.borderColor = '';
        }
    });
}

function cpNextColor(state) {
    return CP_COLORS[0];
}

function cpMakeConnection(gameTitle, leftText, rightText) {
    var state = CP_STAGE[gameTitle];
    if (!state || state.locked) return;

    // If the right item is already used by another left item, free it up first
    Object.keys(state.answers).forEach(function (l) {
        if (state.answers[l] === rightText) {
            delete state.answers[l];
            delete state.colors[l];
        }
    });

    // Reuse the same color if this left item already had a connection
    var color = state.colors[leftText] || cpNextColor(state);
    state.answers[leftText] = rightText;
    state.colors[leftText] = color;

    cpRedrawLines(gameTitle);
    cpSetBubble('"' + leftText + '" connected to "' + rightText + '".');
    cpUpdateNav(gameTitle);
}

function cpRemoveConnection(gameTitle, leftText) {
    var state = CP_STAGE[gameTitle];
    if (!state || state.locked) return;
    delete state.answers[leftText];
    delete state.colors[leftText];
    cpRedrawLines(gameTitle);
    cpUpdateNav(gameTitle);
}

function cpWireBoard(section) {
    if (section.dataset.wired) return;
    section.dataset.wired = '1';

    var gameTitle = section.dataset.gameTitle;
    var board = section.querySelector('.cp-board');
    var svg = board.querySelector('.cp-lines-svg');

    function startDrag(e, leftEl) {
        var state = CP_STAGE[gameTitle];
        if (!state || state.locked) return;
        e.preventDefault();

        leftEl.classList.add('cp-dragging-from');

        var tempLine = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        tempLine.setAttribute('stroke', '#6b4423');
        tempLine.setAttribute('stroke-width', '3');
        tempLine.setAttribute('stroke-dasharray', '6 5');
        tempLine.setAttribute('stroke-linecap', 'round');
        svg.appendChild(tempLine);

        _cpDrag = { gameTitle: gameTitle, leftText: leftEl.dataset.text, tempLine: tempLine, leftEl: leftEl };

        var start = cpDotCenter(leftEl, svg);
        tempLine.setAttribute('x1', start.x);
        tempLine.setAttribute('y1', start.y);
        tempLine.setAttribute('x2', start.x);
        tempLine.setAttribute('y2', start.y);
    }

    function onPointerMove(e) {
        if (!_cpDrag) return;
        var svgRect = svg.getBoundingClientRect();
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var clientY = e.touches ? e.touches[0].clientY : e.clientY;
        _cpDrag.tempLine.setAttribute('x2', clientX - svgRect.left);
        _cpDrag.tempLine.setAttribute('y2', clientY - svgRect.top);
    }

    function onPointerUp(e) {
        if (!_cpDrag) return;
        var clientX = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
        var clientY = e.changedTouches ? e.changedTouches[0].clientY : e.clientY;
        var dropEl = document.elementFromPoint(clientX, clientY);
        var rightItem = dropEl ? dropEl.closest('.cp-item[data-side="right"]') : null;

        _cpDrag.leftEl.classList.remove('cp-dragging-from');
        if (_cpDrag.tempLine.parentNode) _cpDrag.tempLine.parentNode.removeChild(_cpDrag.tempLine);

        if (rightItem) {
            cpMakeConnection(gameTitle, _cpDrag.leftText, rightItem.dataset.text);
        }

        _cpDrag = null;
    }

    board.addEventListener('mousedown', function (e) {
        var leftEl = e.target.closest('.cp-item[data-side="left"]');
        if (leftEl && e.target.closest('.cp-dot')) startDrag(e, leftEl);
    });
    board.addEventListener('touchstart', function (e) {
        var leftEl = e.target.closest('.cp-item[data-side="left"]');
        if (leftEl && e.target.closest('.cp-dot')) startDrag(e, leftEl);
    }, { passive: false });

    document.addEventListener('mousemove', onPointerMove);
    document.addEventListener('touchmove', onPointerMove, { passive: false });
    document.addEventListener('mouseup', onPointerUp);
    document.addEventListener('touchend', onPointerUp);

    // Tap a connected item (either side) to remove its line
    board.addEventListener('click', function (e) {
        var leftEl = e.target.closest('.cp-item[data-side="left"]');
        var rightEl = e.target.closest('.cp-item[data-side="right"]');
        var state = CP_STAGE[gameTitle];
        if (!state || state.locked) return;

        if (leftEl && state.answers[leftEl.dataset.text]) {
            cpRemoveConnection(gameTitle, leftEl.dataset.text);
            cpSetBubble('"' + leftEl.dataset.text + '" unlinked. Drag a new line to match it.');
            return;
        }

        if (rightEl) {
            var linkedLeft = null;
            Object.keys(state.answers).forEach(function (l) {
                if (state.answers[l] === rightEl.dataset.text) linkedLeft = l;
            });
            if (linkedLeft) {
                cpRemoveConnection(gameTitle, linkedLeft);
                cpSetBubble('"' + linkedLeft + '" unlinked. Drag a new line to match it.');
            }
        }
    });
}

function cpUpdateNav(gameTitle) {
    var state = CP_STAGE[gameTitle];
    var section = cpFindSection(gameTitle);
    if (!state || !section) return;

    var total = state.pairs.length;
    var matched = Object.keys(state.answers).length;

    var counter = section.querySelector('.cp-counter');
    if (counter) counter.textContent = matched + ' of ' + total + ' matched';

    var fill = section.querySelector('.cp-progress-fill');
    if (fill) fill.style.width = (total > 0 ? Math.round((matched / total) * 100) : 0) + '%';

    var readyBtn = section.querySelector('.cp-ready-btn');
    if (readyBtn && !state.locked) {
        var allMatched = (matched >= total);
        readyBtn.disabled = !allMatched;
        readyBtn.style.display = allMatched ? 'flex' : 'none';
        if (allMatched) cpSetBubble("You've connected every item! Tap Ready when you want to review before submitting.");
    }
}

function cpApplyLockState(gameTitle) {
    var section = cpFindSection(gameTitle);
    var state = CP_STAGE[gameTitle];
    if (!section || !state) return;

    var board = section.querySelector('.cp-board');
    board.classList.toggle('cp-locked', !!state.locked);

    var editBtn = section.querySelector('.cp-edit-btn');
    var readyBtn = section.querySelector('.cp-ready-btn');
    var finishBtn = section.querySelector('.cp-finish-btn');

    if (editBtn) editBtn.style.visibility = state.locked ? 'visible' : 'hidden';
    if (readyBtn) readyBtn.style.display = state.locked ? 'none' : (Object.keys(state.answers).length >= state.pairs.length ? 'flex' : 'none');
    if (finishBtn) { finishBtn.style.display = state.locked ? 'flex' : 'none'; finishBtn.disabled = false; }
}

function cpReady(gameTitle) {
    var state = CP_STAGE[gameTitle];
    var section = cpFindSection(gameTitle);
    if (!state || !section) return;
    state.locked = true;
    cpApplyLockState(gameTitle);

    // Highlight every connected item on both sides
    var board = section.querySelector('.cp-board');
    Object.keys(state.answers).forEach(function (leftText) {
        var rightText = state.answers[leftText];
        var leftEl = board.querySelector('.cp-item[data-side="left"][data-text="' + CSS.escape(leftText) + '"]');
        var rightEl = board.querySelector('.cp-item[data-side="right"][data-text="' + CSS.escape(rightText) + '"]');
        if (leftEl) leftEl.classList.add('cp-confirmed');
        if (rightEl) rightEl.classList.add('cp-confirmed');
    });

    cpSetBubble('Take a look at your matches. Tap "Finish" to submit, or "Edit" if you\'d like to change something.');
}

function cpEdit(gameTitle) {
    var state = CP_STAGE[gameTitle];
    var section = cpFindSection(gameTitle);
    if (!state || !section) return;
    state.locked = false;
    cpApplyLockState(gameTitle);

    // Remove the confirmed highlight from every item
    section.querySelectorAll('.cp-item.cp-confirmed').forEach(function (el) {
        el.classList.remove('cp-confirmed');
    });

    cpSetBubble('Drag from a dot on the left to its matching dot on the right.');
}

function openConnectPairsStage(gameTitle) {
    var section = cpFindSection(gameTitle);
    var overlay = document.getElementById('cpOverlay');
    if (!section || !overlay) return;

    if (overlay.parentElement !== document.body) document.body.appendChild(overlay);

    if (!section.dataset.homeMarked) {
        var marker = document.createElement('div');
        marker.className = 'cp-home-marker';
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

    var board = section.querySelector('.cp-board');
    var pairs = [];
    var rightShuffled = [];
    try { pairs = JSON.parse(board.dataset.pairs || '[]'); } catch (e) { pairs = []; }
    try { rightShuffled = JSON.parse(board.dataset.rightShuffled || '[]'); } catch (e) { rightShuffled = []; }

    var state = cpInitState(gameTitle, pairs, rightShuffled);
    cpRenderBoard(gameTitle);
    cpWireBoard(section);
    cpApplyLockState(gameTitle);
    cpUpdateNav(gameTitle);

    setTimeout(function () { cpRedrawLines(gameTitle); }, 60);

    cpSetBubble(state.locked
        ? 'Take a look at your matches. Tap "Finish" to submit, or "Edit" if you\'d like to change something.'
        : 'Drag from a dot on the left to its matching dot on the right.');
    adjustBonBonPosition('#cpOverlay', 450);

    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
        sessionStorage.setItem('connectpairs_open_lesson_' + LESSON_DATA.lessonId, gameTitle);
    }
    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.moduleId) {
        sessionStorage.setItem('lessons_view_module_' + LESSON_DATA.moduleId, 'lesson');
    }

    window.addEventListener('resize', function () {
        if (overlay.classList.contains('open')) cpRedrawLines(gameTitle);
    });
}

function closeConnectPairsStage(gameTitle) {
    var section = cpFindSection(gameTitle);
    var overlay = document.getElementById('cpOverlay');
    var marker = document.querySelector('.cp-home-marker[data-game-title="' + CSS.escape(gameTitle) + '"]');
    if (!section || !overlay) return;

    var state = CP_STAGE[gameTitle];
    if (state && state.justCompleted) {
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('connectpairs_open_lesson_' + LESSON_DATA.lessonId);
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
            sessionStorage.removeItem('connectpairs_open_lesson_' + LESSON_DATA.lessonId);
        }
    }, 280);
}

function cpReviewFindSection(gameTitle) {
    return document.querySelector('.cp-review-section[data-game-title="' + CSS.escape(gameTitle) + '"]');
}

function openConnectPairsReviewStage(gameTitle) {
    var section = cpReviewFindSection(gameTitle);
    var overlay = document.getElementById('cpOverlay');
    if (!section || !overlay) return;

    if (overlay.parentElement !== document.body) document.body.appendChild(overlay);

    if (!section.dataset.homeMarked) {
        var marker = document.createElement('div');
        marker.className = 'cp-review-home-marker';
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

    cpSetBubble('Here\'s how you did on "' + gameTitle + '"!');
    adjustBonBonPosition('#cpOverlay', 450);

    if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
        sessionStorage.setItem('connectpairs_review_open_lesson_' + LESSON_DATA.lessonId, gameTitle);
    }
}

function closeConnectPairsReviewStage(gameTitle) {
    var section = cpReviewFindSection(gameTitle);
    var overlay = document.getElementById('cpOverlay');
    var marker = document.querySelector('.cp-review-home-marker[data-game-title="' + CSS.escape(gameTitle) + '"]');
    if (!section || !overlay) return;

    overlay.classList.add('qz-closing');
    setTimeout(function () {
        if (marker && marker.parentNode) marker.parentNode.insertBefore(section, marker);
        section.style.display = 'none';
        overlay.classList.remove('open', 'qz-closing');
        document.body.style.overflow = document.body.dataset.prevOverflow || '';
        if (typeof LESSON_DATA !== 'undefined' && LESSON_DATA.lessonId) {
            sessionStorage.removeItem('connectpairs_review_open_lesson_' + LESSON_DATA.lessonId);
        }
    }, 280);
}

function cpSubmit(gameTitle) {
    var state = CP_STAGE[gameTitle];
    if (!state) return;
    var section = cpFindSection(gameTitle);
    var finishBtn = section && section.querySelector('.cp-finish-btn');
    if (finishBtn) finishBtn.disabled = true;

    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;

    var fd = new FormData();
    fd.append('lesson_id', lessonId);
    fd.append('game_title', gameTitle);
    Object.keys(state.answers).forEach(function (leftText) {
        fd.append('answers[' + leftText + ']', state.answers[leftText]);
    });

    fetch('/learning_management/public/?url=submit_connect_pairs', { method: 'POST', body: fd })
        .then(function (r) {
            return r.text().then(function (text) {
                var json; try { json = JSON.parse(text); } catch (e) { json = null; }
                return json;
            });
        })
        .then(function (resp) {
            if (!resp || resp.ok !== true) {
                if (finishBtn) finishBtn.disabled = false;
                alert('There was a problem saving your answers: ' + (resp && resp.msg ? resp.msg : 'unknown error') + '. Please try again.');
                return;
            }
            var correct = (typeof resp.score === 'number') ? resp.score : state.pairs.length;
            var total = (typeof resp.total === 'number') ? resp.total : state.pairs.length;

            state.justCompleted = true;

            if (LESSON_DATA && LESSON_DATA.connectpairs) {
                LESSON_DATA.connectpairs.forEach(function (cp) { if (cp.title === gameTitle) cp.done = true; });
            }
            checkLessonComplete();
            cpShowResults(gameTitle, correct, total);
        })
        .catch(function () {
            if (finishBtn) finishBtn.disabled = false;
            alert('Could not reach the server to save your answers. Please check your connection and try again.');
        });
}

function cpShowResults(gameTitle, correct, total) {
    var state = CP_STAGE[gameTitle];
    var section = cpFindSection(gameTitle);
    if (!state || !section) { closeConnectPairsStage(gameTitle); return; }

    var board = section.querySelector('.cp-board');
    var results = section.querySelector('.dd-results');
    var counter = section.querySelector('.cp-counter');
    var progressTrack = section.querySelector('.qz-progress-track');
    var questionCard = section.querySelector('.dd-question-card');

    if (board) board.style.display = 'none';
    if (counter) counter.style.display = 'none';
    if (progressTrack) progressTrack.style.display = 'none';
    if (questionCard) questionCard.style.display = 'none';

    var pct = total > 0 ? Math.round((correct / total) * 100) : 0;
    cpSetBubble('You matched ' + correct + ' out of ' + total + ' correctly (' + pct + '%)! ' +
        (pct === 100 ? 'Perfect score!' : pct >= 70 ? 'Great job!' : 'Nice try — review your matches below.'));

    if (results) {
        results.style.display = 'block';
        var fill = results.querySelector('.qz-accuracy-fill');
        var pctLabel = results.querySelector('.qz-accuracy-pct');
        if (fill) requestAnimationFrame(function () { fill.style.width = pct + '%'; });
        if (pctLabel) pctLabel.textContent = pct + '%';

        var list = results.querySelector('.dd-review-list');
        if (list) {
            list.innerHTML = '';
            state.pairs.forEach(function (p) {
                var given = state.answers[p.left];
                var isCorrect = given === p.right;
                var row = document.createElement('div');
                row.className = 'question-card';
                row.innerHTML =
                    '<div class="q-num-label">Item</div>' +
                    '<div class="q-text">' + escHtml(p.left) + '</div>' +
                    '<div class="review-choice" style="' + (isCorrect
                        ? 'border-color:var(--neon-green);background: rgba(57, 255, 158, .10); color: var(--neon-green)'
                        : 'border-color:#ff4d6d; background: rgba(255, 77, 109, .10); color: #ff4d6d;') + '">' +
                    '<span style="font-weight:700;margin-right:8px;">' + (isCorrect ? '✓ Correct' : '✗ Incorrect') + '</span>' +
                    'Your match: ' + escHtml(given || '—') +
                    (!isCorrect ? '<span style="margin-left:auto;color:#ff4d6d;">Correct: ' + escHtml(p.right) + '</span>' : '') +
                    '</div>';
                list.appendChild(row);
            });
        }
    }
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

    var countEl = results.querySelector('.qz-result-count');
    var pillCorrect = results.querySelector('.pill-correct');
    var pillIncorrect = results.querySelector('.pill-incorrect');
    if (countEl) countEl.textContent = total + ' item' + (total === 1 ? '' : 's');
    if (pillCorrect) pillCorrect.innerHTML = '<i class="fa fa-check"></i> ' + correct + ' Correct';
    if (pillIncorrect) pillIncorrect.innerHTML = '<i class="fa fa-times"></i> ' + (total - correct) + ' Incorrect';

    // NEW — hide everything else in the stage besides the board,
    // so only the results card shows (matching the clean reload view).
    var counter = section.querySelector('.dd-counter');
    var progressTrack = section.querySelector('.qz-progress-track');
    var questionCard = section.querySelector('.dd-question-card');

    if (board) board.style.display = 'none';
    if (counter) counter.style.display = 'none';
    if (progressTrack) progressTrack.style.display = 'none';
    if (questionCard) questionCard.style.display = 'none';

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
                    '<div class="review-choice" style="' + (isCorrect
                        ? 'border-color:var(--neon-green);background: rgba(57, 255, 158, .10); color: var(--neon-green)'
                        : 'border-color:#ff4d6d; background: rgba(255, 77, 109, .10); color: #ff4d6d;') + '">' +
                    '<span style="font-weight:700;margin-right:8px;">' +
                    (isCorrect ? '✓ Correct' : '✗ Incorrect') +
                    '</span>' +
                    'Your answer: ' + escHtml(given || '—') +
                    (!isCorrect ? '<span style="margin-left:auto;color:#ff4d6d;">Correct: ' + escHtml(item.category) + '</span>' : '') +
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

    var arrFlag = sessionStorage.getItem('arrange_open_lesson_' + LESSON_DATA.lessonId);
    if (arrFlag) openArrangeStage(arrFlag);

    var cpFlag = sessionStorage.getItem('connectpairs_open_lesson_' + LESSON_DATA.lessonId);
    if (cpFlag) openConnectPairsStage(cpFlag);

    var cpReviewFlag = sessionStorage.getItem('connectpairs_review_open_lesson_' + LESSON_DATA.lessonId);
    if (cpReviewFlag) openConnectPairsReviewStage(cpReviewFlag);

    var arrReviewFlag = sessionStorage.getItem('arrange_review_open_lesson_' + LESSON_DATA.lessonId);
    if (arrReviewFlag) openArrangeReviewStage(arrReviewFlag);
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
    if (QZ_LOCKED) return;
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

    if (!QZ_LOCKED) {
        qzRestoreSelection(blocks[UNIFIED_QZ.cur]);
    }
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
        if (QZ_LOCKED) {
            nextBtn.disabled = false;
            if (UNIFIED_QZ.cur === total - 1) {
                nextBtn.innerHTML = 'View Results <i class="fa fa-arrow-right"></i>';
                nextBtn.onclick = function () { backToQuizResultsFromReview(); };
            } else {
                nextBtn.innerHTML = 'Next <i class="fa fa-chevron-right"></i>';
                nextBtn.onclick = function () { qzNav(1); };
            }
            return;
        }

        var curBlock = blocks[UNIFIED_QZ.cur];
        var firstChoice = curBlock ? curBlock.querySelector('.qz-choice-btn') : null;
        var curQid = firstChoice ? firstChoice.dataset.qid : null;
        var answered = !!(curQid && UNIFIED_QZ.ans[curQid]);
        nextBtn.disabled = !answered;

        if (UNIFIED_QZ.cur === total - 1) {
            nextBtn.innerHTML = 'Finish <i class="fa fa-check"></i>';
            nextBtn.onclick = function () { finishQuiz(); };
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

    if (exitBtn) {
        exitBtn.style.display = '';
        exitBtn.onclick = function () {
            var lessonNextBtn = document.getElementById('nextBtn');
            if (lessonNextBtn && !lessonNextBtn.classList.contains('disabled')) {
                lessonNextBtn.click();
            } else {
                closeQuizStage();
            }
        };
    }
}

function finishQuiz() {
    var nextBtn = document.getElementById('qzNextBtn');
    if (nextBtn) {
        nextBtn.disabled = true;
        nextBtn.innerHTML = 'Saving…';
    }
    submitQuizStageResults(function () {
        renderQuizResultsUI(document.getElementById('qzStage'), document.getElementById('qzResults'));
    });
}

function submitQuizStageResults(callback) {
    var lessonId = LESSON_DATA ? LESSON_DATA.lessonId : 0;

    var groups = {};
    document.querySelectorAll('.qz-question-block:not(.act-question-block)').forEach(function (block) {
        var qzid = block.dataset.qzid;
        if (!qzid) return;
        var firstChoice = block.querySelector('.qz-choice-btn');
        var qid = firstChoice ? firstChoice.dataset.qid : null;
        if (!qid) return;
        if (!groups[qzid]) groups[qzid] = {};
        if (UNIFIED_QZ.ans[qid]) groups[qzid][qid] = UNIFIED_QZ.ans[qid];
    });

    var pending = [];
    (LESSON_DATA && LESSON_DATA.quizzes ? LESSON_DATA.quizzes : []).forEach(function (qz) {
        if (qz.done) return;
        pending.push({ id: qz.id, passing_score: qz.passing_score, answers: groups[qz.id] || {} });
    });

    console.log('[submitQuizStageResults] pending quizzes to save:', pending);

    if (!pending.length) {
        console.warn('[submitQuizStageResults] nothing pending — nothing will be saved. ' +
            'Check LESSON_DATA.quizzes / data-qzid values.');
    }

    submitQuizzesSequentially(pending, lessonId, function (allOk) {
        checkLessonComplete();
        if (!allOk) {
            alert('There was a problem saving your quiz. Your score is shown below, but it may not have been recorded — please try again or contact your teacher if this keeps happening.');
        } else {
            QUIZ_JUST_COMPLETED = true;
        }
        callback();
    });
}

function submitQuizzesSequentially(list, lessonId, callback, allOk) {
    allOk = (allOk === undefined) ? true : allOk;

    if (!list.length) { callback(allOk); return; }

    var item = list[0];
    var rest = list.slice(1);
    var fd = new FormData();
    fd.append('quiz_id', item.id);
    fd.append('lesson_id', lessonId);
    fd.append('passing_score', item.passing_score);
    Object.keys(item.answers).forEach(function (qid) {
        fd.append('answers[' + qid + ']', item.answers[qid]);
    });

    fetch('/learning_management/public/?url=submit_quiz', { method: 'POST', body: fd })
        .then(function (r) {
            return r.text().then(function (text) {
                console.log('[submitQuizzesSequentially] raw response for quiz ' + item.id + ':', text);
                var json;
                try { json = JSON.parse(text); } catch (e) { json = null; }
                return json;
            });
        })
        .then(function (resp) {
            var ok = !!(resp && resp.ok === true);
            if (!ok) {
                console.error('[submitQuizzesSequentially] quiz ' + item.id + ' was NOT saved:', resp);
            } else if (LESSON_DATA && LESSON_DATA.quizzes) {
                LESSON_DATA.quizzes.forEach(function (qz) { if (qz.id === item.id) qz.done = true; });
            }
            submitQuizzesSequentially(rest, lessonId, callback, allOk && ok);
        })
        .catch(function (err) {
            console.error('[submitQuizzesSequentially] fetch FAILED for quiz ' + item.id, err);
            submitQuizzesSequentially(rest, lessonId, callback, false);
        });
}

function renderQuizResultsUI(stage, results) {
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

    var backBtn = document.getElementById('qzResultsBackBtn');
    if (backBtn) {
        backBtn.onclick = function () {
            results.style.display = 'none';
            stage.style.display = 'block';
            if (exitBtn) exitBtn.style.display = '';

            enterQuizReviewMode();

            var blocks = document.querySelectorAll('.qz-question-block:not(.act-question-block)');
            if (blocks.length) {
                blocks[UNIFIED_QZ.cur].style.display = 'none';
                UNIFIED_QZ.cur = blocks.length - 1;
                blocks[UNIFIED_QZ.cur].style.display = 'block';
            }
            qzUpdateNav();
        };
    }
}

function enterQuizReviewMode() {
    QZ_LOCKED = true;
    document.querySelectorAll('.qz-question-block:not(.act-question-block) .qz-choice-btn').forEach(function (btn) {
        var qid = btn.dataset.qid;
        var key = btn.dataset.key;
        var isCorrect = btn.dataset.correct === '1';
        var picked = UNIFIED_QZ.ans[qid] === key;

        btn.classList.remove('selected');
        btn.classList.add('qz-review-choice');
        if (isCorrect) btn.classList.add('qz-correct');
        if (picked && !isCorrect) btn.classList.add('qz-wrong');
    });
}

function backToQuizResultsFromReview() {
    var stage = document.getElementById('qzStage');
    var results = document.getElementById('qzResults');
    if (stage) stage.style.display = 'none';
    if (results) results.style.display = 'block';

    var exitBtn = document.querySelector('#section-quizzes .btn-exit-quiz');
    if (exitBtn) exitBtn.style.display = 'none';
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

        (LESSON_DATA.arrangesteps || []).forEach(function (a) { if (!a.done) hasPending = true; });

        (LESSON_DATA.arrangesteps || []).forEach(function (a) {
            if (!a.done) allDone = false;
        });

        (LESSON_DATA.connectpairs || []).forEach(function (cp) { if (!cp.done) hasPending = true; });
        (LESSON_DATA.connectpairs || []).forEach(function (cp) { if (!cp.done) allDone = false; });

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