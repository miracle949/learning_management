/* ============================================================
   CREATE MODULE (TEACHER) — MODULE / LESSON / ORDERED BLOCK BUILDER
   ------------------------------------------------------------
   Every lesson holds an ordered list of "blocks" (text, image,
   video, quiz, activity, flashcard). Whatever order they sit in
   in the DOM is what gets submitted as blocks[mod][les][block]
   and saved as sort_order on tbl_interactive_contents — so text
   can come before an image, after a video, before another text
   block, etc. renumberAll() re-derives every input's name from
   current DOM position after any add / remove / move, so indices
   never drift.

   UPDATE: text blocks now use a small rich-text toolbar (bold,
   italic, underline, strikethrough, bullet/numbered list, clear
   formatting) instead of a plain textarea. The formatted HTML is
   mirrored into a hidden textarea[data-field="text"] on every
   edit, so the rest of the pipeline (renumberAll, form submit,
   the block_image[...] naming for image blocks) is unaffected —
   the server just receives HTML in `text` instead of plain text.
   IMPORTANT: because that field is now HTML, wherever it's
   rendered back out (student-facing lesson view) it must be
   echoed as raw HTML, not passed through htmlspecialchars().
============================================================ */

const BLOCK_META = {
    text: { label: 'Text', cls: 'tag-text' },
    image: { label: 'Image', cls: 'tag-image' },
    video: { label: 'Video', cls: 'tag-video' },
    quiz: { label: 'Quiz', cls: 'tag-quiz' },
    activity: { label: 'Activity', cls: 'tag-activity' },
    flashcard: { label: 'Flashcard', cls: 'tag-flashcard' }
};

const MAX_IMAGE_BYTES = 5 * 1024 * 1024; // 5MB

const RTE_BUTTONS = [
    { cmd: 'bold', icon: 'fa-bold', title: 'Bold' },
    { cmd: 'italic', icon: 'fa-italic', title: 'Italic' },
    { cmd: 'underline', icon: 'fa-underline', title: 'Underline' },
    { cmd: 'strikeThrough', icon: 'fa-strikethrough', title: 'Strikethrough' },
    { sep: true },
    { cmd: 'insertUnorderedList', icon: 'fa-list-ul', title: 'Bulleted list' },
    { cmd: 'insertOrderedList', icon: 'fa-list-ol', title: 'Numbered list' },
    { sep: true },
    { cmd: 'removeFormat', icon: 'fa-eraser', title: 'Clear formatting' }
];

document.addEventListener('DOMContentLoaded', function () {
    const addModuleBtn = document.getElementById('addModuleBtn');
    if (addModuleBtn) addModuleBtn.addEventListener('click', addModule);
});

/* ============================================================
   MODULE
============================================================ */
function addModule() {
    const container = document.getElementById('contentContainer');
    const emptyState = document.getElementById('contentEmpty');
    if (emptyState) emptyState.remove();

    const wrap = document.createElement('div');
    wrap.innerHTML = `
    <div class="card-parent-box module-item">
      <div class="card-header">
        <h3><i class="fa fa-layer-group"></i> <span class="module-index-label">Module</span></h3>
        <button type="button" class="remove-btn" onclick="removeModule(this)" title="Remove module">
          <i class="fa fa-times"></i>
        </button>
      </div>

      <label style="margin: 1rem 0 0.3rem;">Module Title *</label>
      <input type="text" class="module-title-input form-control" placeholder="Enter module title">

      <label style="margin: 1rem 0 0.3rem;">Module Description</label>
      <textarea class="module-description-input form-control" placeholder="Brief description of this module"></textarea>

      <button type="button" class="btn-add-lesson" onclick="addLesson(this)">
        <i class="fa fa-plus"></i> Add Lesson
      </button>

      <div class="lessons-wrap"></div>
    </div>`;
    container.appendChild(wrap.firstElementChild);
    renumberAll();
}

function removeModule(btn) {
    btn.closest('.module-item').remove();
    renumberAll();
    maybeShowEmptyState();
}

