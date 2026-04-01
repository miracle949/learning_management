document.addEventListener("DOMContentLoaded", function () {

    const contentContainer = document.getElementById("contentContainer");
    const addModuleBtn = document.getElementById("addModuleBtn");

    let modCount = 0;

    function createRemoveButton() {
        return `<button type="button" class="remove-item btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>`;
    }

    function checkEmpty() {
        const noModules = contentContainer.querySelectorAll(".module-item").length === 0;
        const emptyEl = contentContainer.querySelector(".text-content");
        if (emptyEl) emptyEl.style.display = noModules ? "flex" : "none";
    }

    function buildVideo(modIdx, lesIdx, vCount) {
        const el = document.createElement("div");
        el.className = "card-body video-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-video"></i></div>
                    <p class="video-label">Video ${vCount}</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="video_title[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="Enter video title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>YouTube Embed URL *</label>
                    <input type="url" name="video_url[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="https://www.youtube.com/embed/VIDEO_ID">
                    <small class="text-muted mt-1 d-block"><i class="fa fa-info-circle"></i> Use embed URL format: youtube.com/<strong>embed</strong>/VIDEO_ID</small>
                </div>
            </div>`;
        return el;
    }

    function buildImage(modIdx, lesIdx, iCount) {
        const el = document.createElement("div");
        el.className = "card-body image-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-image"></i></div>
                    <p class="image-label">Image ${iCount}</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Caption / Title</label>
                    <input type="text" name="image_title[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="Image caption (optional)">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Upload Image *</label>
                    <div class="image-upload-area mt-2">
                        <input type="file" name="image_file[${modIdx}][${lesIdx}][]" class="image-file-input" accept="image/*" style="display:none;">
                        <div class="image-upload-box">
                            <i class="fa fa-cloud-upload-alt"></i>
                            <p>Click to upload image</p>
                            <span>JPG, PNG, GIF (max 5MB)</span>
                        </div>
                        <div class="image-preview" style="display:none;">
                            <img src="" alt="Preview" class="preview-img">
                            <button type="button" class="remove-preview-btn"><i class="fa fa-times"></i> Remove</button>
                        </div>
                    </div>
                </div>
            </div>`;

        const uploadBox = el.querySelector(".image-upload-box");
        const fileInput = el.querySelector(".image-file-input");
        const previewDiv = el.querySelector(".image-preview");
        const previewImg = el.querySelector(".preview-img");
        const removeBtn = el.querySelector(".remove-preview-btn");

        uploadBox.addEventListener("click", () => fileInput.click());
        fileInput.addEventListener("change", () => {
            const file = fileInput.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { alert("Image is too large. Maximum size is 5MB."); fileInput.value = ""; return; }
            const reader = new FileReader();
            reader.onload = (e) => { previewImg.src = e.target.result; uploadBox.style.display = "none"; previewDiv.style.display = "flex"; };
            reader.readAsDataURL(file);
        });
        removeBtn.addEventListener("click", () => { fileInput.value = ""; previewImg.src = ""; previewDiv.style.display = "none"; uploadBox.style.display = "flex"; });
        return el;
    }

    function buildActivityQuestion(modIdx, lesIdx, aIdx, qCount) {
        const q = document.createElement("div");
        q.className = "activity-question-item card-body";
        q.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-pencil"></i></div><p class="activity-question-num mb-0" style="font-size:15px;font-weight:600;">Question ${qCount}</p></div>
                <button type="button" class="remove-activity-question btn btn-sm"><i class="fa fa-times"></i></button>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-3">
                    <label>Question Type *</label>
                    <select name="activity_question_type[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2 activity-question-type-select">
                        <option value="essay">Essay / Open-ended</option>
                        <option value="multiple_choice">Multiple Choice</option>
                    </select>
                </div>
                <div class="col-lg-12 mt-3"><label>Question *</label><input type="text" name="activity_question_text[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2" placeholder="Enter your question"></div>
                <div class="activity-essay-fields col-lg-12 mt-3"><label>Model Answer <span style="color:#aaa;font-size:12px;">(optional)</span></label><textarea name="activity_essay_answer[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2" rows="4" placeholder="Sample answer for teacher reference..."></textarea></div>
                <div class="activity-mc-fields" style="display:none; width:100%;">
                    <div class="col-lg-12 mt-3"><label>Choice A *</label><input type="text" name="activity_choice_a[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2" placeholder="Choice A"></div>
                    <div class="col-lg-12 mt-3"><label>Choice B *</label><input type="text" name="activity_choice_b[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2" placeholder="Choice B"></div>
                    <div class="col-lg-12 mt-3"><label>Choice C</label><input type="text" name="activity_choice_c[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2" placeholder="Choice C (optional)"></div>
                    <div class="col-lg-12 mt-3"><label>Choice D</label><input type="text" name="activity_choice_d[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2" placeholder="Choice D (optional)"></div>
                    <div class="col-lg-12 mt-3"><label>Correct Answer *</label><select name="activity_correct_answer[${modIdx}][${lesIdx}][${aIdx}][]" class="form-control mt-2"><option value="">-- Select correct answer --</option><option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option></select></div>
                </div>
            </div>`;
        const sel = q.querySelector(".activity-question-type-select");
        const essay = q.querySelector(".activity-essay-fields");
        const mc = q.querySelector(".activity-mc-fields");
        sel.addEventListener("change", () => { if (sel.value === "multiple_choice") { essay.style.display = "none"; mc.style.display = "block"; } else { essay.style.display = "block"; mc.style.display = "none"; } });
        return q;
    }

    function buildActivity(modIdx, lesIdx, aCount) {
        const aIdx = aCount - 1;
        const el = document.createElement("div");
        el.className = "card-body activity-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-circle-question"></i></div><p class="activity-label">Activity ${aCount}</p></div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4"><label>Activity Title *</label><input type="text" name="activity_title[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="Enter activity title"></div>
                <div class="col-lg-12 mt-4"><label>Instructions</label><textarea name="activity_instructions[${modIdx}][${lesIdx}][]" class="form-control mt-2" rows="3" placeholder="Instructions for students"></textarea></div>
                <div class="col-lg-6 mt-4"><label>Total Points</label><input type="number" name="activity_points[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="e.g. 10" min="1"></div>
                <div class="col-lg-6 mt-4"><label>Time Limit (minutes)</label><input type="number" name="activity_time[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="e.g. 20" min="1"></div>
            </div>
            <div class="activity-questions-container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="mb-0" style="font-weight:600;">Questions</label>
                    <button type="button" class="btn-add-activity-question"><i class="fa fa-plus"></i> Add Question</button>
                </div>
                <div class="activity-questions-list d-flex flex-column gap-3">
                    <div class="activity-questions-empty text-content" style="margin-top:0;"><i class="fa fa-circle-question"></i><p>No questions yet — click "Add Question" to start.</p></div>
                </div>
            </div>`;
        const qList = el.querySelector(".activity-questions-list");
        const qEmpty = el.querySelector(".activity-questions-empty");
        const addBtn = el.querySelector(".btn-add-activity-question");
        addBtn.addEventListener("click", () => {
            qEmpty.style.display = "none";
            const qCount = qList.querySelectorAll(".activity-question-item").length + 1;
            const q = buildActivityQuestion(modIdx, lesIdx, aIdx, qCount);
            qList.appendChild(q);
            q.querySelector(".remove-activity-question").addEventListener("click", () => {
                q.remove();
                qList.querySelectorAll(".activity-question-item").forEach((item, i) => { item.querySelector(".activity-question-num").textContent = "Question " + (i + 1); });
                if (qList.querySelectorAll(".activity-question-item").length === 0) qEmpty.style.display = "flex";
            });
        });
        return el;
    }

    function buildQuizQuestion(modIdx, lesIdx, qzIdx, qCount) {
        const q = document.createElement("div");
        q.className = "question-item card-body";
        q.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-list-check"></i></div><p class="question-num mb-0" style="font-size:15px;font-weight:600;">Question ${qCount}</p></div>
                <button type="button" class="remove-question btn btn-sm"><i class="fa fa-times"></i></button>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-3"><label>Question *</label><input type="text" name="question_text[${modIdx}][${lesIdx}][${qzIdx}][]" class="form-control mt-2" placeholder="Enter your question"></div>
                <div class="col-lg-6 mt-3"><label>Choice A *</label><input type="text" name="choice_a[${modIdx}][${lesIdx}][${qzIdx}][]" class="form-control mt-2" placeholder="Choice A"></div>
                <div class="col-lg-6 mt-3"><label>Choice B *</label><input type="text" name="choice_b[${modIdx}][${lesIdx}][${qzIdx}][]" class="form-control mt-2" placeholder="Choice B"></div>
                <div class="col-lg-6 mt-3"><label>Choice C</label><input type="text" name="choice_c[${modIdx}][${lesIdx}][${qzIdx}][]" class="form-control mt-2" placeholder="Choice C (optional)"></div>
                <div class="col-lg-6 mt-3"><label>Choice D</label><input type="text" name="choice_d[${modIdx}][${lesIdx}][${qzIdx}][]" class="form-control mt-2" placeholder="Choice D (optional)"></div>
                <div class="col-lg-12 mt-3"><label>Correct Answer *</label><select name="correct_answer[${modIdx}][${lesIdx}][${qzIdx}][]" class="form-control mt-2"><option value="">-- Select --</option><option value="a">A</option><option value="b">B</option><option value="c">C</option><option value="d">D</option></select></div>
            </div>`;
        return q;
    }

    function buildQuiz(modIdx, lesIdx, qzCount) {
        const qzIdx = qzCount - 1;
        const el = document.createElement("div");
        el.className = "card-body quiz-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-pen-to-square"></i></div><p class="quiz-label">Quiz ${qzCount}</p></div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4"><label>Quiz Title *</label><input type="text" name="quiz_title[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="Enter quiz title"></div>
                <div class="col-lg-12 mt-4"><label>Instructions</label><textarea name="quiz_instructions[${modIdx}][${lesIdx}][]" class="form-control mt-2" rows="3" placeholder="Instructions for students"></textarea></div>
                <div class="col-lg-6 mt-4"><label>Time Limit (minutes)</label><input type="number" name="quiz_time_limit[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="e.g. 30" min="1"></div>
                <div class="col-lg-6 mt-4"><label>Passing Score (%)</label><input type="number" name="quiz_passing_score[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="e.g. 75" min="1" max="100"></div>
            </div>
            <div class="quiz-questions-container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="mb-0" style="font-weight:600;">Questions</label>
                    <button type="button" class="btn-add-question"><i class="fa fa-plus"></i> Add Question</button>
                </div>
                <div class="questions-list d-flex flex-column gap-3">
                    <div class="quiz-questions-empty text-content" style="margin-top:0;"><i class="fa fa-circle-question"></i><p>No questions yet — click "Add Question" to start.</p></div>
                </div>
            </div>`;
        const qList = el.querySelector(".questions-list");
        const qEmpty = el.querySelector(".quiz-questions-empty");
        const addBtn = el.querySelector(".btn-add-question");
        addBtn.addEventListener("click", () => {
            qEmpty.style.display = "none";
            const qqCount = qList.querySelectorAll(".question-item").length + 1;
            const q = buildQuizQuestion(modIdx, lesIdx, qzIdx, qqCount);
            qList.appendChild(q);
            q.querySelector(".remove-question").addEventListener("click", () => {
                q.remove();
                qList.querySelectorAll(".question-item").forEach((item, i) => { item.querySelector(".question-num").textContent = "Question " + (i + 1); });
                if (qList.querySelectorAll(".question-item").length === 0) qEmpty.style.display = "flex";
            });
        });
        return el;
    }

    function buildFlashcard(modIdx, lesIdx) {
        const el = document.createElement("div");
        el.className = "card-body flashcard-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-clone"></i></div><p class="flashcard-label">Flashcard</p></div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4"><label>Card Type *</label><select name="flashcard_type[${modIdx}][${lesIdx}][]" class="form-control mt-2"><option value="term_definition">Term / Definition</option><option value="question_answer">Question / Answer</option></select></div>
                <div class="col-lg-12 mt-4"><label>Front (Term or Question) *</label><input type="text" name="flashcard_front[${modIdx}][${lesIdx}][]" class="form-control mt-2" placeholder="Enter term or question"></div>
                <div class="col-lg-12 mt-4"><label>Back (Definition or Answer) *</label><textarea name="flashcard_back[${modIdx}][${lesIdx}][]" class="form-control mt-2" rows="3" placeholder="Enter definition or answer"></textarea></div>
            </div>`;
        return el;
    }

    function buildLesson(modIdx, lesIdx) {
        const el = document.createElement("div");
        el.className = "card-body lesson-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-file"></i></div><p class="lesson-label">Lesson ${lesIdx + 1}</p></div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4"><label>Lesson Title *</label><input type="text" name="lesson_title[${modIdx}][]" class="form-control mt-2" placeholder="Enter lesson title"></div>
                <div class="col-lg-12 mt-4"><label>Topic</label><input type="text" name="lesson_topic[${modIdx}][]" class="form-control mt-2" placeholder="Enter topic (optional)"></div>
                <div class="col-lg-12 mt-4"><label>Lesson Content *</label><textarea name="lesson_content[${modIdx}][]" class="form-control mt-2" rows="6" placeholder="Enter lesson content"></textarea></div>
            </div>
            <div class="buttons lesson-content-buttons d-flex flex-wrap mt-4">
                <button type="button" class="btn-add-video-in-lesson"><i class="fa fa-video"></i> Add Video</button>
                <button type="button" class="btn-add-image-in-lesson"><i class="fa fa-image"></i> Add Image</button>
                <button type="button" class="btn-add-activity-in-lesson"><i class="fa fa-circle-question"></i> Add Activity</button>
                <button type="button" class="btn-add-quiz-in-lesson"><i class="fa fa-pen-to-square"></i> Add Quiz</button>
                <button type="button" class="btn-add-flashcard-in-lesson"><i class="fa fa-clone"></i> Add Flashcard</button>
            </div>
            <div class="lesson-nested-container d-flex flex-column gap-3 mt-3">
                <div class="lesson-nested-empty text-content" style="margin-top:0.5rem;"><i class="fa fa-circle-question"></i><p>No content yet — add a Video, Image, Activity, Quiz, or Flashcard.</p></div>
            </div>`;

        const nestedContainer = el.querySelector(".lesson-nested-container");
        const emptyState = el.querySelector(".lesson-nested-empty");
        function hideEmpty() { emptyState.style.display = "none"; }
        function maybeShowEmpty() { if (nestedContainer.querySelectorAll(".card-body:not(.activity-question-item):not(.question-item)").length === 0) emptyState.style.display = "flex"; }
        function reNum(cls, labelClass, labelText) { nestedContainer.querySelectorAll("." + cls).forEach((item, i) => { item.querySelector("." + labelClass).textContent = labelText + " " + (i + 1); }); }

        el.querySelector(".btn-add-video-in-lesson").addEventListener("click", () => { hideEmpty(); nestedContainer.appendChild(buildVideo(modIdx, lesIdx, nestedContainer.querySelectorAll(".video-item").length + 1)); });
        el.querySelector(".btn-add-image-in-lesson").addEventListener("click", () => { hideEmpty(); nestedContainer.appendChild(buildImage(modIdx, lesIdx, nestedContainer.querySelectorAll(".image-item").length + 1)); });
        el.querySelector(".btn-add-activity-in-lesson").addEventListener("click", () => { hideEmpty(); nestedContainer.appendChild(buildActivity(modIdx, lesIdx, nestedContainer.querySelectorAll(".activity-item").length + 1)); });
        el.querySelector(".btn-add-quiz-in-lesson").addEventListener("click", () => { hideEmpty(); nestedContainer.appendChild(buildQuiz(modIdx, lesIdx, nestedContainer.querySelectorAll(".quiz-item").length + 1)); });
        el.querySelector(".btn-add-flashcard-in-lesson").addEventListener("click", () => { hideEmpty(); const fc = buildFlashcard(modIdx, lesIdx); nestedContainer.appendChild(fc); reNum("flashcard-item", "flashcard-label", "Flashcard"); });

        nestedContainer.addEventListener("click", (e) => {
            const removeBtn = e.target.closest(".remove-item");
            if (!removeBtn) return;
            const item = removeBtn.closest(".card-body");
            if (!item || item.classList.contains("lesson-item")) return;
            item.remove();
            reNum("video-item", "video-label", "Video");
            reNum("image-item", "image-label", "Image");
            reNum("activity-item", "activity-label", "Activity");
            reNum("quiz-item", "quiz-label", "Quiz");
            maybeShowEmpty();
        });
        return el;
    }

    function buildModule(modIdx) {
        const mod = document.createElement("div");
        mod.className = "card-body module-item";
        mod.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav"><div class="card-icon"><i class="fa fa-layer-group"></i></div><p class="module-label">Module ${modIdx + 1}</p></div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4"><label>Module Title *</label><input type="text" name="module_title[${modIdx}]" class="form-control mt-2" placeholder="Enter module title"></div>
                <div class="col-lg-12 mt-4"><label>Module Description</label><textarea name="module_content[${modIdx}]" class="form-control mt-2" rows="4" placeholder="Brief description of this module"></textarea></div>
            </div>
            <div class="buttons d-flex flex-wrap mt-4">
                <button type="button" class="btn-add-lesson-in-module"><i class="fa fa-file"></i> Add Lesson</button>
            </div>
            <div class="nested-lessons-container d-flex flex-column gap-3 mt-3">
                <div class="nested-lessons-empty text-content" style="margin-top:1rem;"><i class="fa fa-circle-question"></i><p>No lessons yet — click "Add Lesson" above.</p></div>
            </div>`;

        const lessonsContainer = mod.querySelector(".nested-lessons-container");
        const lessonsEmpty = mod.querySelector(".nested-lessons-empty");
        function maybeShowLessonsEmpty() { if (lessonsContainer.querySelectorAll(".lesson-item").length === 0) lessonsEmpty.style.display = "flex"; }

        mod.querySelector(".btn-add-lesson-in-module").addEventListener("click", () => {
            lessonsEmpty.style.display = "none";
            const lesIdx = lessonsContainer.querySelectorAll(".lesson-item").length;
            const lesson = buildLesson(modIdx, lesIdx);
            lessonsContainer.appendChild(lesson);
            lesson.querySelector(".card-body-header .remove-item").addEventListener("click", () => {
                lesson.remove();
                lessonsContainer.querySelectorAll(".lesson-item").forEach((item, i) => { item.querySelector(".lesson-label").textContent = "Lesson " + (i + 1); });
                maybeShowLessonsEmpty();
            });
        });

        mod.querySelector(".card-body-header .remove-item").addEventListener("click", () => {
            mod.remove();
            contentContainer.querySelectorAll(".module-item").forEach((item, i) => { item.querySelector(".module-label").textContent = "Module " + (i + 1); });
            checkEmpty();
        });
        return mod;
    }

    addModuleBtn.addEventListener("click", () => {
        const emptyEl = contentContainer.querySelector(".text-content");
        if (emptyEl) emptyEl.style.display = "none";
        const modIdx = contentContainer.querySelectorAll(".module-item").length;
        const mod = buildModule(modIdx);
        modCount++;
        contentContainer.appendChild(mod);
    });

    checkEmpty();


    // ════════════════════════════════════════════════════════════
    // ── CLASSWORK / ASSIGNMENT dynamic cards ─────────────────────
    // ════════════════════════════════════════════════════════════

    const cwContainer = document.getElementById("cwAssignmentContainer");
    const addCwBtn = document.getElementById("addAssignmentBtn");

    if (cwContainer && addCwBtn) {
        function reNumberAssignments() {
            cwContainer.querySelectorAll(".cw-assignment-card").forEach((card, i) => { card.querySelector(".cw-assignment-num").textContent = "Assignment " + (i + 1); });
            const emptyEl = document.getElementById("cwAssignmentEmpty");
            if (emptyEl) emptyEl.style.display = cwContainer.querySelectorAll(".cw-assignment-card").length === 0 ? "flex" : "none";
        }

        addCwBtn.addEventListener("click", () => {
            const emptyEl = document.getElementById("cwAssignmentEmpty");
            if (emptyEl) emptyEl.style.display = "none";
            const idx = cwContainer.querySelectorAll(".cw-assignment-card").length;
            const card = document.createElement("div");
            card.className = "cw-assignment-card cf-module-card";
            card.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:50%;background:#e6f9ee;display:flex;align-items:center;justify-content:center;"><i class="fa fa-clipboard-check" style="color:#00C950;font-size:15px;"></i></div>
                    <strong class="cw-assignment-num">Assignment ${idx + 1}</strong>
                </div>
                <button type="button" class="cw-remove-btn btn btn-sm"><i class="fa fa-times"></i></button>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-2"><label class="cw-label">Title *</label><input type="text" name="assignment_title[]" class="cw-input mt-2" placeholder="Enter Title" required></div>
                <div class="col-lg-12 mt-3"><label class="cw-label">Description</label><textarea name="assignment_description[]" class="cw-input cw-textarea mt-2" rows="3" placeholder="Enter Description"></textarea></div>
                <div class="col-lg-12 mt-3"><label class="cw-label">Task *</label><input type="text" name="assignment_task[]" class="cw-input mt-2" placeholder="e.g. Essay, Research, Seatwork"></div>
                <div class="col-lg-12 mt-3"><label class="cw-label">Instructions</label><textarea name="assignment_instructions[]" class="cw-input cw-textarea mt-2" rows="4" placeholder="Detailed instructions for students..."></textarea></div>
                <div class="col-lg-6 mt-3">
                    <label class="cw-label">Type</label>
                    <div class="cw-select-wrap">
                        <select name="assignment_type[]" class="cw-select mt-2"><option value="seatwork">Seatwork</option><option value="homework">Homework</option><option value="project">Project</option><option value="quiz">Quiz</option><option value="exam">Exam</option><option value="performance">Performance Task</option></select>
                        <i class="fa fa-chevron-down cw-select-icon"></i>
                    </div>
                </div>
                <div class="col-lg-6 mt-3"><label class="cw-label">Due Date</label><input type="date" name="assignment_due_date[]" class="cw-input cw-date mt-2"></div>
                <div class="col-lg-12 mt-3">
                    <label class="cw-label">Points</label>
                    <input type="number" name="assignment_points[]" class="cw-input mt-2" placeholder="e.g. 100" min="1" value="100">
                </div>
                <div class="col-lg-12 mt-3">
                    <label class="cw-label">Upload Materials</label>
                    <div class="cw-upload-box cw-upload-trigger">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        <p>Upload Materials</p>
                        <span>PDF, Powerpoint, Word Document (Max 50MB)</span>
                        <div style="margin-top:10px;">
                            <input type="file" name="assignment_file[]" class="cw-file-input" accept=".pdf,.ppt,.pptx,.doc,.docx" style="display:none;">
                            <label class="cw-choose-btn" onclick="event.stopPropagation()">Choose File</label>
                            <span class="cw-file-name-label" style="font-size:12px;color:#9ca3af;margin-left:8px;">No file chosen.</span>
                        </div>
                    </div>
                    <div class="cf-pdf-item cw-file-preview-item" style="display:none;margin-top:10px;">
                        <i class="fa fa-file-pdf"></i><span class="cw-file-preview-name"></span>
                        <button type="button" class="cf-pdf-remove cw-remove-file-btn"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            </div>`;

            const uploadBox = card.querySelector(".cw-upload-trigger");
            const fileInput = card.querySelector(".cw-file-input");
            const chooseLabel = card.querySelector(".cw-choose-btn");
            const fileNameLbl = card.querySelector(".cw-file-name-label");
            const previewItem = card.querySelector(".cw-file-preview-item");
            const previewName = card.querySelector(".cw-file-preview-name");
            const removeFile = card.querySelector(".cw-remove-file-btn");

            uploadBox.addEventListener("click", () => fileInput.click());
            chooseLabel.addEventListener("click", (e) => { e.stopPropagation(); fileInput.click(); });
            fileInput.addEventListener("change", () => { const file = fileInput.files[0]; if (!file) return; fileNameLbl.textContent = file.name; previewName.textContent = file.name; previewItem.style.display = "flex"; uploadBox.style.borderColor = "#00C950"; });
            removeFile.addEventListener("click", () => { fileInput.value = ""; fileNameLbl.textContent = "No file chosen."; previewItem.style.display = "none"; uploadBox.style.borderColor = ""; });
            card.querySelector(".cw-remove-btn").addEventListener("click", () => { card.remove(); reNumberAssignments(); });
            cwContainer.appendChild(card);
        });
    }


    // ════════════════════════════════════════════════════════════
    // ── ANNOUNCEMENT dynamic cards ────────────────────────────────
    // ════════════════════════════════════════════════════════════

    const annContainer = document.getElementById("announcementContainer");
    const addAnnBtn = document.getElementById("addAnnouncementBtn");

    if (annContainer && addAnnBtn) {

        function reNumberAnnouncements() {
            annContainer.querySelectorAll(".ann-card").forEach((card, i) => {
                card.querySelector(".ann-num").textContent = "Announcement " + (i + 1);
            });
            const emptyEl = document.getElementById("announcementEmpty");
            if (emptyEl) emptyEl.style.display = annContainer.querySelectorAll(".ann-card").length === 0 ? "flex" : "none";
        }

        addAnnBtn.addEventListener("click", () => {
            const emptyEl = document.getElementById("announcementEmpty");
            if (emptyEl) emptyEl.style.display = "none";

            const idx = annContainer.querySelectorAll(".ann-card").length;
            const card = document.createElement("div");
            card.className = "ann-card cf-module-card ann-card-accent";
            card.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-bullhorn" style="color:#f59e0b;font-size:15px;"></i>
                        </div>
                        <strong class="ann-num">Announcement ${idx + 1}</strong>
                    </div>
                    <button type="button" class="ann-remove-btn btn btn-sm"><i class="fa fa-times"></i></button>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 mt-2">
                        <label class="cw-label">Title *</label>
                        <input type="text" name="announcement_title[]" class="cw-input mt-2" placeholder="e.g. No class on Friday" required>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label class="cw-label">Message *</label>
                        <textarea name="announcement_message[]" class="cw-input cw-textarea mt-2" rows="4" placeholder="Write your announcement here..." required></textarea>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label class="cw-label">Priority</label>
                        <div class="cw-select-wrap">
                            <select name="announcement_priority[]" class="cw-select mt-2">
                                <option value="normal">🔵 Normal</option>
                                <option value="important">🟠 Important</option>
                                <option value="urgent">🔴 Urgent</option>
                            </select>
                            <i class="fa fa-chevron-down cw-select-icon"></i>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label class="cw-label">Post Date</label>
                        <input type="date" name="announcement_date[]" class="cw-input cw-date mt-2">
                    </div>
                    <div class="col-lg-12 mt-4">
                        <label class="cw-label">Attach File <span style="color:#9ca3af;font-size:12px;">(optional)</span></label>
                        <div class="ann-pdf-list"></div>
                        <button type="button" class="btn-cf-add-pdf ann-add-file-btn mt-2">
                            <i class="fa fa-plus"></i> Attach File
                        </button>
                        <input type="file" name="announcement_file[${idx}][]" class="ann-file-input" accept=".pdf,.ppt,.pptx,.doc,.docx,image/*" multiple style="display:none;">
                    </div>
                </div>`;

            const addFileBtn = card.querySelector(".ann-add-file-btn");
            const fileInput = card.querySelector(".ann-file-input");
            const pdfList = card.querySelector(".ann-pdf-list");

            addFileBtn.addEventListener("click", () => fileInput.click());
            fileInput.addEventListener("change", () => {
                Array.from(fileInput.files).forEach(file => {
                    const item = document.createElement("div");
                    item.className = "cf-pdf-item";
                    item.innerHTML = `<i class="fa fa-file-pdf"></i><span>${file.name}</span><button type="button" class="cf-pdf-remove"><i class="fa fa-times"></i></button>`;
                    item.querySelector(".cf-pdf-remove").addEventListener("click", () => item.remove());
                    pdfList.appendChild(item);
                });
            });

            card.querySelector(".ann-remove-btn").addEventListener("click", () => {
                card.remove();
                reNumberAnnouncements();
            });

            annContainer.appendChild(card);
        });
    }

});