document.addEventListener("DOMContentLoaded", function () {

    const contentContainer = document.getElementById("contentContainer");
    const addModuleBtn = document.getElementById("addModuleBtn");

    // ── helpers ───────────────────────────────────────────────────────────────

    function createRemoveButton() {
        return `<button type="button" class="remove-item btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>`;
    }

    function reNumberModules() {
        document.querySelectorAll(".module-item").forEach((mod, i) => {
            mod.querySelector(".module-label").textContent = "Module " + (i + 1);
        });
    }

    function reNumberLessonsInModule(moduleEl) {
        moduleEl.querySelectorAll(":scope > .nested-lessons-container > .lesson-item").forEach((item, i) => {
            item.querySelector(".lesson-label").textContent = "Lesson " + (i + 1);
        });
    }

    function reNumberInLesson(lessonEl, typeClass, labelClass, labelText) {
        lessonEl.querySelectorAll("." + typeClass).forEach((item, i) => {
            item.querySelector("." + labelClass).textContent = labelText + " " + (i + 1);
        });
    }

    function checkEmpty() {
        const noModules = document.querySelectorAll(".module-item").length === 0;
        document.querySelector(".text-content").style.display = noModules ? "flex" : "none";
    }

    // ── VIDEO builder ─────────────────────────────────────────────────────────

    function buildVideo() {
        const el = document.createElement("div");
        el.className = "card-body video-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-video"></i></div>
                    <p class="video-label">Video 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="video_title[]" class="form-control mt-2" placeholder="Enter video title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Video URL *</label>
                    <input type="url" name="video_url[]" class="form-control mt-2" placeholder="https://youtube.com/...">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Description</label>
                    <textarea name="video_description[]" class="form-control mt-2" rows="4" placeholder="Video description"></textarea>
                </div>
            </div>`;
        return el;
    }

    // ── IMAGE builder ─────────────────────────────────────────────────────────

    function buildImage() {
        const el = document.createElement("div");
        el.className = "card-body image-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-image"></i></div>
                    <p class="image-label">Image 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="image_title[]" class="form-control mt-2" placeholder="Enter image title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Image URL *</label>
                    <input type="url" name="image_url[]" class="form-control mt-2" placeholder="https://example.com/image.jpg">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Description</label>
                    <textarea name="image_description[]" class="form-control mt-2" rows="4" placeholder="Image description"></textarea>
                </div>
            </div>`;
        return el;
    }

    // ── ACTIVITY builder ──────────────────────────────────────────────────────

    function buildActivity() {
        const el = document.createElement("div");
        el.className = "card-body activity-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-circle-question"></i></div>
                    <p class="activity-label">Activity 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Activity Title *</label>
                    <input type="text" name="activity_title[]" class="form-control mt-2" placeholder="Enter activity title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Instructions</label>
                    <textarea name="activity_instructions[]" class="form-control mt-2" rows="3" placeholder="Instructions for students"></textarea>
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Total Points</label>
                    <input type="number" name="activity_points[]" class="form-control mt-2" placeholder="e.g. 10" min="1">
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Time Limit (minutes)</label>
                    <input type="number" name="activity_time[]" class="form-control mt-2" placeholder="e.g. 20" min="1">
                </div>
            </div>

            <div class="activity-questions-container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="mb-0" style="font-weight:600;">Questions</label>
                    <button type="button" class="btn-add-activity-question">
                        <i class="fa fa-plus"></i> Add Question
                    </button>
                </div>
                <div class="activity-questions-list d-flex flex-column gap-3">
                    <div class="activity-questions-empty text-content" style="margin-top:0;">
                        <i class="fa fa-circle-question"></i>
                        <p>No questions yet — click "Add Question" to start.</p>
                    </div>
                </div>
            </div>`;

        const qList = el.querySelector(".activity-questions-list");
        const qEmpty = el.querySelector(".activity-questions-empty");
        const addBtn = el.querySelector(".btn-add-activity-question");

        function hideQEmpty() { qEmpty.style.display = "none"; }
        function maybeShowQEmpty() {
            if (qList.querySelectorAll(".activity-question-item").length === 0)
                qEmpty.style.display = "flex";
        }
        function reNumberAQ() {
            qList.querySelectorAll(".activity-question-item").forEach((q, i) => {
                q.querySelector(".activity-question-num").textContent = "Question " + (i + 1);
            });
        }

        addBtn.addEventListener("click", () => {
            hideQEmpty();
            const q = buildActivityQuestion();
            qList.appendChild(q);
            reNumberAQ();
            q.querySelector(".remove-activity-question").addEventListener("click", () => {
                q.remove(); reNumberAQ(); maybeShowQEmpty();
            });
            wireActivityQuestionType(q);
        });

        return el;
    }

    function buildActivityQuestion() {
        const q = document.createElement("div");
        q.className = "activity-question-item card-body";
        q.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-pencil"></i></div>
                    <p class="activity-question-num mb-0" style="font-size:15px;font-weight:600;">Question 1</p>
                </div>
                <button type="button" class="remove-activity-question btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-3">
                    <label>Question Type *</label>
                    <select name="activity_question_type[]" class="form-control mt-2 activity-question-type-select">
                        <option value="essay">Essay / Open-ended</option>
                        <option value="multiple_choice">Multiple Choice</option>
                    </select>
                </div>
                <div class="col-lg-12 mt-3">
                    <label>Question *</label>
                    <input type="text" name="activity_question_text[]" class="form-control mt-2" placeholder="Enter your question">
                </div>
                <div class="activity-essay-fields col-lg-12 mt-3">
                    <label>Sample / Model Answer <span style="color:#aaa;font-size:12px;">(optional)</span></label>
                    <textarea name="activity_essay_answer[]" class="form-control mt-2" rows="4" placeholder="Sample answer for teacher reference..."></textarea>
                </div>
                <div class="activity-mc-fields" style="display:none;width:100%;">
                    <div class="col-lg-12 mt-3"><label>Choice A *</label><input type="text" name="activity_choice_a[]" class="form-control mt-2" placeholder="Choice A"></div>
                    <div class="col-lg-12 mt-3"><label>Choice B *</label><input type="text" name="activity_choice_b[]" class="form-control mt-2" placeholder="Choice B"></div>
                    <div class="col-lg-12 mt-3"><label>Choice C</label><input type="text" name="activity_choice_c[]" class="form-control mt-2" placeholder="Choice C (optional)"></div>
                    <div class="col-lg-12 mt-3"><label>Choice D</label><input type="text" name="activity_choice_d[]" class="form-control mt-2" placeholder="Choice D (optional)"></div>
                    <div class="col-lg-12 mt-3">
                        <label>Correct Answer *</label>
                        <select name="activity_correct_answer[]" class="form-control mt-2">
                            <option value="">-- Select correct answer --</option>
                            <option value="A">A</option><option value="B">B</option>
                            <option value="C">C</option><option value="D">D</option>
                        </select>
                    </div>
                </div>
            </div>`;
        return q;
    }

    function wireActivityQuestionType(q) {
        const sel = q.querySelector(".activity-question-type-select");
        const essay = q.querySelector(".activity-essay-fields");
        const mc = q.querySelector(".activity-mc-fields");
        sel.addEventListener("change", () => {
            essay.style.display = sel.value === "multiple_choice" ? "none" : "block";
            mc.style.display = sel.value === "multiple_choice" ? "block" : "none";
        });
    }

    // ── QUIZ builder ──────────────────────────────────────────────────────────

    function buildQuiz() {
        const el = document.createElement("div");
        el.className = "card-body quiz-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-pen-to-square"></i></div>
                    <p class="quiz-label">Quiz 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Quiz Title *</label>
                    <input type="text" name="quiz_title[]" class="form-control mt-2" placeholder="Enter quiz title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Instructions</label>
                    <textarea name="quiz_instructions[]" class="form-control mt-2" rows="3" placeholder="Instructions for students"></textarea>
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Time Limit (minutes)</label>
                    <input type="number" name="quiz_time_limit[]" class="form-control mt-2" placeholder="e.g. 30" min="1">
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Passing Score (%)</label>
                    <input type="number" name="quiz_passing_score[]" class="form-control mt-2" placeholder="e.g. 75" min="1" max="100">
                </div>
            </div>
            <div class="quiz-questions-container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="mb-0" style="font-weight:600;">Questions</label>
                    <button type="button" class="btn-add-question">
                        <i class="fa fa-plus"></i> Add Question
                    </button>
                </div>
                <div class="questions-list d-flex flex-column gap-3">
                    <div class="quiz-questions-empty text-content" style="margin-top:0;">
                        <i class="fa fa-circle-question"></i>
                        <p>No questions yet — click "Add Question" to start.</p>
                    </div>
                </div>
            </div>`;

        const qList = el.querySelector(".questions-list");
        const qEmpty = el.querySelector(".quiz-questions-empty");
        const addBtn = el.querySelector(".btn-add-question");

        function hideQEmpty() { qEmpty.style.display = "none"; }
        function maybeShowQEmpty() {
            if (qList.querySelectorAll(".question-item").length === 0)
                qEmpty.style.display = "flex";
        }
        function reNumberQQ() {
            qList.querySelectorAll(".question-item").forEach((q, i) => {
                q.querySelector(".question-num").textContent = "Question " + (i + 1);
            });
        }

        addBtn.addEventListener("click", () => {
            hideQEmpty();
            const q = buildQuizQuestion();
            qList.appendChild(q);
            reNumberQQ();
            q.querySelector(".remove-question").addEventListener("click", () => {
                q.remove(); reNumberQQ(); maybeShowQEmpty();
            });
        });

        return el;
    }

    function buildQuizQuestion() {
        const q = document.createElement("div");
        q.className = "question-item card-body";
        q.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-list-check"></i></div>
                    <p class="question-num mb-0" style="font-size:15px;font-weight:600;">Question 1</p>
                </div>
                <button type="button" class="remove-question btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-3">
                    <label>Question *</label>
                    <input type="text" name="question_text[]" class="form-control mt-2" placeholder="Enter your question">
                </div>
                <div class="col-lg-6 mt-3"><label>Choice A *</label><input type="text" name="choice_a[]" class="form-control mt-2" placeholder="Choice A"></div>
                <div class="col-lg-6 mt-3"><label>Choice B *</label><input type="text" name="choice_b[]" class="form-control mt-2" placeholder="Choice B"></div>
                <div class="col-lg-6 mt-3"><label>Choice C</label><input type="text" name="choice_c[]" class="form-control mt-2" placeholder="Choice C (optional)"></div>
                <div class="col-lg-6 mt-3"><label>Choice D</label><input type="text" name="choice_d[]" class="form-control mt-2" placeholder="Choice D (optional)"></div>
                <div class="col-lg-12 mt-3">
                    <label>Correct Answer *</label>
                    <select name="correct_answer[]" class="form-control mt-2">
                        <option value="">-- Select correct answer --</option>
                        <option value="A">A</option><option value="B">B</option>
                        <option value="C">C</option><option value="D">D</option>
                    </select>
                </div>
            </div>`;
        return q;
    }

    // ── LESSON builder ────────────────────────────────────────────────────────
    // Lesson contains: Title, Content, + its own Videos/Images/Activities/Quiz

    function buildLesson() {
        const el = document.createElement("div");
        el.className = "card-body lesson-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-file"></i></div>
                    <p class="lesson-label">Lesson 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>

            <!-- Lesson text content -->
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Lesson Title *</label>
                    <input type="text" name="lesson_title[]" class="form-control mt-2" placeholder="Enter lesson title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Lesson Content *</label>
                    <textarea name="lesson_content[]" class="form-control mt-2" rows="6" placeholder="Enter lesson content"></textarea>
                </div>
            </div>

            <!-- Buttons to add content inside this lesson -->
            <div class="buttons lesson-content-buttons d-flex flex-wrap mt-4">
                <button type="button" class="btn-add-video-in-lesson">
                    <i class="fa fa-video"></i> Add Video
                </button>
                <button type="button" class="btn-add-image-in-lesson">
                    <i class="fa fa-image"></i> Add Image
                </button>
                <button type="button" class="btn-add-activity-in-lesson">
                    <i class="fa fa-circle-question"></i> Add Activity
                </button>
                <button type="button" class="btn-add-quiz-in-lesson">
                    <i class="fa fa-pen-to-square"></i> Add Quiz
                </button>
            </div>

            <!-- Lesson nested content (videos, images, activities, quizzes) -->
            <div class="lesson-nested-container d-flex flex-column gap-3 mt-3">
                <div class="lesson-nested-empty text-content" style="margin-top:0.5rem;">
                    <i class="fa fa-circle-question"></i>
                    <p>No content yet — add a Video, Image, Activity, or Quiz to this lesson.</p>
                </div>
            </div>`;

        const nestedContainer = el.querySelector(".lesson-nested-container");
        const emptyState = el.querySelector(".lesson-nested-empty");

        function hideEmpty() { emptyState.style.display = "none"; }
        function maybeShowEmpty() {
            if (nestedContainer.querySelectorAll(".card-body").length === 0)
                emptyState.style.display = "flex";
        }

        // wire the 4 add buttons inside this lesson
        el.querySelector(".btn-add-video-in-lesson").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildVideo());
            reNumberInLesson(el, "video-item", "video-label", "Video");
        });

        el.querySelector(".btn-add-image-in-lesson").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildImage());
            reNumberInLesson(el, "image-item", "image-label", "Image");
        });

        el.querySelector(".btn-add-activity-in-lesson").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildActivity());
            reNumberInLesson(el, "activity-item", "activity-label", "Activity");
        });

        el.querySelector(".btn-add-quiz-in-lesson").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildQuiz());
            reNumberInLesson(el, "quiz-item", "quiz-label", "Quiz");
        });

        // remove nested items inside the lesson
        nestedContainer.addEventListener("click", (e) => {
            const removeBtn = e.target.closest(".remove-item");
            if (!removeBtn) return;
            const item = removeBtn.closest(".card-body");
            if (!item || item.classList.contains("lesson-item")) return;
            item.remove();
            reNumberInLesson(el, "video-item", "video-label", "Video");
            reNumberInLesson(el, "image-item", "image-label", "Image");
            reNumberInLesson(el, "activity-item", "activity-label", "Activity");
            reNumberInLesson(el, "quiz-item", "quiz-label", "Quiz");
            maybeShowEmpty();
        });

        return el;
    }

    // ── MODULE builder ────────────────────────────────────────────────────────
    // Module only has: Title, Description, and an "Add Lesson" button

    function buildModule() {
        const mod = document.createElement("div");
        mod.className = "card-body module-item";

        mod.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-layer-group"></i></div>
                    <p class="module-label">Module</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Module Title *</label>
                    <input type="text" name="module_title[]" class="form-control mt-2" placeholder="Enter module title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Module Description</label>
                    <textarea name="module_content[]" class="form-control mt-2" rows="4" placeholder="Brief description of this module"></textarea>
                </div>
            </div>

            <!-- Module only adds Lessons -->
            <div class="buttons d-flex flex-wrap mt-4">
                <button type="button" class="btn-add-lesson-in-module">
                    <i class="fa fa-file"></i> Add Lesson
                </button>
            </div>

            <!-- Lessons go here -->
            <div class="nested-lessons-container d-flex flex-column gap-3 mt-3">
                <div class="nested-lessons-empty text-content" style="margin-top:1rem;">
                    <i class="fa fa-circle-question"></i>
                    <p>No lessons yet — click "Add Lesson" above.</p>
                </div>
            </div>`;

        const lessonsContainer = mod.querySelector(".nested-lessons-container");
        const lessonsEmpty = mod.querySelector(".nested-lessons-empty");

        function hideLessonsEmpty() { lessonsEmpty.style.display = "none"; }
        function maybeShowLessonsEmpty() {
            if (lessonsContainer.querySelectorAll(".lesson-item").length === 0)
                lessonsEmpty.style.display = "flex";
        }

        mod.querySelector(".btn-add-lesson-in-module").addEventListener("click", () => {
            hideLessonsEmpty();
            const lesson = buildLesson();
            lessonsContainer.appendChild(lesson);
            reNumberLessonsInModule(mod);

            // wire remove for this lesson
            lesson.querySelector(".card-body-header .remove-item").addEventListener("click", () => {
                lesson.remove();
                reNumberLessonsInModule(mod);
                maybeShowLessonsEmpty();
            });
        });

        // remove the whole module
        mod.querySelector(".card-body-header .remove-item").addEventListener("click", () => {
            mod.remove();
            reNumberModules();
            checkEmpty();
        });

        return mod;
    }

    // ── top-level Add Module ──────────────────────────────────────────────────

    addModuleBtn.addEventListener("click", () => {
        document.querySelector(".text-content").style.display = "none";
        const mod = buildModule();
        contentContainer.appendChild(mod);
        reNumberModules();
    });

    // ── initial empty state ───────────────────────────────────────────────────
    checkEmpty();
});