/* ============================================================
   LESSON
============================================================ */
function addLesson(btn) {
    const moduleItem = btn.closest('.module-item');
    const lessonsWrap = moduleItem.querySelector('.lessons-wrap');

    const wrap = document.createElement('div');
    wrap.innerHTML = `
    <div class="lesson-item">
      <div class="lesson-head">
        <h4><i class="fa fa-file-alt"></i> <span class="lesson-index-label">Lesson</span></h4>
        <button type="button" class="remove-btn" onclick="removeLesson(this)" title="Remove lesson">
          <i class="fa fa-times"></i>
        </button>
      </div>

      <div class="field-row">
        <div>
          <label>Lesson Title *</label>
          <input type="text" class="lesson-title-input" placeholder="Enter lesson title">
        </div>
        <div>
          <label>Topic</label>
          <input type="text" class="lesson-topic-input" placeholder="Enter topic (optional)">
        </div>
      </div>

      <div class="content-label">Lesson Content</div>

      <div class="block-list"></div>

      <div class="add-toolbar">
        <button type="button" onclick="addBlockToLesson(this, 'text')"><i class="fa fa-align-left"></i> Add Text</button>
        <button type="button" onclick="addBlockToLesson(this, 'image')"><i class="fa fa-image"></i> Add Image</button>
        <button type="button" onclick="addBlockToLesson(this, 'video')"><i class="fa fa-video"></i> Add Video</button>
        <button type="button" onclick="addBlockToLesson(this, 'quiz')"><i class="fa fa-question-circle"></i> Add Quiz</button>
        <button type="button" onclick="addBlockToLesson(this, 'activity')"><i class="fa fa-pen"></i> Add Activity</button>
        <button type="button" onclick="addBlockToLesson(this, 'flashcard')"><i class="fa fa-clone"></i> Add Flashcard</button>
      </div>
    </div>`;
    lessonsWrap.appendChild(wrap.firstElementChild);
    renumberAll();
}

function removeLesson(btn) {
    btn.closest('.lesson-item').remove();
    renumberAll();
}

/* ============================================================
   BLOCK LIST HELPERS (insert-between rows)
============================================================ */
function ensureInsertRows(blockList) {
    blockList.querySelectorAll(':scope > .insert-row').forEach(r => r.remove());
    const blocks = Array.from(blockList.children).filter(el => el.classList.contains('block'));
    blocks.forEach(block => blockList.insertBefore(buildInsertRow(), block));
    if (blocks.length) blockList.appendChild(buildInsertRow());
}

function buildInsertRow() {
    const row = document.createElement('div');
    row.className = 'insert-row';
    row.innerHTML = `
    <div class="line"></div>
    <button type="button" class="insert-add" title="Insert a block here" onclick="toggleInsertMenu(this)">
      <i class="fa fa-plus"></i>
    </button>
    <div class="line"></div>`;
    return row;
}

function toggleInsertMenu(btn) {
    const row = btn.closest('.insert-row');
    const existing = row.querySelector('.mini-menu');
    if (existing) { existing.remove(); return; }

    const menu = document.createElement('div');
    menu.className = 'mini-menu';
    menu.innerHTML = Object.keys(BLOCK_META).map(type =>
        `<button type="button" onclick="insertBlockAt('${type}', this)">${BLOCK_META[type].label}</button>`
    ).join('');
    row.appendChild(menu);
}

function insertBlockAt(type, menuBtn) {
    const row = menuBtn.closest('.insert-row');
    const blockList = row.closest('.block-list');
    const nextBlock = row.nextElementSibling && row.nextElementSibling.classList.contains('block')
        ? row.nextElementSibling
        : null;
    const block = buildBlockElement(type);
    blockList.insertBefore(block, nextBlock);
    ensureInsertRows(blockList);
    renumberAll();
}

/* ============================================================
   ADD BLOCK (from bottom toolbar)
============================================================ */
function addBlockToLesson(btn, type) {
    const lessonItem = btn.closest('.lesson-item');
    const blockList = lessonItem.querySelector('.block-list');
    const block = buildBlockElement(type);
    blockList.appendChild(block);
    ensureInsertRows(blockList);
    renumberAll();
}

function moveBlock(btn, dir) {
    const block = btn.closest('.block');
    const blockList = block.closest('.block-list');
    const blocks = Array.from(blockList.children).filter(el => el.classList.contains('block'));
    const idx = blocks.indexOf(block);
    const swapIdx = idx + dir;
    if (swapIdx < 0 || swapIdx >= blocks.length) return;

    if (dir === -1) blockList.insertBefore(block, blocks[swapIdx]);
    else blockList.insertBefore(blocks[swapIdx], block);

    ensureInsertRows(blockList);
    renumberAll();
}

function removeBlock(btn) {
    const block = btn.closest('.block');
    const blockList = block.closest('.block-list');
    block.remove();
    ensureInsertRows(blockList);
    renumberAll();
}

/* ============================================================
   BLOCK BUILDERS
============================================================ */
function buildBlockElement(type) {
    const meta = BLOCK_META[type];
    const el = document.createElement('div');
    el.className = 'block';
    el.dataset.type = type;
    el.innerHTML = `
    <div class="block-handle"><i class="fa fa-grip-vertical"></i></div>
    <div class="block-body">
      <div class="block-top">
        <span class="block-type-tag ${meta.cls}">${meta.label}</span>
        <div class="block-actions">
          <button type="button" onclick="moveBlock(this, -1)" title="Move up"><i class="fa fa-chevron-up"></i></button>
          <button type="button" onclick="moveBlock(this, 1)" title="Move down"><i class="fa fa-chevron-down"></i></button>
          <button type="button" class="danger" onclick="removeBlock(this)" title="Delete"><i class="fa fa-trash"></i></button>
        </div>
      </div>
      <div class="block-fields">${blockFieldsMarkup(type)}</div>
    </div>`;

    if (type === 'image') {
        const addBtn = el.querySelector('.btn-add-image');
        addImageItem(addBtn); // seed the block with one image slot
    }

    if (type === 'text') {
        wireRichTextEditor(el);
    }

    return el;
}

function blockFieldsMarkup(type) {
    if (type === 'text') {
        return `
      <input type="text" class="text-heading-input" data-field="heading" placeholder="Section heading (optional) — e.g. The Software Layer">
      <div class="rte-group">
        <div class="rte-toolbar">${rteToolbarMarkup()}</div>
        <div class="rte-editor" contenteditable="true" data-placeholder="Write the paragraph or section content..."></div>
      </div>
      <textarea data-field="text" style="display:none;"></textarea>
      <textarea class="text-keyidea-input" data-field="key_idea" placeholder="Key idea / callout (optional) — e.g. The operating system is the translator between hardware and application software..."></textarea>`;
    }

    if (type === 'image') {
        return `
    <input type="text" data-field="image_title" placeholder="Group title / caption (optional) — e.g. Visual References">
    <div class="image-items-wrap"></div>
    <button type="button" class="btn-add-question btn-add-image" onclick="addImageItem(this)">
      <i class="fa fa-plus"></i> Add image
    </button>`;
    }

    if (type === 'video') {
        return `
      <input type="text" data-field="video_title" placeholder="Video title (e.g. Hardware vs. Software, Explained in 4 Minutes)">
      <input type="text" data-field="video_url" placeholder="Video URL">`;
    }

    if (type === 'quiz') {
        return `
      <input type="text" data-field="quiz_title" placeholder="Quiz title">
      <textarea data-field="quiz_instructions" placeholder="Instructions (optional)"></textarea>
      <input type="number" data-field="quiz_passing_score" placeholder="Passing score (%)" value="75" min="0" max="100">
      <div class="questions-wrap"></div>
      <button type="button" class="btn-add-question" onclick="addQuizQuestion(this)">
        <i class="fa fa-plus"></i> Add question
      </button>`;
    }

    if (type === 'activity') {
        return `
      <input type="text" data-field="activity_title" placeholder="Activity title">
      <textarea data-field="activity_instructions" placeholder="Instructions (optional)"></textarea>
      <input type="number" data-field="activity_points" placeholder="Total points" value="10" min="0">
      <div class="questions-wrap"></div>
      <button type="button" class="btn-add-question" onclick="addActivityQuestion(this)">
        <i class="fa fa-plus"></i> Add question
      </button>`;
    }

    if (type === 'flashcard') {
        return `
      <div class="cards-wrap"></div>
      <button type="button" class="btn-add-question" onclick="addFlashcard(this)">
        <i class="fa fa-plus"></i> Add flashcard
      </button>`;
    }

    return '';
}

/* ---------- rich text editor (text block body) ---------- */
function rteToolbarMarkup() {
    return RTE_BUTTONS.map(b => {
        if (b.sep) return `<span class="rte-sep"></span>`;
        return `<button type="button" class="rte-btn" data-cmd="${b.cmd}" title="${b.title}"><i class="fa ${b.icon}"></i></button>`;
    }).join('');
}

function wireRichTextEditor(blockEl) {
    const toolbar = blockEl.querySelector('.rte-toolbar');
    const editor = blockEl.querySelector('.rte-editor');
    const hidden = blockEl.querySelector('textarea[data-field="text"]');
    if (!toolbar || !editor || !hidden) return;

    function syncHidden() {
        hidden.value = editor.innerHTML.trim();
    }

    function updateToolbarState() {
        toolbar.querySelectorAll('.rte-btn[data-cmd]').forEach(btn => {
            const cmd = btn.dataset.cmd;
            if (['bold', 'italic', 'underline', 'strikeThrough'].includes(cmd)) {
                let state = false;
                try { state = document.queryCommandState(cmd); } catch (e) { /* noop */ }
                btn.classList.toggle('active', state);
            }
        });
    }

    toolbar.querySelectorAll('.rte-btn[data-cmd]').forEach(btn => {
        // keep the text selection alive — a mousedown on the toolbar
        // would otherwise collapse it before the click fires
        btn.addEventListener('mousedown', (e) => e.preventDefault());
        btn.addEventListener('click', () => {
            editor.focus();
            document.execCommand(btn.dataset.cmd, false, null);
            syncHidden();
            updateToolbarState();
        });
    });

    editor.addEventListener('input', syncHidden);
    editor.addEventListener('keyup', updateToolbarState);
    editor.addEventListener('mouseup', updateToolbarState);
    editor.addEventListener('focus', updateToolbarState);
}

/* ---------- image items (one block can hold multiple images) ---------- */
function addImageItem(btn) {
    const wrap = btn.previousElementSibling; // .image-items-wrap
    const row = document.createElement('div');
    row.className = 'image-item';
    row.innerHTML = `
    <div class="question-row-head">
      <span>Image</span>
      <button type="button" class="danger" onclick="this.closest('.image-item').remove(); renumberAll();"><i class="fa fa-trash"></i></button>
    </div>
    <input type="file" accept=".jpg,.jpeg,.png,.gif,.webp,image/*" data-ifield="image_file" class="image-file-input" style="display:none;">
    <div class="image-upload-box">
      <div class="image-upload-icon"><i class="fa fa-cloud-upload-alt"></i></div>
      <p class="image-upload-title">Click to upload image</p>
      <span class="image-upload-hint">JPG, PNG, GIF &nbsp;·&nbsp; Max 5MB</span>
      <div class="image-upload-choose">Choose File</div>
    </div>
    <div class="image-preview" style="display:none;">
      <img src="" alt="Preview" class="preview-img">
      <button type="button" class="remove-preview-btn"><i class="fa fa-times"></i> Remove Image</button>
    </div>`;
    wrap.appendChild(row);
    wireImageItemUpload(row);
    renumberAll();
}

function wireImageItemUpload(row) {
    const uploadBox = row.querySelector('.image-upload-box');
    const fileInput = row.querySelector('.image-file-input');
    const previewDiv = row.querySelector('.image-preview');
    const previewImg = row.querySelector('.preview-img');
    const removeBtn = row.querySelector('.remove-preview-btn');

    uploadBox.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) return;
        if (file.size > MAX_IMAGE_BYTES) {
            alert('Image is too large. Maximum size is 5MB.');
            fileInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            uploadBox.style.display = 'none';
            previewDiv.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    });

    removeBtn.addEventListener('click', () => {
        fileInput.value = '';
        previewImg.src = '';
        previewDiv.style.display = 'none';
        uploadBox.style.display = 'flex';
    });
}

/* ---------- quiz questions ---------- */
function addQuizQuestion(btn) {
    const wrap = btn.previousElementSibling; // .questions-wrap
    const row = document.createElement('div');
    row.className = 'question-row';
    row.innerHTML = `
    <div class="question-row-head">
      <span>Question</span>
      <button type="button" class="danger" onclick="this.closest('.question-row').remove(); renumberAll();"><i class="fa fa-trash"></i></button>
    </div>
    <input type="text" data-qfield="text" placeholder="Question text">
    <div class="choice-grid">
      <input type="text" data-qfield="choice_a" placeholder="Choice A">
      <input type="text" data-qfield="choice_b" placeholder="Choice B">
      <input type="text" data-qfield="choice_c" placeholder="Choice C">
      <input type="text" data-qfield="choice_d" placeholder="Choice D">
    </div>
    <select data-qfield="correct">
      <option value="a">Correct answer: A</option>
      <option value="b">Correct answer: B</option>
      <option value="c">Correct answer: C</option>
      <option value="d">Correct answer: D</option>
    </select>`;
    wrap.appendChild(row);
    renumberAll();
}

/* ---------- activity questions ---------- */
function addActivityQuestion(btn) {
    const wrap = btn.previousElementSibling; // .questions-wrap
    const row = document.createElement('div');
    row.className = 'question-row';
    row.innerHTML = `
    <div class="question-row-head">
      <span>Question</span>
      <button type="button" class="danger" onclick="this.closest('.question-row').remove(); renumberAll();"><i class="fa fa-trash"></i></button>
    </div>
    <select data-qfield="type" onchange="toggleActivityQuestionType(this)">
      <option value="essay">Essay</option>
      <option value="multiple_choice">Multiple choice</option>
    </select>
    <input type="text" data-qfield="text" placeholder="Question text">
    <textarea class="essay-fields" data-qfield="essay_answer" placeholder="Model answer (optional, for grading reference)"></textarea>
    <div class="choice-grid mc-fields" style="display:none">
      <input type="text" data-qfield="choice_a" placeholder="Choice A">
      <input type="text" data-qfield="choice_b" placeholder="Choice B">
      <input type="text" data-qfield="choice_c" placeholder="Choice C">
      <input type="text" data-qfield="choice_d" placeholder="Choice D">
    </div>
    <select class="mc-fields" data-qfield="correct" style="display:none">
      <option value="a">Correct answer: A</option>
      <option value="b">Correct answer: B</option>
      <option value="c">Correct answer: C</option>
      <option value="d">Correct answer: D</option>
    </select>`;
    wrap.appendChild(row);
    renumberAll();
}

function toggleActivityQuestionType(select) {
    const row = select.closest('.question-row');
    const isMC = select.value === 'multiple_choice';
    row.querySelectorAll('.mc-fields').forEach(el => el.style.display = isMC ? '' : 'none');
    row.querySelectorAll('.essay-fields').forEach(el => el.style.display = isMC ? 'none' : '');
}

/* ---------- flashcards ---------- */
function addFlashcard(btn) {
    const wrap = btn.previousElementSibling; // .cards-wrap
    const row = document.createElement('div');
    row.className = 'card-row';
    row.innerHTML = `
    <div class="question-row-head">
      <span>Card</span>
      <button type="button" class="danger" onclick="this.closest('.card-row').remove(); renumberAll();"><i class="fa fa-trash"></i></button>
    </div>
    <select data-cfield="card_type">
      <option value="term_definition">Term / definition</option>
      <option value="question_answer">Question / answer</option>
    </select>
    <input type="text" data-cfield="front" placeholder="Front">
    <input type="text" data-cfield="back" placeholder="Back">`;
    wrap.appendChild(row);
    renumberAll();
}

/* ============================================================
   RENUMBER — re-derives every input's `name` from current DOM
   position. Run after ANY add / remove / move so mod/lesson/
   block indices always match what's actually on screen.
============================================================ */
function renumberAll() {
    const modules = Array.from(document.querySelectorAll('#contentContainer > .module-item'));

    modules.forEach((moduleEl, modIdx) => {
        moduleEl.querySelector('.module-index-label').textContent = `Module ${modIdx + 1}`;
        moduleEl.querySelector('.module-title-input').name = `module_title[${modIdx}]`;
        moduleEl.querySelector('.module-description-input').name = `module_description[${modIdx}]`;

        const lessons = Array.from(moduleEl.querySelectorAll(':scope > .lessons-wrap > .lesson-item'));

        lessons.forEach((lessonEl, lesIdx) => {
            lessonEl.querySelector('.lesson-index-label').textContent = `Lesson ${lesIdx + 1}`;
            lessonEl.querySelector('.lesson-title-input').name = `lesson_title[${modIdx}][${lesIdx}]`;
            lessonEl.querySelector('.lesson-topic-input').name = `lesson_topic[${modIdx}][${lesIdx}]`;

            const blocks = Array.from(lessonEl.querySelectorAll(':scope > .block-list > .block'));

            blocks.forEach((blockEl, blockIdx) => {
                const prefix = `blocks[${modIdx}][${lesIdx}][${blockIdx}]`;
                blockEl.dataset.blockIdx = blockIdx;

                // type marker — no visible input needed, controller reads it
                // via a hidden field so PHP always knows what this block is
                let typeInput = blockEl.querySelector(':scope > .block-body > input[data-field="__type"]');
                if (!typeInput) {
                    typeInput = document.createElement('input');
                    typeInput.type = 'hidden';
                    typeInput.dataset.field = '__type';
                    typeInput.value = blockEl.dataset.type;
                    blockEl.querySelector('.block-body').appendChild(typeInput);
                }
                typeInput.name = `${prefix}[type]`;
                typeInput.value = blockEl.dataset.type;

                // simple top-level fields (text, heading, key_idea, video_title,
                // video_url, quiz_title, quiz_instructions, quiz_passing_score,
                // activity_title, activity_instructions, activity_points)
                blockEl.querySelectorAll(':scope > .block-body > .block-fields > [data-field]').forEach(input => {
                    input.name = `${prefix}[${input.dataset.field}]`;
                });

                // nested quiz/activity questions
                const questionRows = blockEl.querySelectorAll(':scope .question-row');
                questionRows.forEach((qRow, qIdx) => {
                    qRow.querySelectorAll('[data-qfield]').forEach(input => {
                        input.name = `${prefix}[questions][${qIdx}][${input.dataset.qfield}]`;
                    });
                });

                // nested flashcards
                const cardRows = blockEl.querySelectorAll(':scope .card-row');
                cardRows.forEach((cRow, cIdx) => {
                    cRow.querySelectorAll('[data-cfield]').forEach(input => {
                        input.name = `${prefix}[cards][${cIdx}][${input.dataset.cfield}]`;
                    });
                });

                // nested image items (one image block can hold several images)
                const imageItems = blockEl.querySelectorAll(':scope .image-item');
                imageItems.forEach((imgRow, imgIdx) => {
                    const fileInput = imgRow.querySelector('[data-ifield="image_file"]');
                    if (fileInput) {
                        fileInput.name = `block_image[${modIdx}][${lesIdx}][${blockIdx}][${imgIdx}]`;
                    }
                });
            });
        });
    });
}

function maybeShowEmptyState() {
    const container = document.getElementById('contentContainer');
    if (container.querySelector('.module-item')) return;
    if (document.getElementById('contentEmpty')) return;

    const empty = document.createElement('div');
    empty.className = 'text-content';
    empty.id = 'contentEmpty';
    empty.style.display = 'flex';
    empty.innerHTML = `<i class="fa fa-inbox"></i><p>No modules yet — click "Add Module" to start.</p>`;
    container.appendChild(empty);
}